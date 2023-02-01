<?php
/**
 * Live Search
 *
 * @package    Flextension
 * @subpackage Modules/Live_Search
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Returns the settings values of the Live Search module.
 *
 * @return array An array object of the settings.
 */
function flextension_live_search_settings() {

	$settings = get_option( 'flext_live_search', array() );

	if ( empty( $settings ) ) {
		$settings = array(
			'post_types'     => array( 'post' ),
			'suggestions'    => 5,
			'show_thumbnail' => true,
			'show_date'      => true,
			'show_author'    => true,
		);
	} else {
		$settings = wp_parse_args(
			$settings,
			array(
				'post_types'     => array( 'post' ),
				'suggestions'    => 5,
				'show_thumbnail' => false,
				'show_date'      => false,
				'show_author'    => false,
			)
		);
	}

	return $settings;
}

/**
 * Displays the Live Search form.
 */
function flextension_live_search() {
	flextension_get_template( plugin_dir_path( __FILE__ ) . 'templates/live-search' );
}

/**
 * Prints out the search results response.
 */
function flextension_live_search_get_search_results() {

	check_ajax_referer( 'flextension_ajax', 'ajaxNonce' );

	$data = array();

	$keyword = isset( $_GET['keyword'] ) ? sanitize_text_field( wp_unslash( $_GET['keyword'] ) ) : '';

	// Do the search if the keyword is not empty.
	if ( ! empty( $keyword ) ) {

		$post_types = isset( $_GET['postTypes'] ) ? explode( ',', sanitize_text_field( wp_unslash( $_GET['postTypes'] ) ) ) : array();

		if ( empty( $post_types ) ) {
			$post_types = flextension_live_search_get_post_types();
		}

		$results = array();

		foreach ( $post_types as $post_type ) {
			$posts = flextension_live_search_get_posts( $keyword, $post_type['name'] );
			if ( ! empty( $posts ) ) {
				$results[] = array(
					'title' => $post_type['label'],
					'name'  => $post_type['name'],
					'items' => $posts,
				);
			}
		}

		if ( ! empty( $results ) ) {
			$data['results']  = $results;
			$data['moreLink'] = '<a href="' . esc_attr( get_search_link( $keyword ) ) . '">' . sprintf(
				/* translators: %s: keyword */
				esc_html__( 'See more results for “%s”', 'flextension' ),
				esc_html( $keyword )
			)

			. '</a>';
		} else {
			$data['message'] = esc_html__( 'Sorry, but nothing matched your search terms.', 'flextension' );
		}
	}

	// Print the results.
	wp_send_json( $data );
}

add_action( 'wp_ajax_flextension_live_search', 'flextension_live_search_get_search_results' );

add_action( 'wp_ajax_nopriv_flextension_live_search', 'flextension_live_search_get_search_results' );

/**
 * Returns the post types to search.
 *
 * @return array A list of post types to search.
 */
function flextension_live_search_get_post_types() {

	$settings = flextension_live_search_settings();

	$post_types = array();

	$post_type_objects = get_post_types( array( 'exclude_from_search' => false ), 'objects' );

	foreach ( $post_type_objects as $name => $post_type ) {
		if ( in_array( $name, $settings['post_types'], true ) ) {
			$post_types[] = array(
				'name'  => $post_type->name,
				'label' => $post_type->label,
			);
		}
	}

	return $post_types;
}

/**
 * Finds posts by using a keyword.
 *
 * @param string $keyword   A keyword to search.
 * @param string $post_type Post type.
 * @return array A list of search results.
 */
function flextension_live_search_get_posts( $keyword, $post_type = 'post' ) {

	$settings = flextension_live_search_settings();

	$posts = array();

	$search_results = get_posts(
		array(
			'post_type'   => $post_type,
			's'           => $keyword,
			'numberposts' => intval( $settings['suggestions'] ),
		)
	);

	if ( ! empty( $search_results ) ) {

		foreach ( $search_results as $item ) {

			$post_object        = new stdclass();
			$post_object->ID    = $item->ID;
			$post_object->title = get_the_title( $item->ID );

			if ( $settings['show_thumbnail'] ) {
				$post_object->thumbnail = wp_get_attachment_image_url( get_post_thumbnail_id( $item->ID ) );
			}

			$post_object->post_link = esc_url( get_permalink( $item->ID ) );

			if ( $settings['show_author'] ) {
				$post_object->author = get_the_author_meta( 'display_name', $item->post_author );
			}

			if ( 'page' !== $item->post_type && $settings['show_date'] ) {
				$post_object->date = get_the_date( '', $item->ID );
			}

			$posts[] = $post_object;
		}
	}

	/**
	 * Filters the search results.
	 *
	 * @param array  $posts     An array list of the search results.
	 * @param string $post_type Post type.
	 */
	return apply_filters( 'flextension_live_search_results', $posts, $post_type );
}

/**
 * Returns whether the module is allowed.
 *
 * @return bool Whether the Single Page is allowed.
 */
function flextension_live_search_is_active() {

	if ( is_admin() ) {
		return false;
	}

	// Check feed.
	if ( is_feed() ) {
		return false;
	}

	// Check printpage.
	if ( get_query_var( 'print' ) || get_query_var( 'printpage' ) ) {
		return false;
	}

	return true;
}

/**
 * Enqueues the scripts and stylesheets for the module.
 */
function flextension_live_search_enqueue_scripts() {
	if ( flextension_live_search_is_active() ) {
		wp_enqueue_style( 'flextension-live-search', plugins_url( 'css/style.css', __FILE__ ), array( 'flextension' ), flextension_get_setting( 'version' ) );
		wp_style_add_data( 'flextension-live-search', 'rtl', 'replace' );

		wp_enqueue_script( 'flextension-live-search', plugins_url( 'js/index.js', __FILE__ ), array( 'flextension' ), flextension_get_setting( 'version' ), true );
	}
}

add_action( 'wp_enqueue_scripts', 'flextension_live_search_enqueue_scripts' );
