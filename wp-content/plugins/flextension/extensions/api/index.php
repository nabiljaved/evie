<?php
/**
 * API Extension
 *
 * @package    Flextension
 * @subpackage Extensions/API
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Registers the API extension.
 */
flextension_register_module(
	__FILE__,
	array(
		'public'        => false,
		'type'          => 'extension',
		'load_callback' => 'flextension_api_module_load',
	)
);

/**
 * Loads the Router extension.
 */
function flextension_api_module_load() {
	add_filter( 'flextension_settings', 'flextension_api_add_settings' );

	add_action( 'init', 'flextension_api_register_scripts', 5 );
}

/**
 * Appends settings to the current plugin settings.
 *
 * @param array $settings The current settings of the plugin.
 * @return array An array list of the plugin settings.
 */
function flextension_api_add_settings( $settings = array() ) {

	$settings['api'] = array(
		'url' => esc_url_raw( rest_url() ), // REST API URL.
	);

	if ( is_user_logged_in() ) {
		// Set a REST API nonce only when user logged in.
		$settings['api']['nonce'] = wp_create_nonce( 'wp_rest' );
	}

	// AJAX settings.
	$settings['ajaxUrl']   = esc_url_raw( admin_url( 'admin-ajax.php' ) );
	$settings['ajaxNonce'] = wp_create_nonce( 'flextension_ajax' );

	return $settings;
}

/**
 * Registers the scripts and stylesheets for the module.
 */
function flextension_api_register_scripts() {

	wp_register_script( 'flextension-api', plugins_url( 'js/index.js', __FILE__ ), array( 'flextension' ), flextension_get_setting( 'version' ), true );

}
