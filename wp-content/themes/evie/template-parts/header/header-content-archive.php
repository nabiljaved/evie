<?php
/**
 * Template part for displaying a header content on archive pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Evie
 * @version 1.0.0
 *
 * @var array $args Optional. Additional arguments passed to the template.
 */

if ( is_search() ) {
	?>

	<div class="archive-details">

		<?php get_search_form(); ?>

	</div>

	<?php

} else {

	evie_archive_thumbnail();
	?>

	<div class="archive-details">

		<?php evie_archive_title(); ?>

		<?php evie_archive_description(); ?>

	</div>
	<?php
}

evie_found_posts();
