<?php
/**
 * Stories Templates
 * 
 * @package Stories
 * @since 1.0.0
 */

/**
 * Cache booster
 */
function get_asset_version($file_path) {
    $full_path = get_template_directory() . $file_path;
    return file_exists($full_path) ? filemtime($full_path) : time();
}

/**
 * Posts styles for home, archive or search
 */
 function posts_styles() {
    if ( is_home() or is_archive() or is_search() ) {

        wp_enqueue_style( 'posts', get_template_directory_uri() . '/assets/css/posts.css', array(), get_asset_version('/assets/css/posts.css'), 'all' );

        if ( is_active_sidebar( 'sidebar-posts' ) ) {
            wp_enqueue_style( 'sidebar', get_template_directory_uri() . '/assets/css/sidebar.css', array(), get_asset_version('/assets/css/posts.css'), 'all' );
            wp_enqueue_style( 'sidebar', get_template_directory_uri() . '/assets/css/sidebar.css', array(), get_asset_version('/assets/css/sidebar.css'), 'all' );
        }
    }
}
add_action( 'wp_enqueue_scripts', 'posts_styles' );

/**
 * Post styles for single or page
 */
function post_styles() {
    if ( is_single() or is_page() ) {

        wp_enqueue_style( 'single', get_template_directory_uri() . '/assets/css/single.css', array(), get_asset_version('/assets/css/single.css'), 'all' );
        
        if ( has_post_thumbnail() == true ) {
            wp_enqueue_style( 'single-thumbnail', get_template_directory_uri() . '/assets/css/single-thumbnail.css', array(), get_asset_version('/assets/css/single-thumbnail.css'), 'all' );
        }
        
        if ( is_active_sidebar( 'sidebar-2' ) ) {
            wp_enqueue_style( 'sidebar', get_template_directory_uri() . '/assets/css/sidebar.css', array(), get_asset_version('/assets/css/sidebar.css'), 'all' );
        }
    }
}
add_action( 'wp_enqueue_scripts', 'post_styles', 20 );