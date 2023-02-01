<?php
/**
 * Live Search Module
 *
 * Provides live search suggestions via AJAX.
 *
 * @package    Flextension
 * @subpackage Modules/Live_Search
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Registers the Live Search module.
 */
flextension_register_module(
	__FILE__,
	array(
		'title'         => esc_html__( 'Live Search', 'flextension' ),
		'description'   => esc_html__( 'Displays live search suggestions while typing a keyword.', 'flextension' ),
		'category'      => esc_html__( 'Search', 'flextension' ),
		'enabled'       => true,
		'actions'       => array(
			'settings' => sprintf(
				'<a href="%s">%s</a>',
				flextension_get_admin_page_url( 'live-search' ),
				esc_html__( 'Settings', 'flextension' )
			),
		),
		'load_callback' => 'flextension_live_search_module_load',
	)
);

/**
 * Loads the Live Search module.
 *
 * @param Flextension_Module $module The current module instance.
 */
function flextension_live_search_module_load( $module ) {

	// Public functions.
	require_once plugin_dir_path( __FILE__ ) . 'flextension-live-search.php';

	// Settings.
	if ( is_admin() ) {
		require_once plugin_dir_path( __FILE__ ) . 'admin/class-flextension-live-search-admin.php';
		new Flextension_Live_Search_Admin( $module->name );
	}
}
