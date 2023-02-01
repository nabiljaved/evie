<?php
/**
 * Reading Time
 *
 * @package    Flextension
 * @subpackage Modules/Reading_Time
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Returns the meta key for the post reading time.
 *
 * @return string The meta key for the post reading time.
 */
function flextension_reading_time_meta_key() {
	/**
	 * Filters the meta key for the post reading time.
	 *
	 * @param string The meta key for the post reading time.
	 */
	return apply_filters( 'flextension_reading_time_meta_key', '_flext_reading_time' );
}

/**
 * Returns the supported post types for the reading time.
 *
 * @return array An array of the supported post types for the reading time.
 */
function flextension_reading_time_post_types() {
	/**
	 * Filters the supported post types for the reading time.
	 *
	 * @param array The supported post types for the reading time.
	 */
	return apply_filters( 'flextension_reading_time_post_types', array( 'post' ) );
}

/**
 * Calculates the post reading time in minutes.
 *
 * @param int $post_id The post ID.
 */
function flextension_calculate_reading_time( $post_id = null ) {
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}
	$post_content = strip_shortcodes( get_post_field( 'post_content', $post_id ) );
	$text         = wp_strip_all_tags( $post_content );

	/*
	 * translators: If your word count is based on single characters (e.g. East Asian characters),
	 * enter 'characters_excluding_spaces' or 'characters_including_spaces'. Otherwise, enter 'words'.
	 * Do not translate into your own language.
	 */
	$count_type = _x( 'words', 'Word count type. Do not translate!' ); // phpcs:ignore WordPress.WP.I18n.MissingArgDomain
	if ( strpos( $count_type, 'characters' ) === 0 && preg_match( '/^utf\-?8$/i', get_option( 'blog_charset' ) ) ) {
		// Count number of characters.
		$word_count = mb_strlen( $text );
		$wpm        = 500; // Default number of characters per minute.
	} else {
		// Count number of words.
		$word_count = str_word_count( $text );
		$wpm        = 256; // Default number of words per minute.
	}

	/**
	 * Filters the number of words/characters per minute.
	 *
	 * @param int    $wpm        The number of words/characters per minute.
	 * @param string $count_type Whether to calculate the post reading time by 'words' or 'characters'.
	 */
	$wpm = apply_filters( 'flextension_reading_time_words_per_minute', $wpm, $count_type );

	$reading_time = intval( ceil( $word_count / $wpm ) );

	return $reading_time;
}

/**
 * Returns the post reading time in minutes.
 *
 * @param int $post_id The post ID.
 * @return int The post reading time in minutes.
 */
function flextension_get_reading_time( $post_id = null ) {
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}

	if ( ! in_array( get_post_type( $post_id ), flextension_reading_time_post_types(), true ) ) {
		return 0;
	}

	// Get existing post metadata.
	$reading_time = get_post_meta( $post_id, flextension_reading_time_meta_key(), true );

	// Calculate and save reading time, if there is no existing post metadata.
	if ( ! $reading_time ) {
		$reading_time = flextension_calculate_reading_time( $post_id );
		update_post_meta( $post_id, flextension_reading_time_meta_key(), $reading_time );
	}

	return $reading_time;
}

/**
 * Prints HTML content for the Reading Time.
 *
 * @param int|WP_Post $post      Optional. Post ID or WP_Post object. Default is global `$post`.
 * @param bool        $short     Optional. Display a short version of the reading time. Default false.
 * @param string      $css_class Optional. CSS class to use for Reading Time button. Default empty.
 */
function flextension_reading_time( $post = 0, $short = false, $css_class = '' ) {

	$post = get_post( $post );
	if ( ! $post ) {
		return;
	}

	if ( ! in_array( get_post_type( $post ), flextension_reading_time_post_types(), true ) ) {
		return;
	}

	$reading_time = flextension_get_reading_time( $post->ID );

	$label = '';

	if ( true === $short ) {
		$label = sprintf(
			/* translators: %s: Number of minutes. */
			esc_html__( '%s min read', 'flextension' ),
			number_format_i18n( $reading_time )
		);
	} else {
		$label = sprintf(
			/* translators: %s: Number of minutes. */
			esc_html__( '%s minutes to read', 'flextension' ),
			number_format_i18n( $reading_time )
		);
	}

	$classes = array( 'meta-reading-time' );

	if ( ! empty( $css_class ) ) {
		$classes[] = $css_class;
	}

	$button = sprintf(
		'<span class="%s"><i class="flext-ico-date"></i> %s</span>',
		esc_attr( implode( ' ', $classes ) ),
		esc_attr( $label ),
		esc_attr( $post->ID ),
		flextension_number_format( $reading_time )
	);

	/**
	 * Filters HTML content for the button.
	 *
	 * @param string $button HTML content for the views button.
	 */
	echo apply_filters( 'flextension_reading_time_button', $button ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Updates the Reading Time when saving the post.
 *
 * @param int $post_id Optional. The post ID.
 */
function flextension_reading_time_update( $post_id = 0 ) {
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}

	if ( ! in_array( get_post_type( $post_id ), flextension_reading_time_post_types(), true ) ) {
		return;
	}

	$reading_time = flextension_get_reading_time( $post_id );

	update_post_meta( $post_id, flextension_reading_time_meta_key(), $reading_time );
}

add_action( 'save_post', 'flextension_reading_time_update' );
