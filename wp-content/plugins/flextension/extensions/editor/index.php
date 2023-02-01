<?php
/**
 * Editor Extension
 *
 * Adds additional features to the Block Editor.
 *
 * @package    Flextension
 * @subpackage Extensions/Editor
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Registers the Editor extension.
 */
flextension_register_module(
	__FILE__,
	array(
		'public'        => false,
		'type'          => 'extension',
		'load_callback' => 'flextension_editor_module_load',
	)
);

/**
 * Loads the Editor extension.
 */
function flextension_editor_module_load() {

	// Public functions.
	require_once plugin_dir_path( __FILE__ ) . 'flextension-editor.php';

	flextension_load_files( 'block-supports/*.php', plugin_dir_path( __FILE__ ) );
}
