<?php
/**
 * Featured Categories Module
 *
 * Core class used to provide the featured categories and other terms.
 *
 * @package    Flextension
 * @subpackage Modules/Featured_Categories
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Registers the Featured Categories module.
 */
flextension_register_module(
	__FILE__,
	array(
		'title'         => esc_html__( 'Featured Categories', 'flextension' ),
		'description'   => esc_html__( 'Allows you to set the featured image for categories and displays them in widgets and blocks.', 'flextension' ),
		'category'      => esc_html__( 'Blog', 'flextension' ),
		'enabled'       => true,
		'dependencies'  => array( 'editor' ),
		'load_callback' => 'flextension_featured_categoies_module_load',
	)
);

/**
 * Loads the Featured Categories module.
 */
function flextension_featured_categoies_module_load() {

	// Public functions.
	require_once plugin_dir_path( __FILE__ ) . 'class-flextension-walker-category-checklist.php';

	require_once plugin_dir_path( __FILE__ ) . 'flextension-featured-categories.php';

	require_once plugin_dir_path( __FILE__ ) . 'rest-api/class-flextension-categories-checklist-api.php';
	new Flextension_Categories_Checklist_API();

	// Registers all widgets in the folder 'widgets'.
	flextension_load_files( 'widgets/*.php', plugin_dir_path( __FILE__ ) );

	// Edit Categories.
	if ( is_admin() ) {
		require_once plugin_dir_path( __FILE__ ) . 'admin/class-flextension-featured-categories-edit.php';
		new Flextension_Featured_Categories_Edit();
	}

}
