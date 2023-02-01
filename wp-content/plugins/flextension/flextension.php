<?php
/**
 * Plugin Name: Flextension
 * Plugin URI: https://wydethemes.com/
 * Description: Adds essential components to your WordPress site.
 * Version: 1.1.4
 * Author: Wyde
 * Author URI: https://wydethemes.com
 * Text Domain: flextension
 * Domain Path: /languages/
 * Requires at least: 5.8
 * Tested up to: 6.0
 * Requires PHP: 5.6
 *
 * @package Flextension
 */

defined( 'ABSPATH' ) || exit;

/**
 * Variables
 */
// Current version of the Flextension plugin.
define( 'FLEXTENSION_VERSION', '1.1.4' );

// Plugin File.
define( 'FLEXTENSION_PLUGIN_FILE', __FILE__ );

// Plugin Directory Path.
define( 'FLEXTENSION_PATH', plugin_dir_path( __FILE__ ) );

// Plugin Directory URI.
define( 'FLEXTENSION_URL', plugin_dir_url( __FILE__ ) );

// Include the main plugin class.
if ( ! class_exists( 'Flextension', false ) ) {
	include_once FLEXTENSION_PATH . 'includes/class-flextension.php';
}

/**
 * Plugin Activation.
 *
 * @param bool $networkwide The networkwide.
 */
function flextension_plugin_activation( $networkwide ) {
	do_action( 'flextension_plugin_activation', $networkwide );
}

register_activation_hook( FLEXTENSION_PLUGIN_FILE, 'flextension_plugin_activation' );

/**
 * Plugin Deactivation.
 *
 * @param bool $networkwide The networkwide.
 */
function flextension_plugin_deactivation( $networkwide ) {
	do_action( 'flextension_plugin_deactivation', $networkwide );
}

register_deactivation_hook( FLEXTENSION_PLUGIN_FILE, 'flextension_plugin_deactivation' );

/**
 * Returns the main instance of Flextension class.
 */
function flextension() {
	return Flextension::instance();
}

// Initialize.
flextension();
