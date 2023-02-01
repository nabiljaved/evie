<?php
/**
 * Scrolling Text Block
 *
 * @package    Evie_XT
 * @subpackage Modules/Elements/Blocks
 * @version    1.0.0
 */

/**
 * Registers the block.
 */
function evie_block_scrolling_text_register() {
	register_block_type_from_metadata( plugin_dir_path( __FILE__ ) . 'scrolling-text' );
}

add_action( 'init', 'evie_block_scrolling_text_register' );
