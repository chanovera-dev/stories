<?php
/**
 * Stories custom widgets
 * 
 * @package Stories
 * @since 1.0.0
 */

/**
 * Custom output for category list in sidebar widget
 */
function custom_category_list($output, $args) {
    $categories = get_categories($args);

    $output = '';
    foreach ($categories as $category) {
        // Aquí puedes personalizar el SVG
        $svg_icon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bookmark" viewBox="0 0 16 16"><path d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v13.5a.5.5 0 0 1-.777.416L8 13.101l-5.223 2.815A.5.5 0 0 1 2 15.5zm2-1a1 1 0 0 0-1 1v12.566l4.723-2.482a.5.5 0 0 1 .554 0L13 14.566V2a1 1 0 0 0-1-1z"/></svg>';

        $output .= '<li>';
        $output .= '<a href="' . esc_url(get_category_link($category->term_id)) . '">';
        $output .= $svg_icon . ' ' . esc_html($category->name);
        $output .= '</a>';
        $output .= '</li>';
    }
    $output .= '';

    return $output;
}
add_filter('wp_list_categories', 'custom_category_list', 10, 2);

/**
 * custom output fot wp_archive_list()
 */
function custom_archives_link( $link_html, $url, $text, $format, $before, $after ) {
    $custom_link = '<li><a href="' . esc_url($url) . '">
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-archive" viewBox="0 0 16 16">
        <path d="M0 2a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1v7.5a2.5 2.5 0 0 1-2.5 2.5h-9A2.5 2.5 0 0 1 1 12.5V5a1 1 0 0 1-1-1V2zm2 3v7.5A1.5 1.5 0 0 0 3.5 14h9a1.5 1.5 0 0 0 1.5-1.5V5H2zm13-3H1v2h14V2zM5 7.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5z"/>
    </svg>
    ' . $text . '</a></li>';
    // Return the modified link HTML
    return $before . $custom_link . $after;
}
add_filter( 'get_archives_link', 'custom_archives_link', 10, 6 );

/**
 * Custom output for wp_block_search()
 */
function custom_wp_block_search($block_content, $block) {
    if ($block['blockName'] === 'core/search') {
        ob_start();
        ?>
        <form role="search" method="get" action="<?php echo home_url( '/' ); ?>" class="wp-block-search__button-outside wp-block-search__text-button wp-block-search">
            <div class="wp-block-search__inside-wrapper ">
                <input class="wp-block-search__input" id="wp-block-search__input-1" placeholder="<?php esc_html_e('Buscar', 'stories'); ?>" value="" type="search" name="s" required="">
                <button aria-label="<?php esc_html_e('Buscar', 'stories'); ?>" class="wp-block-search__button wp-element-button" type="submit">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115 l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                    </svg>
                </button>
            </div>
        </form>
        <?php
        return ob_get_clean();
    }
    return $block_content;
}
add_filter('render_block', 'custom_wp_block_search', 10, 2);
