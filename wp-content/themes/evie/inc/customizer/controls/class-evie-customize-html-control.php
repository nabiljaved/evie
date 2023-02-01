<?php
/**
 * Evie Customize HTML Control: Evie_Customize_HTML_Control class
 *
 * @package    Evie
 * @subpackage Customizer/Controls
 * @version    1.0.0
 */

if ( class_exists( 'WP_Customize_Control', false ) && ! class_exists( 'Evie_Customize_HTML_Control', false ) ) {

	/**
	 * Customize HTML Control.
	 */
	class Evie_Customize_HTML_Control extends WP_Customize_Control {
		/**
		 * The type of control being rendered.
		 *
		 * @var string $type The customize control type.
		 */
		public $type = 'evie-html';

		/**
		 * Enqueue our scripts and styles.
		 */
		public function enqueue() {
			wp_enqueue_editor();
		}

		/**
		 * Render the control in the customizer.
		 */
		public function render_content() {
			echo '<span class="customize-control-title">' . esc_html( $this->label ) . '</span>';
			if ( ! empty( $this->description ) ) {
				echo '<p class="customize-control-description">' . esc_html( $this->description ) . '</p>';
			}

			?>
			<textarea id="<?php echo esc_attr( $this->id ); ?>" class="evie-customize-control-html" <?php $this->link(); ?>><?php echo esc_html( $this->value() ); ?></textarea>
			<?php
		}

		/**
		 * Adds the setting link to the editor control.
		 *
		 * @param string $output Editor's HTML markup.
		 */
		public function add_editor_setting_link( $output = '' ) {
			return preg_replace( '/<textarea/', '<textarea ' . $this->get_link(), $output, 1 );
		}

	}

}
