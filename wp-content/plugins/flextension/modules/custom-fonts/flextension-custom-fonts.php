<?php
/**
 * Custom Fonts
 *
 * @package    Flextension
 * @subpackage Modules/Custom_Fonts
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Registers a new font type.
 *
 * @param array $types An array list of font types.
 * @return array An array list of font types.
 */
function flextension_custom_fonts_register( $types = array() ) {
	$types['custom-fonts'] = esc_html__( 'Custom Fonts', 'flextension' );
	return $types;
}

add_filter( 'flextension_font_types', 'flextension_custom_fonts_register', 20 );

/**
 * Returns the settings values of the Custom Fonts module.
 *
 * @return array An array object of the settings.
 */
function flextension_custom_fonts_settings() {
	return wp_parse_args(
		get_option( 'flext_custom_fonts', array() ),
		array(
			'fonts' => array(),
		)
	);
}

/**
 * Adds selected Custom Fonts to the fonts list.
 *
 * @param array $fonts An array list of the fonts.
 * @return array An array list of the fonts.
 */
function flextension_custom_fonts( $fonts = array() ) {
	$settings = flextension_custom_fonts_settings();
	if ( ! empty( $settings['fonts'] ) ) {
		$custom_fonts = array();
		foreach ( $settings['fonts'] as $key => $font ) {
			$custom_fonts[ $font['name'] ] = $font['name'];
		}

		$fonts['custom-fonts'] = array(
			'title' => esc_html__( 'Custom Fonts', 'flextension' ),
			'fonts' => $custom_fonts,
		);
	}
	return $fonts;
}

add_filter( 'flextension_fonts', 'flextension_custom_fonts', 20 );

/**
 * Generates CSS for custom fonts.
 */
function flextension_custom_fonts_css() {

	$custom_styles = array();

	$settings = flextension_custom_fonts_settings();
	if ( ! empty( $settings['fonts'] ) ) {
		foreach ( $settings['fonts'] as $font ) {
			if ( ! empty( $font['files'] ) ) {
				foreach ( $font['files'] as $key => $file ) {
					$font_styles = array( 'font-family: "' . $font['name'] . '"' );

					if ( ! empty( $file['style'] ) ) {
						$font_styles[] = 'font-style: ' . $file['style'];
					}

					if ( ! empty( $file['weight'] ) ) {
						$font_styles[] = 'font-weight:' . $file['weight'];
					}

					$urls = array( 'local("")' );
					if ( ! empty( $file['woff2'] ) ) {
						$urls[] = 'url("' . esc_url( wp_get_attachment_url( $file['woff2'] ) ) . '") format("woff2")';
					}

					if ( ! empty( $file['woff'] ) ) {
						$urls[] = 'url("' . esc_url( wp_get_attachment_url( $file['woff'] ) ) . '") format("woff")';
					}

					$font_styles[] = 'src:' . implode( ',', $urls );

					$custom_styles[] = '@font-face {' . implode( ';', $font_styles ) . ';}';
				}
			}
		}
	}

	$css = '';
	if ( ! empty( $custom_styles ) ) {
		$css = implode( ' ', $custom_styles );
	}

	return $css;
}

/**
 * Prints out the CSS for custom fonts.
 */
function flextension_custom_fonts_print_styles() {
	$custom_fonts_css = flextension_custom_fonts_css();
	if ( ! empty( $custom_fonts_css ) ) {
		wp_add_inline_style( 'flextension', $custom_fonts_css );
	}
}

add_action( 'wp_enqueue_scripts', 'flextension_custom_fonts_print_styles' );

add_action( 'enqueue_block_editor_assets', 'flextension_custom_fonts_print_styles' );
