<?php
/**
 * Meta Box
 *
 * @package    Flextension
 * @subpackage Extensions/Meta_Box
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Retrieves the field value from the post meta.
 *
 * @param int    $post_id Post ID.
 * @param string $name    The meta name.
 * @param string $type    The field type.
 * @return mixed The meta value.
 */
function flextension_get_post_meta( $post_id, $name, $type = 'text' ) {

	if ( 'checkbox_list' === $type ) {

		return (array) get_post_meta( $post_id, $name );

	} else {

		if ( empty( $post_id ) ) {
			return '';
		}

		return get_post_meta( $post_id, $name, true );
	}
}
