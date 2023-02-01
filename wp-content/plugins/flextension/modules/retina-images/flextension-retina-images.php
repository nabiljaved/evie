<?php
/**
 * Retina Images
 *
 * @package    Flextension
 * @subpackage Modules/Retina_Images
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Returns the settings values of the Retina Images module.
 *
 * @return array An array object of the settings.
 */
function flextension_retina_images_settings() {
	$settings = get_option( 'flext_retina_images', array() );

	if ( empty( $settings ) ) {
		$settings = array(
			'sizes' => array_keys( flextension_retina_images_available_sizes() ),
		);
	}

	return $settings;
}

/**
 * Returns the available image sizes.
 */
function flextension_retina_images_available_sizes() {

	// Exclude unnecessary.
	$excluded_sizes = flextension_retina_images_excluded_sizes();

	/**
	 * Since WordPress 5.3, two retina sizes have been added for medium_large and large.
	 * so that we don't need to create a retina version for them.
	 */
	$excluded_sizes = array_merge( $excluded_sizes, array( 'medium_large', 'large', '1536x1536', '2048x2048' ) );

	$images_sizes = wp_get_registered_image_subsizes();

	foreach ( $excluded_sizes as $size ) {
		if ( isset( $images_sizes[ $size ] ) ) {
			unset( $images_sizes[ $size ] );
		}
	}

	foreach ( $images_sizes as $size => $size_data ) {
		if ( preg_match( '/flext-lqip/', $size ) ) {
			unset( $images_sizes[ $size ] );
		}
	}

	/**
	 * Filters the list of the available image sizes.
	 *
	 * @param array $images_sizes An array list of the available image sizes.
	 */
	return apply_filters( 'flextension_retina_images_available_sizes', $images_sizes );
}

/**
 * Returns the excluded image sizes to create retina images.
 *
 * @return array $default_sizes An array list of the excluded image sizes.
 */
function flextension_retina_images_excluded_sizes() {
	$settings = flextension_get_theme_support(
		'retina-images',
		array(
			'exclude' => array(),
		)
	);

	$exclude = array();

	if ( isset( $settings['exclude'] ) && is_array( $settings['exclude'] ) ) {
		$exclude = $settings['exclude'];
	}

	return $exclude;
}

/**
 * Registers retina sizes.
 */
function flextension_retina_images_register_retina_sizes() {
	global $pagenow;

	// Do not add image sizes on the media settings page.
	if ( flextension_get_admin_page( 'media' ) === $pagenow || 'options.php' === $pagenow ) {
		return;
	}

	$image_sizes = flextension_retina_images_available_sizes();

	$settings = flextension_retina_images_settings();

	if ( empty( $settings['sizes'] ) ) {
		return;
	}

	// Register new retina sizes.
	foreach ( $image_sizes as $size => $size_data ) {
		if ( in_array( $size, $settings['sizes'], true ) ) {

			$new_width  = (int) $size_data['width'] * 2;
			$new_height = (int) $size_data['height'] * 2;

			add_image_size( $size . '-2x', $new_width, $new_height, $size_data['crop'] );
		}
	}
}

add_action( 'init', 'flextension_retina_images_register_retina_sizes', 20 );
