<?php
/**
 * Custom Fonts Settings
 *
 * @package    Flextension
 * @subpackage Modules/Custom_Fonts/Admin
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Custom Fonts Admin Class
 */
class Flextension_Custom_Fonts_Admin extends Flextension_Module_Admin {

	/**
	 * Initializes the module.
	 */
	public function initialize() {
		add_filter( 'upload_mimes', array( $this, 'add_upload_mimes' ) );
		// If it is on a module settings page, register settings.
		if ( $this->is_settings_page( $this->settings_page_slug( 'fonts' ), 'themes', 'custom-fonts' ) ) {

			add_action( 'admin_init', array( $this, 'register_settings' ) );
			// Enqueue admin scripts.
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		}
	}

	/**
	 * Adds upload mime types for
	 *
	 * @param array $types Mime types keyed by the file extension regex corresponding to those types.
	 * @return array Mime types keyed by the file extension regex corresponding to those types.
	 */
	public function add_upload_mimes( $types = array() ) {
		$types['woff']  = 'application/x-font-woff';
		$types['woff2'] = 'application/x-font-woff2';
		return $types;
	}

	/**
	 * Registers a new settings section under Settings.
	 */
	public function register_settings() {

		// Register a new settings section on Settings -> Fonts -> Custom Fonts.
		add_settings_section(
			'custom-fonts',
			'',
			array( $this, 'settings_section' ),
			$this->settings_page_slug( 'fonts' )
		);

		$settings = flextension_custom_fonts_settings();

		$options = array();

		$values = array();
		if ( ! empty( $settings['fonts'] ) ) {
			foreach ( $settings['fonts'] as $font ) {

				$fields = array();
				if ( ! empty( $font['files'] ) ) {
					foreach ( $font['files'] as $key => $file ) {
						$files = array();
						foreach ( $file as $key => $value ) {
							$files[] = array( 'value' => $value );
						}

						$fields[] = array(
							'fields' => $files,
						);
					}
				}

				$values[] = array(
					'fields' => array(
						array(
							'value' => $font['name'],
						),
						array(
							'value' => $fields,
						),
					),
				);
			}
		}

		add_settings_field(
			'fonts',
			esc_html__( 'Custom Fonts', 'flextension' ),
			array( $this, 'settings_field' ),
			$this->settings_page_slug( 'fonts' ),
			'custom-fonts',
			array(
				'type'          => 'fields_list',
				'name'          => 'flext_custom_fonts[fonts]',
				'value'         => $values,
				'template'      => array(
					'type'   => 'fieldset',
					'fields' => array(
						array(
							'type'  => 'text',
							'name'  => 'name',
							'label' => esc_html__( 'Name', 'flextension' ),
						),
						array(
							'type'             => 'fields_list',
							'name'             => 'files',
							'template'         => array(
								'type'   => 'fieldset',
								'fields' => array(
									array(
										'type'    => 'select',
										'name'    => 'weight',
										'label'   => esc_html__( 'Weight', 'flextension' ),
										'options' => array(
											'100' => _x( '100', 'Font weight', 'flextension' ),
											'200' => _x( '200', 'Font weight', 'flextension' ),
											'300' => _x( '300', 'Font weight', 'flextension' ),
											'400' => _x( '400 (Regular)', 'Font weight', 'flextension' ),
											'500' => _x( '500', 'Font weight', 'flextension' ),
											'600' => _x( '600', 'Font weight', 'flextension' ),
											'700' => _x( '700', 'Font weight', 'flextension' ),
											'800' => _x( '800', 'Font weight', 'flextension' ),
											'900' => _x( '900', 'Font weight', 'flextension' ),
										),
									),
									array(
										'type'    => 'select',
										'name'    => 'style',
										'label'   => esc_html__( 'Style', 'flextension' ),
										'options' => array(
											''       => esc_html__( 'Normal', 'flextension' ),
											'italic' => esc_html__( 'Italic', 'flextension' ),
										),
									),
									array(
										'type'      => 'file',
										'name'      => 'woff',
										'label'     => esc_html__( 'Font file (.woff)', 'flextension' ),
										'mime_type' => 'application/x-font-woff',
									),
									array(
										'type'      => 'file',
										'name'      => 'woff2',
										'label'     => esc_html__( 'Font file (.woff2)', 'flextension' ),
										'mime_type' => 'application/x-font-woff2',
									),
								),
							),
							'sortable'         => false,
							'add_button'       => esc_html__( 'Add font files', 'flextension' ),
							'show_placeholder' => true,
							'wrapper_class'    => 'flext-custom-fonts-files',
						),
					),
				),
				'sortable'      => false,
				'add_button'    => esc_html__( 'Add New Font Family', 'flextension' ),
				'wrapper_class' => 'flext-custom-fonts',
			)
		);

		// Register a new setting.
		register_setting(
			$this->settings_page_slug( 'fonts' ),
			'flext_custom_fonts',
			array(
				'type'              => 'array',
				'sanitize_callback' => array( $this, 'sanitize_settings' ),
			)
		);
	}

	/**
	 * Prints out the settings section.
	 */
	public function settings_section() {
		?>
		<p>
			<?php
			echo sprintf(
				/* translators: %s: The link to Adobe Fonts API Tokens page */
				esc_html__( 'You can find the instructions on how to add custom fonts in the %s.', 'flextension' ),
				'<a href="' . esc_url( flextension_get_doc_url( $this->name ) ) . '" target="_blank">' . esc_html__( 'Documentation', 'flextension' ) . '</a>'
			);
			?>
		</p>
		<?php
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
		foreach ( $wp_settings_fields[ $this->settings_page_slug( 'fonts' ) ]['custom-fonts'] as $field ) {
			if ( isset( $values[ $field['id'] ] ) ) {
				$values[ $field['id'] ] = $this->sanitize_field( $values[ $field['id'] ], $field['args'] );
			}
		}
		return $values;
	}

	/**
	 * Enqueues scripts and stylesheets for the admin page.
	 */
	public function admin_enqueue_scripts() {
		wp_enqueue_style( 'flextension-custom-fonts-editor', plugins_url( 'css/style.css', __FILE__ ), array( 'flextension' ), flextension_get_setting( 'version' ) );
		wp_style_add_data( 'flextension-custom-fonts-editor', 'rtl', 'replace' );

		$custom_fonts_css = flextension_custom_fonts_css();
		if ( ! empty( $custom_fonts_css ) ) {
			wp_add_inline_style( 'flextension-custom-fonts-editor', $custom_fonts_css );
		}

		wp_enqueue_script( 'flextension-custom-fonts-editor', plugins_url( 'js/index.js', __FILE__ ), array( 'flextension' ), flextension_get_setting( 'version' ), true );
	}

}
