<?php
/**
 * Google Fonts Module
 *
 * @package    Flextension
 * @subpackage Modules/Google_Fonts
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Registers the Google Fonts module.
 */
flextension_register_module(
	__FILE__,
	array(
		'title'         => esc_html__( 'Google Fonts', 'flextension' ),
		'description'   => esc_html__( 'Adds Google Fonts to your website.', 'flextension' ),
		'category'      => esc_html__( 'Advanced', 'flextension' ),
		'dependencies'  => array( 'fonts' ),
		'actions'       => array(
			'settings' => sprintf(
				'<a href="%s">%s</a>',
				flextension_get_admin_page_url( 'fonts', 'themes', 'google-fonts' ),
				esc_html__( 'Settings', 'flextension' )
			),
		),
		'links'         => array(
			array(
				'text'   => esc_html__( 'View documentation', 'flextension' ),
				'url'    => flextension_get_doc_url( 'google-fonts' ),
				'target' => '_blank',
			),
		),
		'load_callback' => 'flextension_google_fonts_module_load',
	)
);

/**
 * Loads the Google Fonts module.
 *
 * @param Flextension_Module $module The current module instance.
 */
function flextension_google_fonts_module_load( $module ) {

	// Public functions.
	require_once plugin_dir_path( __FILE__ ) . 'flextension-google-fonts.php';

	// Settings.
	if ( is_admin() ) {
		require_once plugin_dir_path( __FILE__ ) . 'admin/class-flextension-google-fonts-admin.php';
		new Flextension_Google_Fonts_Admin( $module->name );
	}
}
