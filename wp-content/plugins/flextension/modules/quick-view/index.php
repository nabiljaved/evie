<?php
/**
 * Quick View Module
 *
 * @package    Flextension
 * @subpackage Modules/Quick_View
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Registers the Quick View module.
 */
flextension_register_module(
	__FILE__,
	array(
		'title'         => esc_html__( 'Quick View', 'flextension' ),
		'description'   => esc_html__( 'Displays quick view content in the lightbox.', 'flextension' ),
		'category'      => esc_html__( 'Post', 'flextension' ),
		'dependencies'  => array( 'lightbox' ),
		'load_callback' => 'flextension_quick_view_module_load',
	)
);

/**
 * Loads the Quick View module.
 */
function flextension_quick_view_module_load() {

	// Public functions.
	require_once plugin_dir_path( __FILE__ ) . 'flextension-quick-view.php';

	require_once plugin_dir_path( __FILE__ ) . 'rest-api/class-flextension-quick-view-api.php';
	new Flextension_Quick_View_API();
}
