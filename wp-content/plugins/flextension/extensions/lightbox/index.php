<?php
/**
 * Lightbox Extension
 *
 * @package    Flextension
 * @subpackage Extensions/Lightbox
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Registers the Lightbox extension.
 */
flextension_register_module(
	__FILE__,
	array(
		'title'         => esc_html__( 'Lightbox', 'flextension' ),
		'description'   => esc_html__( 'Displays an HTML content in a responsive lightbox.', 'flextension' ),
		'type'          => 'extension',
		'dependencies'  => array( 'api' ),
		'load_callback' => 'flextension_lightbox_module_load',
	)
);

/**
 * Loads the Lightbox extension.
 */
function flextension_lightbox_module_load() {
	add_action( 'init', 'flextension_lightbox_register_scripts', 5 );
}

/**
 * Registers the scripts and stylesheets.
 */
function flextension_lightbox_register_scripts() {

	wp_register_style( 'flextension-lightbox', plugins_url( 'css/style.css', __FILE__ ), array( 'flextension' ), flextension_get_setting( 'version' ) );
	wp_style_add_data( 'flextension-lightbox', 'rtl', 'replace' );

	wp_register_script( 'flextension-lightbox', plugins_url( 'js/index.js', __FILE__ ), array( 'flextension-api' ), flextension_get_setting( 'version' ), true );
}
