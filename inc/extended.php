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

/**
 * Breadcrumbs
 */
function wp_breadcrumbs() {
    $separator = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-right" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708"/></svg>';
    $icon_home = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12.76c0-1.358 0-2.037.274-2.634c.275-.597.79-1.038 1.821-1.922l1-.857C9.96 5.75 10.89 4.95 12 4.95s2.041.799 3.905 2.396l1 .857c1.03.884 1.546 1.325 1.82 1.922c.275.597.275 1.276.275 2.634V17c0 1.886 0 2.828-.586 3.414S16.886 21 15 21H9c-1.886 0-2.828 0-3.414-.586S5 18.886 5 17z"/><path stroke-linecap="round" stroke-linejoin="round" d="M14.5 21v-5a1 1 0 0 0-1-1h-3a1 1 0 0 0-1 1v5"/></g></svg>';
    $home = 'Inicio';
    $showCurrent = 1;
    $showOnHome = 0;
    $current = '';

    global $post;
    $homeLink = get_bloginfo('url');
    echo '<a href="' . $homeLink . '">' . $icon_home . $home . '</a>' . $separator;

    if (is_category()) {
        $thisCat = get_category(get_query_var('cat'), false);
        if ($thisCat->parent != 0) {
            $cats = get_category_parents($thisCat->parent, TRUE, $separator);
            if ($showCurrent == 0) $cats = preg_replace("#^(.+)$separator$#", "$1", $cats);
            echo $cats;
        }
        if ($showCurrent == 1) echo $current . ' ' . single_cat_title('', false);
    } elseif (is_home()) {
        $paged = get_query_var('paged') ? get_query_var('paged') : 1;
        echo $current . ' ' . ' Página ' . $paged;
    }elseif (is_search()) {
        echo $current . ' ' . get_search_query();
    } elseif (is_day()) {
        echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a>' . $separator;
        echo '<a href="' . get_month_link(get_the_time('Y'), get_the_time('m')) . '">' . get_the_time('F') . '</a>' . $separator;
        echo get_the_time('d') . $separator;
        echo $current . ' ' . get_the_time('l');
    } elseif (is_month()) {
        echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a>' . $separator;
        echo $current . ' ' . get_the_time('F');
    } elseif (is_year()) {
        echo $current . ' ' . get_the_time('Y');
    } elseif (is_single() && !is_attachment()) {
        if (get_post_type() != 'post') {
            $post_type = get_post_type_object(get_post_type());
            $slug = $post_type->rewrite;
            echo '<a href="' . $homeLink . '/' . $slug['slug'] . '/">' . $post_type->labels->singular_name . '</a>' . $separator;
            if ($showCurrent == 1) echo $current . ' ';
        } else
        {
            $cat = get_the_category();
            $cat = $cat[0];
            $cats = get_category_parents($cat, TRUE, $separator);
            if ($showCurrent == 0) $cats = preg_replace("#^(.+)$separator$#", "$1", $cats);
            echo $cats;
            echo $current . ' ';
        }
    } elseif (!is_single() && !is_page() && get_post_type() != 'post' && !is_404()) {}
}