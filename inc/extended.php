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

/**
 * Custom output un the_category() function
 */
function custom_the_category() {
    // Obtener las categorías
    $categories = get_the_category();
    $output = '<ul class="post-categories">';

    if ($categories) {
        foreach ($categories as $category) {
            // Crear el SVG
            $svg_icon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bookmark" viewBox="0 0 16 16"><path d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v13.5a.5.5 0 0 1-.777.416L8 13.101l-5.223 2.815A.5.5 0 0 1 2 15.5zm2-1a1 1 0 0 0-1 1v12.566l4.723-2.482a.5.5 0 0 1 .554 0L13 14.566V2a1 1 0 0 0-1-1z"/></svg>';

            // Crear el enlace a la categoría
            $category_link = esc_url(get_category_link($category->term_id));
            $category_name = esc_html($category->name);

            // Formatear la salida
            $output .= '<li class="category-item"><a href="' . $category_link . '">' . $svg_icon . $category_name . '</a></li>';
        }
    }

    $output .= '</ul>';
    return $output;
}
add_filter('the_category', 'custom_the_category');

/**
 * Custom output for get_te_tags() function
 */
function custom_modify_tag_links($links) {
    foreach ($links as &$link) {
        $svg_icon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-tag" viewBox="0 0 16 16">
            <path d="M6 4.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m-1 0a.5.5 0 1 0-1 0 .5.5 0 0 0 1 0"/>
            <path d="M2 1h4.586a1 1 0 0 1 .707.293l7 7a1 1 0 0 1 0 1.414l-4.586 4.586a1 1 0 0 1-1.414 0l-7-7A1 1 0 0 1 1 6.586V2a1 1 0 0 1 1-1m0 5.586 7 7L13.586 9l-7-7H2z"/>
        </svg>';

        // Inserta el SVG dentro del enlace, justo antes del texto de la etiqueta
        $link = preg_replace('/(<a.*?>)(.*?)(<\/a>)/', '$1' . $svg_icon . ' $2$3', $link);
    }
    return $links;
}
add_filter('term_links-post_tag', 'custom_modify_tag_links');
