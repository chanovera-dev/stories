<?php
/**
 * Stories Templates
 * 
 * @package Stories
 * @since 1.0.0
 */

/**
 * Posts styles for home, archive or search
 */
function posts_styles() {
    if ( is_home() or is_archive() or is_search() ) {

        function unload_parts_header() {
            wp_dequeue_style( 'wp-block-library' );
        }
        add_action( 'wp_enqueue_scripts', 'unload_parts_header', 100 );

        wp_enqueue_style( 'posts', get_template_directory_uri() . '/assets/css/posts.css', array(), get_asset_version('/assets/css/posts.css'), 'all' );
        wp_enqueue_style( 'breadcrumbs', get_template_directory_uri() . '/assets/css/breadcrumbs.css', array(), get_asset_version('/assets/css/breadcrumbs.css'), 'all' );

        if ( is_active_sidebar( 'sidebar-posts' ) ) {
            wp_enqueue_style( 'sidebar-posts', get_template_directory_uri() . '/assets/css/sidebar.css', array(), get_asset_version('/assets/css/sidebar.css'), 'all' );
        }
    }
}
add_action( 'wp_enqueue_scripts', 'posts_styles' );

/**
 * Post styles for "Detras del espejo"
 */
function posts_detras_styles() {
    if ( is_page_template( 'archive-detras-del-espejo.php' ) ) {
        wp_enqueue_style( 'custom-breadcrumbs', get_template_directory_uri() . '/assets/css/custom-breadcrumbs.css', array(), get_asset_version('/assets/css/custom-breadcrumbs.css'), 'all' );
        wp_enqueue_style( 'posts', get_template_directory_uri() . '/assets/css/posts.css', array(), get_asset_version('/assets/css/posts.css'), 'all' );
    }
}
add_action( 'wp_enqueue_scripts', 'posts_detras_styles' );

/**
 * Post styles for single or page
 */
function post_styles() {
    if ( is_single() or is_page() ) {

        wp_enqueue_style( 'breadcrumbs', get_template_directory_uri() . '/assets/css/breadcrumbs.css', array(), get_asset_version('/assets/css/breadcrumbs.css'), 'all' );
        wp_enqueue_style( 'single', get_template_directory_uri() . '/assets/css/single.css', array(), get_asset_version('/assets/css/single.css'), 'all' );

        if ( is_single() ) {
            wp_enqueue_style( 'posts', get_template_directory_uri() . '/assets/css/posts.css', array(), get_asset_version('/assets/css/posts.css'), 'all' );

            if ( comments_open() ) {
                wp_enqueue_style( 'forms', get_template_directory_uri() . '/assets/css/posts.css', array(), get_asset_version('/assets/css/forms.css'), 'all' );
            }
        }
        
        if ( has_post_thumbnail() == true ) {
            wp_enqueue_style( 'single-thumbnail', get_template_directory_uri() . '/assets/css/single-thumbnail.css', array(), get_asset_version('/assets/css/single-thumbnail.css'), 'all' );
        }
        
        if ( is_active_sidebar( 'sidebar-2' ) ) {
            wp_enqueue_style( 'sidebar', get_template_directory_uri() . '/assets/css/sidebar.css', array(), get_asset_version('/assets/css/sidebar.css'), 'all' );
        }
    }
}
add_action( 'wp_enqueue_scripts', 'post_styles', 20 );

/**
 * 404 styles for error page
 */
function page404_styles() {
    if ( is_404() ) {
        wp_enqueue_style( 'error404-styles', get_template_directory_uri() . '/assets/css/error404.css', array(), get_asset_version('/assets/css/error404.css'), 'all' );
    }
}
add_action( 'wp_enqueue_scripts', 'page404_styles' );