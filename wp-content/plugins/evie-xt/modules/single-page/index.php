<?php
/**
 * Single Page Module
 *
 * Interacts with the user by dynamically rewriting the current page
 * rather than loading entire new pages from a server.
 *
 * @package    Evie_XT
 * @subpackage Modules/Single_Page
 * @version    1.0.0
 */

/**
 * Registers the Single Page module.
 */
flextension_register_module(
	__FILE__,
	array(
		'title'         => esc_html__( 'Single Page', 'evie-xt' ),
		'description'   => esc_html__( 'Interacts with the user by dynamically rewriting the current page rather than loading entire new pages from a server. It makes the site behave more like a desktop/mobile application.', 'evie-xt' ),
		'category'      => esc_html__( 'Advanced', 'evie-xt' ),
		'dependencies'  => array( 'ajax-pagination' ),
		'actions'       => array(
			'settings' => sprintf(
				'<a href="%s">%s</a>',
				flextension_get_admin_page_url( 'single-page' ),
				esc_html__( 'Settings', 'evie-xt' )
			),
		),
		'load_callback' => 'evie_single_page_module_load',
	)
);

/**
 * Loads the Single Page module.
 *
 * @param Flextension_Module $module The current module instance.
 */
function evie_single_page_module_load( $module ) {
	// Public functions.
	require_once plugin_dir_path( __FILE__ ) . 'evie-single-page.php';

	// Walker Nav Menu for REST API.
	require_once plugin_dir_path( __FILE__ ) . 'rest-api/class-evie-walker-nav-menu.php';

	// Page Renderer REST API.
	require_once plugin_dir_path( __FILE__ ) . 'rest-api/class-evie-rest-page-renderer-controller.php';

	// Settings.
	if ( is_admin() ) {
		require_once plugin_dir_path( __FILE__ ) . 'admin/class-evie-single-page-admin.php';
		new Evie_Single_Page_Admin( $module->name );
	}
}
