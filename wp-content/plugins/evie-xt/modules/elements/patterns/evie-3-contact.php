<?php
/**
 * Evie 3 - Contact page
 *
 * @package    Evie_XT
 * @subpackage Modules/Elements/Patterns
 * @version    1.0.0
 */

return array(
	'title'      => esc_html__( 'Evie 3 - Contact page', 'evie-xt' ),
	'categories' => array( 'evie-3', 'evie-pages' ),
	'content'    => '<!-- wp:columns {"align":"wide"} -->
					<div class="wp-block-columns alignwide"><!-- wp:column {"width":"66.66%"} -->
					<div class="wp-block-column" style="flex-basis:66.66%">' . evie_block_pattern_contact_form_template() . '</div>
					<!-- /wp:column -->
					
					<!-- wp:column {"width":"33.33%"} -->
					<div class="wp-block-column" style="flex-basis:33.33%"><!-- wp:paragraph {"fontSize":"large","flextAnimation":"fade-right","flextAnimationDelay":0.125} -->
					<p class="has-large-font-size flext-has-animation flext-animation-fade-right flext-animation-delay-125 flext-animation-once">We are here to serve! How can we help you?</p>
					<!-- /wp:paragraph -->
					
					<!-- wp:paragraph {"textColor":"grey","flextAnimation":"fade-right","flextAnimationDelay":0.25} -->
					<p class="has-grey-color has-text-color flext-has-animation flext-animation-fade-right flext-animation-delay-250 flext-animation-once">Use the form to get in touch. Our office hours are Monday through Friday, 9 am to 5 pm Central Daylight Time.</p>
					<!-- /wp:paragraph -->
					
					<!-- wp:paragraph -->
					<p></p>
					<!-- /wp:paragraph -->
					
					<!-- wp:social-links -->
					<ul class="wp-block-social-links"><!-- wp:social-link {"url":"https://www.instagram.com/envato/","service":"instagram","flextAnimation":"fade-up","flextAnimationDelay":0.25} /-->
					
					<!-- wp:social-link {"url":"https://twitter.com/envato","service":"twitter","flextAnimation":"fade-up","flextAnimationDelay":0.375} /-->
					
					<!-- wp:social-link {"url":"https://www.youtube.com/envato","service":"youtube","flextAnimation":"fade-up","flextAnimationDelay":0.5} /-->
					
					<!-- wp:social-link {"url":"https://www.linkedin.com/company/envato/","service":"linkedin","flextAnimation":"fade-up","flextAnimationDelay":0.625} /--></ul>
					<!-- /wp:social-links --></div>
					<!-- /wp:column --></div>
					<!-- /wp:columns -->',
);
