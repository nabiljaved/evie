<?php
/**
 * Google Fonts
 *
 * @package    Flextension
 * @subpackage Modules/Google_Fonts
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Registers a new font type.
 *
 * @param array $types An array list of font types.
 * @return array An array list of font types.
 */
function flextension_google_fonts_register( $types = array() ) {
	$types['google-fonts'] = esc_html__( 'Google Fonts', 'flextension' );
	return $types;
}

add_filter( 'flextension_font_types', 'flextension_google_fonts_register' );

/**
 * Returns default Google Fonts list in case the API key not given.
 *
 * @return array An array list of the Google Fonts.
 */
function flextension_google_fonts_default_list() {
	$fonts    = array();
	$url      = plugins_url( 'js/vendor/google-fonts.min.js', __FILE__ );
	$response = wp_safe_remote_get( $url );
	$body     = wp_remote_retrieve_body( $response );
	if ( ! empty( $body ) ) {
		$items = json_decode( $body, true );
		if ( ! empty( $items ) ) {
			foreach ( $items as $name ) {
				$fonts[ $name ] = $name;
			}
		}
	}
	return $fonts;
}

/**
 * Retrieves a list of Google Fonts.
 *
 * @return array An array list of the Google Fonts.
 */
function flextension_google_fonts_list() {
	$cache_key = 'flext_google_fonts';

	// Retrieves Google Fonts from cache.
	$fonts = get_transient( $cache_key );
	if ( empty( $fonts ) ) {
		$fonts = array();

		$settings = flextension_google_fonts_settings();

		$api_key = $settings['api_key'];

		if ( ! empty( $api_key ) ) {
			$url = add_query_arg(
				array(
					'key' => $api_key,
				),
				'https://www.googleapis.com/webfonts/v1/webfonts'
			);

			$response = wp_safe_remote_get( $url );
			$body     = wp_remote_retrieve_body( $response );
			if ( ! empty( $body ) ) {
				$data = json_decode( $body, true );
				if ( isset( $data['items'] ) && ! empty( $data['items'] ) ) {
					foreach ( $data['items'] as $font ) {
						$fonts[ $font['family'] ] = $font['family'];
					}
				}
			}
		} else {
			$fonts = flextension_google_fonts_default_list();
		}

		set_transient( $cache_key, $fonts, 7 * DAY_IN_SECONDS );
	}

	return $fonts;
}

/**
 * Removes Google Fonts caches and reload new data from API.
 */
function flextension_google_fonts_clear_cache() {
	delete_transient( 'flext_google_fonts' );
}

/**
 * Returns the settings values of the Google Fonts module.
 *
 * @return array An array object of the settings.
 */
function flextension_google_fonts_settings() {
	return wp_parse_args(
		get_option( 'flext_google_fonts', array() ),
		array(
			'api_key' => '',
			'fonts'   => array(),
		)
	);
}

/**
 * Returns the Google Fonts URL.
 *
 * @param array $fonts An array of Google Fonts.
 * @return string The Google fonts URL.
 */
function flextension_google_fonts_url( $fonts = array() ) {

	if ( empty( $fonts ) ) {
		$settings = flextension_google_fonts_settings();

		$fonts = $settings['fonts'];
	}

	if ( empty( $fonts ) ) {
		return '';
	}

	/**
	 * Filters the Google Fonts variants.
	 *
	 * @param array $variants An array list of the font variants.
	 */
	$variants = apply_filters( 'flextension_google_fonts_variants', array( '300', '400', '700' ) );

	$font_families = array();

	$font_variants = '';
	if ( ! empty( $variants ) ) {
		$font_variants = ':' . implode( ',', $variants );
	}

	if ( ! empty( $fonts ) ) {
		foreach ( $fonts as $name ) {
			$font_families[ $name ] = $name . $font_variants;
		}
	}

	$query_args = array();
	if ( ! empty( $font_families ) ) {
		$query_args['family'] = rawurlencode( implode( '|', array_values( $font_families ) ) );
	}

	/**
	 * Filters the Google font subset.
	 *
	 * @param array $subsets An array list of the font subset.
	 */
	$subsets = apply_filters( 'flextension_google_fonts_subset', array( 'latin', 'latin-ext' ) );
	if ( ! empty( $subsets ) ) {
		$query_args['subset'] = rawurlencode( implode( ',', $subsets ) );
	}

	$fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );

	return $fonts_url;
}

/**
 * Adds preconnect for Google Fonts.
 *
 * @param array  $urls          URLs to print for resource hints.
 * @param string $relation_type The relation type the URLs are printed.
 * @return array $urls          URLs to print for resource hints.
 */
function flextension_google_fonts_add_resource_hints( $urls, $relation_type ) {
	if ( 'preconnect' === $relation_type ) {
		$urls[] = array(
			'href' => 'https://fonts.googleapis.com',
		);

		$urls[] = array(
			'href' => 'https://fonts.gstatic.com',
			'crossorigin',
		);
	}

	return $urls;
}

/**
 * Adds selected Google Fonts to the fonts list.
 *
 * @param array $fonts An array list of the fonts.
 * @return array An array list of the fonts.
 */
function flextension_google_fonts( $fonts = array() ) {
	$settings = flextension_google_fonts_settings();
	if ( ! empty( $settings['fonts'] ) ) {
		$google_fonts = array();
		foreach ( $settings['fonts'] as $name ) {
			$google_fonts[ $name ] = $name;
		}

		$fonts['google-fonts'] = array(
			'title' => esc_html__( 'Google Fonts', 'flextension' ),
			'fonts' => $google_fonts,
		);
	}
	return $fonts;
}

add_filter( 'flextension_fonts', 'flextension_google_fonts' );

/**
 * Registers scripts and stylesheets.
 */
function flextension_google_fonts_register_scripts() {
	$url = flextension_google_fonts_url();
	if ( ! empty( $url ) ) {

		add_filter( 'wp_resource_hints', 'flextension_google_fonts_add_resource_hints', 10, 2 );

		wp_register_style( 'flextension-google-fonts', $url, array(), flextension_get_setting( 'version' ) );
	}
}

add_action( 'init', 'flextension_google_fonts_register_scripts' );

/**
 * Enqueues the scripts and stylesheets.
 */
function flextension_google_fonts_enqueue_styles() {
	wp_enqueue_style( 'flextension-google-fonts' );
}

add_action( 'wp_enqueue_scripts', 'flextension_google_fonts_enqueue_styles', 5 );

add_action( 'enqueue_block_editor_assets', 'flextension_google_fonts_enqueue_styles', 5 );
