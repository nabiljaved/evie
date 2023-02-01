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
function flextension_block_counter_register() {
	register_block_type_from_metadata( plugin_dir_path( __FILE__ ) . 'counter' );
}

add_action( 'init', 'flextension_block_counter_register' );
