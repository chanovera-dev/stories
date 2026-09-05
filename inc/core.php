<?php
/**
 * Stories Core Functions
 *
 * Handles basic theme setup, theme support, menu registrations, and script/style enqueuing.
 *
 * @package Stories
 * @subpackage Inc
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'stories_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 */
	function stories_setup() {
		// Make theme available for translation.
		load_theme_textdomain( 'stories', STORIES_DIR . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		// Let WordPress manage the document title.
		add_theme_support( 'title-tag' );

		// Enable support for Post Thumbnails on posts and pages.
		add_theme_support( 'post-thumbnails' );

		// Set default image sizes.
		set_post_thumbnail_size( 1200, 675, true );

		// Enable support for Post Formats.
		add_theme_support(
			'post-formats',
			array(
				'aside',
				'gallery',
				'link',
				'image',
				'quote',
				'status',
				'video',
				'audio',
				'chat',
			)
		);

		// Register Navigation Menus.
		register_nav_menus(
			array(
				'primary'  => __( 'Primary Menu', 'stories' ),
				'footer'   => __( 'Footer Menu', 'stories' ),
				'social'   => __( 'Social Menu', 'stories' ),
				'footer-1' => __( 'Footer 1', 'stories' ),
				'footer-2' => __( 'Footer 2', 'stories' ),
				'footer-3' => __( 'Footer 3', 'stories' ),
			)
		);

		// Switch default core markup for search form, comment form, comments, gallery, and caption to HTML5.
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
			)
		);

		// Custom logo support.
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 250,
				'width'       => 250,
				'flex-width'  => true,
				'flex-height' => true,
			)
		);

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		// Gutenberg block styles & responsive embeds.
		add_theme_support( 'wp-block-styles' );
		add_theme_support( 'align-wide' );
		add_theme_support( 'responsive-embeds' );
	}
endif;
add_action( 'after_setup_theme', 'stories_setup' );

/**
 * Set content width in pixels.
 */
function stories_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'stories_content_width', 1200 );
}
add_action( 'after_setup_theme', 'stories_content_width', 0 );

/**
 * Helper function to retrieve the version of a theme asset based on its modification time.
 *
 * @param string $file_path Relative path to the file from theme root.
 * @return int|string File modification time or theme version fallback.
 */
function stories_get_asset_version( $file_path ) {
	$full_path = STORIES_DIR . $file_path;
	return file_exists( $full_path ) ? filemtime( $full_path ) : STORIES_VERSION;
}

/**
 * Helper function to enqueue a stylesheet with automatic versioning.
 *
 * @param string $handle Name of the stylesheet.
 * @param string $path   Relative path to the stylesheet or external URL.
 * @param array  $deps   Optional list of dependencies.
 * @param string $media  Media type.
 */
function stories_enqueue_style( $handle, $path, $deps = array(), $media = 'all' ) {
	$src = ( strpos( $path, 'http' ) === 0 ) ? $path : STORIES_URI . $path;
	$ver = ( strpos( $path, 'http' ) === 0 ) ? '1.0' : stories_get_asset_version( $path );
	wp_enqueue_style( $handle, $src, $deps, $ver, $media );
}

/**
 * Helper function to enqueue a script with automatic versioning and dependency support.
 *
 * @param string $handle    Name of the script.
 * @param string $path      Relative path to the script or external URL.
 * @param array  $deps      Optional list of dependencies.
 * @param bool   $in_footer Whether to enqueue in footer.
 */
function stories_enqueue_script( $handle, $path, $deps = array(), $in_footer = true ) {
	$src = ( strpos( $path, 'http' ) === 0 ) ? $path : STORIES_URI . $path;
	$ver = ( strpos( $path, 'http' ) === 0 ) ? '1.0' : stories_get_asset_version( $path );
	wp_enqueue_script( $handle, $src, $deps, $ver, $in_footer );
}

/**
 * Returns an array of asset paths for the theme.
 *
 * @return array List of CSS and JS file paths.
 */
function stories_get_assets() {
	$assets_path = '/assets';

	return array(
		'css' => array(
			'main'         => "$assets_path/css/main.css",
			'custom-forms' => "$assets_path/css/custom-forms.css",
			'loop'         => "$assets_path/css/loop.css",
			'single'       => "$assets_path/css/single.css",
			'pagination'   => "$assets_path/css/pagination.css",
			'posts'        => "$assets_path/css/posts.css",
			'comments'     => "$assets_path/css/comments.css",
			'related'      => "$assets_path/css/related.css",
			'rounded'      => "$assets_path/css/rounded.css",
		),
		'js'  => array(
			'main'    => "$assets_path/js/main.js",
			'related' => "$assets_path/js/related.js",
			'ajax'    => "$assets_path/js/ajax.js",
		),
	);
}

/**
 * Enqueue scripts and styles.
 */
function stories_enqueue_scripts() {
	$a = stories_get_assets();

	// Enqueue main stylesheet.
	wp_enqueue_style( 'stories-style', get_stylesheet_uri(), array(), stories_get_asset_version( '/style.css' ) );

	// Enqueue theme assets CSS.
	stories_enqueue_style( 'stories-main', $a['css']['main'] );
	stories_enqueue_style( 'stories-custom-forms', $a['css']['custom-forms'], array( 'stories-main' ) );

	// Enqueue loop grid CSS on post lists and singular views (for Timeline and Related Posts cards).
	if ( is_home() || is_archive() || is_search() || is_front_page() || is_singular() ) {
		if ( ! is_singular() ) {
			stories_enqueue_style( 'stories-pagination', $a['css']['pagination'], array( 'stories-main' ) );
		}

		$loop_design = function_exists( 'stories_get_loop_design' ) ? stories_get_loop_design() : 'default';
		if ( 'default' !== $loop_design && file_exists( STORIES_DIR . "/assets/css/{$loop_design}.css" ) ) {
			stories_enqueue_style( "stories-loop-{$loop_design}", "/assets/css/{$loop_design}.css", array( 'stories-main' ) );
		} elseif ( file_exists( STORIES_DIR . '/assets/css/loop.css' ) ) {
			stories_enqueue_style( 'stories-loop', $a['css']['loop'], array( 'stories-main' ) );
		} elseif ( file_exists( STORIES_DIR . '/assets/css/posts.css' ) ) {
			stories_enqueue_style( 'stories-posts', $a['css']['posts'], array( 'stories-main' ) );
		}
	}

	// Enqueue single post view styles on singular views.
	if ( is_singular() ) {
		stories_enqueue_style( 'stories-single', $a['css']['single'], array( 'stories-main' ) );
	}

	// Enqueue comments CSS on singular pages when comments are open.
	if ( is_singular() && comments_open() ) {
		stories_enqueue_style( 'stories-comments', $a['css']['comments'], array( 'stories-main', 'stories-custom-forms' ) );
	}

	// Enqueue related posts carousel styles & scripts on single post views.
	if ( is_single() ) {
		stories_enqueue_style( 'stories-related-styles', $a['css']['related'], array( 'stories-main' ) );
		stories_enqueue_script( 'stories-related-script', $a['js']['related'], array(), true );
	}

	// Enqueue rounded & squircle styles if enabled.
	$theme_options  = get_option( 'stories_theme_options', array() );
	$enable_rounded = isset( $theme_options['enable_rounded'] ) ? ! empty( $theme_options['enable_rounded'] ) : true;

	if ( $enable_rounded ) {
		stories_enqueue_style( 'stories-rounded', $a['css']['rounded'], array( 'stories-main' ) );
	}

	// Enqueue theme main JavaScript (Vanilla JS).
	$enable_is_chromium = isset( $theme_options['enable_is_chromium'] ) ? ! empty( $theme_options['enable_is_chromium'] ) : true;

	$posts_page_id = get_option( 'page_for_posts' );
	$all_posts_url = ( 'page' === get_option( 'show_on_front' ) && ! empty( $posts_page_id ) ) ? get_permalink( $posts_page_id ) : home_url( '/' );

	stories_enqueue_script( 'stories-main', $a['js']['main'], array(), true );
	wp_localize_script(
		'stories-main',
		'storiesAjax',
		array(
			'ajax_url'           => admin_url( 'admin-ajax.php' ),
			'nonce'              => wp_create_nonce( 'stories_ajax_nonce' ),
			'enable_is_chromium' => $enable_is_chromium ? 1 : 0,
			'all_posts_url'      => esc_url( $all_posts_url ),
		)
	);
	wp_localize_script(
		'stories-main',
		'avante_likes_obj',
		array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
		)
	);

	// Comment reply script on single views.
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	// Disable lightbox on 404 pages.
	if ( is_404() ) {
		wp_dequeue_script( 'wp-block-image-lightbox' );
		wp_dequeue_style( 'wp-block-image-lightbox' );
	}
}
add_action( 'wp_enqueue_scripts', 'stories_enqueue_scripts' );

/**
 * Customizes the avatar size for comments to 70px.
 *
 * @param string $avatar The avatar HTML markup.
 * @return string Modified avatar HTML with fixed width and height.
 */
function stories_custom_comment_avatar_size( $avatar ) {
	$avatar = preg_replace( '/(width|height)="\d*"\s/', '', $avatar );
	$avatar = preg_replace( '/style=["\'](.*?)["\']/', '', $avatar );
	$avatar = preg_replace( '/src=([\'"])((?:(?!\1).)*?)\1/', 'src=$1$2$1 width="70" height="70"', $avatar );
	return $avatar;
}
add_filter( 'get_avatar', 'stories_custom_comment_avatar_size', 10, 1 );

/**
 * Register widget areas.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function stories_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Barra lateral principal', 'stories' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Añade widgets aquí para que aparezcan en la barra lateral del blog, entradas y archivos.', 'stories' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		)
	);

	register_sidebar(
		array(
			'name'          => esc_html__( 'Barra lateral de páginas', 'stories' ),
			'id'            => 'sidebar-page',
			'description'   => esc_html__( 'Añade widgets aquí para que aparezcan en las páginas estáticas. Si se deja vacía, se usará la barra lateral principal.', 'stories' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		)
	);
}
add_action( 'widgets_init', 'stories_widgets_init' );
