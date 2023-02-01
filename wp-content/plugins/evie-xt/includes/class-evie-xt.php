<?php
/**
 * The core plugin class.
 *
 * Adds additional modules and extensions.
 *
 * @package Evie_XT
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Evie XT class.
 */
final class Evie_XT {

	/**
	 * The single instance of the class.
	 *
	 * @var Evie_XT $instance The single instance of the class.
	 */
	protected static $instance = null;

	/**
	 * Initializes the class, adds actions and filters.
	 */
	public function __construct() {
		// Include core functions.
		require_once EVIE_XT_PATH . 'includes/template-functions.php';
		require_once EVIE_XT_PATH . 'includes/class-evie-xt-update-checker.php';

		add_filter( 'flextension_get_setting', array( $this, 'get_setting' ), 10, 2 );

		// Set version number of the modules.
		add_filter( 'flextension_modules_version', array( $this, 'modules_version' ) );

		// Register extensions.
		add_action( 'flextension_register_extensions', array( $this, 'register_extensions' ) );

		// Register modules.
		add_action( 'flextension_register_modules', array( $this, 'register_modules' ) );

		// Load plugin language.
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		if ( is_admin() ) {
			$this->check_update();
		}
	}

	/**
	 * Main Evie instance.
	 *
	 * Ensures only one instance of Evie is loaded or can be loaded.
	 *
	 * @static
	 *
	 * @return Evie - Main instance.
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Filters the setting value for the Flextension plugin.
	 *
	 * @since 1.0.6
	 *
	 * @param mixed  $value The setting value.
	 * @param string $name  The name of setting.
	 * @return mixed The setting value.
	 */
	public function get_setting( $value, $name ) {
		if ( 'documentation' === $name ) {
			$value = 'https://evietheme.com/documentation';
		}
		return $value;
	}

	/**
	 * Appends the Evie XT version to the current modules version.
	 *
	 * @since 1.0.4
	 *
	 * @param string $version A current version number of the modules.
	 * @return string A new version number of the modules.
	 */
	public function modules_version( $version ) {
		$version .= '-evie-xt-' . EVIE_XT_VERSION;
		return $version;
	}

	/**
	 * Includes plugin's modules.
	 */
	public function register_modules() {
		// Load modules.
		flextension_load_files( 'modules/*/index.php', EVIE_XT_PATH );
	}

	/**
	 * Includes plugin's extensions.
	 */
	public function register_extensions() {
		// Load extensions.
		flextension_load_files( 'extensions/*/index.php', EVIE_XT_PATH );
	}

	/**
	 * Loads language files
	 */
	public function load_plugin_textdomain() {
		// The third argument must not be hardcoded, to account for relocated folders.
		load_plugin_textdomain( 'evie-xt', false, plugin_basename( dirname( EVIE_XT_FILE ) ) . '/languages' );
	}

	/**
	 * Checks for available updates.
	 *
	 * @since 1.1.3
	 */
	public function check_update() {
		new Evie_XT_Update_Checker( plugin_basename( EVIE_XT_FILE ), EVIE_XT_VERSION );
	}

}
