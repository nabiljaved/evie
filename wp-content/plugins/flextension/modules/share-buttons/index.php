<?php
/**
 * Share Buttons Module
 *
 * @package    Flextension
 * @subpackage Modules/Share_Buttons
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Registers the Share Buttons module.
 */
flextension_register_module(
	__FILE__,
	array(
		'title'         => esc_html__( 'Share Buttons', 'flextension' ),
		'description'   => esc_html__( 'Allows visitors to share the content on your website over Facebook, Twitter and other social sharing and bookmarking services.', 'flextension' ),
		'category'      => esc_html__( 'Post', 'flextension' ),
		'enabled'       => true,
		'dependencies'  => array( 'lightbox' ),
		'actions'       => array(
			'settings' => sprintf(
				'<a href="%s">%s</a>',
				flextension_get_admin_page_url( 'share-buttons' ),
				esc_html__( 'Settings', 'flextension' )
			),
		),
		'load_callback' => 'flextension_share_buttons_module_load',
	)
);

/**
 * Loads the Share Buttons module.
 *
 * @param Flextension_Module $module The current module instance.
 */
function flextension_share_buttons_module_load( $module ) {
	// Public functions.
	require_once plugin_dir_path( __FILE__ ) . 'flextension-share-buttons.php';

	require_once plugin_dir_path( __FILE__ ) . 'rest-api/class-flextension-share-buttons-api.php';
	new Flextension_Share_Buttons_API();

	// Settings.
	if ( is_admin() ) {
		require_once plugin_dir_path( __FILE__ ) . 'admin/class-flextension-share-buttons-admin.php';
		new Flextension_Share_Buttons_Admin( $module->name );
	}
}
