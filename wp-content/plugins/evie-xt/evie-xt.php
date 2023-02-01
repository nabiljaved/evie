<?php
/**
 * Plugin Name: Evie XT
 * Plugin URI: https://evietheme.com/
 * Description: Additional features and functionalities for the Evie theme.
 * Version: 1.1.3
 * Author: Wyde
 * Author URI: https://wydethemes.com
 * Text Domain: evie-xt
 * Domain Path: /languages/
 * Requires at least: 5.8
 * Tested up to: 6.0
 * Requires PHP: 5.6
 *
 * @package Evie_XT
 */

defined( 'ABSPATH' ) || exit;

/**
 * Variables
 */
// Current version of the Evie XT plugin.
define( 'EVIE_XT_VERSION', '1.1.3' );

// Plugin File.
define( 'EVIE_XT_FILE', __FILE__ );

// Plugin Directory Path.
define( 'EVIE_XT_PATH', plugin_dir_path( __FILE__ ) );

// Include the main plugin class.
if ( ! class_exists( 'Evie_XT', false ) ) {
	include_once EVIE_XT_PATH . 'includes/class-evie-xt.php';
}

/**
 * Returns the main instance of Evie class.
 */
function evie_xt() {
	return Evie_XT::instance();
}

evie_xt();
