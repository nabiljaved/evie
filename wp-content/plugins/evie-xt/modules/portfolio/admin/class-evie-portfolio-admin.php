<?php
/**
 * Portfolio Settings.
 *
 * @package    Evie_XT
 * @subpackage Modules/Portfolio/Admin
 * @version    1.0.0
 */

/**
 * Portfolio Admin class.
 */
class Evie_Portfolio_Admin extends Flextension_Module_Admin {

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

		// Add a new settings page into Settings -> Portfolio.
		add_options_page(
			esc_html__( 'Portfolio Settings', 'evie-xt' ),
			esc_html__( 'Portfolio', 'evie-xt' ),
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
		$settings = evie_portfolio_settings();

		// Register a new settings section on Settings -> Portfolio.
		add_settings_section(
			'default',
			'',
			null,
			$this->settings_page_slug()
		);

		// Add settings fields.
		add_settings_field(
			'portfolio_page',
			esc_html__( 'Portfolio page', 'evie-xt' ),
			array( $this, 'settings_field' ),
			$this->settings_page_slug(),
			'default',
			array(
				'type'        => 'select_pages',
				'name'        => 'evie_portfolio[portfolio_page]',
				'value'       => $settings['portfolio_page'],
				'description' => esc_html__( 'The portfolio archive page.', 'evie-xt' ),
			)
		);

		add_settings_field(
			'comments',
			esc_html__( 'Comments', 'evie-xt' ),
			array( $this, 'settings_field' ),
			$this->settings_page_slug(),
			'default',
			array(
				'type'        => 'checkbox',
				'name'        => 'evie_portfolio[comments]',
				'value'       => $settings['comments'],
				'description' => esc_html__( 'Enable project comments.', 'evie-xt' ),
			)
		);

		/**
		 * Register settings.
		 */
		register_setting(
			$this->settings_page_slug(),
			'evie_portfolio',
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

		if ( isset( $_GET['settings-updated'] ) && ! empty( $_GET['settings-updated'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			flush_rewrite_rules( false ); // Flush permalink rewrite rules after saving changes to the Portfolio page.
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
