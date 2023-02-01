<?php
/**
 * Featured Media Module
 *
 * Adds featured gallery, video and audio to the post.
 *
 * @package    Flextension
 * @subpackage Modules/Featured_Media
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Registers the Featured Media module.
 */
flextension_register_module(
	__FILE__,
	array(
		'title'         => esc_html__( 'Featured Media', 'flextension' ),
		'description'   => esc_html__( 'Displays featured video, audio and images gallery of the post.', 'flextension' ),
		'category'      => esc_html__( 'Blog', 'flextension' ),
		'enabled'       => true,
		'dependencies'  => array( 'meta-box', 'carousel', 'lightbox-gallery' ),
		'load_callback' => 'flextension_featured_media_module_load',
	)
);

/**
 * Loads the Featured Media module.
 */
function flextension_featured_media_module_load() {

	// Public functions.
	require_once plugin_dir_path( __FILE__ ) . 'flextension-featured-media.php';

	// Featured Media Metabox.
	if ( is_admin() ) {
		require_once plugin_dir_path( __FILE__ ) . 'admin/class-flextension-featured-media-meta-box.php';
		new Flextension_Featured_Media_Meta_Box();
	}
}
