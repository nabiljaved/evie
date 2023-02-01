<?php
/**
 * Adobe Fonts
 *
 * @package    Flextension
 * @subpackage Modules/Adobe_Fonts
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Registers a new font type.
 *
 * @param array $types An array list of font types.
 * @return array An array list of font types.
 */
function flextension_adobe_fonts_register( $types = array() ) {
	$types['adobe-fonts'] = esc_html__( 'Adobe Fonts', 'flextension' );
	return $types;
}

add_filter( 'flextension_font_types', 'flextension_adobe_fonts_register' );

/**
 * Retrieves response body from Adobe Fonts API.
 *
 * @param string $path Optional. Additional path to retrieve.
 * @return array An associative array of the response body.
 */
function flextension_adobe_fonts_api_fetch( $path = '' ) {
	$settings = flextension_adobe_fonts_settings();

	$token = '';
	if ( ! empty( $settings['api_key'] ) ) {
		$token = $settings['api_key'];
	}

	$url = add_query_arg(
		array(
			'token' => $token,
		),
		'https://typekit.com/api/v1/json/kits/' . $path
	);

	$response = wp_safe_remote_get( $url );

	$body = wp_remote_retrieve_body( $response );

	$data = array();
	if ( ! empty( $body ) ) {
		$data = json_decode( $body, true );
	}
	return $data;
}

/**
 * Retrieves a list of Adobe Fonts projects.
 *
 * @return array An array list of the Adobe Fonts projects.
 */
function flextension_adobe_fonts_projects_list() {
	$cache_key = 'flext_adobe_fonts_projects';

	// Retrieves Adobe Fonts projects from cache.
	$projects = get_transient( $cache_key );
	if ( empty( $projects ) ) {

		$projects = array();

		$data = flextension_adobe_fonts_api_fetch();
		if ( ! empty( $data ) && isset( $data['kits'] ) && ! empty( $data['kits'] ) ) {
			foreach ( $data['kits'] as $kit ) {
				$projects[ $kit['id'] ] = flextension_adobe_fonts_project( $kit['id'] );
			}
		}

		set_transient( $cache_key, $projects, 7 * DAY_IN_SECONDS );
	}

	return $projects;
}

/**
 * Retrieves information about the project including fonts list.
 *
 * @param string $id The project ID.
 * @return array An array object of the project.
 */
function flextension_adobe_fonts_project( $id = '' ) {

	$project = array();

	$path = $id . '/published/';

	$data = flextension_adobe_fonts_api_fetch( $path );
	if ( ! empty( $data ) && isset( $data['kit'] ) && ! empty( $data['kit'] ) ) {
		$fonts = array();
		foreach ( $data['kit']['families'] as $font ) {
			$fonts[ $font['slug'] ] = $font['name'];
		}
		$project = array(
			'name'  => isset( $data['kit']['name'] ) ? $data['kit']['name'] : '',
			'fonts' => $fonts,
		);
	}

	return $project;
}

/**
 * Removes Adobe Fonts caches and reload new data from API.
 */
function flextension_adobe_fonts_clear_cache() {
	delete_transient( 'flext_adobe_fonts_projects' );
}

/**
 * Returns the settings values of the Adobe Fonts module.
 *
 * @return array An array object of the settings.
 */
function flextension_adobe_fonts_settings() {

	$settings = wp_parse_args(
		get_option( 'flext_adobe_fonts', array() ),
		array(
			'api_key' => '',
			'project' => '',
			'fonts'   => array(),
		)
	);

	return $settings;
}

/**
 * Adds selected Adobe Fonts to the fonts list.
 *
 * @param array $fonts An array list of the fonts.
 * @return array An array list of the fonts.
 */
function flextension_adobe_fonts( $fonts = array() ) {
	$settings = flextension_adobe_fonts_settings();
	if ( ! empty( $settings['project'] ) && ! empty( $settings['fonts'] ) ) {
		$projects = flextension_adobe_fonts_projects_list();
		$id       = $settings['project'];
		if ( ! empty( $projects ) && ! empty( $projects[ $id ] ) ) {
			$adobe_fonts = array();
			foreach ( $settings['fonts'] as $slug ) {
				if ( isset( $projects[ $id ]['fonts'] ) && isset( $projects[ $id ]['fonts'][ $slug ] ) ) {
					$adobe_fonts[ $slug ] = $projects[ $settings['project'] ]['fonts'][ $slug ];
				}
			}

			$fonts['adobe-fonts'] = array(
				'title' => esc_html__( 'Adobe Fonts', 'flextension' ),
				'fonts' => $adobe_fonts,
			);
		}
	}
	return $fonts;
}

add_filter( 'flextension_fonts', 'flextension_adobe_fonts' );

/**
 * Registers scripts and stylesheets.
 */
function flextension_adobe_fonts_register_scripts() {
	$settings = flextension_adobe_fonts_settings();
	if ( ! empty( $settings['project'] ) && ! empty( $settings['fonts'] ) ) {
		wp_register_style( 'flextension-adobe-fonts', 'https://use.typekit.net/' . $settings['project'] . '.css', array(), flextension_get_setting( 'version' ) );
	}
}

add_action( 'init', 'flextension_adobe_fonts_register_scripts' );

/**
 * Enqueues the scripts and stylesheets for the module.
 */
function flextension_adobe_fonts_enqueue_styles() {
	wp_enqueue_style( 'flextension-adobe-fonts' );
}

add_action( 'wp_enqueue_scripts', 'flextension_adobe_fonts_enqueue_styles', 5 );

add_action( 'enqueue_block_editor_assets', 'flextension_adobe_fonts_enqueue_styles', 5 );
