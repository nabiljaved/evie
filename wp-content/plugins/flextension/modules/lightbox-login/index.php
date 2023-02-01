<?php
/**
 * Lightbox Login Module
 *
 * @package    Flextension
 * @subpackage Modules/Lightbox_Login
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Registers the Lightbox Login module.
 */
flextension_register_module(
	__FILE__,
	array(
		'title'         => esc_html__( 'Lightbox Login', 'flextension' ),
		'description'   => esc_html__( 'Displays a login form in the lightbox.', 'flextension' ),
		'category'      => esc_html__( 'Utilities', 'flextension' ),
		'enabled'       => true,
		'dependencies'  => array( 'lightbox' ),
		'load_callback' => 'flextension_lightbox_login_module_load',
	)
);

/**
 * Loads the Lightbox Login module.
 */
function flextension_lightbox_login_module_load() {

	// Public functions.
	require_once plugin_dir_path( __FILE__ ) . 'flextension-lightbox-login.php';

	require_once plugin_dir_path( __FILE__ ) . 'rest-api/class-flextension-lightbox-login-api.php';
	new Flextension_Lightbox_Login_API();
}
