<?php
/**
 * Instagram Feed Widget
 *
 * @package    Flextension
 * @subpackage Modules/Instagram/Widgets
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Flextension Instagram class.
 *
 * @see WP_Widget
 */
class Flextension_Instagram_Widget extends WP_Widget {

	/**
	 * Default settings array for the widget.
	 *
	 * @var array
	 */
	public $default_settings;

	/**
	 * Sets up a new Instagram widget instance.
	 */
	public function __construct() {

		$this->default_settings = array(
			'title'         => esc_html__( 'Follow Us', 'flextension' ),
			'gutters'       => '',
			'number'        => 9,
			'columns'       => 3,
			'show_username' => false,
		);

		$widget_options = array(
			'classname'                   => 'flext-widget-instagram',
			'description'                 => esc_html__( 'Displays a media feed from your Instagram account.', 'flextension' ),
			'customize_selective_refresh' => true,
		);

		parent::__construct( 'flext-instagram', esc_html__( 'Flextension Instagram Feed', 'flextension' ), $widget_options );

	}

	/**
	 * Outputs the content for the current Instagram widget instance.
	 *
	 * @param array $args     Display arguments including 'before_title', 'after_title',
	 *                        'before_widget', and 'after_widget'.
	 * @param array $instance Settings for the current Instagram widget instance.
	 */
	public function widget( $args, $instance ) {

		$instance = wp_parse_args( (array) $instance, $this->default_settings );

		$title = $instance['title'];

		/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

		echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		echo flextension_instagram_widget( $instance ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Handles updating the settings for the current Instagram widget instance.
	 *
	 * @param array $new_instance New settings for this instance as input by the user via
	 *                            WP_Widget::form().
	 * @param array $old_instance Old settings for this instance.
	 * @return array Updated settings to save.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['title']         = sanitize_text_field( $new_instance['title'] );
		$instance['gutters']       = sanitize_key( $new_instance['gutters'] );
		$instance['number']        = absint( $new_instance['number'] );
		$instance['columns']       = absint( $new_instance['columns'] );
		$instance['show_username'] = (bool) $new_instance['show_username'];

		return $instance;
	}

	/**
	 * Outputs the settings form for the Instagram widget.
	 *
	 * @param array $instance Current settings.
	 */
	public function form( $instance ) {

		$instance = wp_parse_args( (array) $instance, $this->default_settings );

		$settings = flextension_instagram_settings();

		if ( ! empty( $settings['username'] ) ) {
			?>
			<p>
				<?php echo esc_html__( 'Displaying a media feed from:', 'flextension' ) . ' <a href="https://instagram.com/' . esc_attr( $settings['username'] ) . '" target="_blank"><strong>' . esc_html( $settings['username'] ) . '</strong></a>.'; ?>
			</p>
			<p>
				<?php
				echo sprintf(
					wp_kses(
					/* translators: %s: Settings page link */
						__( 'You can connect to another account in %s.', 'flextension' ),
						array(
							'a' => array(
								'href'   => array(),
								'target' => array(),
							),
						)
					),
					'<a href="' . esc_attr( flextension_get_admin_page_url( 'instagram' ) ) . '" target="_blank">Settings → Instagram Feed</a>'
				);
				?>
			</p>
			<?php
		} else {
			?>
			<p>
				<?php
				echo sprintf(
					wp_kses(
						/* translators: %s: Settings page link */
						__( 'There are no connected accounts. To display a media feed, you will need to connect an account in %s.', 'flextension' ),
						array(
							'a' => array(
								'href'   => array(),
								'target' => array(),
							),
						)
					),
					'<a href="' . esc_attr( flextension_get_admin_page_url( 'instagram' ) ) . '">Settings → Instagram Feed</a>'
				);
				?>
			</p>
			<?php
		}

		?>
		<!-- Title -->
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php echo esc_html__( 'Title', 'flextension' ); ?>:</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
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

		<!-- Display Username -->
		<p>
			<input id="<?php echo esc_attr( $this->get_field_id( 'show_username' ) ); ?>" class="checkbox" name="<?php echo esc_attr( $this->get_field_name( 'show_username' ) ); ?>" type="checkbox" <?php checked( (bool) $instance['show_username'] ); ?> />
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_username' ) ); ?>"><?php echo esc_html__( 'Display Username', 'flextension' ); ?></label>
		</p>

		<?php
	}

}

/**
 * Registers an Instagram widget.
 */
function flextension_instagram_widget_init() {
	register_widget( 'Flextension_Instagram_Widget' );
}

add_action( 'widgets_init', 'flextension_instagram_widget_init' );
