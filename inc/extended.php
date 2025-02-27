<?php
/**
 * Stories extended functions
 * 
 * @package Stories
 * @since 1.0.0
 */

/**
 * Load parts in header
 */
function load_parts_header() {
    
    // Estilos globales
    wp_register_style( 'global', get_template_directory_uri() . '/style.css', '', 1, 'all' ); 
    wp_enqueue_style( 'global' );
     
}
add_action( 'wp_enqueue_scripts', 'load_parts_header' );

/**
 * Support for svg files
 */
function mime_types( $mimes ) {
    $mimes[ 'svg' ] = 'image/svg+xml';
    return $mimes;
}
add_filter( 'upload_mimes', 'mime_types' );

/**
 * Reduce the excerpt to 20 words
 */
function reduce_excerpt( $limit ) {
    return 20;
}
add_filter( 'excerpt_length', 'reduce_excerpt', 999 );