<?php
/**
 * Author Cover Image
 *
 * @package    Flextension
 * @subpackage Modules/Author/Admin
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Author Cover Image Class.
 */
class Flextension_Author_Cover_Image {

	/**
	 * Initializes the class, adds actions and filters.
	 */
	public function __construct() {

		add_action( 'personal_options_update', array( $this, 'save_user_cover_image' ) );
		add_action( 'edit_user_profile_update', array( $this, 'save_user_cover_image' ) );

		add_filter( 'user_profile_picture_description', array( $this, 'add_user_cover_image' ), 50, 2 );

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
	}

	/**
	 * Returns a user profile description with user Cover Image setting fields.
	 *
	 * @param string  $description The description that will be printed.
	 * @param WP_User $user        The current WP_User object.
	 * @return string The description that will be printed.
	 */
	public function add_user_cover_image( $description = '', $user = 0 ) {
		if ( $this->is_capable( $user ) ) {

			$description .= '</p>';

			$control = new Flextension_Module_Control();

			ob_start();

			$control->render(
				array(
					'name'          => 'flext_cover_image',
					'label'         => esc_html__( 'Cover Image', 'flextension' ),
					'type'          => 'image',
					'value'         => $user->get( '_flext_cover_image' ),
					'description'   => esc_html__( 'A cover image helps personalize your account.', 'flextension' ),
					'preview_size'  => 'large',
					'wrapper_class' => 'flext-author-cover-image',
				)
			);

			$control->render(
				array(
					'name'          => 'flext_cover_image_position',
					'label'         => esc_html__( 'Cover Image Position', 'flextension' ),
					'type'          => 'select',
					'value'         => $user->get( '_flext_cover_image_position' ),
					'description'   => esc_html__( 'A cover image background position.', 'flextension' ),
					'options'       => array(
						'left top'      => esc_html__( 'Left Top', 'flextension' ),
						'left center'   => esc_html__( 'Left Center', 'flextension' ),
						'left bottom'   => esc_html__( 'Left Bottom', 'flextension' ),
						'right top'     => esc_html__( 'Right Top', 'flextension' ),
						'right center'  => esc_html__( 'Right Center', 'flextension' ),
						'right bottom'  => esc_html__( 'Right Bottom', 'flextension' ),
						'center top'    => esc_html__( 'Center Top', 'flextension' ),
						''              => esc_html__( 'Center Center', 'flextension' ),
						'center bottom' => esc_html__( 'Center Bottom', 'flextension' ),
					),
					'dependencies'  => array(
						array(
							'name'     => 'flext_cover_image',
							'operator' => '!==',
							'value'    => '',
						),
					),
					'wrapper_class' => 'flext-author-cover-image',
				)
			);

			$description .= ob_get_clean() . '<p>';
		}

		return $description;
	}

	/**
	 * Saves user Cover Image settings.
	 *
	 * @param int $user_id The user ID.
	 */
	public function save_user_cover_image( $user_id ) {

		// Bail if we're doing an auto save.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( ! $this->is_capable( $user_id ) ) {
			return;
		}

		/**
		 * Nonce has been verified by WordPress before doing this action.
		 *
		 * @see wp-admin/user-edit.php for more details.
		 */
		$value = isset( $_POST['flext_cover_image'] ) ? absint( wp_unslash( $_POST['flext_cover_image'] ) ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Missing

		if ( ! empty( $value ) ) {
			update_user_meta( $user_id, '_flext_cover_image', $value );

			/**
			 * Background image position.
			 *
			 * @since 1.0.3
			 */
			$position = isset( $_POST['flext_cover_image_position'] ) ? sanitize_text_field( wp_unslash( $_POST['flext_cover_image_position'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing
			update_user_meta( $user_id, '_flext_cover_image_position', $position );
		} else {
			delete_user_meta( $user_id, '_flext_cover_image' );
			delete_user_meta( $user_id, '_flext_cover_image_position' );
		}
	}

	/**
	 * Returns whether the user is capable of having Cover Image.
	 *
	 * @param int|WP_User $user User ID or object.
	 * @return bool Whether the user is capable of having Cover Image.
	 */
	public function is_capable( $user ) {
		/**
		 * Filters the user capability that allows having Cover Image.
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

			wp_enqueue_style( 'flextension-author-cover-image-editor', plugins_url( 'css/style.css', __FILE__ ), array( 'flextension' ), flextension_get_setting( 'version' ) );

		}
	}
}
