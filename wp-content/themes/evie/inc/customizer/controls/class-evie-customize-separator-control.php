<?php
/**
 * Evie Customize Separator Control: Evie_Customize_Separator_Control class
 *
 * @package    Evie
 * @subpackage Customizer/Controls
 * @version    1.0.0
 */

if ( class_exists( 'WP_Customize_Control', false ) && ! class_exists( 'Evie_Customize_Separator_Control', false ) ) {

	/**
	 * Evie Customize Separator Control class.
	 */
	class Evie_Customize_Separator_Control extends WP_Customize_Control {

		/**
		 * The type of control being rendered.
		 *
		 * @var string $type The type of control being rendered.
		 */
		public $type = 'evie-separator';

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
			echo '<hr />';
		}
	}

}
