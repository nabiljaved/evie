<?php
/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Evie
 * @version 1.0.0
 */

if ( ! function_exists( 'evie_posted_on' ) ) {
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
		echo '<div class="posted-on">';
		echo '	<div class="author vcard">';
		if ( 'all' === $show_author || 'avatar' === $show_author || is_customize_preview() ) {
			echo sprintf(
				'<a href="%s">%s</a>',
				esc_url( $author_url ),
				get_avatar( get_the_author_meta( 'email', $author_id ), 34 )
			);
		}
		echo '		<span>';
		if ( 'all' === $show_author || 'name' === $show_author || is_customize_preview() ) {
			echo sprintf(
				'<a class="url fn" href="%s">%s</a>',
				esc_url( $author_url ),
				esc_html( get_the_author_meta( 'display_name', $author_id ) )
			);
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
		echo '	</div>';
		echo '</div>';
	}
}

if ( ! function_exists( 'evie_get_author_thumbnail' ) ) {
	/**
	 * Returns the featured image for the author.
	 *
	 * @param int $user_id The author's ID.
	 * @param int $size    Thumbnail size.
	 * @return string The author's featured image.
	 */
	function evie_get_author_thumbnail( $user_id = 0, $size = 96 ) {
		$image = '';
		if ( $user_id ) {
			$image = get_avatar( get_the_author_meta( 'email', $user_id ), $size );
		}
		return $image;
	}
}

if ( ! function_exists( 'evie_author_thumbnail' ) ) {
	/**
	 * Prints out the featured image for the author.
	 *
	 * @param int $user_id The author's ID.
	 * @param int $size    Thumbnail size.
	 */
	function evie_author_thumbnail( $user_id = 0, $size = 96 ) {
		echo evie_get_author_thumbnail( $user_id, $size ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}

if ( ! function_exists( 'evie_get_author_follow_button' ) ) {
	/**
	 * Returns HTML markup for the follow button.
	 *
	 * @param int|object $author The author ID or author object. Default 0.
	 * @return string The HTML markup for the follow button.
	 */
	function evie_get_author_follow_button( $author = 0 ) {
		$button = '';
		if ( function_exists( 'flextension_author_follow_button' ) ) {
			$author_id = 0;
			if ( is_object( $author ) && isset( $author->ID ) ) {
				$author_id = $author->ID;
			} else {
				$author_id = absint( $author );
			}

			$button = flextension_author_follow_button( $author_id );
		}
		return $button;
	}
}

if ( ! function_exists( 'evie_author_follow_button' ) ) {
	/**
	 * Prints out HTML markup for the follow button.
	 *
	 * @param int|object $author The author ID or author object. Default 0.
	 */
	function evie_author_follow_button( $author = 0 ) {
		echo evie_get_author_follow_button( $author ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}

if ( ! function_exists( 'evie_author_follow_numbers' ) ) {
	/**
	 * Return HTML markup for the links of followers and following.
	 *
	 * @param int|object $author The author ID or author object. Default 0.
	 * @return string HTML markup for the links of followers and following.
	 */
	function evie_author_follow_numbers( $author = 0 ) {
		$links = '';
		if ( function_exists( 'flextension_author_follow_numbers' ) ) {
			$author_id = 0;
			if ( is_object( $author ) && isset( $author->ID ) ) {
				$author_id = $author->ID;
			} else {
				$author_id = absint( $author );
			}
			$links = flextension_author_follow_numbers( $author_id );
		}
		return $links;
	}
}

if ( ! function_exists( 'evie_author_description' ) ) {
	/**
	 * Return HTML markup for the links of followers and following.
	 *
	 * @param int $author_id The author ID. Default 0.
	 * @return string HTML markup for the links of followers and following.
	 */
	function evie_author_description( $author_id = 0 ) {
		return get_the_author_meta( 'description', $author_id );
	}
}

if ( ! function_exists( 'evie_author_has_posts' ) ) {
	/**
	 * Returns whether a user has written any post.
	 *
	 * @param int          $user_id   User ID.
	 * @param array|string $post_type Single post type or array of post types to count the number of posts for. Default 'post'.
	 * @return bool Whether a user has written any post.
	 */
	function evie_author_has_posts( $user_id, $post_type = 'post' ) {
		return count_user_posts( $user_id, $post_type ) > 0;
	}
}

if ( ! function_exists( 'evie_post_meta_categories' ) ) {
	/**
	 * Displays a list of categories for the post.
	 *
	 * @param int|WP_Post $post      Optional. Post ID or WP_Post object. Default is global `$post`.
	 * @param string      $taxonomy  Optional. Category taxonomy. Default 'category'.
	 * @param string      $separator Optional. String to use between the terms. Default value: '/'.
	 */
	function evie_post_meta_categories( $post = 0, $taxonomy = 'category', $separator = '/' ) {
		$post = get_post( $post );
		if ( ! $post ) {
			return;
		}

		if ( evie_get_theme_setting( 'post_category', true ) || is_customize_preview() ) {
			$terms_list = get_the_term_list( $post, $taxonomy, '<span class="cat-links">', $separator, '</span>' );
			if ( ! is_wp_error( $terms_list ) ) {
				echo '<span class="meta-categories">' . $terms_list . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}
	}
}

if ( ! function_exists( 'evie_post_meta_category' ) ) {
	/**
	 * Displays single category for the post.
	 *
	 * @param int|WP_Post $post     Optional. Post ID or WP_Post object. Default is global `$post`.
	 * @param string      $taxonomy Optional. Category taxonomy. Default 'category'.
	 */
	function evie_post_meta_category( $post = 0, $taxonomy = 'category' ) {
		$category_link = '';

		$categories = get_the_terms( $post, $taxonomy );

		if ( ! empty( $categories ) ) {

			$category_names = array();

			foreach ( $categories as $category ) {
				$category_names[] = esc_html( $category->name );
			}

			if ( count( $category_names ) > 0 && isset( $categories[0] ) ) {
				$category_link = sprintf(
					'<a href="%s" title="%s" rel="category tag">%s</a>',
					esc_url( get_term_link( $categories[0]->term_id, $categories[0]->taxonomy ) ),
					esc_attr( implode( ', ', $category_names ) ),
					esc_html( $categories[0]->name )
				);
			}
		}

		if ( ! empty( $category_link ) ) {
			echo '<span class="meta-category">' . $category_link . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}
}

if ( ! function_exists( 'evie_post_meta_date' ) ) {
	/**
	 * Returns a nicely formatted string for the published date.
	 *
	 * @param int|WP_Post $post Optional. Post ID or WP_Post object. Default is global `$post`.
	 * @return string A nicely formatted string for the published date.
	 */
	function evie_post_meta_date( $post = 0 ) {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U', $post ) !== get_the_modified_time( 'U', $post ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf(
			$time_string,
			esc_attr( get_the_date( 'c', $post ) ),
			esc_html( get_the_date( '', $post ) ),
			esc_attr( get_the_modified_date( 'c', $post ) ),
			esc_html( get_the_modified_date( '', $post ) )
		);

		$posted_date = '<a href="' . esc_url( get_day_link( get_the_date( 'Y', $post ), get_the_date( 'm', $post ), get_the_date( 'd', $post ) ) ) . '" rel="bookmark">' . $time_string . '</a>';

		return '<span class="meta-date">' . $posted_date . '</span>';
	}
}

if ( ! function_exists( 'evie_post_meta_data' ) ) {
	/**
	 * Displays the post meta data.
	 *
	 * @param int|WP_Post $post Optional. Post ID or WP_Post object. Default is global `$post`.
	 */
	function evie_post_meta_data( $post = 0 ) {
		if ( function_exists( 'flextension_reading_time' ) ) {
			flextension_reading_time( $post, true );
		}
	}
}

if ( ! function_exists( 'evie_single_post_meta_data' ) ) {
	/**
	 * Displays the post meta data for the single post.
	 *
	 * @param int|WP_Post $post Optional. Post ID or WP_Post object. Default is global `$post`.
	 */
	function evie_single_post_meta_data( $post = 0 ) {
		if ( true === evie_get_theme_setting( 'post_date', true ) || is_customize_preview() ) {
			echo evie_post_meta_date( $post ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
		if ( function_exists( 'flextension_reading_time' ) ) {
			flextension_reading_time( $post );
		}
	}
}

if ( ! function_exists( 'evie_tags_list' ) ) {
	/**
	 * Displays a list of tags for the single post.
	 *
	 * @param int    $post_id  Optional. Post ID or WP_Post object. Default is global `$post`.
	 * @param string $taxonomy Optional. Taxonomy.
	 */
	function evie_tags_list( $post_id = 0, $taxonomy = 'post_tag' ) {
		if ( true === evie_get_theme_setting( 'post_tags', true ) || is_customize_preview() ) {
			if ( ! $post_id ) {
				$post_id = get_the_ID();
			}

			echo get_the_term_list( $post_id, $taxonomy, '<div class="tags-links"><div class="terms-list">', ' ', '</div></div>' );
		}
	}
}

if ( ! function_exists( 'evie_get_post_thumbnail' ) ) {
	/**
	 * Retrieves the post thumbnail.
	 *
	 * Wraps the post thumbnail in an anchor element on index views.
	 *
	 * @param int|WP_Post  $post Optional. Post ID or WP_Post object.  Default is global `$post`.
	 * @param string|array $size The post thumbnail size. Image size or array of width and height
	 *                           values (in that order). Default 'post-thumbnail'.
	 * @return string The post thumbnail.
	 */
	function evie_get_post_thumbnail( $post = 0, $size = 'post-thumbnail' ) {
		$post = get_post( $post );
		if ( ! $post ) {
			return '';
		}

		$thumbnail = get_the_post_thumbnail( $post, $size );

		if ( ! empty( $thumbnail ) ) {
			if ( ! is_singular( $post ) ) {
				$thumbnail = '<a href="' . esc_attr( get_permalink( $post ) ) . '" aria-hidden="true" tabindex="-1">' . $thumbnail . '</a>';
			}
			$thumbnail = '<div class="post-thumbnail">' . $thumbnail . '</div><!-- .post-thumbnail -->';
		}

		return $thumbnail;
	}
}

if ( ! function_exists( 'evie_post_thumbnail' ) ) {
	/**
	 * Displays an optional post thumbnail.
	 *
	 * Wraps the post thumbnail in an anchor element on index views, or a div
	 * element when on single views.
	 *
	 * @param int|WP_Post  $post Optional. Post ID or WP_Post object.  Default is global `$post`.
	 * @param string|array $size The post thumbnail size. Image size or array of width and height
	 *                           values (in that order). Default 'post-thumbnail'.
	 */
	function evie_post_thumbnail( $post = 0, $size = 'post-thumbnail' ) {
		echo evie_get_post_thumbnail( $post, $size ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}

if ( ! function_exists( 'evie_post_media' ) ) {
	/**
	 * Displays the media content for the post.
	 *
	 * @param int|WP_Post  $post       Optional. Post ID or WP_Post object.  Default is global `$post`.
	 * @param string|array $size       Optional. The post thumbnail size. Image size or array of width and height
	 *                                 values (in that order). Default 'post-thumbnail'.
	 * @param array        $args       Optional. Arguments to retrieve the featured media. See flextension_get_featured_media() for all.
	 * @param string       $class      Optional. The CSS class name of the post media wrapper.
	 * @param bool|string  $quick_view Optional. Whether to show a Quick View button. Accepts true, false, 'inside' and 'outside'.
	 */
	function evie_post_media( $post = 0, $size = 'post-thumbnail', $args = array(), $class = '', $quick_view = false ) {
		if ( ! $post ) {
			$post = get_the_ID();
		}

		if ( post_password_required( $post ) ) {
			return;
		}

		$media = '';

		$classes = array( 'entry-media' );

		if ( is_single( $post ) ) {
			$classes[] = 'single-entry-media';
		}

		if ( ! empty( $class ) ) {
			$classes = array_merge( $classes, evie_get_classes( $class ) );
		}

		if ( function_exists( 'flextension_get_featured_media' ) ) {

			$media = flextension_get_featured_media( $post, $size, $args );

		}

		/**
		 * Fires before the post media is rendered.
		 *
		 * @param int|WP_Post $post Optional. Post ID or WP_Post object.  Default is global `$post`.
		 */
		do_action( 'evie_before_post_media', $post );

		if ( empty( $media ) ) {
			$media = evie_get_post_thumbnail( $post, $size );
		}

		/**
		 * Filters the post media.
		 *
		 * @param string       $media The media content for the post.
		 * @param int|WP_Post  $post  Optional. Post ID or WP_Post object.  Default is global `$post`.
		 * @param string|array $size  The post thumbnail size. Image size or array of width and height
		 *                            values (in that order). Default 'post-thumbnail'.
		 * @param array        $args  Optional. Arguments to retrieve the featured media. See flextension_get_featured_media() for all.
		 * @param string       $class The CSS class name of the post media wrapper.
		 */
		$media = apply_filters( 'evie_post_media', $media, $post, $size, $args, $class );

		if ( ! empty( $media ) ) {

			if ( true === $quick_view ) {
				$quick_view = 'inside';
			}

			if ( 'inside' === $quick_view ) {
				$media .= evie_quick_view_button( $post );
			}

			echo '<div class="' . esc_attr( implode( ' ', $classes ) ) . '">' . $media . '</div><!-- .entry-media -->'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

			if ( 'outside' === $quick_view ) {
				echo evie_quick_view_button( $post ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}

		/**
		 * Fires after the post media is rendered.
		 *
		 * @param int|WP_Post $post Optional. Post ID or WP_Post object.  Default is global `$post`.
		 */
		do_action( 'evie_after_post_media', $post );

	}
}

if ( ! function_exists( 'evie_post_buttons' ) ) {
	/**
	 * Displays the buttons and actions for the post.
	 *
	 * @param int|WP_Post $post Optional. Post ID or WP_Post object. Default is global `$post`.
	 */
	function evie_post_buttons( $post = 0 ) {
		$post = get_post( $post );
		if ( ! $post ) {
			return '';
		}

		echo '<div class="entry-buttons">';

		$button_class = 'evie-button';

		if ( function_exists( 'flextension_post_views_button' ) ) {
			flextension_post_views_button( $post, $button_class );
		}

		if ( function_exists( 'flextension_post_likes_button' ) ) {
			flextension_post_likes_button( $post, $button_class );
		}

		evie_comments_button( 'evie-button' );

		if ( function_exists( 'flextension_share_buttons' ) ) {
			flextension_share_buttons( $post, $button_class );
		}

		echo '</div>';
	}
}

if ( ! function_exists( 'evie_single_post_buttons' ) ) {
	/**
	 * Displays the buttons and actions for the post.
	 *
	 * @param int|WP_Post $post Optional. Post ID or WP_Post object. Default is global `$post`.
	 */
	function evie_single_post_buttons( $post = 0 ) {
		if ( true === evie_get_theme_setting( 'post_buttons', false ) || is_customize_preview() ) {
			evie_post_buttons( $post );
		}
	}
}

if ( ! function_exists( 'evie_comments_button' ) ) {
	/**
	 * Displays a link to the comments popup window if comments_popup_script() is used, otherwise it displays a normal link to comments.
	 *
	 * @param string $css_class Optional. CSS class to use for the button. Default empty.
	 */
	function evie_comments_button( $css_class = '' ) {
		if ( ! post_password_required() && ( comments_open() || get_comments_number() ) && ( evie_get_theme_setting( 'post_comments', true ) || is_customize_preview() ) ) {
			$classes = array( 'post-comments' );
			if ( ! empty( $css_class ) ) {
				$classes[] = $css_class;
			}
			comments_popup_link( '<i class="evie-ico-comment"></i><span>0</span>', '<i class="evie-ico-comment"></i><span>1</span>', '<i class="evie-ico-comment"></i><span>%</span>', implode( ' ', $classes ) );
		}
	}
}

if ( ! function_exists( 'evie_quick_view_button' ) ) {
	/**
	 * Displays a Quick View button.
	 *
	 * @param int|WP_Post $post  Optional. Post ID or WP_Post object. Default is global `$post`.
	 * @param string      $class Additional CSS class for the  button.
	 * @param string      $mode  Request mode between 'rest' and 'legacy'. Default is 'rest'.
	 */
	function evie_quick_view_button( $post = 0, $class = '', $mode = '' ) {
		$button = '';
		if ( function_exists( 'flextension_quick_view_button' ) && true === evie_get_theme_setting( 'quick_view_enable', true ) ) {
			$button = flextension_quick_view_button( $post, $class, $mode );
		}
		return $button;
	}
}

if ( ! function_exists( 'evie_edit_link' ) ) {
	/**
	 * Prints an accessibility-friendly link to edit a post or page.
	 *
	 * This also gives us a little context about what exactly we're editing
	 * (post or page?) so that users understand a bit more where they are in terms
	 * of the template hierarchy and their content. Helpful when/if the single-page
	 * layout with multiple posts/pages shown gets confusing.
	 *
	 * @param int|WP_Post $post Optional. Post ID or WP_Post object.  Default is global `$post`.
	 */
	function evie_edit_link( $post = 0 ) {
		$post = get_post( $post );
		if ( ! $post ) {
			return;
		}

		edit_post_link(
			sprintf(
				'<span><i class="evie-ico-pencil"></i> %s</span><span class="screen-reader-text">"%s"</span>',
				esc_html__( 'Edit', 'evie' ),
				get_the_title( $post )
			),
			'<span class="edit-link">',
			'</span>',
			$post->ID,
			'post-edit-link'
		);
	}
}

if ( ! function_exists( 'evie_comment' ) ) {
	/**
	 * Outputs a comment in the HTML5 format.
	 *
	 * @see wp_list_comments()
	 *
	 * @param WP_Comment $comment Comment to display.
	 * @param array      $args    An array of arguments.
	 * @param int        $depth   Depth of the current comment.
	 */
	function evie_comment( $comment, $args, $depth ) {
		$short_ping = ( 'pingback' === $comment->comment_type || 'trackback' === $comment->comment_type ) && true === $args['short_ping'];
		?>
		<<?php echo ( 'div' === $args['style'] ) ? 'div' : 'li'; ?> id="comment-<?php comment_ID(); ?>" <?php comment_class(); ?>>
			<article id="div-comment-<?php comment_ID(); ?>" class="comment-body">

				<header class="comment-header">
					<div class="comment-author vcard">
						<?php
						if ( 0 !== $args['avatar_size'] ) {
							echo get_avatar( $comment, $args['avatar_size'] );
						}
						?>
						<?php
							printf(
								wp_kses(
									/* translators: %s: comment author link */
									__( '%s <span class="says">says:</span>', 'evie' ),
									array(
										'span' => array(
											'class' => array(),
										),
									)
								),
								sprintf( '<strong class="fn">%s</strong>', get_comment_author_link( $comment ) )
							);
						?>
						<?php if ( true !== $short_ping ) : ?>
						<div class="comment-meta">
							<a href="<?php echo esc_url( get_comment_link( $comment, $args ) ); ?>">
								<time datetime="<?php comment_time( 'c' ); ?>">
									<?php
										/* translators: 1: comment date, 2: comment time */
										printf( esc_html__( '%1$s at %2$s', 'evie' ), get_comment_date( '', $comment ), get_comment_time() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
									?>
								</time>
							</a>
						</div><!-- .comment-meta -->
						<?php endif; ?>
					</div><!-- .comment-author -->
				</header><!-- .comment-header -->

				<div class="comment-content">				
					<?php
					if ( true !== $short_ping ) {
						comment_text();
					}
					?>
				</div><!-- .comment-content -->

				<footer class="comment-footer">
					<?php
					comment_reply_link(
						array_merge(
							$args,
							array(
								'add_below' => 'div-comment',
								'depth'     => $depth,
								'max_depth' => $args['max_depth'],
								'before'    => '<div class="reply">',
								'after'     => '</div>',
							)
						)
					);
					?>
					<?php edit_comment_link( esc_html__( 'Edit', 'evie' ), '<span class="edit-link">', '</span>' ); ?>
				</footer>

				<?php if ( '0' === $comment->comment_approved ) : ?>
				<p class="comment-awaiting-moderation"><?php echo esc_html__( 'Your comment is awaiting moderation.', 'evie' ); ?></p>
				<?php endif; ?>

			</article><!-- .comment-body -->
		<?php
	}
}

if ( ! function_exists( 'evie_post_title' ) ) {
	/**
	 * Displays the post title.
	 *
	 * @param int|WP_Post $post  Optional. Post ID or WP_Post object. Default is global $post.
	 * @param string      $tag   Optional. The HTML tag for the post title. Default 'h3'.
	 * @param string      $class Optional. The CSS class for the post title. Default 'entry-title'.
	 */
	function evie_post_title( $post = 0, $tag = 'h3', $class = 'entry-title' ) {
		$post = get_post( $post );
		if ( ! $post ) {
			return;
		}

		$post_format = get_post_format( $post );

		if ( 'aside' === $post_format || 'status' === $post_format ) {
			return;
		}

		/**
		 * Filters the HTML tag for the post title.
		 *
		 * @param array $tag The current HTML tag for the post title, 'h1' for the single post, otherwise, 'h2'.
		 */
		$title_tag = apply_filters( 'evie_post_title_tag', is_single( $post ) ? 'h1' : $tag );

		if ( is_single( $post ) ) {
			$title = get_the_title( $post );
			echo '<' . $title_tag . ' class="' . esc_attr( $class ) . '">' . esc_html( $title ) . '</' . $title_tag . '>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		} else {
			$title   = get_the_title( $post );
			$tooltip = the_title_attribute(
				array(
					'echo' => false,
					'post' => $post,
				)
			);
			echo '<' . $title_tag . ' class="' . esc_attr( $class ) . '"><a href="' . esc_url( get_permalink( $post ) ) . '" title="' . $tooltip . '" rel="bookmark">' . esc_html( $title ) . '</a></' . $title_tag . '>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}
}

if ( ! function_exists( 'evie_category_thumbnail' ) ) {
	/**
	 * Returns the featured image for the term.
	 *
	 * @param string       $term_id The term ID.
	 * @param string|array $size    The post thumbnail size. Image size or array of width and height
	 *                              values (in that order). Default 'post-thumbnail'.
	 * @return string The term's featured image.
	 */
	function evie_category_thumbnail( $term_id = '', $size = '' ) {
		$image = '';
		if ( function_exists( 'flextension_get_term_thumbnail' ) ) {
			$image = flextension_get_term_thumbnail( $term_id, $size );
		}
		return $image;
	}
}

if ( ! function_exists( 'evie_archive_thumbnail' ) ) {
	/**
	 * Prints out the archive thumbnail.
	 */
	function evie_archive_thumbnail() {
		$thumbnail = '';
		if ( is_author() ) {
			$author_id = get_query_var( 'author' );
			$thumbnail = evie_get_author_thumbnail( $author_id, 150 );
			if ( ! empty( $thumbnail ) ) {
				$thumbnail .= evie_get_author_follow_button( $author_id );
			}
		} else {
			$thumbnail = evie_category_thumbnail( get_queried_object_id(), 'thumbnail' );
		}

		if ( ! empty( $thumbnail ) ) {
			echo '<div class="archive-image">' . $thumbnail . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}
}

if ( ! function_exists( 'evie_archive_title' ) ) {
	/**
	 * Prints out the archive title.
	 *
	 * @param string $type The archive title type.
	 * @param string $tag  Title tag.
	 */
	function evie_archive_title( $type = '', $tag = 'h1' ) {
		$title  = '';
		$prefix = '';

		if ( is_home() && is_front_page() ) {
				$prefix = esc_html( get_bloginfo( 'description', 'display' ) );
				$title  = esc_html( get_bloginfo( 'name' ) );
		} elseif ( is_day() ) {
				$prefix = esc_html__( 'Daily Archives', 'evie' );
				$title  = evie_get_date_archive_title( _x( 'F j, Y', 'daily archives date format', 'evie' ) );
		} elseif ( is_month() ) {
				$prefix = esc_html__( 'Monthly Archives', 'evie' );
				$title  = evie_get_date_archive_title( _x( 'F Y', 'monthly archives date format', 'evie' ) );
		} elseif ( is_year() ) {
				$prefix = esc_html__( 'Yearly Archives', 'evie' );
				$title  = evie_get_date_archive_title( _x( 'Y', 'yearly archives date format', 'evie' ) );
		} elseif ( is_author() ) {
				$label = evie_get_post_type_title();

				$prefix = sprintf(
					/* translators: %s: Post type label */
					esc_html__( '%s by ', 'evie' ),
					$label
				);

				$title = get_the_author_meta( 'display_name', get_query_var( 'author' ) );

		} elseif ( is_post_type_archive() ) {
			$title = post_type_archive_title( '', false );
		} else {
			$label = evie_get_post_type_title();

			$prefix = sprintf(
				/* translators: %s: Post type label */
				esc_html__( '%s in ', 'evie' ),
				$label
			);

			$title = single_cat_title( '', false );
		}

		$original_title = $title;

		/**
		 * Filters the archive title prefix.
		 *
		 * @param string $prefix Archive title prefix.
		 * @param string $type   The archive title type.
		 */
		$prefix = apply_filters( 'evie_archive_title_prefix', $prefix, $type );

		/**
		 * Filters the archive title.
		 *
		 * @param string $title Archive title to be displayed.
		 * @param string $type  The archive title type.
		 */
		$title = apply_filters( 'evie_archive_title', $title, $type );

		if ( ! empty( $prefix ) ) {
			echo '<span class="archive-title-prefix">' . $prefix . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		if ( ! empty( $title ) ) {
			echo '<' . $tag . ' class="page-title archive-title">' . $title . '</' . $tag . '>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}
}

if ( ! function_exists( 'evie_archive_description' ) ) {
	/**
	 * Prints out the archive description.
	 */
	function evie_archive_description() {
		$description = '';
		if ( is_author() ) {
			$author_id   = get_query_var( 'author' );
			$description = get_the_author_meta( 'description', $author_id );
			if ( function_exists( 'flextension_author_follow_numbers' ) ) {
				$description = flextension_author_follow_numbers( $author_id ) . $description;
			}
		} else {
			$description = term_description();
		}

		if ( ! empty( $description ) ) {
			echo '<div class="archive-description">' . $description . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}
}

if ( ! function_exists( 'evie_found_posts' ) ) {
	/**
	 * Prints out the number of posts.
	 */
	function evie_found_posts() {

		$found_posts = isset( $GLOBALS['wp_query']->found_posts ) ? (int) $GLOBALS['wp_query']->found_posts : 0;

		if ( $found_posts > 0 ) :
			$label = _n( 'Entry', 'Entries', $found_posts, 'evie' );
			if ( is_search() ) {
				$label = _n( 'Result', 'Results', $found_posts, 'evie' );
			}
			?>
			<div class="posts-count">
				<strong><?php echo esc_html( number_format_i18n( $found_posts ) ); ?></strong>
				<span>
					<?php
					/**
					 * Filters the label for the post count in the header.
					 *
					 * @param string $label       The label for the post count.
					 * @param int    $found_posts Number of posts.
					 */
					$label = apply_filters(
						'evie_header_archive_post_count_label',
						$label,
						$found_posts
					);

					echo esc_html( $label );
					?>
				</span>
			</div>
			<?php
		endif;
	}
}

if ( ! function_exists( 'evie_single_post_footer' ) ) {
	/**
	 * Prints out the post footer for the single post.
	 */
	function evie_single_post_footer() {
		$is_preview   = is_customize_preview();
		$show_tags    = ( true === get_theme_mod( 'blog_single_post_tags', true ) );
		$show_buttons = ( true === get_theme_mod( 'blog_single_post_buttons', false ) );
		$show_author  = ( true === get_theme_mod( 'blog_single_post_author', false ) );
		if ( $is_preview || $show_tags || $show_buttons || $show_author ) :
			?>
		<footer class="single-entry-footer">

			<?php if ( $is_preview || $show_tags || $show_buttons ) : ?>
			<div class="post-tags">
				<?php

				if ( $is_preview || $show_tags ) {
					evie_tags_list();
				}

				if ( $is_preview || $show_buttons ) {
					evie_single_post_buttons();
				}

				?>
			</div>
			<?php endif; ?>

			<?php
			if ( $is_preview || $show_author ) {
				evie_single_post_author();
			}
			?>

		</footer><!-- .single-entry-footer -->
			<?php
		endif;
	}
}

if ( ! function_exists( 'evie_single_post_author' ) ) {
	/**
	 * Displays the author box on the single post.
	 */
	function evie_single_post_author() {
		if ( evie_get_theme_setting( 'post_author', false ) || is_customize_preview() ) {
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

			/**
			 * Filters the arguments for the single post author template.
			 *
			 * @param array $args Parsed arguments.
			 */
			$args = apply_filters( 'evie_single_post_author_args', $args );

			get_template_part( 'template-parts/single/author', get_post_type(), $args );
		}
	}
}

if ( ! function_exists( 'evie_post_navigation' ) ) {
	/**
	 * Displays the post navigation.
	 */
	function evie_post_navigation() {
		if ( evie_get_theme_setting( 'post_navigation', true ) || is_customize_preview() ) {

			$prev_post = get_previous_post();

			$next_post = get_next_post();

			if ( empty( $prev_post ) && empty( $next_post ) ) {
				return;
			}

			$post_type = get_post_type_object( get_post_type() );

			$args = array(
				'archive_link'        => '',
				'archive_text'        => '',
				'prev_post_thumbnail' => '',
				'next_post_thumbnail' => '',
				'post_type'           => $post_type->labels->singular_name,
			);

			$archive_link = get_post_type_archive_link( $post_type->name );
			if ( false !== $archive_link ) {
				$args['archive_link'] = $archive_link;
				$args['archive_text'] = $post_type->labels->all_items;
			}

			if ( ! empty( $prev_post ) ) {
				$prev_post_thumbnail = get_the_post_thumbnail( $prev_post, 'evie-landscape' );
				if ( ! empty( $prev_post_thumbnail ) ) {
					$args['prev_post_thumbnail'] = '<div class="nav-thumbnail">' . $prev_post_thumbnail . '</div>';
				}
			}

			if ( ! empty( $next_post ) ) {
				$next_post_thumbnail = get_the_post_thumbnail( $next_post, 'evie-landscape' );
				if ( ! empty( $next_post_thumbnail ) ) {
					$args['next_post_thumbnail'] = '<div class="nav-thumbnail">' . $next_post_thumbnail . '</div>';
				}
			}

			get_template_part( 'template-parts/single/navigation', $post_type->name, $args );
		}
	}
}

if ( ! function_exists( 'evie_related_posts_carousel' ) ) {
	/**
	 * Renders the Related Posts carousel.
	 *
	 * @param array $attributes The attributes list for the block.
	 */
	function evie_related_posts_carousel( $attributes ) {

		if ( function_exists( 'evie_block_post_carousel_render' ) && ( evie_get_theme_setting( 'post_related', false ) || is_customize_preview() ) ) {

			$post_type = get_query_var( 'post_type' );

			if ( empty( $post_type ) ) {
				$post_type = get_post_type();
			}

			$query_vars = array(
				'post__not_in' => array( get_the_ID() ),
			);

			$taxonomy = 'post_tag';
			$tags     = get_the_tags();

			if ( ! empty( $tags ) ) {
				$tags = wp_list_pluck( $tags, 'slug' );
			} else {
				$taxonomy   = 'category';
				$categories = get_the_category();
				if ( ! empty( $categories ) ) {
					$tags = wp_list_pluck( $categories, 'slug' );
				}
			}

			$attributes = wp_parse_args(
				array(
					'className'  => 'related-posts',
					'query'      => array(),
					'query_vars' => $query_vars,
				),
				$attributes
			);

			$attributes['query'] = wp_parse_args(
				$attributes['query'],
				array(
					'postType'      => $post_type,
					'filterBy'      => 'tag',
					'taxonomy'      => $taxonomy,
					'terms'         => $tags,
					'filterAuthor'  => '',
					'authors'       => array(),
					'timeRange'     => '',
					'orderBy'       => 'date',
					'order'         => 'DESC',
					'numberOfItems' => 10,
				)
			);

			/**
			 * Filters the Related Posts carousel attributes.
			 *
			 * @param array $attributes The Related Posts carousel attributes.
			 * @param string $post_type Current post type.
			 */
			$attributes = apply_filters( 'evie_related_posts_attributes', $attributes, $post_type );

			$block = array(
				'blockName' => 'evie/post-carousel',
				'attrs'     => $attributes,
			);

			echo render_block( $block ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}
}

if ( ! function_exists( 'evie_related_posts' ) ) {
	/**
	 * Displays the related posts section.
	 */
	function evie_related_posts() {
		get_template_part( 'template-parts/single/related' );
	}
}


if ( ! function_exists( 'evie_posts_not_found' ) ) {
	/**
	 * Prints out the message when there are no posts to show.
	 */
	function evie_posts_not_found() {
		if ( 'following' === get_query_var( 'filter' ) && ! evie_user_has_following() ) {
			echo '<h4 class="page-title">' . esc_html__( 'You are not following any authors yet.', 'evie' ) . '</h4>';
		} else {
			echo '<h4 class="page-title">' . esc_html__( 'There are no posts to show right now.', 'evie' ) . '</h4>';
		}
	}
}
