<?php
/**
 * Post Carousel Widget
 *
 * @package    Flextension
 * @subpackage Modules/Elements/Widgets
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Core class used to implement a Post Carousel widget to display Popular Posts, Recent Posts and Recent Comments.
 *
 * @see WP_Widget
 */
class Flextension_Post_Carousel_Widget extends WP_Widget {

	/**
	 * Default settings array for the widget.
	 *
	 * @var array
	 */
	public $default_settings;

	/**
	 * Sets up a new Post Carousel widget instance.
	 */
	public function __construct() {

		add_action( 'customize_controls_enqueue_scripts', array( $this, 'controls_scripts' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

		$this->default_settings = array(
			'title'     => '',
			'post_type' => 'post',
			'type'      => 'recent',
			'tags'      => '',
			'number'    => 5,
		);

		$widget_options = array(
			'classname'                   => 'flext-post-carousel-widget',
			'description'                 => esc_html__( 'Displays Popular Posts, Recent Posts and Recent Comments in carousel.', 'flextension' ),
			'customize_selective_refresh' => true,
		);

		parent::__construct( 'flext-post-carousel', esc_html__( 'Flextension Post Carousel', 'flextension' ), $widget_options );
	}

	/**
	 * Outputs the content for the current Post Carousel widget instance.
	 *
	 * @param array $args     Display arguments including 'before_title', 'after_title',
	 *                        'before_widget', and 'after_widget'.
	 * @param array $instance Settings for the current Post Carousel widget instance.
	 */
	public function widget( $args, $instance ) {
		if ( ! isset( $args['widget_id'] ) ) {
			$args['widget_id'] = $this->id;
		}

		$instance = wp_parse_args( (array) $instance, $this->default_settings );

		$title = $instance['title'];

		/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

		echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		if ( $title ) {
			echo $args['before_title'] . esc_html( $title ) . $args['after_title']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		echo flextension_post_carousel_widget( $instance ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Handles updating the settings for the current Post Carousel widget instance.
	 *
	 * @param array $new_instance New settings for this instance as input by the user via
	 *                            WP_Widget::form().
	 * @param array $old_instance Old settings for this instance.
	 * @return array Updated settings to save.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['title']     = sanitize_text_field( $new_instance['title'] );
		$instance['post_type'] = sanitize_key( $new_instance['post_type'] );
		$instance['type']      = sanitize_key( $new_instance['type'] );
		$instance['tags']      = sanitize_text_field( $new_instance['tags'] );
		$instance['number']    = intval( $new_instance['number'] );
		return $instance;
	}

	/**
	 * Outputs the settings form for the Post Carousel widget.
	 *
	 * @param array $instance Current settings.
	 */
	public function form( $instance ) {

		$instance = wp_parse_args( (array) $instance, $this->default_settings );

		$post_types = array(
			'post' => esc_html__( 'Post', 'flextension' ),
		);

		/**
		 * Filters the post types to display in the Post Carousel widget.
		 *
		 * @param array $post_types An array of post types.
		 */
		$post_types = apply_filters( 'flextension_post_carousel_widget_post_types', $post_types );

		$types = array(
			'popular' => esc_html__( 'Popular', 'flextension' ),
			'recent'  => esc_html__( 'Latest', 'flextension' ),
			'tags'    => esc_html__( 'Specific Tags', 'flextension' ),
		);

		?>
		<!-- Title -->
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php echo esc_html__( 'Title', 'flextension' ); ?>:</label>
			<input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" />
		</p>

		<!-- Post type -->
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'post_type' ) ); ?>"><?php echo esc_html__( 'Post type', 'flextension' ); ?>:</label>
			<select id="<?php echo esc_attr( $this->get_field_id( 'post_type' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'post_type' ) ); ?>">
				<?php foreach ( $post_types as $value => $label ) : ?>
				<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $instance['post_type'], $value ); ?>><?php echo esc_html( $label ); ?></option>
				<?php endforeach; ?>
				</select>
		</p>

		<!-- Source -->
		<p class="flext-post-carousel-widget-type-input-field">
			<label for="<?php echo esc_attr( $this->get_field_id( 'type' ) ); ?>"><?php echo esc_html__( 'Source', 'flextension' ); ?>:</label>
			<select id="<?php echo esc_attr( $this->get_field_id( 'type' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'type' ) ); ?>" onchange="flextension.postCarouselTypeChange( this );">
				<?php foreach ( $types as $value => $label ) : ?>
				<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $instance['type'], $value ); ?>><?php echo esc_html( $label ); ?></option>
				<?php endforeach; ?>
			</select>
		</p>

		<!-- Tags -->
		<p class="flext-post-carousel-widget-tags-input-field" style="display: none;">
			<label for="<?php echo esc_attr( $this->get_field_id( 'tags' ) ); ?>"><?php echo esc_html__( 'Tags', 'flextension' ); ?>:</label>
			<input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id( 'tags' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'tags' ) ); ?>" value="<?php echo esc_attr( $instance['tags'] ); ?>" />
			<span><?php echo esc_html__( 'Enter a tag name, or multiple tags separated by commas.', 'flextension' ); ?></span>
		</p>

		<!-- Number of posts to show -->
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php echo esc_html__( 'Number of posts to show', 'flextension' ); ?></label>
			<input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" type="number" step="1" min="1" value="<?php echo esc_attr( $instance['number'] ); ?>" size="3" />
		</p>

		<script>
			( function() {
				setTimeout( () => {
					flextension.postCarouselTypeChange( '#<?php echo esc_attr( $this->get_field_id( 'type' ) ); ?>' );
				}, 100 );				
			} )();
		</script>
		<?php
	}

	/**
	 * Loads admin scripts.
	 *
	 * @param string $hook Current page.
	 */
	public function admin_enqueue_scripts( $hook ) {

		if ( 'widgets.php' === $hook ) {
			wp_enqueue_script( 'flextension-widget-editor' );
		}

	}

	/**
	 * Loads customize controls scripts.
	 */
	public function controls_scripts() {

		wp_enqueue_script( 'flextension-widget-editor' );

	}
}

/**
 * Registers a Post Carousel widget.
 */
function flextension_post_carousel_widget_register() {
	register_widget( 'Flextension_Post_Carousel_Widget' );
}

add_action( 'widgets_init', 'flextension_post_carousel_widget_register' );
