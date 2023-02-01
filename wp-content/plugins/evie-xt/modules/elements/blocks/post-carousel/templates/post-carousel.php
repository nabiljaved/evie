<?php
/**
 * Template part for displaying a Post Carousel block.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package    Evie_XT
 * @subpackage Modules/Elements/Blocks/Post_Carousel/Templates
 * @version    1.0
 *
 * @var array $attributes The attributes list for the block.
 */

// This template part requires the block attributes.
if ( ! $attributes ) {
	return;
}

$evie_carousel_attrs = array();

$evie_posts_args = evie_posts_args(
	array(
		'layout'        => 'carousel',
		'style'         => $attributes['postStyle'],
		'hover_effect'  => $attributes['hoverEffect'],
		'animation'     => '',
		'show_title'    => $attributes['displayTitle'],
		'show_category' => $attributes['displayCategory'],
		'show_author'   => $attributes['displayAuthor'],
		'show_date'     => $attributes['displayDate'],
		'show_buttons'  => $attributes['displayButtons'],
		'query_vars'    => $attributes['query_vars'],
		'post_class'    => array( 'flext-slide' ),
	)
);

$evie_carousel_attrs['class'] = evie_posts_class( $evie_posts_args );

if ( true === $attributes['navigation'] ) {
	$evie_carousel_attrs['data-navigation'] = $attributes['navigation'];
}

if ( true === $attributes['pagination'] ) {
	$evie_carousel_attrs['data-pagination'] = $attributes['pagination'];
}

if ( ! empty( $attributes['columns'] ) ) {
	$evie_carousel_attrs['data-slides-per-view'] = absint( $attributes['columns'] );
}

$evie_carousel_attrs['data-space-between'] = 30;

if ( true !== $attributes['isEditMode'] ) :
	?>

	<div class="post-carousel-header">
		<div class="post-carousel-content">
			<?php if ( ! empty( $attributes['title'] ) ) : ?>
			<h2 class="post-carousel-title"><?php evie_block_post_carousel_esc_content( $attributes['title'] ); ?></h2>
			<?php endif; ?>

			<?php if ( ! empty( $attributes['description'] ) ) : ?>
			<p class="post-carousel-description"><?php evie_block_post_carousel_esc_content( $attributes['description'] ); ?></p>
			<?php endif; ?>
		</div>

		<?php if ( true === $attributes['navigation'] ) : ?>
		<div class="post-carousel-navigation">
			<div class="flext-button-prev"></div>
			<div class="flext-button-next"></div>
		</div>
		<?php endif; ?>
	</div>

<?php endif; ?>

<div <?php evie_attributes( $evie_carousel_attrs ); ?>>

	<div class="posts-list flext-carousel-wrapper">

		<?php
		while ( $attributes['posts_query']->have_posts() ) {
			$attributes['posts_query']->the_post();
			evie_block_post_carousel_content( $evie_posts_args, $attributes, $attributes['posts_query'] );
		}
		?>

	</div>

</div>
