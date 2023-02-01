<?php
/**
 * Featured Media Editor
 *
 * @package    Flextension
 * @subpackage Modules/Featured_Media/Admin
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Featured Media Meta Box class.
 */
class Flextension_Featured_Media_Meta_Box {

	/**
	 * Initializes the class, adds actions and filters.
	 */
	public function __construct() {

		// Initialize the meta box on the edit screen.
		add_action( 'load-post.php', array( $this, 'init_meta_box' ) );
		add_action( 'load-post-new.php', array( $this, 'init_meta_box' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
	}

	/**
	 * Initializes the meta box on the project edit screen.
	 */
	public function init_meta_box() {
		add_filter( 'flextension_meta_box_sections', array( $this, 'register_meta_box_sections' ), 10, 2 );
	}

	/**
	 * Registers the meta box sections on the page edit screen.
	 *
	 * @param array   $sections An array list of the meta box sections.
	 * @param WP_Post $post     Post object.
	 * @return array An array list of the meta box sections.
	 */
	public function register_meta_box_sections( $sections = array(), $post = 0 ) {

		$post_types = flextension_featured_media_post_types();

		$fields = array(
			array(
				'name'    => '_flext_featured_media_type',
				'label'   => esc_html__( 'Type', 'flextension' ),
				'type'    => 'select',
				'options' => array(
					''       => esc_html__( 'Gallery', 'flextension' ),
					'slider' => esc_html__( 'Slider', 'flextension' ),
				),
			),
			array(
				'name'         => '_flext_featured_rollover_image',
				'label'        => esc_html__( 'Image rollover', 'flextension' ),
				'description'  => esc_html__( 'This image will appear when the mouse over the featured image.', 'flextension' ),
				'type'         => 'image',
				'preview_size' => 'post-thumbnail',
			),
			array(
				'name' => '_flext_featured_images',
				'type' => 'images',
			),
			array(
				'name'         => '_flext_featured_media',
				'label'        => esc_html__( 'Media', 'flextension' ),
				'description'  => esc_html__( 'Enter a media URL from media services (e.g. YouTube, Vimeo, SoundCloud) or choose from Media Library.', 'flextension' ),
				'type'         => 'media',
				'preview_size' => array(
					'width' => '252',
				),
				'allow_url'    => true,
			),
			array(
				'name'         => '_flext_featured_media_poster',
				'label'        => esc_html__( 'Poster image (optional)', 'flextension' ),
				'description'  => esc_html__( 'If not set, Featured Image will be used.', 'flextension' ),
				'type'         => 'image',
				'preview_size' => 'post-thumbnail',
			),
		);

		/**
		 * Filters the meta setting fields for the featured media.
		 *
		 * @param array $fields An array list of the setting fields.
		 * @param WP_Post $post Post object.
		 */
		$fields = apply_filters( 'flextension_featured_media_meta_fields', $fields, $post );

		$sections[] = array(
			'id'         => 'featured-media',
			'title'      => esc_html__( 'Featured Media', 'flextension' ),
			'post_types' => $post_types,
			'context'    => 'side',
			'fields'     => $fields,
		);

		return $sections;
	}

	/**
	 * Loads the scripts and stylesheets for the admin page.
	 *
	 * @param string $hook The current screen.
	 */
	public function admin_enqueue_scripts( $hook ) {
		if ( in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
			wp_enqueue_script( 'flextension-featured-media-editor', plugins_url( 'js/index.js', __FILE__ ), array(), flextension_get_setting( 'version' ), true );
		}
	}

}
