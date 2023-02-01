<?php
/**
 * Widget API: Flextension_Social_Icons_Widget class
 *
 * @package    Flextension
 * @subpackage Modules/Social_Links/Widgets
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Core class used to implement a Social Icons widget.
 *
 * @see WP_Widget
 */
class Flextension_Social_Icons_Widget extends WP_Widget {

	/**
	 * Default settings array for the widget.
	 *
	 * @var array
	 */
	public $default_settings;

	/**
	 * Sets up a new Social Icons widget instance.
	 */
	public function __construct() {

		$this->default_settings = array(
			'title' => esc_html__( 'Follow Us', 'flextension' ),
			'align' => '',
			'style' => '',
		);

		$widget_options = array(
			'classname'                   => 'flext-widget-social-icons',
			'description'                 => esc_html__( 'Displays social icons linked to your social media accounts.', 'flextension' ),
			'customize_selective_refresh' => true,
		);

		parent::__construct( 'flext-social-icons', esc_html__( 'Flextension Social Icons', 'flextension' ), $widget_options );
	}

	/**
	 * Outputs the content for the current Social Icons widget instance.
	 *
	 * @param array $args     Display arguments including 'before_title', 'after_title',
	 *                        'before_widget', and 'after_widget'.
	 * @param array $instance Settings for the current Social Icons widget instance.
	 */
	public function widget( $args, $instance ) {

		$instance = wp_parse_args( (array) $instance, $this->default_settings );

		$title = $instance['title'];

		/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

		echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		if ( $title ) {
			echo $args['before_title'] . esc_html( $title ) . $args['after_title']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		echo flextension_social_icons_widget( $instance ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Handles updating the settings for the current Social Icons widget instance.
	 *
	 * @param array $new_instance New settings for this instance as input by the user via
	 *                            WP_Widget::form().
	 * @param array $old_instance Old settings for this instance.
	 * @return array Updated settings to save.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['title'] = sanitize_text_field( $new_instance['title'] );
		$instance['align'] = sanitize_text_field( $new_instance['align'] );
		$instance['style'] = sanitize_text_field( $new_instance['style'] );
		return $instance;
	}

	/**
	 * Outputs the settings form for the Social Icons widget.
	 *
	 * @param array $instance Current settings.
	 */
	public function form( $instance ) {

		$instance = wp_parse_args( (array) $instance, $this->default_settings );
		?>

		<!-- Title -->
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php echo esc_html__( 'Title', 'flextension' ); ?>:</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
		</p>

		<!-- Alignment -->
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'align' ) ); ?>"><?php echo esc_html__( 'Alignment', 'flextension' ); ?>:</label>
			<select id="<?php echo esc_attr( $this->get_field_id( 'align' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'align' ) ); ?>" class="widefat">
				<?php
				$options = array(
					''       => esc_html__( 'Not Set', 'flextension' ),
					'left'   => esc_html__( 'Left', 'flextension' ),
					'center' => esc_html__( 'Center', 'flextension' ),
					'right'  => esc_html__( 'Right', 'flextension' ),
				);
				foreach ( $options as $value => $label ) :
					?>
				<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $instance['align'], $value ); ?>><?php echo esc_html( $label ); ?></option>
				<?php endforeach; ?>
			</select>
		</p>

		<!-- Style -->
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'style' ) ); ?>"><?php echo esc_html__( 'Style', 'flextension' ); ?>:</label>
			<select id="<?php echo esc_attr( $this->get_field_id( 'style' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'style' ) ); ?>" class="widefat">
				<?php
				$options = array(
					''       => esc_html__( 'Icons Only', 'flextension' ),
					'names'  => esc_html__( 'Names Only', 'flextension' ),
					'circle' => esc_html__( 'Circle', 'flextension' ),
				);
				foreach ( $options as $value => $label ) :
					?>
				<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $instance['style'], $value ); ?>><?php echo esc_html( $label ); ?></option>
				<?php endforeach; ?>
			</select>
		</p>
		<?php
	}
}

/**
 * Registers a Social Icons widget.
 */
function flextension_social_icons_widget_register() {
	register_widget( 'Flextension_Social_Icons_Widget' );
}

add_action( 'widgets_init', 'flextension_social_icons_widget_register' );
