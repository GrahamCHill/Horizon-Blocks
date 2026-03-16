<?php
/**
 * Pattern registration helpers.
 *
 * @package HorizonBlocks
 */

if ( ! function_exists( 'horizon_blocks_register_pattern_categories' ) ) {
	/**
	 * Registers theme-specific pattern categories.
	 */
	function horizon_blocks_register_pattern_categories(): void {
		if ( function_exists( 'register_block_pattern_category' ) ) {
			register_block_pattern_category(
				'horizon-blocks-pages',
				array(
					'label' => __( 'Horizon Blocks Pages', 'horizon-blocks' ),
				)
			);

			register_block_pattern_category(
				'horizon-blocks-sections',
				array(
					'label' => __( 'Horizon Blocks Sections', 'horizon-blocks' ),
				)
			);
		}
	}
}

add_action( 'init', 'horizon_blocks_register_pattern_categories' );
