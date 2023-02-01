<?php
/**
 * Social Links Module
 *
 * @package    Flextension
 * @subpackage Modules/Social_Links
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Registers the Social Links module.
 */
flextension_register_module(
	__FILE__,
	array(
		'title'         => esc_html__( 'Social Links', 'flextension' ),
		'description'   => esc_html__( 'Adds a social links widget to your website and social links settings to the user contact info section.', 'flextension' ),
		'category'      => esc_html__( 'Social', 'flextension' ),
		'actions'       => array(
			'settings' => sprintf(
				'<a href="%s">%s</a>',
				flextension_get_admin_page_url( 'social-links' ),
				esc_html__( 'Settings', 'flextension' )
			),
		),
		'load_callback' => 'flextension_social_links_module_load',
	)
);

/**
 * Loads the Social Links module.
 *
 * @param Flextension_Module $module The current module instance.
 */
function flextension_social_links_module_load( $module ) {

	// Public functions.
	require_once plugin_dir_path( __FILE__ ) . 'flextension-social-links.php';

	// Registers all widgets in the folder 'widgets'.
	flextension_load_files( 'widgets/*.php', plugin_dir_path( __FILE__ ) );

	// Settings.
	if ( is_admin() ) {
		require_once plugin_dir_path( __FILE__ ) . 'admin/class-flextension-social-links-admin.php';
		new Flextension_Social_Links_Admin( $module->name );

		require_once plugin_dir_path( __FILE__ ) . 'admin/class-flextension-social-links-user-profile.php';
		new Flextension_Social_Links_User_Profile();
	}

}
