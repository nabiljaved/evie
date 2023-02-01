<?php
/**
 * Evie Customizer
 *
 * @package    Evie
 * @subpackage Customizer
 * @version    1.0.0
 */

/**
 * Add custom controls in the Theme Customizer section.
 */
require get_parent_theme_file_path( 'inc/customizer/controls/class-evie-customize-dropdown-control.php' ); // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
require get_parent_theme_file_path( 'inc/customizer/controls/class-evie-customize-label-control.php' ); // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
require get_parent_theme_file_path( 'inc/customizer/controls/class-evie-customize-range-control.php' ); // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
require get_parent_theme_file_path( 'inc/customizer/controls/class-evie-customize-html-control.php' ); // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
require get_parent_theme_file_path( 'inc/customizer/controls/class-evie-customize-separator-control.php' ); // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
require get_parent_theme_file_path( 'inc/customizer/controls/class-evie-customize-unit-control.php' ); // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound

/**
 * Add custom sanitizer in the Theme Customizer section.
 */
require get_parent_theme_file_path( 'inc/customizer/customize-sanitizer.php' ); // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound

/**
 * Include customize settings.
 */
require get_parent_theme_file_path( 'inc/customizer/sections/site-identity.php' ); // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
require get_parent_theme_file_path( 'inc/customizer/sections/colors.php' ); // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
require get_parent_theme_file_path( 'inc/customizer/sections/navigation.php' ); // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
require get_parent_theme_file_path( 'inc/customizer/sections/header.php' ); // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
require get_parent_theme_file_path( 'inc/customizer/sections/background.php' ); // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
require get_parent_theme_file_path( 'inc/customizer/sections/loader.php' ); // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
require get_parent_theme_file_path( 'inc/customizer/sections/footer.php' ); // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
require get_parent_theme_file_path( 'inc/customizer/sections/blog.php' ); // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
require get_parent_theme_file_path( 'inc/customizer/sections/typography.php' ); // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound

/**
 * Loads dynamic logic for the customizer controls area.
 */
function evie_customizer_controls_scripts() {
	$theme_version = wp_get_theme()->get( 'Version' );

	wp_enqueue_style( 'evie-customize-controls', get_theme_file_uri( 'assets/css/customize-controls.css' ), array(), $theme_version );
	wp_style_add_data( 'evie-customize-controls', 'rtl', 'replace' );

	wp_enqueue_script( 'evie-customize-controls', get_theme_file_uri( 'assets/js/customize-controls.js' ), array( 'customize-base', 'customize-controls', 'underscore', 'jquery', 'editor' ), $theme_version, true );
}

add_action( 'customize_controls_enqueue_scripts', 'evie_customizer_controls_scripts' );

/**
 * Binds JS handlers to instantly live-preview changes.
 */
function evie_customizer_preview_scripts() {
	$theme_version = wp_get_theme()->get( 'Version' );

	wp_enqueue_style( 'evie-customize-preview', get_theme_file_uri( 'assets/css/customize-preview.css' ), array( 'wp-block-library', 'customize-preview', 'evie' ), $theme_version );
	wp_style_add_data( 'evie-customize-preview', 'rtl', 'replace' );

	wp_enqueue_script( 'evie-customize-preview', get_theme_file_uri( 'assets/js/customize-preview.js' ), array( 'customize-preview', 'customize-selective-refresh', 'evie' ), $theme_version, true );

}

add_action( 'customize_preview_init', 'evie_customizer_preview_scripts' );
