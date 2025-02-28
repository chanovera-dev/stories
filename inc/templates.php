<?php
/**
 * Stories Templates
 * 
 * @package Stories
 * @since 1.0.0
 */

 function posts_styles() {
    if ( is_home() or is_archive() or is_search() ) {

        wp_enqueue_style( 'posts', get_template_directory_uri() . '/assets/css/posts.css', array(), '1.0.0', 'all' );

        if ( is_active_sidebar( 'sidebar-posts' ) ) {
            wp_enqueue_style( 'sidebar', get_template_directory_uri() . '/assets/css/sidebar.css', array(), '1.0.0', 'all' );
        }
    }
}
add_action( 'wp_enqueue_scripts', 'posts_styles' );

function post_styles() {
    if ( is_single() or is_page() ) {
        wp_enqueue_style( 'single', get_template_directory_uri() . '/assets/css/single.css', array(), '1.0.0', 'all' );

        if ( is_active_sidebar( 'sidebar-2' ) ) {
            wp_enqueue_style( 'sidebar', get_template_directory_uri() . '/assets/css/sidebar.css', array(), '1.0.0', 'all' );
        }
    }
}
add_action( 'wp_enqueue_scripts', 'post_styles', 20 );