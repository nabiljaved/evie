<?php
/**
 * Authors Widget
 *
 * @package    Flextension
 * @subpackage Modules/Author/Widgets
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Core class used to implement an authors widget.
 *
 * @see WP_Widget
 */
class Flextension_Authors_Widget extends WP_Widget {

	/**
	 * Default settings array for the widget.
	 *
	 * @var array
	 */
	public $default_settings;

	/**
	 * Sets up a new Authors widget instance.
	 */
	public function __construct() {

		$this->default_settings = array(
			'title'      => esc_html__( 'Authors', 'flextension' ),
			'role'       => '',
			'followers'  => false,
			'avatar'     => true,
			'cover'      => false,
			'image_size' => 'post-thumbnail',
		);

		$widget_options = array(
			'classname'                   => 'flext-widget-authors',
			'description'                 => esc_html__( 'Displays a list of authors, contributors and editors.', 'flextension' ),
			'customize_selective_refresh' => true,
		);

		parent::__construct( 'flext-authors', esc_html__( 'Flextension Authors', 'flextension' ), $widget_options );

	}

	/**
	 * Outputs the content for the current Authors widget instance.
	 *
	 * @param array $args     Display arguments including 'before_title', 'after_title',
	 *                        'before_widget', and 'after_widget'.
	 * @param array $instance Settings for the current Authors widget instance.
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

		echo flextension_authors_widget( $instance ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Handles updating the settings for the current Authors widget instance.
	 *
	 * @param array $new_instance New settings for this instance as input by the user via
	 *                            WP_Widget::form().
	 * @param array $old_instance Old settings for this instance.
	 * @return array Updated settings to save.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['title']      = sanitize_text_field( $new_instance['title'] );
		$instance['role']       = sanitize_text_field( $new_instance['role'] );
		$instance['followers']  = (bool) $new_instance['followers'];
		$instance['avatar']     = (bool) $new_instance['avatar'];
		$instance['cover']      = (bool) $new_instance['cover'];
		$instance['image_size'] = sanitize_key( $new_instance['image_size'] );
		return $instance;
	}

	/**
	 * Outputs the settings form for the Authors widget.
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

		<!-- Role -->
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'role' ) ); ?>"><?php echo esc_html__( 'Role', 'flextension' ); ?></label>
			<select name="<?php echo esc_attr( $this->get_field_name( 'role' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'role' ) ); ?>" class="widefat">
				<?php

				$roles = flextension_author_get_roles();

				foreach ( $roles as $slug => $name ) {
					?>
				<option value="<?php echo esc_attr( $slug ); ?>" <?php selected( $instance['role'], $slug ); ?>><?php echo esc_html( $name ); ?></option>
					<?php
				}

				?>
			</select>
		</p>

		<!-- Display followers -->
		<p>
			<input id="<?php echo esc_attr( $this->get_field_id( 'followers' ) ); ?>" class="checkbox" name="<?php echo esc_attr( $this->get_field_name( 'followers' ) ); ?>" type="checkbox" <?php checked( (bool) $instance['followers'] ); ?> />
			<label for="<?php echo esc_attr( $this->get_field_id( 'followers' ) ); ?>"><?php echo esc_html__( 'Display followers', 'flextension' ); ?></label>
		</p>

		<!-- Display avatar -->
		<p>
			<input id="<?php echo esc_attr( $this->get_field_id( 'avatar' ) ); ?>" class="checkbox" name="<?php echo esc_attr( $this->get_field_name( 'avatar' ) ); ?>" type="checkbox" <?php checked( (bool) $instance['avatar'] ); ?> />
			<label for="<?php echo esc_attr( $this->get_field_id( 'avatar' ) ); ?>"><?php echo esc_html__( 'Display avatar', 'flextension' ); ?></label>
		</p>

		<!-- Display cover image -->
		<p>
			<input id="<?php echo esc_attr( $this->get_field_id( 'cover' ) ); ?>" class="checkbox" name="<?php echo esc_attr( $this->get_field_name( 'cover' ) ); ?>" type="checkbox" <?php checked( (bool) $instance['cover'] ); ?> onclick="flextension.authorWidgetImageSizeChange( this );"/>
			<label for="<?php echo esc_attr( $this->get_field_id( 'cover' ) ); ?>"><?php echo esc_html__( 'Display cover image', 'flextension' ); ?></label>
		</p>

		<!-- Image Size -->
		<p class="flext-author-widget-image-size-field" style="display: none;">
			<label for="<?php echo esc_attr( $this->get_field_id( 'image_size' ) ); ?>"><?php echo esc_html__( 'Image size', 'flextension' ); ?></label>
			<select name="<?php echo esc_attr( $this->get_field_name( 'image_size' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'image_size' ) ); ?>" class="widefat">
				<?php
				$image_sizes = array(
					'post-thumbnail' => esc_html__( 'Thumbnail', 'flextension' ),
					'medium'         => esc_html__( 'Medium', 'flextension' ),
					'large'          => esc_html__( 'Large', 'flextension' ),
					'full'           => esc_html__( 'Full Size', 'flextension' ),
				);

				if ( ! empty( $image_sizes ) ) {
					foreach ( $image_sizes as $size => $name ) {
						?>
							<option value="<?php echo esc_attr( $size ); ?>" <?php selected( $instance['image_size'], $size ); ?>><?php echo esc_html( $name ); ?></option>
						<?php
					}
				}
				?>
			</select>
		</p>

		<script>
			( function() {
				target = document.querySelector( '#<?php echo esc_attr( $this->get_field_id( 'cover' ) ); ?>' );
				if ( target !== null ) {
					const imageSize = target.parentElement.parentElement.querySelector(
						'.flext-author-widget-image-size-field'
					);
					if ( imageSize !== null ) {
						imageSize.style.display = target.checked ? '' : 'none';
					}
				}
			} )();
		</script>

		<?php
	}
}

/**
 * Registers a Authors widget.
 */
function flextension_authors_widget_register() {
	register_widget( 'Flextension_Authors_Widget' );
}

add_action( 'widgets_init', 'flextension_authors_widget_register' );
