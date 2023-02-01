<?php
/**
 * The template for displaying a search form.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package Evie
 * @version 1.0.0
 */

?>
<form method="get" class="search-form" action="<?php echo esc_attr( home_url( '/' ) ); ?>">
	<input type="search" name="s" placeholder="<?php echo esc_attr__( 'Search &hellip;', 'evie' ); ?>" value="<?php the_search_query(); ?>" class="keyword" />
	<button type="submit" class="evie-button evie-i" aria-label="<?php echo esc_attr( esc_html__( 'Search', 'evie' ) ); ?>">
		<i class="evie-ico-search"></i>
	</button>
</form>
