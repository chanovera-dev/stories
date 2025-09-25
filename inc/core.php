<?php
/**
 * Stories core functions
 * 
 * @package Stories
 * @since 1.0.0
 */

function setup_stories() {

    /**
     * Register menus
     */
    register_nav_menus(
        array(
            'primary' => __( 'Primary menu', 'stories' ),
            'pages'   => __( 'Pages menu', 'stories' ),
            'social'  => __( 'Social menu', 'stories' ),
        )
    );

    /**
     * Add theme support
     */
    add_theme_support( 'title-tag' );
    add_theme_support( 'automatic-feed-links' );
    add_theme_support( 'custom-logo',
        array(
            'height'      => 32,
            'width'       => 172,
            'flex-height' => true,
            'flex-width'  => true,
        )
    );

    add_theme_support( 'html5',
        apply_filters(
            'chanovera_html5_args',
            array(
                'search-form',
                'comment-form',
                'comment-list',
                'gallery',
                'caption',
                'widgets',
                'style',
                'script',
            )
        )
    );

    add_theme_support( 'post-formats',
        array(
            'aside',
            'image',
            'video',
            'quote',
            'link',
            'gallery',
            'audio',
            'chat',
        )
    );

    add_theme_support( 'customize-selective-refresh-widgets' );

    add_theme_support( 'post-thumbnails',
        array( 'post', 'page' )
    );
    set_post_thumbnail_size( 350, 200, true );

    add_theme_support( 'wp-block-styles' );
    add_theme_support( 'align-wide' );
    
}
add_action( 'after_setup_theme', 'setup_stories' );

/**
 * Cache booster
 */
function get_asset_version($file_path) {
    $full_path = get_template_directory() . $file_path;
    return file_exists($full_path) ? filemtime($full_path) : time();
}

/**
 * Load parts in header
 */
function load_parts_header() {
    
    wp_register_style( 'global', get_template_directory_uri() . '/style.css', array(), get_asset_version('/style.css'), 'all' ); 
    wp_enqueue_style( 'global' );
     
}
add_action( 'wp_enqueue_scripts', 'load_parts_header' );

/**
 * Add components to footer
 */
function footer_components() {
    /* js for header */
    wp_enqueue_script('global-js', get_template_directory_uri() . '/assets/js/global.js', array(), get_asset_version('/assets/js/global.js'), true);
    wp_enqueue_style( 'custom-forms', get_template_directory_uri() . '/assets/css/forms.css', array(), get_asset_version('/assets/css/forms.css'), 'all' );
    wp_enqueue_style( 'wp-root', get_template_directory_uri() . '/assets/css/wp-root.css', array(), get_asset_version('/assets/css/wp-root.css'), 'all' );
    if ( is_user_logged_in() ) {
        wp_enqueue_style( 'logged-in', get_template_directory_uri() . '/assets/css/logged-in.css', array(), get_asset_version('/assets/css/logged-in.css'), 'all' );
    }
}
add_action( 'wp_footer', 'footer_components' );

/**
 * Register widgets areas
 */
function widgets_areas() {

    register_sidebar(
        array(
            'name'          => __( 'Blog Posts sidebar', 'stories' ),
            'id'            => 'sidebar-posts',
            'before_widget' => '',
            'after_widget'  => '',
        )
    );

    register_sidebar(
        array(
            'name'          => __( 'Single Post sidebar', 'stories' ),
            'id'            => 'sidebar-2',
            'before_widget' => '',
            'after_widget'  => '',
        )
    );

    register_sidebar(
        array(
            'name'          => __( 'Single Page sidebar', 'stories' ),
            'id'            => 'sidebar-3',
            'before_widget' => '',
            'after_widget'  => '',
        )
    );

}
add_action( 'widgets_init', 'widgets_areas' );