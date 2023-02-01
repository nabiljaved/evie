<?php
/**
 * Template part for displaying a post author box on single post
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Evie
 * @version 1.0.0
 *
 * @var array $args Optional. Additional arguments passed to the template. See evie_single_post_author() for all.
 */

?>

<div class="post-author">
	<div class="author-avatar">
		<a href="<?php echo esc_attr( $args['posts_url'] ); ?>">
			<?php evie_author_thumbnail( $args['author'], 80 ); ?>
			<?php evie_author_follow_button( $args['author'] ); ?>
		</a>
	</div>
	<div class="author-detail">
		<h4 class="author-title">
			<a href="<?php echo esc_attr( $args['posts_url'] ); ?>">
				<?php echo esc_html( $args['display_name'] ); ?>
			</a>
			<?php if ( ! empty( $args['edit_link'] ) ) : ?>
			<a class="edit-profile-link" href="<?php echo esc_attr( $args['edit_link'] ); ?>" target="_blank">
				<?php echo esc_html__( 'Edit Profile', 'evie' ); ?>
			</a>
			<?php endif; ?>
		</h4>		
		<?php echo '<div class="author-bio">' . evie_author_follow_numbers( $args['author'] ) . evie_author_description( $args['author'] ) . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</div>
</div>
