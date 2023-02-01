<?php
/**
 * Facebook Module
 *
 * @package    Flextension
 * @subpackage Modules/Facebook
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Registers the Facebook module.
 */
flextension_register_module(
	__FILE__,
	array(
		'title'         => esc_html__( 'Facebook', 'flextension' ),
		'description'   => esc_html__( 'Displays a Facebook Page widget in your sidebar.', 'flextension' ),
		'category'      => esc_html__( 'Social', 'flextension' ),
		'load_callback' => 'flextension_facebook_module_load',
	)
);

/**
 * Loads the Facebook module.
 */
function flextension_facebook_module_load() {

	// Public functions.
	require_once plugin_dir_path( __FILE__ ) . 'flextension-facebook.php';

	// Widget.
	require_once plugin_dir_path( __FILE__ ) . 'widgets/class-flextension-facebook-page-widget.php';

}
