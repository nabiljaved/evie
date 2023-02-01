<?php
/**
 * WooCommerce Extension
 *
 * @package    Evie_XT
 * @subpackage Modules/WooCommerce
 * @version    1.0.0
 */

/**
 * Returns whether the WooCommerce plugin is active.
 *
 * @return bool Whether the WooCommerce plugin is active.
 */
function evie_is_wc_plugin_active() {
	return class_exists( 'WooCommerce', false );
}

/**
 * Registers the WooCommerce extension.
 */
flextension_register_module(
	__FILE__,
	array(
		'title'             => esc_html__( 'E-commerce', 'evie-xt' ),
		'description'       => esc_html__( 'Adds theme support and custom styles for the WooCommerce plugin.', 'evie-xt' ),
		'enabled'           => evie_is_wc_plugin_active(),
		'actions'           => evie_wc_module_actions(),
		'load_callback'     => 'evie_wc_module_load',
		'activate_callback' => 'evie_wc_module_activate',
	)
);

/**
 * Loads the WooCommerce extension.
 */
function evie_wc_module_load() {
	add_filter( 'evie_plugins', 'evie_wc_module_add_plugin' );

	if ( evie_is_wc_plugin_active() ) {

		require_once plugin_dir_path( __FILE__ ) . 'evie-variation-swatches.php';

		if ( is_admin() ) {

			add_filter( 'product_attributes_type_selector', 'evie_wc_product_attribute_types' );

			add_action( 'woocommerce_product_option_terms', 'evie_wc_product_attributes', 10, 3 );

			require_once plugin_dir_path( __FILE__ ) . 'admin/class-evie-product-attribute-edit.php';
			new Evie_Product_Attribute_Edit();
		}
	}

}

/**
 * Returns module actions.
 *
 * @return array An array of the module actions.
 */
function evie_wc_module_actions() {
	$actions = array();

	if ( ! evie_is_wc_plugin_active() ) {
		$actions['install'] = sprintf(
			'<a href="%s">%s</a>',
			sprintf( '%s?page=install-plugins', admin_url( flextension_get_admin_page( 'themes' ) ) ),
			esc_html__( 'Install Required Plugins', 'evie-xt' )
		);
	}

	return $actions;
}

/**
 * Adds the WooCommerce plugin to the recommended plugins list.
 *
 * @param array $plugins An array list of the recommended plugins.
 * @return array An array list of the recommended plugins.
 */
function evie_wc_module_add_plugin( $plugins = array() ) {
	$plugins[] = array(
		'name' => esc_html__( 'WooCommerce', 'evie-xt' ),
		'slug' => 'woocommerce',
	);
	return $plugins;
}

/**
 * Activates required plugins after the module is first activated.
 */
function evie_wc_module_activate() {
	if ( isset( $GLOBALS['tgmpa'] ) && isset( $GLOBALS['tgmpa']->plugins['woocommerce'] ) ) {
		activate_plugins( $GLOBALS['tgmpa']->plugins['woocommerce']['file_path'] );
	}
}
