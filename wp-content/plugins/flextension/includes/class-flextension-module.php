<?php
/**
 * Module: Flextension_Module class
 *
 * Additional module
 *
 * @package    Flextension
 * @subpackage Includes
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Module main class.
 */
class Flextension_Module {

	/**
	 * The module name.
	 *
	 * @var string
	 */
	public $name = '';

	/**
	 * File path of the module.
	 *
	 * @var string
	 */
	public $file = '';

	/**
	 * Human-readable mobule label.
	 *
	 * @var string
	 */
	public $title = '';

	/**
	 * The module description.
	 *
	 * @var string
	 */
	public $description = '';

	/**
	 * The module version.
	 *
	 * @var string
	 */
	public $version = '';

	/**
	 * The module category.
	 *
	 * @var string
	 */
	public $category = 'basic';

	/**
	 * The module type.
	 *
	 * @var string
	 */
	public $type = 'module';

	/**
	 * Whether the module is public and can be activated and deactivated in the modules list.
	 *
	 * @var string
	 */
	public $public = true;

	/**
	 * Whether the module is enabled.
	 *
	 * @var string
	 */
	public $enabled = false;

	/**
	 * Whether the module is active.
	 *
	 * @var string
	 */
	public $active = false;

	/**
	 * The module priority.
	 *
	 * @var string
	 */
	public $priority = 10;

	/**
	 * The module actions links, only appear when the module is enabled.
	 *
	 * @var array
	 */
	public $actions = array();

	/**
	 * The module dependencies.
	 *
	 * @var array
	 */
	public $dependencies = array();

	/**
	 * The module links.
	 *
	 * @var array
	 */
	public $links = array();

	/**
	 * Calls after the module has been loaded.
	 *
	 * @var callable
	 */
	public $load_callback = null;

	/**
	 * Calls when the module is activated.
	 *
	 * @var callable
	 */
	public $activate_callback = null;

	/**
	 * Calls when the module is deactivated.
	 *
	 * @var callable
	 */
	public $deactivate_callback = null;

	/**
	 * Initializes the module properties.
	 *
	 * @see flextension_register_module()
	 *
	 * @param string $name Block type name including namespace.
	 * @param array  $args {
	 *     Optional. Array of arguments for registering a module. Default empty array.
	 *
	 *     @type string        $name                A module name.
	 *     @type string        $title               Human-readable module label.
	 *     @type string        $description         A module description.
	 *     @type string|null   $category            Module category classification, used in modules list.
	 *     @type string|null   $type                Module type. Accepts 'module' and 'extension'.
	 *     @type bool          $public              Whether the module will be showed in the modules list.
	 *     @type bool          $enabled             Whether the module is enabled.
	 *     @type int           $priority            List the modules in order of priority.
	 *     @type array         $dependencies        Array list of the dependencies.
	 *     @type array         $actions             Array list of the action links.
	 *     @type array         $links               Array list of the links.
	 *     @type callable|null $load_callback       A callback function when the module is loaded.
	 *     @type callable|null $activate_callback   A callback function when the module is activated.
	 *     @type callable|null $deactivate_callback A callback function when the module is deactivated.
	 * }
	 */
	public function __construct( $name, $args = array() ) {
		$this->name = $name;

		$this->set_props( $args );
	}

	/**
	 * Sets module properties.
	 *
	 * @param array|string $args Optional. Array of arguments for registering a module.
	 */
	public function set_props( $args = array() ) {
		$args = wp_parse_args(
			$args,
			array(
				'load_callback'       => null,
				'activate_callback'   => null,
				'deactivate_callback' => null,
			)
		);

		$args['name'] = $this->name;

		/**
		 * Filters the arguments for registering a module.
		 *
		 * @param array  $args Array of arguments for registering a module.
		 * @param string $name Module name.
		 */
		$args = apply_filters( 'flextension_register_module_args', $args, $this->name );

		foreach ( $args as $property_name => $property_value ) {
			$this->$property_name = $property_value;
		}
	}

	/**
	 * Loads the module.
	 */
	public function load() {
		$this->enabled = true;
		if ( true !== $this->active ) {
			if ( ! empty( $this->load_callback ) && is_callable( $this->load_callback ) ) {
				call_user_func( $this->load_callback, $this );
			}
			$this->active = true;
		}
	}

	/**
	 * Activates the module.
	 */
	public function activate() {
		$this->enabled = true;
		if ( true !== $this->active ) {
			if ( ! empty( $this->activate_callback ) && is_callable( $this->activate_callback ) ) {
				call_user_func( $this->activate_callback, $this );
			}
			$this->active = true;
		}
	}

	/**
	 * Deactivates the module.
	 */
	public function deactivate() {
		$this->enabled = false;
		if ( false !== $this->active ) {
			if ( ! empty( $this->deactivate_callback ) && is_callable( $this->deactivate_callback ) ) {
				call_user_func( $this->deactivate_callback, $this );
			}
			$this->active = false;
		}
	}

}
