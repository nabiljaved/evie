<?php
/**
 * Wishlist Module
 *
 * @package    Evie_XT
 * @subpackage Modules/Wishlist
 * @version    1.0.0
 */

/**
 * Returns whether the YITH WooCommerce Wishlist plugin is active.
 *
 * @return bool Whether the YITH WooCommerce Wishlist plugin is active.
 */
function evie_is_wishlist_plugin_active() {
	return defined( 'YITH_WCWL' ) && YITH_WCWL;
}

/**
 * Registers the Wishlist module.
 */
flextension_register_module(
	__FILE__,
	array(
		'title'               => esc_html__( 'Wishlist', 'evie-xt' ),
		'description'         => esc_html__( 'Allows customers to create and add products to the wishlist.', 'evie-xt' ),
		'dependencies'        => array( 'woocommerce' ),
		'enabled'             => evie_is_wishlist_plugin_active(),
		'actions'             => evie_wishlist_module_actions(),
		'load_callback'       => 'evie_wishlist_module_load',
		'activate_callback'   => 'evie_wishlist_module_activate',
		'deactivate_callback' => 'evie_wishlist_module_deactivate',
	)
);

/**
 * Loads the Wishlist module.
 */
function evie_wishlist_module_load() {
	add_filter( 'evie_plugins', 'evie_wishlist_module_add_plugin' );

	if ( evie_is_wishlist_plugin_active() ) {
		add_action( 'init', 'evie_wishlist_register_scripts' );

		add_action( 'wp_enqueue_scripts', 'evie_wishlist_enqueue_scripts' );

		add_filter( 'yith_wcwl_positions', 'evie_wishlist_button_positions' );
	}
}

/**
 * Registers default styles and registers a new style.
 */
function evie_wishlist_register_scripts() {
	// Remove default styles.
	wp_deregister_style( 'yith-wcwl-main' );

	wp_register_style( 'evie-wishlist', plugins_url( 'css/style.css', __FILE__ ), array(), EVIE_XT_VERSION );

	wp_style_add_data( 'evie-wishlist', 'rtl', 'replace' );
}

/**
 * Enqueues the scripts and stylesheets.
 */
function evie_wishlist_enqueue_scripts() {
	wp_enqueue_style( 'evie-wishlist' );
}

/**
 * Filters the "Add to Wishlist" button position settings.
 *
 * @param array $positions An array of position settings.
 * @return array An array of position settings.
 */
function evie_wishlist_button_positions( $positions = array() ) {
	if ( isset( $positions['thumbnails'] ) ) {
		$positions['thumbnails']['hook'] = 'woocommerce_before_single_product_summary';
	}

	return $positions;
}

/**
 * Returns module actions.
 *
 * @return array An array of the module actions.
 */
function evie_wishlist_module_actions() {
	$actions = array();

	if ( ! evie_is_wishlist_plugin_active() ) {
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
function evie_wishlist_module_add_plugin( $plugins = array() ) {
	$plugins[] = array(
		'name' => esc_html__( 'YITH WooCommerce Wishlist', 'evie-xt' ),
		'slug' => 'yith-woocommerce-wishlist',
	);
	return $plugins;
}

/**
 * Activates required plugins after the module is first activated.
 */
function evie_wishlist_module_activate() {
	if ( isset( $GLOBALS['tgmpa'] ) && isset( $GLOBALS['tgmpa']->plugins['yith-woocommerce-wishlist'] ) ) {
		activate_plugins( $GLOBALS['tgmpa']->plugins['yith-woocommerce-wishlist']['file_path'] );
	}
}

/**
 * Deactivates required plugins after the module is deactivated.
 */
function evie_wishlist_module_deactivate() {
	if ( isset( $GLOBALS['tgmpa'] ) && isset( $GLOBALS['tgmpa']->plugins['yith-woocommerce-wishlist'] ) ) {
		deactivate_plugins( $GLOBALS['tgmpa']->plugins['yith-woocommerce-wishlist']['file_path'] );
	}
}
