<?php
/**
 * Flextension Animation block support.
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
function flextension_animation_support_register( $block_type ) {

	$has_support = flextension_has_block_support( $block_type, 'flextensionAnimation' );

	if ( true === $has_support ) {
		if ( ! $block_type->attributes ) {
			$block_type->attributes = array();
		}

		if ( ! array_key_exists( 'flextAnimation', $block_type->attributes ) ) {
			$block_type->attributes['flextAnimation'] = array(
				'type' => 'string',
			);
		}

		if ( ! array_key_exists( 'flextAnimationDelay', $block_type->attributes ) ) {
			$block_type->attributes['flextAnimationDelay'] = array(
				'type'    => 'number',
				'default' => 0,
			);
		}

		if ( ! array_key_exists( 'flextAnimationOnce', $block_type->attributes ) ) {
			$block_type->attributes['flextAnimationOnce'] = array(
				'type'    => 'boolean',
				'default' => true,
			);
		}
	}
}

/**
 * Adds the Animation classnames to the output.
 *
 * @access private
 *
 * @param  WP_Block_Type $block_type       Block Type.
 * @param  array         $block_attributes Block attributes.
 *
 * @return array Block CSS classes and inline styles.
 */
function flextension_animation_support_apply( $block_type, $block_attributes ) {
	$attributes = array();

	if ( flextension_is_context_editor() ) {
		return $attributes;
	}

	$has_support = flextension_has_block_support( $block_type, 'flextensionAnimation' );

	if ( true === $has_support ) {
		$classes = array();

		if ( isset( $block_attributes['flextAnimation'] ) && ! empty( $block_attributes['flextAnimation'] ) ) {
			$classes[] = 'flext-has-animation';
			$classes[] = 'flext-animation-' . $block_attributes['flextAnimation'];

			if ( isset( $block_attributes['flextAnimationDelay'] ) && floatval( $block_attributes['flextAnimationDelay'] ) > 0.0 ) {
				$classes[] = 'flext-animation-delay-' . ( floatval( $block_attributes['flextAnimationDelay'] ) * 1000 );
			}

			if ( ! isset( $block_attributes['flextAnimationOnce'] ) || ( isset( $block_attributes['flextAnimationOnce'] ) && false === $block_attributes['flextAnimationOnce'] ) ) {
				$classes[] = 'flext-animation-once';
			}
		}

		if ( ! empty( $classes ) ) {
			$attributes['class'] = implode( ' ', $classes );
		}
	}

	return $attributes;
}

// Register the block support.
WP_Block_Supports::get_instance()->register(
	'flextension-animation',
	array(
		'register_attribute' => 'flextension_animation_support_register',
		'apply'              => 'flextension_animation_support_apply',
	)
);
