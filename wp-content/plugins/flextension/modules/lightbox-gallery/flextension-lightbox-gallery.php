<?php
/**
 * Lightbox Gallery
 *
 * @package    Flextension
 * @subpackage Modules/Lightbox_Gallery
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Appends Carousel settings to the current plugin settings.
 *
 * @param array $settings The current settings of the plugin.
 * @return array An array list of the plugin settings.
 */
function flextension_lightbox_gallery_add_settings( $settings = array() ) {
	$settings['strings']['next']     = esc_html__( 'Next', 'flextension' );
	$settings['strings']['previous'] = esc_html__( 'Previous', 'flextension' );
	$settings['strings']['close']    = esc_html__( 'Close', 'flextension' );
	return $settings;
}

add_filter( 'flextension_settings', 'flextension_lightbox_gallery_add_settings' );

/**
 * Registers the scripts and stylesheets.
 */
function flextension_lightbox_gallery_register_scripts() {

	wp_register_style( 'photoswipe', plugins_url( 'css/vendor/photoswipe.css', __FILE__ ), array(), '1.1.0' );

	wp_register_style( 'flextension-lightbox-gallery', plugins_url( 'css/style.css', __FILE__ ), array( 'flextension', 'photoswipe' ), flextension_get_setting( 'version' ) );
	wp_style_add_data( 'flextension-lightbox-gallery', 'rtl', 'replace' );

	wp_register_script( 'photoswipe', plugins_url( 'js/vendor/photoswipe.min.js', __FILE__ ), array(), '4.1.3', true );

	wp_register_script( 'photoswipe-ui-default', plugins_url( 'js/vendor/photoswipe-ui-default.min.js', __FILE__ ), array(), '4.1.3', true );

	wp_register_script( 'flextension-lightbox-gallery', plugins_url( 'js/index.js', __FILE__ ), array( 'photoswipe', 'photoswipe-ui-default', 'flextension' ), flextension_get_setting( 'version' ), true );

}

add_action( 'init', 'flextension_lightbox_gallery_register_scripts', 5 );

/**
 * Enqueues the scripts and stylesheets for the module.
 */
function flextension_lightbox_gallery_enqueue_scripts() {
	wp_enqueue_style( 'photoswipe' );

	wp_enqueue_style( 'flextension-lightbox-gallery' );

	wp_enqueue_script( 'photoswipe' );

	wp_enqueue_script( 'photoswipe-ui-default' );

	wp_enqueue_script( 'flextension-lightbox-gallery' );
}

add_action( 'wp_enqueue_scripts', 'flextension_lightbox_gallery_enqueue_scripts' );
