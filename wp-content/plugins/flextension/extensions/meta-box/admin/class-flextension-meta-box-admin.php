<?php
/**
 * Adds custom meta box tabs to the admin area.
 *
 * @package    Flextension
 * @subpackage Extensions/Meta_Box/Admin
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Meta Box Admin module.
 *
 * Adds a Meta Box module.
 */
class Flextension_Meta_Box_Admin extends Flextension_Module_Admin {

	/**
	 * Whether the meta box is saved.
	 *
	 * @var bool $saved Whether the meta box is saved.
	 */
	public $saved = false;

	/**
	 * Whether the meta box nonce is rendered.
	 *
	 * @var bool $nonce_rendered Whether the meta box nonce is rendered.
	 */
	private $nonce_rendered = false;

	/**
	 * The meta box dependencies.
	 *
	 * @var array $dependencies The meta box dependencies.
	 */
	private $dependencies = array();

	/**
	 * Initializes the module.
	 */
	public function initialize() {

		add_action( 'load-post.php', array( $this, 'init_meta_box' ) );

		add_action( 'load-post-new.php', array( $this, 'init_meta_box' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

	}

	/**
	 * Initializes the meta boxes.
	 */
	public function init_meta_box() {

		$this->control = new Flextension_Module_Control();

		add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ), 10, 2 );

		add_action( 'save_post', array( $this, 'save_meta_box' ), 10, 2 );
	}

	/**
	 * Sanitizes the ID of field.
	 *
	 * @param string $id      The ID of field.
	 * @param string $replace The replacement value.
	 * @return string Sanitized value of the ID of field.
	 */
	public function sanitize_id( $id, $replace = '-' ) {

		if ( ! empty( $id ) ) {
			$id = strtolower( $id );
			$id = preg_replace( '/[^a-z0-9\-]/', $replace, $id );
			$id = trim( $id, $replace );
		}

		return $id;
	}

	/**
	 * Returns the active meta box sections for given post type.
	 *
	 * @param string  $post_type The post type to retrieve the meta box sections.
	 * @param WP_Post $post     Post object.
	 * @return array An array list of the active meta box sections.
	 */
	public function get_active_sections( $post_type = '', $post = 0 ) {

		$active_sections = array();

		/**
		* Filters the meta box sections.
		*
		* @param array   $sections An array list of the meta box sections.
		* @param WP_Post $post     Post object.
		*/
		$sections = (array) apply_filters( 'flextension_meta_box_sections', array(), $post );

		if ( ! empty( $sections ) ) {

			$defaults = array(
				'id'          => '',
				'icon'        => '',
				'title'       => '',
				'description' => '',
				'post_types'  => '',
				'context'     => 'advanced',
				'priority'    => 'default',
				'fields'      => array(),
			);

			foreach ( $sections as $section ) {

				$section = wp_parse_args( $section, $defaults );

				if ( in_array( $post_type, (array) $section['post_types'], true ) ) {

					array_push( $active_sections, $section );

				}
			}
		}

		return $active_sections;
	}

	/**
	 * Adds only one meta box per post type.
	 *
	 * @param string  $post_type Post type.
	 * @param WP_Post $post      Post object.
	 */
	public function add_meta_box( $post_type, $post ) {

		$post_type_object = get_post_type_object( $post_type );

		if ( null === $post_type_object ) {
			return;
		}

		$sections = $this->get_active_sections( $post_type, $post );

		if ( empty( $sections ) ) {
			return;
		}

		$meta_box = array(
			'id'       => 'flextension-meta-box',
			'title'    => sprintf(
				/* translators: %s: Name of the post type */
				esc_html__( '%s Settings', 'flextension' ),
				$post_type_object->labels->singular_name
			),
			'context'  => 'advanced',
			'priority' => 'default',
		);

		/**
		* Filters the meta box settings.
		*
		* @param array $meta_box An array list of the meta box settings.
		* @param WP_Post $post   Post object.
		*/
		$meta_box = apply_filters( 'flextension_meta_box', $meta_box, $post );

		if ( null !== $meta_box ) {

			$advanced_sections = array();

			foreach ( $sections as $section ) {

				if ( 'side' === $section['context'] ) {

					$section_id = $this->sanitize_id( 'flextension-meta-box-' . $section['id'] );

					add_meta_box(
						$section_id,
						$section['title'],
						array( $this, 'render_side_meta_box' ),
						$post_type,
						'side',
						$section['priority'],
						(array) $section['fields']
					);

					if ( isset( $section['templates'] ) ) {

						$this->dependencies[] = array(
							'selector'  => '#' . $section_id,
							'templates' => (array) $section['templates'],
						);

					}
				} else {
					array_push( $advanced_sections, $section );
				}
			}

			if ( ! empty( $advanced_sections ) ) {

				add_meta_box(
					$this->sanitize_id( $meta_box['id'] ),
					$meta_box['title'],
					array( $this, 'render_meta_box' ),
					$post_type,
					$meta_box['context'],
					$meta_box['priority'],
					$advanced_sections
				);

			}
		}

	}

	/**
	 * Renders nonce hidden field for meta box.
	 */
	public function render_nonce() {
		if ( true !== $this->nonce_rendered ) {
			// Add nonce for security and authentication.
			wp_nonce_field( 'flextension_meta_box', 'flextension_meta_nonce' );
			$this->nonce_rendered = true;
		}
	}

	/**
	 * Renders the meta box content.
	 *
	 * @param WP_Post $post     The post object.
	 * @param array   $meta_box The meta box object.
	 */
	public function render_side_meta_box( $post, $meta_box = array() ) {

		if ( ! $post ) {
			return;
		}

		$fields = is_array( $meta_box['args'] ) ? (array) $meta_box['args'] : array();

		$this->render_fields( $post, $fields );

		$this->render_nonce();
	}

	/**
	 * Renders the meta box content.
	 *
	 * @param WP_Post $post     The post object.
	 * @param array   $meta_box The meta box object.
	 */
	public function render_meta_box( $post, $meta_box = array() ) {

		if ( ! $post ) {
			return;
		}

		$sections = is_array( $meta_box['args'] ) ? (array) $meta_box['args'] : array();

		if ( ! empty( $sections ) ) {

			echo '<div class="flext-tabs flext-meta-box-tabs is-vertical">';

			$this->render_tab_menus( $post, $sections );

			$this->render_tab_content( $post, $sections );

			echo '<div class="clear"></div>';

			echo '</div>';

		}

		$this->render_nonce();
	}

	/**
	 * Renders the meta box tab navigation menus.
	 *
	 * @param WP_Post $post     The post object.
	 * @param array   $sections The meta box sections to render.
	 */
	public function render_tab_menus( $post, $sections = array() ) {

		echo '<div class="flext-tabs-nav">';
		foreach ( (array) $sections as $section ) {
			$icon = isset( $section['icon'] ) ? $section['icon'] : '';
			if ( empty( $icon ) ) {
				$icon = 'dashicons dashicons-marker';
			}
			echo sprintf( '<a href="#%1$s-tab"><i class="%2$s"></i><span>%3$s</span></a>', esc_attr( $section['id'] ), esc_attr( $icon ), esc_html( $section['title'] ) );
		}
		echo '</div>';

	}

	/**
	 * Renders the meta box tab content.
	 *
	 * @param WP_Post $post     The post object.
	 * @param array   $sections The meta box sections to render.
	 */
	public function render_tab_content( $post, $sections = array() ) {

		echo '<div class="flext-tab-wrapper">';

		foreach ( (array) $sections as $section ) {
			echo sprintf( '<div id="%s-tab" class="flext-tab">', esc_attr( $section['id'] ) );

			if ( ! empty( $section['description'] ) ) {
				echo '<p class="section-description">' . esc_html( $section['description'] ) . '</p>';
			}

			$this->render_fields( $post, $section['fields'] );

			echo '</div>';
		}

		echo '</div>';

	}

	/**
	 * Renders the meta box fields.
	 *
	 * @param WP_Post $post   The post object.
	 * @param array   $fields An array list of the fields.
	 */
	public function render_fields( $post, $fields = array() ) {

		foreach ( (array) $fields as $field ) {

			if ( isset( $field['name'] ) ) {
				$field['value'] = flextension_get_post_meta( $post->ID, $field['name'], $field['type'] );
			}

			$field['field_before'] = '<div class="flext-meta-field">';
			$field['field_after']  = '</div>';

			$field['label_before'] = '<div class="flext-meta-label">';
			$field['label_after']  = '</div>';

			$this->control->render( $field );
		}
	}

	/**
	 * Saves the meta box data.
	 *
	 * @param int     $post_id Post ID.
	 * @param WP_Post $post    Post object.
	 */
	public function save_meta_box( $post_id, $post ) {

		// If it is saved already.
		if ( $this->saved ) {
			return;
		}

		// $post_id and $post are required
		if ( empty( $post_id ) || empty( $post ) ) {
			return;
		}

		// Verify this came from our screen and with proper authorization.
		if ( ! isset( $_POST['flextension_meta_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['flextension_meta_nonce'] ), 'flextension_meta_box' ) ) {
			return;
		}

		// Check if user has permissions to save data.
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		// Check if not an autosave.
		if ( wp_is_post_autosave( $post_id ) || wp_is_post_revision( $post_id ) ) {
			return;
		}

		$sections = $this->get_active_sections( $post->post_type, $post );

		if ( ! empty( $sections ) ) {

			foreach ( $sections as $section ) {

				/**
				 * If this section is not available for the current template,
				 * delete section's fields from the post meta data.
				 */
				if ( isset( $section['templates'] ) && ! in_array( get_page_template_slug( $post_id ), (array) $section['templates'], true ) ) {
					$this->delete_section( $post_id, $section );
				} else {
					$this->save_section( $post_id, $section );
				}
			}
		}

		$this->saved = true;

		do_action( 'flextension_meta_box_save', $post_id, $post );
	}

	/**
	 * Saves section's fields into the post meta data.
	 *
	 * @param int   $post_id Post ID.
	 * @param array $section The section object.
	 */
	public function save_section( $post_id, $section ) {
		foreach ( (array) $section['fields'] as $field ) {
			$this->save_field( $post_id, $field );
		}
	}

	/**
	 * Delete section's fields from the post meta data.
	 *
	 * @param int   $post_id Post ID.
	 * @param array $section The section object.
	 */
	public function delete_section( $post_id, $section ) {
		foreach ( (array) $section['fields'] as $field ) {
			$this->delete_field( $post_id, $field );
		}
	}

	/**
	 * Sanitizes the value before saving.
	 *
	 * @param string $value The value to sanitize.
	 * @param array  $field An array object of the field.
	 * @return string The sanitized value.
	 */
	public function sanitize( $value, $field ) {

		if ( $this->control ) {

			return $this->control->sanitize( $value, $field );
		}

		return $value;
	}

	/**
	 * Saves the meta field data.
	 *
	 * @param int   $post_id Post ID.
	 * @param array $field   An array object of the field.
	 */
	public function save_field( $post_id, $field ) {

		if ( empty( $field['name'] ) ) {
			return;
		}

		$name = $field['name'];

		$multiple = 'checkbox_list' === $field['type'];

		if ( true === $multiple ) {

			delete_post_meta( $post_id, $name );

			$values = isset( $_POST[ $name ] ) ? $this->sanitize( (array) wp_unslash( $_POST[ $name ] ) ) : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.NonceVerification.Missing
			foreach ( $values as $value ) {
				add_post_meta( $post_id, $name, $value, false );
			}
		} else {

			$value = isset( $_POST[ $name ] ) ? $this->sanitize( wp_unslash( $_POST[ $name ] ), $field ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.NonceVerification.Missing

			if ( ! empty( $value ) ) {
				update_post_meta( $post_id, $name, $value );
			} else {
				delete_post_meta( $post_id, $name );
			}
		}

	}

	/**
	 * Deletes the meta field data.
	 *
	 * @param int   $post_id Post ID.
	 * @param array $field   An array object of the field.
	 */
	public function delete_field( $post_id, $field ) {

		if ( empty( $field['name'] ) ) {
			return;
		}

		$name = $field['name'];

		$multiple = 'checkbox_list' === $field['type'];

		if ( true === $multiple ) {

			delete_post_meta( $post_id, $name );

		} else {

			delete_post_meta( $post_id, $name );

		}

	}

	/**
	 * Enqueues the settings controls scripts and stylesheets.
	 *
	 * @param string $hook Current page.
	 */
	public function admin_enqueue_scripts( $hook ) {

		if ( in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {

			wp_enqueue_style( 'flextension-meta-box', plugins_url( 'css/style.css', __FILE__ ), array( 'flextension-tabs' ), flextension_get_setting( 'version' ) );
			wp_style_add_data( 'flextension-meta-box', 'rtl', 'replace' );

			wp_enqueue_script( 'flextension-meta-box', plugins_url( 'js/index.js', __FILE__ ), array( 'wp-data', 'flextension-tabs' ), flextension_get_setting( 'version' ), true );

			if ( ! empty( $this->dependencies ) ) {
				wp_localize_script( 'flextension-meta-box', 'flextensionMetaBoxDependencies', $this->dependencies );
			}
		}
	}

}
