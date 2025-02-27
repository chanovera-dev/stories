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
    }
 }
 add_action( 'wp_enqueue_scripts', 'posts_styles' );