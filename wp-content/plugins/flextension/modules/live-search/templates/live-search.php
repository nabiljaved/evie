<?php
/**
 * Template part for displaying a Live Search.
 *
 * @package    Flextension
 * @subpackage Modules/Live_Search/Templates
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

?>
<div id="flext-live-search" class="flext-live-search">
	<form id="flext-live-search-form" class="flext-live-search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>" method="post">
		<div class="live-search-field-wrapper">
			<div class="live-search-field">
				<input type="text" name="s" id="flext-search-keyword" value="<?php the_search_query(); ?>" placeholder="<?php echo esc_html__( 'Start typing...', 'flextension' ); ?>" />
				<span class="flext-icon-button flext-loader flext-loader-xs"></span>
				<button class="flext-icon-button clear-search-button" aria-label="<?php echo esc_attr( esc_html__( 'Cancel', 'flextension' ) ); ?>">
					<i class="flext-ico-cancel"></i>
				</button>
			</div>
			<button class="close-search-button" type="button" aria-label="<?php echo esc_attr( esc_html__( 'Search', 'flextension' ) ); ?>">
				<i class="flext-ico-back"></i>
			</button>
		</div>
	</form>
</div><!-- #flext-live-search -->
