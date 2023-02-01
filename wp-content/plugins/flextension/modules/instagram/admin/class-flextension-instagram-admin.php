<?php
/**
 * Instagram Settings
 *
 * @package    Flextension
 * @subpackage Modules/Instagram/Admin
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Instagram Admin class.
 */
class Flextension_Instagram_Admin extends Flextension_Module_Admin {

	/**
	 * Initializes the module.
	 */
	public function initialize() {

		$this->maybe_update_access_token();

		add_action( 'admin_menu', array( $this, 'register_settings_page' ) );
		// If it is on a module settings page.
		if ( $this->is_settings_page() ) {
			// Enqueue admin scripts.
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		}
	}

	/**
	 * Registers a new settings page under Settings.
	 */
	public function register_settings_page() {

		// Add a new settings page into Settings -> Instagram.
		add_options_page(
			esc_html__( 'Instagram Feed Settings', 'flextension' ),
			esc_html__( 'Instagram Feed', 'flextension' ),
			'manage_options',
			$this->settings_page_slug(),
			array( $this, 'settings_page' )
		);

	}

	/**
	 * Prints out the settings page.
	 */
	public function settings_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient rights to view this page.', 'flextension' ) );
		}
		?>
		<div class="wrap flext-instagram-settings">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			<?php

			$this->process_request();

			$settings = flextension_instagram_settings();

			if ( ! empty( $settings['user_id'] ) ) {

				if ( empty( $settings['username'] ) ) {
					$settings = flextension_instagram_get_profile( $settings['user_id'] );
				}

				if ( ! empty( $settings['username'] ) ) {
					?>
					<h4><?php echo esc_html__( 'Currently connected to the following account:', 'flextension' ); ?></h4>
					<form method="post">
						<input type="hidden" name="action" value="remove-account" />
						<?php wp_nonce_field( 'remove-account', '_flext_remove-account' ); ?>
						<p class="flext-instagram-account">
							<a href="<?php echo esc_attr( 'https://www.instagram.com/' . $settings['username'] ); ?>" class="flext-instagram-username" target="_blank">
								<i class="dashicons dashicons-instagram"></i>
								<strong><?php echo esc_html( $settings['username'] ); ?></strong>
							</a>
							<button type="submit" class="button flext-remove-account-button"><?php echo esc_html__( 'Remove', 'flextension' ); ?></button>
						</p>
					</form>

					<h3><?php echo esc_html__( 'Refresh Cache', 'flextension' ); ?></h3>
					<p><?php echo esc_html__( 'Your Instagram feed is temporarily cached and preconfigured for hourly automatic updates. Click the button below to refresh it now.', 'flextension' ); ?></p>
					<form method="post">
						<input type="hidden" name="action" value="reset-cache" />
						<?php wp_nonce_field( 'reset-cache', '_flext_reset-cache' ); ?>
						<button type="submit" class="button flext-reset-cache-button"><i class="dashicons dashicons-update"></i><span><?php echo esc_html__( 'Refresh Cache', 'flextension' ); ?></span></button>
					</form>
					<?php
				} else {
					$settings['user_id'] = '';
					flextension_instagram_update_settings( $settings );
				}
			}

			if ( empty( $settings['user_id'] ) ) {
				?>
				<p><?php echo esc_html__( 'Connect to your Instagram account to get the Instagram Access Token. You can find more details in the documentation.', 'flextension' ); ?></p>
				<p>
					<a href="<?php echo esc_attr( flextension_instagram_token_request_url( flextension_get_admin_page_url( $this->name ) ) ); ?>" class="flext-instagram-connect-button"><?php echo esc_html__( 'Connect', 'flextension' ); ?></a>
				</p>
				<?php
			}
			?>
		</div>
		<?php
	}

	/**
	 * Processes the current request.
	 */
	public function process_request() {

		$action = isset( $_POST['action'] ) && ! empty( $_POST['action'] ) ? sanitize_key( $_POST['action'] ) : '';

		switch ( $action ) {
			case 'remove-account':
				check_admin_referer( 'remove-account', '_flext_remove-account' );

				flextension_instagram_update_settings(
					array(
						'account_type' => '',
						'username'     => '',
						'user_id'      => '',
						'access_token' => '',
					)
				);

				flextension_instagram_reset_cache();
				break;
			case 'reset-cache':
				check_admin_referer( 'reset-cache', '_flext_reset-cache' );

				flextension_instagram_reset_cache();
				?>
				<div class="notice notice-success is-dismissible">
					<p><?php echo esc_html__( 'Your Instagram media feed has been updated.', 'flextension' ); ?></p>
				</div>
				<?php
				break;
		}

	}

	/**
	 * Updates an access token if it is given.
	 */
	public function maybe_update_access_token() {

		$code = isset( $_GET['code'] ) && ! empty( $_GET['code'] ) ? sanitize_text_field( wp_unslash( $_GET['code'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

		if ( ! empty( $code ) ) {

			$settings = flextension_instagram_settings();

			$settings['access_token'] = $code;

			$settings['user_id'] = isset( $_GET['user_id'] ) ? sanitize_key( $_GET['user_id'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

			flextension_instagram_update_settings( $settings );

			wp_safe_redirect( flextension_get_admin_page_url( $this->name ) );
		}
	}

	/**
	 * Enqueues scripts and stylesheets for the admin page.
	 */
	public function admin_enqueue_scripts() {
		wp_enqueue_style( 'flextension-instagram-admin', plugins_url( 'css/style.css', __FILE__ ), array( 'flextension' ), flextension_get_setting( 'version' ) );
		wp_style_add_data( 'flextension-instagram-admin', 'rtl', 'replace' );
	}

}
