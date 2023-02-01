<?php
/**
 * Post Views Module
 *
 * @package    Flextension
 * @subpackage Modules/Post_Views
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Registers the Post Views module.
 */
flextension_register_module(
	__FILE__,
	array(
		'title'         => esc_html__( 'Post Views', 'flextension' ),
		'description'   => esc_html__( 'Displays number of views for the post.', 'flextension' ),
		'category'      => esc_html__( 'Post', 'flextension' ),
		'enabled'       => true,
		'load_callback' => 'flextension_post_views_module_load',
	)
);

/**
 * Loads the Post Views module.
 */
function flextension_post_views_module_load() {
	// Public functions.
	require_once plugin_dir_path( __FILE__ ) . 'flextension-post-views.php';
}
