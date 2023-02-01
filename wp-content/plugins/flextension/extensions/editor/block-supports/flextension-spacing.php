<?php
/**
 * Flextension Spacing block support.
 *
 * @package    Flextension
 * @subpackage Extensions/Block_Supports
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Registers the Animation block attributes for block types that support it.
 *
 * @access private
 *
 * @param WP_Block_Type $block_type Block Type.
 */
function flextension_spacing_support_register( $block_type ) {
	$has_support = flextension_has_block_support( $block_type, 'flextensionSpacing' );

	if ( true === $has_support ) {
		if ( ! $block_type->attributes ) {
			$block_type->attributes = array();
		}

		if ( ! array_key_exists( 'flextMargin', $block_type->attributes ) ) {
			$block_type->attributes['flextMargin'] = array(
				'type' => 'object',
			);
		}

		if ( ! array_key_exists( 'flextMarginTablet', $block_type->attributes ) ) {
			$block_type->attributes['flextMarginTablet'] = array(
				'type' => 'object',
			);
		}

		if ( ! array_key_exists( 'flextMarginMobile', $block_type->attributes ) ) {
			$block_type->attributes['flextMarginMobile'] = array(
				'type' => 'object',
			);
		}

		if ( ! array_key_exists( 'flextPadding', $block_type->attributes ) ) {
			$block_type->attributes['flextPadding'] = array(
				'type' => 'object',
			);
		}

		if ( ! array_key_exists( 'flextPaddingTablet', $block_type->attributes ) ) {
			$block_type->attributes['flextPaddingTablet'] = array(
				'type' => 'object',
			);
		}

		if ( ! array_key_exists( 'flextPaddingMobile', $block_type->attributes ) ) {
			$block_type->attributes['flextPaddingMobile'] = array(
				'type' => 'object',
			);
		}
	}
}

// Register the block support.
WP_Block_Supports::get_instance()->register(
	'flextension-spacing',
	array(
		'register_attribute' => 'flextension_spacing_support_register',
	)
);
