<?php
/**
 * The core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @package    Flextension
 * @subpackage Includes
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Main Core Class
 */
final class Flextension {

	/**
	 * The single instance of the class.
	 *
	 * @var Flextension $instance The single instance of the class.
	 */
	protected static $instance = null;

	/**
	 * The plugin settings array.
	 *
	 * @var array $settings The plugin settings array.
	 */
	protected $settings = array();

	/**
	 * The plugin data array.
	 *
	 * @var array $data The plugin data array.
	 */
	protected $data = array();

	/**
	 * Initializes the class, adds actions and filters.
	 */
	public function __construct() {
		// Settings.
		$this->settings = array(
			'name'          => esc_html__( 'Flextension', 'flextension' ),
			'version'       => FLEXTENSION_VERSION,
			'documentation' => 'https://wydethemes.com/documentation/flextension',
		);

		// Include core functions.
		require_once FLEXTENSION_PATH . 'includes/flextension-functions.php';

		// Include core classes.
		require_once FLEXTENSION_PATH . 'includes/class-flextension-update-checker.php';
		require_once FLEXTENSION_PATH . 'includes/class-flextension-module.php';
		require_once FLEXTENSION_PATH . 'includes/class-flextension-module-admin.php';

		add_action( 'plugins_loaded', array( $this, 'load_modules' ) );

		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		add_action( 'init', array( $this, 'register_scripts' ), 5 );

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 5 );

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ), 5 );

		if ( is_admin() ) {
			$this->check_update();
		}
	}

	/**
	 * Main Flextension instance.
	 *
	 * Ensures only one instance of Flextension is loaded or can be loaded.
	 *
	 * @return Flextension - Main instance.
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Loads plugin's modules and extensions.
	 */
	public function load_modules() {
		// Loads modules.
		flextension_load_modules();

		// Include admin classes.
		if ( is_admin() ) {
			require_once FLEXTENSION_PATH . 'admin/class-flextension-modules-manager.php';
			new Flextension_Modules_Manager();
		}
	}

	/**
	 * Loads language files
	 */
	public function load_plugin_textdomain() {
		// The third argument must not be hardcoded, to account for relocated folders.
		load_plugin_textdomain( 'flextension', false, plugin_basename( dirname( FLEXTENSION_PLUGIN_FILE ) ) . '/languages' );
	}

	/**
	 * Returns a setting value.
	 *
	 * @param string $name The name of setting.
	 * @return mixed The setting value.
	 */
	public function get_setting( $name ) {
		$value = isset( $this->settings[ $name ] ) ? $this->settings[ $name ] : null;
		/**
		 * Filters the setting value.
		 *
		 * @since 1.0.6
		 *
		 * @param mixed  $value The setting value.
		 * @param string $name  The name of setting name.
		 */
		return apply_filters( 'flextension_get_setting', $value, $name );
	}

	/**
	 * Updates a setting value.
	 *
	 * @param string $name  The name.
	 * @param mixed  $value The value.
	 */
	public function update_setting( $name, $value ) {
		$this->settings[ $name ] = $value;
		return true;
	}

	/**
	 * Returns data.
	 *
	 * @param string $name The name.
	 */
	public function get_data( $name ) {
		return isset( $this->data[ $name ] ) ? $this->data[ $name ] : null;
	}

	/**
	 * Sets data.
	 *
	 * @param string $name  The name.
	 * @param mixed  $value The value.
	 */
	public function set_data( $name, $value ) {
		$this->data[ $name ] = $value;
	}

	/**
	 * Checks for available updates.
	 *
	 * @since 1.1.2
	 */
	public function check_update() {
		new Flextension_Update_Checker( plugin_basename( FLEXTENSION_PLUGIN_FILE ), FLEXTENSION_VERSION );
	}

	/**
	 * Registers scripts and stylesheets.
	 */
	public function register_scripts() {
		wp_register_style( 'flextension', FLEXTENSION_URL . 'assets/css/flextension.css', array(), flextension_get_setting( 'version' ) );
		wp_style_add_data( 'flextension', 'rtl', 'replace' );

		wp_register_script( 'flextension', FLEXTENSION_URL . 'assets/js/flextension.js', array(), flextension_get_setting( 'version' ), true );

		/**
		 * Filters the plugin settings.
		 *
		 * @param array $settings The current settings for the plugin.
		 */
		$settings = apply_filters( 'flextension_settings', array() );

		wp_localize_script( 'flextension', 'flextensionSettings', $settings );
	}

	/**
	 * Enqueues the scripts and stylesheets for admin page.
	 */
	public function admin_enqueue_scripts() {
		wp_enqueue_style( 'flextension' );

		wp_enqueue_script( 'flextension' );
	}

	/**
	 * Enqueues the scripts and stylesheets for the frontend.
	 */
	public function enqueue_scripts() {
		wp_enqueue_style( 'flextension' );

		wp_enqueue_script( 'flextension' );
	}

}
