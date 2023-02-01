<?php
/**
 * User Profile Social Links
 *
 * @package    Flextension
 * @subpackage Modules/Social_Links/Admin
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * User Profile Social Links Class.
 */
class Flextension_Social_Links_User_Profile {

	/**
	 * The setting values.
	 *
	 * @var array
	 */
	private $settings = array();

	/**
	 * Initializes the class, adds actions and filters.
	 */
	public function __construct() {

		add_action( 'show_user_profile', array( $this, 'add_user_social_links' ), 5 );
		add_action( 'edit_user_profile', array( $this, 'add_user_social_links' ), 5 );

		add_action( 'personal_options_update', array( $this, 'save_user_social_links' ) );
		add_action( 'edit_user_profile_update', array( $this, 'save_user_social_links' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
	}

	/**
	 * Adds user social links setting fields.
	 *
	 * @param WP_User $user The current WP_User object.
	 */
	public function add_user_social_links( $user ) {

		if ( ! $this->is_capable( $user ) ) {
			return;
		}

		// The setting values from database.
		$settings = flextension_social_links_settings();

		if ( empty( $settings['active_items'] ) ) {
			return;
		}

		$social_links = array();
		foreach ( $settings['active_items'] as $name ) {
			$social_links[ $name ] = (array) get_user_meta( $user->ID, $name, true );
		}

		$control = new Flextension_Module_Control();

		$all_links = flextension_social_links_list();
		?>
		<h2 id="flext-social-links"><?php echo esc_html__( 'Social Links', 'flextension' ); ?></h2>
		<table class="form-table flext-social-links" role="presentation">
			<?php
			foreach ( $social_links as $name => $values ) {

				if ( ! isset( $all_links[ $name ] ) ) {
					continue;
				}
				?>
				<tr>
					<th>
						<label for="<?php echo esc_attr( $name ); ?>"><?php echo esc_html( $all_links[ $name ]['title'] ); ?></label>
					</th>
					<td>
						<?php

						$fieldset = array(
							'name' => $name,
							'type' => 'fieldset',
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

						$control->render( $fieldset );

						?>
					</td>
				</tr>
				<?php
			}
			?>
		</table>
		<?php
	}

	/**
	 * Saves user profile social links settings.
	 *
	 * @param int $user_id The user ID.
	 */
	public function save_user_social_links( $user_id ) {

		// Bail if we're doing an auto save.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( ! $this->is_capable( $user_id ) ) {
			return;
		}

		$active_items = flextension_social_icons();

		if ( empty( $active_items ) ) {
			return;
		}

		foreach ( $active_items as $name => $link ) {
			/**
			 * Nonce has been verified by WordPress before doing this action.
			 *
			 * @see wp-admin/user-edit.php for more details.
			 */
			$value = isset( $_POST[ $name ] ) ? array_map( 'sanitize_text_field', (array) wp_unslash( $_POST[ $name ] ) ) : array(); // phpcs:ignore WordPress.Security.NonceVerification.Missing

			if ( ! flextension_social_link_setting_is_valid( $value, $link['fields'] ) ) {
				$value = array();
			}

			if ( ! empty( $value ) ) {
				update_user_meta( $user_id, $name, $value );
			} else {
				delete_user_meta( $user_id, $name );
			}
		}
	}

	/**
	 * Returns whether the user is capable of having Social Links.
	 *
	 * @param int|WP_User $user User ID or object.
	 * @return bool Whether the user is capable of having Social Links.
	 */
	public function is_capable( $user ) {
		/**
		 * Filters the user capability that allows having Social Links.
		 *
		 * @link https://wordpress.org/support/article/roles-and-capabilities/
		 *
		 * @param string $capability The capability name.
		 */
		$capability = apply_filters( 'flextension_social_links_user_capability', 'edit_posts' );

		return user_can( $user, $capability );
	}

	/**
	 * Enqueues scripts and stylesheets for the admin page.
	 *
	 * @param string $hook Current page.
	 */
	public function admin_enqueue_scripts( $hook ) {

		if ( in_array( $hook, array( 'profile.php', 'user-edit.php' ), true ) ) {

			wp_enqueue_style( 'flextension-social-links-editor', plugins_url( 'css/style.css', __FILE__ ), array( 'flextension' ), flextension_get_setting( 'version' ) );

			wp_enqueue_script( 'flextension-social-links-editor', plugins_url( 'js/index.js', __FILE__ ), array( 'flextension' ), flextension_get_setting( 'version' ), true );
		}
	}
}
