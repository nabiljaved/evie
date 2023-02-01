<?php
/**
 * Carousel Extension
 *
 * @package    Flextension
 * @subpackage Extensions/Carousel
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Registers the Carousel extension.
 */
flextension_register_module(
	__FILE__,
	array(
		'title'         => esc_html__( 'Carousel', 'flextension' ),
		'description'   => esc_html__( 'Includes carousel files in your website to create a modern mobile touch slider.', 'flextension' ),
		'category'      => esc_html__( 'Widget', 'flextension' ),
		'type'          => 'extension',
		'load_callback' => 'flextension_carousel_module_load',
	)
);

/**
 * Loads the Carousel extension.
 */
function flextension_carousel_module_load() {

	add_action( 'init', 'flextension_carousel_register_scripts', 5 );

}

/**
 * Registers the scripts and stylesheets.
 */
function flextension_carousel_register_scripts() {

	wp_register_style( 'flextension-carousel', plugins_url( 'css/style.css', __FILE__ ), array( 'flextension' ), flextension_get_setting( 'version' ) );

	wp_register_script( 'flextension-carousel', plugins_url( 'js/index.js', __FILE__ ), array( 'flextension' ), flextension_get_setting( 'version' ), true );

}
