<?php
/**
 * Fonts
 *
 * @package    Flextension
 * @subpackage Extensions/Fonts
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Returns an array list of font types.
 *
 * @return array An array list of font types.
 */
function flextension_font_types() {
	/**
	 * Filters the font types.
	 *
	 * @param array $types An array list of font types.
	 */
	return apply_filters( 'flextension_font_types', array() );
}

/**
 * Returns an array list of the fonts.
 *
 * @return array An array list of the fonts.
 */
function flextension_fonts() {
	/**
	 * Filters the array list of fonts.
	 *
	 * @param array $fonts An array list of the fonts.
	 */
	return apply_filters( 'flextension_fonts', array() );
}
