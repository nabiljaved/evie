<?php
/**
 * MailChimp For WP Extension.
 *
 * @package    Evie_XT
 * @subpackage Extensions/MailChimp_For_WP
 * @version    1.0.0
 */

if ( defined( 'MC4WP_VERSION' ) && MC4WP_VERSION ) {

	/**
	 * Registers the Contact Form 7 extension.
	 */
	flextension_register_module(
		__FILE__,
		array(
			'title'         => esc_html__( 'MailChimp for WP', 'evie-xt' ),
			'description'   => esc_html__( 'Adds theme support and custom styles for the MC4WP plugin.', 'evie-xt' ),
			'type'          => 'extension',
			'enabled'       => true,
			'load_callback' => 'evie_mc4wp_module_load',
		)
	);

	/**
	 * Initializes the extension.
	 */
	function evie_mc4wp_module_load() {
		add_action( 'wp_enqueue_scripts', 'evie_mc4wp_enqueue_scripts', 5 );
	}

	/**
	 * Enqueues the scripts and stylesheets.
	 */
	function evie_mc4wp_enqueue_scripts() {
		wp_enqueue_style( 'evie-mc4wp', plugins_url( 'css/style.css', __FILE__ ), array(), EVIE_XT_VERSION );
	}
}
