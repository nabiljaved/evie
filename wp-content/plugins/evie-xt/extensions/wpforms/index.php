<?php
/**
 * WPForms Extension
 *
 * @package    Evie_XT
 * @subpackage Extensions/WPForms
 * @version    1.0.0
 */

if ( defined( 'WPFORMS_VERSION' ) && WPFORMS_VERSION ) {

	/**
	 * Registers the WPForms extension.
	 */
	flextension_register_module(
		__FILE__,
		array(
			'title'         => esc_html__( 'WPForms', 'evie-xt' ),
			'description'   => esc_html__( 'Adds theme support and custom styles for the WPForms plugin.', 'evie-xt' ),
			'type'          => 'extension',
			'enabled'       => true,
			'load_callback' => 'evie_wpforms_module_load',
		)
	);

	/**
	 * Loads the WPForms extension.
	 */
	function evie_wpforms_module_load() {
		add_action( 'init', 'evie_wpforms_register_scripts' );

		add_action( 'init', 'evie_wpforms_remove_block_styles', 20 );

		add_action( 'wpforms_wp_footer', 'evie_wpforms_remove_styles', 20 );

		add_filter( 'flextension_extension_support_blocks', 'evie_wpforms_add_default_supported_blocks' );

		add_action( 'wp_enqueue_scripts', 'evie_wpforms_enqueue_scripts' );

		add_action( 'wp_enqueue_scripts', 'evie_wpforms_remove_styles', 20 );

		add_action( 'enqueue_block_editor_assets', 'evie_wpforms_enqueue_block_editor_assets' );
	}

	/**
	 * Adds WPForm block into a list of default blocks that support all additional extensions.
	 *
	 * @param array $blocks An array list of supported blocks.
	 * @return array An array list of supported blocks.
	 */
	function evie_wpforms_add_default_supported_blocks( $blocks = array() ) {
		$blocks[] = 'wpforms/form-selector';
		return $blocks;
	}

	/**
	 * Removes default CSS styles for WPForms block from WPForms plugin.
	 */
	function evie_wpforms_remove_block_styles() {
		wp_deregister_style( 'wpforms-integrations' );
		wp_deregister_style( 'wpforms-gutenberg-form-selector' );
	}

	/**
	 * Removes default CSS styles from WP Forms plugin because the theme provides custom ones.
	 */
	function evie_wpforms_remove_styles() {
		wp_dequeue_style( 'wpforms-full' );
		wp_dequeue_style( 'wpforms-base' );
	}

	/**
	 * Registers scripts and stylesheets.
	 */
	function evie_wpforms_register_scripts() {

		wp_register_style( 'evie-wpforms', plugins_url( 'css/style.css', __FILE__ ), array(), EVIE_XT_VERSION );
		wp_style_add_data( 'evie-wpforms', 'rtl', 'replace' );

		wp_register_style( 'evie-wpforms-edit', plugins_url( 'css/edit.css', __FILE__ ), array( 'evie-wpforms' ), EVIE_XT_VERSION );
		wp_style_add_data( 'evie-wpforms-edit', 'rtl', 'replace' );

		wp_register_script( 'evie-wpforms', plugins_url( 'js/index.js', __FILE__ ), array( 'evie-single-page' ), EVIE_XT_VERSION, true );
	}

	/**
	 * Enqueues the scripts and stylesheets.
	 */
	function evie_wpforms_enqueue_scripts() {

		wp_enqueue_style( 'evie-wpforms' );

		wp_enqueue_script( 'evie-wpforms' );
	}

	/**
	 * Enqueues block editor scripts and stylesheets.
	 */
	function evie_wpforms_enqueue_block_editor_assets() {
		wp_enqueue_style( 'evie-wpforms' );
		wp_enqueue_style( 'evie-wpforms-edit' );
	}
}
