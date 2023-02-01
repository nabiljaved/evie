<?php
/**
 * Portfolio Customizer
 *
 * @package    Evie
 * @subpackage Customizer
 * @version    1.0.0
 */

/**
 * Include customize settings.
 */
require get_parent_theme_file_path( 'inc/customizer/sections/portfolio.php' ); // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound

/**
 * Loads dynamic logic for the customizer controls area.
 */
function evie_customizer_portfolio_controls_scripts() {
	$theme_version = wp_get_theme()->get( 'Version' );

	wp_enqueue_script( 'evie-portfolio-customize-controls', get_theme_file_uri( 'assets/js/portfolio-customize-controls.js' ), array( 'jquery', 'jquery-ui-core' ), $theme_version, true );

}

add_action( 'customize_controls_enqueue_scripts', 'evie_customizer_portfolio_controls_scripts' );

/**
 * Binds JS handlers to instantly live-preview changes.
 */
function evie_customizer_portfolio_preview_scripts() {
	$theme_version = wp_get_theme()->get( 'Version' );

	wp_enqueue_script( 'evie-portfolio-customize-preview', get_theme_file_uri( 'assets/js/portfolio-customize-preview.js' ), array( 'customize-preview', 'customize-selective-refresh', 'jquery', 'evie' ), $theme_version, true );
}

add_action( 'customize_preview_init', 'evie_customizer_portfolio_preview_scripts' );
