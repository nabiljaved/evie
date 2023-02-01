<?php
/**
 * Product Variation Swatches
 *
 * Adds Color, Image and Button variation swatches for the product attributes.
 *
 * @package    Evie_XT
 * @subpackage Modules/WooCommerce
 * @version    1.0.0
 */

/**
 * Returns custom product's attribute types
 *
 * @return array An array of the product attribute types
 */
function evie_wc_get_attribute_types() {
	/**
	 * Filters the product attribute types.
	 *
	 * @param array $types An array of the product attribute types.
	 */
	return apply_filters(
		'evie_wc_attribute_types',
		array(
			'color'  => esc_html__( 'Color', 'evie-xt' ),
			'image'  => esc_html__( 'Image', 'evie-xt' ),
			'button' => esc_html__( 'Button', 'evie-xt' ),
		)
	);
}

/**
 * Returns setting fields for the product's attribute.
 *
 * @param string $type The product attribute type.
 * @return array An array of the setting fields.
 */
function evie_wc_attribute_setting_fields( $type = '' ) {

	$fields = array(
		'color' => array(
			array(
				'name'          => 'flext_pa_color',
				'label'         => esc_html__( 'Color', 'evie-xt' ),
				'type'          => 'color',
				'wrapper_class' => 'form-field',
			),
		),
		'image' => array(
			array(
				'name'          => 'flext_pa_image',
				'label'         => esc_html__( 'Image', 'evie-xt' ),
				'type'          => 'image',
				'wrapper_class' => 'form-field',
			),
		),
	);

	/**
	 * Filters the setting fields for the product's attribute.
	 *
	 * @param array $fields An array of the setting fields.
	 */
	$fields = apply_filters( 'evie_wc_attribute_setting_fields', $fields );

	if ( ! empty( $type ) ) {
		return isset( $fields[ $type ] ) ? $fields[ $type ] : null;
	} else {
		return $fields;
	}
}

/**
 * Print select for product variations
 *
 * @param object               $attribute_taxonomy Attribute taxonomy object.
 * @param integer              $i                  Row index value.
 * @param WC_Product_Attribute $attribute          Product attribute.
 */
function evie_wc_product_attributes( $attribute_taxonomy, $i, $attribute ) {

	if ( in_array( $attribute_taxonomy->attribute_type, array_keys( evie_wc_get_attribute_types() ), true ) ) {

		?>
		<select multiple="multiple" data-placeholder="<?php esc_attr_e( 'Select terms', 'evie-xt' ); ?>" class="multiselect attribute_values wc-enhanced-select" name="attribute_values[<?php echo esc_attr( $i ); ?>][]">
			<?php
			$args      = array(
				'taxonomy'   => $attribute->get_taxonomy(),
				'orderby'    => ! empty( $attribute_taxonomy->attribute_orderby ) ? $attribute_taxonomy->attribute_orderby : 'name',
				'hide_empty' => 0,
			);
			$all_terms = get_terms( $args );
			if ( is_wp_error( $all_terms ) ) {
				$all_terms = array();
			}

			if ( ! empty( $all_terms ) ) {
				foreach ( $all_terms as $term ) {
					$options = $attribute->get_options();
					$options = ! empty( $options ) ? $options : array();
					echo '<option value="' . esc_attr( $term->term_id ) . '"' . wc_selected( $term->term_id, $options ) . '>' . esc_html( $term->name ) . '</option>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				}
			}
			?>
		</select>
		<button class="button plus select_all_attributes"><?php esc_html_e( 'Select all', 'evie-xt' ); ?></button>
		<button class="button minus select_no_attributes"><?php esc_html_e( 'Select none', 'evie-xt' ); ?></button>
		<button class="button fr plus add_new_attribute"><?php esc_html_e( 'Add new', 'evie-xt' ); ?></button>
		<?php
	}
}

/**
 * Adds custom product attribute types.
 *
 * @param array $types Product attribute types.
 * @return array New product attribute types
 */
function evie_wc_product_attribute_types( $types = array() ) {
	return array_merge(
		$types,
		evie_wc_get_attribute_types()
	);
}

/**
 * Retrieve a product attribute type by the attribute name.
 *
 * @param string $name Attribute name.
 * @return string Product attribute type.
 */
function evie_wc_get_attribute_type( $name ) {
	$type = 'select';

	$attribute_taxonomies = wc_get_attribute_taxonomies();
	if ( ! empty( $attribute_taxonomies ) ) {
		foreach ( $attribute_taxonomies as $tax ) {
			if ( wc_attribute_taxonomy_name( $tax->attribute_name ) === $name ) {
				$type = $tax->attribute_type;
			};
		}
	}

	return $type;
}

/**
 * Retrieves an attribute meta value.
 *
 * @param string $term_id The term taxonomy ID.
 * @param string $type    Attribute type.
 * @return string Attribute meta value.
 */
function evie_wc_get_attribute_meta_value( $term_id, $type ) {
	return get_term_meta( $term_id, 'product_attribute_' . $type, true );
}

/**
 * Returns HTML content for the attribute option.
 *
 * @param WP_Term $term     The attribute term.
 * @param string  $type     The attribute type.
 * @param bool    $selected Whether the option is selected.
 * @return string HTML content for the attribute option.
 */
function evie_wc_get_attribute_option( $term, $type, $selected = false ) {
	$option = '';
	switch ( $type ) {
		case 'color':
			$button_attrs          = array();
			$button_attrs['class'] = 'wc-attribute-term attribute-type-color';
			if ( true === $selected ) {
				$button_attrs['class'] .= ' wc-option-selected';
			}

			$value = evie_wc_get_attribute_meta_value( $term->term_id, $type );
			$color = sanitize_hex_color( $value );
			if ( ! empty( $color ) ) {
				$button_attrs['style'] = 'background-color:' . esc_attr( $color ) . ';';
			}

			$option = sprintf(
				'<span%1$s data-value="%2$s" data-title="%3$s" role="radio"></span>',
				evie_attributes( $button_attrs, true, false ),
				esc_attr( $term->slug ),
				esc_html( $term->name )
			);

			break;
		case 'image':
			$button_classes = array( 'wc-attribute-term', 'attribute-type-image' );
			if ( true === $selected ) {
				$button_classes[] = 'wc-option-selected';
			}

			$value = evie_wc_get_attribute_meta_value( $term->term_id, $type );

			$option = sprintf(
				'<span class="%1$s" data-value="%2$s" data-title="%3$s" role="radio">%4$s</span>',
				esc_attr( implode( ' ', $button_classes ) ),
				esc_attr( $term->slug ),
				esc_html( $term->name ),
				wp_get_attachment_image( absint( $value ), array( 40, 40 ) )
			);
			break;
		default:
			$button_attrs          = array();
			$button_attrs['class'] = 'wc-attribute-term attribute-type-button';
			if ( true === $selected ) {
				$button_attrs['class'] .= ' wc-option-selected';
			}

			$option = sprintf(
				'<span%1$s data-value="%2$s" role="radio">%3$s</span>',
				evie_attributes( $button_attrs, true, false ),
				esc_attr( $term->slug ),
				esc_html( $term->name )
			);
			break;
	}

	/**
	 * Filters HTML content for the attribute option.
	 *
	 * @param string  $option HTML content for the attribute option.
	 * @param WP_Term $term   The attribute term.
	 * @param string  $type   The attribute type.
	 */
	return apply_filters( 'evie_wc_attribute_option', $option, $term, $type );
}

/**
 * Sets the dropdown class based on the attribute type.
 *
 * @param array $args The dropdown variation attribute options.
 */
function evie_wc_dropdown_variation_attribute_options_args( $args = array() ) {
	if ( ! empty( $args['attribute'] ) ) {
		$type = evie_wc_get_attribute_type( $args['attribute'] );
		if ( in_array( $type, array_keys( evie_wc_get_attribute_types() ), true ) ) {
			$args['class'] = 'product-attribute-placeholder product-attribute-type-' . $type;
		}
	}

	return $args;
}

add_filter( 'woocommerce_dropdown_variation_attribute_options_args', 'evie_wc_dropdown_variation_attribute_options_args' );

/**
 * Appends custom variation swatches after default dropdown options.
 *
 * @param string $html HTML content for the dropdown options.
 * @param array  $args The dropdown variation attribute options.
 * @return string New HTML content for the dropdown options.
 */
function evie_wc_dropdown_variation_attribute_options_html( $html = '', $args = array() ) {
	if ( ! empty( $args['attribute'] ) ) {

		$attribute = $args['attribute'];
		$type      = evie_wc_get_attribute_type( $attribute );

		if ( in_array( $type, array_keys( evie_wc_get_attribute_types() ), true ) ) {
			$product = $args['product'];
			$options = $args['options'];

			if ( empty( $options ) && ! empty( $product ) ) {
				$attributes = $product->get_variation_attributes();
				$options    = $attributes[ $attribute ];
			}

			$output = '';
			if ( ! empty( $options ) ) {
				if ( $product && taxonomy_exists( $attribute ) ) {
					// Get terms if this is a taxonomy - ordered. We need the names too.
					$terms = wc_get_product_terms(
						$product->get_id(),
						$attribute,
						array(
							'fields' => 'all',
						)
					);

					$output = '<ul class="wc-variation-swatches wc-variation-attribute-type-' . esc_attr( $type ) . '">';

					foreach ( $terms as $term ) {
						if ( in_array( $term->slug, $options, true ) ) {

							$output .= '<li>' . evie_wc_get_attribute_option( $term, $type, ( sanitize_title( $args['selected'] ) === $term->slug ) ) . '</li>';

						}
					}

					$output .= '</ul>';
				}

				$html .= $output;
			}
		}
	}

	return $html;
}

add_filter( 'woocommerce_dropdown_variation_attribute_options_html', 'evie_wc_dropdown_variation_attribute_options_html', 10, 2 );

/**
 * Enqueues the scripts and stylesheets.
 */
function evie_wc_product_attributes_enqueue_scripts() {

	wp_enqueue_style( 'evie-wc-variation-swatche', plugins_url( 'css/variation-swatches.css', __FILE__ ), array(), EVIE_XT_VERSION );
	wp_style_add_data( 'evie-wc-variation-swatche', 'rtl', 'replace' );

	wp_enqueue_script( 'evie-wc-variation-swatches', plugins_url( 'js/variation-swatches.js', __FILE__ ), array( 'jquery', 'flextension' ), EVIE_XT_VERSION, true );

}

add_action( 'wp_enqueue_scripts', 'evie_wc_product_attributes_enqueue_scripts' );
