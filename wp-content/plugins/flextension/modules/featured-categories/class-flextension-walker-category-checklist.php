<?php
/**
 * Flextension_Walker_Category_Checklist class
 *
 * @package    Flextension
 * @subpackage Modules/Featured_Categories/Widgets
 * @version    1.0.0
 *
 * @since 1.1.3
 */

/**
 * Core walker class to output an unordered list of category checkbox input elements.
 *
 * @see Walker
 * @see wp_category_checklist()
 * @see wp_terms_checklist()
 *
 * @since 1.1.3
 */
class Flextension_Walker_Category_Checklist extends Walker {
	/**
	 * A type of tree.
	 *
	 * @var string
	 */
	public $tree_type = 'category';

	/**
	 * An array of database fields.
	 *
	 * @var array
	 */
	public $db_fields = array(
		'parent' => 'parent',
		'id'     => 'term_id',
	);

	/**
	 * Starts the list before the elements are added.
	 *
	 * @see Walker:start_lvl()
	 *
	 * @param string $output Used to append additional content (passed by reference).
	 * @param int    $depth  Depth of category. Used for tab indentation.
	 * @param array  $args   An array of arguments. @see wp_terms_checklist().
	 */
	public function start_lvl( &$output, $depth = 0, $args = array() ) {
		$indent  = str_repeat( "\t", $depth );
		$output .= "$indent<ul class='children'>\n";
	}

	/**
	 * Ends the list of after the elements are added.
	 *
	 * @see Walker::end_lvl()
	 *
	 * @param string $output Used to append additional content (passed by reference).
	 * @param int    $depth  Depth of category. Used for tab indentation.
	 * @param array  $args   An array of arguments. @see wp_terms_checklist().
	 */
	public function end_lvl( &$output, $depth = 0, $args = array() ) {
		$indent  = str_repeat( "\t", $depth );
		$output .= "$indent</ul>\n";
	}

	/**
	 * Start the element output.
	 *
	 * @see Walker::start_el()
	 *
	 * @param string  $output            Used to append additional content (passed by reference).
	 * @param WP_Term $data_object       The current term object.
	 * @param int     $depth             Depth of the term in reference to parents. Default 0.
	 * @param array   $args              An array of arguments. @see wp_terms_checklist().
	 * @param int     $current_object_id Optional. ID of the current term. Default 0.
	 */
	public function start_el( &$output, $data_object, $depth = 0, $args = array(), $current_object_id = 0 ) {
		// Restores the more descriptive, specific name for use within this method.
		$category = $data_object;

		if ( empty( $args['taxonomy'] ) ) {
			$taxonomy = 'category';
		} else {
			$taxonomy = $args['taxonomy'];
		}

		$name = 'tax_input[' . $taxonomy . ']';

		if ( ! empty( $args['checkbox_name'] ) ) {
			$name = $args['checkbox_name'];
		}

		$class = ! empty( $args['item_class'] ) ? ' class="' . esc_attr( $args['item_class'] ) . '"' : '';

		$args['selected_terms'] = ! empty( $args['selected_terms'] ) ? array_map( 'intval', $args['selected_terms'] ) : array();

		$is_selected = in_array( $category->term_id, $args['selected_terms'], true );

		$output .= "<li id=\"{$taxonomy}-{$category->term_id}\"$class>" .
			'<label>
				<input value="' . $category->term_id . '" type="checkbox" name="' . $name . '[]" id="in-' . $taxonomy . '-' . $category->term_id . '"' . checked( $is_selected, true, false ) . ' />' .
				esc_html( $category->name ) .
			'</label>';
	}

	/**
	 * Ends the element output, if needed.
	 *
	 * @see Walker::end_el()
	 *
	 * @param string  $output      Used to append additional content (passed by reference).
	 * @param WP_Term $data_object The current term object.
	 * @param int     $depth       Depth of the term in reference to parents. Default 0.
	 * @param array   $args        An array of arguments. @see wp_terms_checklist().
	 */
	public function end_el( &$output, $data_object, $depth = 0, $args = array() ) {
		$output .= "</li>\n";
	}
}
