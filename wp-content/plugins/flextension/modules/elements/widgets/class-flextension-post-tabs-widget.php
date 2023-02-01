<?php
/**
 * Post Tabs Widget
 *
 * @package    Flextension
 * @subpackage Modules/Elements/Widgets
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Core class used to implement a list widget to display Popular Posts, Recent Posts and Recent Comments.
 *
 * @see WP_Widget
 */
class Flextension_Post_Tabs_Widget extends WP_Widget {

	/**
	 * Default settings array for the widget.
	 *
	 * @var array
	 */
	public $default_settings;

	/**
	 * Sets up a new Post Tabs widget instance.
	 */
	public function __construct() {

		$this->default_settings = array(
			'post_type' => 'post',
			'popular'   => true,
			'recent'    => true,
			'comments'  => true,
			'number'    => 5,
		);

		$widget_options = array(
			'classname'                   => 'flext-post-tabs-widget',
			'description'                 => esc_html__( 'Displays Popular Posts, Recent Posts and Recent Comments in tabs.', 'flextension' ),
			'customize_selective_refresh' => true,
		);

		parent::__construct( 'flext-post-tabs', esc_html__( 'Flextension Post Tabs', 'flextension' ), $widget_options );
	}

	/**
	 * Outputs the content for the current Post Tabs widget instance.
	 *
	 * @param array $args     Display arguments including 'before_title', 'after_title',
	 *                        'before_widget', and 'after_widget'.
	 * @param array $instance Settings for the current Post Tabs widget instance.
	 */
	public function widget( $args, $instance ) {
		if ( ! isset( $args['widget_id'] ) ) {
			$args['widget_id'] = $this->id;
		}

		echo $args['before_widget'] . flextension_post_tabs_widget( $instance ) . $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Handles updating the settings for the current Post Tabs widget instance.
	 *
	 * @param array $new_instance New settings for this instance as input by the user via
	 *                            WP_Widget::form().
	 * @param array $old_instance Old settings for this instance.
	 * @return array Updated settings to save.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['post_type'] = sanitize_key( $new_instance['post_type'] );
		$instance['popular']   = (bool) $new_instance['popular'];
		$instance['recent']    = (bool) $new_instance['recent'];
		$instance['comments']  = (bool) $new_instance['comments'];
		$instance['number']    = intval( $new_instance['number'] );

		return $instance;
	}

	/**
	 * Outputs the settings form for the Post Tabs widget.
	 *
	 * @param array $instance Current settings.
	 */
	public function form( $instance ) {

		$instance = wp_parse_args( (array) $instance, $this->default_settings );

		$post_types = array(
			'post' => esc_html__( 'Post', 'flextension' ),
		);

		/**
		 * Filters the post types to display in the Post Tabs widget.
		 *
		 * @param array $post_types An array of post types.
		 */
		$post_types = apply_filters( 'flextension_post_tabs_widget_post_types', $post_types );

		?>
		<!-- Post type -->
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'post_type' ) ); ?>"><?php echo esc_html__( 'Post type', 'flextension' ); ?>:</label>
			<select id="<?php echo esc_attr( $this->get_field_id( 'post_type' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'post_type' ) ); ?>">
				<?php foreach ( $post_types as $value => $label ) : ?>
				<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $instance['post_type'], $value ); ?>><?php echo esc_html( $label ); ?></option>
				<?php endforeach; ?>
				</select>
		</p>

		<!-- Tabs -->
		<p>
			<strong><?php echo esc_html__( 'Tabs', 'flextension' ); ?></strong><br />
			<!-- Popular Posts -->
			<input class="checkbox" type="checkbox" <?php checked( $instance['popular'] ); ?> id="<?php echo esc_attr( $this->get_field_id( 'popular' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'popular' ) ); ?>" value="true" />
			<label for="<?php echo esc_attr( $this->get_field_id( 'popular' ) ); ?>"><?php echo esc_html__( 'Popular Posts', 'flextension' ); ?></label>
			<br />
			<!-- Recent Posts -->
			<input class="checkbox" type="checkbox" <?php checked( $instance['recent'] ); ?> id="<?php echo esc_attr( $this->get_field_id( 'recent' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'recent' ) ); ?>" value="true" />
			<label for="<?php echo esc_attr( $this->get_field_id( 'recent' ) ); ?>"><?php echo esc_html__( 'Recent Posts', 'flextension' ); ?></label>
			<br />
			<!-- Recent Comments -->
			<input class="checkbox" type="checkbox" <?php checked( $instance['comments'] ); ?> id="<?php echo esc_attr( $this->get_field_id( 'comments' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'comments' ) ); ?>" value="true" />
			<label for="<?php echo esc_attr( $this->get_field_id( 'comments' ) ); ?>"><?php echo esc_html__( 'Recent Comments', 'flextension' ); ?></label>
		</p>

		<!-- Number of posts to show -->
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php echo esc_html__( 'Number of posts to show:', 'flextension' ); ?></label>
			<input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" type="number" step="1" min="1" value="<?php echo esc_attr( $instance['number'] ); ?>" size="3" />
		</p>

		<?php
	}
}

/**
 * Registers a Post Tabs widget.
 */
function flextension_post_tabs_widget_register() {
	register_widget( 'Flextension_Post_Tabs_Widget' );
}

add_action( 'widgets_init', 'flextension_post_tabs_widget_register' );
