<?php
/**
 * Mega Menu Module
 *
 * @package    Evie_XT
 * @subpackage Modules/Mega_Menu
 * @version    1.0.0
 */

/**
 * Registers the Mega Menu module.
 */
flextension_register_module(
	__FILE__,
	array(
		'title'         => esc_html__( 'Mega Menu', 'evie-xt' ),
		'description'   => esc_html__( 'Displays Mega Menu in your the dropdown menu.', 'evie-xt' ),
		'enabled'       => true,
		'dependencies'  => array( 'api' ),
		'load_callback' => 'evie_mega_menu_module_load',
	)
);

/**
 * Loads the Mega Menu module.
 */
function evie_mega_menu_module_load() {
	// Public functions.
	require_once plugin_dir_path( __FILE__ ) . 'evie-mega-menu.php';

	// Menu settings.
	require_once plugin_dir_path( __FILE__ ) . 'admin/class-evie-mega-menu-customize.php';
	new Evie_Mega_Menu_Customize();

}
