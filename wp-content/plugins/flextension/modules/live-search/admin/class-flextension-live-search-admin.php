<?php
/**
 * Live Search Settings
 *
 * @package    Flextension
 * @subpackage Modules/Live_Search/Admin
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Live Search Admin class.
 */
class Flextension_Live_Search_Admin extends Flextension_Module_Admin {

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

		// Add a new settings page into Settings -> Live Search.
		add_options_page(
			esc_html__( 'Live Search Settings', 'flextension' ),
			esc_html__( 'Live Search', 'flextension' ),
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
		$settings = flextension_live_search_settings();

		// Register a new settings section on Settings -> Live Search.
		add_settings_section(
			'default',
			'',
			null,
			$this->settings_page_slug()
		);

		// Register settings fields.
		$post_types = get_post_types( array( 'exclude_from_search' => false ) );

		/**
		 * Filters the list of post types for the Live Search.
		 *
		 * @param array $post_types An array list of post types.
		 */
		$post_types = apply_filters( 'flextension_live_search_post_types', $post_types );

		if ( isset( $post_types['attachment'] ) ) {
			unset( $post_types['attachment'] );
		}

		$post_type_options = array();

		foreach ( $post_types as $post_type ) {
			$label = $post_type;

			$post_type_object = get_post_type_object( $post_type );
			if ( null !== $post_type_object ) {
				$label = $post_type_object->label;
			}

			$post_type_options[ $post_type ] = $label;
		}

		add_settings_field(
			'post_types',
			esc_html__( 'Post types', 'flextension' ),
			array( $this, 'settings_field' ),
			$this->settings_page_slug(),
			'default',
			array(
				'type'        => 'checkbox_list',
				'name'        => 'flext_live_search[post_types]',
				'value'       => $settings['post_types'],
				'options'     => $post_type_options,
				'description' => esc_html__( 'Select post types to display in Live Search suggestions.', 'flextension' ),
			)
		);

		add_settings_field(
			'suggestions',
			esc_html__( 'Number of suggestions', 'flextension' ),
			array( $this, 'settings_field' ),
			$this->settings_page_slug(),
			'default',
			array(
				'type'        => 'select',
				'name'        => 'flext_live_search[suggestions]',
				'value'       => $settings['suggestions'],
				'options'     => array(
					1  => 1,
					2  => 2,
					3  => 3,
					4  => 4,
					5  => 5,
					6  => 6,
					7  => 7,
					8  => 8,
					9  => 9,
					10 => 10,
				),
				'description' => esc_html__( 'Select number of search suggestion items per post type.', 'flextension' ),
			)
		);

		add_settings_field(
			'show_thumbnail',
			esc_html__( 'Featured image', 'flextension' ),
			array( $this, 'settings_field' ),
			$this->settings_page_slug(),
			'default',
			array(
				'type'        => 'checkbox',
				'name'        => 'flext_live_search[show_thumbnail]',
				'value'       => (bool) $settings['show_thumbnail'],
				'description' => esc_html__( 'Display featured images in search results.', 'flextension' ),
			)
		);

		add_settings_field(
			'show_date',
			esc_html__( 'Post date', 'flextension' ),
			array( $this, 'settings_field' ),
			$this->settings_page_slug(),
			'default',
			array(
				'type'        => 'checkbox',
				'name'        => 'flext_live_search[show_date]',
				'value'       => (bool) $settings['show_date'],
				'description' => esc_html__( 'Display post date in search results.', 'flextension' ),
			)
		);

		add_settings_field(
			'show_author',
			esc_html__( 'Post author', 'flextension' ),
			array( $this, 'settings_field' ),
			$this->settings_page_slug(),
			'default',
			array(
				'type'        => 'checkbox',
				'name'        => 'flext_live_search[show_author]',
				'value'       => (bool) $settings['show_author'],
				'description' => esc_html__( 'Display author in search results.', 'flextension' ),
			)
		);

		// Register a new setting.
		register_setting(
			$this->settings_page_slug(),
			'flext_live_search',
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

}
