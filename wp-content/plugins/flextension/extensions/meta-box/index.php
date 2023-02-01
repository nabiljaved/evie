<?php
/**
 * Meta Box Extension
 *
 * Adds meta box fields into tabs for a better view.
 *
 * @package    Flextension
 * @subpackage Extensions/Meta_Box
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Registers the Meta Box extension.
 */
flextension_register_module(
	__FILE__,
	array(
		'title'         => esc_html__( 'Meta Box', 'flextension' ),
		'description'   => esc_html__( 'Adds meta box fields into WordPress Editor.', 'flextension' ),
		'type'          => 'extension',
		'dependencies'  => array( 'tabs' ),
		'load_callback' => 'flextension_meta_box_module_load',
	)
);

/**
 * Loads the Meta Box extension.
 *
 * @param Flextension_Module $module The current module instance.
 */
function flextension_meta_box_module_load( $module ) {

	// Public functions.
	require_once plugin_dir_path( __FILE__ ) . 'flextension-meta-box.php';

	// Admin area.
	if ( is_admin() ) {
		require_once plugin_dir_path( __FILE__ ) . 'admin/class-flextension-meta-box-admin.php';
		new Flextension_Meta_Box_Admin( $module->name );
	}

}
