<?php
/**
 * Retina Images Module
 *
 * @package    Flextension
 * @subpackage Modules/Retina_Images
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Registers the Retina Images module.
 */
flextension_register_module(
	__FILE__,
	array(
		'title'         => esc_html__( 'Retina Images', 'flextension' ),
		'description'   => esc_html__( 'Serves larger images on Retina screens to make your images look crisp and beautiful on any device.', 'flextension' ),
		'category'      => esc_html__( 'Advanced', 'flextension' ),
		'actions'       => array(
			'settings' => sprintf(
				'<a href="%s">%s</a>',
				sprintf( '%s#%s', admin_url( flextension_get_admin_page( 'media' ) ), flextension_get_page_slug( 'retina-images' ) ),
				esc_html__( 'Settings', 'flextension' )
			),
		),
		'load_callback' => 'flextension_retina_images_module_load',
	)
);

/**
 * Loads the Retina Images module.
 *
 * @param Flextension_Module $module The current module instance.
 */
function flextension_retina_images_module_load( $module ) {

	// Public functions.
	require_once plugin_dir_path( __FILE__ ) . 'flextension-retina-images.php';

	// Settings.
	if ( is_admin() ) {
		require_once plugin_dir_path( __FILE__ ) . 'admin/class-flextension-retina-images-admin.php';
		new Flextension_Retina_Images_Admin( $module->name );
	}
}
