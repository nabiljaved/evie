<?php
/**
 * Facebook Page Widget
 *
 * @package    Flextension
 * @subpackage Modules/Facebook/Widgets
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Core class used to implement a Facebook widget.
 *
 * @see WP_Widget
 */
class Flextension_Facebook_Page_Widget extends WP_Widget {

	/**
	 * Default settings array for the widget.
	 *
	 * @var array
	 */
	public $default_settings;

	/**
	 * Sets up a new Facebook widget instance.
	 */
	public function __construct() {

		$this->default_settings = array(
			'title'         => esc_html__( 'Find us on Facebook', 'flextension' ),
			'page_url'      => '',
			'width'         => '240',
			'height'        => '500',
			'show_facepile' => true,
			'small_header'  => false,
			'timeline'      => true,
			'events'        => false,
			'messages'      => false,
		);

		$widget_options = array(
			'classname'                   => 'flext-widget-facebook-page',
			'description'                 => esc_html__( 'Displays Timeline, Events and Messages from Facebook Page.', 'flextension' ),
			'customize_selective_refresh' => true,
		);

		parent::__construct( 'flext-facebook-page', esc_html__( 'Flextension Facebook Page', 'flextension' ), $widget_options );

	}

	/**
	 * Outputs the content for the current Facebook widget instance.
	 *
	 * @param array $args     Display arguments including 'before_title', 'after_title',
	 *                        'before_widget', and 'after_widget'.
	 * @param array $instance Settings for the current Facebook widget instance.
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

		echo flextension_facebook_page_widget( $instance ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Handles updating the settings for the current Facebook widget instance.
	 *
	 * @param array $new_instance New settings for this instance as input by the user via
	 *                            WP_Widget::form().
	 * @param array $old_instance Old settings for this instance.
	 * @return array Updated settings to save.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['title']         = sanitize_text_field( $new_instance['title'] );
		$instance['page_url']      = esc_url( $new_instance['page_url'] );
		$instance['width']         = absint( $new_instance['width'] );
		$instance['height']        = absint( $new_instance['height'] );
		$instance['show_facepile'] = (bool) $new_instance['show_facepile'];
		$instance['small_header']  = (bool) $new_instance['small_header'];
		$instance['timeline']      = (bool) $new_instance['timeline'];
		$instance['events']        = (bool) $new_instance['events'];
		$instance['messages']      = (bool) $new_instance['messages'];

		return $instance;
	}

	/**
	 * Outputs the settings form for the Facebook widget.
	 *
	 * @param array $instance Current settings.
	 */
	public function form( $instance ) {

		$instance = wp_parse_args( (array) $instance, $this->default_settings );
		?>
		<!-- Title -->
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php echo esc_html__( 'Title', 'flextension' ); ?>:</label>
			<input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" />
		</p>

		<!-- Facebook Page URL -->
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'page_url' ) ); ?>"><?php echo esc_html__( 'Facebook Page URL', 'flextension' ); ?>:</label>
			<input class="widefat" type="text" id="<?php echo esc_attr( $this->get_field_id( 'page_url' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'page_url' ) ); ?>" value="<?php echo esc_url( $instance['page_url'] ); ?>" />
		</p>

		<!-- Width -->
		<div>
			<label for="<?php echo esc_attr( $this->get_field_id( 'width' ) ); ?>"><?php echo esc_html__( 'Width', 'flextension' ); ?>:</label>
			<input class="tiny-text" type="text" style="width: 50px;" id="<?php echo esc_attr( $this->get_field_id( 'width' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'width' ) ); ?>" value="<?php echo esc_attr( $instance['width'] ); ?>" />
			<p><?php echo esc_html__( 'The pixel width of the plugin. Min. is 180 & Max. is 500.', 'flextension' ); ?></p>
		</div>

		<!-- Height -->
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'height' ) ); ?>"><?php echo esc_html__( 'Height', 'flextension' ); ?>:</label>
			<input class="tiny-text" type="text" style="width: 50px;" id="<?php echo esc_attr( $this->get_field_id( 'height' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'height' ) ); ?>" value="<?php echo esc_attr( $instance['height'] ); ?>" />
		</p>

		<!-- Show Friend's Faces -->
		<p>
			<input class="checkbox" type="checkbox" <?php checked( $instance['show_facepile'] ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_facepile' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_facepile' ) ); ?>" value="true" />
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_facepile' ) ); ?>"><?php echo esc_html__( "Show Friend's Faces", 'flextension' ); ?></label>
		</p>

		<!-- Small Header -->
		<p>
			<input class="checkbox" type="checkbox" <?php checked( $instance['small_header'] ); ?> id="<?php echo esc_attr( $this->get_field_id( 'small_header' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'small_header' ) ); ?>" value="true" />
			<label for="<?php echo esc_attr( $this->get_field_id( 'small_header' ) ); ?>"><?php echo esc_html__( 'Small Header', 'flextension' ); ?></label>
		</p>

		<!-- Timeline -->
		<p>
			<strong><?php echo esc_html__( 'Tabs', 'flextension' ); ?></strong><br />

			<input class="checkbox" type="checkbox" <?php checked( $instance['timeline'] ); ?> id="<?php echo esc_attr( $this->get_field_id( 'timeline' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'timeline' ) ); ?>" value="true" />
			<label for="<?php echo esc_attr( $this->get_field_id( 'timeline' ) ); ?>"><?php echo esc_html__( 'Timeline', 'flextension' ); ?></label>

			<input class="checkbox" type="checkbox" <?php checked( $instance['events'] ); ?> id="<?php echo esc_attr( $this->get_field_id( 'events' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'events' ) ); ?>" value="true" />
			<label for="<?php echo esc_attr( $this->get_field_id( 'events' ) ); ?>"><?php echo esc_html__( 'Events', 'flextension' ); ?></label>

			<input class="checkbox" type="checkbox" <?php checked( $instance['messages'] ); ?> id="<?php echo esc_attr( $this->get_field_id( 'messages' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'messages' ) ); ?>" value="true" />
			<label for="<?php echo esc_attr( $this->get_field_id( 'messages' ) ); ?>"><?php echo esc_html__( 'Messages', 'flextension' ); ?></label>
		</p>
		<?php
	}
}

/**
 * Registers a Facebook Page widget.
 */
function flextension_facebook_page_widget_register() {
	register_widget( 'Flextension_Facebook_Page_Widget' );
}

add_action( 'widgets_init', 'flextension_facebook_page_widget_register' );
