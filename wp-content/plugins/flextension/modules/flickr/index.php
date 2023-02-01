<?php
/**
 * Flickr Module
 *
 * @package    Flextension
 * @subpackage Modules/Flickr
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Registers the Flickr module.
 */
flextension_register_module(
	__FILE__,
	array(
		'title'         => esc_html__( 'Flickr Photos', 'flextension' ),
		'description'   => esc_html__( 'Displays a Flickr Photos feed in your website.', 'flextension' ),
		'category'      => esc_html__( 'Social', 'flextension' ),
		'load_callback' => 'flextension_flickr_module_load',
	)
);

/**
 * Loads the Flickr module.
 */
function flextension_flickr_module_load() {

	require_once plugin_dir_path( __FILE__ ) . 'flextension-flickr.php';

	require_once plugin_dir_path( __FILE__ ) . 'widgets/class-flextension-flickr-widget.php';

}
