<?php
/**
 * Customize Range Control
 *
 * @package    Evie
 * @subpackage Customizer/Controls
 * @version    1.0.0
 */

if ( class_exists( 'WP_Customize_Control', false ) && ! class_exists( 'Evie_Customize_Range_Control', false ) ) {

	/**
	 * Customize Range Control.
	 */
	class Evie_Customize_Range_Control extends WP_Customize_Control {

		/**
		 * The type of control being rendered
		 *
		 * @var string $type The customize control type.
		 */
		public $type = 'evie-range';

		/**
		 * A visual representation of step ticks.
		 *
		 * @var array
		 */
		public $marks = array();

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
			$this->marks = isset( $args['marks'] ) ? $args['marks'] : array();
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
			<div class="flext-field-range-wrapper">
				<?php

				if ( ! empty( $this->marks ) ) {
					?>
					<div class="evie-range-marks">
					<?php
					foreach ( $this->marks as $value => $label ) {
						echo '<span>' . esc_html( $label ) . '</span>';
					}
					?>
					</div>
					<?php
				}

				?>
				<input
					type="range"
					id="<?php echo esc_attr( $this->id ); ?>"
					value="<?php echo esc_attr( $this->value() ); ?>"
					<?php $this->link(); ?>
					<?php $this->input_attrs(); ?>
				/>

				<div class="evie-range-value-wrapper"><span class="evie-range-value"><?php echo esc_html( $this->value() ); ?></span></div>

			</div>

			<?php

		}
	}

}
