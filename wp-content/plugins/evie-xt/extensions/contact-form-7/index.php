<?php
/**
 * Contact Form 7 Extension.
 *
 * @package    Evie_XT
 * @subpackage Extensions/Contact_Form_7
 * @version    1.0.0
 */

if ( defined( 'WPCF7_VERSION' ) && WPCF7_VERSION ) {

	/**
	 * Registers the Contact Form 7 extension.
	 */
	flextension_register_module(
		__FILE__,
		array(
			'title'         => esc_html__( 'Contact Form 7', 'evie-xt' ),
			'description'   => esc_html__( 'Adds theme support and custom styles for the Contact Form 7 plugin.', 'evie-xt' ),
			'type'          => 'extension',
			'enabled'       => true,
			'load_callback' => 'evie_contact_form_7_module_load',
		)
	);

	/**
	 * Loads the Contact Form 7 extension.
	 */
	function evie_contact_form_7_module_load() {
		add_filter( 'flextension_extension_support_blocks', 'evie_contact_form_7_add_default_supported_blocks' );

		add_action( 'wp_enqueue_scripts', 'evie_contact_form_7_enqueue_scripts' );
	}

	/**
	 * Adds Contact Form 7 block into a list of default blocks that support all additional extensions.
	 *
	 * @param array $blocks An array list of supported blocks.
	 * @return array An array list of supported blocks.
	 */
	function evie_contact_form_7_add_default_supported_blocks( $blocks = array() ) {
		$blocks[] = 'contact-form-7/contact-form-selector';
		return $blocks;
	}

	/**
	 * Enqueues the scripts and stylesheets for the module.
	 */
	function evie_contact_form_7_enqueue_scripts() {
		wp_enqueue_style( 'evie-cf7', plugins_url( 'css/style.css', __FILE__ ), array( 'contact-form-7' ), EVIE_XT_VERSION );

		wp_enqueue_script( 'evie-cf7', plugins_url( 'js/index.js', __FILE__ ), array( 'evie-single-page' ), EVIE_XT_VERSION, true );
	}
}
