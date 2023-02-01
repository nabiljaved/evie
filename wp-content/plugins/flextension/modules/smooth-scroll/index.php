<?php
/**
 * Smooth Scroll Module
 *
 * @package    Flextension
 * @subpackage Modules/Smooth_Scroll
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Registers the Smooth Scroll module.
 */
flextension_register_module(
	__FILE__,
	array(
		'title'         => esc_html__( 'Smooth Scroll', 'flextension' ),
		'description'   => esc_html__( 'Enables smooth scrolling feature.', 'flextension' ),
		'category'      => esc_html__( 'Advanced', 'flextension' ),
		'actions'       => array(
			'settings' => sprintf(
				'<a href="%s">%s</a>',
				flextension_get_admin_page_url( 'smooth-scroll' ),
				esc_html__( 'Settings', 'flextension' )
			),
		),
		'load_callback' => 'flextension_smooth_scroll_module_load',
	)
);

/**
 * Loads the Smooth Scroll module.
 *
 * @param Flextension_Module $module The current module instance.
 */
function flextension_smooth_scroll_module_load( $module ) {

	// Public functions.
	require_once plugin_dir_path( __FILE__ ) . 'flextension-smooth-scroll.php';

	// Settings.
	if ( is_admin() ) {

		require_once plugin_dir_path( __FILE__ ) . 'admin/class-flextension-smooth-scroll-admin.php';
		new Flextension_Smooth_Scroll_Admin( $module->name );

	}
}

