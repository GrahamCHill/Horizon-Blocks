<?php
/**
 * Theme admin settings.
 *
 * @package HorizonBlocks
 */

if ( ! function_exists( 'horizon_blocks_get_theme_options' ) ) {
	/**
	 * Returns stored theme options merged with defaults.
	 */
	function horizon_blocks_get_theme_options(): array {
		$defaults = array(
			'organization_name'   => '',
			'contact_email'       => '',
			'contact_phone'       => '',
			'contact_address'     => '',
			'default_social_image' => '',
			'header_cta_label'    => '',
			'header_cta_url'      => '',
			'footer_copy'         => '',
			'enable_breadcrumbs'  => 1,
		);

		$options = get_option( 'horizon_blocks_theme_options', array() );

		if ( ! is_array( $options ) ) {
			$options = array();
		}

		return wp_parse_args( $options, $defaults );
	}
}

if ( ! function_exists( 'horizon_blocks_register_theme_settings' ) ) {
	/**
	 * Registers the theme settings group and fields.
	 */
	function horizon_blocks_register_theme_settings(): void {
		register_setting(
			'horizon_blocks_theme_options',
			'horizon_blocks_theme_options',
			array(
				'type'              => 'array',
				'sanitize_callback' => 'horizon_blocks_sanitize_theme_options',
				'default'           => horizon_blocks_get_theme_options(),
			)
		);

		$sections = array(
			'horizon_blocks_brand_section'   => array(
				'title'  => __( 'Brand and Contact', 'horizon-blocks' ),
				'fields' => array(
					'organization_name' => __( 'Organization Name', 'horizon-blocks' ),
					'contact_email'     => __( 'Contact Email', 'horizon-blocks' ),
					'contact_phone'     => __( 'Contact Phone', 'horizon-blocks' ),
					'contact_address'   => __( 'Contact Address', 'horizon-blocks' ),
				),
			),
			'horizon_blocks_social_section'  => array(
				'title'  => __( 'Sharing and Header', 'horizon-blocks' ),
				'fields' => array(
					'default_social_image' => __( 'Default Social Image', 'horizon-blocks' ),
					'header_cta_label'     => __( 'Header CTA Label', 'horizon-blocks' ),
					'header_cta_url'       => __( 'Header CTA URL', 'horizon-blocks' ),
				),
			),
			'horizon_blocks_display_section' => array(
				'title'  => __( 'Display', 'horizon-blocks' ),
				'fields' => array(
					'footer_copy'        => __( 'Footer Copy', 'horizon-blocks' ),
					'enable_breadcrumbs' => __( 'Enable Breadcrumbs', 'horizon-blocks' ),
				),
			),
		);

		foreach ( $sections as $section_id => $section ) {
			add_settings_section(
				$section_id,
				$section['title'],
				'__return_false',
				'horizon-blocks-settings'
			);

			foreach ( $section['fields'] as $field => $label ) {
				add_settings_field(
					$field,
					$label,
					'horizon_blocks_render_settings_field',
					'horizon-blocks-settings',
					$section_id,
					array(
						'field' => $field,
						'label' => $label,
					)
				);
			}
		}
	}
}

add_action( 'admin_init', 'horizon_blocks_register_theme_settings' );

if ( ! function_exists( 'horizon_blocks_sanitize_theme_options' ) ) {
	/**
	 * Sanitizes the theme options.
	 *
	 * @param array<string, mixed> $input Raw settings input.
	 */
	function horizon_blocks_sanitize_theme_options( array $input ): array {
		return array(
			'organization_name'   => isset( $input['organization_name'] ) ? sanitize_text_field( $input['organization_name'] ) : '',
			'contact_email'       => isset( $input['contact_email'] ) ? sanitize_email( $input['contact_email'] ) : '',
			'contact_phone'       => isset( $input['contact_phone'] ) ? sanitize_text_field( $input['contact_phone'] ) : '',
			'contact_address'     => isset( $input['contact_address'] ) ? sanitize_textarea_field( $input['contact_address'] ) : '',
			'default_social_image' => isset( $input['default_social_image'] ) ? esc_url_raw( $input['default_social_image'] ) : '',
			'header_cta_label'    => isset( $input['header_cta_label'] ) ? sanitize_text_field( $input['header_cta_label'] ) : '',
			'header_cta_url'      => isset( $input['header_cta_url'] ) ? esc_url_raw( $input['header_cta_url'] ) : '',
			'footer_copy'         => isset( $input['footer_copy'] ) ? sanitize_text_field( $input['footer_copy'] ) : '',
			'enable_breadcrumbs'  => empty( $input['enable_breadcrumbs'] ) ? 0 : 1,
		);
	}
}

if ( ! function_exists( 'horizon_blocks_render_settings_field' ) ) {
	/**
	 * Renders an individual field.
	 *
	 * @param array<string, string> $args Field configuration.
	 */
	function horizon_blocks_render_settings_field( array $args ): void {
		$options = horizon_blocks_get_theme_options();
		$field   = $args['field'];
		$value   = $options[ $field ] ?? '';

		if ( 'enable_breadcrumbs' === $field ) {
			?>
			<label for="<?php echo esc_attr( $field ); ?>">
				<input
					type="checkbox"
					id="<?php echo esc_attr( $field ); ?>"
					name="horizon_blocks_theme_options[<?php echo esc_attr( $field ); ?>]"
					value="1"
					<?php checked( ! empty( $value ) ); ?>
				/>
				<?php esc_html_e( 'Show breadcrumbs when supported by the active SEO plugin.', 'horizon-blocks' ); ?>
			</label>
			<?php
			return;
		}

		if ( 'contact_address' === $field ) {
			?>
			<textarea
				id="<?php echo esc_attr( $field ); ?>"
				name="horizon_blocks_theme_options[<?php echo esc_attr( $field ); ?>]"
				rows="4"
				class="large-text"
			><?php echo esc_textarea( $value ); ?></textarea>
			<?php
			return;
		}

		if ( 'default_social_image' === $field ) {
			?>
			<div class="hb-media-field">
				<input
					type="url"
					id="<?php echo esc_attr( $field ); ?>"
					name="horizon_blocks_theme_options[<?php echo esc_attr( $field ); ?>]"
					value="<?php echo esc_attr( $value ); ?>"
					class="regular-text hb-media-field__input"
				/>
				<button
					type="button"
					class="button hb-media-field__button"
					data-target="<?php echo esc_attr( $field ); ?>"
				>
					<?php esc_html_e( 'Select image', 'horizon-blocks' ); ?>
				</button>
			</div>
			<p class="description"><?php esc_html_e( 'Used as the fallback Open Graph and Twitter image.', 'horizon-blocks' ); ?></p>
			<?php
			return;
		}

		$type = 'contact_email' === $field ? 'email' : ( 'header_cta_url' === $field ? 'url' : 'text' );
		?>
		<input
			type="<?php echo esc_attr( $type ); ?>"
			id="<?php echo esc_attr( $field ); ?>"
			name="horizon_blocks_theme_options[<?php echo esc_attr( $field ); ?>]"
			value="<?php echo esc_attr( $value ); ?>"
			class="regular-text"
		/>
		<?php
	}
}

if ( ! function_exists( 'horizon_blocks_admin_assets' ) ) {
	/**
	 * Loads admin assets for the theme settings page.
	 *
	 * @param string $hook_suffix Current admin page hook.
	 */
	function horizon_blocks_admin_assets( string $hook_suffix ): void {
		if ( 'appearance_page_horizon-blocks-settings' !== $hook_suffix ) {
			return;
		}

		wp_enqueue_media();
		wp_register_script( 'horizon-blocks-admin', '', array( 'media-editor' ), '1.0.0', true );
		wp_enqueue_script( 'horizon-blocks-admin' );
		wp_add_inline_script(
			'horizon-blocks-admin',
			"(function(){document.addEventListener('click',function(event){const button=event.target.closest('.hb-media-field__button');if(!button){return;}const targetId=button.getAttribute('data-target');const input=document.getElementById(targetId);if(!input){return;}const frame=wp.media({title:'Select image',button:{text:'Use image'},multiple:false,library:{type:'image'}});frame.on('select',function(){const attachment=frame.state().get('selection').first().toJSON();if(attachment&&attachment.url){input.value=attachment.url;}});frame.open();});})();",
			'after'
		);
		wp_register_style( 'horizon-blocks-admin', false, array(), '1.0.0' );
		wp_enqueue_style( 'horizon-blocks-admin' );
		wp_add_inline_style(
			'horizon-blocks-admin',
			'.hb-media-field{display:flex;gap:.75rem;align-items:center}.hb-media-field__input{max-width:28rem}'
		);
	}
}

add_action( 'admin_enqueue_scripts', 'horizon_blocks_admin_assets' );

if ( ! function_exists( 'horizon_blocks_add_theme_settings_page' ) ) {
	/**
	 * Adds the theme settings page to Appearance.
	 */
	function horizon_blocks_add_theme_settings_page(): void {
		add_theme_page(
			__( 'Horizon Theme Settings', 'horizon-blocks' ),
			__( 'Horizon Settings', 'horizon-blocks' ),
			'manage_options',
			'horizon-blocks-settings',
			'horizon_blocks_render_theme_settings_page'
		);
	}
}

add_action( 'admin_menu', 'horizon_blocks_add_theme_settings_page' );

if ( ! function_exists( 'horizon_blocks_render_theme_settings_page' ) ) {
	/**
	 * Renders the theme settings page.
	 */
	function horizon_blocks_render_theme_settings_page(): void {
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Horizon Theme Settings', 'horizon-blocks' ); ?></h1>
			<p><?php esc_html_e( 'Configure shared theme metadata and plugin integration behavior.', 'horizon-blocks' ); ?></p>
			<form action="options.php" method="post">
				<?php
				settings_fields( 'horizon_blocks_theme_options' );
				do_settings_sections( 'horizon-blocks-settings' );
				submit_button();
				?>
			</form>
		</div>
		<?php
	}
}
