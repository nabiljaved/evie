<?php
/**
 * Featured Categories Editor
 *
 * @package    Flextension
 * @subpackage Modules/Featured_Categories/Admin
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Featured Categories Edit class.
 */
class Flextension_Featured_Categories_Edit {

	/**
	 * The current taxonomy to add or edit.
	 *
	 * @var string
	 */
	private $taxonomy;

	/**
	 * Initializes the class, adds actions and filters.
	 */
	public function __construct() {
		$this->taxonomy = isset( $_REQUEST['taxonomy'] ) ? sanitize_title( wp_unslash( $_REQUEST['taxonomy'] ) ) : '';  // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		add_action( 'init', array( $this, 'init' ), 20 );
	}

	/**
	 * Adds actions and filters.
	 */
	public function init() {
		// Only load when the current taxonomy is capable.
		if ( $this->can_load() ) {
			// Initialize.
			add_action( 'admin_init', array( $this, 'admin_init' ) );

			// Add and edit taxonomy.
			add_action( 'create_term', array( $this, 'save_term' ) );

			add_action( 'edit_term', array( $this, 'save_term' ) );

			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		}
	}

	/**
	 * Initializes taxonomy form fields.
	 */
	public function admin_init() {
		$this->add_term_field( $this->taxonomy );
	}

	/**
	 * Adds custom form fields and columns when adding and editing a term.
	 *
	 * @param string $taxonomy The taxonomy slug.
	 */
	public function add_term_field( $taxonomy ) {
		add_action( "{$taxonomy}_add_form_fields", array( $this, 'add_term_form_fields' ) );
		add_action( "{$taxonomy}_edit_form_fields", array( $this, 'edit_term_form_fields' ) );

		// Add custom columns to the term table list.
		add_filter( "manage_edit-{$taxonomy}_columns", array( $this, 'manage_term_columns' ) );
		add_filter( "manage_{$taxonomy}_custom_column", array( $this, 'manage_term_column_content' ), 10, 3 );
	}

	/**
	 * Adds custom form fields when adding a new term.
	 */
	public function add_term_form_fields() {

		$args = array(
			'name'          => 'flext_term_image_id',
			'label'         => esc_html__( 'Featured Image', 'flextension' ),
			'type'          => 'image',
			'preview_size'  => $this->term_preview_size(),
			'wrapper_class' => 'form-field term-image-wrap',
		);

		$control = new Flextension_Module_Control();

		$control->render( $args );
	}

	/**
	 * Returns a preview image size for the term.
	 *
	 * @since 1.1.1
	 *
	 * @return string|array The preview thumbnail size. Image size or array of width and height values (in that order).
	 */
	public function term_preview_size() {
		/**
		 * Filters the preview thumbnail size for the term.
		 *
		 * @since 1.1.1
		 *
		 * @param string|array $size The preview thumbnail size. Image size or array of width and height values (in that order).
		 */
		return apply_filters( 'flextension_category_thumbnail_preview_size', 'thumbnail' );
	}

	/**
	 * Adds custom form fields when editing a term.
	 *
	 * @param object $term Current taxonomy term object.
	 */
	public function edit_term_form_fields( $term ) {

		$args = array(
			'name'         => 'flext_term_image_id',
			'type'         => 'image',
			'value'        => flextension_get_term_thumbnail_id( $term->term_id ),
			'preview_size' => $this->term_preview_size(),
		);

		$control = new Flextension_Module_Control();

		?>
		<tr class="form-field flext-term-form-field flext-term-image-field">
			<th scope="row">
				<label for="flext-term-image-id"><?php echo esc_html__( 'Featured Image', 'flextension' ); ?></label>
			</th>
			<td>
				<?php $control->render( $args ); ?>
			</td>
		</tr>
		<?php
	}

	/**
	 * Adds custom columns to the taxonomy list.
	 *
	 * @param array $columns The list of columns.
	 * @return array The list of columns to print on the manage screen for a taxonomy
	 */
	public function manage_term_columns( $columns ) {
		$new_columns = array();

		if ( isset( $columns['cb'] ) ) {
			$new_columns['cb'] = $columns['cb'];
		}

		$new_columns['image'] = esc_html__( 'Image', 'flextension' );

		if ( isset( $columns['cb'] ) ) {
			unset( $columns['cb'] );
		}

		return array_merge( $new_columns, $columns );
	}

	/**
	 * Adds column content to the taxonomy list.
	 *
	 * @param string $content     Column content.
	 * @param string $column_name The name of columns.
	 * @param string $term_id     The term taxonomy ID.
	 * @return string Column content to print on the the taxonomy list.
	 */
	public function manage_term_column_content( $content, $column_name, $term_id ) {

		if ( 'image' === $column_name ) {
			$content = flextension_get_term_thumbnail( $term_id, array( 50, 50 ) );
		}

		return $content;
	}

	/**
	 * Saves the featured and image id of the taxonomy.
	 *
	 * @param int $term_id Term ID.
	 */
	public function save_term( $term_id ) {

		// Bail if we're doing an auto save.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		/**
		 * Nonce has been verified by WordPress core in wp-admin/edit-tags.php before doing this action.
		 */
		$value = isset( $_POST['flext_term_image_id'] ) ? absint( wp_unslash( $_POST['flext_term_image_id'] ) ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Missing

		$meta_key = flextension_term_thumbnail_meta_key();

		if ( ! empty( $value ) ) {
			update_term_meta( $term_id, $meta_key, $value );
		} else {
			delete_term_meta( $term_id, $meta_key );
		}

	}

	/**
	 * Returns whether the taxonomy is capable.
	 */
	public function can_load() {

		$is_enabled = false;
		if ( wp_doing_ajax() ) {
			$is_enabled = isset( $_POST['action'] ) ? 'add-tag' === sanitize_title( wp_unslash( $_POST['action'] ) ) : false; // phpcs:ignore WordPress.Security.NonceVerification.Missing
		} else {
			global $pagenow;
			$is_enabled = ( 'edit-tags.php' === $pagenow || 'term.php' === $pagenow );
		}

		if ( true === $is_enabled ) {
			return $this->is_capable_taxonomy( $this->taxonomy );
		}

		return false;
	}

	/**
	 * Returns whether the taxonomy is capable.
	 *
	 * @param string $taxonomy The taxonomy slug.
	 * @return bool Whether the taxonomy is capable.
	 */
	public function is_capable_taxonomy( $taxonomy = '' ) {
		$is_capable = false;
		if ( ! empty( $taxonomy ) ) {
			$taxonomies = (array) flextension_featured_taxonomies();
			$is_capable = in_array( $taxonomy, $taxonomies, true );
		}
		return $is_capable;
	}

	/**
	 * Loads the scripts and stylesheets for the admin page.
	 */
	public function admin_enqueue_scripts() {

		wp_enqueue_style( 'flextension-featured-categories-admin', plugins_url( 'css/style.css', __FILE__ ), array(), flextension_get_setting( 'version' ) );

		wp_enqueue_script( 'flextension-featured-categories-admin', plugins_url( 'js/index.js', __FILE__ ), array( 'flextension' ), flextension_get_setting( 'version' ), true );

	}

}
