<?php
/**
 * Tabs Extension
 *
 * @package    Flextension
 * @subpackage Extensions/Tabs
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Registers the Tabs extension.
 */
flextension_register_module(
	__FILE__,
	array(
		'public'        => false,
		'type'          => 'extension',
		'load_callback' => 'flextension_tabs_module_load',
	)
);

/**
 * Loads the Tabs extension.
 */
function flextension_tabs_module_load() {
	add_action( 'init', 'flextension_tabs_register_scripts', 5 );
}

/**
 * Registers the scripts and stylesheets for the module.
 */
function flextension_tabs_register_scripts() {

	wp_register_style( 'flextension-tabs', plugins_url( 'css/style.css', __FILE__ ), array( 'flextension' ), flextension_get_setting( 'version' ) );
	wp_style_add_data( 'flextension-tabs', 'rtl', 'replace' );

	wp_register_script( 'flextension-tabs', plugins_url( 'js/index.js', __FILE__ ), array( 'flextension' ), flextension_get_setting( 'version' ), true );

}
