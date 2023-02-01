<?php
/**
 * Router Extension
 *
 * @package    Evie_XT
 * @subpackage Extensions/Router
 * @version    1.0.0
 */

/**
 * Registers the Router extension.
 */
flextension_register_module(
	__FILE__,
	array(
		'public'        => false,
		'type'          => 'extension',
		'dependencies'  => array( 'api' ),
		'load_callback' => 'evie_router_module_load',
	)
);

/**
 * Loads the Router extension.
 */
function evie_router_module_load() {
	add_filter( 'evie_settings', 'evie_router_add_settings' );

	add_action( 'wp_enqueue_scripts', 'evie_router_enqueue_scripts' );
}

/**
 * Appends settings to the current plugin settings.
 *
 * @param array $settings The current settings of the plugin.
 * @return array An array list of the plugin settings.
 */
function evie_router_add_settings( $settings = array() ) {

	$settings['router'] = array(
		'root' => esc_url_raw( untrailingslashit( home_url() ) ),
	);

	$permalink_structure = get_option( 'permalink_structure' );

	if ( ! empty( $permalink_structure ) ) {
		$settings['router']['permalink'] = true;

		$taxonomies = get_taxonomies(
			array(
				'publicly_queryable' => 1,
			),
			'objects'
		);

		foreach ( $taxonomies as $taxonomy ) {
			if ( ! empty( $taxonomy->query_var ) ) {
				$slug = empty( $taxonomy->rewrite ) ? $taxonomy->name : $taxonomy->rewrite['slug'];

				$settings['router']['taxonomies'][ $taxonomy->query_var ] = $slug;
			}
		}
	}

	return $settings;
}

/**
 * Enqueues the scripts and stylesheets.
 */
function evie_router_enqueue_scripts() {

	wp_enqueue_script( 'evie-router', plugins_url( 'js/index.js', __FILE__ ), array( 'evie' ), EVIE_XT_VERSION, true );

}
