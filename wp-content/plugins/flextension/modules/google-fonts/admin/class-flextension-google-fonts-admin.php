<?php
/**
 * Google Fonts Settings
 *
 * @package    Flextension
 * @subpackage Modules/Google_Fonts/Admin
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Google Fonts Admin Class
 */
class Flextension_Google_Fonts_Admin extends Flextension_Module_Admin {

	/**
	 * Initializes the module.
	 */
	public function initialize() {
		// If it is on a module settings page, register settings.
		if ( $this->is_settings_page( $this->settings_page_slug( 'fonts' ), 'themes', 'google-fonts' ) ) {

			$this->process_request();

			add_action( 'admin_init', array( $this, 'register_settings' ) );

			// Enqueue admin scripts.
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		}
	}

	/**
	 * Processes action request, if capable.
	 */
	public function process_request() {
		if ( ! isset( $_GET['_wpnonce'] ) || ! wp_verify_nonce( sanitize_key( wp_unslash( $_GET['_wpnonce'] ) ), 'flext-action' ) ) {
			return;
		}

		$action = isset( $_GET['flext-action'] ) ? sanitize_title( wp_unslash( $_GET['flext-action'] ) ) : '';
		if ( 'update' === $action ) {
			flextension_google_fonts_clear_cache();
			wp_safe_redirect( remove_query_arg( array( 'flext-action', '_wpnonce' ) ) );
		}
	}

	/**
	 * Registers a new settings section under Settings.
	 */
	public function register_settings() {

		// Register a new settings section on Settings -> Fonts -> Google Fonts.
		add_settings_section(
			'google-fonts',
			'',
			array( $this, 'settings_section' ),
			$this->settings_page_slug( 'fonts' )
		);

		$settings = flextension_google_fonts_settings();

		add_settings_field(
			'api_key',
			esc_html__( 'API Key', 'flextension' ),
			array( $this, 'settings_field' ),
			$this->settings_page_slug( 'fonts' ),
			'google-fonts',
			array(
				'type'        => 'text',
				'name'        => 'flext_google_fonts[api_key]',
				'value'       => $settings['api_key'],
				'description' => sprintf(
					/* translators: %s: The link to the Google Fonts Developer API page */
					esc_html__( 'Get your %s to enable automatic updates for Google Fonts list.', 'flextension' ),
					'<a href="https://developers.google.com/fonts/docs/developer_api#APIKey" target="_blank">' . esc_html__( 'API Key', 'flextension' ) . '</a>'
				),
			)
		);

		$all_fonts = flextension_google_fonts_list();
		if ( ! empty( $all_fonts ) ) {
			$options = array();
			foreach ( $all_fonts as $slug => $name ) {
				$options[ $slug ] = $name;
			}

			$values = array();
			if ( ! empty( $settings['fonts'] ) ) {
				foreach ( $settings['fonts'] as $name ) {
					$values[] = array(
						'fields' => array(
							array( 'value' => $name ),
							array(),
						),
					);
				}
			}

			if ( ! empty( $settings['api_key'] ) ) {
				add_settings_field(
					'refresh',
					'',
					array( $this, 'settings_field' ),
					$this->settings_page_slug( 'fonts' ),
					'google-fonts',
					array(
						'type'        => 'link_button',
						'name'        => 'flext-google-fonts-update-button',
						'icon'        => 'dashicons dashicons-update',
						'value'       => esc_html__( 'Update', 'flextension' ),
						'url'         => add_query_arg( '_wpnonce', wp_create_nonce( 'flext-action' ), flextension_get_admin_page_url( 'fonts', 'themes', 'google-fonts&flext-action=update' ) ),
						'description' => esc_html__( 'A fonts list is preconfigured for weekly automatic updates. Click the "Update" button to update it now.', 'flextension' ),
					)
				);
			}

			add_settings_field(
				'fonts',
				esc_html__( 'Google Fonts', 'flextension' ),
				array( $this, 'settings_field' ),
				$this->settings_page_slug( 'fonts' ),
				'google-fonts',
				array(
					'type'          => 'fields_list',
					'name'          => 'flext_google_fonts[fonts]',
					'value'         => $values,
					'template'      => array(
						'type'   => 'fieldset',
						'fields' => array(
							array(
								'type'          => 'select',
								'name'          => '',
								'options'       => $options,
								'wrapper_class' => 'flext-google-fonts-list',
							),
							array(
								'type'          => 'label',
								'name'          => 'preview',
								'text'          => esc_html__( 'Sample Text!', 'flextension' ),
								'wrapper_class' => 'flext-google-fonts-preview',
							),
						),
					),
					'sortable'      => false,
					'wrapper_class' => 'flext-google-fonts',
				)
			);
		}

		// Register a new setting.
		register_setting(
			$this->settings_page_slug( 'fonts' ),
			'flext_google_fonts',
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
				/* translators: %s: A link to the instructions page */
				esc_html__( 'You can find the instructions on how to enable automatic updates for Google Fonts list in the %s.', 'flextension' ),
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
		foreach ( $wp_settings_fields[ $this->settings_page_slug( 'fonts' ) ]['google-fonts'] as $field ) {
			if ( isset( $values[ $field['id'] ] ) ) {
				$values[ $field['id'] ] = $this->sanitize_field( $values[ $field['id'] ], $field['args'] );
				if ( 'fonts' === $field['id'] ) {
					$values['fonts'] = array_unique( $values['fonts'] );
				}
			}
		}
		return $values;
	}

	/**
	 * Enqueues scripts and stylesheets for the admin page.
	 */
	public function admin_enqueue_scripts() {
		wp_enqueue_style( 'flextension-google-fonts-editor', plugins_url( 'css/style.css', __FILE__ ), array( 'flextension' ), flextension_get_setting( 'version' ) );

		wp_enqueue_script( 'flextension-google-fonts-editor', plugins_url( 'js/index.js', __FILE__ ), array( 'flextension' ), flextension_get_setting( 'version' ), true );
	}

}
