<?php
/**
 * Flextension classname block support.
 *
 * @package    Flextension
 * @subpackage Extensions/Block_Supports
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Registers the custom classname block attribute for block types that support it.
 *
 * @access private
 *
 * @param WP_Block_Type $block_type Block Type.
 */
function flextension_classname_support_register( $block_type ) {
	$has_support = flextension_has_block_support( $block_type, 'flextensionSpacing' ) || flextension_has_block_support( $block_type, 'flextensionVisibility' );

	if ( true === $has_support ) {
		if ( ! $block_type->attributes ) {
			$block_type->attributes = array();
		}

		if ( ! array_key_exists( 'flextClassName', $block_type->attributes ) ) {
			$block_type->attributes['flextClassName'] = array(
				'type' => 'string',
			);
		}
	}
}

/**
 * Adds the custom classnames to the output.
 *
 * @access private
 *
 * @param  WP_Block_Type $block_type       Block Type.
 * @param  array         $block_attributes Block attributes.
 *
 * @return array Block CSS classes and inline styles.
 */
function flextension_classname_support_apply( $block_type, $block_attributes ) {
	$attributes = array();

	if ( flextension_is_context_editor() ) {
		return $attributes;
	}

	$has_support = flextension_has_block_support( $block_type, 'flextensionSpacing' ) || flextension_has_block_support( $block_type, 'flextensionVisibility' );

	if ( true === $has_support ) {
		if ( isset( $block_attributes['flextClassName'] ) && ! empty( $block_attributes['flextClassName'] ) ) {
			$attributes['class'] = $block_attributes['flextClassName'];
		}
	}

	return $attributes;
}

// Register the block support.
WP_Block_Supports::get_instance()->register(
	'flextension-classname',
	array(
		'register_attribute' => 'flextension_classname_support_register',
		'apply'              => 'flextension_classname_support_apply',
	)
);
