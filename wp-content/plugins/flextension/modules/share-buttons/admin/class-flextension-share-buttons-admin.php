<?php
/**
 * Share Buttons Settings
 *
 * @package    Flextension
 * @subpackage Modules/Share_Buttons/Admin
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Share Buttons Admin Class
 */
class Flextension_Share_Buttons_Admin extends Flextension_Module_Admin {

	/**
	 * Initializes the module.
	 */
	public function initialize() {
		add_action( 'admin_menu', array( $this, 'register_settings_page' ) );
		// If it is on a module settings page, register settings.
		if ( $this->is_settings_page() ) {
			add_action( 'admin_init', array( $this, 'register_settings' ) );

			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		}
	}

	/**
	 * Registers a new settings page under Settings.
	 */
	public function register_settings_page() {

		// Add a new settings page into Settings -> Share Buttons.
		add_options_page(
			esc_html__( 'Share Buttons', 'flextension' ),
			esc_html__( 'Share Buttons', 'flextension' ),
			'manage_options',
			$this->settings_page_slug(),
			array( $this, 'settings_page' )
		);

	}

	/**
	 * Registers a new settings section under Settings.
	 */
	public function register_settings() {

		// The setting values from database.
		$settings = flextension_share_buttons_settings();

		// Register a new settings section on Settings -> Share Buttons.
		add_settings_section(
			'default',
			'',
			array( $this, 'settings_section' ),
			$this->settings_page_slug()
		);

		$share_buttons = array();

		$all_share_buttons = flextension_share_links();
		foreach ( $all_share_buttons as $name => $link ) {
			$share_buttons[ $name ] = '<i class="' . esc_attr( $link['icon'] ) . '"></i> ' . $link['title'];
		}

		add_settings_field(
			'all_items',
			esc_html__( 'Share buttons', 'flextension' ),
			array( $this, 'settings_field' ),
			$this->settings_page_slug(),
			'default',
			array(
				'type'          => 'checkbox_list',
				'value'         => $settings['active_items'],
				'options'       => $share_buttons,
				'layout'        => 'grid',
				'wrapper_class' => 'flext-share-buttons-active',
			)
		);

		$fields = array();
		if ( ! empty( $settings['active_items'] ) && is_array( $settings['active_items'] ) ) {
			foreach ( $settings['active_items'] as $name ) {
				$fields[] = array(
					'value' => $name,
					'icon'  => $all_share_buttons[ $name ]['icon'],
					'label' => $all_share_buttons[ $name ]['title'],
				);
			}
		}

		add_settings_field(
			'active_items',
			esc_html__( 'Order', 'flextension' ),
			array( $this, 'settings_field' ),
			$this->settings_page_slug(),
			'default',
			array(
				'type'          => 'fields_list',
				'name'          => 'flext_share_buttons[active_items]',
				'value'         => $fields,
				'template'      => array(
					'type'  => 'hidden',
					'label' => ' ',
				),
				'add_button'    => false,
				'wrapper_class' => 'flext-share-buttons-order',
			)
		);

		add_settings_field(
			'new_tab',
			esc_html__( 'Link target', 'flextension' ),
			array( $this, 'settings_field' ),
			$this->settings_page_slug(),
			'default',
			array(
				'type'        => 'checkbox',
				'name'        => 'flext_share_buttons[new_tab]',
				'value'       => $settings['new_tab'],
				'description' => esc_html__( 'Open link in a new tab', 'flextension' ),
			)
		);

		// Register a new setting.
		register_setting(
			$this->settings_page_slug(),
			'flext_share_buttons',
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
		<p><?php echo esc_html__( 'Select the share buttons listed below to display in the post sharing options.', 'flextension' ); ?></p>
		<?php
	}

	/**
	 * Enqueues scripts and stylesheets for the admin page.
	 */
	public function admin_enqueue_scripts() {
		wp_enqueue_style( 'flextension-share-buttons-editor', plugins_url( 'css/style.css', __FILE__ ), array( 'flextension' ), flextension_get_setting( 'version' ) );

		wp_enqueue_script( 'flextension-share-buttons-editor', plugins_url( 'js/index.js', __FILE__ ), array( 'flextension' ), flextension_get_setting( 'version' ), true );
	}

}
