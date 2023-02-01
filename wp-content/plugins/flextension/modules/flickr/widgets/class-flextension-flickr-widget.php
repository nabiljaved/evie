<?php
/**
 * Flextension Flickr Widget
 *
 * @package    Flextension
 * @subpackage Modules/Flickr/Widgets
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Flickr Widget class.
 *
 * @see WP_Widget
 */
class Flextension_Flickr_Widget extends WP_Widget {

	/**
	 * Default settings array for the widget.
	 *
	 * @var array
	 */
	public $default_settings;

	/**
	 * Sets up a new Flickr widget instance.
	 */
	public function __construct() {

		$this->default_settings = array(
			'title'      => esc_html__( 'Find us on Flickr', 'flextension' ),
			'flickr_id'  => '',
			'type'       => 'user',
			'image_size' => 'small',
			'number'     => 9,
			'columns'    => 3,
			'gutters'    => '',
		);

		$widget_options = array(
			'classname'                   => 'flext-widget-flickr',
			'description'                 => esc_html__( 'Displays Flickr photos.', 'flextension' ),
			'customize_selective_refresh' => true,
		);

		parent::__construct( 'flext-flickr', esc_html__( 'Flextension Flickr Photos', 'flextension' ), $widget_options );
	}

	/**
	 * Outputs the content for the current Flickr widget instance.
	 *
	 * @param array $args     Display arguments including 'before_title', 'after_title',
	 *                        'before_widget', and 'after_widget'.
	 * @param array $instance Settings for the current Flickr widget instance.
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

		echo flextension_flickr_widget( $instance ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Handles updating the settings for the current Flickr widget instance.
	 *
	 * @param array $new_instance New settings for this instance as input by the user via
	 *                            WP_Widget::form().
	 * @param array $old_instance Old settings for this instance.
	 * @return array Updated settings to save.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['title']      = sanitize_text_field( $new_instance['title'] );
		$instance['flickr_id']  = sanitize_text_field( $new_instance['flickr_id'] );
		$instance['type']       = sanitize_key( $new_instance['type'] );
		$instance['image_size'] = sanitize_key( $new_instance['image_size'] );
		$instance['number']     = absint( $new_instance['number'] );
		$instance['columns']    = absint( $new_instance['columns'] );
		$instance['gutters']    = sanitize_key( $new_instance['gutters'] );

		return $instance;
	}

	/**
	 * Outputs the settings form for the Flickr widget.
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

		<!-- Type -->
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'type' ) ); ?>"><?php echo esc_html__( 'Type', 'flextension' ); ?>:</label>
			<select id="<?php echo esc_attr( $this->get_field_id( 'type' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'type' ) ); ?>">
				<?php
				$types = array(
					'user'  => esc_html__( 'User', 'flextension' ),
					'group' => esc_html__( 'Group', 'flextension' ),
				);
				?>
				<?php foreach ( $types as $value => $text ) : ?>
				<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $instance['type'], $value ); ?>><?php echo esc_html( $text ); ?></option>
				<?php endforeach; ?>
			</select>
		</p>

		<!-- Flickr ID -->
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'flickr_id' ) ); ?>"><?php echo esc_html__( 'Flickr ID', 'flextension' ); ?>:</label>
			<input id="<?php echo esc_attr( $this->get_field_id( 'flickr_id' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'flickr_id' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['flickr_id'] ); ?>" />
			<span class="description">
				<?php
				echo sprintf(
					/* translators: %s: The link to find the flickID */
					esc_html__( 'To find the flickID, please visit %s.', 'flextension' ),
					'<a href="https://www.webpagefx.com/tools/idgettr/" target="_blank">idGettr</a>'
				);
				?>
			</span>
		</p>

		<!-- Image Size -->
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'image_size' ) ); ?>"><?php echo esc_html__( 'Image Size', 'flextension' ); ?>:</label>
			<select id="<?php echo esc_attr( $this->get_field_id( 'image_size' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'image_size' ) ); ?>">
				<?php
				$options = array(
					'small'  => esc_html__( 'Small', 'flextension' ),
					'medium' => esc_html__( 'Medium', 'flextension' ),
					'large'  => esc_html__( 'Large', 'flextension' ),
				);
				?>
				<?php foreach ( $options as $value => $text ) : ?>
				<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $instance['image_size'], $value ); ?>><?php echo esc_html( $text ); ?></option>
				<?php endforeach; ?>
			</select>
		</p>

		<!-- Number of images to show -->
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php echo esc_html__( 'Number of images to show', 'flextension' ); ?>:</label>
			<input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" type="number" min="1" max="20" value="<?php echo intval( $instance['number'] ); ?>" size="2" />
		</p>

		<!-- Columns -->
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'columns' ) ); ?>"><?php echo esc_html__( 'Columns', 'flextension' ); ?>:</label>
			<select id="<?php echo esc_attr( $this->get_field_id( 'columns' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'columns' ) ); ?>">
				<?php for ( $index = 1; $index <= 12; $index++ ) : ?>
				<option value="<?php echo esc_attr( $index ); ?>" <?php selected( $instance['columns'], $index ); ?>><?php echo esc_html( $index ); ?></option>
				<?php endfor; ?>
			</select>
		</p>

		<!-- Gutters -->
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'gutters' ) ); ?>"><?php echo esc_html__( 'Gutters', 'flextension' ); ?>:</label>
			<select id="<?php echo esc_attr( $this->get_field_id( 'gutters' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'gutters' ) ); ?>">
				<option value=""><?php echo esc_html__( 'None', 'flextension' ); ?></option>
				<option value="small" <?php selected( $instance['gutters'], 'small' ); ?>><?php echo esc_html__( 'Small', 'flextension' ); ?></option>
				<option value="medium" <?php selected( $instance['gutters'], 'medium' ); ?>><?php echo esc_html__( 'Medium', 'flextension' ); ?></option>
				<option value="large" <?php selected( $instance['gutters'], 'large' ); ?>><?php echo esc_html__( 'Large', 'flextension' ); ?></option>
			</select>
		</p>
		<?php
	}
}

/**
 * Registers a Flickr widget.
 */
function flextension_flickr_widget_register() {
	register_widget( 'Flextension_Flickr_Widget' );
}

add_action( 'widgets_init', 'flextension_flickr_widget_register' );
