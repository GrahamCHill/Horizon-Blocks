<?php
/**
 * Front-end assets.
 *
 * @package HorizonBlocks
 */

if ( ! function_exists( 'horizon_blocks_enqueue_assets' ) ) {
	/**
	 * Enqueues compiled CSS and JavaScript when available.
	 */
	function horizon_blocks_enqueue_assets(): void {
		$theme_uri  = get_template_directory_uri();
		$theme_path = get_template_directory();
		$css_file   = '/assets/css/main.css';
		$js_file    = '/assets/js/main.js';

		if ( file_exists( $theme_path . $css_file ) ) {
			wp_enqueue_style(
				'horizon-blocks-main',
				$theme_uri . $css_file,
				array(),
				(string) filemtime( $theme_path . $css_file )
			);
		}

		if ( file_exists( $theme_path . $js_file ) ) {
			wp_enqueue_script(
				'horizon-blocks-main',
				$theme_uri . $js_file,
				array(),
				(string) filemtime( $theme_path . $js_file ),
				true
			);
		}
	}
}

add_action( 'wp_enqueue_scripts', 'horizon_blocks_enqueue_assets' );
