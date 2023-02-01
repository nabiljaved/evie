<?php
/**
 * Social Links Settings
 *
 * @package    Flextension
 * @subpackage Modules/Social_Links/Admin
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Social Links Admin Class
 */
class Flextension_Social_Links_Admin extends Flextension_Module_Admin {

	/**
	 * The setting values.
	 *
	 * @var array
	 */
	private $settings = array();

	/**
	 * The current tab.
	 *
	 * @var string
	 */
	private $current_tab = '';

	/**
	 * Initializes the module.
	 */
	public function initialize() {
		add_action( 'admin_menu', array( $this, 'register_settings_page' ) );
		// If it is on a module settings page, register settings.
		if ( $this->is_settings_page() ) {

			// The setting values from database.
			$this->settings = flextension_social_links_settings();

			$this->current_tab = isset( $_REQUEST['tab'] ) ? sanitize_title( wp_unslash( $_REQUEST['tab'] ) ) : 'general'; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

			add_action( 'admin_init', array( $this, 'register_settings' ) );

			// Enqueue admin scripts.
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		}
	}

	/**
	 * Registers a new settings page under Settings.
	 */
	public function register_settings_page() {

		// Add a new settings page into Settings -> Social Links.
		add_options_page(
			esc_html__( 'Social Links', 'flextension' ),
			esc_html__( 'Social Links', 'flextension' ),
			'manage_options',
			$this->settings_page_slug(),
			array( $this, 'settings_page' )
		);

	}

	/**
	 * Registers a new settings section under Settings.
	 */
	public function register_settings() {

		switch ( $this->current_tab ) {
			case 'general':
				$this->general_settings();
				break;
			case 'links':
				$this->links_settings();
				break;
		}
	}

	/**
	 * Registers General settings.
	 */
	public function general_settings() {
		// Register a new settings section on Settings -> Social Links.
		add_settings_section(
			'general',
			'',
			array( $this, 'general_settings_section' ),
			$this->settings_page_slug()
		);

		$social_links = array();

		$all_social_links = flextension_social_links_list();
		if ( ! empty( $all_social_links ) ) {
			foreach ( $all_social_links as $name => $link ) {
				$social_links[ $name ] = '<i class="' . esc_attr( $link['icon'] ) . '"></i> ' . $link['title'];
			}
		}

		add_settings_field(
			'all_items',
			esc_html__( 'Social links', 'flextension' ),
			array( $this, 'settings_field' ),
			$this->settings_page_slug(),
			'general',
			array(
				'type'          => 'checkbox_list',
				'value'         => $this->settings['active_items'],
				'options'       => $social_links,
				'layout'        => 'grid',
				'wrapper_class' => 'flext-social-links-active',
			)
		);

		$fields = array();
		if ( ! empty( $this->settings['active_items'] ) && is_array( $this->settings['active_items'] ) ) {
			foreach ( $this->settings['active_items'] as $name ) {
				$fields[] = array(
					'value' => $name,
					'icon'  => $all_social_links[ $name ]['icon'],
					'label' => $all_social_links[ $name ]['title'],
				);
			}
		}

		add_settings_field(
			'active_items',
			esc_html__( 'Order', 'flextension' ),
			array( $this, 'settings_field' ),
			$this->settings_page_slug(),
			'general',
			array(
				'type'          => 'fields_list',
				'name'          => 'flext_social_links[active_items]',
				'value'         => $fields,
				'template'      => array(
					'type'  => 'hidden',
					'label' => ' ',
				),
				'add_button'    => false,
				'wrapper_class' => 'flext-social-links-order',
			)
		);

		add_settings_field(
			'new_tab',
			esc_html__( 'Link target', 'flextension' ),
			array( $this, 'settings_field' ),
			$this->settings_page_slug(),
			'general',
			array(
				'type'        => 'checkbox',
				'name'        => 'flext_social_links[new_tab]',
				'value'       => $this->settings['new_tab'],
				'description' => esc_html__( 'Open link in a new tab', 'flextension' ),
			)
		);

		// Register a new setting.
		register_setting(
			$this->settings_page_slug(),
			'flext_social_links',
			array(
				'type'              => 'array',
				'sanitize_callback' => array( $this, 'sanitize_settings' ),
			)
		);
	}

	/**
	 * Registers Links settings.
	 */
	public function links_settings() {

		if ( empty( $this->settings['active_items'] ) ) {
			return;
		}

		// Register a new settings section on Settings -> Social Links.
		add_settings_section(
			'links',
			'',
			array( $this, 'links_settings_section' ),
			$this->settings_page_slug()
		);

		$social_links = array();
		foreach ( $this->settings['active_items'] as $name ) {
			$social_links[ $name ] = isset( $this->settings['social_links'][ $name ] ) ? $this->settings['social_links'][ $name ] : array();
		}

		$all_links = flextension_social_links_list();

		$fields = array();
		foreach ( $social_links as $name => $values ) {

			if ( ! isset( $all_links[ $name ] ) ) {
				continue;
			}

			$fieldset = array(
				'name'   => $name,
				'icon'   => $all_links[ $name ]['icon'],
				'legend' => $all_links[ $name ]['title'],
				'type'   => 'fieldset',
			);

			if ( ! isset( $all_links[ $name ]['fields'] ) ) {
				$fieldset['fields'] = array(
					array(
						'name'        => 'url',
						'value'       => isset( $values['url'] ) ? $values['url'] : '',
						'placeholder' => sprintf(
							/* translators: %s: Name of the social media */
							esc_html__( '%s URL', 'flextension' ),
							$all_links[ $name ]['title']
						),
					),
				);
			} else {
				$fieldset['fields'] = array();
				if ( is_array( $all_links[ $name ]['fields'] ) ) {
					foreach ( $all_links[ $name ]['fields'] as $field ) {
						if ( isset( $field['name'] ) && ! empty( $field['name'] ) ) {
							$field['value']       = isset( $values[ $field['name'] ] ) ? $values[ $field['name'] ] : '';
							$fieldset['fields'][] = $field;
						}
					}
				}
			}

			$link = '{url}';
			if ( isset( $all_links[ $name ]['link'] ) ) {
				$link = $all_links[ $name ]['link'];
			}

			$fieldset['fields'][] = array(
				'name'       => 'label',
				'label'      => esc_html__( 'Link:', 'flextension' ),
				'type'       => 'label',
				'text'       => $link,
				'class'      => 'flext-social-link',
				'attributes' => array(
					'data-format' => $link,
				),
			);

			$fields[ $name ] = $fieldset;
		}

		add_settings_field(
			'social_links',
			esc_html__( 'Social Links', 'flextension' ),
			array( $this, 'settings_field' ),
			$this->settings_page_slug(),
			'links',
			array(
				'type'          => 'custom_fields',
				'name'          => 'flext_social_links[social_links]',
				'fields'        => $fields,
				'wrapper_class' => 'flext-social-links',
			)
		);

		// Register a new setting.
		register_setting(
			$this->settings_page_slug(),
			'flext_social_links',
			array(
				'type'              => 'array',
				'sanitize_callback' => array( $this, 'sanitize_settings' ),
			)
		);
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
			<?php $this->settings_tabs(); ?>
			<form action="options.php" method="post">
				<?php
				// Output security fields for this page.
				settings_fields( $this->settings_page_slug() );
				// Output all settings sections added to this page.
				do_settings_sections( $this->settings_page_slug() );
				// Prints out a save changes button.
				submit_button();
				?>
				<input type="hidden" name="tab" value="<?php echo esc_attr( $this->current_tab ); ?>" />
			</form>
		</div>
		<?php
	}

	/**
	 * Prints out the settings tabs menu.
	 */
	private function settings_tabs() {
		?>
		<nav class="nav-tab-wrapper wp-clearfix" aria-label="Secondary menu">
		<?php
		$tabs = array(
			'general' => esc_html__( 'General', 'flextension' ),
		);

		if ( ! empty( $this->settings['active_items'] ) ) {
			$tabs['links'] = esc_html__( 'Links', 'flextension' );
		}

		$page_url = flextension_get_admin_page_url( $this->name );

		foreach ( $tabs as $tab => $label ) {

			$class = ( $tab === $this->current_tab ) ? 'nav-tab nav-tab-active' : 'nav-tab';

			if ( ! empty( $tab ) ) {
				$page_url = add_query_arg( 'tab', $tab, $page_url );
			}

			echo sprintf(
				'<a class="%1$s" href="%2$s">%3$s</a>',
				esc_attr( $class ),
				esc_attr( $page_url ),
				esc_html( $label )
			);
		}
		?>
		</nav>
		<?php
	}

	/**
	 * Prints out the settings section.
	 */
	public function general_settings_section() {
		?>
		<p>
			<?php echo esc_html__( 'Select the social links listed below to use in the Social Icons widget and the Social Icons for post authors.', 'flextension' ); ?>
		</p>
		<?php
	}

	/**
	 * Prints out the settings section.
	 */
	public function links_settings_section() {
		?>
		<p>
			<?php
			echo sprintf(
				/* translators: %s: The link to Authors screen */
				esc_html__( 'These are global settings of the social links for your site. You can also configure the settings for the post authors on %s screen.', 'flextension' ),
				'<a href="' . esc_attr( admin_url( 'users.php' ) ) . '">' . esc_html__( 'Users', 'flextension' ) . '</a>'
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

		$active_items = flextension_social_icons();

		$new_values = $this->settings;

		if ( isset( $wp_settings_fields[ $this->settings_page_slug() ][ $this->current_tab ] ) ) {
			foreach ( $wp_settings_fields[ $this->settings_page_slug() ][ $this->current_tab ] as $field ) {

				$value = array();

				if ( isset( $values[ $field['id'] ] ) ) {
					if ( 'links' === $this->current_tab ) {
						$value = $this->sanitize_links( $values[ $field['id'] ], $active_items );
					} else {
						$value = $this->sanitize_field( $values[ $field['id'] ], $field['args'] );
					}
				}

				$new_values[ $field['id'] ] = $value;
			}
		}

		return $new_values;
	}

	/**
	 * Returns whether the setting is valid.
	 *
	 * @param array $values Setting values.
	 * @param array $active_items Active social items.
	 * @return bool Whether the setting is valid.
	 */
	public function sanitize_links( $values, $active_items = array() ) {
		if ( ! empty( $values ) ) {
			foreach ( $values as $name => $value ) {
				if ( isset( $active_items[ $name ]['fields'] ) && ! flextension_social_link_setting_is_valid( $value, $active_items[ $name ]['fields'] ) ) {
					unset( $values[ $name ] );
				}
			}
		}

		return $values;
	}

	/**
	 * Enqueues scripts and stylesheets for the admin page.
	 */
	public function admin_enqueue_scripts() {
		wp_enqueue_style( 'flextension-social-links-editor', plugins_url( 'css/style.css', __FILE__ ), array( 'flextension' ), flextension_get_setting( 'version' ) );

		wp_enqueue_script( 'flextension-social-links-editor', plugins_url( 'js/index.js', __FILE__ ), array( 'flextension' ), flextension_get_setting( 'version' ), true );
	}

}
