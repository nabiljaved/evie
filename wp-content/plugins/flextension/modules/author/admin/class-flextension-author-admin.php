<?php
/**
 * Author Settings.
 *
 * @package    Flextension
 * @subpackage Modules/Author/Admin
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Author Admin class.
 */
class Flextension_Author_Admin extends Flextension_Module_Admin {

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

		// Add a new settings page into Settings -> Author.
		add_options_page(
			esc_html__( 'Advanced Author Settings', 'flextension' ),
			esc_html__( 'Advanced Author', 'flextension' ),
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
		$settings = flextension_author_settings();

		// Register a new settings section on Settings -> Author.
		add_settings_section(
			'default',
			'',
			null,
			$this->settings_page_slug()
		);

		// Add settings fields.
		add_settings_field(
			'follow',
			esc_html__( 'Follow Button', 'flextension' ),
			array( $this, 'settings_field' ),
			$this->settings_page_slug(),
			'default',
			array(
				'type'        => 'checkbox',
				'name'        => 'flext_author[follow]',
				'value'       => $settings['follow'],
				'description' => esc_html__( 'Display “Follow” button and allow people to follow authors.', 'flextension' ),
			)
		);

		add_settings_field(
			'followers',
			esc_html__( 'Followers and Following', 'flextension' ),
			array( $this, 'settings_field' ),
			$this->settings_page_slug(),
			'default',
			array(
				'type'               => 'checkbox',
				'name'               => 'flext_author[followers]',
				'value'              => $settings['followers'],
				'description'        => esc_html__( 'Display number of followers and following.', 'flextension' ),
				'dependencies'       => array(
					array(
						'name'  => 'flext_author[follow]',
						'value' => true,
					),
				),
				'wrapper_attributes' => array(
					'data-wrapper' => 'tr',
				),
			)
		);

		/**
		 * Register settings.
		 */
		register_setting(
			$this->settings_page_slug(),
			'flext_author',
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
			wp_die( esc_html__( 'You do not have sufficient rights to view this page.', 'flextension' ) );
		}

		if ( isset( $_GET['settings-updated'] ) && ! empty( $_GET['settings-updated'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			flush_rewrite_rules( false ); // Flush permalink rewrite rules after saving changes to the Author page.
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
