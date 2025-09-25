<?php
/**
 * Stories engine room
 */

// Prevent direct access to this file for security reasons
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Define the theme version as a constant for better performance and clarity
 */
$theme = wp_get_theme( 'stories' );
define( 'STORIES_VERSION', $theme['Version'] );

/**
 * Load core functionalities of the theme
 */
$stories = (object) array(
    'version' => STORIES_VERSION,

    /**
     * Initialize all required components of the theme
     * Each component is loaded only if the corresponding file exists
     */
    'core'              => file_exists( __DIR__ . '/inc/core.php' ) ? require_once __DIR__ . '/inc/core.php' : null,
    'extended'          => file_exists( __DIR__ . '/inc/extended.php' ) ? require_once __DIR__ . '/inc/extended.php' : null,
    'custom-widgets'    => file_exists( __DIR__ . '/inc/custom-widgets.php' ) ? require_once __DIR__ . '/inc/custom-widgets.php' : null,
    'templates'         => file_exists( __DIR__ . '/inc/templates.php' ) ? require_once __DIR__ . '/inc/templates.php' : null,
);