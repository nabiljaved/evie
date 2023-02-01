<?php
/**
 * Template part for displaying a message that posts cannot be found
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Evie
 * @version 1.0.0
 */

?>
<div class="no-results not-found alignwide">

	<?php
	if ( is_search() ) :
		?>
		<h3><?php echo esc_html__( 'Sorry, but nothing matched your search terms.', 'evie' ); ?></h3>
		<p><?php echo esc_html__( 'Please try again with some different keywords.', 'evie' ); ?></p>

	<?php elseif ( is_home() || is_archive() ) : ?>

		<?php evie_posts_not_found(); ?>

	<?php else : ?>

		<p><?php echo esc_html__( 'It seems we can’t find what you’re looking for. Perhaps searching can help.', 'evie' ); ?></p>

		<?php

		get_search_form();

	endif;
	?>

</div>
