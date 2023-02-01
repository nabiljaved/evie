<?php
/**
 * Product Attribute Editor
 *
 * Adds Color, Image and Button variation swatches for the product attributes.
 *
 * @package    Evie_XT
 * @subpackage Modules/WooCommerce/Admin
 * @version    1.0.0
 */

/**
 * Product Attribute Edit class.
 */
class Evie_Product_Attribute_Edit {

	/**
	 * The module control manager.
	 *
	 * @var Flextension_Module_Control
	 */
	private $control;

	/**
	 * The current taxonomy to add or edit.
	 *
	 * @var string
	 */
	private $taxonomy;

	/**
	 * The current type of the attribute.
	 *
	 * @var string
	 */
	private $type;

	/**
	 * Initializes the class, adds actions and filters.
	 */
	public function __construct() {
		$this->taxonomy = isset( $_REQUEST['taxonomy'] ) ? sanitize_title( wp_unslash( $_REQUEST['taxonomy'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		// Only load when the current taxonomy is capable.
		if ( $this->can_load() ) {
			$this->control = new Flextension_Module_Control();
			// Initialize.
			add_action( 'admin_init', array( $this, 'admin_init' ) );

			// Add and edit taxonomy.
			add_action( 'create_term', array( $this, 'save_attribute' ), 10, 3 );

			add_action( 'edit_term', array( $this, 'save_attribute' ), 10, 3 );

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
		add_action( "{$taxonomy}_edit_form_fields", array( $this, 'edit_term_form_fields' ), 10, 2 );

		// Add custom columns to the term table list.
		add_filter( "manage_edit-{$taxonomy}_columns", array( $this, 'manage_term_columns' ) );
		add_filter( "manage_{$taxonomy}_custom_column", array( $this, 'manage_term_columns_fields' ), 10, 3 );
	}

	/**
	 * Adds custom form fields when adding a new term.
	 *
	 * @param string $taxonomy The taxonomy slug.
	 */
	public function add_term_form_fields( $taxonomy ) {

		$fields = evie_wc_attribute_setting_fields( $this->type );

		if ( empty( $fields ) ) {
			return;
		}

		foreach ( $fields as $field ) {
			$this->control->render( $field );
		}
	}

	/**
	 * Adds custom form fields when editing a term.
	 *
	 * @param object $term Current taxonomy term object.
	 * @param string $taxonomy Current taxonomy slug.
	 */
	public function edit_term_form_fields( $term, $taxonomy ) {

		$fields = evie_wc_attribute_setting_fields( $this->type );

		if ( empty( $fields ) ) {
			return;
		}

		foreach ( $fields as $field ) {

			$field['value'] = evie_wc_get_attribute_meta_value( $term->term_id, $this->type );

			?>
			<tr class="form-field flext-term-form-field">
				<th scope="row">
					<label for="<?php echo esc_attr( $field['name'] ); ?>"><?php echo esc_html( $field['label'] ); ?></label>
				</th>
				<td>
					<?php
					unset( $field['label'] ); // Remove the field's label.

					$this->control->render( $field );
					?>
				</td>
			</tr>
			<?php

		}
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

		$column_name = 'preview-' . $this->type;

		$new_columns[ $column_name ] = '';

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
	public function manage_term_columns_fields( $content, $column_name, $term_id ) {
		global $taxonomy;

		if ( 'preview-' . $this->type === $column_name ) {
			$content .= $this->get_attribute_column_content( $term_id, $this->type );
		}

		return $content;
	}

	/**
	 * Returns the attribute column content.
	 *
	 * @param string $term_id The term taxonomy ID.
	 * @param string $type    The attribute type.
	 * @return string Attribute column content.
	 */
	public function get_attribute_column_content( $term_id, $type ) {
		$content = '';
		switch ( $type ) {
			case 'color':
				$value = evie_wc_get_attribute_meta_value( $term_id, $type );
				$color = sanitize_hex_color( $value );
				if ( ! empty( $color ) ) {
					$content = sprintf( '<span class="flext-pa-icon flext-pa-color" style="background-color:%s;"></span>', esc_attr( $color ) );
				}
				break;
			case 'image':
				$value       = evie_wc_get_attribute_meta_value( $term_id, $type );
				$image_id    = absint( $value );
				$image_attrs = wp_get_attachment_image_src( $image_id, 'thumbnail' );
				if ( is_array( $image_attrs ) && ! empty( $image_attrs[0] ) ) {
					$content = sprintf( '<img src="%s" alt="" width="%d" height="%d" class="flext-pa-icon flext-pa-image" />', esc_attr( esc_url( $image_attrs[0] ) ), esc_attr( $image_attrs[1] ), esc_attr( $image_attrs[2] ) );
				}
				break;
		}

		/**
		 * Filters the attribute column content.
		 *
		 * @param string $content Attribute column content.
		 * @param string $term_id The term taxonomy ID.
		 * @param string $type    The attribute type.
		 */
		return apply_filters( 'evie_wc_attribute_column_content', $content, $term_id, $type );
	}

	/**
	 * Saves the featured and image id of the taxonomy.
	 *
	 * @param int    $term_id      Term ID.
	 * @param int    $tt_id        Term taxonomy ID.
	 * @param string $taxonomy  Taxonomy slug.
	 */
	public function save_attribute( $term_id, $tt_id, $taxonomy ) {

		// Bail if we're doing an auto save.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		$fields = evie_wc_attribute_setting_fields( $this->type );

		if ( empty( $fields ) ) {
			return;
		}

		foreach ( $fields as $field ) {
			/**
			 * Nonce has been verified by WordPress before doing this action.
			 * The value will be sanitized by the Control Manager based on the field type before saving.
			 */
			$value = isset( $_POST[ $field['name'] ] ) ? wp_unslash( $_POST[ $field['name'] ] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

			if ( ! empty( $value ) ) {

				$value = $this->control->sanitize( $value, array( 'type' => $this->type ) );

				update_term_meta( $term_id, 'product_attribute_' . $this->type, $value );
			}
		}

	}

	/**
	 * Returns whether the taxonomy is allowed.
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
			if ( true === $this->is_capable_taxonomy( $this->taxonomy ) ) {

				$this->type = evie_wc_get_attribute_type( $this->taxonomy );

				$attribute_types = evie_wc_get_attribute_types();

				$valid_types = is_array( $attribute_types ) ? array_keys( $attribute_types ) : array();

				return in_array( $this->type, $valid_types, true );
			}
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
			$taxonomies = wc_get_attribute_taxonomy_names();
			$is_capable = in_array( $taxonomy, $taxonomies, true );
		}
		return $is_capable;
	}

	/**
	 * Enqueues the scripts and stylesheets for the admin page.
	 */
	public function admin_enqueue_scripts() {

		wp_enqueue_style( 'evie-wc-pa-editor', plugins_url( 'css/editor.css', __FILE__ ), array(), EVIE_XT_VERSION );

		wp_enqueue_script( 'evie-wc-pa-editor', plugins_url( 'js/editor.js', __FILE__ ), array(), EVIE_XT_VERSION, true );

	}

}
