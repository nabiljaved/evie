<?php
/**
 * Helper functions for the plugin.
 *
 * @package Evie_XT
 * @version 1.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Returns the URL of the documentation.
 *
 * @param string $path Path to the section.
 * @return string URL of the documentation.
 */
function evie_get_doc_url( $path = '' ) {
	return flextension_get_doc_url( $path );
}

/**
 * Retrieves the path of the highest priority template file that exists.
 *
 * This is the load order:
 *
 * yourtheme/<template_path>/$template_name
 * yourtheme/$template_name
 * $default_path/$template_name
 *
 * @param string $slug The slug name for the generic template.
 * @param string $name The name of the specialized template.
 * @return string
 */
function evie_locate_template( $slug, $name = null ) {

	$template_name = basename( $slug );

	$templates = array();

	/**
	 * Filters the template directory path.
	 *
	 * @param string $path The template directory path. Default 'template-parts'.
	 */
	$template_dir_path = apply_filters( 'evie_template_dir', 'template-parts' );

	if ( ! empty( $name ) ) {
		$templates[] = trailingslashit( $template_dir_path ) . "{$template_name}-{$name}.php";
	}

	$templates[] = trailingslashit( $template_dir_path ) . "{$template_name}.php";

	$template = locate_template( $templates );

	if ( ! $template ) {
		$template = evie_get_default_template( $slug, $name );
	}

	$template = wp_normalize_path( $template );

	/**
	 * Filters the path of the template file.
	 *
	 * @param string $template      The path of the template file.
	 * @param string $template_name The template name.
	 */
	return apply_filters( 'evie_locate_template', $template, $template_name );
}

/**
 * Loads the template file with query variables.
 *
 * @param string $template_slug The slug name for the generic template.
 * @param string $template_name The name of template to load.
 * @param array  $args          Array of the variables. (default: array).
 */
function evie_get_template( $template_slug, $template_name = null, $args = array() ) {
	$cache_key = sanitize_key( implode( '-', array( 'template', $template_slug, $template_name, EVIE_XT_VERSION ) ) );

	$template = (string) wp_cache_get( $cache_key, 'flextension' );
	if ( empty( $template ) ) {
		$template = evie_locate_template( $template_slug, $template_name );
		wp_cache_set( $cache_key, $template, 'flextension', HOUR_IN_SECONDS );
	}

	if ( ! empty( $template ) ) {
		include $template;
	}

}

/**
 * Retrieves default template path.
 *
 * @param string $slug The slug name for the generic template.
 * @param string $name The name of the specialized template.
 */
function evie_get_default_template( $slug, $name = null ) {

	$templates = array();

	if ( ! empty( $name ) ) {
		$templates[] = "{$slug}-{$name}.php";
	}

	$templates[] = "{$slug}.php";

	$template = '';

	foreach ( $templates as $template_file ) {
		if ( file_exists( $template_file ) ) {
			$template = $template_file;
			break;
		}
	}

	return $template;
}

/**
 * Determines whether the current request is a WordPress REST or Ajax request.
 *
 * @return bool Whether the current request is a WordPress REST or Ajax request.
 */
function evie_doing_request() {
	if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
		return true;
	}
	if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
		return true;
	}
}
