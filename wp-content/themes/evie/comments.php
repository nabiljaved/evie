<?php
/**
 * The template for displaying comments
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Evie
 * @version 1.0.0
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-section">

	<button class="evie-button evie-unelevated toggle-comments" aria-label="<?php echo esc_attr( esc_html__( 'Toggle comments', 'evie' ) ); ?>">
		<span>
		<?php

		echo sprintf(
			/* translators: 1: Number of comments */
			esc_html__( 'Comments (%1$s)', 'evie' ),
			esc_html( number_format_i18n( get_comments_number() ) )
		);

		?>
		</span>
		<i class="evie-ico-down"></i>
	</button>

	<div class="evie-container">
	<?php if ( have_comments() ) : ?>

		<ol class="comment-list">
			<?php
				wp_list_comments();
			?>
		</ol><!-- .comment-list -->

		<?php

		/**
		 * Filters the comments navigation arguments.
		 *
		 * @param array $args Array of comments navigation arguments.
		 */
		the_comments_navigation(
			apply_filters(
				'evie_comments_navigation_args',
				array(
					'prev_text' => '<i class="evie-ico-left"></i><span>' . esc_html__( 'Older', 'evie' ) . '</span>',
					'next_text' => '<span>' . esc_html__( 'Newer', 'evie' ) . '</span><i class="evie-ico-right"></i>',
				)
			)
		);

	endif;

	// If comments are closed and there are comments, let's leave a little note, shall we?
	if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
		?>
		<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'evie' ); ?></p>
		<?php
	endif;

	comment_form();
	?>
	</div>

</div><!-- #comments -->
