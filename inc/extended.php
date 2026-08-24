<?php
/**
 * Stories Extended Functions
 *
 * Contains extended custom filters, theme modifications, and helper utilities.
 *
 * @package Stories
 * @subpackage Inc
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Filter the excerpt length to 30 words.
 *
 * @param int $length Excerpt length in words.
 * @return int Modified excerpt length.
 */
function stories_custom_excerpt_length( $length ) {
	return apply_filters( 'stories_excerpt_length', 30 );
}
add_filter( 'excerpt_length', 'stories_custom_excerpt_length', 999 );

/**
 * Filter the excerpt "read more" string.
 *
 * @param string $more Current read more string.
 * @return string Modified read more string.
 */
function stories_custom_excerpt_more( $more ) {
	if ( is_admin() ) {
		return $more;
	}
	return ' &hellip; <a class="read-more" href="' . esc_url( get_permalink() ) . '">' . esc_html__( 'Leer historia', 'stories' ) . ' &rarr;</a>';
}
add_filter( 'excerpt_more', 'stories_custom_excerpt_more' );

/**
 * Add custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array Filtered body classes.
 */
function stories_body_classes( $classes ) {
	// Add a class if sidebar is active.
	if ( is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'has-sidebar';
	} else {
		$classes[] = 'no-sidebar';
	}

	// Add class if viewing a singular post or page.
	if ( is_singular() ) {
		$classes[] = 'is-singular';
	}

	// Add pagination style class.
	$theme_options    = get_option( 'stories_theme_options', array() );
	$pagination_style = ! empty( $theme_options['pagination_style'] ) ? $theme_options['pagination_style'] : 'default';
	$classes[]        = 'pagination-style-' . sanitize_html_class( $pagination_style );

	return $classes;
}
add_filter( 'body_class', 'stories_body_classes' );

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function stories_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">' . "\n", esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'stories_pingback_header' );

/**
 * Wraps nav menu items with children in wrapper-for-title with submenu toggle button.
 */
function stories_custom_menu_walker( $item_output, $item, $depth, $args ) {
	if ( empty( $args->theme_location ) || $args->theme_location !== 'primary' ) {
		return $item_output;
	}

	global $submenu_items_by_parent;
	static $checked_menus = array();

	if ( ! empty( $args->menu ) && ! in_array( $args->menu->term_id, $checked_menus ) ) {
		$menu_items = wp_get_nav_menu_items( $args->menu->term_id );
		if ( $menu_items ) {
			foreach ( $menu_items as $menu_item ) {
				$submenu_items_by_parent[ $menu_item->menu_item_parent ][] = $menu_item;
			}
		}
		$checked_menus[] = $args->menu->term_id;
	}

	$has_children = ! empty( $submenu_items_by_parent[ $item->ID ] );

	if ( $has_children ) {
		$text     = '<a href="' . esc_url( $item->url ) . '">' . esc_html( $item->title ) . '</a>';
		$svg_icon = stories_get_icon( 'chevron-down' );

		return '<div class="wrapper-for-title">' . $text . '<button class="button-for-submenu" aria-label="Toggle submenu">' . $svg_icon . '</button></div>';
	}

	return $item_output;
}
add_filter( 'walker_nav_menu_start_el', 'stories_custom_menu_walker', 10, 4 );

/**
 * Enable SVG uploads in WordPress Media Library.
 *
 * @param array $mimes Allowed mime types.
 * @return array Filtered mime types.
 */
function stories_mime_types( $mimes ) {
	$mimes['svg']  = 'image/svg+xml';
	$mimes['svgz'] = 'image/svg+xml';
	return $mimes;
}
add_filter( 'upload_mimes', 'stories_mime_types' );

/**
 * Fix SVG upload display and validation in WordPress 4.7+.
 *
 * @param array       $data     File data.
 * @param string      $file     Full path to file.
 * @param string      $filename Filename.
 * @param array|null  $mimes    Mime types.
 * @return array Corrected file data.
 */
function stories_fix_svg_mime_type( $data, $file, $filename, $mimes ) {
	$ext = pathinfo( $filename, PATHINFO_EXTENSION );
	if ( 'svg' === strtolower( $ext ) ) {
		$data['ext']  = 'svg';
		$data['type'] = 'image/svg+xml';
	}
	return $data;
}
add_filter( 'wp_check_filetype_and_ext', 'stories_fix_svg_mime_type', 10, 4 );

/**
 * Add CSS to fix SVG thumbnail preview display in WordPress Media Library.
 */
function stories_svg_admin_styles() {
	echo '<style>
		.attachment-266x266, .thumbnail img[src$=".svg"], img[src$=".svg"].attachment-post-thumbnail {
			width: 100% !important;
			height: auto !important;
		}
	</style>';
}
add_action( 'admin_head', 'stories_svg_admin_styles' );

/**
 * Output Google Tag Manager / Analytics in the head section.
 */
function stories_gtm_header() {
	$options = get_option( 'stories_theme_options', array() );
	$enabled = ! empty( $options['gtm_enable'] );
	$gtm_id  = ! empty( $options['gtm_id'] ) ? trim( $options['gtm_id'] ) : '';

	if ( ! $enabled || empty( $gtm_id ) ) {
		return;
	}

	if ( stripos( $gtm_id, 'GTM-' ) === 0 ) {
		?>
		<!-- Google Tag Manager -->
		<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
		new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
		j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
		'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
		})(window,document,'script','dataLayer','<?php echo esc_js( $gtm_id ); ?>');</script>
		<!-- End Google Tag Manager -->
		<?php
	} else {
		?>
		<!-- Google Analytics / gtag.js -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo esc_attr( $gtm_id ); ?>"></script>
		<script>
			window.dataLayer = window.dataLayer || [];
			function gtag(){dataLayer.push(arguments);}
			gtag('js', new Date());
			gtag('config', '<?php echo esc_js( $gtm_id ); ?>');
		</script>
		<!-- End Google Analytics -->
		<?php
	}
}
add_action( 'wp_head', 'stories_gtm_header', 1 );

/**
 * Output Google Tag Manager noscript fallback immediately after <body> opening tag.
 */
function stories_gtm_body_open() {
	$options = get_option( 'stories_theme_options', array() );
	$enabled = ! empty( $options['gtm_enable'] );
	$gtm_id  = ! empty( $options['gtm_id'] ) ? trim( $options['gtm_id'] ) : '';

	if ( ! $enabled || empty( $gtm_id ) || stripos( $gtm_id, 'GTM-' ) !== 0 ) {
		return;
	}
	?>
	<!-- Google Tag Manager (noscript) -->
	<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo esc_attr( $gtm_id ); ?>"
	height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
	<!-- End Google Tag Manager (noscript) -->
	<?php
}
add_action( 'wp_body_open', 'stories_gtm_body_open', 1 );

/**
 * Head Cleanups & Optimizations based on Theme Options.
 */
function stories_apply_head_optimizations() {
	$options = get_option( 'stories_theme_options', array() );

	// 1. Disable Emojis
	if ( ! empty( $options['disable_emojis'] ) ) {
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_action( 'admin_print_styles', 'print_emoji_styles' );
		remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
		remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
		remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
		add_filter( 'tiny_mce_plugins', 'stories_disable_emojis_tinymce' );
		add_filter( 'wp_resource_hints', 'stories_disable_emojis_dns_prefetch', 10, 2 );
	}

	// 2. Clean Meta Tags (WP Version, RSD, WLW, Shortlink)
	if ( ! empty( $options['clean_meta_tags'] ) ) {
		remove_action( 'wp_head', 'wp_generator' );
		remove_action( 'wp_head', 'wlwmanifest_link' );
		remove_action( 'wp_head', 'rsd_link' );
		remove_action( 'wp_head', 'wp_shortlink_wp_head' );
		remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10 );
	}

	// 3. Disable oEmbed Discovery & Scripts
	if ( ! empty( $options['disable_oembed'] ) ) {
		remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 10 );
		remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
		remove_action( 'wp_head', 'wp_oembed_add_host_js' );
		add_filter( 'embed_oembed_discover', '__return_false' );
	}
}
add_action( 'init', 'stories_apply_head_optimizations' );

/**
 * Filter function to remove the tinymce emoji plugin.
 *
 * @param array $plugins List of TinyMCE plugins.
 * @return array Filtered list.
 */
function stories_disable_emojis_tinymce( $plugins ) {
	return is_array( $plugins ) ? array_diff( $plugins, array( 'wpemoji' ) ) : array();
}

/**
 * Filter function to remove emoji DNS prefetch.
 *
 * @param array  $urls          URLs to prefetch.
 * @param string $relation_type Relation type.
 * @return array Filtered URLs.
 */
function stories_disable_emojis_dns_prefetch( $urls, $relation_type ) {
	if ( 'dns-prefetch' === $relation_type ) {
		$urls = array_filter(
			$urls,
			function( $url ) {
				return false === strpos( $url, 'https://s.w.org/images/core/emoji' );
			}
		);
	}
	return $urls;
}

/**
 * Dequeue Gutenberg Block Styles if option is enabled.
 */
function stories_dequeue_block_styles() {
	$options = get_option( 'stories_theme_options', array() );
	if ( ! empty( $options['disable_block_styles'] ) ) {
		wp_dequeue_style( 'wp-block-library' );
		wp_dequeue_style( 'wp-block-library-theme' );
		wp_dequeue_style( 'wc-blocks-style' );
		wp_dequeue_style( 'global-styles' );
	}
}
add_action( 'wp_enqueue_scripts', 'stories_dequeue_block_styles', 100 );

/**
 * Render HTML <button> element instead of <input> for comment form submit button.
 *
 * @param array $defaults Default comment form arguments.
 * @return array Modified comment form arguments.
 */
function stories_comment_form_submit_button( $defaults ) {
	$defaults['submit_button'] = '<button name="%1$s" type="submit" id="%2$s" class="%3$s">%4$s</button>';
	return $defaults;
}
add_filter( 'comment_form_defaults', 'stories_comment_form_submit_button' );