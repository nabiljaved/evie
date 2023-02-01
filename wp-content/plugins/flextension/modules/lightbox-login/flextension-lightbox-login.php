<?php
/**
 * Lightbox Login
 *
 * @package    Flextension
 * @subpackage Modules/Lightbox_Login
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Returns Lightbox Login button.
 *
 * @param string $class      Additional CSS class for the button.
 * @param string $icon_class A CSS class for the icon.
 * @return string Lightbox Login button.
 */
function flextension_lightbox_login_button( $class = '', $icon_class = '' ) {
	if ( ! empty( $class ) ) {
		$class = ' ' . $class;
	}

	if ( empty( $icon_class ) ) {
		$icon_class = 'flext-ico-user';
	}

	return sprintf(
		'<a href="%1$s" class="flext-lightbox-login-button%2$s" aria-label="%3$s"><i class="%4$s"></i></a>',
		esc_attr( esc_url( flextension_lightbox_login_url() ) ),
		esc_attr( $class ),
		esc_attr( esc_html__( 'Log In', 'flextension' ) ),
		esc_attr( $icon_class )
	);
}

/**
 * Returns the login URL.
 *
 * @return string The login URL.
 */
function flextension_lightbox_login_url() {
	$url = wp_login_url( home_url() );

	/**
	 * Filters the login URL.
	 *
	 * @param string $url The login URL.
	 */
	return apply_filters( 'flextension_lightbox_login_url', $url );
}

/**
 * Enqueues the scripts and stylesheets for the module.
 */
function flextension_lightbox_login_enqueue_scripts() {

	wp_enqueue_style( 'flextension-lightbox-login', plugins_url( 'css/style.css', __FILE__ ), array( 'flextension', 'flextension-lightbox' ), flextension_get_setting( 'version' ) );
	wp_style_add_data( 'flextension-lightbox-login', 'rtl', 'replace' );

	wp_enqueue_script( 'flextension-lightbox-login', plugins_url( 'js/index.js', __FILE__ ), array( 'flextension', 'flextension-api', 'flextension-lightbox' ), flextension_get_setting( 'version' ), true );

}

add_action( 'wp_enqueue_scripts', 'flextension_lightbox_login_enqueue_scripts' );
