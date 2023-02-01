<?php
/**
 * The Update Checker.
 *
 * Checks for an update for the Flextension plugin.
 *
 * @package    Flextension
 * @subpackage Includes
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Flextension Update Checker class.
 */
class Flextension_Update_Checker {

	/**
	 * The plugin file.
	 *
	 * @var string $plugin The plugin file.
	 */
	public $plugin;

	/**
	 * The plugin slug.
	 *
	 * @var string $slug The plugin slug.
	 */
	public $slug;

	/**
	 * The current version of the plugin.
	 *
	 * @var string $version The current version of the plugin.
	 */
	public $version;

	/**
	 * An absolute URI to check for an update.
	 *
	 * @var string $update_uri An absolute URI to check for an update.
	 */
	public $update_uri;

	/**
	 * A cache key.
	 *
	 * @var string $cache_key The name of cache key.
	 */
	public $cache_key;

	/**
	 * Initializes the class, adds actions and filters.
	 *
	 * @param string $plugin     The plugin slug to check.
	 * @param string $version    The current version of the plugin.
	 * @param string $update_uri An absolute URI to check for an update.
	 */
	public function __construct( $plugin, $version, $update_uri = '' ) {

		$this->plugin = $plugin;

		$this->slug = dirname( $plugin );

		$this->version = $version;

		$this->update_uri = $update_uri;

		if ( empty( $this->update_uri ) ) {
			$this->update_uri = 'https://wydethemes.com/plugins/' . $this->slug . '.json';
		}

		$this->cache_key = $this->slug . '_plugin_info';

		add_filter( 'plugins_api', array( $this, 'info' ), 20, 3 );

		add_filter( 'site_transient_update_plugins', array( $this, 'update' ) );

		add_action( 'upgrader_process_complete', array( $this, 'purge' ), 10, 2 );
	}

	/**
	 * Retrieves the plugin information from the remote server.
	 *
	 * @return object A response data object.
	 */
	public function request() {

		$data = get_transient( $this->cache_key );
		if ( false === $data ) {
			$url      = add_query_arg( array( 'v' => $this->version ), $this->update_uri );
			$response = wp_remote_get(
				$url,
				array(
					'timeout' => 10,
					'headers' => array(
						'Accept' => 'application/json',
					),
				)
			);
			$body     = wp_remote_retrieve_body( $response );
			if ( ! empty( $body ) ) {
				$data = json_decode( $body, true );
				if ( is_array( $data ) ) {
					$data = (object) $data;
				}
				set_transient( $this->cache_key, $data, HOUR_IN_SECONDS );
			}
		}

		return $data;
	}

	/**
	 * Returns the plugin information.
	 *
	 * @param false|object|array $result The result object or array. Default false.
	 * @param string             $action The type of information being requested from the Plugin Installation API.
	 * @param object             $args   Plugin API arguments.
	 * @return false|object|array The result object or array. Default false.
	 */
	public function info( $result, $action, $args ) {

		if ( 'plugin_information' === $action && $this->slug === $args->slug ) {
			$response = $this->request();
			if ( ! empty( $response ) ) {
				$result = $response;
			}
		}

		return $result;
	}

	/**
	 * Checks for available updates to the plugin.
	 *
	 * If there is a new update available, then add new data to the existing plugin transient.
	 *
	 * @param mixed $transient Value of site transient.
	 * @return mixed Value of site transient.
	 */
	public function update( $transient ) {

		if ( empty( $transient->checked ) ) {
			return $transient;
		}

		$response = $this->request();
		if ( ! empty( $response ) && version_compare( $this->version, $response->version, '<' ) ) {
			$result              = new stdClass();
			$result->slug        = $this->slug;
			$result->plugin      = $this->plugin;
			$result->new_version = $response->version;
			$result->tested      = $response->tested;
			$result->requires    = $response->requires;
			$result->package     = $response->download_link;

			$transient->response[ $result->plugin ] = $result;
		}

		return $transient;
	}

	/**
	 * Purges the plugin information from the transient.
	 *
	 * @param WP_Upgrader $upgrader   WP_Upgrader instance.
	 * @param array       $hook_extra {
	 *     Array of bulk item update data.
	 *
	 *     @type string $action       Type of action. Default 'update'.
	 *     @type string $type         Type of update process. Accepts 'plugin', 'theme', 'translation', or 'core'.
	 *     @type bool   $bulk         Whether the update process is a bulk update. Default true.
	 *     @type array  $plugins      Array of the basename paths of the plugins' main files.
	 *     @type array  $themes       The theme slugs.
	 *     @type array  $translations {
	 *         Array of translations update data.
	 *
	 *         @type string $language The locale the translation is for.
	 *         @type string $type     Type of translation. Accepts 'plugin', 'theme', or 'core'.
	 *         @type string $slug     Text domain the translation is for. The slug of a theme/plugin or
	 *                                'default' for core translations.
	 *         @type string $version  The version of a theme, plugin, or core.
	 *     }
	 * }
	 */
	public function purge( $upgrader, $hook_extra ) {

		if ( 'update' === $hook_extra['action'] && 'plugin' === $hook_extra['type'] ) {
			delete_transient( $this->cache_key );
		}

	}

}
