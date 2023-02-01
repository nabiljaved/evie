<?php
/**
 * Single Page
 *
 * @package    Evie_XT
 * @subpackage Modules/Single_Page
 * @version    1.0.0
 */

/**
 * Returns the compatible post types.
 *
 * @return string[] An array of the compatible post types.
 */
function evie_single_page_post_types() {
	$post_types = array( 'post', 'page', 'project' );

	/**
	 * Filters the array of compatible post types.
	 *
	 * @param string[] $post_types Array of compatible post types.
	 */
	return apply_filters( 'evie_single_page_post_types', $post_types );
}

/**
 * Returns the settings values of the Single Page module.
 *
 * @return array An array object of the settings.
 */
function evie_single_page_settings() {
	return wp_parse_args(
		get_option( 'evie_single_page', array() ),
		array(
			'exclude_urls'      => array(),
			'exclude_selectors' => array(),
		)
	);
}

/**
 * Returns the excluding URLs and selectors.
 *
 * @return string[] An array list of the excluding URLs and selectors.
 */
function evie_single_page_get_excluding_list() {

	$settings = evie_single_page_settings();

	$urls      = empty( $settings['exclude_urls'] ) ? array() : $settings['exclude_urls'];
	$selectors = empty( $settings['exclude_selectors'] ) ? array() : $settings['exclude_selectors'];

	$post_types = get_post_types(
		array(
			'_builtin'    => false,
			'has_archive' => true,
		)
	);

	if ( ! empty( $post_types ) ) {
		$supports = evie_single_page_post_types();
		foreach ( $post_types as $post_type ) {
			if ( ! in_array( $post_type, $supports, true ) ) {
				$path = str_replace( home_url(), '', get_post_type_archive_link( $post_type ) );
				if ( ! empty( $path ) ) {
					$urls[] = $path;
				}
			}
		}
	}

	$excluding_list = array(
		'links'     => $urls,
		'selectors' => $selectors,
	);

	/**
	 * Filters the list of excluded links and selectors.
	 *
	 * @param array $excluding_list An array list of the excluded links and selectors.
	 */
	return apply_filters( 'evie_single_page_excluding_list', $excluding_list );
}

/**
 * Returns the content for the page.
 *
 * @param string $slug The template slug to display.
 * @param string $name The name of the specialised template.
 * @return string The content for the page.
 */
function evie_single_page_get_template_part( $slug = 'single', $name = '' ) {

	if ( empty( $name ) ) {
		$name = get_post_type();
	}

	ob_start();
	get_template_part( 'template-parts/' . $slug, $name );
	if ( 'single' === $slug ) {
		echo '<div id="content-footer">';
		if ( has_action( 'wp_footer', 'evie_cursor' ) ) {
			// Remove custom cursor element from the footer content.
			remove_action( 'wp_footer', 'evie_cursor' );
		}
		wp_enqueue_script( 'comment-reply' );
		// Since WP 5.8, WordPress Editor generates custom block styles in wp_footer so we need to fire it get those styles.
		do_action( 'wp_footer' ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- Invoking wp_footer hook for custom block styles.
		echo '</div>';
	}
	$content = ob_get_clean();

	/**
	 * Filters the page content.
	 *
	 * @param string $content The content for the page.
	 * @param string $slug The template slug to display.
	 * @param string $name The name of the specialised template.
	 */
	return apply_filters( 'evie_single_page_get_template_part', $content, $slug, $name );
}

/**
 * Returns the content for the archive page.
 *
 * @param string $post_type The post type.
 * @return string The content for the page.
 */
function evie_single_page_get_archive( $post_type = '' ) {
	return evie_single_page_get_template_part( 'archive', $post_type );
}

/**
 * Registers custom rest routes for Single Page Application.
 */
function evie_single_page_register_rest_routes() {
	$page_renderer_controller = new Evie_REST_Page_Renderer_Controller();
	$page_renderer_controller->register_routes();
}

add_action( 'rest_api_init', 'evie_single_page_register_rest_routes' );

/**
 * Appends settings to the current plugin settings.
 *
 * @param array $settings The current settings of the plugin.
 * @return array An array list of the plugin settings.
 */
function evie_single_page_add_settings( $settings = array() ) {
	$excluding_list = evie_single_page_get_excluding_list();

	if ( ! empty( $excluding_list ) ) {
		$settings['singlePage']['exclude'] = $excluding_list;
	}

	return $settings;
}

add_filter( 'evie_settings', 'evie_single_page_add_settings' );

/**
 * Gets categories dropdown parameters for the Categories widget.
 *
 * @global WP_Rewrite $wp_rewrite
 *
 * @param array $args An array of Categories widget drop-down arguments.
 * @return array An array of Categories widget drop-down arguments.
 */
function evie_single_page_categories_widget_dropdown_args( $args = array() ) {
	global $wp_rewrite;

	if ( $wp_rewrite->using_permalinks() ) {
		$args['value_field'] = 'slug';
	}

	return $args;
}

add_filter( 'widget_categories_dropdown_args', 'evie_single_page_categories_widget_dropdown_args' );

/**
 * Sets default Global Assets setting to true if the Single Page is enabled.
 *
 * @param bool $global_assets The value for Global Assets setting.
 * @return bool Whether the Global Assets setting is enabled.
 */
function evie_single_page_wpforms_global_assets( $global_assets = true ) {
	$global_assets = true;
	return $global_assets;
}

add_filter( 'wpforms_global_assets', 'evie_single_page_wpforms_global_assets', 50 );

/**
 * Returns whether the Single Page is allowed.
 *
 * @return bool Whether the Single Page is allowed.
 */
function evie_single_page_is_active() {
	if ( is_admin() || is_preview() || is_customize_preview() || is_feed() ) {
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
 * Enqueues the scripts and stylesheets.
 */
function evie_single_page_enqueue_scripts() {

	if ( evie_single_page_is_active() ) {

		wp_enqueue_style( 'evie-single-page', plugins_url( 'css/style.css', __FILE__ ), array(), EVIE_XT_VERSION );
		wp_style_add_data( 'evie-single-page', 'rtl', 'replace' );

		wp_enqueue_script( 'evie-single-page', plugins_url( 'js/index.js', __FILE__ ), array( 'evie-ajax-pagination' ), EVIE_XT_VERSION, true );
	}

}

add_action( 'wp_enqueue_scripts', 'evie_single_page_enqueue_scripts' );
