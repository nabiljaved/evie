<?php
/**
 * Module: Flextension_Module_Admin class
 *
 * Module on admin page
 *
 * @package    Flextension
 * @subpackage Includes
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Module admin class.
 */
class Flextension_Module_Admin {

	/**
	 * The module name.
	 *
	 * @var string
	 */
	public $name = null;

	/**
	 * The module control manager.
	 *
	 * @var Flextension_Module_Control $control The module control manager.
	 */
	public $control = null;

	/**
	 * Initializes the class, adds actions and filters.
	 *
	 * @param string $name The module name.
	 */
	public function __construct( $name = null ) {

		// Init name of module.
		$this->name = $name;

		// Flextension Module Control Manager.
		require_once plugin_dir_path( __FILE__ ) . 'class-flextension-module-control.php';

		$this->control = new Flextension_Module_Control();

		// Initialize.
		$this->initialize();
	}

	/**
	 * Initializes the module.
	 */
	public function initialize() {
		/* do nothing */
	}

	/**
	 * Returns the settings page slug for the module.
	 *
	 * @param string $name Optional. The page name.
	 * @return string A page slug for the settings page.
	 */
	public function settings_page_slug( $name = '' ) {
		if ( empty( $name ) ) {
			$name = $this->name;
		}
		return flextension_get_page_slug( $name );
	}

	/**
	 * Returns whether the current page is a module settings page.
	 *
	 * @global string $pagenow The current admin page.
	 *
	 * @param string $page Optional. The admin settings page.
	 * @param string $type Optional. The admin settings type.
	 * @param string $tab  Optional. The name of tab.
	 * @return bool Whether the current page is a module settings page.
	 */
	public function is_settings_page( $page = '', $type = 'general', $tab = '' ) {
		global $pagenow;

		if ( empty( $page ) ) {
			$page = $this->settings_page_slug();
		}

		$is_valid_tab = true;
		if ( ! empty( $tab ) ) {
			$current_tab  = isset( $_REQUEST['tab'] ) ? sanitize_title( wp_unslash( $_REQUEST['tab'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$is_valid_tab = ( $tab === $current_tab );
		}

		if ( flextension_get_admin_page( $type ) === $pagenow && isset( $_GET['page'] ) && sanitize_title( wp_unslash( $_GET['page'] ) ) === $page && true === $is_valid_tab ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			return true;
		} elseif ( flextension_get_admin_page( $page ) === $pagenow && true === $is_valid_tab ) {
			return true;
		}

		if ( 'options.php' === $pagenow && isset( $_POST['option_page'] ) && sanitize_title( wp_unslash( $_POST['option_page'] ) ) === $page && true === $is_valid_tab ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
			return true;
		}

		return false;
	}

	/**
	 * Prints out the settings field.
	 *
	 * @param array $args Optional. Extra arguments used when outputting the field.
	 */
	public function settings_field( $args = array() ) {
		$this->control->render( $args );
	}

	/**
	 * Sanitizes a field value.
	 *
	 * @param mixed $value          The value to sanitize.
	 * @param array $field          The field settings.
	 * @param mixed $original_value The original value.
	 * @return mixed The sanitized value.
	 */
	public function sanitize_field( $value = '', $field = '', $original_value = '' ) {
		return $this->control->sanitize( $value, $field, $original_value );
	}

}
