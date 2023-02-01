<?php
/**
 * Portfolio Permalink Settings
 *
 * @package    Evie_XT
 * @subpackage Modules/Portfolio/Admin
 * @version    1.0.0
 */

/**
 * Portfolio Permalink Settings class.
 */
class Evie_Portfolio_Permalink_Settings extends Flextension_Module_Admin {

	/**
	 * Permalink settings.
	 *
	 * @var array An array object of the permalinks settings.
	 */
	private $permalinks = array();

	/**
	 * Initializes the module.
	 */
	public function initialize() {

		// If it is on a permalink settings page, register settings.
		if ( $this->is_settings_page( 'permalink' ) ) {
			$this->save_permalink_settings();
			add_action( 'admin_init', array( $this, 'register_settings' ) );

			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		}

	}

	/**
	 * Registers a new settings section under Settings.
	 */
	public function register_settings() {

		$this->permalinks = evie_portfolio_permalink_structure();

		// Register a new settings section on Settings -> Permalinks.
		add_settings_section(
			'evie_portfolio_permalinks_section',
			esc_html__( 'Project permalinks', 'evie-xt' ),
			array( $this, 'settings_section' ),
			'permalink'
		);

		add_settings_field(
			'evie_portfolio_category_base',
			esc_html__( 'Project category base', 'evie-xt' ),
			array( $this, 'settings_field' ),
			'permalink',
			'optional',
			array(
				'name'        => 'evie_portfolio_category_base',
				'value'       => $this->permalinks['category_base'],
				'placeholder' => 'project-category',
			)
		);

		$attributes = evie_portfolio_attribute_taxonomies();

		if ( ! empty( $attributes ) ) {
			foreach ( $attributes as $name => $labels ) {

				$label = strtolower( $labels['singular_name'] );

				add_settings_field(
					"evie_portfolio_{$name}_base",
					sprintf(
						/* translators: %s: Attibute name */
						__( 'Project %s base', 'evie-xt' ),
						$label
					),
					array( $this, 'settings_field' ),
					'permalink',
					'optional',
					array(
						'name'        => "evie_portfolio_{$name}_base",
						'value'       => $this->permalinks[ "{$name}_base" ],
						'placeholder' => "project-{$name}",
					)
				);
			}
		}

		add_settings_field(
			'evie_portfolio_attribute_base',
			esc_html__( 'Project attribute base', 'evie-xt' ),
			array( $this, 'settings_field' ),
			'permalink',
			'optional',
			array(
				'name'        => 'evie_portfolio_attribute_base',
				'value'       => $this->permalinks['attribute_base'],
				'placeholder' => 'project-attribute',
			)
		);

		add_settings_field(
			'evie_portfolio_tag_base',
			esc_html__( 'Project tag base', 'evie-xt' ),
			array( $this, 'settings_field' ),
			'permalink',
			'optional',
			array(
				'name'        => 'evie_portfolio_tag_base',
				'value'       => $this->permalinks['tag_base'],
				'placeholder' => 'project-tag',
			)
		);

	}

	/**
	 * Prints out the settings section.
	 */
	public function settings_section() {

		echo wp_kses_post(
			sprintf(
				/* translators: %s: Home URL */
				__( 'If you like, you may enter custom structures for your project URLs here. For example, using <code>item</code> would make your project links like <code>%s/item/sample-project/</code>.', 'evie-xt' ),
				esc_url( untrailingslashit( home_url() ) )
			)
		);

		$portfolio_page_id   = evie_portfolio_page();
		$portfolio_page_slug = _x( 'portfolio', 'default-slug', 'evie-xt' );
		if ( $portfolio_page_id > 0 ) {
			$portfolio_page_slug = get_page_uri( $portfolio_page_id );
		}
		$portfolio_base      = urldecode( $portfolio_page_slug );
		$project_base        = _x( 'project', 'default-slug', 'evie-xt' );
		$permalink_structure = trailingslashit( $this->permalinks['project_base'] );

		if ( strpos( $permalink_structure, '/' ) !== 0 ) {
			$permalink_structure = '/' . $permalink_structure;
		}

		$structures = array(
			0 => '/' . trailingslashit( $project_base ),
			1 => '/' . trailingslashit( $portfolio_base ),
			2 => '/' . trailingslashit( $portfolio_base ) . trailingslashit( '%project_category%' ),
		);
		?>
		<table class="form-table evie-project-permalink-settings">
			<tbody>
				<tr>
					<th><label><input name="evie_project_permalink" type="radio" value="<?php echo esc_attr( $structures[0] ); ?>" <?php checked( $structures[0], $permalink_structure ); ?> /> <?php esc_html_e( 'Default', 'evie-xt' ); ?></label></th>
					<td><code class="default-example"><?php echo esc_html( home_url() ); ?>/?project=sample-project</code> <code class="non-default-example"><?php echo esc_html( home_url() ); ?>/<?php echo esc_html( $project_base ); ?>/sample-project/</code></td>
				</tr>
				<?php if ( $portfolio_page_id > 0 ) : ?>
					<tr>
						<th><label><input name="evie_project_permalink" type="radio" value="<?php echo esc_attr( $structures[1] ); ?>" <?php checked( $structures[1], $permalink_structure ); ?> /> <?php esc_html_e( 'Portfolio base', 'evie-xt' ); ?></label></th>
						<td><code><?php echo esc_html( home_url() ); ?>/<?php echo esc_html( $portfolio_base ); ?>/sample-project/</code></td>
					</tr>
					<tr>
						<th><label><input name="evie_project_permalink" type="radio" value="<?php echo esc_attr( $structures[2] ); ?>" <?php checked( $structures[2], $permalink_structure ); ?> /> <?php esc_html_e( 'Category base', 'evie-xt' ); ?></label></th>
						<td><code><?php echo esc_html( home_url() ); ?>/<?php echo esc_html( $portfolio_base ); ?>/project-category/sample-project/</code></td>
					</tr>
				<?php endif; ?>
				<tr>
					<th>
						<label><input name="evie_project_permalink" id="evie-custom-permalink" type="radio" value="custom" <?php checked( in_array( $permalink_structure, $structures, true ), false ); ?> />
						<?php esc_html_e( 'Custom base', 'evie-xt' ); ?></label></th>
					<td>
						<code><?php echo esc_html( home_url() ); ?></code> <input name="evie_project_permalink_structure" id="evie-project-permalink-structure" type="text" value="<?php echo esc_attr( $permalink_structure ); ?>" class="regular-text code"> <code>sample-project</code> <span class="description"><?php esc_html_e( 'Enter a custom base to use. A base must be set or WordPress will use default instead.', 'evie-xt' ); ?></span>
					</td>
				</tr>
			</tbody>
		</table>
		<?php

		wp_nonce_field( 'evie_permalinks', 'evie_permalinks_nonce' );
	}

	/**
	 * Saves the portfolio permalink settings.
	 */
	public function save_permalink_settings() {

		// We need to save the options ourselves; settings api does not trigger save for the permalinks page.
		if ( isset( $_POST['evie_project_permalink'], $_POST['evie_permalinks_nonce'] ) && wp_verify_nonce( sanitize_key( $_POST['evie_permalinks_nonce'] ), 'evie_permalinks' ) ) {

			if ( isset( $_POST['evie_project_category_base'] ) ) {
				$this->permalinks['category_base'] = $this->sanitize_permalink( wp_unslash( $_POST['evie_project_category_base'] ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			}

			$attributes = evie_portfolio_attribute_taxonomies();

			if ( ! empty( $attributes ) ) {
				foreach ( $attributes as $name => $labels ) {
					$this->permalinks[ "{$name}_base" ] = isset( $_POST[ "evie_project_{$name}_base" ] ) ? $this->sanitize_permalink( wp_unslash( $_POST[ "evie_project_{$name}_base" ] ) ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
				}
			}

			if ( isset( $_POST['evie_project_attribute_base'] ) ) {
				$this->permalinks['attribute_base'] = $this->sanitize_permalink( wp_unslash( $_POST['evie_project_attribute_base'] ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			}

			if ( isset( $_POST['evie_project_tag_base'] ) ) {
				$this->permalinks['tag_base'] = $this->sanitize_permalink( wp_unslash( $_POST['evie_project_tag_base'] ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			}

			// Project permalink base.
			$project_base = isset( $_POST['evie_project_permalink'] ) ? sanitize_text_field( wp_unslash( $_POST['evie_project_permalink'] ) ) : '';

			if ( 'custom' === $project_base ) {
				if ( isset( $_POST['evie_project_permalink_structure'] ) ) {
					$project_base = preg_replace( '#/+#', '/', '/' . str_replace( '#', '', $this->sanitize_permalink( wp_unslash( $_POST['evie_project_permalink_structure'] ) ) ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
				} else {
					$project_base = '/';
				}

				// This is an invalid base structure and breaks pages.
				if ( '/%project_category%/' === trailingslashit( $project_base ) ) {
					$project_base = '/' . _x( 'project', 'slug', 'evie-xt' ) . $project_base;
				}
			} elseif ( empty( $project_base ) ) {
				$project_base = _x( 'project', 'slug', 'evie-xt' );
			}

			$this->permalinks['project_base'] = $this->sanitize_permalink( $project_base );

			// Shop base may require verbose page rules if nesting pages.
			$portfolio_page_id   = evie_portfolio_page();
			$portfolio_page_slug = _x( 'portfolio', 'default-slug', 'evie-xt' );
			if ( $portfolio_page_id > 0 ) {
				$portfolio_page_slug = get_page_uri( $portfolio_page_id );
			}

			if ( $portfolio_page_id > 0 && stristr( trim( $project_base, '/' ), $portfolio_page_slug ) ) {
				$this->permalinks['use_verbose_page_rules'] = true;
			}

			update_option( 'evie_portfolio_permalinks', $this->permalinks );
		}
	}

	/**
	 * Sanitize permalink values before insertion into DB.
	 *
	 * Cannot use wc_clean because it sometimes strips % chars and breaks the user's setting.
	 *
	 * @param  string $value The permalink to sanitize.
	 * @return string The sanitized permalink.
	 */
	public function sanitize_permalink( $value ) {
		$value = esc_url_raw( trim( $value ) );
		$value = str_replace( 'http://', '', $value );
		return untrailingslashit( $value );
	}

	/**
	 * Enqueues the scripts and stylesheets for the admin page.
	 */
	public function admin_enqueue_scripts() {
		wp_enqueue_script( 'evie-portfolio-permalink-settings', plugins_url( 'js/permalink-settings.js', __FILE__ ), array(), EVIE_XT_VERSION, true );
	}

}
