<?php
/**
 * Widget API: Flextension_Categories_Widget class
 *
 * @package    Flextension
 * @subpackage Modules/Featured_Categories/Widgets
 * @version    1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Core class used to implement a Categories widget.
 *
 * @see WP_Widget
 */
class Flextension_Categories_Widget extends WP_Widget {

	/**
	 * Default settings array for the widget.
	 *
	 * @var array Array of default settings.
	 */
	public $default_settings;

	/**
	 * Sets up a new Categories widget instance.
	 */
	public function __construct() {

		add_action( 'customize_controls_enqueue_scripts', array( $this, 'controls_scripts' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

		$this->default_settings = array(
			'title'      => esc_html__( 'Categories', 'flextension' ),
			'taxonomy'   => 'category',
			'display'    => 'all',
			'terms'      => array(),
			'show_count' => false,
			'show_image' => false,
		);

		$widget_options = array(
			'classname'                   => 'flext-widget-categories',
			'description'                 => esc_html__( 'Displays a list of categories with images.', 'flextension' ),
			'customize_selective_refresh' => true,
		);

		parent::__construct( 'flext_categories', esc_html__( 'Flextension Categories', 'flextension' ), $widget_options );

	}

	/**
	 * Outputs the content for the current Categories widget instance.
	 *
	 * @param array $args     Display arguments including 'before_title', 'after_title',
	 *                        'before_widget', and 'after_widget'.
	 * @param array $instance Settings for the current Categories widget instance.
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

		echo flextension_categories_widget( $instance ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Handles updating the settings for the current Categories widget instance.
	 *
	 * @param array $new_instance New settings for this instance as input by the user via
	 *                            WP_Widget::form().
	 * @param array $old_instance Old settings for this instance.
	 * @return array Updated settings to save.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['title']      = sanitize_text_field( $new_instance['title'] );
		$instance['taxonomy']   = sanitize_text_field( $new_instance['taxonomy'] );
		$instance['display']    = sanitize_text_field( $new_instance['display'] );
		$instance['terms']      = array_map( 'intval', $new_instance['terms'] );
		$instance['show_count'] = isset( $new_instance['show_count'] ) ? (bool) $new_instance['show_count'] : false;
		$instance['show_image'] = isset( $new_instance['show_image'] ) ? (bool) $new_instance['show_image'] : false;
		return $instance;
	}

	/**
	 * Outputs the settings form for the Categories widget.
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

		<!-- Taxonomy -->
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'taxonomy' ) ); ?>"><?php echo esc_html__( 'Taxonomy', 'flextension' ); ?>:</label>
			<select id="<?php echo esc_attr( $this->get_field_id( 'taxonomy' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'taxonomy' ) ); ?>" class="widefat" onchange="flextension.widgetCategoriesTaxonomyChange(this);">
				<?php
				$taxonomies = flextension_featured_taxonomies();
				foreach ( $taxonomies as $name ) :
					$taxonomy = get_taxonomy( $name );
					?>
					<option value="<?php echo esc_attr( $taxonomy->name ); ?>" <?php selected( $instance['taxonomy'], $taxonomy->name ); ?>><?php echo esc_html( $taxonomy->label ); ?></option>
				<?php endforeach; ?>
			</select>
		</p>

		<!-- Display -->
		<?php
		/**
		 * Choose to display all terms or select specific terms to display.
		 *
		 * @since 1.1.3
		 */
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'display' ) ); ?>"><?php echo esc_html__( 'Display', 'flextension' ); ?>:</label>
			<select id="<?php echo esc_attr( $this->get_field_id( 'display' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'display' ) ); ?>" class="widefat" onchange="flextension.widgetCategoriesDisplayChange(this);">
				<option value="all" <?php selected( $instance['display'], 'all' ); ?>><?php echo esc_html__( 'All', 'flextension' ); ?></option>
				<option value="custom" <?php selected( $instance['display'], 'custom' ); ?>><?php echo esc_html__( 'Custom', 'flextension' ); ?></option>
			</select>
		</p>

		<!-- Terms -->
		<?php

		/**
		 * Select specific terms to display
		 *
		 * @since 1.1.3
		 */

		$attrs = array(
			'class'     => 'flext-widget-categories-terms-field',
			'data-name' => $this->get_field_name( 'terms' ),
		);
		if ( 'all' === $instance['display'] ) {
			$attrs['style'] = 'display: none;';
		}
		?>
		<div <?php flextension_attributes( $attrs ); ?>>
			<label for="<?php echo esc_attr( $this->get_field_id( 'terms' ) ); ?>"><?php echo esc_html__( 'Terms', 'flextension' ); ?>:</label>
			<ul class="flext-widget-categories-terms">
				<?php
				$args = array(
					'taxonomy'       => $instance['taxonomy'],
					'selected_terms' => $instance['terms'],
					'checkbox_name'  => $this->get_field_name( 'terms' ),
				);

				echo flextension_terms_checklist( $args ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				?>
			</ul>
		</div>

		<!-- Show post counts -->
		<p>
			<input type="checkbox"<?php checked( $instance['show_count'] ); ?> class="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'show_count' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_count' ) ); ?>" />
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_count' ) ); ?>"><?php echo esc_html__( 'Show post counts', 'flextension' ); ?></label>
		</p>

		<!-- Show image -->
		<p>
			<input type="checkbox"<?php checked( $instance['show_image'] ); ?> class="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'show_image' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_image' ) ); ?>" />
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_image' ) ); ?>"><?php echo esc_html__( 'Show featured image', 'flextension' ); ?></label>
		</p>
		<?php
	}

	/**
	 * Enqueues the scripts and stylesheets for the admin page.
	 *
	 * @param string $hook Current page.
	 */
	public function admin_enqueue_scripts( $hook ) {

		if ( 'widgets.php' === $hook ) {
			wp_enqueue_style( 'flextension-categories-widget-editor' );
			wp_enqueue_script( 'flextension-categories-widget-editor' );
		}

	}

	/**
	 * Enqueues customize controls scripts and stylesheets.
	 */
	public function controls_scripts() {
		wp_enqueue_style( 'flextension-categories-widget-editor' );
		wp_enqueue_script( 'flextension-categories-widget-editor' );
	}
}

/**
 * Registers a Categories widget.
 */
function flextension_categories_widget_register() {
	register_widget( 'Flextension_Categories_Widget' );
}

add_action( 'widgets_init', 'flextension_categories_widget_register' );
