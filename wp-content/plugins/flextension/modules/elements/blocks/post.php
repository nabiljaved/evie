<?php
/**
 * Post Block
 *
 * @package    Flextension
 * @subpackage Modules/Elements/Blocks
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Displays a post title.
 *
 * @since 1.0.8
 *
 * @param int|WP_Post $post  Optional. Post ID or WP_Post object. Default is global `$post`.
 * @param string      $tag   Optional. The HTML tag for the post title. Default 'h3'.
 * @param string      $class Optional. The CSS class for the post title. Default 'entry-title'.
 */
function flextension_post_title( $post = 0, $tag = 'h3', $class = 'entry-title' ) {
	$post = get_post( $post );
	if ( ! $post ) {
		return;
	}

	$post_format = get_post_format( $post );

	if ( 'aside' === $post_format || 'status' === $post_format ) {
		return;
	}

	$title   = get_the_title( $post );
	$tooltip = the_title_attribute(
		array(
			'echo' => false,
			'post' => $post,
		)
	);
	echo '<' . esc_html( $tag ) . ' class="' . esc_attr( $class ) . '"><a href="' . esc_url( get_permalink( $post ) ) . '" title="' . $tooltip . '" rel="bookmark">' . esc_html( $title ) . '</a></' . esc_html( $tag ) . '>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Displays a single category link.
 *
 * @since 1.0.8
 *
 * @param int|WP_Post $post     Optional. Post ID or WP_Post object. Default is global `$post`.
 * @param string      $taxonomy Optional. Taxonomy name. Default 'category'.
 */
function flextension_post_single_category( $post = 0, $taxonomy = 'category' ) {
	echo flextension_get_single_category( $post, $taxonomy ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Displays the media content for the post.
 *
 * @since 1.0.8
 *
 * @param int|WP_Post  $post Optional. Post ID or WP_Post object.  Default is global `$post`.
 * @param string|array $size Optional. The post thumbnail size. Image size or array of width and height
 *                           values (in that order). Default 'post-thumbnail'.
 * @param array        $args Optional. Arguments to retrieve the featured media. See flextension_get_featured_media() for all.
 */
function flextension_post_media( $post = 0, $size = 'post-thumbnail', $args = array() ) {
	if ( ! $post ) {
		$post = get_the_ID();
	}

	$media = '';
	if ( post_password_required( $post ) ) {
		return $media;
	}

	if ( function_exists( 'flextension_get_featured_media' ) ) {
		$media = flextension_get_featured_media( $post, $size, $args );
	}

	if ( empty( $media ) ) {
		$thumbnail = get_the_post_thumbnail( $post, $size );
		if ( ! empty( $thumbnail ) ) {
			$media = '<div class="post-thumbnail"><a href="' . esc_attr( get_permalink( $post ) ) . '" aria-hidden="true" tabindex="-1">' . $thumbnail . '</a></div><!-- .post-thumbnail -->';
		}
	}

	if ( ! empty( $media ) ) {
		echo '<div class="entry-media">' . $media . '</div><!-- .entry-media -->'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}

/**
 * Prints HTML with meta information for the current post-date/time and author.
 *
 * @param int|WP_Post $post        Optional. Post ID or WP_Post object.  Default is global `$post`.
 * @param bool        $show_date   Optional. Whether to show the post date. Default is true.
 * @param string      $show_author Optional. The option of the author. Default is false.
 */
function flextension_posted_on( $post = 0, $show_date = true, $show_author = false ) {
	if ( true !== $show_date && true !== $show_author ) {
		return;
	}

	$post = get_post( $post );
	if ( ! $post ) {
		return;
	}

	$author_id = $post->post_author;
	echo '<div class="posted-on">';
	if ( true === $show_author ) {
		echo '<span class="entry-author author vcard">';
		echo sprintf(
			'<a href="%1$s" title="%2$s">%3$s</a>',
			esc_url( get_author_posts_url( $author_id ) ),
			esc_attr( get_the_author_meta( 'display_name', $author_id ) ),
			get_avatar( get_the_author_meta( 'email', $author_id ), 24 )
		);
		echo '</span>';
	}

	if ( true === $show_date ) {

		echo '<a href="' . esc_url( get_permalink( $post ) ) . '" rel="bookmark">';

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

	echo '</div>';
}

/**
 * Prints HTML with meta information for the author.
 *
 * @since 1.0.9
 *
 * @param int|WP_Post $post Optional. Post ID or WP_Post object. Default is global `$post`.
 */
function flextension_post_author( $post = 0 ) {
	$post = get_post( $post );
	if ( ! $post ) {
		return;
	}

	$author_id  = $post->post_author;
	$author_url = get_author_posts_url( $author_id );
	echo '<div class="entry-author author vcard">';
	echo sprintf(
		'<a href="%1$s" title="%2$s">%3$s</a>',
		esc_url( $author_url ),
		esc_attr( get_the_author_meta( 'display_name', $author_id ) ),
		get_avatar( get_the_author_meta( 'email', $author_id ), 24 )
	);
	echo '</div>';
}

/**
 * Returns a nicely formatted string for the published date.
 *
 * @since 1.0.9
 *
 * @param int|WP_Post $post Optional. Post ID or WP_Post object. Default is global `$post`.
 */
function flextension_post_date( $post = 0 ) {
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

	echo '<span class="meta-date">' . $posted_date . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Displays the buttons and actions for the post.
 *
 * @since 1.0.8
 *
 * @param int|WP_Post $post Optional. Post ID or WP_Post object. Default is global `$post`.
 */
function flextension_post_buttons( $post = 0 ) {
	$post = get_post( $post );
	if ( ! $post ) {
		return '';
	}

	if ( function_exists( 'flextension_post_views_button' ) ) {
		flextension_post_views_button( $post );
	}

	if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
		comments_popup_link( '<i class="flext-ico-chat"></i><span>0</span>', '<i class="flext-ico-chat"></i><span>1</span>', '<i class="flext-ico-chat"></i><span>%</span>', 'post-comments' );
	}

	if ( function_exists( 'flextension_post_likes_button' ) ) {
		flextension_post_likes_button( $post );
	}

	if ( function_exists( 'flextension_share_buttons' ) ) {
		flextension_share_buttons( $post );
	}
}

/**
 * Displays the post content.
 *
 * @since 1.0.8
 *
 * @param array $attributes The attributes list for the block.
 */
function flextension_block_post_content( $attributes ) {
	$post_type = get_post_type();
	if ( 'post' === $post_type ) {
		$post_type = '';
	}

	$defaults = array(
		'displayTitle'    => true,
		'displayCategory' => true,
		'displayAuthor'   => true,
		'displayDate'     => true,
		'displayButtons'  => false,
		'postClass'       => '',
		'contentTemplate' => plugin_dir_path( __FILE__ ) . 'post/templates/post',
	);

	$attributes = wp_parse_args( $attributes, $defaults );

	flextension_get_template( $attributes['contentTemplate'], $post_type, $attributes );
}

/**
 * Generates WP query variables from the block query attribute values.
 *
 * @since 1.1.2
 *
 * @param array $queries The block query attribute values.
 * @return array An array of query variables.
 */
function flextension_block_get_query_vars( $queries = array() ) {
	$queries = wp_parse_args(
		$queries,
		array(
			'postType'      => '',
			'posts'         => array(),
			'taxonomy'      => '',
			'terms'         => array(),
			'author'        => '',
			'authors'       => array(),
			'timeRange'     => '',
			'orderBy'       => '',
			'order'         => '',
			'numberOfItems' => 10,
		)
	);

	$query_vars = array();

	if ( ! empty( $queries['postType'] ) ) {
		$query_vars['post_type'] = $queries['postType'];
	}

	if ( 'authors' === $queries['author'] && ! empty( $queries['authors'] ) ) {
		$query_vars['author__in'] = $queries['authors'];
	} elseif ( 'following' === $queries['author'] && function_exists( 'flextension_author_get_following' ) ) {
		$following = flextension_author_get_following();
		if ( empty( $following ) ) {
			$following = array( 0 );
		}
		$query_vars['author__in'] = $following;
	}

	if ( ! empty( $queries['posts'] ) ) {
		$query_vars['post__in'] = $queries['posts'];
		if ( empty( $queries['orderBy'] ) ) {
			$query_vars['orderby'] = 'post__in';
		}
	} elseif ( ! empty( $queries['taxonomy'] ) && ! empty( $queries['terms'] ) ) {
		$query_vars['tax_query'] = array(  // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
			array(
				'field'    => 'name',
				'taxonomy' => $queries['taxonomy'],
				'terms'    => $queries['terms'],
			),
		);
	}

	if ( empty( $queries['posts'] ) && ! empty( $queries['timeRange'] ) ) {
		switch ( $queries['timeRange'] ) {
			case 'daily':
				$query_vars['date_query'] = array(
					array(
						'after' => '24 hours ago',
					),
				);
				break;
			case '2days':
				$query_vars['date_query'] = array(
					array(
						'after' => '48 hours ago',
					),
				);
				break;
			case '3days':
				$query_vars['date_query'] = array(
					array(
						'after' => '72 hours ago',
					),
				);
				break;
			case 'weekly':
				$query_vars['date_query'] = array(
					array(
						'after' => '7 days ago',
					),
				);
				break;
			case 'monthly':
				$query_vars['date_query'] = array(
					array(
						'after' => '1 month ago',
					),
				);
				break;
		}
	}

	if ( ! empty( $queries['numberOfItems'] ) ) {
		$query_vars['posts_per_page'] = intval( $queries['numberOfItems'] );
	}

	if ( ! empty( $queries['orderBy'] ) ) {
		$query_vars['orderby'] = $queries['orderBy'];
	}

	if ( ! empty( $queries['order'] ) ) {
		$query_vars['order'] = $queries['order'];
	}

	return $query_vars;
}

/**
 * Returns an absolute URL for the 'See more' link.
 *
 * @since 1.0.8
 *
 * @param array $attributes Arguments passed to the template.
 * @return string An absolute URL for the 'See more' link.
 */
function flextension_block_get_more_link_url( $attributes = array() ) {

	$url = '';
	if ( ! empty( $attributes ) && ! empty( $attributes['link'] ) ) {
		if ( 'custom' === $attributes['link'] && ! empty( $attributes['linkURL'] ) ) {
			$url = esc_url( $attributes['linkURL'] );
		} elseif ( 'archive' === $attributes['link'] ) {
			$url = esc_url( get_post_type_archive_link( $attributes['query_vars']['post_type'] ) );
		} elseif ( 'more' === $attributes['link'] ) {
			$query_vars = $attributes['query_vars'];

			$url = esc_url( get_post_type_archive_link( $query_vars['post_type'] ) );

			$query_args = array();

			if ( ! empty( $attributes['query']['author'] ) ) {
				if ( 'authors' === $attributes['query']['author'] && count( $attributes['query']['authors'] ) === 1 ) {
					$url = get_author_posts_url( $attributes['query']['authors'][0] );
				} elseif ( 'following' === $attributes['query']['author'] ) {
					$query_args['filter'] = 'following';
				}
			}

			if ( ! empty( $query_vars['tax_query'] ) ) {
				foreach ( $query_vars['tax_query'] as $tax_query ) {

					$terms = $tax_query['terms'];

					if ( ! empty( $terms ) && ! is_array( $terms ) ) {
						$terms = explode( ',', $terms );
					}

					if ( 'slug' !== $tax_query['field'] ) {
						$term_args = array(
							'taxonomy' => $tax_query['taxonomy'],
							'fields'   => 'slugs',
						);

						if ( 'id' === $tax_query['field'] ) {
							$term_args['include'] = $tax_query['terms'];
						} else {
							$term_args[ $tax_query['field'] ] = $tax_query['terms'];
						}

						$terms = get_terms( $term_args );
					}

					if ( ! empty( $terms ) ) {
						$taxonomy = get_taxonomy( $tax_query['taxonomy'] );
						if ( ! empty( $taxonomy ) && ! empty( $taxonomy->query_var ) ) {
							$query_args[ $taxonomy->query_var ] = implode( ',', $terms );
						}
					}
				}
			}

			if ( ! empty( $query_vars['orderby'] ) ) {
				$query_args['orderby'] = $query_vars['orderby'];
			}

			if ( ! empty( $query_vars['order'] ) ) {
				$query_args['order'] = $query_vars['order'];
			}

			// Remove default order and orderby queries.
			if ( 'date' === $query_args['orderby'] && 'DESC' === strtoupper( $query_args['order'] ) ) {
				unset( $query_args['orderby'] );
				unset( $query_args['order'] );
			}

			if ( ! empty( $query_args ) ) {
				$url = add_query_arg( $query_args, $url );
			}
		}
	}

	return $url;
}
