<?php
/**
 * Instagram Module
 *
 * @package    Flextension
 * @subpackage Modules/Instagram
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Registers the Instagram module.
 */
flextension_register_module(
	__FILE__,
	array(
		'title'         => esc_html__( 'Instagram Feed', 'flextension' ),
		'description'   => esc_html__( 'Displays your Instagram feed on your website.', 'flextension' ),
		'category'      => esc_html__( 'Social', 'flextension' ),
		'dependencies'  => array( 'carousel' ),
		'actions'       => array(
			'settings' => sprintf(
				'<a href="%s">%s</a>',
				flextension_get_admin_page_url( 'instagram' ),
				esc_html__( 'Settings', 'flextension' )
			),
		),
		'load_callback' => 'flextension_instagram_module_load',
	)
);

/**
 * Loads the Instagram module.
 *
 * @param Flextension_Module $module The current module instance.
 */
function flextension_instagram_module_load( $module ) {

	// Public functions.
	require_once plugin_dir_path( __FILE__ ) . 'flextension-instagram.php';

	// Registers all widgets in the folder 'widgets'.
	flextension_load_files( 'widgets/*.php', plugin_dir_path( __FILE__ ) );

	// Settings.
	if ( is_admin() ) {
		require_once plugin_dir_path( __FILE__ ) . 'admin/class-flextension-instagram-admin.php';
		new Flextension_Instagram_Admin( $module->name );
	}

}
