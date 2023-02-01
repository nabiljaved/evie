<?php
/**
 * List table class for handling modules.
 *
 * Extends the WP_List_Table class to provide a future-compatible
 * way of listing out all Flextension modules.
 *
 * This class also allows for the bulk actions.
 *
 * @package    Flextension
 * @subpackage Admin
 */

defined( 'ABSPATH' ) || exit;

/**
 * WP_List_Table isn't always available. If it isn't available,
 * we load it here.
 */
if ( ! class_exists( 'WP_List_Table', false ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * Core class used to implement displaying installed modules in a list table.
 *
 * @access private
 *
 * @package Flextension
 * @subpackage Admin
 *
 * @see WP_List_Table
 */
class Flextension_Modules_List_Table extends WP_List_Table {

	/**
	 * An array list of registered modules.
	 *
	 * @var array
	 */
	public $registered_modules = array();

	/**
	 * The currently chosen view.
	 *
	 * @var string One of: 'all', 'install', 'update', 'activate'
	 */
	public $view_context = 'all';

	/**
	 * Sorts modules by module property.
	 *
	 * @var string
	 */
	public $sortby = 'title';

	/**
	 * The module counts for the various views.
	 *
	 * @var array
	 */
	protected $view_totals = array(
		'all'       => 0,
		'active'    => 0,
		'inactive'  => 0,
		'extension' => 0,
	);

	/**
	 * References parent constructor and sets defaults for class.
	 */
	public function __construct() {
		parent::__construct(
			array(
				'singular' => 'module',
				'plural'   => 'modules',
			)
		);

		$status = isset( $_REQUEST['module_status'] ) ? sanitize_key( $_REQUEST['module_status'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( ! empty( $status ) && in_array( $status, array( 'active', 'inactive', 'extension' ), true ) ) {
			$this->view_context = $status;
		}

		if ( isset( $_SERVER['REQUEST_URI'] ) ) {
			// Clean up request URI from temporary args for screen options/paging uri's to work as expected.
			$_SERVER['REQUEST_URI'] = remove_query_arg( array( 'updated', 'message', 'action', 'module', '_wpnonce' ), esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) );
		}
	}

	/**
	 * Loads modules and extensions.
	 *
	 * The active modules have been loaded by default.
	 * Then we need to load inactive modules to show all the modules.
	 *
	 * @since 1.1.0
	 */
	public function load_modules() {
		$this->registered_modules = flextension_get_registered_modules();
		if ( ! empty( $this->registered_modules ) ) {
			foreach ( $this->registered_modules as $name => $module ) {
				if ( true !== (bool) $module['active'] && ! empty( $module['file'] ) ) {
					$file = flextension_get_module_file( $module['file'] );
					if ( file_exists( $file ) ) {
						include_once $file;
					}
				}
			}
		}
	}

	/**
	 * Prepares all of our information to be outputted into a usable table.
	 */
	public function prepare_items() {
		$columns               = $this->get_columns(); // Get all necessary column information.
		$hidden                = array(); // No columns to hide, but we must set as an array.
		$sortable              = array(); // No reason to make sortable columns.
		$primary               = $this->get_primary_column_name(); // Column which has the row actions.
		$this->_column_headers = array( $columns, $hidden, $sortable, $primary ); // Get all necessary column headers.

		$this->load_modules();

		$this->process_actions( $this->current_action() );

		// Store all of our module data into $items array so WP_List_Table can use it.
		// Categorize the modules which have open actions.
		$modules = $this->categorize_modules_to_views();

		// Set the counts for the view links.
		$this->set_view_totals( $modules );

		// Prep variables for use and grab list of all installed modules.
		$this->items = array();

		// Redirect to the 'all' view if no modules were found for the selected view context.
		if ( empty( $modules[ $this->view_context ] ) ) {
			$this->view_context = 'all';
		}

		foreach ( $modules[ $this->view_context ] as $name => $module ) {
			$this->items[ $name ] = $module;
		}
	}

	/**
	 * Processes activation and deactivation actions.
	 *
	 * @param string $action The current action to process.
	 */
	public function process_actions( $action ) {

		$message = '';
		$updated = false;

		if ( ! empty( $action ) ) {

			switch ( $action ) {
				case 'activate':
					$module = isset( $_REQUEST['module'] ) ? sanitize_key( $_REQUEST['module'] ) : '';

					if ( ! empty( $module ) ) {
						check_admin_referer( 'activate-module-' . $module );

						$this->set_module_state( $module, 1 );

						$url = add_query_arg(
							array(
								'updated' => true,
								'message' => esc_html__( 'Module activated.', 'flextension' ),
							)
						);

						wp_safe_redirect( $url );
					}

					break;
				case 'deactivate':
					$module = isset( $_REQUEST['module'] ) ? sanitize_key( $_REQUEST['module'] ) : '';

					if ( ! empty( $module ) ) {

						check_admin_referer( 'deactivate-module-' . $module );

						$this->set_module_state( $module, 0 );

						$url = add_query_arg(
							array(
								'updated' => true,
								'message' => esc_html__( 'Module deactivated.', 'flextension' ),
							)
						);

						wp_safe_redirect( $url );
					}

					break;
				case 'activate-selected':
					check_admin_referer( 'bulk-modules' );

					$modules = isset( $_POST['checked'] ) ? array_map( 'sanitize_key', (array) wp_unslash( $_POST['checked'] ) ) : array();

					if ( ! empty( $modules ) ) {
						foreach ( $modules as $name ) {
							$this->set_module_state( $name, 1 );
						}

						$url = add_query_arg(
							array(
								'updated' => true,
								'message' => esc_html__( 'Selected modules activated.', 'flextension' ),
							)
						);

						wp_safe_redirect( $url );
					} else {
						$updated = false;
						$message = esc_html__( 'Please select at least one item to perform this action on.', 'flextension' );
					}

					break;
				case 'deactivate-selected':
					check_admin_referer( 'bulk-modules' );

					$modules = isset( $_POST['checked'] ) ? array_map( 'sanitize_key', (array) wp_unslash( $_POST['checked'] ) ) : array();

					if ( ! empty( $modules ) ) {
						foreach ( $modules as $name ) {
							$this->set_module_state( $name, 0 );
						}

						$url = add_query_arg(
							array(
								'updated' => true,
								'message' => esc_html__( 'Selected modules deactivated.', 'flextension' ),
							)
						);

						wp_safe_redirect( $url );
					} else {
						$updated = false;
						$message = esc_html__( 'Please select at least one item to perform this action on.', 'flextension' );
					}

					break;
			}
		} else {
			$message = isset( $_REQUEST['message'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['message'] ) ) : '';
			$updated = isset( $_REQUEST['updated'] ) ? (bool) sanitize_key( $_REQUEST['updated'] ) : false;
		}

		if ( ! empty( $message ) ) {
			if ( true === $updated ) {
				echo '<div id="message" class="updated notice is-dismissible"><p>' . esc_html( $message ) . '</p></div>';
			} else {
				echo '<div id="error" class="notice notice-error is-dismissible"><p>' . esc_html( $message ) . '</p></div>';
			}
		}
	}

	/**
	 * Sets the active status for module dependencies.
	 *
	 * @since 1.1.1
	 *
	 * @param Flextension_Module $module The module to set.
	 */
	public function set_dependencies( $module ) {
		if ( ! empty( $module->dependencies ) ) {
			$modules = flextension_get_modules();
			foreach ( $module->dependencies as $name ) {
				$dep     = $modules[ $name ];
				$enabled = $dep->enabled;
				if ( true === $module->enabled ) {
					$enabled = true;
				} elseif ( true === $dep->public && 'extension' !== $dep->type && isset( $this->registered_modules[ $dep->name ] ) ) {
					$enabled = (bool) $this->registered_modules[ $dep->name ]['active'];
				} else {
					$enabled = flextension_module_is_required( $dep );
				}

				$dep->enabled = $enabled;

				if ( true === $enabled ) {
					$dep->activate();
				} else {
					$dep->deactivate();
				}

				$this->registered_modules[ $dep->name ]['active'] = $enabled;

				$modules[ $dep->name ] = $dep;

				$this->set_dependencies( $dep );
			}
			flextension_update_modules( $modules );
		}
	}

	/**
	 * Sets whether the module is active.
	 *
	 * @param string   $name   The name of the module.
	 * @param bool|int $active Whether the module is active.
	 */
	public function set_module_state( $name, $active ) {
		$modules = flextension_get_modules();
		if ( isset( $modules[ $name ] ) ) {

			$module = $modules[ $name ];

			$module->enabled = (bool) $active;

			$this->registered_modules[ $name ]['active'] = (bool) $active;

			flextension_update_modules( $modules );

			$this->set_dependencies( $module );

			if ( (bool) $active ) {
				$module->activate();
			} else {
				$module->deactivate();
			}

			$settings = array(
				'version' => flextension_modules_version(),
				'modules' => $this->registered_modules,
			);

			// Save modules settings.
			update_option( 'flext_modules', $settings );
		}
	}

	/**
	 * Get a list of CSS classes for the <table> tag.
	 *
	 * @return array CSS classnames.
	 */
	public function get_table_classes() {
		return array( 'widefat', 'striped', 'plugins', 'modules' );
	}

	/**
	 * Categorizes the modules which have open actions into views for the TGMPA page.
	 *
	 * @return array An array of the categorized modules.
	 */
	protected function categorize_modules_to_views() {

		$all_modules = flextension_get_modules();

		// Sort modules.
		if ( is_array( $all_modules ) ) {
			uasort( $all_modules, array( $this, 'modules_order_callback' ) );
		}

		$modules = array(
			'all'       => array(), // Meaning: all modules which still have open actions.
			'active'    => array(),
			'inactive'  => array(),
			'extension' => array(),
		);

		if ( ! empty( $all_modules ) ) {

			foreach ( $all_modules as $name => $module ) {

				if ( true !== $module->public ) {
					continue;
				}

				if ( 'extension' === $module->type ) {
					if ( true === $module->enabled ) {
						$modules['extension'][ $name ] = $module;
					}
				} else {
					$modules['all'][ $name ] = $module;
					// Check whether the module is enabled.
					if ( true === $module->enabled ) {
						$modules['active'][ $name ] = $module;
					} else {
						$modules['inactive'][ $name ] = $module;
					}
				}
			}
		}

		return $modules;
	}

	/**
	 * Compares module priority.
	 * Returns -1 if $module_a is less than $module_b; 1 if $module_a is greater than $module_b.
	 *
	 * @param Flextension_Module $module_a The first module to compare.
	 * @param Flextension_Module $module_b The second module to compare.
	 * @return int The integer value of comparison.
	 */
	public function modules_order_callback( $module_a, $module_b ) {
		if ( 'title' === $this->sortby || $module_a->priority === $module_b->priority ) {
			// If their priority values are equal, then sort by title instead.
			return strcmp( $module_a->title, $module_b->title );
		}

		return ( $module_a->priority < $module_b->priority ) ? -1 : 1;
	}

	/**
	 * Sets the number of modules for the view links.
	 *
	 * @param array $modules Modules order by view.
	 */
	protected function set_view_totals( $modules ) {
		foreach ( $modules as $type => $list ) {
			$this->view_totals[ $type ] = count( $list );
		}
	}

	/**
	 * Gets an associative array ( id => link ) of the views available on this table.
	 *
	 * @return array An array of the module links.
	 */
	public function get_views() {
		$status_links = array();

		foreach ( $this->view_totals as $type => $count ) {
			if ( ! $count ) {
				continue;
			}

			switch ( $type ) {
				case 'all':
					/* translators: 1: number of modules. */
					$text = _nx(
						'All <span class="count">(%s)</span>',
						'All <span class="count">(%s)</span>',
						$count,
						'modules',
						'flextension'
					);
					break;
				case 'active':
					/* translators: 1: number of modules. */
					$text = _n(
						'Active <span class="count">(%s)</span>',
						'Active <span class="count">(%s)</span>',
						$count,
						'flextension'
					);
					break;
				case 'inactive':
					/* translators: 1: number of modules. */
					$text = _n(
						'Inactive <span class="count">(%s)</span>',
						'Inactive <span class="count">(%s)</span>',
						$count,
						'flextension'
					);
					break;
				case 'extension':
					/* translators: 1: number of modules. */
					$text = _n(
						'Extensions <span class="count">(%s)</span>',
						'Extensions <span class="count">(%s)</span>',
						$count,
						'flextension'
					);
					break;
				default:
					$text = '';
					break;
			}

			if ( ! empty( $text ) ) {
				$status_links[ $type ] = sprintf(
					'<a href="%s"%s>%s</a>',
					add_query_arg( 'module_status', $type ),
					( $type === $this->view_context ) ? ' class="current"' : '',
					sprintf( $text, number_format_i18n( $count ) )
				);
			}
		}

		return $status_links;
	}

	/**
	 * Generates content for a single row of the table
	 *
	 * @param object $item The current item.
	 */
	public function single_row( $item ) {
		$class = 'inactive';
		if ( true === $item->enabled ) {
			$class = 'active';
		}
		echo '<tr class="' . esc_attr( $class ) . '">';
		$this->single_row_columns( $item );
		echo '</tr>';
	}

	/**
	 * Required for bulk installing.
	 *
	 * Adds a checkbox for each module.
	 *
	 * @param Flextension_Module $item The module object.
	 * @return string The input checkbox with all necessary info.
	 */
	public function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="checked[]" value="%1$s" id="%2$s" />',
			esc_attr( $item->name ),
			esc_attr( $item->title )
		);
	}

	/**
	 * Returns whether the module can be deactivated.
	 *
	 * @param Flextension_Module $item The module object.
	 * @return bool Whether the module can be deactivated.
	 */
	public function can_deactivate( $item ) {
		$can_deactivate = true;
		$dependents     = flextension_get_dependents( $item->name );
		if ( ! empty( $dependents ) ) {
			foreach ( $dependents as $name => $dep ) {
				if ( true === $dep->enabled ) {
					$can_deactivate = false;
					break;
				}
			}
		}

		return $can_deactivate;
	}

	/**
	 * Create default title column along with the action links.
	 *
	 * @param Flextension_Module $item The module object.
	 * @return string The module name and action links.
	 */
	public function column_title( $item ) {

		$actions = array();

		if ( 'extension' !== $item->type ) {
			if ( true !== $item->enabled ) {
				$actions['activate'] = sprintf(
					'<a href="%s">%s</a>',
					wp_nonce_url(
						add_query_arg(
							array(
								'module' => $item->name,
								'action' => 'activate',
							)
						),
						'activate-module-' . $item->name
					),
					esc_html__( 'Activate', 'flextension' )
				);
			} else {
				if ( isset( $item->actions ) && is_array( $item->actions ) ) {
					$actions = (array) $item->actions;
				}

				if ( true === $this->can_deactivate( $item ) ) {
					$actions['deactivate'] = sprintf(
						'<a href="%s">%s</a>',
						wp_nonce_url(
							add_query_arg(
								array(
									'module' => $item->name,
									'action' => 'deactivate',
								)
							),
							'deactivate-module-' . $item->name
						),
						esc_html__( 'Deactivate', 'flextension' )
					);
				}
			}
		}

		/**
		 * Filters the list of action links displayed for a specific module in the modules list table.
		 *
		 * @param string[] $actions     An array of plugin action links. By default this can include 'activate' and 'deactivate'.
		 * @param string   $name        Path to the plugin file relative to the plugins directory.
		 * @param array    $module      An array of module data.
		 * @param string   $context     The plugin context. By default this can include 'all', 'active', 'inactive'.
		 */
		$actions = apply_filters( "flextension_module_action_links_{$item->name}", $actions, $item->name, $item, $this->view_context );

		return sprintf( '<strong>%s</strong>%s', $item->title, $this->row_actions( $actions, true ) );
	}

	/**
	 * Returns the module description.
	 *
	 * @param Flextension_Module $item The module object.
	 * @return string The module description.
	 */
	public function column_description( $item ) {

		$description = '<p>' . esc_html( $item->description ) . '</p>';

		$items = array();

		if ( ! empty( $item->version ) ) {
			$items[] = sprintf(
				/* translators: %s: Version string */
				esc_html__( 'Version %s', 'flextension' ),
				$item->version
			);
		}

		if ( isset( $item->links ) && is_array( $item->links ) ) {

			foreach ( $item->links as $link ) {

				if ( isset( $link['text'] ) && isset( $link['url'] ) ) {
					// Target of link.
					$target = isset( $link['target'] ) ? $link['target'] : '_self';

					$items[] = sprintf(
						'<a href="%s" target="%s" role="button">%s</a>',
						esc_url( $link['url'] ),
						esc_attr( $target ),
						esc_html( $link['text'] )
					);

				}
			}

			if ( true === $item->enabled ) {

				$dependents = $this->get_dependents( $item->name );
				if ( ! empty( $dependents ) ) {
					$items[] = esc_html__( 'Required for: ', 'flextension' ) . '<strong>' . implode( ', ', $dependents ) . '</strong>';
				}
			}
		}

		if ( ! empty( $items ) ) {
			$description .= '<p>' . implode( ' | ', $items ) . '</p>';
		}

		return '<div class="plugin-description">' . $description . '</div>';
	}

	/**
	 * Retrieves the reference names for the dependency.
	 *
	 * @param string $name The name of the dependency.
	 * @return array An array of the reference names.
	 */
	public function get_dependents( $name ) {

		$dependent_names = array();

		$dependents = flextension_get_dependents( $name );
		if ( ! empty( $dependents ) ) {
			foreach ( $dependents as $name => $module ) {
				if ( true === $module->public && true === $module->enabled ) {
					$dependent_names[] = $module->title;
				}
			}
		}

		return $dependent_names;
	}

	/**
	 * Sets default message within the modules table if no modules
	 * are left for interaction.
	 *
	 * Hides the menu item to prevent the user from clicking and
	 * getting a permissions error.
	 */
	public function no_items() {
		echo esc_html__( 'No modules found.', 'flextension' ) . ' <a href="' . esc_url( self_admin_url() ) . '"> ' . esc_html__( 'Return to the Dashboard', 'flextension' ) . '</a>';
	}

	/**
	 * Output all the column information within the table.
	 *
	 * @return array $columns The column names.
	 */
	public function get_columns() {
		$columns = array(
			'cb'          => '<input type="checkbox" />',
			'title'       => esc_html__( 'Module', 'flextension' ),
			'description' => esc_html__( 'Description', 'flextension' ),
		);

		return apply_filters( 'flextension_modules_list_table_columns', $columns );
	}

	/**
	 * Gets the name of the primary column for this specific list table.
	 *
	 * @return string Unalterable name for the primary column, in this case, 'name'.
	 */
	protected function get_primary_column_name() {
		return 'title';
	}

	/**
	 * Defines the bulk actions for handling registered modules.
	 *
	 * @return array $actions The bulk actions for the module install table.
	 */
	public function get_bulk_actions() {

		$actions = array();

		if ( 'active' !== $this->view_context ) {
			$actions['activate-selected'] = esc_html__( 'Activate', 'flextension' );
		}

		if ( 'inactive' !== $this->view_context ) {
			$actions['deactivate-selected'] = esc_html__( 'Deactivate', 'flextension' );
		}

		return $actions;
	}

	/**
	 * Extra controls to be displayed between bulk actions and pagination.
	 *
	 * @param string $which 'top' or 'bottom' table navigation.
	 */
	public function extra_tablenav( $which ) {
		if ( 'bottom' === $which ) {
			echo '<span class="alignright"><small>',
				esc_html(
					sprintf(
						/* translators: %s: version number */
						__( 'Flextension v%s', 'flextension' ),
						flextension_get_setting( 'version' )
					)
				),
				'</small></span>';
		}
	}

}
