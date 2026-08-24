<?php
/**
 * Stories Custom Blocks
 *
 * Registers custom Gutenberg block categories, patterns, and custom block definitions.
 *
 * @package Stories
 * @subpackage Inc
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register custom block category for the Stories theme.
 *
 * @param array $categories Array of block categories.
 * @return array Filtered block categories.
 */
function stories_register_block_category( $categories ) {
	return array_merge(
		$categories,
		array(
			array(
				'slug'  => 'stories-blocks',
				'title' => __( 'Stories Theme Blocks', 'stories' ),
				'icon'  => 'book-alt',
			),
		)
	);
}
add_filter( 'block_categories_all', 'stories_register_block_category', 10, 1 );

/**
 * Register theme block patterns.
 */
function stories_register_block_patterns() {
	if ( function_exists( 'register_block_pattern_category' ) ) {
		register_block_pattern_category(
			'stories-patterns',
			array( 'label' => __( 'Stories Patterns', 'stories' ) )
		);
	}
}
add_action( 'init', 'stories_register_block_patterns' );

/**
 * Hook to register custom Gutenberg / ACF blocks when ACF Pro is present.
 */
function stories_register_acf_blocks() {
	if ( function_exists( 'acf_register_block_type' ) ) {
		acf_register_block_type(
			array(
				'name'            => 'story-hero',
				'title'           => __( 'Story Hero', 'stories' ),
				'description'     => __( 'A custom hero section for stories.', 'stories' ),
				'render_template' => 'template-parts/blocks/story-hero.php',
				'category'        => 'stories-blocks',
				'icon'            => 'format-image',
				'keywords'        => array( 'hero', 'story', 'header' ),
			)
		);
	}
}
add_action( 'acf/init', 'stories_register_acf_blocks' );
