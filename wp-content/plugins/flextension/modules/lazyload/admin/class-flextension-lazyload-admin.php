<?php
/**
 * Lazyload Admin Module
 *
 * @package    Flextension
 * @subpackage Modules/Lazyload/Admin
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Lazyload Admin class.
 */
class Flextension_Lazyload_Admin extends Flextension_Module_Admin {

	/**
	 * Initializes the module.
	 */
	public function initialize() {
		// If it is on a settings page, register settings.
		if ( $this->is_settings_page( 'media' ) ) {
			add_action( 'admin_init', array( $this, 'register_settings' ) );
		}
	}

	/**
	 * Registers a new settings section.
	 */
	public function register_settings() {

		// The settings values from database.
		$settings = flextension_lazyload_settings();

		// Register a new settings section on Settings -> Media.
		add_settings_section(
			'flextension_lazyload_section',
			sprintf( '<span id="%s">%s</span>', $this->settings_page_slug(), esc_html__( 'Lazy Load', 'flextension' ) ),
			null,
			'media'
		);

		add_settings_field(
			'lqip',
			esc_html__( 'Image placeholders', 'flextension' ),
			array( $this, 'settings_field' ),
			'media',
			'flextension_lazyload_section',
			array(
				'type'        => 'checkbox',
				'name'        => 'flext_lazyload[lqip]',
				'value'       => (bool) $settings['lqip'],
				'description' => __( 'Displays a low quality image placeholder while the image is loading. You will need to regenerate thumbnails after enabling this option.', 'flextension' ),
			)
		);

		// Register a new setting.
		register_setting(
			'media',
			'flext_lazyload',
			array(
				'type'              => 'array',
				'sanitize_callback' => array( $this, 'sanitize_settings' ),
			)
		);

	}

	/**
	 * Sanitizes the settings values based on the field type.
	 *
	 * @global $wp_settings_fields Storage array of settings fields and info about their pages/sections.
	 *
	 * @param array $values An array object of the setttings values.
	 * @return array The sanitized settings values.
	 */
	public function sanitize_settings( $values = array() ) {
		global $wp_settings_fields;
		foreach ( $wp_settings_fields['media']['flextension_lazyload_section'] as $field ) {
			if ( isset( $values[ $field['id'] ] ) ) {
				$values[ $field['id'] ] = $this->sanitize_field( $values[ $field['id'] ], $field['args'] );
			}
		}
		return $values;
	}
}
