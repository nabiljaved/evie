<?php
/**
 * Cursor Module
 *
 * Adds an animated cursor to your site.
 *
 * @package    Evie_XT
 * @subpackage Modules/Cursor
 * @version    1.0.0
 */

/**
 * Registers the Cursor module.
 */
flextension_register_module(
	__FILE__,
	array(
		'title'         => esc_html__( 'Cursor', 'evie-xt' ),
		'description'   => esc_html__( 'Adds an animated cursor to your site.', 'evie-xt' ),
		'actions'       => array(
			'settings' => sprintf(
				'<a href="%s">%s</a>',
				admin_url( 'customize.php?autofocus[section]=cursor_section' ),
				esc_html__( 'Customize', 'evie-xt' )
			),
		),
		'load_callback' => 'evie_cursor_module_load',
	)
);

/**
 * Loads the Cursor module.
 */
function evie_cursor_module_load() {
	/* Public functions. */
	require_once plugin_dir_path( __FILE__ ) . 'evie-cursor.php';
}
