<?php
/**
 * Child theme functions.
 *
 * @link https://developer.wordpress.org/themes/advanced-topics/child-themes/
 *
 * @package Evie
 * @version 0.1.1
 */

/**
 * Loads Evie theme text domain.
 *
 * @since 0.1.1 Use the same text domain as the parent theme to make it easy for translation.
 */
function evie_child_after_setup_theme() {

	load_child_theme_textdomain( 'evie', get_stylesheet_directory() . '/languages' );

}

add_action( 'after_setup_theme', 'evie_child_after_setup_theme' );

/**
 * Enqueues Child theme scripts and styles.
 */
function evie_child_enqueue_scripts_and_styles() {

	wp_enqueue_style( 'evie-child', get_stylesheet_uri(), array( 'evie' ), wp_get_theme()->get( 'Version' ) );

}

add_action( 'wp_enqueue_scripts', 'evie_child_enqueue_scripts_and_styles' );
