<?php
/**
 * Modules Manager
 *
 * @package    Flextension
 * @subpackage Admin
 */

defined( 'ABSPATH' ) || exit;

/**
 * Modules Manager class
 */
class Flextension_Modules_Manager {

	/**
	 * The slug name to refer to the manager menu.
	 *
	 * @var string $menu_slug The menu slug.
	 */
	public $menu_slug = 'manager';

	/**
	 * The settings page.
	 *
	 * @var string $settings_page The settings page.
	 */
	public $settings_page;

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'init' ) );
	}

	/**
	 * Initializes the manager page.
	 */
	public function init() {
		add_action( 'admin_menu', array( $this, 'add_settings_page' ) );

		add_filter( 'plugin_action_links_' . plugin_basename( FLEXTENSION_PLUGIN_FILE ), array( $this, 'action_links' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
	}

	/**
	 * Registers a callback for our specific plugin's actions
	 *
	 * @param array $links An array of plugin action links.
	 */
	public function action_links( $links ) {
		$action_links = array(
			'settings' => sprintf(
				'<a href="%s">%s</a>',
				flextension_get_admin_page_url( $this->menu_slug, 'admin' ),
				esc_html__( 'Settings', 'flextension' )
			),
		);

		return array_merge( $action_links, $links );
	}

	/**
	 * Registers the plugin settings page.
	 */
	public function add_settings_page() {
		$svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 34 34"><path fill="currentColor" fill-rule="evenodd" clip-rule="evenodd" d="M18.7,2H32v13.3H18.7V2z M22,5.3h6.7V12H22V5.3z M25.3,32V18.7h-10v-10H2V32H25.3z M12,12H5.3v6.7H12V12z   M5.3,28.7V22H12v6.7H5.3z M15.3,28.7V22H22v6.7H15.3z"/></svg>';

		$icon = 'data:image/svg+xml;base64,' . base64_encode( $svg );

		$this->settings_page = add_menu_page(
			esc_html__( 'Flextension Modules', 'flextension' ),
			esc_html__( 'Flextension', 'flextension' ),
			'manage_options',
			flextension_get_page_slug( $this->menu_slug ),
			array( $this, 'settings_page' ),
			$icon
		);
	}

	/**
	 * Prints out the plugin settings page.
	 */
	public function settings_page() {

		if ( ! class_exists( 'Flextension_Modules_List_Table', false ) ) {
			require_once FLEXTENSION_PATH . 'admin/class-flextension-modules-list-table.php';
		}

		?>
		<div class="wrap flext-manager">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			<?php
			$modules_list_table = new Flextension_Modules_List_Table();
			$modules_list_table->prepare_items();
			$modules_list_table->views();
			?>
			<form id="bulk-action-form" method="post">
				<input type="hidden" name="module_status" value="<?php echo esc_attr( $modules_list_table->view_context ); ?>" />
				<?php $modules_list_table->display(); ?>
			</form>
			<script>
			if ( window.history.replaceState ) {
				window.history.pushState( null, '', '<?php echo esc_url( flextension_get_admin_page_url( $this->menu_slug, 'admin' ) ); ?>' );
			}
			</script>
		</div>
		<?php
	}

	/**
	 * Enqueues scripts and stylesheets for the admin page.
	 *
	 * @param string $hook Current page.
	 */
	public function admin_enqueue_scripts( $hook ) {
		if ( $hook === $this->settings_page ) {
			wp_enqueue_style( 'flextension-admin', FLEXTENSION_URL . 'assets/css/admin.css', array( 'flextension' ), flextension_get_setting( 'version' ) );
			wp_style_add_data( 'flextension-admin', 'rtl', 'replace' );
		}
	}

}
