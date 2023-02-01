<?php
/**
 * Social Links
 *
 * @package    Flextension
 * @subpackage Modules/Social_Links
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Returns the array list of all social icons.
 *
 * @return array An array list of all social icons.
 */
function flextension_social_links_list() {

	$links = array(
		'500px'       => array(
			'icon'   => 'flext-ico-500px',
			'title'  => esc_html__( '500px', 'flextension' ),
			'link'   => 'https://www.500px.com/p/{username}',
			'fields' => array(
				array(
					'name'        => 'username',
					'required'    => true,
					'placeholder' => esc_html__( 'Username', 'flextension' ),
				),
			),
		),
		'android'     => array(
			'icon'  => 'flext-ico-android',
			'title' => esc_html__( 'Android', 'flextension' ),
		),
		'apple'       => array(
			'icon'  => 'flext-ico-apple',
			'title' => esc_html__( 'Apple', 'flextension' ),
		),
		'behance'     => array(
			'icon'   => 'flext-ico-behance',
			'title'  => esc_html__( 'Behance', 'flextension' ),
			'link'   => 'https://www.behance.net/{username}',
			'fields' => array(
				array(
					'name'        => 'username',
					'required'    => true,
					'placeholder' => esc_html__( 'Username', 'flextension' ),
				),
			),
		),
		'bitbucket'   => array(
			'icon'   => 'flext-ico-bitbucket',
			'title'  => esc_html__( 'Bitbucket', 'flextension' ),
			'link'   => 'https://bitbucket.org/{username}',
			'fields' => array(
				array(
					'name'        => 'username',
					'required'    => true,
					'placeholder' => esc_html__( 'Username', 'flextension' ),
				),
			),
		),
		'codepen'     => array(
			'icon'   => 'flext-ico-codepen',
			'title'  => esc_html__( 'CodePen', 'flextension' ),
			'link'   => 'https://codepen.io/{username}',
			'fields' => array(
				array(
					'name'        => 'username',
					'required'    => true,
					'placeholder' => esc_html__( 'Username', 'flextension' ),
				),
			),
		),
		'deviantart'  => array(
			'icon'   => 'flext-ico-deviantart',
			'title'  => esc_html__( 'DeviantArt', 'flextension' ),
			'link'   => 'https://www.deviantart.com/{username}',
			'fields' => array(
				array(
					'name'        => 'username',
					'required'    => true,
					'placeholder' => esc_html__( 'Username', 'flextension' ),
				),
			),
		),
		'digg'        => array(
			'icon'   => 'flext-ico-digg',
			'title'  => esc_html__( 'Digg', 'flextension' ),
			'link'   => 'https://digg.com/@{username}',
			'fields' => array(
				array(
					'name'        => 'username',
					'required'    => true,
					'placeholder' => esc_html__( 'Username', 'flextension' ),
				),
			),
		),
		'discord'     => array(
			'icon'   => 'flext-ico-discord',
			'title'  => esc_html__( 'Discord', 'flextension' ),
			'link'   => 'https://discordapp.com/users/{username}',
			'fields' => array(
				array(
					'name'        => 'username',
					'required'    => true,
					'placeholder' => esc_html__( 'Username', 'flextension' ),
				),
			),
		),
		'dribbble'    => array(
			'icon'   => 'flext-ico-dribbble',
			'title'  => esc_html__( 'Dribbble', 'flextension' ),
			'link'   => 'https://dribbble.com/{username}',
			'fields' => array(
				array(
					'name'        => 'username',
					'required'    => true,
					'placeholder' => esc_html__( 'Username', 'flextension' ),
				),
			),
		),
		'email'       => array(
			'icon'   => 'flext-ico-mail',
			'title'  => esc_html__( 'Email', 'flextension' ),
			'link'   => 'mailto:{email}',
			'fields' => array(
				array(
					'name'        => 'email',
					'type'        => 'email',
					'required'    => true,
					'placeholder' => esc_html__( 'Email Address', 'flextension' ),
				),
			),
		),
		'facebook'    => array(
			'icon'   => 'flext-ico-facebook',
			'title'  => esc_html__( 'Facebook', 'flextension' ),
			'link'   => 'https://www.facebook.com/{username}',
			'fields' => array(
				array(
					'name'        => 'username',
					'required'    => true,
					'placeholder' => esc_html__( 'Username', 'flextension' ),
				),
			),
		),
		'flickr'      => array(
			'icon'   => 'flext-ico-flickr',
			'title'  => esc_html__( 'Flickr', 'flextension' ),
			'link'   => 'https://www.flickr.com/photos/{id}',
			'fields' => array(
				array(
					'name'        => 'id',
					'required'    => true,
					'placeholder' => esc_html__( 'Flickr ID', 'flextension' ),
				),
			),
		),
		'foursquare'  => array(
			'icon'   => 'flext-ico-foursquare',
			'title'  => esc_html__( 'Foursquare', 'flextension' ),
			'link'   => 'https://foursquare.com/{type}{name}',
			'fields' => array(
				array(
					'name'    => 'type',
					'label'   => esc_html__( 'Type', 'flextension' ),
					'type'    => 'select',
					'options' => array(
						''      => esc_html__( 'Username', 'flextension' ),
						'user/' => esc_html__( 'User ID', 'flextension' ),
						'v/'    => esc_html__( 'Place ID', 'flextension' ),
					),
				),
				array(
					'name'        => 'name',
					'required'    => true,
					'placeholder' => esc_html__( 'Username, User ID or Place ID', 'flextension' ),
				),
			),
		),
		'github'      => array(
			'icon'   => 'flext-ico-github',
			'title'  => esc_html__( 'Github', 'flextension' ),
			'link'   => 'https://github.com/{username}',
			'fields' => array(
				array(
					'name'        => 'username',
					'required'    => true,
					'placeholder' => esc_html__( 'Username', 'flextension' ),
				),
			),
		),
		'gitlab'      => array(
			'icon'   => 'flext-ico-gitlab',
			'title'  => esc_html__( 'GitLab', 'flextension' ),
			'link'   => 'https://gitlab.com/{username}',
			'fields' => array(
				array(
					'name'        => 'username',
					'required'    => true,
					'placeholder' => esc_html__( 'Username', 'flextension' ),
				),
			),
		),
		'houzz'       => array(
			'icon'  => 'flext-ico-houzz',
			'title' => esc_html__( 'Houzz', 'flextension' ),
		),
		'instagram'   => array(
			'icon'   => 'flext-ico-instagram',
			'title'  => esc_html__( 'Instagram', 'flextension' ),
			'link'   => 'https://www.instagram.com/{username}',
			'fields' => array(
				array(
					'name'        => 'username',
					'required'    => true,
					'placeholder' => esc_html__( 'Username', 'flextension' ),
				),
			),
		),
		'jsfiddle'    => array(
			'icon'  => 'flext-ico-jsfiddle',
			'title' => esc_html__( 'JSFiddle', 'flextension' ),
		),
		'line'        => array(
			'icon'   => 'flext-ico-line',
			'title'  => esc_html__( 'Line', 'flextension' ),
			'link'   => 'https://line.me/{type}{action}{id}',
			'fields' => array(
				array(
					'name'    => 'type',
					'label'   => esc_html__( 'Type', 'flextension' ),
					'type'    => 'select',
					'options' => array(
						'R'      => esc_html__( 'Official Account', 'flextension' ),
						'ti/p/~' => esc_html__( 'Personal Account', 'flextension' ),
					),
				),
				array(
					'name'         => 'action',
					'label'        => esc_html__( 'Action', 'flextension' ),
					'type'         => 'select',
					'options'      => array(
						'/ti/p/@'               => esc_html__( 'Add as a friend', 'flextension' ),
						'/home/public/main?id=' => esc_html__( 'Open timeline', 'flextension' ),
						'/nv/recommendOA/@'     => esc_html__( 'Share with', 'flextension' ),
					),
					'dependencies' => array(
						array(
							'name'  => 'type',
							'value' => 'R',
						),
					),
				),
				array(
					'name'        => 'id',
					'required'    => true,
					'placeholder' => esc_html__( 'LINE ID', 'flextension' ),
				),
			),
		),
		'linkedin'    => array(
			'icon'   => 'flext-ico-linkedin',
			'title'  => esc_html__( 'LinkedIn', 'flextension' ),
			'link'   => 'https://www.linkedin.com/{type}/{username}',
			'fields' => array(
				array(
					'name'    => 'type',
					'label'   => esc_html__( 'Type', 'flextension' ),
					'type'    => 'select',
					'options' => array(
						'company' => esc_html__( 'Company', 'flextension' ),
						'in'      => esc_html__( 'User Profile', 'flextension' ),
					),
				),
				array(
					'name'        => 'username',
					'required'    => true,
					'placeholder' => esc_html__( 'Company or User ID', 'flextension' ),
				),
			),
		),
		'medium'      => array(
			'icon'   => 'flext-ico-medium',
			'title'  => esc_html__( 'Medium', 'flextension' ),
			'link'   => 'https://{username}.medium.com/',
			'fields' => array(
				array(
					'name'        => 'username',
					'required'    => true,
					'placeholder' => esc_html__( 'Username', 'flextension' ),
				),
			),
		),
		'meetup'      => array(
			'icon'   => 'flext-ico-meetup',
			'title'  => esc_html__( 'Meetup', 'flextension' ),
			'link'   => 'https://www.meetup.com/members/{id}',
			'fields' => array(
				array(
					'name'        => 'id',
					'required'    => true,
					'placeholder' => esc_html__( 'Member ID', 'flextension' ),
				),
			),
		),
		'ok'          => array(
			'icon'   => 'flext-ico-odnoklassniki',
			'title'  => esc_html__( 'Odnoklassniki', 'flextension' ),
			'link'   => 'https://ok.ru/{type}{name}',
			'fields' => array(
				array(
					'name'    => 'type',
					'label'   => esc_html__( 'Type', 'flextension' ),
					'type'    => 'select',
					'options' => array(
						''         => esc_html__( 'Profile name or Group Name', 'flextension' ),
						'profile/' => esc_html__( 'Profile ID', 'flextension' ),
						'group/'   => esc_html__( 'Group ID', 'flextension' ),
					),
				),
				array(
					'name'        => 'name',
					'required'    => true,
					'placeholder' => esc_html__( 'Name or ID', 'flextension' ),
				),
			),
		),
		'phone'       => array(
			'icon'   => 'flext-ico-phone',
			'title'  => esc_html__( 'Phone', 'flextension' ),
			'link'   => 'tel:+{number}',
			'fields' => array(
				array(
					'name'        => 'number',
					'type'        => 'tel',
					'required'    => true,
					'description' => esc_html__( 'Phone number with country code.', 'flextension' ),
					'placeholder' => esc_html__( 'Phone Number', 'flextension' ),
				),
			),
		),
		'pinterest'   => array(
			'icon'   => 'flext-ico-pinterest',
			'title'  => esc_html__( 'Pinterest', 'flextension' ),
			'link'   => 'https://pinterest.com/{username}',
			'fields' => array(
				array(
					'name'        => 'username',
					'required'    => true,
					'placeholder' => esc_html__( 'Username', 'flextension' ),
				),
			),
		),
		'reddit'      => array(
			'icon'   => 'flext-ico-reddit',
			'title'  => esc_html__( 'Reddit', 'flextension' ),
			'link'   => 'https://www.reddit.com/{type}/{username}',
			'fields' => array(
				array(
					'name'    => 'type',
					'label'   => esc_html__( 'Type', 'flextension' ),
					'type'    => 'select',
					'options' => array(
						'r'    => esc_html__( 'Subreddit', 'flextension' ),
						'user' => esc_html__( 'User', 'flextension' ),
					),
				),
				array(
					'name'        => 'username',
					'required'    => true,
					'placeholder' => esc_html__( 'Subreddit Name or Reddit User', 'flextension' ),
				),
			),
		),
		'rss'         => array(
			'icon'  => 'flext-ico-rss',
			'title' => esc_html__( 'RSS', 'flextension' ),
		),
		'skype'       => array(
			'icon'   => 'flext-ico-skype',
			'title'  => esc_html__( 'Skype', 'flextension' ),
			'link'   => 'skype:{username}?{action}',
			'fields' => array(
				array(
					'name'        => 'username',
					'required'    => true,
					'placeholder' => esc_html__( 'Username', 'flextension' ),
				),
				array(
					'name'    => 'action',
					'label'   => esc_html__( 'Action', 'flextension' ),
					'type'    => 'select',
					'options' => array(
						'add'       => esc_html__( 'Add to contacts', 'flextension' ),
						'call'      => esc_html__( 'Call', 'flextension' ),
						'chat'      => esc_html__( 'Chat', 'flextension' ),
						'voicemail' => esc_html__( 'Send voice mail', 'flextension' ),
						'userinfo'  => esc_html__( 'View user profile', 'flextension' ),
					),
				),
			),
		),
		'snapchat'    => array(
			'icon'   => 'flext-ico-snapchat',
			'title'  => esc_html__( 'Snapchat', 'flextension' ),
			'link'   => 'https://www.snapchat.com/add/{username}',
			'fields' => array(
				array(
					'name'        => 'username',
					'required'    => true,
					'placeholder' => esc_html__( 'Username', 'flextension' ),
				),
			),
		),
		'soundcloud'  => array(
			'icon'   => 'flext-ico-soundcloud',
			'title'  => esc_html__( 'SoundCloud', 'flextension' ),
			'link'   => 'https://soundcloud.com/{username}',
			'fields' => array(
				array(
					'name'        => 'username',
					'required'    => true,
					'placeholder' => esc_html__( 'Username', 'flextension' ),
				),
			),
		),
		'spotify'     => array(
			'icon'   => 'flext-ico-spotify',
			'title'  => esc_html__( 'Spotify', 'flextension' ),
			'link'   => 'https://open.spotify.com/{type}/{id}',
			'fields' => array(
				array(
					'name'    => 'type',
					'label'   => esc_html__( 'Type', 'flextension' ),
					'type'    => 'select',
					'options' => array(
						'artist'   => esc_html__( 'Artist', 'flextension' ),
						'playlist' => esc_html__( 'Playlist', 'flextension' ),
						'show'     => esc_html__( 'Show', 'flextension' ),
						'user'     => esc_html__( 'User', 'flextension' ),
					),
				),
				array(
					'name'        => 'id',
					'required'    => true,
					'placeholder' => esc_html__( 'User ID or Show ID', 'flextension' ),
				),
			),
		),
		'steam'       => array(
			'icon'  => 'flext-ico-steam',
			'title' => esc_html__( 'Steam', 'flextension' ),
		),
		'strava'      => array(
			'icon'   => 'flext-ico-strava',
			'title'  => esc_html__( 'Strava', 'flextension' ),
			'link'   => 'https://www.strava.com/athletes/{id}',
			'fields' => array(
				array(
					'name'        => 'id',
					'required'    => true,
					'placeholder' => esc_html__( 'Strava ID', 'flextension' ),
				),
			),
		),
		'telegram'    => array(
			'icon'   => 'flext-ico-telegram',
			'title'  => esc_html__( 'Telegram', 'flextension' ),
			'link'   => 'https://t.me/{username}',
			'fields' => array(
				array(
					'name'        => 'username',
					'required'    => true,
					'placeholder' => esc_html__( 'Username', 'flextension' ),
				),
			),
		),
		'thumbtack'   => array(
			'icon'  => 'flext-ico-thumbtack',
			'title' => esc_html__( 'Thumbtack', 'flextension' ),
		),
		'tiktok'      => array(
			'icon'   => 'flext-ico-tiktok',
			'title'  => esc_html__( 'TikTok', 'flextension' ),
			'link'   => 'https://tiktok.com/@{username}',
			'fields' => array(
				array(
					'name'        => 'username',
					'required'    => true,
					'placeholder' => esc_html__( 'Username', 'flextension' ),
				),
			),
		),
		'tripadvisor' => array(
			'icon'  => 'flext-ico-tripadvisor',
			'title' => esc_html__( 'TripAdvisor', 'flextension' ),
		),
		'tumblr'      => array(
			'icon'   => 'flext-ico-tumblr',
			'title'  => esc_html__( 'Tumblr', 'flextension' ),
			'link'   => 'https://{username}.tumblr.com/',
			'fields' => array(
				array(
					'name'        => 'username',
					'required'    => true,
					'placeholder' => esc_html__( 'Username', 'flextension' ),
				),
			),
		),
		'twitch'      => array(
			'icon'   => 'flext-ico-twitch',
			'title'  => esc_html__( 'Twitch', 'flextension' ),
			'link'   => 'https://www.twitch.tv/{username}',
			'fields' => array(
				array(
					'name'        => 'username',
					'required'    => true,
					'placeholder' => esc_html__( 'Username', 'flextension' ),
				),
			),
		),
		'twitter'     => array(
			'icon'   => 'flext-ico-twitter',
			'title'  => esc_html__( 'Twitter', 'flextension' ),
			'link'   => 'https://twitter.com/{username}',
			'fields' => array(
				array(
					'name'        => 'username',
					'required'    => true,
					'placeholder' => esc_html__( 'Username', 'flextension' ),
				),
			),
		),
		'viber'       => array(
			'icon'   => 'flext-ico-viber',
			'title'  => esc_html__( 'Viber', 'flextension' ),
			'link'   => 'viber://{action}?number={number}',
			'fields' => array(
				array(
					'name'    => 'action',
					'label'   => esc_html__( 'Action', 'flextension' ),
					'type'    => 'select',
					'options' => array(
						'add'     => esc_html__( 'Add to contacts', 'flextension' ),
						'chat'    => esc_html__( 'Chat', 'flextension' ),
						'contact' => esc_html__( 'Contact', 'flextension' ),
					),
				),
				array(
					'name'        => 'number',
					'type'        => 'tel',
					'required'    => true,
					'description' => esc_html__( 'Phone number with country code.', 'flextension' ),
					'placeholder' => esc_html__( 'Phone Number', 'flextension' ),
				),
			),
		),
		'vimeo'       => array(
			'icon'   => 'flext-ico-vimeo',
			'title'  => esc_html__( 'Vimeo', 'flextension' ),
			'link'   => 'https://vimeo.com/{username}',
			'fields' => array(
				array(
					'name'        => 'username',
					'required'    => true,
					'placeholder' => esc_html__( 'Username', 'flextension' ),
				),
			),
		),
		'vk'          => array(
			'icon'   => 'flext-ico-vkontakte',
			'title'  => esc_html__( 'VK', 'flextension' ),
			'link'   => 'https://vk.com/{id}',
			'fields' => array(
				array(
					'name'    => 'type',
					'label'   => esc_html__( 'Type', 'flextension' ),
					'type'    => 'select',
					'options' => array(
						'group' => esc_html__( 'Group', 'flextension' ),
						'user'  => esc_html__( 'User', 'flextension' ),
					),
				),
				array(
					'name'        => 'id',
					'required'    => true,
					'placeholder' => esc_html__( 'Group or User ID', 'flextension' ),
				),
			),
		),
		'whatsapp'    => array(
			'icon'   => 'flext-ico-whatsapp',
			'title'  => esc_html__( 'WhatsApp', 'flextension' ),
			'link'   => 'https://wa.me/{number}',
			'fields' => array(
				array(
					'name'        => 'number',
					'type'        => 'tel',
					'required'    => true,
					'description' => esc_html__( 'Phone number with country code.', 'flextension' ),
					'placeholder' => esc_html__( 'Phone Number', 'flextension' ),
				),
			),
		),
		'wechat'      => array(
			'icon'   => 'flext-ico-wechat',
			'title'  => esc_html__( 'WeChat', 'flextension' ),
			'link'   => 'https://web.wechat.com/{username}',
			'fields' => array(
				array(
					'name'        => 'username',
					'required'    => true,
					'placeholder' => esc_html__( 'Username', 'flextension' ),
				),
			),
		),
		'weibo'       => array(
			'icon'   => 'flext-ico-weibo',
			'title'  => esc_html__( 'Weibo', 'flextension' ),
			'link'   => 'https://www.weibo.com/{username}',
			'fields' => array(
				array(
					'name'        => 'username',
					'required'    => true,
					'placeholder' => esc_html__( 'Username', 'flextension' ),
				),
			),
		),
		'xing'        => array(
			'icon'   => 'flext-ico-xing',
			'title'  => esc_html__( 'Xing', 'flextension' ),
			'link'   => 'https://www.xing.com/{type}{id}',
			'fields' => array(
				array(
					'name'    => 'type',
					'label'   => esc_html__( 'Type', 'flextension' ),
					'type'    => 'select',
					'options' => array(
						'profile/'   => esc_html__( 'Personal', 'flextension' ),
						'companies/' => esc_html__( 'Company', 'flextension' ),
					),
				),
				array(
					'name'        => 'id',
					'required'    => true,
					'placeholder' => esc_html__( 'User or Company ID', 'flextension' ),
				),
			),
		),
		'yahoo'       => array(
			'icon'  => 'flext-ico-yahoo',
			'title' => esc_html__( 'Yahoo', 'flextension' ),
		),
		'yelp'        => array(
			'icon'  => 'flext-ico-yelp',
			'title' => esc_html__( 'Yelp', 'flextension' ),
		),
		'youtube'     => array(
			'icon'   => 'flext-ico-youtube',
			'title'  => esc_html__( 'Youtube', 'flextension' ),
			'link'   => 'https://www.youtube.com/{type}{id}',
			'fields' => array(
				array(
					'name'    => 'type',
					'label'   => esc_html__( 'Type', 'flextension' ),
					'type'    => 'select',
					'options' => array(
						''         => esc_html__( 'Custom', 'flextension' ),
						'channel/' => esc_html__( 'ID-based', 'flextension' ),
						'user/'    => esc_html__( 'Legacy username', 'flextension' ),
					),
				),
				array(
					'name'        => 'id',
					'required'    => true,
					'placeholder' => esc_html__( 'Username or Channel ID', 'flextension' ),
				),
			),
		),
	);

	/**
	 * Filters the array list of social links.
	 *
	 * @param array $links An array list of social links.
	 */
	$links = apply_filters( 'flextension_social_links', $links );

	ksort( $links );

	return $links;
}

/**
 * Returns the settings values of the Social Links module.
 *
 * @return array An array object of the settings.
 */
function flextension_social_links_settings() {
	return wp_parse_args(
		get_option( 'flext_social_links', array() ),
		array(
			'active_items' => array(),
			'social_links' => array(),
			'new_tab'      => false,
		)
	);
}

/**
 * Retrieves the array list of active social links.
 *
 * @return array An array list of active social links.
 */
function flextension_social_icons() {
	$all_links    = flextension_social_links_list();
	$settings     = flextension_social_links_settings();
	$social_icons = array();
	if ( ! empty( $settings['active_items'] ) ) {
		foreach ( $settings['active_items'] as $name ) {
			if ( isset( $all_links[ $name ] ) ) {
				$social_icons[ $name ] = $all_links[ $name ];
			}
		}
	}
	return $social_icons;
}

/**
 * Retrieves the array list of active social links.
 *
 * @return array An array list of active social links.
 */
function flextension_get_social_links() {
	$settings     = flextension_social_links_settings();
	$social_links = $settings['social_links'];
	$active_items = flextension_social_icons();
	$links        = array();

	if ( ! empty( $active_items ) ) {

		foreach ( $active_items as $name => $value ) {

			$url = flextension_get_social_url( $name, isset( $social_links[ $name ] ) ? $social_links[ $name ] : array(), $active_items );

			if ( ! empty( $url ) ) {
				$links[ $name ] = array(
					'icon' => $value['icon'],
					'text' => $value['title'],
					'url'  => $url,
				);
			}
		}
	}
	return $links;
}

/**
 * Returns whether the social link setting is valid.
 *
 * @param mixed $values Setting values.
 * @param array $fields Social link setting fields.
 * @return bool Whether the setting is valid.
 */
function flextension_social_link_setting_is_valid( $values = array(), $fields = array() ) {
	$is_valid = true;
	if ( is_string( $values ) ) {
		$is_valid = ! empty( $values );
	} elseif ( is_array( $values ) && ! empty( $values ) && ! empty( $fields ) ) {
		foreach ( $values as $name => $value ) {
			if ( empty( $value ) ) {
				foreach ( $fields as $field ) {
					if ( $name === $field['name'] && isset( $field['required'] ) && true === $field['required'] ) {
						$is_valid = false;
						break 2;
					}
				}
			}
		}
	}

	return $is_valid;
}

/**
 * Returns the URL for the social media.
 *
 * @param string $name         The name of social media.
 * @param mixed  $values       The link object.
 * @param array  $active_items The active social links.
 * @return string The URL for the social media.
 */
function flextension_get_social_url( $name, $values, $active_items = array() ) {

	if ( empty( $active_items ) ) {
		$active_items = flextension_social_icons();
	}

	$url = '';
	if ( is_string( $values ) ) {
		$url = $values;
	} elseif ( is_array( $values ) && ! empty( $values ) ) {
		if ( isset( $active_items[ $name ]['link'] ) && ! empty( $active_items[ $name ]['link'] ) ) {
			$data = array();
			foreach ( $values as $key => $value ) {
				$data[ '{' . $key . '}' ] = $value;
			}

			if ( ! empty( $data ) ) {
				$url = str_replace( array_keys( $data ), array_values( $data ), $active_items[ $name ]['link'] );
			}
		} elseif ( isset( $values['url'] ) && ! empty( $values['url'] ) ) {
			$url = $values['url'];
		}
	}

	/**
	 * Filters the URL for the social media.
	 *
	 * @param string $url    The the URL for the social media.
	 * @param string $name   The name of social media.
	 * @param mixed  $values The social link object.
	 */
	return apply_filters( 'flextension_get_social_url', $url, $name, $values );
}

/**
 * Returns an array list of HTML links.
 *
 * @param array  $links   An array list of the social links.
 * @param string $display Whether to show only icons or names.
 * @return array An array list of HTML links.
 */
function flextension_social_links( $links = array(), $display = '' ) {
	$social_links = array();

	if ( ! empty( $links ) ) {

		$settings   = flextension_social_links_settings();
		$attributes = array();
		if ( $settings['new_tab'] ) {
			$attributes['target'] = '_blank';
		}

		$attributes['rel'] = 'nofollow';

		foreach ( $links as $name => $link ) {
			if ( is_array( $link ) && ! empty( $link['url'] ) ) {

				$text = $link['text'];
				if ( 'names' !== $display ) {
					$text = ! empty( $link['icon'] ) ? '<i class="' . esc_attr( $link['icon'] ) . '"></i>' : '<i class="flext-ico-website"></i>';
				}

				$social_links[] = sprintf(
					'<a class="flext-link-%1$s" href="%2$s" title="%3$s"%4$s>%5$s</a>',
					esc_attr( $name ),
					esc_url( $link['url'] ),
					esc_attr( $link['text'] ),
					flextension_get_attributes( $attributes ),
					$text
				);
			}
		}
	}

	return $social_links;
}

/**
 * Retrieves the user's contact links.
 *
 * @param int $user_id The user ID.
 * @return string HTML output for the user's contact links.
 */
function flextension_get_user_social_links( $user_id = 0 ) {

	if ( ! $user_id ) {
		return '';
	}

	$links = array();

	$website = get_the_author_meta( 'url', $user_id );
	if ( ! empty( $website ) ) {
		$links['website'] = array(
			'icon' => 'flext-ico-globe',
			'text' => esc_html__( 'Website', 'flextension' ),
			'url'  => $website,
		);
	}

	$active_items = flextension_social_icons();

	if ( ! empty( $active_items ) ) {

		foreach ( $active_items as $name => $value ) {

			$social_link = get_user_meta( $user_id, $name, true );

			if ( ! empty( $social_link ) ) {
				$links[ $name ] = array(
					'icon' => $value['icon'],
					'text' => $value['title'],
					'url'  => flextension_get_social_url( $name, $social_link ),
				);
			}
		}
	}

	$social_links = flextension_social_links( $links );

	$output = '';

	if ( ! empty( $social_links ) ) {
		$output = '<div class="flext-user-social-links flext-social-icons">' . implode( ' ', $social_links ) . '</div>';
	}

	return $output;
}

/**
 * Renders Social Icons widget.
 *
 * @param array $attributes The attributes list for the widget.
 * @return string The HTML content for the widget.
 */
function flextension_social_icons_widget( $attributes = array() ) {

	wp_enqueue_style( 'flextension-social-links' );

	/**
	 * Filters the Social Icons widget arguments.
	 *
	 * @param array An array list of Social Icons widget arguments.
	 */
	$defaults = apply_filters(
		'flextension_social_icons_widget_settings',
		array(
			'align' => '',
			'style' => '',
		)
	);

	$attributes = wp_parse_args(
		$attributes,
		$defaults
	);

	$output = '';

	$links = flextension_get_social_links();

	$social_links = flextension_social_links( $links, $attributes['style'] );

	if ( ! empty( $social_links ) ) {

		$classes = array();

		$classes[] = 'flext-social-icons';

		if ( ! empty( $attributes['align'] ) ) {
			$classes[] = 'flext-align-' . $attributes['align'];
		}

		if ( ! empty( $attributes['style'] ) ) {
			$classes[] = 'flext-style-' . $attributes['style'];
		}

		$output = '<div class="' . esc_attr( implode( ' ', $classes ) ) . '">' . implode( ' ', $social_links ) . '</div>';
	}

	return $output;
}

/**
 * Adds Skype and Viber to a list of allowed protocols.
 *
 * @param array $protocols An array list of protocols.
 * @return array An array list of protocols.
 */
function flextension_social_links_allow_skype_protocol( $protocols = array() ) {
	$protocols[] = 'skype';
	$protocols[] = 'viber';
	return $protocols;
}

add_filter( 'kses_allowed_protocols', 'flextension_social_links_allow_skype_protocol' );

/**
 * Adds the contact links to the author description.
 *
 * @param string $value   The value of the metadata.
 * @param int    $user_id The user ID for the value.
 * @return string The author description with the contact links.
 */
function flextension_social_links_author_description( $value = '', $user_id = 0 ) {
	return $value . flextension_get_user_social_links( $user_id );
}

add_filter( 'get_the_author_description', 'flextension_social_links_author_description', 15, 2 );

/**
 * Registers scripts and stylesheets.
 */
function flextension_social_links_register_scripts() {
	wp_register_style( 'flextension-social-links', plugins_url( 'css/style.css', __FILE__ ), array( 'flextension' ), flextension_get_setting( 'version' ) );
	wp_style_add_data( 'flextension-social-links', 'rtl', 'replace' );
}

add_action( 'init', 'flextension_social_links_register_scripts' );

/**
 * Enqueues the scripts and stylesheets for the module.
 */
function flextension_social_links_enqueue_scripts() {
	wp_enqueue_style( 'flextension-social-links' );
}

add_action( 'wp_enqueue_scripts', 'flextension_social_links_enqueue_scripts' );

/**
 * Enqueues the scripts and stylesheets for the Block Editor.
 *
 * @since 1.0.8 Fix an appearance issue with Author's social icons in the Block Editor.
 */
function flextension_social_links_enqueue_block_editor_assets() {
	wp_enqueue_style( 'flextension-social-links' );
}

add_action( 'enqueue_block_editor_assets', 'flextension_social_links_enqueue_block_editor_assets' );
