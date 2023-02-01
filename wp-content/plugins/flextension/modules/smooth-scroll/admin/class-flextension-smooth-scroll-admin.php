<?php
/**
 * Smooth Scroll Admin
 *
 * @package    Flextension
 * @subpackage Modules/Smooth_Scroll/Admin
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Flextension Smooth Scroll Admin class
 */
class Flextension_Smooth_Scroll_Admin extends Flextension_Module_Admin {

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

		// Add a new settings page into Settings -> Smooth Scroll.
		add_options_page(
			esc_html__( 'Smooth Scroll', 'flextension' ),
			esc_html__( 'Smooth Scroll', 'flextension' ),
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
		$settings = flextension_smooth_scroll_settings();

		// Register a new settings section on Settings -> Smooth Scroll.
		add_settings_section(
			'default',
			'',
			null,
			$this->settings_page_slug()
		);

		add_settings_field(
			'duration',
			esc_html__( 'Animation duration', 'flextension' ),
			array( $this, 'settings_field' ),
			$this->settings_page_slug(),
			'default',
			array(
				'type'        => 'select',
				'name'        => 'flext_smooth_scroll[duration]',
				'value'       => $settings['duration'],
				'options'     => array(
					300 => esc_html__( 'Short', 'flextension' ),
					''  => esc_html__( 'Medium', 'flextension' ),
					900 => esc_html__( 'Long', 'flextension' ),
				),
				'description' => esc_html__( 'Define how long an animation should take to complete. Default "Medium".', 'flextension' ),
			)
		);

		// Register a new setting.
		register_setting(
			$this->settings_page_slug(),
			'flext_smooth_scroll',
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

	/**
	 * Prints out the settings section.
	 *
	 * @param array $args Optional. Extra arguments used when outputting the field.
	 */
	public function settings_section( $args = array() ) {
		?>
		<p>
		<?php echo esc_html__( 'Select the options listed below to improve user experience. You can find more details in the documentation.', 'flextension' ); ?>
		</p>
		<?php
	}

}
