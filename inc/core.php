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
}
add_action( 'after_setup_theme', 'setup_stories' );

/**
 * Add components to footer
 */
function footer_components() {}
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