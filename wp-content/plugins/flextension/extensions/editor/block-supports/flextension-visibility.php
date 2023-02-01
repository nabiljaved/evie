<?php
/**
 * Flextension Visibility block support.
 *
 * @package    Flextension
 * @subpackage Extensions/Block_Supports
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Registers the Visibility block attributes for block types that support it.
 *
 * @access private
 *
 * @param WP_Block_Type $block_type Block Type.
 */
function flextension_visibility_support_register( $block_type ) {
	$has_support = flextension_has_block_support( $block_type, 'flextensionVisibility' );

	if ( true === $has_support ) {
		if ( ! $block_type->attributes ) {
			$block_type->attributes = array();
		}

		if ( ! array_key_exists( 'flextHidden', $block_type->attributes ) ) {
			$block_type->attributes['flextHidden'] = array(
				'type' => 'boolean',
			);
		}

		if ( ! array_key_exists( 'flextHiddenTablet', $block_type->attributes ) ) {
			$block_type->attributes['flextHiddenTablet'] = array(
				'type' => 'boolean',
			);
		}

		if ( ! array_key_exists( 'flextHiddenMobile', $block_type->attributes ) ) {
			$block_type->attributes['flextHiddenMobile'] = array(
				'type' => 'boolean',
			);
		}
	}
}

// Register the block support.
WP_Block_Supports::get_instance()->register(
	'flextension-visibility',
	array(
		'register_attribute' => 'flextension_visibility_support_register',
	)
);
