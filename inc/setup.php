<?php
/**
 * Theme setup hooks.
 *
 * @package HorizonBlocks
 */

if ( ! function_exists( 'horizon_blocks_setup' ) ) {
	/**
	 * Registers theme support and core settings.
	 */
	function horizon_blocks_setup(): void {
		add_theme_support( 'wp-block-styles' );
		add_theme_support( 'responsive-embeds' );
		add_theme_support( 'editor-styles' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption', 'style', 'script' ) );
		add_theme_support( 'custom-spacing' );
		add_theme_support( 'custom-line-height' );
		add_theme_support( 'custom-units' );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'title-tag' );
		add_editor_style( 'assets/css/main.css' );
		add_post_type_support( 'page', 'excerpt' );

		register_nav_menus(
			array(
				'primary' => __( 'Primary Navigation', 'horizon-blocks' ),
				'footer'  => __( 'Footer Navigation', 'horizon-blocks' ),
			)
		);
	}
}

add_action( 'after_setup_theme', 'horizon_blocks_setup' );
