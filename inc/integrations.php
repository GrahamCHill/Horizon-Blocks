<?php
/**
 * Third-party plugin integrations.
 *
 * @package HorizonBlocks
 */

if ( ! function_exists( 'horizon_blocks_register_plugin_support' ) ) {
	/**
	 * Registers plugin supports exposed by the theme.
	 */
	function horizon_blocks_register_plugin_support(): void {
		add_theme_support( 'woocommerce' );
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );
		add_theme_support( 'elementor' );
		add_theme_support( 'yoast-seo-breadcrumbs' );
	}
}

add_action( 'after_setup_theme', 'horizon_blocks_register_plugin_support', 20 );

if ( ! function_exists( 'horizon_blocks_register_elementor_locations' ) ) {
	/**
	 * Registers theme locations for Elementor Pro.
	 *
	 * @param object $manager Elementor theme manager.
	 */
	function horizon_blocks_register_elementor_locations( $manager ): void {
		if ( method_exists( $manager, 'register_all_core_location' ) ) {
			$manager->register_all_core_location();
		}
	}
}

add_action( 'elementor/theme/register_locations', 'horizon_blocks_register_elementor_locations' );

if ( ! function_exists( 'horizon_blocks_disable_elementor_default_styles' ) ) {
	/**
	 * Lets the theme own typography and colors when Elementor is active.
	 */
	function horizon_blocks_disable_elementor_default_styles(): void {
		update_option( 'elementor_disable_color_schemes', 'yes' );
		update_option( 'elementor_disable_typography_schemes', 'yes' );
	}
}

add_action( 'after_switch_theme', 'horizon_blocks_disable_elementor_default_styles' );

if ( ! function_exists( 'horizon_blocks_woocommerce_enqueue_styles' ) ) {
	/**
	 * Keeps WooCommerce from forcing legacy stylesheet assumptions.
	 *
	 * @return array<string, string>
	 */
	function horizon_blocks_woocommerce_enqueue_styles(): array {
		return array();
	}
}

add_filter( 'woocommerce_enqueue_styles', 'horizon_blocks_woocommerce_enqueue_styles' );

if ( ! function_exists( 'horizon_blocks_woocommerce_supports' ) ) {
	/**
	 * Adds WooCommerce image sizing and product grid support.
	 */
	function horizon_blocks_woocommerce_supports(): void {
		add_theme_support(
			'woocommerce',
			array(
				'thumbnail_image_width' => 640,
				'single_image_width'    => 1080,
				'product_grid'          => array(
					'default_rows'    => 3,
					'min_rows'        => 1,
					'max_rows'        => 6,
					'default_columns' => 3,
					'min_columns'     => 1,
					'max_columns'     => 4,
				),
			)
		);
	}
}

add_action( 'after_setup_theme', 'horizon_blocks_woocommerce_supports', 25 );

if ( ! function_exists( 'horizon_blocks_woocommerce_fragments' ) ) {
	/**
	 * Keeps the header cart count live.
	 *
	 * @param array<string, string> $fragments Existing fragments.
	 * @return array<string, string>
	 */
	function horizon_blocks_woocommerce_fragments( array $fragments ): array {
		if ( ! function_exists( 'horizon_blocks_render_header_cart' ) ) {
			return $fragments;
		}

		$fragments['a.hb-header-cart'] = horizon_blocks_render_header_cart();

		return $fragments;
	}
}

add_filter( 'woocommerce_add_to_cart_fragments', 'horizon_blocks_woocommerce_fragments' );

if ( ! function_exists( 'horizon_blocks_shop_columns' ) ) {
	/**
	 * Sets a stable product grid column count.
	 */
	function horizon_blocks_shop_columns(): int {
		return 3;
	}
}

add_filter( 'loop_shop_columns', 'horizon_blocks_shop_columns' );

if ( ! function_exists( 'horizon_blocks_sale_badge' ) ) {
	/**
	 * Simplifies the sale badge markup for theme styling.
	 */
	function horizon_blocks_sale_badge( string $html ): string {
		return '<span class="onsale hb-onsale">' . esc_html__( 'Sale', 'horizon-blocks' ) . '</span>';
	}
}

add_filter( 'woocommerce_sale_flash', 'horizon_blocks_sale_badge' );

if ( ! function_exists( 'horizon_blocks_supports_breadcrumbs' ) ) {
	/**
	 * Checks whether breadcrumbs should be shown.
	 */
	function horizon_blocks_supports_breadcrumbs(): bool {
		$options = function_exists( 'horizon_blocks_get_theme_options' ) ? horizon_blocks_get_theme_options() : array();

		return ! empty( $options['enable_breadcrumbs'] );
	}
}

if ( ! function_exists( 'horizon_blocks_render_breadcrumbs_shortcode' ) ) {
	/**
	 * Renders breadcrumbs from supported SEO plugins.
	 */
	function horizon_blocks_render_breadcrumbs_shortcode(): string {
		if ( ! horizon_blocks_supports_breadcrumbs() ) {
			return '';
		}

		if ( function_exists( 'yoast_breadcrumb' ) ) {
			return yoast_breadcrumb( '<nav class="hb-breadcrumbs" aria-label="Breadcrumbs">', '</nav>', false );
		}

		return '';
	}
}

add_shortcode( 'horizon_breadcrumbs', 'horizon_blocks_render_breadcrumbs_shortcode' );

if ( ! function_exists( 'horizon_blocks_render_footer_copy_shortcode' ) ) {
	/**
	 * Renders footer copy from theme settings with a sensible fallback.
	 */
	function horizon_blocks_render_footer_copy_shortcode(): string {
		$options = function_exists( 'horizon_blocks_get_theme_options' ) ? horizon_blocks_get_theme_options() : array();
		$text    = ! empty( $options['footer_copy'] ) ? $options['footer_copy'] : __( 'Powered by WordPress and Horizon Blocks.', 'horizon-blocks' );

		return sprintf(
			'<p class="has-small-font-size">%s</p>',
			esc_html( $text )
		);
	}
}

add_shortcode( 'horizon_footer_copy', 'horizon_blocks_render_footer_copy_shortcode' );

if ( ! function_exists( 'horizon_blocks_contact_form_7_autop' ) ) {
	/**
	 * Keeps Contact Form 7 output predictable inside block layouts.
	 */
	function horizon_blocks_contact_form_7_autop(): bool {
		return false;
	}
}

add_filter( 'wpcf7_autop_or_not', 'horizon_blocks_contact_form_7_autop' );

if ( ! function_exists( 'horizon_blocks_contact_form_7_form_class' ) ) {
	/**
	 * Adds theme-specific classes to Contact Form 7 forms.
	 *
	 * @param string $class Current form class string.
	 */
	function horizon_blocks_contact_form_7_form_class( string $class ): string {
		$class .= ' hb-contact-form';

		return trim( $class );
	}
}

add_filter( 'wpcf7_form_class_attr', 'horizon_blocks_contact_form_7_form_class' );
