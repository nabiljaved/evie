<?php
/**
 * Portfolio Module
 *
 * Adds a Project post type, to display the portfolio on your website.
 *
 * @package    Evie_XT
 * @subpackage Modules/Portfolio
 * @version    1.0.0
 */

/**
 * Registers the Elements module.
 */
flextension_register_module(
	__FILE__,
	array(
		'title'               => esc_html__( 'Portfolio', 'evie-xt' ),
		'description'         => esc_html__( 'Adds a Project post type and displays the portfolio on your website.', 'evie-xt' ),
		'category'            => esc_html__( 'Portfolio', 'evie-xt' ),
		'dependencies'        => array( 'meta-box' ),
		'enabled'             => true,
		'actions'             => array(
			'settings' => sprintf(
				'<a href="%s">%s</a>',
				flextension_get_admin_page_url( 'portfolio' ),
				esc_html__( 'Settings', 'evie-xt' )
			),
		),
		'load_callback'       => 'evie_portfolio_module_load',
		'activate_callback'   => 'evie_portfolio_module_activate',
		'deactivate_callback' => 'evie_portfolio_module_deactivate',
	)
);

/**
 * Loads the Portfolio module.
 *
 * @param Flextension_Module $module The current module instance.
 */
function evie_portfolio_module_load( $module ) {
	// Public functions.
	require_once plugin_dir_path( __FILE__ ) . 'evie-portfolio.php';

	// Settings.
	if ( is_admin() ) {
		require_once plugin_dir_path( __FILE__ ) . 'admin/class-evie-portfolio-admin.php';
		new Evie_Portfolio_Admin( $module->name );

		require_once plugin_dir_path( __FILE__ ) . 'admin/class-evie-portfolio-permalink-settings.php';
		new Evie_Portfolio_Permalink_Settings( $module->name );

	}
}

/**
 * Activates required plugins after the module is first activated.
 */
function evie_portfolio_module_activate() {
	flush_rewrite_rules();
}

/**
 * Deactivates required plugins after the module is deactivated.
 */
function evie_portfolio_module_deactivate() {
	flush_rewrite_rules();
}
