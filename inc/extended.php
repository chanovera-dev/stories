<?php
/**
 * Stories extended functions
 * 
 * @package Stories
 * @since 1.0.0
 */

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