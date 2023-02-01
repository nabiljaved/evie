<?php
/**
 * Cookie Notice Extension
 *
 * @package    Evie_XT
 * @subpackage Extensions/Cookie_Notice
 * @version    1.0.0
 * @since      0.5.0
 */

if ( class_exists( 'Cookie_Notice', false ) ) {

	/**
	 * Registers the Cookie Notice extension.
	 */
	flextension_register_module(
		__FILE__,
		array(
			'title'         => esc_html__( 'Cookie Notice', 'evie-xt' ),
			'description'   => esc_html__( 'Adds theme support and custom styles for the Cookie Notice plugin.', 'evie-xt' ),
			'type'          => 'extension',
			'enabled'       => true,
			'load_callback' => 'evie_cookie_notice_module_load',
		)
	);

	/**
	 * Loads the Cookie Notice extension.
	 */
	function evie_cookie_notice_module_load() {
		add_action( 'wp_enqueue_scripts', 'evie_cookie_notice_enqueue_scripts' );
	}

	/**
	 * Enqueues the scripts and stylesheets.
	 */
	function evie_cookie_notice_enqueue_scripts() {
		wp_enqueue_style( 'evie-cookie-notice', plugins_url( 'css/style.css', __FILE__ ), array(), EVIE_XT_VERSION );
		wp_style_add_data( 'evie-cookie-notice', 'rtl', 'replace' );
	}
}
