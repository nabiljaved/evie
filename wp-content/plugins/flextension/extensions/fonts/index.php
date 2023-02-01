<?php
/**
 * Fonts Extension
 *
 * Adds fonts into tabs for a better view.
 *
 * @package    Flextension
 * @subpackage Extensions/Fonts
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Registers the Fonts extension.
 */
flextension_register_module(
	__FILE__,
	array(
		'title'         => esc_html__( 'Fonts', 'flextension' ),
		'description'   => esc_html__( 'Adds new fonts into your WordPress site.', 'flextension' ),
		'type'          => 'extension',
		'load_callback' => 'flextension_fonts_module_load',
	)
);

/**
 * Loads the Fonts extension.
 *
 * @param Flextension_Module $module The current module instance.
 */
function flextension_fonts_module_load( $module ) {

	// Public functions.
	require_once plugin_dir_path( __FILE__ ) . 'flextension-fonts.php';

	// Admin area.
	if ( is_admin() ) {
		require_once plugin_dir_path( __FILE__ ) . 'admin/class-flextension-fonts-admin.php';
		new Flextension_Fonts_Admin( $module->name );
	}

}
