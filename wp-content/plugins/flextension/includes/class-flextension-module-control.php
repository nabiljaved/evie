<?php
/**
 * Flextension Module Control
 *
 * Renders setting control.
 *
 * @package    Flextension
 * @subpackage Includes
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Module Control class
 */
class Flextension_Module_Control {

	/**
	 * Initializes the class, adds actions and filters.
	 */
	public function __construct() {

		add_action( 'init', array( $this, 'register_scripts' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

	}

	/**
	 * Prints out the settings field.
	 *
	 * @param array $args Optional. Extra arguments used when outputting the field.
	 */
	public function render( $args = array() ) {
		if ( ! empty( $args ) ) {

			$this->render_field( $args );

		}
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
	 * Filters label text and strips out disallowed HTML.
	 *
	 * @param string $label The label text.
	 * @return string Filtered label text containing only the allowed HTML.
	 */
	public function esc_label( $label = '' ) {
		$allowed_html = array(
			'a'      => array(
				'href'   => array(),
				'title'  => array(),
				'class'  => array(),
				'target' => array(),
			),
			'br'     => array(),
			'em'     => array(),
			'strong' => array(),
			'i'      => array(
				'class' => array(),
			),
			'span'   => array(
				'class' => array(),
			),
		);
		return wp_kses( $label, $allowed_html );
	}

	/**
	 * Prints out the HTML content for the field.
	 *
	 * @param array $args Optional. Extra arguments used when outputting the field.
	 */
	public function render_field( $args = array() ) {

		$args = wp_parse_args(
			$args,
			array(
				'type'               => 'text',
				'name'               => '',
				'icon'               => '',
				'label'              => '',
				'description'        => '',
				'value'              => null,
				'default'            => '',
				'class'              => '',
				'placeholder'        => '',
				'attributes'         => array(),
				'dependencies'       => array(),
				'sanitize_callback'  => '',
				'wrapper_class'      => '',
				'wrapper_attributes' => array(),
				'field_before'       => '',
				'field_after'        => '',
				'label_before'       => '',
				'label_after'        => '',
			)
		);

		/**
		 * Filters an array arguments used when outputting the field.
		 *
		 * @param array $args An array arguments used when outputting the field.
		 */
		$args = apply_filters( 'flextension_render_field_args', $args );

		if ( null === $args['value'] ) {
			$args['value'] = $args['default'];
		}

		$classes = array( 'flext-field' );

		$classes[] = 'flext-field-type-' . $this->sanitize_id( $args['type'] );

		if ( ! empty( $args['wrapper_class'] ) ) {
			$classes[] = $args['wrapper_class'];
		}

		$field_id = $this->sanitize_id( $args['name'] );

		echo sprintf(
			'<div id="flext-field-%1$s" class="%2$s" data-type="%3$s"%4$s%5$s>',
			esc_attr( $field_id ),
			esc_attr( implode( ' ', $classes ) ),
			esc_attr( $args['type'] ),
			flextension_get_attributes( $args['wrapper_attributes'] ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			$this->get_data_dependencies( $args['dependencies'] ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		);

		$this->field_label( $args );

		echo $args['field_before']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		$this->field( $args );

		echo $args['field_after']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		echo '</div>';
	}

	/**
	 * Prints out the field.
	 *
	 * @param array $args Optional. Extra arguments used when outputting the field.
	 */
	public function field( $args = array() ) {

		if ( ! isset( $args['type'] ) ) {
			$args['type'] = 'text';
		}

		switch ( $args['type'] ) {
			case 'button':
				$this->button_field( $args );
				break;
			case 'link_button':
				$this->link_button_field( $args );
				break;
			case 'checkbox':
				$this->checkbox_field( $args );
				break;
			case 'checkbox_list':
				$this->checkbox_list_field( $args );
				break;
			case 'color':
				$this->color_field( $args );
				break;
			case 'fieldset':
				$this->fieldset_field( $args );
				break;
			case 'fields_list':
				$this->fields_list_field( $args );
				break;
			case 'custom_fields':
				$this->custom_fields( $args );
				break;
			case 'image':
				$this->image_field( $args );
				break;
			case 'images':
				$this->images_field( $args );
				break;
			case 'image_select':
				$this->image_select_field( $args );
				break;
			case 'label':
				$this->label_field( $args );
				break;
			case 'media':
				$this->media_field( $args );
				break;
			case 'file':
				$this->file_field( $args );
				break;
			case 'radio':
				$this->radio_field( $args );
				break;
			case 'range':
				$this->range_field( $args );
				break;
			case 'textarea':
				$this->textarea_field( $args );
				break;
			case 'select':
				$this->select_field( $args );
				break;
			case 'select_pages':
				$this->select_pages_field( $args );
				break;
			case 'template':
				$this->template_field( $args );
				break;
			case 'wysiwyg':
			case 'html':
				$this->text_editor_field( $args );
				break;
			default:
				$this->input_field( $args );
		}
	}

	/**
	 * Prints out the label for the field.
	 *
	 * @param array $args Optional. Extra arguments used when outputting the field.
	 */
	public function field_label( $args = array() ) {
		if ( ! empty( $args['label'] ) ) {
			$label = $args['label'];
			if ( ! empty( $args['icon'] ) ) {
				$label = '<i class="' . esc_attr( $args['icon'] ) . '"></i> ' . $label;
			}

			echo sprintf(
				'%1$s<label for="%2$s">%3$s</label>%4$s',
				$args['label_before'], // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				$this->sanitize_id( $args['name'] ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				$this->esc_label( $label ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				$args['label_after'] // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			);
		}
	}

	/**
	 * Prints out the description for the field.
	 *
	 * @param array $args Optional. Extra arguments used when outputting the field.
	 */
	public function field_description( $args = array() ) {
		$description = $this->esc_label( $args['description'] );
		if ( ! empty( $description ) ) {
			echo '<p class="description">' . $description . '</p>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}

	/**
	 * Returns a data dependencies attribute for the field.
	 *
	 * @param array $dependencies Array of the dependencies.
	 * @return string The data dependencies attribute.
	 */
	public function get_data_dependencies( $dependencies = array() ) {
		$data_dependencies = '';
		if ( ! empty( $dependencies ) ) {
			$data_dependencies = wp_json_encode( $dependencies );
		}

		if ( ! empty( $data_dependencies ) ) {
			$data_dependencies = ' data-dependencies="' . esc_attr( $data_dependencies ) . '"';
		}

		return $data_dependencies;
	}

	/**
	 * Prints out the input field.
	 *
	 * @param array $args Optional. Extra arguments used when outputting the field.
	 */
	public function input_field( $args = array() ) {

		if ( empty( $args['class'] ) ) {
			switch ( $args['type'] ) {
				case 'email':
				case 'text':
				case 'url':
					$args['class'] = 'regular-text';
					break;
				case 'number':
					$args['class'] = 'small-text';
					break;
			}
		}

		echo sprintf(
			'<input id="%1$s" name="%2$s" type="%3$s" value="%4$s" placeholder="%5$s" class="%6$s"%7$s />',
			$this->sanitize_id( $args['name'] ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			esc_attr( $args['name'] ),
			esc_attr( $args['type'] ),
			esc_attr( $args['value'] ),
			esc_attr( $args['placeholder'] ),
			esc_attr( $args['class'] ),
			flextension_get_attributes( $args['attributes'] ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		);

		$this->field_description( $args );

	}

	/**
	 * Prints out the range field.
	 *
	 * @param array $args Optional. Extra arguments used when outputting the field.
	 */
	public function range_field( $args = array() ) {

		echo '<div class="flext-field-range-wrapper">';
		if ( isset( $args['marks'] ) && ! empty( $args['marks'] ) ) {
			echo '<div class="flext-field-range-marks">';
			foreach ( $args['marks'] as $value => $label ) {
				echo '<span>' . esc_html( $label ) . '</span>';
			}
			echo '</div>';
		}

		echo sprintf(
			'<input id="%1$s" name="%2$s" type="range" value="%3$s" class="%4$s"%5$s />',
			esc_attr( $this->sanitize_id( $args['name'] ) ),
			esc_attr( $args['name'] ),
			esc_attr( $args['value'] ),
			esc_attr( $args['class'] ),
			flextension_get_attributes( $args['attributes'] ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		);

		echo '<div class="flext-field-range-value-wrapper"><span class="flext-field-range-value">' . intval( $args['value'] ) . '</span></div>';

		echo '</div>';

		$this->field_description( $args );

	}

	/**
	 * Prints out the color picker field.
	 *
	 * @param array $args Optional. Extra arguments used when outputting the field.
	 */
	public function color_field( $args = array() ) {

		echo sprintf(
			'<input id="%1$s" name="%2$s" type="text" value="%3$s" data-default-color="%4$s" class="%5$s"%6$s />',
			$this->sanitize_id( $args['name'] ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			esc_attr( $args['name'] ),
			esc_attr( $args['value'] ),
			esc_attr( $args['default'] ),
			esc_attr( $args['class'] ),
			flextension_get_attributes( $args['attributes'] ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		);

		$this->field_description( $args );

	}

	/**
	 * Prints out the textarea field.
	 *
	 * @param array $args Optional. Extra arguments used when outputting the field.
	 */
	public function textarea_field( $args = array() ) {

		if ( ! isset( $args['row'] ) ) {
			$args['row'] = 5;
		}

		echo sprintf(
			'<textarea id="%1$s" name="%2$s" rows="%3$s" cols="40" class="code%4$s"%5$s>%6$s</textarea>',
			$this->sanitize_id( $args['name'] ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			esc_attr( $args['name'] ),
			esc_attr( $args['row'] ),
			esc_attr( $args['class'] ),
			flextension_get_attributes( $args['attributes'] ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			esc_textarea( $args['value'] )
		);

		$this->field_description( $args );

	}

	/**
	 * Prints out the button field.
	 *
	 * @param array $args Optional. Extra arguments used when outputting the field.
	 */
	public function button_field( $args = array() ) {

		$classes = array( 'button' );
		if ( ! empty( $args['class'] ) ) {
			$classes[] = $args['class'];
		}

		$icon = '';
		if ( ! empty( $args['icon'] ) ) {
			$icon = '<i class="' . esc_attr( $args['icon'] ) . '"></i>';
		}

		echo sprintf(
			'<button id="%1$s" class="%2$s"%3$s>%4$s %5$s</button>',
			$this->sanitize_id( $args['name'] ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			esc_attr( implode( ' ', $classes ) ),
			flextension_get_attributes( $args['attributes'] ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			$icon, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			esc_html( $args['value'] )
		);

		$this->field_description( $args );

	}

	/**
	 * Prints out the link button field.
	 *
	 * @param array $args Optional. Extra arguments used when outputting the field.
	 */
	public function link_button_field( $args = array() ) {

		$classes = array( 'button' );
		if ( ! empty( $args['class'] ) ) {
			$classes[] = $args['class'];
		}

		$icon = '';
		if ( ! empty( $args['icon'] ) ) {
			$icon = '<i class="' . esc_attr( $args['icon'] ) . '"></i>';
		}

		echo sprintf(
			'<a id="%1$s" href="%2$s" class="%3$s"%4$s>%5$s %6$s</a>',
			$this->sanitize_id( $args['name'] ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			esc_attr( esc_url( $args['url'] ) ),
			esc_attr( implode( ' ', $classes ) ),
			flextension_get_attributes( $args['attributes'] ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			$icon, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			esc_html( $args['value'] )
		);

		$this->field_description( $args );

	}

	/**
	 * Prints out the checkbox field.
	 *
	 * @param array $args Optional. Extra arguments used when outputting the field.
	 */
	public function checkbox_field( $args = array() ) {

		echo sprintf(
			'<input id="%1$s" name="%2$s" type="checkbox" value="1" class="%3$s"%4$s %5$s />',
			$this->sanitize_id( $args['name'] ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			esc_attr( $args['name'] ),
			esc_attr( $args['class'] ),
			flextension_get_attributes( $args['attributes'] ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			checked( ! empty( $args['value'] ), true, false )
		);

		if ( ! empty( $args['description'] ) ) {
			echo sprintf(
				'<label for="%1$s">%2$s</label>',
				$this->sanitize_id( $args['name'] ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				esc_html( $args['description'] )
			);
		}

	}

	/**
	 * Prints out the checkbox list field.
	 *
	 * @param array $args Optional. Extra arguments used when outputting the field.
	 */
	public function checkbox_list_field( $args = array() ) {

		// Multi Checkbox.
		if ( isset( $args['options'] ) && is_array( $args['options'] ) ) {

			$class = 'check-list';
			if ( isset( $args['layout'] ) && 'grid' === $args['layout'] ) {
				$class = 'check-list flext-grid grid-fixed';
			}

			echo sprintf( '<ul class="%s">', esc_attr( $class ) );

			// Checkbox options.
			$index = 0;
			foreach ( (array) $args['options'] as $value => $text ) {
				$index++;
				$checked = false;
				if ( isset( $args['value'] ) && is_array( $args['value'] ) && in_array( $value, $args['value'], true ) ) {
					$checked = true;
				}

				$name = $args['name'];

				$id = $this->sanitize_id( $name ? $name : 'flext-checkbox' ) . '-' . $index;

				if ( ! empty( $args['name'] ) ) {
					$name = $name . '[]';
				}

				echo '<li>';

				echo sprintf(
					'<input id="%1$s" name="%2$s" type="checkbox" value="%3$s" class="%4$s"%5$s %6$s />',
					esc_attr( $id ),
					esc_attr( $name ),
					esc_attr( $value ),
					esc_attr( $args['class'] ),
					flextension_get_attributes( $args['attributes'] ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					checked( $checked, true, false )
				);

				echo sprintf(
					'<label for="%1$s">%2$s</label>',
					esc_attr( $id ),
					$this->esc_label( $text ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				);

				echo '</li>';

			}

			echo '</ul>';

			$this->field_description( $args );

		}

	}

	/**
	 * Prints out the radio field.
	 *
	 * @param array $args Optional. Extra arguments used when outputting the field.
	 */
	public function radio_field( $args = array() ) {

		if ( isset( $args['options'] ) && is_array( $args['options'] ) ) {
			// Radio options.
			$index = 0;
			foreach ( (array) $args['options'] as $value => $text ) {
				$index++;
				$id = $this->sanitize_id( $args['name'] ) . '-' . $index;

				echo '<span class="select-option">';

				echo sprintf(
					'<input id="%1$s" name="%2$s" type="radio" value="%3$s" class="%4$s"%5$s %6$s />',
					esc_attr( $id ),
					esc_attr( $args['name'] ),
					esc_attr( $value ),
					esc_attr( $args['class'] ),
					flextension_get_attributes( $args['attributes'] ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					checked( $value, $args['value'], false )
				);

				echo sprintf(
					'<label for="%1$s">%2$s</label>',
					esc_attr( $id ),
					esc_html( $text )
				);

				echo '</span>';
			}

			$this->field_description( $args );
		}

	}

	/**
	 * Prints out the select field.
	 *
	 * @param array $args Optional. Extra arguments used when outputting the field.
	 */
	public function select_field( $args = array() ) {

		echo sprintf(
			'<select id="%1$s" name="%2$s" value="%3$s" class="%4$s"%5$s>',
			$this->sanitize_id( $args['name'] ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			esc_attr( $args['name'] ),
			esc_attr( $args['value'] ),
			esc_attr( $args['class'] ),
			flextension_get_attributes( $args['attributes'] ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		);

		if ( isset( $args['placeholder'] ) && ! empty( $args['placeholder'] ) ) {
			echo sprintf( '<option value="" selected disabled>%1$s</option>', esc_html( $args['placeholder'] ) );
		}

		if ( isset( $args['options'] ) && is_array( $args['options'] ) ) {
			foreach ( (array) $args['options'] as $value => $text ) {

				echo sprintf(
					'<option value="%1$s" %2$s>%3$s</option>',
					esc_attr( $value ),
					selected( $args['value'], $value, false ),
					esc_html( $text )
				);

			}
		}

		echo '</select>';

		$this->field_description( $args );
	}

	/**
	 * Prints out the select pages field.
	 *
	 * @param array $args Optional. Extra arguments used when outputting the field.
	 */
	public function select_pages_field( $args = array() ) {

		$pages_args = array(
			'depth'            => 0,
			'child_of'         => 0,
			'selected'         => $args['value'],
			'name'             => $args['name'],
			'id'               => $this->sanitize_id( $args['name'] ),
			'show_option_none' => ' ',
			'post_status'      => 'publish,private',
		);

		wp_dropdown_pages( $pages_args ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		$this->field_description( $args );
	}

	/**
	 * Prints out the label field.
	 *
	 * @param array $args Optional. Extra arguments used when outputting the field.
	 */
	public function label_field( $args = array() ) {

		echo sprintf(
			'<span class="flext-field-label%1$s"%3$s>%2$s</span>',
			esc_attr( ! empty( $args['class'] ) ? ' ' . $args['class'] : '' ),
			esc_attr( $args['text'] ),
			flextension_get_attributes( $args['attributes'] ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		);

		$this->field_description( $args );
	}

	/**
	 * Prints out the image select field.
	 *
	 * @param array $args Optional. Extra arguments used when outputting the field.
	 */
	public function image_select_field( $args = array() ) {

		if ( isset( $args['options'] ) && is_array( $args['options'] ) ) {
			$classes = array( 'flext-field-select-options' );
			if ( isset( $args['layout'] ) && 'grid' === $args['layout'] ) {
				$classes[] = 'flext-grid';
				if ( isset( $args['columns'] ) && ! empty( $args['columns'] ) ) {
					$classes[] = 'flext-columns-' . intval( $args['columns'] );
				}
			}

			echo '<ul class="' . esc_attr( implode( ' ', $classes ) ) . '">';

			$index = 0;

			foreach ( (array) $args['options'] as $value => $option ) {

				$index++;

				$id = $this->sanitize_id( $args['name'] ) . '-' . $index;

				echo '<li class="select-option">';

				echo sprintf(
					'<input id="%1$s" name="%2$s" type="radio" value="%3$s" class="%4$s"%5$s %6$s />',
					esc_attr( $id ),
					esc_attr( $args['name'] ),
					esc_attr( $value ),
					esc_attr( $args['class'] ),
					flextension_get_attributes( $args['attributes'] ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					checked( $value, $args['value'], false )
				);

				$image_url = '';
				$title     = '';
				$label     = '';
				if ( is_array( $option ) ) {
					$image_url = $option['url'];
					$title     = $option['title'];
				} else {
					$image_url = $option;
				}

				if ( ! empty( $title ) ) {
					$label = '<span>' . $option['title'] . '</span>';
				}

				echo sprintf(
					'<label for="%1$s"><img src="%2$s" alt="%3$s" />%4$s</label>',
					esc_attr( $id ),
					esc_url( $image_url ),
					esc_attr( $title ),
					$label  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				);

				echo '</li>';
			}

			echo '</ul>';

			$this->field_description( $args );
		}

	}

	/**
	 * Prints out the image field.
	 *
	 * @param array $args Optional. Extra arguments used when outputting the field.
	 */
	public function image_field( $args = array() ) {

		$args = wp_parse_args(
			$args,
			array(
				'preview_size'  => 'thumbnail',
				'add_button'    => esc_html__( 'Add image', 'flextension' ),
				'remove_button' => esc_html__( 'Remove image', 'flextension' ),
			)
		);

		$image_id = $args['value'];

		$preview_size = $args['preview_size'];
		if ( ! is_string( $preview_size ) ) {
			$preview_size = 'thumbnail';
		}

		$image_output = sprintf( '<img data-size="%s" style="display:none;" />', esc_attr( $preview_size ) );

		if ( ! empty( $image_id ) ) {
			$image_attrs = wp_get_attachment_image_src( $image_id, $args['preview_size'] );
			if ( false !== $image_attrs ) {
				$image_output = sprintf(
					'<img src="%s" width="%s" height="%s" data-size="%s" />',
					esc_attr( $image_attrs[0] ),
					esc_attr( $image_attrs[1] ),
					esc_attr( $image_attrs[2] ),
					esc_attr( $preview_size )
				);
			} else {
				$image_id = '';
			}
		}

		?>
		<input type="hidden" class="image-id" name="<?php echo esc_attr( $args['name'] ); ?>" id="<?php echo $this->sanitize_id( $args['name'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>" value="<?php echo esc_attr( $image_id ); ?>">

		<?php echo '<div class="image-wrapper">' . $image_output . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

		<div class="flext-field-buttons">
			<button class="button button-secondary add-image-button"><?php echo esc_html( $args['add_button'] ); ?></button>
			<button class="button button-secondary remove-image-button"><?php echo esc_html( $args['remove_button'] ); ?></button>
		</div>
		<?php

		$this->field_description( $args );
	}

	/**
	 * Prints out the images field.
	 *
	 * @param array $args Optional. Extra arguments used when outputting the field.
	 */
	public function images_field( $args = array() ) {

		$args = wp_parse_args(
			$args,
			array(
				'preview_size' => 'thumbnail',
				'add_button'   => esc_html__( 'Add images', 'flextension' ),
			)
		);

		$image_ids = explode( ',', $args['value'] );

		?>

		<ul class="flext-gallery-images">
			<?php
			$new_image_ids = array();

			foreach ( $image_ids as $image_id ) {

				$image = wp_get_attachment_image( $image_id, $args['preview_size'] );

				// if attachment is empty skip.
				if ( empty( $image ) ) {
					continue;
				}

				echo '<li class="flext-image" data-image-id="' . esc_attr( $image_id ) . '">' . $image . '<a href="#" class="delete-item-button"><i class="dashicons dashicons-no-alt"></i></a></li>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

				// rebuild ids to be saved.
				$new_image_ids[] = $image_id;
			}
			?>
		</ul>

		<input type="hidden" class="flext-image-ids" name="<?php echo esc_attr( $args['name'] ); ?>" id="<?php echo $this->sanitize_id( $args['name'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>" value="<?php echo esc_attr( implode( ',', $new_image_ids ) ); ?>">

		<div class="flext-field-buttons hide-if-no-js">
			<button class="button is-secondary add-image-button"><?php echo esc_html( $args['add_button'] ); ?></button>
		</div>
		<?php

		$this->field_description( $args );
	}

	/**
	 * Prints out the media field.
	 *
	 * @param array $args Optional. Extra arguments used when outputting the field.
	 */
	public function media_field( $args = array() ) {

		$args = wp_parse_args(
			$args,
			array(
				'media_type'    => '',
				'media_url'     => true,
				'preview_size'  => array(),
				'add_button'    => esc_html__( 'Media Library', 'flextension' ),
				'remove_button' => esc_html__( 'Remove media', 'flextension' ),
			)
		);

		$this->field_description( $args );

		$input_type = 'hidden';
		if ( true === $args['media_url'] ) {
			$input_type = 'url';
		}

		echo sprintf(
			'<input type="%1$s" class="flext-field-media-url" name="%2$s" id="%3$s" value="%4$s" data-type="%5$s">',
			esc_attr( $input_type ),
			esc_attr( $args['name'] ),
			$this->sanitize_id( $args['name'] ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			esc_attr( $args['value'] ),
			esc_attr( $args['media_type'] )
		);

		echo '<div class="flext-field-media-preview">';
		if ( ! empty( $args['value'] ) && ! empty( $args['preview_size'] ) ) {
			echo wp_oembed_get( $args['value'], $args['preview_size'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
		echo '</div>';

		?>

		<div class="flext-field-buttons">
			<?php if ( ! empty( $args['add_button'] ) ) : ?>
			<button class="button button-secondary add-media-button"><?php echo esc_html( $args['add_button'] ); ?></button>
			<?php endif; ?>
			<?php if ( ! empty( $args['remove_button'] ) ) : ?>
			<button class="button button-secondary remove-media-button"><?php echo esc_html( $args['remove_button'] ); ?></button>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Prints out the file field.
	 *
	 * @param array $args Optional. Extra arguments used when outputting the field.
	 */
	public function file_field( $args = array() ) {

		$args = wp_parse_args(
			$args,
			array(
				'mime_type'     => '',
				'add_button'    => esc_html__( 'Choose file', 'flextension' ),
				'remove_button' => esc_html__( 'Remove file', 'flextension' ),
				'delete_file'   => false,
			)
		);

		$file_id = $args['value'];

		$file_link = '';
		if ( ! empty( $file_id ) ) {
			$file_url = wp_get_attachment_url( $file_id );
			if ( ! empty( $file_url ) ) {
				$file_link = '<a href="' . esc_url( $file_url ) . '" target="_blank">' . basename( $file_url ) . '</a>';
			} else {
				$file_id = '';
			}
		}

		echo sprintf(
			'<input type="hidden" class="file-id" name="%1$s" id="%2$s" value="%3$s" data-type="%4$s"%5$s>',
			esc_attr( $args['name'] ),
			$this->sanitize_id( $args['name'] ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			esc_attr( $file_id ),
			esc_attr( $args['mime_type'] ),
			flextension_get_attributes( $args['attributes'] ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		);

		echo '<div class="file-preview">' . $file_link . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
		?>

		<div class="flext-field-buttons">
			<button class="button button-secondary add-file-button"><?php echo esc_html( $args['add_button'] ); ?></button>
			<?php
			$attrs = array();
			if ( true === $args['delete_file'] ) {
				$attrs['data-delete-file']     = true;
				$attrs['data-confirm-message'] = esc_html__( 'Are you sure you want to permanently delete this file?', 'flextension' );
			}

			echo sprintf(
				'<button class="button button-secondary remove-file-button"%1$s>%2$s</button>',
				flextension_get_attributes( $attrs ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				esc_html( $args['remove_button'] )
			);
			?>
		</div>
		<?php

		$this->field_description( $args );
	}

	/**
	 * Prints out the text editor (TinyMCE) field.
	 *
	 * @param array $args Optional. Extra arguments used when outputting the field.
	 */
	public function text_editor_field( $args = array() ) {

		wp_editor(
			$args['value'],
			$this->sanitize_id( $args['name'] ),
			array(
				'wpautop'        => true,
				'media_buttons'  => false,
				'teeny'          => true,
				'default_editor' => 'html',
				'textarea_rows'  => 5,
			)
		);

		$this->field_description( $args );

	}

	/**
	 * Prints out the fieldset field.
	 *
	 * @param array $args Optional. Extra arguments used when outputting the field.
	 */
	public function fieldset_field( $args = array() ) {
		$args = wp_parse_args(
			$args,
			array(
				'fields' => array(),
			)
		);

		$fields = (array) $args['fields'];
		if ( ! empty( $fields ) ) {

			echo '<fieldset>';

			if ( isset( $args['legend'] ) && ! empty( $args['legend'] ) ) {
				$label = $args['legend'];
				if ( ! empty( $args['icon'] ) ) {
					$label = '<i class="' . esc_attr( $args['icon'] ) . '"></i> ' . $label;
				}
				echo '<legend>' . $this->esc_label( $label ) . '</legend>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}

			foreach ( $fields as $field ) {

				if ( ! isset( $field['name'] ) ) {
					continue;
				}

				if ( ! empty( $field['name'] ) ) {
					$field['name'] = $args['name'] . '[' . $field['name'] . ']';
				} else {
					$field['name'] = $args['name'];
				}

				if ( isset( $field['dependencies'] ) && ! empty( $field['dependencies'] ) ) {
					$count = count( $field['dependencies'] );
					for ( $i = 0; $i < $count; $i++ ) {
						$field['dependencies'][ $i ]['name'] = $args['name'] . '[' . $field['dependencies'][ $i ]['name'] . ']';
					}
				}

				$this->render_field( $field );

			}

			echo '</fieldset>';
		}

		$this->field_description( $args );
	}

	/**
	 * Prints out the custom fields.
	 *
	 * @param array $args Optional. Extra arguments used when outputting the field.
	 */
	public function custom_fields( $args = array() ) {
		$args = wp_parse_args(
			$args,
			array(
				'fields' => array(),
			)
		);

		if ( ! empty( $args['class'] ) ) {
			$classes[] = $args['class'];
		}

		$fields = (array) $args['fields'];

		if ( ! empty( $fields ) ) {

			foreach ( $fields as $key => $field ) {

				$field['name'] = $args['name'] . '[' . $key . ']';

				if ( isset( $field['dependencies'] ) && ! empty( $field['dependencies'] ) ) {
					$count = count( $field['dependencies'] );
					for ( $i = 0; $i < $count; $i++ ) {
						$field['dependencies'][ $i ]['name'] = $args['name'] . '[' . $field['dependencies'][ $i ]['name'] . ']';
					}
				}

				if ( ! empty( $args['add_button'] ) ) {
					echo '<a href="#" class="flext-delete-item-button"><i class="dashicons dashicons-no-alt"></i></a>';
				}

				$this->render_field( $field );

			}
		}

		$this->field_description( $args );

	}

	/**
	 * Prints out the template field.
	 *
	 * @param array $args Optional. Extra arguments used when outputting the field.
	 */
	public function template_field( $args = array() ) {

		$args = wp_parse_args(
			$args,
			array(
				'field' => array(),
			)
		);

		if ( ! empty( $args['field'] ) ) {

			$field = $args['field'];

			if ( ! empty( $field['name'] ) ) {
				$field['name'] = $args['name'] . '[' . $field['name'] . ']';
			} else {
				$field['name'] = $args['name'];
			}

			echo sprintf( '<template id="%1$s-template" class="flext-input-template">', $this->sanitize_id( $args['name'] ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

			$this->render_field( $field );

			echo '</template>';

		}

	}

	/**
	 * Prints out the fields list field.
	 *
	 * @param array $args Optional. Extra arguments used when outputting the field.
	 */
	public function fields_list_field( $args = array() ) {
		$args = wp_parse_args(
			$args,
			array(
				'template'         => array(),
				'value'            => array(),
				'add_button'       => esc_html__( 'Add New', 'flextension' ),
				'sortable'         => true,
				'show_placeholder' => false,
			)
		);

		$classes = array( 'flext-input-list' );
		if ( true === $args['sortable'] ) {
			$classes[] = 'flext-is-sortable';
		}

		if ( ! empty( $args['add_button'] ) ) {
			$classes[] = 'flext-is-addable';
		}

		if ( ! empty( $args['show_placeholder'] ) ) {
			$classes[] = 'flext-has-placeholder';
		}

		if ( ! empty( $args['class'] ) ) {
			$classes[] = $args['class'];
		}

		$default_template = array(
			'type' => 'text',
			'name' => '',
		);

		$template = $args['template'];

		if ( empty( $template ) ) {
			$template = $default_template;
		}

		?>
		<div class="flext-fields-list">

			<ul class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
			<?php

			$values = $args['value'];

			if ( ! empty( $values ) ) {

				$field = wp_parse_args(
					$template,
					$default_template
				);

				foreach ( $values as $key => $value ) {

					if ( is_array( $value ) ) {
						$field = array_replace_recursive( $field, $value );
					} else {
						$field['value'] = $value;
					}

					$field['name'] = $args['name'] . '[' . sanitize_key( $key ) . ']';

					if ( isset( $field['dependencies'] ) && ! empty( $field['dependencies'] ) ) {
						$count = count( $field['dependencies'] );
						for ( $i = 0; $i < $count; $i++ ) {
							$field['dependencies'][ $i ]['name'] = $args['name'] . '[' . $field['dependencies'][ $i ]['name'] . ']';
						}
					}

					echo '<li>';

					if ( ! empty( $args['add_button'] ) ) {
						echo '<a href="#" class="flext-delete-item-button"><i class="dashicons dashicons-no-alt"></i></a>';
					}

					$this->render_field( $field );

					echo '</li>';
				}
			}

			?>
			</ul>

			<?php

			if ( ! empty( $template ) ) {

				if ( ! empty( $template['name'] ) ) {
					$template['name'] = $args['name'] . '[' . $template['name'] . ']';
				} else {
					$template['name'] = $args['name'] . '[{{index}}]';
				}

				if ( isset( $template['dependencies'] ) && ! empty( $template['dependencies'] ) ) {
					$count = count( $template['dependencies'] );
					for ( $i = 0; $i < $count; $i++ ) {
						$template['dependencies'][ $i ]['name'] = $args['name'] . '[' . $template['dependencies'][ $i ]['name'] . ']';
					}
				}

				echo '<template id="flext-field-' . esc_attr( $this->sanitize_id( $args['name'] ) ) . '-template" class="flext-input-template">';

				$this->render_field( $template );

				echo '</template>';

				if ( ! empty( $args['add_button'] ) ) {
					echo sprintf( '<button id="flext-field-%1$s-add-button" class="button button-secondary flext-add-item-button">%2$s</button>', esc_attr( $this->sanitize_id( $args['name'] ) ), esc_html( $args['add_button'] ) );
				}
			}

			?>
		</div>
		<?php
		$this->field_description( $args );

	}

	/**
	 * Sanitizes a field value based on the field type.
	 *
	 * @param mixed $value          The value to sanitize.
	 * @param array $field          The field settings.
	 * @param mixed $original_value The original value.
	 * @return mixed The sanitized value.
	 */
	public function sanitize( $value, $field, $original_value = '' ) {

		if ( empty( $original_value ) ) {
			$original_value = $value;
		}

		$callback = $this->get_callback( $field );

		return is_callable( $callback ) ? call_user_func( $callback, $value, $field, $original_value ) : $value;
	}

	/**
	 * Returns a sanitize callback for a field.
	 *
	 * @param  array $field The field settings.
	 * @return callable The sanitize callback.
	 */
	private function get_callback( $field ) {
		// User-defined callback.
		if ( isset( $field['sanitize_callback'] ) && is_callable( $field['sanitize_callback'] ) ) {
			return $field['sanitize_callback'];
		}

		$callbacks = array(
			'checkbox'      => array( $this, 'sanitize_checkbox' ),
			'checkbox_list' => array( $this, 'sanitize_select' ),
			'color'         => array( $this, 'sanitize_color' ),
			'email'         => 'sanitize_email',
			'fields_list'   => array( $this, 'sanitize_fields_list' ),
			'file'          => array( $this, 'sanitize_number' ),
			'hidden'        => 'sanitize_text_field',
			'image'         => 'absint',
			'image_select'  => array( $this, 'sanitize_select' ),
			'number'        => array( $this, 'sanitize_number' ),
			'oembed'        => array( $this, 'sanitize_url' ),
			'radio'         => array( $this, 'sanitize_select' ),
			'range'         => array( $this, 'sanitize_number' ),
			'select'        => array( $this, 'sanitize_select' ),
			'single_image'  => 'absint',
			'text'          => 'sanitize_text_field',
			'text_list'     => array( $this, 'sanitize_text' ),
			'textarea'      => 'wp_kses_post',
			'url'           => array( $this, 'sanitize_url' ),
			'wysiwyg'       => 'wp_kses_post',
		);

		$type = $field['type'];

		return isset( $callbacks[ $type ] ) ? $callbacks[ $type ] : null;
	}

	/**
	 * Sanitizes checkbox value.
	 *
	 * @param string $value Checkbox value.
	 * @return bool The sanitized value.
	 */
	private function sanitize_checkbox( $value ) {
		return ! empty( $value );
	}

	/**
	 * Sanitizes numeric value.
	 *
	 * @param string $value The number value.
	 * @param array  $field The field settings.
	 * @return int The number value.
	 */
	private function sanitize_number( $value, $field ) {
		$value = is_numeric( $value ) ? $value : 0;
		if ( isset( $field['attributes'] ) ) {
			if ( ! empty( $field['attributes']['min'] ) && $value < intval( $field['attributes']['min'] ) ) {
				$value = intval( $field['attributes']['min'] );
			}

			if ( ! empty( $field['attributes']['max'] ) && $value > intval( $field['attributes']['max'] ) ) {
				$value = intval( $field['attributes']['max'] );
			}
		}
		return $value;
	}

	/**
	 * Sanitizes fields list values.
	 *
	 * @param string $values The fieldset values.
	 * @param array  $field  The field settings.
	 * @return int The sanitized values.
	 */
	private function sanitize_fields_list( $values, $field ) {

		if ( ! empty( $values ) ) {
			$template = wp_parse_args(
				$field['template'],
				array(
					'type' => 'text',
				)
			);

			foreach ( $values as $key => $value ) {
				$values[ $key ] = $this->sanitize( $value, $template );
			}
		}

		return $values;
	}

	/**
	 * Sanitizes color value.
	 *
	 * @param string $value The color value.
	 * @return string
	 */
	private function sanitize_color( $value ) {
		if ( false === strpos( $value, 'rgba' ) ) {
			return sanitize_hex_color( $value );
		}

		// rgba value.
		$red   = '';
		$green = '';
		$blue  = '';
		$alpha = '';
		sscanf( $value, 'rgba(%d,%d,%d,%f)', $red, $green, $blue, $alpha );

		return 'rgba(' . $red . ',' . $green . ',' . $blue . ',' . $alpha . ')';
	}

	/**
	 * Sanitizes value for a select field.
	 *
	 * @param  string|array $value The submitted value.
	 * @param  array        $field The field settings.
	 * @return string|array
	 */
	private function sanitize_select( $value, $field ) {
		$options = $field['options'];
		return is_array( $value ) ? array_intersect( $value, array_keys( $options ) ) : ( isset( $options[ $value ] ) ? $value : '' );
	}

	/**
	 * Sanitizes text field.
	 *
	 * @param  string|array $value The submitted value.
	 * @return string|array The sanitized value.
	 */
	private function sanitize_text( $value ) {
		return is_array( $value ) ? array_map( __METHOD__, $value ) : sanitize_text_field( $value );
	}

	/**
	 * Sanitizes URL field.
	 *
	 * @param  string $value The submitted value.
	 * @return string
	 */
	private function sanitize_url( $value ) {
		return esc_url_raw( $value );
	}

	/**
	 * Registers the settings controls scripts.
	 */
	public function register_scripts() {

		if ( ! wp_style_is( 'flextension-controls', 'registered' ) ) {
			wp_register_style( 'flextension-controls', FLEXTENSION_URL . 'assets/css/controls.css', array( 'wp-mediaelement' ), flextension_get_setting( 'version' ) );
			wp_style_add_data( 'flextension-controls', 'rtl', 'replace' );
		}

		if ( ! wp_script_is( 'flextension-controls', 'registered' ) ) {
			wp_register_script( 'flextension-controls', FLEXTENSION_URL . 'assets/js/controls.js', array( 'jquery', 'wp-color-picker', 'wp-api-fetch', 'wp-mediaelement' ), flextension_get_setting( 'version' ), true );
		}
	}

	/**
	 * Enqueues the settings controls scripts and stylesheets.
	 */
	public function admin_enqueue_scripts() {

		wp_enqueue_media();

		wp_enqueue_style( 'wp-color-picker' );

		wp_enqueue_style( 'flextension-controls' );

		wp_enqueue_script( 'flextension-controls' );

	}

}
