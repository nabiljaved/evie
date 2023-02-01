<?php
/**
 * Lightbox Gallery Module
 *
 * Displays the large size of image in a lightbox when you set its link to Media File.
 *
 * @package    Flextension
 * @subpackage Modules/Lightbox_Gallery
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Registers the Lightbox Gallery module.
 */
flextension_register_module(
	__FILE__,
	array(
		'title'         => esc_html__( 'Lightbox Gallery', 'flextension' ),
		'description'   => esc_html__( 'Displays the large size of image in a lightbox when you set its link to Media File.', 'flextension' ),
		'category'      => esc_html__( 'Post', 'flextension' ),
		'enabled'       => true,
		'load_callback' => 'flextension_lightbox_gallery_module_load',
	)
);

/**
 * Loads the Lightbox Gallery module.
 */
function flextension_lightbox_gallery_module_load() {
	// Public functions.
	require_once plugin_dir_path( __FILE__ ) . 'flextension-lightbox-gallery.php';
}
