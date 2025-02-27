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
        wp_enqueue_style( 'custom-forms', get_template_directory_uri() . '/assets/css/forms.css', array(), '1.0.0', 'all' );

        if ( is_active_sidebar( 'sidebar-posts' ) ) {
            wp_enqueue_style( 'sidebar', get_template_directory_uri() . '/assets/css/sidebar.css', array(), '1.0.0', 'all' );
        }
    }
 }
 add_action( 'wp_enqueue_scripts', 'posts_styles' );