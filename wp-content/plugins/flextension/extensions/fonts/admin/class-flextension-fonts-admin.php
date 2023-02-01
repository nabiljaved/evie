<?php
/**
 * Fonts Settings
 *
 * @package    Flextension
 * @subpackage Extensions/Fonts/Admin
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Fonts Admin Class
 */
class Flextension_Fonts_Admin extends Flextension_Module_Admin {

	/**
	 * All available font types.
	 *
	 * @var array
	 */
	public $font_types = array();

	/**
	 * Initializes the module.
	 */
	public function initialize() {
		add_action( 'admin_menu', array( $this, 'register_settings_page' ) );
	}

	/**
	 * Registers a new settings page under Settings.
	 */
	public function register_settings_page() {
		$this->font_types = flextension_font_types();
		if ( ! empty( $this->font_types ) ) {
			// Add a new settings page into Appearance -> Fonts.
			add_submenu_page(
				flextension_get_admin_page( 'themes' ),
				esc_html__( 'Fonts Settings', 'flextension' ),
				esc_html__( 'Fonts', 'flextension' ),
				'manage_options',
				$this->settings_page_slug(),
				array( $this, 'settings_page' )
			);
		}
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
			<nav class="nav-tab-wrapper wp-clearfix" aria-label="Secondary menu">
			<?php

			$page_url = flextension_get_admin_page_url( $this->name, 'themes' );

			$current_tab = isset( $_REQUEST['tab'] ) ? sanitize_title( wp_unslash( $_REQUEST['tab'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

			if ( empty( $current_tab ) ) {
				$keys = array_keys( $this->font_types );
				if ( ! empty( $keys[0] ) ) {
					wp_safe_redirect( add_query_arg( array( 'tab' => $keys[0] ), $page_url ) );
				}
			}

			foreach ( $this->font_types as $tab => $label ) {

				$class = ( $tab === $current_tab ) ? 'nav-tab nav-tab-active' : 'nav-tab';

				echo sprintf(
					'<a class="%1$s" href="%2$s">%3$s</a>',
					esc_attr( $class ),
					esc_attr( add_query_arg( 'tab', $tab, $page_url ) ),
					esc_html( $label )
				);
			}
			?>
			</nav>
			<form action="options.php" method="post">
				<?php
				// Output security fields for this page.
				settings_fields( $this->settings_page_slug() );
				// Output all settings sections added to this page.
				do_settings_sections( $this->settings_page_slug() );
				// Prints out a save changes button.
				submit_button();
				?>
				<input type="hidden" name="tab" value="<?php echo esc_attr( $current_tab ); ?>" />
			</form>
		</div>
		<?php
	}

}
