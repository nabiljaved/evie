<?php
/**
 * Share Buttons
 *
 * @package    Flextension
 * @subpackage Modules/Share_Buttons
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Returns share links for the post
 *
 * @return array An array of the share links.
 */
function flextension_share_links() {
	$share_links = array(
		'digg'      => array(
			'icon'  => 'flext-ico-digg',
			'title' => esc_html__( 'Digg', 'flextension' ),
			'link'  => 'http://digg.com/submit?phase=2&url={url}',
		),
		'facebook'  => array(
			'icon'  => 'flext-ico-facebook',
			'title' => esc_html__( 'Facebook', 'flextension' ),
			'link'  => 'https://www.facebook.com/sharer/sharer.php?u={url}',
		),
		'line'      => array(
			'icon'  => 'flext-ico-line',
			'title' => esc_html__( 'Line', 'flextension' ),
			'link'  => 'https://social-plugins.line.me/lineit/share?url={url}',
		),
		'linkedin'  => array(
			'icon'  => 'flext-ico-linkedin',
			'title' => esc_html__( 'Linkedin', 'flextension' ),
			'link'  => 'https://www.linkedin.com/shareArticle?mini=true&url={url}&title={text}',
		),
		'mail'      => array(
			'icon'  => 'flext-ico-mail',
			'title' => esc_html__( 'Mail', 'flextension' ),
			'link'  => 'mailto:?subject={text}&body={url}',
		),
		'pinterest' => array(
			'icon'  => 'flext-ico-pinterest',
			'title' => esc_html__( 'Pinterest', 'flextension' ),
			'link'  => 'https://pinterest.com/pin/create/button/?url={url}&description={text}&media={media}',
		),
		'reddit'    => array(
			'icon'  => 'flext-ico-reddit',
			'title' => esc_html__( 'Reddit', 'flextension' ),
			'link'  => 'https://www.reddit.com/submit?url={url}',
		),
		'skype'     => array(
			'icon'  => 'flext-ico-skype',
			'title' => esc_html__( 'Skype', 'flextension' ),
			'link'  => 'https://web.skype.com/share?url={url}',
		),
		'telegram'  => array(
			'icon'  => 'flext-ico-telegram',
			'title' => esc_html__( 'Telegram', 'flextension' ),
			'link'  => 'https://t.me/share/url?url={url}&text={text}',
		),
		'tumblr'    => array(
			'icon'  => 'flext-ico-tumblr',
			'title' => esc_html__( 'Tumblr', 'flextension' ),
			'link'  => 'https://tumblr.com/widgets/share/tool?canonicalUrl={url}',
		),
		'twitter'   => array(
			'icon'  => 'flext-ico-twitter',
			'title' => esc_html__( 'Twitter', 'flextension' ),
			'link'  => 'https://twitter.com/intent/tweet?source=webclient&url={url}&text={text}',
		),
		'viber'     => array(
			'icon'  => 'flext-ico-viber',
			'title' => esc_html__( 'Viber', 'flextension' ),
			'link'  => 'viber://forward?text={url}',
		),
		'vk'        => array(
			'icon'  => 'flext-ico-vkontakte',
			'title' => esc_html__( 'VK', 'flextension' ),
			'link'  => 'https://vk.com/share.php?url={url}',
		),
		'weibo'     => array(
			'icon'  => 'flext-ico-weibo',
			'title' => esc_html__( 'Weibo', 'flextension' ),
			'link'  => 'http://service.weibo.com/share/share.php?url={url}',
		),
		'whatsapp'  => array(
			'icon'  => 'flext-ico-whatsapp',
			'title' => esc_html__( 'WhatsApp', 'flextension' ),
			'link'  => 'whatsapp://send?text={url}',
		),
	);

	/**
	 * Filters the list of the share links.
	 *
	 * @param array $share_links An array of the share links.
	 */
	return apply_filters( 'flextension_share_links', $share_links );
}

/**
 * Returns the settings values of the Share Buttons module.
 *
 * @return array An array object of the settings.
 */
function flextension_share_buttons_settings() {
	return wp_parse_args(
		get_option( 'flext_share_buttons', array() ),
		array(
			'active_items' => array( 'facebook', 'twitter', 'pinterest', 'linkedin', 'tumblr', 'mail' ),
			'new_tab'      => false,
		)
	);
}

/**
 * Prints the share icons.
 *
 * @param int|WP_Post $post Optional. Post ID or WP_Post object. Default is global `$post`.
 */
function flextension_share_icons( $post = 0 ) {

	$post = get_post( $post );
	if ( ! $post ) {
		return;
	}

	/**
	 * Filters The image size to share.
	 *
	 * @param string $size The image size to share.
	 */
	$share_image_size = apply_filters( 'flextension_share_image_size', 'full' );

	$all_links = flextension_share_links();
	$settings  = flextension_share_buttons_settings();

	$defaults = array(
		'icon'     => '',
		'title'    => '',
		'link'     => '',
		'template' => '<a href="{link}" title="{title}" class="flext-icon-button" rel="nofollow" target="{target}"><i class="{icon}"></i></a>',
	);

	$link_data = array(
		'{url}'   => rawurlencode( get_permalink( $post ) ),
		'{text}'  => rawurlencode( get_the_title( $post ) ),
		'{media}' => rawurlencode( get_the_post_thumbnail_url( $post, $share_image_size ) ),
	);

	foreach ( $settings['active_items'] as $name ) {
		if ( ! isset( $all_links[ $name ] ) ) {
			continue;
		}

		$item = array_replace_recursive( $defaults, $all_links[ $name ] );

		$link = str_replace( array_keys( $link_data ), array_values( $link_data ), $item['link'] );

		$data = array(
			'{link}'   => esc_attr( esc_url( $link ) ),
			'{title}'  => esc_attr( $item['title'] ),
			'{icon}'   => esc_attr( $item['icon'] ),
			'{target}' => $settings['new_tab'] ? '_blank' : '_self',
		);

		echo str_replace( array_keys( $data ), array_values( $data ), $item['template'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}

/**
 * Prints the link button to share the current post.
 *
 * @param int|WP_Post $post      Optional. Post ID or WP_Post object. Default is global `$post`.
 * @param string      $css_class Optional. CSS class to use for the button. Default empty.
 */
function flextension_share_buttons( $post = 0, $css_class = '' ) {
	$post = get_post( $post );
	if ( ! $post ) {
		return;
	}

	$classes = array( 'post-share' );

	if ( ! empty( $css_class ) ) {
		$classes[] = $css_class;
	}

	echo sprintf(
		'<a class="%1$s" href="#flext-share-content-%2$s" data-post-id="%2$s" title="%3$s"><i class="flext-ico-share"></i></a>',
		esc_attr( implode( ' ', $classes ) ),
		esc_attr( $post->ID ),
		esc_attr( esc_html__( 'Send this to friends or share it on social media.', 'flextension' ) )
	) . '<!-- .post-share -->';
}

/**
 * Returns HTML content for the modal with social sharing buttons for the post.
 *
 * @param int $post_id The post ID.
 */
function flextension_share_modal( $post_id = 0 ) {
	flextension_get_template( plugin_dir_path( __FILE__ ) . 'templates/share-buttons', '', array( 'post_id' => $post_id ) );
}

/**
 * Adds WhatsApp to a list of allowed protocols.
 *
 * @param array $protocols An array list of protocols.
 * @return array An array list of protocols.
 */
function flextension_share_buttons_allow_whatsapp_protocol( $protocols = array() ) {
	$protocols[] = 'whatsapp';
	return $protocols;
}

add_filter( 'kses_allowed_protocols', 'flextension_share_buttons_allow_whatsapp_protocol' );

/**
 * Loads the scripts and stylesheets.
 */
function flextension_share_buttons_enqueue_scripts() {

	wp_enqueue_style( 'flextension-share-buttons', plugins_url( 'css/style.css', __FILE__ ), array( 'flextension-lightbox' ), flextension_get_setting( 'version' ) );
	wp_style_add_data( 'flextension-share-buttons', 'rtl', 'replace' );

	wp_enqueue_script( 'flextension-share-buttons', plugins_url( 'js/index.js', __FILE__ ), array( 'flextension-lightbox' ), flextension_get_setting( 'version' ), true );

}

add_action( 'wp_enqueue_scripts', 'flextension_share_buttons_enqueue_scripts' );

add_action( 'enqueue_block_editor_assets', 'flextension_share_buttons_enqueue_scripts' );
