<?php
/**
 * Lazy Load Module
 *
 * Adds lazy loading support to images. Defers the loading of images on the page as long as they are not needed.
 * Images outside of the viewport will not be loaded before user scrolls to them. This can speed up your site.
 *
 * @package    Flextension
 * @subpackage Modules/Lazyload
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Registers the Lazyload module.
 */
flextension_register_module(
	__FILE__,
	array(
		'title'         => esc_html__( 'Lazy Load', 'flextension' ),
		'description'   => esc_html__( 'Defers the loading of images on the page as long as they are not needed. Images outside of the viewport will not be loaded before user scrolls to them. This can speed up your site.', 'flextension' ),
		'category'      => esc_html__( 'Advanced', 'flextension' ),
		'actions'       => array(
			'settings' => sprintf(
				'<a href="%s">%s</a>',
				sprintf( '%s#%s', admin_url( flextension_get_admin_page( 'media' ) ), flextension_get_page_slug( 'lazyload' ) ),
				esc_html__( 'Settings', 'flextension' )
			),
		),
		'load_callback' => 'flextension_lazyload_module_load',
	)
);

/**
 * Loads the Lazyload module.
 *
 * @param Flextension_Module $module The current module instance.
 */
function flextension_lazyload_module_load( $module ) {
	// Disable default WordPress lazyload feature.
	add_filter( 'wp_lazy_loading_enabled', '__return_false' );

	// Public functions.
	require_once plugin_dir_path( __FILE__ ) . 'flextension-lazyload.php';

	// Settings.
	if ( is_admin() ) {
		require_once plugin_dir_path( __FILE__ ) . 'admin/class-flextension-lazyload-admin.php';
		new Flextension_Lazyload_Admin( $module->name );
	}
}
