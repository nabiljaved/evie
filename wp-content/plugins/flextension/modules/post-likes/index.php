<?php
/**
 * Post Likes Module
 *
 * @package    Flextension
 * @subpackage Modules/Post_Likes
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Registers the Post Likes module.
 */
flextension_register_module(
	__FILE__,
	array(
		'title'         => esc_html__( 'Post Likes', 'flextension' ),
		'description'   => esc_html__( 'Displays number of likes for the post.', 'flextension' ),
		'category'      => esc_html__( 'Post', 'flextension' ),
		'enabled'       => true,
		'dependencies'  => array( 'api', 'lightbox' ),
		'load_callback' => 'flextension_post_likes_module_load',
	)
);

/**
 * Loads the Post Likes module.
 */
function flextension_post_likes_module_load() {
	// Public functions.
	require_once plugin_dir_path( __FILE__ ) . 'flextension-post-likes.php';
}
