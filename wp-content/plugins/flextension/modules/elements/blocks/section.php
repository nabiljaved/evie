<?php
/**
 * Counter Block
 *
 * @package    Flextension
 * @subpackage Modules/Elements/Blocks
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Registers the block.
 */
function flextension_block_section_register() {
	register_block_type_from_metadata( plugin_dir_path( __FILE__ ) . 'section' );
}

add_action( 'init', 'flextension_block_section_register' );
