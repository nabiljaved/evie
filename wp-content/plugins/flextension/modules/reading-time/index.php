<?php
/**
 * Reading Time Module
 *
 * Displays an estimated reading time of the post.
 *
 * @package    Flextension
 * @subpackage Modules/Reading_Time
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Registers the Reading Time module.
 */
flextension_register_module(
	__FILE__,
	array(
		'title'         => esc_html__( 'Reading Time', 'flextension' ),
		'description'   => esc_html__( 'Displays an estimated reading time of the post.', 'flextension' ),
		'category'      => esc_html__( 'Post', 'flextension' ),
		'load_callback' => 'flextension_reading_time_module_load',
	)
);

/**
 * Loads the Reading Time module.
 */
function flextension_reading_time_module_load() {
	// Public functions.
	require_once plugin_dir_path( __FILE__ ) . 'flextension-reading-time.php';
}
