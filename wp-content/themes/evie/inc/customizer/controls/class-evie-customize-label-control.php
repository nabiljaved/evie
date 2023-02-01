<?php
/**
 * Customize Label Control
 *
 * @package    Evie
 * @subpackage Customizer/Controls
 * @version    1.0.0
 */

if ( class_exists( 'WP_Customize_Control', false ) && ! class_exists( 'Evie_Customize_Label_Control', false ) ) {

	/**
	 * Customize Label Control.
	 */
	class Evie_Customize_Label_Control extends WP_Customize_Control {

		/**
		 * The type of control being rendered
		 *
		 * @var string $type The customize control type.
		 */
		public $type = 'evie-label';

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
			// Process settings.
			if ( ! isset( $this->settings ) ) {
				$this->settings = 'blogname';
			}
			parent::__construct( $manager, $id, $args );
		}

		/**
		 * Render the control's content.
		 */
		public function render_content() {
			if ( ! empty( $this->label ) ) {
				echo '<span class="customize-control-title">' . esc_html( $this->label ) . '</span>';
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

				echo '<p class="customize-control-description">' . wp_kses( $this->description, $allowed_html ) . '</p>';
			}

		}
	}

}
