<?php
/**
 * Automatic Updates Module
 *
 * Enables automatic updates for the Evie theme.
 * You might be promted to install the Envato Market plugin after you activate this module.
 *
 * @package    Evie_XT
 * @subpackage Modules/Auto_Update
 * @version    1.0.0
 */

/**
 * Returns whether the Envato Market plugin is active.
 *
 * @return bool Whether the Envato Market plugin is active.
 */
function evie_is_auto_update_plugin_active() {
	return function_exists( 'envato_market' );
}

/**
 * Registers the Auto Update module.
 */
flextension_register_module(
	__FILE__,
	array(
		'title'               => esc_html__( 'Automatic Updates', 'evie-xt' ),
		'description'         => esc_html__( 'Enables automatic updates for the Evie theme.', 'evie-xt' ),
		'category'            => esc_html__( 'Utility', 'evie-xt' ),
		'links'               => array(
			array(
				'text'   => esc_html__( 'View documentation', 'evie-xt' ),
				'url'    => evie_get_doc_url( 'automatic-update' ),
				'target' => '_blank',
			),
		),
		'enabled'             => evie_is_auto_update_plugin_active(),
		'actions'             => evie_auto_update_actions(),
		'load_callback'       => 'evie_auto_update_module_load',
		'activate_callback'   => 'evie_auto_update_module_activate',
		'deactivate_callback' => 'evie_auto_update_module_deactivate',
	)
);

/**
 * Loads the Auto Update module.
 */
function evie_auto_update_module_load() {
	add_filter( 'evie_plugins', 'evie_auto_update_add_plugin' );
}

/**
 * Returns module actions.
 *
 * @return array An array of the module actions.
 */
function evie_auto_update_actions() {
	$actions = array();

	if ( ! evie_is_auto_update_plugin_active() ) {
		$actions['install'] = sprintf(
			'<a href="%s">%s</a>',
			sprintf( '%s?page=install-plugins', admin_url( flextension_get_admin_page( 'themes' ) ) ),
			esc_html__( 'Install Required Plugins', 'evie-xt' )
		);
	} else {
		$actions['settings'] = sprintf(
			'<a href="%s">%s</a>',
			sprintf( '%s?page=%s', admin_url( flextension_get_admin_page( 'admin' ) ), 'envato-market' ),
			esc_html__( 'Settings', 'evie-xt' )
		);
	}

	return $actions;
}

/**
 * Adds the Envato Market plugin to the recommended plugins list.
 *
 * @param array $plugins An array list of the recommended plugins.
 * @return array An array list of the recommended plugins.
 */
function evie_auto_update_add_plugin( $plugins = array() ) {
	$plugins[] = array(
		'name'   => esc_html__( 'Envato Market', 'evie-xt' ),
		'slug'   => 'envato-market',
		'source' => 'https://envato.github.io/wp-envato-market/dist/envato-market.zip',
	);
	return $plugins;
}

/**
 * Activates required plugins after the module is first activated.
 */
function evie_auto_update_module_activate() {
	if ( isset( $GLOBALS['tgmpa'] ) && isset( $GLOBALS['tgmpa']->plugins['envato-market'] ) ) {
		activate_plugins( $GLOBALS['tgmpa']->plugins['envato-market']['file_path'] );
	}
}

/**
 * Deactivates required plugins after the module is deactivated.
 */
function evie_auto_update_module_deactivate() {
	if ( isset( $GLOBALS['tgmpa'] ) && isset( $GLOBALS['tgmpa']->plugins['envato-market'] ) ) {
		deactivate_plugins( $GLOBALS['tgmpa']->plugins['envato-market']['file_path'] );
	}
}
