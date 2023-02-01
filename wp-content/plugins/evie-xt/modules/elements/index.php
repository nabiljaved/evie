<?php
/**
 * Elements Module
 *
 * @package    Evie_XT
 * @subpackage Modules/Elements
 * @version    1.0.0
 */

/**
 * Registers the Elements module.
 */
flextension_register_module(
	__FILE__,
	array(
		'name'          => 'evie-elements',
		'title'         => esc_html__( 'Blocks & Widgets', 'evie-xt' ),
		'description'   => esc_html__( 'Adds exclusive blocks and widgets for the Evie theme.', 'evie-xt' ),
		'category'      => esc_html__( 'Content', 'evie-xt' ),
		'links'         => array(
			array(
				'text'   => esc_html__( 'View documentation', 'evie-xt' ),
				'url'    => evie_get_doc_url( 'blocks' ),
				'target' => '_blank',
			),
		),
		'dependencies'  => array( 'carousel', 'tabs', 'editor' ),
		'enabled'       => true,
		'load_callback' => 'evie_elements_module_load',
	)
);

/**
 * Loads the Elements module.
 */
function evie_elements_module_load() {
	// Add a new image size for the featured slider.
	add_image_size( 'evie-large', 690, 690, true );

	// Public functions.
	require_once plugin_dir_path( __FILE__ ) . 'evie-elements.php';

	evie_elements_register_widgets();

	evie_elements_register_blocks();
}
