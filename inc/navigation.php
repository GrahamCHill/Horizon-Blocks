<?php
/**
 * Shared navigation rendering.
 *
 * @package HorizonBlocks
 */

if ( ! function_exists( 'horizon_blocks_render_primary_menu_shortcode' ) ) {
	/**
	 * Renders desktop and mobile navigation from the same WordPress menu location.
	 */
	function horizon_blocks_render_primary_menu_shortcode(): string {
		$has_menu = has_nav_menu( 'primary' );

		if ( ! $has_menu ) {
			return '';
		}

		$desktop_menu = wp_nav_menu(
			array(
				'theme_location' => 'primary',
				'container'      => false,
				'menu_class'     => 'hb-menu hb-menu--desktop',
				'fallback_cb'    => false,
				'echo'           => false,
				'depth'          => 2,
			)
		);

		$mobile_menu = wp_nav_menu(
			array(
				'theme_location' => 'primary',
				'container'      => false,
				'menu_class'     => 'hb-menu hb-menu--mobile',
				'fallback_cb'    => false,
				'echo'           => false,
				'depth'          => 2,
			)
		);

		if ( ! $desktop_menu || ! $mobile_menu ) {
			return '';
		}

		ob_start();
		?>
		<nav class="hb-shared-nav" aria-label="<?php esc_attr_e( 'Primary navigation', 'horizon-blocks' ); ?>">
			<div class="hb-shared-nav__desktop">
				<?php echo $desktop_menu; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
			<div class="hb-shared-nav__mobile">
				<button
					class="hb-menu-toggle"
					type="button"
					aria-expanded="false"
					aria-controls="hb-mobile-menu-panel"
				>
					<span class="hb-menu-toggle__label"><?php esc_html_e( 'Menu', 'horizon-blocks' ); ?></span>
				</button>
				<div class="hb-mobile-menu-panel" id="hb-mobile-menu-panel" hidden>
					<?php echo $mobile_menu; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
			</div>
		</nav>
		<?php
		return (string) ob_get_clean();
	}
}

add_shortcode( 'horizon_primary_menu', 'horizon_blocks_render_primary_menu_shortcode' );

if ( ! function_exists( 'horizon_blocks_render_footer_menus_shortcode' ) ) {
	/**
	 * Renders optional footer menu columns from registered menu locations.
	 */
	function horizon_blocks_render_footer_menus_shortcode(): string {
		$menu_locations = array(
			'footer_company'   => __( 'Company', 'horizon-blocks' ),
			'footer_resources' => __( 'Resources', 'horizon-blocks' ),
			'footer_legal'     => __( 'Legal', 'horizon-blocks' ),
		);
		$columns = array();

		foreach ( $menu_locations as $location => $label ) {
			if ( ! has_nav_menu( $location ) ) {
				continue;
			}

			$menu = wp_nav_menu(
				array(
					'theme_location' => $location,
					'container'      => false,
					'menu_class'     => 'hb-footer-menu',
					'fallback_cb'    => false,
					'echo'           => false,
					'depth'          => 1,
				)
			);

			if ( ! $menu ) {
				continue;
			}

			$columns[] = sprintf(
				'<div class="hb-footer-menu-group"><h4 class="hb-footer-menu-group__title">%s</h4>%s</div>',
				esc_html( $label ),
				$menu
			);
		}

		if ( empty( $columns ) ) {
			return '';
		}

		return sprintf(
			'<div class="hb-footer-menus">%s</div>',
			implode( '', $columns )
		);
	}
}

add_shortcode( 'horizon_footer_menus', 'horizon_blocks_render_footer_menus_shortcode' );
