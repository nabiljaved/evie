<?php
/**
 * Customize Dropdown Control
 *
 * @package    Evie
 * @subpackage Customizer/Controls
 * @version    1.0.0
 */

if ( class_exists( 'WP_Customize_Control', false ) && ! class_exists( 'Evie_Customize_Dropdown_Control', false ) ) {

	/**
	 * Customize Dropdown Control.
	 */
	class Evie_Customize_Dropdown_Control extends WP_Customize_Control {

		/**
		 * The type of control being rendered
		 *
		 * @var string $type The customize control type.
		 */
		public $type = 'evie-dropdown';

		/**
		 * List of choices for 'select' type controls, where values are the keys, and labels are the values.
		 *
		 * @var array
		 */
		public $choices = array();

		/**
		 * List of groups and options for 'select' type controls, where groups are the keys, and options are the values.
		 *
		 * @var array
		 */
		public $groups = array();

		/**
		 * Constructor.
		 *
		 * @uses WP_Customize_Control::__construct()
		 *
		 * @param WP_Customize_Manager $manager Customizer bootstrap instance.
		 * @param string               $id      Control ID.
		 * @param array                $args    Optional. Arguments to override class property defaults.
		 */
		public function __construct( $manager, $id, $args = array() ) {
			$this->groups  = isset( $args['groups'] ) ? $args['groups'] : array();
			$this->choices = isset( $args['choices'] ) ? $args['choices'] : array();
			parent::__construct( $manager, $id, $args );
		}

		/**
		 * Render the control's content.
		 */
		public function render_content() {
			echo '<span class="customize-control-title">' . esc_html( $this->label ) . '</span>';

			if ( ! empty( $this->description ) ) {

				$allowed_html = array(
					'a'      => array(
						'href'   => array(),
						'title'  => array(),
						'class'  => array(),
						'target' => array(),
					),
					'br'     => array(),
					'em'     => array(),
					'strong' => array(),
					'i'      => array(
						'class' => array(),
					),
					'span'   => array(
						'class' => array(),
					),
					'code'   => array(),
				);

				echo '<p class="customize-control-description">' . wp_kses( $this->description, $allowed_html ) . '</p>';
			}

			?>
			<select
				id="<?php echo esc_attr( $this->id ); ?>"
				value="<?php echo esc_attr( $this->value() ); ?>"
				<?php $this->link(); ?>
				<?php $this->input_attrs(); ?>
			>
			<?php

			if ( ! empty( $this->groups ) ) {
				foreach ( $this->groups as $name => $group ) {
					echo sprintf( '<optgroup label="%s">', esc_attr( $group['label'] ) );
					if ( ! empty( $group['options'] ) ) {
						foreach ( $group['options'] as $value => $label ) {
							echo sprintf(
								'<option value="%1$s" %2$s>%3$s</option>',
								esc_attr( $value ),
								selected( $this->value(), $value, false ),
								esc_html( $label )
							);
						}
					}
					echo '</optgroup>';
				}
			} elseif ( ! empty( $this->choices ) ) {
				foreach ( $this->choices as $value => $label ) {
					echo '<option value="' . esc_attr( $value ) . '"' . selected( $this->value(), $value, false ) . '>' . esc_html( $label ) . '</option>';
				}
			}

			?>
			</select>

			<?php

		}
	}

}
