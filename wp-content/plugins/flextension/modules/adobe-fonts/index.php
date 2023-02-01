<?php
/**
 * Adobe Fonts Module
 *
 * @package    Flextension
 * @subpackage Modules/Adobe_Fonts
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Registers the Adobe Fonts module.
 */
flextension_register_module(
	__FILE__,
	array(
		'title'         => esc_html__( 'Adobe Fonts', 'flextension' ),
		'description'   => esc_html__( 'Adds Adobe Fonts to your website.', 'flextension' ),
		'category'      => esc_html__( 'Advanced', 'flextension' ),
		'dependencies'  => array( 'fonts' ),
		'actions'       => array(
			'settings' => sprintf(
				'<a href="%s">%s</a>',
				flextension_get_admin_page_url( 'fonts', 'themes', 'adobe-fonts' ),
				esc_html__( 'Settings', 'flextension' )
			),
		),
		'links'         => array(
			array(
				'text'   => esc_html__( 'View documentation', 'flextension' ),
				'url'    => flextension_get_doc_url( 'adobe-fonts' ),
				'target' => '_blank',
			),
		),
		'load_callback' => 'flextension_adobe_fonts_module_load',
	)
);

/**
 * Loads the Adobe Fonts module.
 *
 * @param Flextension_Module $module The current module instance.
 */
function flextension_adobe_fonts_module_load( $module ) {
	// Public functions.
	require_once plugin_dir_path( __FILE__ ) . 'flextension-adobe-fonts.php';

	// Settings.
	if ( is_admin() ) {
		require_once plugin_dir_path( __FILE__ ) . 'admin/class-flextension-adobe-fonts-admin.php';
		new Flextension_Adobe_Fonts_Admin( $module->name );
	}
}
