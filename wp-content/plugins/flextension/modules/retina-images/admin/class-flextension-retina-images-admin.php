<?php
/**
 * Retina Images Settings
 *
 * @package    Flextension
 * @subpackage Modules/Retina_Images/Admin
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * The admin-specific functionality of the module.
 */
class Flextension_Retina_Images_Admin extends Flextension_Module_Admin {

	/**
	 * Initializes the module.
	 */
	public function initialize() {
		// If it is on the Settings -> Media, register new settings.
		if ( $this->is_settings_page( 'media' ) ) {
			add_action( 'admin_init', array( $this, 'register_settings' ) );
		}
	}

	/**
	 * Registers a new settings section.
	 */
	public function register_settings() {

		// The settings values from database.
		$settings = flextension_retina_images_settings();

		// Register a new settings section on Settings -> Media.
		add_settings_section(
			'flextension_retina_images_section',
			sprintf( '<span id="%s">%s</span>', $this->settings_page_slug(), esc_html__( 'Retina Images', 'flextension' ) ),
			array( $this, 'settings_section' ),
			'media'
		);

		$image_sizes = array_keys( flextension_retina_images_available_sizes() );

		// Add setting fields.
		add_settings_field(
			'sizes',
			esc_html__( 'Sizes', 'flextension' ),
			array( $this, 'settings_field' ),
			'media',
			'flextension_retina_images_section',
			array(
				'type'    => 'checkbox_list',
				'name'    => 'flext_retina_images[sizes]',
				'value'   => $settings['sizes'],
				'options' => array_combine( $image_sizes, $image_sizes ),
			)
		);

		// Register a new setting.
		register_setting(
			'media',
			'flext_retina_images',
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
		foreach ( $wp_settings_fields['media']['flextension_retina_images_section'] as $field ) {
			if ( isset( $values[ $field['id'] ] ) ) {
				$values[ $field['id'] ] = $this->sanitize_field( $values[ $field['id'] ], $field['args'] );
			}
		}
		return $values;
	}

	/**
	 * Prints out the settings section.
	 */
	public function settings_section() {
		?>
		<p><?php esc_html_e( 'Select the image sizes below to generate the Retina Images. After saving changes, new image sizes may not be shown until you regenerate thumbnails.', 'flextension' ); ?></p>
		<?php
	}

}
