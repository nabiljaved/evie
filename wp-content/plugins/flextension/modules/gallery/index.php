<?php
/**
 * Gallery Module
 *
 * @package    Flextension
 * @subpackage Modules/Gallery
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Registers the Gallery module.
 */
flextension_register_module(
	__FILE__,
	array(
		'title'         => esc_html__( 'Gallery', 'flextension' ),
		'description'   => esc_html__( 'Adds a Carousel Gallery and Waterfall Gallery to the Block Editor.', 'flextension' ),
		'category'      => esc_html__( 'Gallery', 'flextension' ),
		'enabled'       => true,
		'dependencies'  => array( 'carousel', 'lightbox-gallery' ),
		'load_callback' => 'flextension_gallery_module_load',
	)
);

/**
 * Loads the Gallery module.
 */
function flextension_gallery_module_load() {
	require_once plugin_dir_path( __FILE__ ) . 'flextension-gallery.php';
}
