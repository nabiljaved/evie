<?php
/**
 * Mega Menu Customize
 *
 * @package    Evie_XT
 * @subpackage Modules/Mega_Menu/Admin
 * @version    1.0.0
 */

/**
 * Mega Menu Customize class
 */
class Evie_Mega_Menu_Customize {

	/**
	 * Registered settings for the Mega Menu.
	 *
	 * @var array
	 */
	protected $settings = array();

	/**
	 * Initializes the class, adds actions and filters.
	 */
	public function __construct() {

		// Register customize preview.
		add_action( 'customize_register', array( $this, 'customize_register' ), 1000 );

		if ( is_admin() ) {
			// Menu settings in the Customizer, Appearance -> Customize -> Menus.
			add_filter( 'wp_setup_nav_menu_item', array( $this, 'setup_nav_menu_item' ) );

			add_action( 'wp_nav_menu_item_custom_fields_customize_template', array( $this, 'add_mega_menu_field_template' ) );

			add_action( 'customize_save_after', array( $this, 'customize_save' ) );

			add_action( 'customize_controls_enqueue_scripts', array( $this, 'customize_controls_enqueue_scripts' ) );

			// Menu settings in Appearance -> Menus.
			add_action( 'wp_nav_menu_item_custom_fields', array( $this, 'add_mega_menu_fields' ), 10 );

			add_action( 'wp_update_nav_menu_item', array( $this, 'save_mega_menu_fields' ), 10, 2 );

			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		}
	}

	/**
	 * Registers customize preview for the menu item.
	 *
	 * @param WP_Customize_Manager $wp_customize The customize instance.
	 */
	public function customize_register( WP_Customize_Manager $wp_customize ) {
		if ( $wp_customize->settings_previewed() ) {
			foreach ( $wp_customize->settings() as $setting ) {
				if ( $setting instanceof WP_Customize_Nav_Menu_Item_Setting ) {
					$this->customize_preview_setting( $setting );
				}
			}
		}
	}

	/**
	 * Adds filters to supply the setting's value of menu item.
	 *
	 * @see WP_Customize_Nav_Menu_Item_Setting::preview()
	 *
	 * @param WP_Customize_Nav_Menu_Item_Setting $setting Setting.
	 */
	public function customize_preview_setting( WP_Customize_Nav_Menu_Item_Setting $setting ) {
		$values = $this->get_sanitized_values( $setting );
		if ( null === $values ) {
			return;
		}

		$this->settings[ $setting->post_id ] = $values;

		add_filter( 'get_post_metadata', array( $this, 'get_preview_metadata' ), 10, 4 );
	}

	/**
	 * Returns the preview metadata for the menu item.
	 *
	 * @param mixed  $value     The value to return, either a single metadata value or an array
	 *                          of values depending on the value of `$single`. Default null.
	 * @param int    $object_id ID of the object metadata is for.
	 * @param string $meta_key  Metadata key.
	 * @param bool   $single    Whether to return only the first value of the specified `$meta_key`.
	 * @return mixed $value New metadata value.
	 */
	public function get_preview_metadata( $value, $object_id, $meta_key, $single ) {
		if ( isset( $this->settings[ $object_id ] ) && in_array( $meta_key, array( '_evie_mega_menu', '_evie_mega_menu_columns' ), true ) ) {
			$key = substr( $meta_key, 1 );
			if ( isset( $this->settings[ $object_id ][ $key ] ) ) {
				if ( $single && is_array( $this->settings[ $object_id ][ $key ] ) ) {
					return $this->settings[ $object_id ][ $key ][0];
				} else {
					return $this->settings[ $object_id ][ $key ];
				}
			}
		}
		return $value;
	}

	/**
	 * Adds a Mega Menu field to the menu item in the Customizer.
	 */
	public function add_mega_menu_field_template() {
		?>
		<p class="field-evie-mega-menu description-thin">
			<label for="menu-item-evie-mega-menu-{{ data.menu_item_id }}">
				<?php echo esc_html__( 'Mega Menu', 'evie-xt' ); ?>
				<br />
				<select id="menu-item-evie-mega-menu-{{ data.menu_item_id }}" name="menu-item-evie-mega-menu-{{ data.menu_item_id }}" class="menu-item-evie-mega-menu" >
					<option value=""><?php echo esc_html__( 'Disable', 'evie-xt' ); ?></option>
					<option value="menu"><?php echo esc_html__( 'Menu items', 'evie-xt' ); ?></option>
					<option value="post"><?php echo esc_html__( 'Recent posts', 'evie-xt' ); ?></option>
				</select>
			</label>
		</p>
		<p class="field-evie-mega-menu-columns description-thin">
			<label for="menu-item-evie-mega-menu-columns-{{ data.menu_item_id }}">
				<?php echo esc_html__( 'Columns', 'evie-xt' ); ?>
				<br />
				<select id="menu-item-evie-mega-menu-columns-{{ data.menu_item_id }}" name="menu-item-evie-mega-menu-columns-{{ data.menu_item_id }}" class="menu-item-evie-mega-menu-columns" >
					<?php for ( $i = 2; $i <= 6; $i++ ) : ?>
					<option value="<?php echo esc_attr( $i ); ?>">
						<?php

						echo sprintf(
							/* translators: %s: Number of columns. */
							esc_html__( '%s columns', 'evie-xt' ),
							esc_html( $i )
						);

						?>
					</option>
					<?php endfor; ?>
				</select>
			</label>
		</p>
			<?php
	}

	/**
	 * Adds a Mega Menu metadata to the menu item.
	 *
	 * @param object $menu_item The menu item object.
	 */
	public function setup_nav_menu_item( $menu_item ) {
		if ( isset( $menu_item->post_type ) && 'nav_menu_item' === $menu_item->post_type && isset( $menu_item->ID ) && ! isset( $menu_item->evie_mega_menu ) ) {
			$menu_item->evie_mega_menu         = get_post_meta( $menu_item->ID, '_evie_mega_menu', true );
			$menu_item->evie_mega_menu_columns = get_post_meta( $menu_item->ID, '_evie_mega_menu_columns', true );
		}
		return $menu_item;
	}

	/**
	 * Saves menu item settings.
	 *
	 * @param WP_Customize_Manager $wp_customize The customize instance.
	 */
	public function customize_save( WP_Customize_Manager $wp_customize ) {
		foreach ( $wp_customize->settings() as $setting ) {
			if ( $setting instanceof WP_Customize_Nav_Menu_Item_Setting && $setting->check_capabilities() ) {
				$this->save_setting( $setting );
			}
		}
	}

	/**
	 * Gets sanitized posted values for Mega Menu setting.
	 *
	 * @param WP_Customize_Nav_Menu_Item_Setting $setting Setting.
	 *
	 * @return array|string|null Setting values or null if no posted value present.
	 */
	public function get_sanitized_values( WP_Customize_Nav_Menu_Item_Setting $setting ) {

		if ( ! $setting->post_value() ) {
			return null;
		}

		$unsanitized_values = $setting->manager->unsanitized_post_values();
		$menu_settings      = isset( $unsanitized_values[ $setting->id ] ) ? $unsanitized_values[ $setting->id ] : array();
		if ( ! empty( $menu_settings ) && isset( $menu_settings['evie_mega_menu'] ) ) {
			$mega_menu = $menu_settings['evie_mega_menu'];
			if ( ! in_array( $mega_menu, array( '', 'menu', 'post' ), true ) ) {
				$menu_settings['evie_mega_menu'] = '';
			}

			if ( 'menu' === $menu_settings['evie_mega_menu'] ) {
				$menu_settings['evie_mega_menu_columns'] = absint( $menu_settings['evie_mega_menu_columns'] );
			} else {
				$menu_settings['evie_mega_menu_columns'] = 0;
			}

			return $menu_settings;
		}

		return '';
	}

	/**
	 * Saves changes to the nav menu item Mega Menu.
	 *
	 * @see WP_Customize_Nav_Menu_Item_Setting::update()
	 *
	 * @param WP_Customize_Nav_Menu_Item_Setting $setting Setting.
	 */
	public function save_setting( WP_Customize_Nav_Menu_Item_Setting $setting ) {
		$values = $this->get_sanitized_values( $setting );
		if ( ! empty( $values['evie_mega_menu'] ) ) {
			update_post_meta( $setting->post_id, '_evie_mega_menu', $values['evie_mega_menu'] );
		}

		if ( ! empty( $values['evie_mega_menu_columns'] ) ) {
			update_post_meta( $setting->post_id, '_evie_mega_menu_columns', $values['evie_mega_menu_columns'] );
		}
	}

	/**
	 * Adds Mega Menu fields to the menu item.
	 *
	 * @param int $item_id Menu item ID.
	 */
	public function add_mega_menu_fields( $item_id ) {

		$mega_menu = get_post_meta( $item_id, '_evie_mega_menu', true );
		$columns   = get_post_meta( $item_id, '_evie_mega_menu_columns', true );
		?>
		<p class="field-evie-mega-menu description-thin">
			<label for="menu-item-evie-mega-menu-<?php echo esc_attr( $item_id ); ?>">
				<?php echo esc_html__( 'Mega Menu', 'evie-xt' ); ?>
				<br />
				<select id="menu-item-evie-mega-menu-<?php echo esc_attr( $item_id ); ?>" name="evie_mega_menu[<?php echo esc_attr( $item_id ); ?>]" class="menu-item-evie-mega-menu">
					<option value="" <?php selected( $mega_menu, '' ); ?>><?php echo esc_html__( 'Disable', 'evie-xt' ); ?></option>
					<option value="menu" <?php selected( $mega_menu, 'menu' ); ?>><?php echo esc_html__( 'Menu items', 'evie-xt' ); ?></option>
					<option value="post" <?php selected( $mega_menu, 'post' ); ?>><?php echo esc_html__( 'Recent posts', 'evie-xt' ); ?></option>
				</select>
			</label>
		</p>
		<p class="field-evie-mega-menu-columns description-thin">
			<label for="menu-item-evie-mega-menu-columns-<?php echo esc_attr( $item_id ); ?>">
				<?php echo esc_html__( 'Columns', 'evie-xt' ); ?>
				<br />
				<select id="menu-item-evie-mega-menu-columns-<?php echo esc_attr( $item_id ); ?>" name="evie_mega_menu_columns[<?php echo esc_attr( $item_id ); ?>]" class="menu-item-evie-mega-menu-columns">
					<?php for ( $i = 2; $i <= 6; $i++ ) : ?>
					<option value="<?php echo esc_attr( $i ); ?>" <?php selected( $columns, $i ); ?>>
						<?php

						echo sprintf(
							/* translators: %s: Number of columns. */
							esc_html__( '%s columns', 'evie-xt' ),
							esc_html( $i )
						);

						?>
					</option>
					<?php endfor; ?>
				</select>
			</label>
		</p>
			<?php

	}

	/**
	 * Saves the menu item meta.
	 *
	 * @param int $menu_id         ID of the updated menu.
	 * @param int $menu_item_db_id ID of the new menu item.
	 */
	public function save_mega_menu_fields( $menu_id, $menu_item_db_id ) {
		/**
		 * Nonce has been verified by WordPress core in wp-admin/nav-menus.php before doing this action.
		 */
		$mega_menu = isset( $_POST['evie_mega_menu'][ $menu_item_db_id ] ) ? sanitize_key( $_POST['evie_mega_menu'][ $menu_item_db_id ] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing
		if ( ! empty( $mega_menu ) ) {
			update_post_meta( $menu_item_db_id, '_evie_mega_menu', $mega_menu );
		} else {
			delete_post_meta( $menu_item_db_id, '_evie_mega_menu' );
		}

		$mega_menu_columns = isset( $_POST['evie_mega_menu_columns'][ $menu_item_db_id ] ) ? absint( $_POST['evie_mega_menu_columns'][ $menu_item_db_id ] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Missing
		if ( $mega_menu_columns > 0 ) {
			update_post_meta( $menu_item_db_id, '_evie_mega_menu_columns', $mega_menu_columns );
		} else {
			delete_post_meta( $menu_item_db_id, '_evie_mega_menu_columns' );
		}
	}

	/**
	 * Enqueues the scripts and stylesheets for the customizer.
	 */
	public function customize_controls_enqueue_scripts() {
		wp_enqueue_style( 'evie-mega-menu-editor', plugins_url( 'css/editor.css', __FILE__ ), array(), EVIE_XT_VERSION );
		wp_enqueue_script( 'evie-mega-menu-customizer', plugins_url( 'js/customizer.js', __FILE__ ), array( 'customize-nav-menus' ), EVIE_XT_VERSION, true );
	}

	/**
	 * Enqueues the scripts and stylesheets for the admin page.
	 */
	public function admin_enqueue_scripts() {
		wp_enqueue_style( 'evie-mega-menu-editor', plugins_url( 'css/editor.css', __FILE__ ), array(), EVIE_XT_VERSION );
		wp_enqueue_script( 'evie-mega-menu-editor', plugins_url( 'js/editor.js', __FILE__ ), array(), EVIE_XT_VERSION, true );
	}

}
