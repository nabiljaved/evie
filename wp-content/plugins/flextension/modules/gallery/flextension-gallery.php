<?php
/**
 * Gallery
 *
 * @package    Flextension
 * @subpackage Modules/Gallery
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Registers scripts and stylesheets.
 */
function flextension_gallery_register_scripts() {

	wp_register_style( 'flextension-gallery-block', plugins_url( 'css/style.css', __FILE__ ), array( 'flextension' ), flextension_get_setting( 'version' ) );
	wp_style_add_data( 'flextension-gallery-block', 'rtl', 'replace' );

	wp_register_style( 'flextension-gallery-block-editor', plugins_url( 'css/block-editor.css', __FILE__ ), array( 'flextension', 'flextension-gallery-block' ), flextension_get_setting( 'version' ) );
	wp_style_add_data( 'flextension-gallery-block-editor', 'rtl', 'replace' );

	wp_register_script( 'flextension-gallery-block', plugins_url( 'js/index.js', __FILE__ ), array( 'flextension-lightbox', 'flextension-carousel', 'imagesloaded' ), flextension_get_setting( 'version' ), true );

	wp_register_script(
		'flextension-gallery-block-editor',
		plugins_url( 'js/block-editor.js', __FILE__ ),
		array( 'wp-blob', 'wp-block-editor', 'wp-blocks', 'wp-components', 'wp-compose', 'wp-core-data', 'wp-data', 'wp-element', 'wp-hooks', 'wp-i18n', 'wp-keycodes', 'wp-primitives', 'wp-viewport', 'flextension', 'flextension-carousel', 'imagesloaded' ),
		flextension_get_setting( 'version' ),
		true
	);

	register_block_type_from_metadata( plugin_dir_path( __FILE__ ) . 'blocks/carousel-gallery' );

	register_block_type_from_metadata( plugin_dir_path( __FILE__ ) . 'blocks/waterfall-gallery' );
}

add_action( 'init', 'flextension_gallery_register_scripts' );

/**
 * Loads the scripts and stylesheets for the block editor.
 */
function flextension_gallery_enqueue_block_editor_assets() {

	wp_enqueue_style( 'flextension-gallery-block' );

	wp_enqueue_style( 'flextension-gallery-block-editor' );

	wp_enqueue_script( 'flextension-gallery-block-editor' );

}

add_action( 'enqueue_block_editor_assets', 'flextension_gallery_enqueue_block_editor_assets' );

/**
 * Enqueues the scripts and stylesheets for the module.
 */
function flextension_gallery_enqueue_scripts() {

	wp_enqueue_style( 'flextension-gallery-block' );

	wp_enqueue_script( 'flextension-gallery-block' );

}

add_action( 'wp_enqueue_scripts', 'flextension_gallery_enqueue_scripts' );
