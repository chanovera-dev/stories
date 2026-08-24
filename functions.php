<?php
/**
 * Stories theme functions and definitions
 *
 * @package Stories
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Define Theme Constants
 */
define( 'STORIES_VERSION', '1.0.4' );
define( 'STORIES_DIR', get_template_directory() );
define( 'STORIES_URI', get_template_directory_uri() );

/**
 * Require modular theme files from the inc/ directory
 */
$stories_includes = array(
	'/inc/core.php',          // Core functions: theme support, scripts/styles enqueue, nav menus.
	'/inc/extended.php',      // Extended functions: custom tweaks, filters, extended features.
	'/inc/custom-blocks.php', // Custom Gutenberg blocks registration and category setup.
	'/inc/icons.php',         // SVG icon helper functions.
	'/inc/colors.php',        // Color schemes engine and dynamic palette styles.
	'/inc/templates.php',     // Custom template tags and helper functions.
	'/inc/wp-panel.php',      // WordPress admin theme settings panel.
	'/inc/ajax.php',          // AJAX handlers and script localization.
);

foreach ( $stories_includes as $file ) {
	$filepath = STORIES_DIR . $file;
	if ( file_exists( $filepath ) ) {
		require_once $filepath;
	}
}
