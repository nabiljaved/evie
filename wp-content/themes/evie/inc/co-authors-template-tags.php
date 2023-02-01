<?php
/**
 * Additional functions to allow styling of the Co-Authors templates
 *
 * This file should be loaded only when the Co-Authors Plus plugin is active.
 *
 * @package Evie
 * @version 1.0.0
 */

/**
 * Prints HTML with meta information for the current post-date/time and author.
 *
 * @param int|WP_Post $post        Optional. Post ID or WP_Post object.  Default is global `$post`.
 * @param string      $show_author Optional. The option of the author. Accepts 'all', 'name', 'avatar' and 'hide'.
 * @param bool        $show_date   Optional. Whether to show the post date.
 */
function evie_posted_on( $post = 0, $show_author = '', $show_date = '' ) {
	$post = get_post( $post );
	if ( ! $post ) {
		return;
	}

	$prefix = evie_customizer_prefix();

	if ( '' === $show_author ) {
		$show_author = evie_get_theme_setting( 'author', 'all', $prefix );
	}

	if ( '' === $show_date ) {
		$show_date = evie_get_theme_setting( 'date', true, $prefix );
	}

	if ( true === $show_author ) {
		$show_author = 'all';
	} elseif ( false === $show_author ) {
		$show_author = 'hide';
	}

	if ( 'hide' === $show_author && ! $show_date && ! is_customize_preview() ) {
		return;
	}

	$author_id  = $post->post_author;
	$author_url = get_author_posts_url( $author_id );

	$coauthors    = get_coauthors( $post->ID );
	$multi_author = is_array( $coauthors ) && count( $coauthors ) > 1;

	$class = '';

	if ( true === $multi_author ) {
		$class = ' has-multi-author';
	}

	echo '<div class="posted-on' . esc_attr( $class ) . '">';
	echo '	<div class="author vcard">';

	if ( 'all' === $show_author || 'avatar' === $show_author || is_customize_preview() ) {
		if ( true === $multi_author ) {
			echo '<a href="' . esc_url( get_permalink( $post ) ) . '#post-authors">';
			$count = count( $coauthors );
			$max   = min( $count, 2 );
			for ( $i = 0; $i < $max; $i ++ ) {
				echo coauthors_get_avatar( $coauthors[ $i ], 34 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}

			if ( $count > $max ) {
				echo '<i>+</i>';
			}

			echo '</a>';

		} else {
			echo sprintf(
				'<a href="%s">%s</a>',
				esc_url( $author_url ),
				get_avatar( get_the_author_meta( 'email', $author_id ), 34 )
			);
		}
	}

	echo '		<span>';

	if ( 'all' === $show_author || 'name' === $show_author || is_customize_preview() ) {
		if ( true === $multi_author ) {
			echo sprintf(
				'<a class="url fn" href="%s#post-authors">%s</a>',
				esc_url( get_permalink( $post ) ),
				esc_html__( 'Multiple Authors', 'evie' )
			);
		} else {
			echo sprintf(
				'<a class="url fn" href="%s">%s</a>',
				esc_url( $author_url ),
				esc_html( get_the_author_meta( 'display_name', $author_id ) )
			);
		}
	}

	if ( $show_date || is_customize_preview() ) {

		echo '<a href="' . esc_url( get_permalink( $post ) ) . '" rel="bookmark" class="meta-date">';

		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U', $post ) !== get_the_modified_time( 'U', $post ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		echo sprintf(
			$time_string, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			esc_attr( get_the_date( 'c', $post ) ),
			esc_html( get_the_date( '', $post ) ),
			esc_attr( get_the_modified_date( 'c', $post ) ),
			esc_html( get_the_modified_date( '', $post ) )
		);

		echo '</a>';

	}

	echo '		</span>';
	echo '	</div><!-- .author -->';
	echo '</div><!-- .posted-on -->';
}

/**
 * Returns the featured image for the author.
 *
 * @param int|object $author The author ID or author object.
 * @param int        $size   Thumbnail size.
 * @return string The author's featured image.
 */
function evie_get_author_thumbnail( $author = 0, $size = 96 ) {
	$image = '';
	if ( is_object( $author ) && isset( $author->type ) ) {
		$image = coauthors_get_avatar( $author, $size );
	} else {
		$image = get_avatar( get_the_author_meta( 'email', $author ), $size );
	}
	return $image;
}

/**
 * Return HTML markup for the links of followers and following.
 *
 * @param int|object $author The author ID or author object.
 * @return string HTML markup for the links of followers and following.
 */
function evie_author_description( $author = 0 ) {
	$description = '';
	if ( is_object( $author ) && isset( $author->type ) ) {
		if ( ! empty( $author->description ) ) {
			$description = '<p class="flext-author-description">' . $author->description . '</p>';
		}
	} else {
		$description = get_the_author_meta( 'description', $author );
	}
	return $description;
}

/**
 * Returns whether a user has written any post.
 *
 * @param int          $user_id   User ID.
 * @param array|string $post_type Single post type or array of post types to count the number of posts for. Default 'post'.
 * @return bool Whether a user has written any post.
 */
function evie_author_has_posts( $user_id, $post_type = 'post' ) {
	/**
	 * Co-Authors Plus coudn't count author's posts in specific post type, then we need to do it manually.
	 */
	$posts = get_posts(
		array(
			'post_type'        => $post_type,
			'numberposts'      => 1,
			'suppress_filters' => false,
			'author_name'      => get_the_author_meta( 'user_nicename', $user_id ),
		)
	);
	return count( $posts ) > 0;
}

/**
 * Displays the post authors in the single post.
 */
function evie_single_post_author() {
	if ( evie_get_theme_setting( 'post_author', false ) || is_customize_preview() ) {
		$coauthors = get_coauthors( get_the_id() );
		if ( ! empty( $coauthors ) ) {
			echo '<div id="post-authors" class="post-authors">';
			foreach ( $coauthors as $author ) {

				$description = evie_author_description( $author );

				$args = array(
					'author'       => $author,
					'display_name' => $author->display_name,
					'posts_url'    => get_author_posts_url( $author->ID, $author->user_nicename ),
					'edit_link'    => '',
				);

				if ( 'guest-author' === $author->type ) {
					if ( current_user_can( 'edit_post', $author->ID ) ) {
						$args['edit_link'] = get_edit_post_link( $author->ID );
					}
				} else {
					$args['description'] = get_the_author_meta( 'description', $author->ID );

					if ( current_user_can( 'edit_users' ) || get_current_user_id() === $author->ID ) {
						$args['edit_link'] = admin_url( 'profile.php?user_id=' . $author->ID );
					}
				}

				get_template_part( 'template-parts/single/author', get_post_type(), $args );
			}
			echo '</div>';
		} else {
			$author_id = get_the_author_meta( 'ID' );

			$description = evie_author_description( $author_id );

			$args = array(
				'author'       => $author_id,
				'display_name' => get_the_author_meta( 'display_name', $author_id ),
				'posts_url'    => get_author_posts_url( $author_id ),
				'edit_link'    => '',
			);

			if ( current_user_can( 'edit_users' ) || get_current_user_id() === $author_id ) {
				$args['edit_link'] = admin_url( 'profile.php?user_id=' . $author_id );
			}

			get_template_part( 'template-parts/single/author', get_post_type(), $args );
		}
	}
}
