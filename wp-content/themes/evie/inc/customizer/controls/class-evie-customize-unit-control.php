<?php
/**
 * Customize Unit Control
 *
 * @package    Evie
 * @subpackage Customizer/Controls
 * @version    1.0.0
 */

if ( class_exists( 'WP_Customize_Control', false ) && ! class_exists( 'Evie_Customize_Unit_Control', false ) ) {

	/**
	 * Customize Unit Control.
	 */
	class Evie_Customize_Unit_Control extends WP_Customize_Control {

		/**
		 * The type of control being rendered
		 *
		 * @var string $type The customize control type.
		 */
		public $type = 'evie-unit';

		/**
		 * The placeholder of the input.
		 *
		 * @var string $placeholder The placeholder of the input.
		 */
		public $placeholder = '';

		/**
		 * Whether to show the Clear button.
		 *
		 * @var bool
		 */
		public $clear = true;

		/**
		 * Render the control's content.
		 */
		public function render_content() {

			$input_id       = '_customize-input-' . $this->id;
			$description_id = '_customize-description-' . $this->id;

			if ( ! empty( $this->label ) ) {
				echo sprintf(
					'<label for="evie-customize-unit-%s-desktop" class="customize-control-title">%s</label>',
					esc_attr( $input_id ),
					esc_html( $this->label )
				);
			}

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

				echo '<p id="' . esc_attr( $description_id ) . '" class="description customize-control-description">' . wp_kses( $this->description, $allowed_html ) . '</p>';
			}

			$breakpoints = array(
				'mobile'  => 'dashicons dashicons-smartphone',
				'tablet'  => 'dashicons dashicons-tablet',
				'desktop' => 'dashicons dashicons-desktop',
			);

			$values = (array) $this->value();

			echo '<div class="evie-customize-input-controls">';

			$input_index = 0;

			foreach ( $breakpoints as $name => $icon ) {

				$value = isset( $values[ $input_index ] ) ? $values[ $input_index ] : '';

				echo '<div class="evie-customize-input-control">';

				echo sprintf(
					'<input id="evie-customize-unit-%1$s-%2$s" type="text" value="%3$s" placeholder="%4$s" %5$s />',
					esc_attr( $input_id ),
					esc_attr( $name ),
					esc_attr( $value ),
					esc_attr( $this->placeholder ),
					$this->input_attrs() // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				);

				if ( ! empty( $icon ) ) {
					$label = '<span class="' . esc_attr( $icon ) . '"></span>';
				} else {
					$label = ucfirst( $name );
				}

				echo sprintf(
					'<label for="evie-customize-unit-%1$s-%2$s">%3$s</label>',
					esc_attr( $input_id ),
					esc_attr( $name ),
					$label // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				);

				echo '</div>';

				$input_index++;

			}

			if ( $this->clear ) {
				echo '<div class="evie-customize-clear-button"><button class="button button-secondary" aria-label="' . esc_attr( esc_html__( 'Clear', 'evie' ) ) . '">' . esc_html__( 'Clear', 'evie' ) . '</button></div>';
			}

			echo '</div>';

			echo sprintf(
				'<input id="%1$s" class="evie-customize-unit-values" type="hidden" value="%2$s" %3$s />',
				esc_attr( $input_id ),
				esc_attr( implode( ',', $values ) ),
				$this->get_link() // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			);

		}
	}

}
