<?php
/**
 * Evie 3 - Authors page
 *
 * @package    Evie_XT
 * @subpackage Modules/Elements/Patterns
 * @version    1.0.0
 */

return array(
	'title'      => esc_html__( 'Evie 3 - Authors page', 'evie-xt' ),
	'categories' => array( 'evie-3', 'evie-pages' ),
	'content'    => '<!-- wp:columns {"align":"wide"} -->
					<div class="wp-block-columns alignwide"><!-- wp:column {"width":"66.66%"} -->
					<div class="wp-block-column" style="flex-basis:66.66%"><!-- wp:flextension/authors {"blockId":"0b95b5ba-1ac3-4512-bf39-e6fce558f510","displayCover":true,"imageSize":"large","displayFollowers":true,"displayPosts":true,"postType":"project","roles":["contributor","author"],"pagination":true,"numberOfItems":3,"className":"is-style-list"} /--></div>
					<!-- /wp:column -->
					
					<!-- wp:column {"width":"33.33%"} -->
					<div class="wp-block-column" style="flex-basis:33.33%"><!-- wp:flextension/categories {"layout":"grid","columns":1,"taxonomy":"project_category","terms":[]} /--></div>
					<!-- /wp:column --></div>
					<!-- /wp:columns -->',
);
