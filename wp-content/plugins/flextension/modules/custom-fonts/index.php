<?php
/**
 * Custom Fonts Module
 *
 * @package    Flextension
 * @subpackage Modules/Custom_Fonts
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Registers the Custom Fonts module.
 */
flextension_register_module(
	__FILE__,
	array(
		'title'         => esc_html__( 'Custom Fonts', 'flextension' ),
		'description'   => esc_html__( 'Allows you to add and use custom fonts by simply uploading web-font files to your site.', 'flextension' ),
		'category'      => esc_html__( 'Advanced', 'flextension' ),
		'dependencies'  => array( 'fonts' ),
		'actions'       => array(
			'settings' => sprintf(
				'<a href="%s">%s</a>',
				flextension_get_admin_page_url( 'fonts', 'themes', 'custom-fonts' ),
				esc_html__( 'Settings', 'flextension' )
			),
		),
		'links'         => array(
			array(
				'text'   => esc_html__( 'View documentation', 'flextension' ),
				'url'    => flextension_get_doc_url( 'custom-fonts' ),
				'target' => '_blank',
			),
		),
		'load_callback' => 'flextension_custom_fonts_module_load',
	)
);

/**
 * Loads the Custom Fonts module.
 *
 * @param Flextension_Module $module The current module instance.
 */
function flextension_custom_fonts_module_load( $module ) {

	// Public functions.
	require_once plugin_dir_path( __FILE__ ) . 'flextension-custom-fonts.php';

	// Settings.
	if ( is_admin() ) {
		require_once plugin_dir_path( __FILE__ ) . 'admin/class-flextension-custom-fonts-admin.php';
		new Flextension_Custom_Fonts_Admin( $module->name );
	}
}
