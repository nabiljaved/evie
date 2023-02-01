<?php
/**
 * Mega Menu
 *
 * @package    Evie_XT
 * @subpackage Modules/Mega_Menu
 * @version    1.0.0
 */

/**
 * Adds a Mega Menu class to a menu item element.
 *
 * @param string[] $classes Array of the CSS classes that are applied to the menu item's `<li>` element.
 * @param WP_Post  $item    The current menu item.
 * @param stdClass $args    An object of wp_nav_menu() arguments.
 * @param int      $depth   Depth of menu item. Used for padding.
 */
function evie_mega_menu_class( $classes, $item, $args, $depth ) {
	if ( 0 === $depth && intval( $args->depth ) !== 1 && is_object( $item ) && isset( $item->ID ) ) {
		$mega_menu = get_post_meta( $item->ID, '_evie_mega_menu', true );
		if ( ! empty( $mega_menu ) ) {
			if ( 'post' === $mega_menu ) {
				$classes[] = 'evie-mega-menu evie-mm-has-posts';
			} else {
				$classes[] = 'evie-mega-menu';
			}
			if ( '' === get_theme_mod( 'nav_type', '' ) ) {
				$columns = get_post_meta( $item->ID, '_evie_mega_menu_columns', true );
				if ( ! empty( $columns ) ) {
					$classes[] = 'has-' . absint( $columns ) . '-columns';
				}
			}
		}
	}
	return $classes;
}

add_filter( 'nav_menu_css_class', 'evie_mega_menu_class', 10, 4 );

/**
 * Prepends a menu thumbnail to the menu title.
 *
 * @param string  $title The menu item's title.
 * @param WP_Post $item  The current menu item.
 * @return string The menu title with menu thumbnail.
 */
function evie_mega_menu_add_menu_thumbnail( $title, $item ) {

	if ( is_object( $item ) && isset( $item->ID ) ) {

		if ( 'post_type' === $item->type && ! empty( $item->object_id ) ) {
			$show_thumbnail = get_post_meta( $item->ID, '_port10_show_thumbnail', true );

			if ( ! empty( $show_thumbnail ) ) {
				$title = get_the_post_thumbnail( $item->object_id, 'thumbnail' ) . $title;
			}
		}
	}
	return $title;
}

/**
 * Adds recent posts to the menu item.
 *
 * @param string   $item_output The menu item's HTML output.
 * @param WP_Post  $item        Menu item data object.
 * @param int      $depth       Depth of menu item. Used for padding.
 * @param stdClass $args        An object of wp_nav_menu() arguments.
 * @return string The menu item's HTML output.
 */
function evie_mega_menu_add_recent_posts( $item_output, $item, $depth, $args ) {
	if ( 0 === $depth && is_object( $item ) && ! empty( $item->ID ) ) {
		$mega_menu = get_post_meta( $item->ID, '_evie_mega_menu', true );
		if ( ! empty( $mega_menu ) && 'post' === $mega_menu ) {
			$item_output .= '<div class="evie-mm-posts">';

			$terms = evie_mega_menu_get_menu_terms( $args->menu, $item->ID );

			if ( ! empty( $terms ) ) {
				foreach ( $terms as $taxonomy => $term ) {
					foreach ( $term as $id ) {
						$item_output .= sprintf(
							'<div class="evie-mm-content" data-taxonomy="%s" data-term="%s"></div>',
							esc_attr( $taxonomy ),
							esc_attr( $id )
						);
					}
				}
			}

			$item_output .= '</div>';
		}
	}
	return $item_output;
}

add_filter( 'walker_nav_menu_start_el', 'evie_mega_menu_add_recent_posts', 10, 4 );

/**
 * Retrieves sub menu terms for given parent menu item.
 *
 * @param WP_Term $menu      The menu object.
 * @param int     $parent_id The ID of the parent menu item.
 * @return array Array of the sub menu items.
 */
function evie_mega_menu_get_menu_terms( $menu, $parent_id ) {
	$terms = array();
	if ( is_object( $menu ) && isset( $menu->term_id ) ) {

		$args = array(
			'post_type'  => 'nav_menu_item',
			'meta_key'   => '_menu_item_menu_item_parent', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
			'meta_value' => $parent_id, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
			'tax_query'  => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
				array(
					'taxonomy' => 'nav_menu',
					'field'    => 'term_id',
					'terms'    => $menu->term_id,
				),
			),
			'order'      => 'ASC',
			'orderby'    => 'menu_order',
			'nopaging'   => true,
		);

		$items = get_posts( $args );

		foreach ( $items as $item ) {

			$object_id = get_post_meta( $item->ID, '_menu_item_object_id', true );
			$object    = get_post_meta( $item->ID, '_menu_item_object', true );
			$type      = get_post_meta( $item->ID, '_menu_item_type', true );

			if ( 'taxonomy' === $type ) {
				$terms[ $object ][] = $object_id;
			}
		}
	}

	return $terms;
}

/**
 * Registers the Featured Content REST API endpoint.
 */
function evie_mega_menu_register_api_routes() {

	register_rest_route(
		'evie/v1',
		'/mega-menu',
		array(
			'methods'             => WP_REST_Server::READABLE,
			'callback'            => 'evie_mega_menu_get_posts',
			'permission_callback' => '__return_true',
		)
	);
}

add_action( 'rest_api_init', 'evie_mega_menu_register_api_routes' );

/**
 * Retrieves a collection of posts.
 *
 * @access public
 *
 * @param WP_REST_Request $request Full details about the request.
 * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
 */
function evie_mega_menu_get_posts( $request ) {

	$rows = 1;

	if ( 'full' === get_theme_mod( 'nav_type', '' ) ) {
		$rows = 2;
	}

	/**
	 * Filters the number of rows for the posts to show in the Mega Menu.
	 *
	 * @param int $rows The number of rows for the posts.
	 */
	$rows = apply_filters( 'evie_mega_menu_posts_rows', $rows );

	$columns = 4;

	if ( isset( $request['columns'] ) && ! empty( $request['columns'] ) ) {
		$columns = absint( $request['columns'] );
	}

	$per_page = absint( $rows * $columns );

	$args = array(
		'post_type'   => 'any',
		'tax_query'   => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
			array(
				'taxonomy' => isset( $request['taxonomy'] ) ? $request['taxonomy'] : 'category',
				'field'    => 'term_id',
				'terms'    => isset( $request['term'] ) ? absint( $request['term'] ) : 0,
			),
		),
		'numberposts' => $per_page,
	);

	/**
	 * Filters the Mega Menu posts query arguments.
	 *
	 * @param array           $args    An array of posts query arguments.
	 * @param WP_REST_Request $request Full details about the request.
	 */
	$args = apply_filters( 'evie_mega_menu_posts_args', $args, $request );

	ob_start();

	evie_get_template( plugin_dir_path( __FILE__ ) . 'templates/mega-menu-posts', '', $args );

	$content = ob_get_clean();
	// Response data array.
	$data = array(
		'rendered' => $content,
	);

	$response = rest_ensure_response( $data );

	return $response;

}

/**
 * Enqueues the scripts and stylesheets.
 */
function evie_mega_menu_enqueue_scripts() {

	wp_enqueue_style( 'evie-mega-menu', plugins_url( 'css/style.css', __FILE__ ), array( 'evie' ), EVIE_XT_VERSION );
	wp_style_add_data( 'evie-mega-menu', 'rtl', 'replace' );

	wp_enqueue_script( 'evie-mega-menu', plugins_url( 'js/index.js', __FILE__ ), array( 'flextension-api', 'evie' ), EVIE_XT_VERSION, true );

}

add_action( 'wp_enqueue_scripts', 'evie_mega_menu_enqueue_scripts' );
