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
		$svg_icon = stories_get_icon( 'chevron-down' );

		return '<div class="wrapper-for-title">' . $item_output . '<button class="button-for-submenu" aria-label="Toggle submenu">' . $svg_icon . '</button></div>';
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

/**
 * Output dynamic meta description tag for SEO when no external SEO plugin is active.
 */
function stories_meta_description() {
	// Skip if a dedicated SEO plugin is handling meta descriptions (Yoast, Rank Math, AIOSEO, SEOPress).
	if ( defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' ) || defined( 'AIOSEO_VERSION' ) || function_exists( 'seopress_init' ) ) {
		return;
	}

	$description = '';

	if ( is_singular() ) {
		$post = get_queried_object();
		if ( $post ) {
			if ( ! empty( $post->post_excerpt ) ) {
				$description = $post->post_excerpt;
			} elseif ( ! empty( $post->post_content ) ) {
				$description = wp_strip_all_tags( strip_shortcodes( $post->post_content ) );
			}
		}
	} elseif ( is_home() || is_front_page() ) {
		$description = get_bloginfo( 'description', 'display' );
		if ( empty( $description ) ) {
			$description = get_bloginfo( 'name', 'display' );
		}
	} elseif ( is_category() || is_tag() || is_tax() ) {
		$term_desc = term_description();
		if ( ! empty( $term_desc ) ) {
			$description = wp_strip_all_tags( $term_desc );
		} else {
			$description = sprintf( __( 'Explora todas las historias y publicaciones etiquetadas en %s.', 'stories' ), single_term_title( '', false ) );
		}
	} elseif ( is_author() ) {
		$author = get_queried_object();
		if ( $author && ! empty( $author->description ) ) {
			$description = $author->description;
		} else {
			$description = sprintf( __( 'Historias y artículos escritos por %s.', 'stories' ), get_the_author_meta( 'display_name' ) );
		}
	} elseif ( is_search() ) {
		$description = sprintf( __( 'Resultados de búsqueda para: %s.', 'stories' ), get_search_query() );
	}

	if ( ! empty( $description ) ) {
		$description = wp_strip_all_tags( $description );
		$description = preg_replace( '/\s+/', ' ', $description );
		$description = wp_trim_words( $description, 28, '...' );
		echo '<meta name="description" content="' . esc_attr( trim( $description ) ) . '">' . "\n";
	}
}
add_action( 'wp_head', 'stories_meta_description', 2 );

/**
 * Preload critical CSS stylesheet in <head> for faster First Contentful Paint (FCP).
 */
function stories_preload_critical_assets() {
	if ( is_admin() ) {
		return;
	}
	$assets       = stories_get_assets();
	$main_css_url = STORIES_URI . $assets['css']['main'];
	$version      = stories_get_asset_version( $assets['css']['main'] );
	$full_url     = add_query_arg( 'ver', $version, $main_css_url );
	echo '<link rel="preload" href="' . esc_url( $full_url ) . '" as="style">' . "\n";
}
add_action( 'wp_head', 'stories_preload_critical_assets', 1 );

/**
 * Add defer attribute to frontend enqueued scripts to eliminate render-blocking JavaScript.
 *
 * @param string $tag    The <script> tag for the enqueued script.
 * @param string $handle The script's registered handle.
 * @param string $src    The script's source URL.
 * @return string Modified script tag.
 */
function stories_defer_enqueued_scripts( $tag, $handle, $src ) {
	if ( is_admin() || wp_is_json_request() ) {
		return $tag;
	}

	// Don't modify if already has async or defer attribute.
	if ( false !== strpos( $tag, ' defer' ) || false !== strpos( $tag, ' async' ) ) {
		return $tag;
	}

	return str_replace( '<script ', '<script defer ', $tag );
}
add_filter( 'script_loader_tag', 'stories_defer_enqueued_scripts', 10, 3 );

/**
 * Load non-critical below-the-fold stylesheets asynchronously to eliminate render-blocking CSS.
 *
 * @param string $html   The link tag for the enqueued style.
 * @param string $handle The style's registered handle.
 * @param string $href   The stylesheet's source URL.
 * @param string $media  The stylesheet's media attribute.
 * @return string Modified link tag.
 */
function stories_async_non_critical_styles( $html, $handle, $href, $media ) {
	if ( is_admin() ) {
		return $html;
	}

	// Handles that are strictly below the fold or decorative.
	$async_handles = array(
		'stories-related-styles',
		'stories-comments',
		'stories-rounded',
	);

	if ( in_array( $handle, $async_handles, true ) ) {
		return '<link rel="stylesheet" id="' . esc_attr( $handle ) . '-css" href="' . esc_url( $href ) . '" media="print" onload="this.media=\'all\'">' . "\n" .
				'<noscript><link rel="stylesheet" id="' . esc_attr( $handle ) . '-noscript-css" href="' . esc_url( $href ) . '" media="' . esc_attr( $media ) . '"></noscript>' . "\n";
	}

	return $html;
}
add_filter( 'style_loader_tag', 'stories_async_non_critical_styles', 10, 4 );

/**
 * Helper to resolve relative URL to absolute URL.
 *
 * @param string $url  Image or link URL.
 * @param string $base Base URL.
 * @return string Absolute URL.
 */
function stories_resolve_relative_url( $url, $base ) {
	if ( empty( $url ) ) {
		return '';
	}

	// If already an absolute URL.
	if ( parse_url( $url, PHP_URL_SCHEME ) !== null ) {
		return $url;
	}

	// Protocol-relative URL //example.com/img.jpg
	if ( 0 === strpos( $url, '//' ) ) {
		$scheme = parse_url( $base, PHP_URL_SCHEME );
		return ( $scheme ? $scheme : 'https' ) . ':' . $url;
	}

	if ( empty( $base ) ) {
		return $url;
	}

	$parts = parse_url( $base );
	if ( ! $parts || empty( $parts['host'] ) ) {
		return $url;
	}

	$scheme = ! empty( $parts['scheme'] ) ? $parts['scheme'] : 'https';
	$host   = $parts['host'];
	$port   = ! empty( $parts['port'] ) ? ':' . $parts['port'] : '';
	$root   = $scheme . '://' . $host . $port;

	// Absolute path from host root
	if ( 0 === strpos( $url, '/' ) ) {
		return $root . $url;
	}

	// Relative path
	$path = ! empty( $parts['path'] ) ? $parts['path'] : '/';
	$dir  = preg_replace( '#/[^/]*$#', '', $path );
	return $root . $dir . '/' . $url;
}

/**
 * Helper to extract the first URL from post content (supporting Gutenberg embeds, HTML anchors, and raw URLs).
 *
 * @param string $content Raw post content.
 * @return string Extracted URL or empty string.
 */
function stories_extract_first_url( $content ) {
	if ( empty( $content ) || ! is_string( $content ) ) {
		return '';
	}

	// 1. Check Gutenberg embed blocks: <!-- wp:embed {"url":"..."} --> or core-embed variants
	if ( preg_match( '/<!--\s+wp:(?:core-embed\/[a-z0-9-]+|embed)\s+(\{.+?\})\s+-->/s', $content, $matches ) ) {
		$embed_data = json_decode( $matches[1], true );
		if ( ! empty( $embed_data['url'] ) && filter_var( $embed_data['url'], FILTER_VALIDATE_URL ) ) {
			return trim( $embed_data['url'] );
		}
	}

	// 2. Check standard href attribute: <a href="...">
	$url = get_url_in_content( $content );
	if ( ! empty( $url ) && filter_var( $url, FILTER_VALIDATE_URL ) ) {
		return trim( $url );
	}

	// 3. Check plain text URL inside content
	if ( preg_match( '#https?://[^\s<>"\'{}|\\^`\[\]()]+#i', $content, $matches ) ) {
		if ( filter_var( $matches[0], FILTER_VALIDATE_URL ) ) {
			return trim( $matches[0] );
		}
	}

	return '';
}

/**
 * Parse HTML content and extract OpenGraph, Twitter Cards, JSON-LD Schema, and standard metadata.
 *
 * @param string $html     Raw HTML string of the target webpage.
 * @param string $base_url Base URL of the target page for resolving relative asset URLs.
 * @return array Extracted metadata including title, image, author, excerpt, categories, and tags.
 */
function stories_parse_html_meta( $html, $base_url = '' ) {
	$data = array(
		'title'      => '',
		'image'      => '',
		'author'     => '',
		'excerpt'    => '',
		'categories' => array(),
		'tags'       => array(),
	);

	if ( empty( $html ) || ! is_string( $html ) ) {
		return $data;
	}

	// 1. Parse JSON-LD scripts (Schema.org)
	if ( preg_match_all( '#<script[^>]+type=["\']application/ld\+json["\'][^>]*>(.*?)</script>#is', $html, $matches ) ) {
		foreach ( $matches[1] as $json_str ) {
			$json = json_decode( trim( $json_str ), true );
			if ( ! $json || ! is_array( $json ) ) {
				continue;
			}

			// Handle @graph array or single object
			$items = isset( $json['@graph'] ) && is_array( $json['@graph'] ) ? $json['@graph'] : array( $json );

			foreach ( $items as $item ) {
				if ( ! is_array( $item ) ) {
					continue;
				}

				// Title / Headline
				if ( empty( $data['title'] ) ) {
					if ( ! empty( $item['headline'] ) && is_string( $item['headline'] ) ) {
						$data['title'] = $item['headline'];
					} elseif ( ! empty( $item['name'] ) && is_string( $item['name'] ) && ( ! isset( $item['@type'] ) || in_array( $item['@type'], array( 'Article', 'NewsArticle', 'BlogPosting', 'WebPage' ), true ) ) ) {
						$data['title'] = $item['name'];
					}
				}

				// Author
				if ( empty( $data['author'] ) && ! empty( $item['author'] ) ) {
					if ( is_string( $item['author'] ) ) {
						$data['author'] = $item['author'];
					} elseif ( is_array( $item['author'] ) ) {
						if ( ! empty( $item['author']['name'] ) && is_string( $item['author']['name'] ) ) {
							$data['author'] = $item['author']['name'];
						} elseif ( ! empty( $item['author'][0]['name'] ) && is_string( $item['author'][0]['name'] ) ) {
							$data['author'] = $item['author'][0]['name'];
						}
					}
				}

				// Image
				if ( empty( $data['image'] ) && ! empty( $item['image'] ) ) {
					if ( is_string( $item['image'] ) ) {
						$data['image'] = $item['image'];
					} elseif ( is_array( $item['image'] ) ) {
						if ( ! empty( $item['image']['url'] ) && is_string( $item['image']['url'] ) ) {
							$data['image'] = $item['image']['url'];
						} elseif ( ! empty( $item['image'][0] ) && is_string( $item['image'][0] ) ) {
							$data['image'] = $item['image'][0];
						} elseif ( ! empty( $item['image'][0]['url'] ) && is_string( $item['image'][0]['url'] ) ) {
							$data['image'] = $item['image'][0]['url'];
						}
					}
				}

				// Excerpt / Description
				if ( empty( $data['excerpt'] ) && ! empty( $item['description'] ) && is_string( $item['description'] ) ) {
					$data['excerpt'] = $item['description'];
				}

				// Article Section -> Category
				if ( ! empty( $item['articleSection'] ) ) {
					$sections = is_array( $item['articleSection'] ) ? $item['articleSection'] : array( $item['articleSection'] );
					foreach ( $sections as $sec ) {
						if ( is_string( $sec ) && ! in_array( $sec, $data['categories'], true ) ) {
							$data['categories'][] = trim( $sec );
						}
					}
				}

				// Keywords -> Tags
				if ( ! empty( $item['keywords'] ) ) {
					$kw_raw = is_array( $item['keywords'] ) ? implode( ',', $item['keywords'] ) : $item['keywords'];
					if ( is_string( $kw_raw ) ) {
						$split_kws = explode( ',', $kw_raw );
						foreach ( $split_kws as $kw ) {
							$kw_clean = trim( $kw );
							if ( ! empty( $kw_clean ) && ! in_array( $kw_clean, $data['tags'], true ) ) {
								$data['tags'][] = $kw_clean;
							}
						}
					}
				}
			}
		}
	}

	// 2. Parse OpenGraph & HTML Meta Tags via DOMDocument / XPath
	$internal_errors = libxml_use_internal_errors( true );
	$dom             = new DOMDocument();

	// Force UTF-8 interpretation if charset is missing or varied.
	$encoded_html = function_exists( 'mb_encode_numericentity' )
		? mb_encode_numericentity( $html, array( 0x80, 0x10FFFF, 0, 0x1FFFFF ), 'UTF-8' )
		: $html;

	@$dom->loadHTML( $encoded_html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD );
	libxml_clear_errors();
	libxml_use_internal_errors( $internal_errors );

	$xpath = new DOMXPath( $dom );

	// Title
	if ( empty( $data['title'] ) ) {
		$title_queries = array(
			'//meta[@property="og:title"]/@content',
			'//meta[@name="twitter:title"]/@content',
			'//meta[@name="title"]/@content',
			'//title/text()',
			'//h1[1]/text()',
		);
		foreach ( $title_queries as $query ) {
			$nodes = $xpath->query( $query );
			if ( $nodes && $nodes->length > 0 ) {
				$raw_title = trim( $nodes->item( 0 )->nodeValue );
				if ( ! empty( $raw_title ) ) {
					$data['title'] = html_entity_decode( $raw_title, ENT_QUOTES | ENT_HTML5, 'UTF-8' );
					break;
				}
			}
		}
	}

	// Image
	if ( empty( $data['image'] ) ) {
		$image_queries = array(
			'//meta[@property="og:image:secure_url"]/@content',
			'//meta[@property="og:image"]/@content',
			'//meta[@property="og:image:url"]/@content',
			'//meta[@name="twitter:image:src"]/@content',
			'//meta[@name="twitter:image"]/@content',
			'//link[@rel="image_src"]/@href',
		);
		foreach ( $image_queries as $query ) {
			$nodes = $xpath->query( $query );
			if ( $nodes && $nodes->length > 0 ) {
				$raw_img = trim( $nodes->item( 0 )->nodeValue );
				if ( ! empty( $raw_img ) ) {
					$img_url = stories_resolve_relative_url( $raw_img, $base_url );
					if ( filter_var( $img_url, FILTER_VALIDATE_URL ) ) {
						$data['image'] = $img_url;
						break;
					}
				}
			}
		}
	}

	// Excerpt / Description
	if ( empty( $data['excerpt'] ) ) {
		$excerpt_queries = array(
			'//meta[@property="og:description"]/@content',
			'//meta[@name="twitter:description"]/@content',
			'//meta[@name="description"]/@content',
		);
		foreach ( $excerpt_queries as $query ) {
			$nodes = $xpath->query( $query );
			if ( $nodes && $nodes->length > 0 ) {
				$raw_desc = trim( $nodes->item( 0 )->nodeValue );
				if ( ! empty( $raw_desc ) ) {
					$data['excerpt'] = html_entity_decode( $raw_desc, ENT_QUOTES | ENT_HTML5, 'UTF-8' );
					break;
				}
			}
		}
	}

	// Author
	if ( empty( $data['author'] ) ) {
		$author_queries = array(
			'//meta[@name="author"]/@content',
			'//meta[@property="article:author"]/@content',
			'//meta[@name="twitter:creator"]/@content',
			'//meta[@name="dc.creator"]/@content',
			'//meta[@name="parsely-author"]/@content',
			'//meta[@name="sailthru.author"]/@content',
			'//meta[@property="author"]/@content',
			'//*[@rel="author"]/text()',
			'//*[contains(@class, "author-name")]/text()',
			'//*[contains(@class, "byline")]/text()',
		);
		foreach ( $author_queries as $query ) {
			$nodes = $xpath->query( $query );
			if ( $nodes && $nodes->length > 0 ) {
				$raw_author = trim( $nodes->item( 0 )->nodeValue );
				if ( ! empty( $raw_author ) ) {
					$data['author'] = html_entity_decode( $raw_author, ENT_QUOTES | ENT_HTML5, 'UTF-8' );
					break;
				}
			}
		}
	}

	// Categories & Section
	if ( empty( $data['categories'] ) ) {
		$category_nodes = $xpath->query( '//meta[@property="article:section"]/@content' );
		if ( $category_nodes && $category_nodes->length > 0 ) {
			foreach ( $category_nodes as $c_node ) {
				$sec = trim( $c_node->nodeValue );
				if ( ! empty( $sec ) && ! in_array( $sec, $data['categories'], true ) ) {
					$data['categories'][] = html_entity_decode( $sec, ENT_QUOTES | ENT_HTML5, 'UTF-8' );
				}
			}
		}
	}

	// Tags / Keywords
	if ( empty( $data['tags'] ) ) {
		$tag_nodes = $xpath->query( '//meta[@property="article:tag"]/@content' );
		if ( $tag_nodes && $tag_nodes->length > 0 ) {
			foreach ( $tag_nodes as $t_node ) {
				$tag_val = trim( $t_node->nodeValue );
				if ( ! empty( $tag_val ) && ! in_array( $tag_val, $data['tags'], true ) ) {
					$data['tags'][] = html_entity_decode( $tag_val, ENT_QUOTES | ENT_HTML5, 'UTF-8' );
				}
			}
		}

		if ( empty( $data['tags'] ) ) {
			$keyword_nodes = $xpath->query( '//meta[@name="keywords"]/@content' );
			if ( $keyword_nodes && $keyword_nodes->length > 0 ) {
				$raw_kw = trim( $keyword_nodes->item( 0 )->nodeValue );
				if ( ! empty( $raw_kw ) ) {
					$kw_list = explode( ',', $raw_kw );
					foreach ( $kw_list as $kw ) {
						$kw_clean = trim( $kw );
						if ( ! empty( $kw_clean ) && ! in_array( $kw_clean, $data['tags'], true ) ) {
							$data['tags'][] = html_entity_decode( $kw_clean, ENT_QUOTES | ENT_HTML5, 'UTF-8' );
						}
					}
				}
			}
		}
	}

	return $data;
}

/**
 * Retrieve remote metadata (title, image, author, categories, tags, excerpt) for a link format post with caching.
 *
 * Checks post_meta cache first to prevent repeated remote HTTP requests.
 *
 * @param int $post_id Post ID.
 * @return array Cached or freshly fetched link metadata.
 */
function stories_get_link_post_metadata( $post_id = 0 ) {
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}
	if ( ! $post_id ) {
		return array();
	}

	// Extract target URL from post content or fallback to permalink.
	$raw_content = get_post_field( 'post_content', $post_id );
	$link_url    = stories_extract_first_url( $raw_content );
	if ( empty( $link_url ) ) {
		$link_url = get_permalink( $post_id );
	}

	// Check post_meta cache.
	$cached = get_post_meta( $post_id, '_stories_link_metadata', true );
	if ( is_array( $cached ) && ! empty( $cached['cached_at'] ) && isset( $cached['url'] ) && $cached['url'] === $link_url ) {
		return $cached;
	}

	// Prepare default local fallback data.
	$local_thumb   = has_post_thumbnail( $post_id ) ? get_the_post_thumbnail_url( $post_id, 'medium' ) : '';
	$local_title   = get_the_title( $post_id );
	$local_author  = get_the_author_meta( 'display_name', get_post_field( 'post_author', $post_id ) );
	$local_excerpt = get_the_excerpt( $post_id );

	$local_cats = array();
	$cat_objs   = get_the_category( $post_id );
	if ( ! empty( $cat_objs ) && ! is_wp_error( $cat_objs ) ) {
		foreach ( $cat_objs as $c ) {
			$local_cats[] = array(
				'name' => $c->name,
				'url'  => get_category_link( $c->term_id ),
			);
		}
	}

	$local_tags = array();
	$tag_objs   = get_the_tags( $post_id );
	if ( ! empty( $tag_objs ) && ! is_wp_error( $tag_objs ) ) {
		foreach ( $tag_objs as $t ) {
			$local_tags[] = array(
				'name' => $t->name,
				'url'  => get_tag_link( $t->term_id ),
			);
		}
	}

	$data = array(
		'url'        => $link_url,
		'title'      => $local_title,
		'image'      => $local_thumb,
		'author'     => $local_author,
		'categories' => $local_cats,
		'tags'       => $local_tags,
		'excerpt'    => $local_excerpt,
		'cached_at'  => time(),
		'is_remote'  => false,
	);

	$site_host = wp_parse_url( home_url(), PHP_URL_HOST );
	$link_host = wp_parse_url( $link_url, PHP_URL_HOST );

	if ( ! empty( $link_url ) && filter_var( $link_url, FILTER_VALIDATE_URL ) && $link_host && strtolower( $link_host ) !== strtolower( (string) $site_host ) ) {
		$response = wp_safe_remote_get(
			$link_url,
			array(
				'timeout'     => 6,
				'redirection' => 4,
				'user-agent'  => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36',
				'sslverify'   => false,
				'headers'     => array(
					'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
				),
			)
		);

		if ( ! is_wp_error( $response ) && wp_remote_retrieve_response_code( $response ) === 200 ) {
			$html = wp_remote_retrieve_body( $response );
			if ( ! empty( $html ) ) {
				$remote_meta = stories_parse_html_meta( $html, $link_url );
				if ( ! empty( $remote_meta['title'] ) ) {
					$data['title'] = $remote_meta['title'];
				}
				if ( ! empty( $remote_meta['image'] ) ) {
					$data['image'] = $remote_meta['image'];
				}
				if ( ! empty( $remote_meta['author'] ) ) {
					$data['author'] = $remote_meta['author'];
				}
				if ( ! empty( $remote_meta['excerpt'] ) ) {
					$data['excerpt'] = $remote_meta['excerpt'];
				}
				if ( ! empty( $remote_meta['categories'] ) ) {
					$data['categories'] = $remote_meta['categories'];
				}
				if ( ! empty( $remote_meta['tags'] ) ) {
					$data['tags'] = $remote_meta['tags'];
				}
				$data['is_remote'] = true;
			}
		}
	}

	// Persist the metadata in post meta so subsequent views do not perform HTTP requests.
	update_post_meta( $post_id, '_stories_link_metadata', $data );

	return $data;
}

/**
 * Invalidate link metadata cache when post is saved or updated.
 *
 * @param int $post_id Post ID.
 */
function stories_clear_link_metadata_cache( $post_id ) {
	if ( wp_is_post_revision( $post_id ) || wp_is_post_autosave( $post_id ) ) {
		return;
	}
	delete_post_meta( $post_id, '_stories_link_metadata' );
}
add_action( 'save_post', 'stories_clear_link_metadata_cache' );
add_action( 'edit_post', 'stories_clear_link_metadata_cache' );