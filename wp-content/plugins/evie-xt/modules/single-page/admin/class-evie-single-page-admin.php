<?php
/**
 * Single Page Settings
 *
 * @package    Evie_XT
 * @subpackage Modules/Single_Page/Admin
 * @version    1.0.0
 */

/**
 * The admin-specific functionality of the module.
 */
class Evie_Single_Page_Admin extends Flextension_Module_Admin {

	/**
	 * Initializes the module.
	 */
	public function initialize() {
		add_action( 'admin_menu', array( $this, 'register_settings_page' ) );
		// If it is on a module settings page, register settings.
		if ( $this->is_settings_page() ) {
			add_action( 'admin_init', array( $this, 'register_settings' ) );
		}
	}

	/**
	 * Registers a new settings page under Settings.
	 */
	public function register_settings_page() {

		// Add a new settings page into Settings -> Single Page.
		add_options_page(
			esc_html__( 'Single Page', 'evie-xt' ),
			esc_html__( 'Single Page', 'evie-xt' ),
			'manage_options',
			$this->settings_page_slug(),
			array( $this, 'settings_page' )
		);

	}

	/**
	 * Registers a new settings section under Settings.
	 */
	public function register_settings() {

		// The settings values from database.
		$settings = evie_single_page_settings();

		// Register a new settings section on Settings -> Single Page.
		add_settings_section(
			'default',
			'',
			null,
			$this->settings_page_slug()
		);

		add_settings_field(
			'exclude_urls',
			esc_html__( 'Advanced Rules', 'evie-xt' ),
			array( $this, 'settings_field' ),
			$this->settings_page_slug(),
			'default',
			array(
				'type'        => 'fields_list',
				'name'        => 'evie_single_page[exclude_urls]',
				'value'       => $settings['exclude_urls'],
				'label'       => esc_html__( 'Exclude the following URL(s)', 'evie-xt' ),
				'template'    => array(
					'type'        => 'text',
					'name'        => '',
					'placeholder' => '/page-or-post-slug/',
					'class'       => 'large-text',
				),
				'sortable'    => false,
				'description' => esc_html__( 'Ignore the links that contain these URL strings.', 'evie-xt' ),
			)
		);

		add_settings_field(
			'exclude_selectors',
			'',
			array( $this, 'settings_field' ),
			$this->settings_page_slug(),
			'default',
			array(
				'type'        => 'fields_list',
				'name'        => 'evie_single_page[exclude_selectors]',
				'value'       => $settings['exclude_selectors'],
				'label'       => esc_html__( 'Exclude the following selector(s)', 'evie-xt' ),
				'template'    => array(
					'type'        => 'text',
					'name'        => '',
					'placeholder' => '.no-ajax',
					'class'       => 'large-text',
				),
				'sortable'    => false,
				'description' => esc_html__( 'Ignore the links that match the specified group of CSS selectors.', 'evie-xt' ),
			)
		);

		// Register a new setting.
		register_setting(
			$this->settings_page_slug(),
			'evie_single_page',
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
		foreach ( $wp_settings_fields[ $this->settings_page_slug() ]['default'] as $field ) {
			if ( isset( $values[ $field['id'] ] ) ) {
				$values[ $field['id'] ] = $this->sanitize_field( $values[ $field['id'] ], $field['args'] );
			}
		}
		return $values;
	}

	/**
	 * Prints out the settings page.
	 */
	public function settings_page() {
		// Check user capabilities.
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient rights to view this page.', 'evie-xt' ) );
		}
		?>
		<div class="wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			<form action="options.php" method="post">
			<?php
				// Output security fields for this page.
				settings_fields( $this->settings_page_slug() );
				// Output all settings sections added to this page.
				do_settings_sections( $this->settings_page_slug() );
				// Prints out a save changes button.
				submit_button();
			?>
			</form>
		</div>
		<?php
	}

}
