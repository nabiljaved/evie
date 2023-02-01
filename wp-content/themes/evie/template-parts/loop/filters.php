<?php
/**
 * Template part for displaying the filters for the list of posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Evie
 * @version 1.0.0
 *
 * @var array $args Optional. Additional arguments passed to the template. See evie_posts_filters() for all.
 */

?>
<div class="posts-filters">

	<?php

	if ( true === $args['show_filter'] ) {
		evie_filters();
	}

	if ( true === $args['show_sortby'] ) {
		evie_sortby();
	}

	?>

</div><!-- .posts-filters -->
