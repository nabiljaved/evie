<?php
/**
 * Demo Content Module
 *
 * Provides predefined demo content from the Evie theme
 * so you can easily select which demo you want to import.
 *
 * @package    Evie_XT
 * @subpackage Modules/Demo_Content
 * @version    1.0.0
 */

/**
 * Registers the Demo Content module.
 */
flextension_register_module(
	__FILE__,
	array(
		'title'         => esc_html__( 'Demo Content', 'evie-xt' ),
		'description'   => esc_html__( 'Includes predefined demo content from the theme.', 'evie-xt' ),
		'category'      => esc_html__( 'Content', 'evie-xt' ),
		'links'         => array(
			array(
				'text'   => esc_html__( 'View documentation', 'evie-xt' ),
				'url'    => evie_get_doc_url( 'demo-content' ),
				'target' => '_blank',
			),
		),
		'actions'       => evie_demo_content_get_actions(),
		'load_callback' => 'evie_demo_content_module_load',
	)
);

/**
 * Loads the Demo Content module.
 */
function evie_demo_content_module_load() {
	add_filter( 'evie_plugins', 'evie_demo_content_plugins' );
}

/**
 * Returns module actions.
 *
 * @return array An array of the module actions.
 */
function evie_demo_content_get_actions() {
	$actions = array();

	if ( class_exists( 'OCDI_Plugin', false ) ) {
		$actions['import'] = sprintf(
			'<a href="%s">%s</a>',
			sprintf( '%s?page=evie-demo-content', admin_url( flextension_get_admin_page( 'themes' ) ) ),
			esc_html__( 'Import Demo', 'evie-xt' )
		);
	} else {
		$actions['install'] = sprintf(
			'<a href="%s">%s</a>',
			sprintf( '%s?page=install-plugins', admin_url( flextension_get_admin_page( 'themes' ) ) ),
			esc_html__( 'Install Required Plugins', 'evie-xt' )
		);
	}

	return $actions;
}

/**
 * Adds the One Click Demo Import plugin to the recommended plugins list.
 *
 * @param array $plugins An array list of the recommended plugins.
 * @return array An array list of the recommended plugins.
 */
function evie_demo_content_plugins( $plugins = array() ) {
	$plugins[] = array(
		'name'               => esc_html__( 'One Click Demo Import', 'evie-xt' ),
		'slug'               => 'one-click-demo-import',
		'required'           => true,
		'force_deactivation' => true,
	);
	return $plugins;
}

if ( class_exists( 'OCDI_Plugin' ) ) {
	require_once plugin_dir_path( __FILE__ ) . 'evie-demo-content.php';
}
