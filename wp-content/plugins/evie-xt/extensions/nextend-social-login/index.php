<?php
/**
 * Nextend Social Login Extension.
 *
 * @package    Evie_XT
 * @subpackage Extensions/Nextend_Social_Login
 * @version    1.0.0
 */

if ( class_exists( 'NextendSocialLogin', false ) ) {

	/**
	 * Registers the Nextend Social Login extension.
	 */
	flextension_register_module(
		__FILE__,
		array(
			'title'         => esc_html__( 'Nextend Social Login', 'evie-xt' ),
			'description'   => esc_html__( 'Adds theme support and custom styles for the Nextend Social Login plugin.', 'evie-xt' ),
			'type'          => 'extension',
			'enabled'       => true,
			'load_callback' => 'evie_nextend_social_login_module_load',
		)
	);

	/**
	 * Loads the Nextend Social Login extension.
	 */
	function evie_nextend_social_login_module_load() {
		add_action( 'wp_enqueue_scripts', 'evie_nextend_social_login_enqueue_scripts' );

		add_action( 'login_enqueue_scripts', 'evie_extend_social_login_login_enqueue_scripts' );
	}

	/**
	 * Enqueues scripts and styles for login page.
	 */
	function evie_extend_social_login_login_enqueue_scripts() {
		wp_enqueue_style( 'evie-nextend-social-login-page', plugins_url( 'css/login-page.css', __FILE__ ), array(), EVIE_XT_VERSION );
	}

	/**
	 * Enqueues the scripts and stylesheets for the module.
	 */
	function evie_nextend_social_login_enqueue_scripts() {
		wp_enqueue_style( 'evie-nextend-social-login', plugins_url( 'css/style.css', __FILE__ ), array(), EVIE_XT_VERSION );
	}
}
