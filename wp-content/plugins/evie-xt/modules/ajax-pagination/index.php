<?php
/**
 * AJAX Pagination Module
 *
 * Adds infinite scrolling support and AJAX Pagination to the blog posts,
 * pulling the next set of posts automatically into view when the reader approaches the bottom of the page.
 *
 * @package    Evie_XT
 * @subpackage Modules/AJAX_Pagination
 * @version    1.0.0
 */

/**
 * Registers the AJAX Pagination module.
 */
flextension_register_module(
	__FILE__,
	array(
		'title'         => esc_html__( 'AJAX Pagination', 'evie-xt' ),
		'description'   => esc_html__( 'Adds infinite scrolling support and AJAX Pagination to the blog posts, pulling the next set of posts automatically into view when the reader approaches the bottom of the posts list.', 'evie-xt' ),
		'category'      => esc_html__( 'Advanced', 'evie-xt' ),
		'dependencies'  => array( 'api', 'router' ),
		'load_callback' => 'evie_ajax_pagination_module_load',
	)
);

/**
 * Loads the AJAX Pagination module.
 */
function evie_ajax_pagination_module_load() {
	// Rest API.
	require_once plugin_dir_path( __FILE__ ) . 'rest-api/class-evie-rest-block-renderer-controller.php';
	require_once plugin_dir_path( __FILE__ ) . 'rest-api/class-evie-rest-post-controller.php';
	require_once plugin_dir_path( __FILE__ ) . 'rest-api/class-evie-rest-comments-renderer-controller.php';
	require_once plugin_dir_path( __FILE__ ) . 'rest-api/class-evie-rest-posts-controller.php';
	require_once plugin_dir_path( __FILE__ ) . 'rest-api/class-evie-rest-posts-renderer-controller.php';

	/* Public functions. */
	require_once plugin_dir_path( __FILE__ ) . 'evie-ajax-pagination.php';
}
