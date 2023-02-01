<?php
/**
 * Elements Module
 *
 * @package    Flextension
 * @subpackage Modules/Elements
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Registers the Elements module.
 */
flextension_register_module(
	__FILE__,
	array(
		'title'         => esc_html__( 'Basic Elements', 'flextension' ),
		'description'   => esc_html__( 'Adds basic blocks and widgets to your WordPress website.', 'flextension' ),
		'category'      => esc_html__( 'Content', 'flextension' ),
		'enabled'       => true,
		'dependencies'  => array( 'carousel', 'tabs', 'editor' ),
		'load_callback' => 'flextension_elements_module_load',
	)
);

/**
 * Loads the Elements module.
 */
function flextension_elements_module_load() {
	// Public functions.
	require_once plugin_dir_path( __FILE__ ) . 'flextension-elements.php';

	// Registers all widgets in the folder 'widgets'.
	flextension_elements_register_widgets();

	// Registers all blocks in the folder 'blocks'.
	flextension_elements_register_blocks();
}
