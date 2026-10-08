<?php
/**
 * Stories Typography Engine
 *
 * Handles dynamic font discovery from assets/fonts, theme.json font integration,
 * Gutenberg typography overrides, frontend CSS variable output, and admin font preview.
 *
 * @package Stories
 * @subpackage Inc
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Retrieve all registered font families available in the theme.
 *
 * Scans theme.json and the assets/fonts directory in both parent and child themes.
 *
 * @return array Associative array of font family definitions keyed by slug.
 */
function stories_get_registered_fonts() {
	static $cached_fonts = null;

	if ( null !== $cached_fonts ) {
		return $cached_fonts;
	}

	$fonts = array();

	// 1. Read theme.json fontFamilies directly from file to prevent recursive filter loops.
	$theme_json_path = STORIES_DIR . '/theme.json';
	if ( file_exists( $theme_json_path ) ) {
		$raw_json = file_get_contents( $theme_json_path );
		$data     = json_decode( $raw_json, true );
		if ( isset( $data['settings']['typography']['fontFamilies'] ) && is_array( $data['settings']['typography']['fontFamilies'] ) ) {
			foreach ( $data['settings']['typography']['fontFamilies'] as $font ) {
				if ( isset( $font['slug'], $font['name'] ) ) {
					$fonts[ $font['slug'] ] = array(
						'name'       => $font['name'],
						'slug'       => $font['slug'],
						'fontFamily' => isset( $font['fontFamily'] ) ? $font['fontFamily'] : $font['name'],
						'fontFace'   => isset( $font['fontFace'] ) && is_array( $font['fontFace'] ) ? $font['fontFace'] : array(),
					);
				}
			}
		}
	}

	// 2. Scan assets/fonts directory in parent theme and active child theme.
	$scan_locations = array(
		STORIES_DIR . '/assets/fonts' => STORIES_URI . '/assets/fonts',
	);

	if ( get_stylesheet_directory() !== STORIES_DIR ) {
		$scan_locations[ get_stylesheet_directory() . '/assets/fonts' ] = get_stylesheet_directory_uri() . '/assets/fonts';
	}

	foreach ( $scan_locations as $dir_path => $dir_uri ) {
		if ( ! is_dir( $dir_path ) ) {
			continue;
		}

		$folders = scandir( $dir_path );
		if ( false === $folders ) {
			continue;
		}

		foreach ( $folders as $folder ) {
			if ( '.' === $folder || '..' === $folder ) {
				continue;
			}

			$full_folder_path = $dir_path . '/' . $folder;
			if ( ! is_dir( $full_folder_path ) ) {
				continue;
			}

			$slug = sanitize_title( $folder );

			// Format human-friendly font name if not set (e.g., bricolage-grotesque -> Bricolage Grotesque).
			$human_name = ucwords( str_replace( array( '-', '_' ), ' ', $folder ) );

			// If font is already registered from theme.json, ensure its fontFace has valid URLs.
			if ( ! isset( $fonts[ $slug ] ) ) {
				$font_files = scandir( $full_folder_path );
				$faces      = array();

				if ( false !== $font_files ) {
					foreach ( $font_files as $file ) {
						$ext = strtolower( pathinfo( $file, PATHINFO_EXTENSION ) );
						if ( in_array( $ext, array( 'woff2', 'woff', 'ttf', 'otf' ), true ) ) {
							$file_url = $dir_uri . '/' . $folder . '/' . $file;
							$faces[]  = array(
								'src'        => array( $file_url ),
								'fontWeight' => '100 900',
								'fontStyle'  => 'normal',
								'fontFamily' => '"' . $human_name . '"',
							);
						}
					}
				}

				$fonts[ $slug ] = array(
					'name'       => $human_name,
					'slug'       => $slug,
					'fontFamily' => '"' . $human_name . '", sans-serif',
					'fontFace'   => $faces,
				);
			}
		}
	}

	/**
	 * Filters the registered font families available in Stories.
	 *
	 * @param array $fonts Associative array of font family definitions.
	 */
	$cached_fonts = apply_filters( 'stories_registered_fonts', $fonts );

	return $cached_fonts;
}

/**
 * Get font choices as key-value pairs for dropdown selects.
 *
 * @return array Array of slug => font name.
 */
function stories_get_font_choices() {
	$registered = stories_get_registered_fonts();
	$choices    = array();

	foreach ( $registered as $slug => $data ) {
		$name             = is_array( $data ) && isset( $data['name'] ) ? $data['name'] : (string) $data;
		$choices[ $slug ] = $name;
	}

	return $choices;
}

/**
 * Get the currently active font slug for headings.
 *
 * @return string Font slug (e.g. 'manrope', 'bricolage-grotesque').
 */
function stories_get_font_headings() {
	$options = get_option( 'stories_theme_options', array() );

	if ( ! empty( $options['font_headings'] ) ) {
		$slug = sanitize_key( $options['font_headings'] );
	} else {
		$single = get_option( 'stories_font_headings' );
		if ( ! empty( $single ) ) {
			$slug = sanitize_key( $single );
		} else {
			$avante_slug = get_option( 'avante_font_headings' );
			$slug        = ! empty( $avante_slug ) ? sanitize_key( $avante_slug ) : 'manrope';
		}
	}

	return apply_filters( 'stories_active_font_headings', ! empty( $slug ) ? $slug : 'manrope' );
}

/**
 * Get the currently active font slug for body/content text.
 *
 * @return string Font slug (e.g. 'manrope', 'bricolage-grotesque').
 */
function stories_get_font_body() {
	$options = get_option( 'stories_theme_options', array() );

	if ( ! empty( $options['font_body'] ) ) {
		$slug = sanitize_key( $options['font_body'] );
	} else {
		$single = get_option( 'stories_font_body' );
		if ( ! empty( $single ) ) {
			$slug = sanitize_key( $single );
		} else {
			$avante_slug = get_option( 'avante_font_body' );
			$slug        = ! empty( $avante_slug ) ? sanitize_key( $avante_slug ) : 'manrope';
		}
	}

	return apply_filters( 'stories_active_font_body', ! empty( $slug ) ? $slug : 'manrope' );
}

/**
 * Get the currently active font slug for monospace/code text.
 *
 * @return string Font slug (e.g. 'fira-code').
 */
function stories_get_font_monospace() {
	$options = get_option( 'stories_theme_options', array() );

	if ( ! empty( $options['font_monospace'] ) ) {
		$slug = sanitize_key( $options['font_monospace'] );
	} else {
		$single = get_option( 'stories_font_monospace' );
		if ( ! empty( $single ) ) {
			$slug = sanitize_key( $single );
		} else {
			$avante_slug = get_option( 'avante_font_monospace' );
			$slug        = ! empty( $avante_slug ) ? sanitize_key( $avante_slug ) : 'fira-code';
		}
	}

	return apply_filters( 'stories_active_font_monospace', ! empty( $slug ) ? $slug : 'fira-code' );
}

/**
 * Get clean CSS font-family definition for a given font slug.
 *
 * @param string $slug Font slug.
 * @return string CSS font-family string.
 */
function stories_get_font_family_css( $slug ) {
	$registered = stories_get_registered_fonts();

	if ( isset( $registered[ $slug ]['fontFamily'] ) ) {
		return $registered[ $slug ]['fontFamily'];
	}

	// Standard fallbacks based on slug.
	switch ( $slug ) {
		case 'manrope':
			return 'Manrope, "Manrope-Fallback", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif';
		case 'bricolage-grotesque':
			return '"Bricolage Grotesque", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif';
		case 'fira-code':
			return '"Fira Code", monospace';
		default:
			$name = ucwords( str_replace( array( '-', '_' ), ' ', $slug ) );
			return '"' . $name . '", sans-serif';
	}
}

/**
 * Generate CSS @font-face rules for all registered fonts in assets/fonts.
 *
 * @return string CSS @font-face rules.
 */
function stories_generate_font_faces_css() {
	$fonts = stories_get_registered_fonts();
	$css   = '';

	// Font metric override for zero Cumulative Layout Shift (CLS) on swap
	$css .= "@font-face {\n";
	$css .= "\tfont-family: 'Manrope-Fallback';\n";
	$css .= "\tsrc: local('Arial');\n";
	$css .= "\tascent-override: 104.5%;\n";
	$css .= "\tdescent-override: 27.5%;\n";
	$css .= "\tline-gap-override: 0%;\n";
	$css .= "\tsize-adjust: 99.5%;\n";
	$css .= "}\n";

	// Hardcoded reliable mappings for bundled theme fonts.
	$bundled_fonts = array(
		'manrope'            => array(
			'family' => 'Manrope',
			'file'   => '/assets/fonts/manrope/Manrope-VariableFont_wght.woff2',
			'weight' => '200 800',
			'format' => 'woff2',
		),
		'bricolage-grotesque' => array(
			'family' => 'Bricolage Grotesque',
			'file'   => '/assets/fonts/bricolage-grotesque/BricolageGrotesque[opsz,wdth,wght].woff2',
			'weight' => '200 800',
			'format' => 'woff2',
		),
		'fira-code'          => array(
			'family' => 'Fira Code',
			'file'   => '/assets/fonts/fira-code/FiraCode-VariableFont_wght.woff2',
			'weight' => '300 700',
			'format' => 'woff2',
		),
	);

	foreach ( $bundled_fonts as $slug => $data ) {
		if ( file_exists( STORIES_DIR . $data['file'] ) ) {
			$url  = STORIES_URI . $data['file'];
			$css .= "@font-face {\n";
			$css .= "\tfont-family: '{$data['family']}';\n";
			$css .= "\tsrc: url('{$url}') format('{$data['format']}');\n";
			$css .= "\tfont-weight: {$data['weight']};\n";
			$css .= "\tfont-style: normal;\n";
			$css .= "\tfont-display: swap;\n";
			$css .= "}\n";
		}
	}

	// Additional dynamic fonts scanned from assets/fonts.
	foreach ( $fonts as $slug => $font_data ) {
		if ( isset( $bundled_fonts[ $slug ] ) ) {
			continue;
		}

		if ( ! empty( $font_data['fontFace'] ) && is_array( $font_data['fontFace'] ) ) {
			foreach ( $font_data['fontFace'] as $face ) {
				$src_arr = isset( $face['src'] ) ? (array) $face['src'] : array();
				$src_url = ! empty( $src_arr[0] ) ? $src_arr[0] : '';

				if ( empty( $src_url ) ) {
					continue;
				}

				// Resolve relative file paths if needed.
				if ( 0 === strpos( $src_url, 'file:./' ) ) {
					$src_url = STORIES_URI . '/' . ltrim( substr( $src_url, 7 ), '/' );
				}

				$family = ! empty( $face['fontFamily'] ) ? trim( $face['fontFamily'], '"\'' ) : $font_data['name'];
				$weight = ! empty( $face['fontWeight'] ) ? $face['fontWeight'] : '100 900';
				$style  = ! empty( $face['fontStyle'] ) && 'large' !== $face['fontStyle'] ? $face['fontStyle'] : 'normal';
				$ext    = strtolower( pathinfo( parse_url( $src_url, PHP_URL_PATH ), PATHINFO_EXTENSION ) );
				$format = 'woff2' === $ext ? 'woff2' : ( 'woff' === $ext ? 'woff' : 'truetype' );

				$css .= "@font-face {\n";
				$css .= "\tfont-family: '{$family}';\n";
				$css .= "\tsrc: url('{$src_url}') format('{$format}');\n";
				$css .= "\tfont-weight: {$weight};\n";
				$css .= "\tfont-style: {$style};\n";
				$css .= "\tfont-display: swap;\n";
				$css .= "}\n";
			}
		}
	}

	return $css;
}

/**
 * Generate full frontend typography CSS (variables + element styling).
 *
 * @return string CSS string.
 */
function stories_generate_typography_css() {
	$font_headings  = stories_get_font_headings();
	$font_body      = stories_get_font_body();
	$font_monospace = stories_get_font_monospace();

	$family_headings  = stories_get_font_family_css( $font_headings );
	$family_body      = stories_get_font_family_css( $font_body );
	$family_monospace = stories_get_font_family_css( $font_monospace );

	$registered = stories_get_registered_fonts();

	$css  = stories_generate_font_faces_css();
	$css .= ":root {\n";

	// Output fallback preset variables for all registered fonts.
	foreach ( $registered as $slug => $data ) {
		$fam = stories_get_font_family_css( $slug );
		$css .= "\t--wp--preset--font-family--{$slug}: {$fam};\n";
	}

	// Main typography CSS variables.
	$css .= "\t--font-headings: var(--wp--preset--font-family--{$font_headings}, {$family_headings});\n";
	$css .= "\t--font-body: var(--wp--preset--font-family--{$font_body}, {$family_body});\n";
	$css .= "\t--font-mono: var(--wp--preset--font-family--{$font_monospace}, {$family_monospace});\n";
	$css .= "\t--font-family-base: var(--font-body);\n";
	$css .= "}\n\n";

	// Global typography styling rules.
	$css .= "body,\n";
	$css .= "p,\n";
	$css .= "li,\n";
	$css .= "input,\n";
	$css .= "textarea,\n";
	$css .= "select,\n";
	$css .= "button {\n";
	$css .= "\tfont-family: var(--font-body);\n";
	$css .= "}\n\n";

	$css .= "h1, h2, h3, h4, h5, h6,\n";
	$css .= ".h1, .h2, .h3, .h4, .h5, .h6,\n";
	$css .= ".site-title,\n";
	$css .= ".post__title,\n";
	$css .= ".entry-title,\n";
	$css .= ".widget-title,\n";
	$css .= ".theater-title,\n";
	$css .= ".stories-heading {\n";
	$css .= "\tfont-family: var(--font-headings);\n";
	$css .= "}\n\n";

	$css .= "code,\n";
	$css .= "kbd,\n";
	$css .= "samp,\n";
	$css .= "pre {\n";
	$css .= "\tfont-family: var(--font-mono);\n";
	$css .= "}\n";

	return $css;
}

/**
 * Enqueue dynamic typography CSS inline with stories-main on frontend.
 */
function stories_enqueue_typography_styles() {
	$css = stories_generate_typography_css();
	if ( ! empty( $css ) ) {
		wp_add_inline_style( 'stories-main', $css );
	}
}
add_action( 'wp_enqueue_scripts', 'stories_enqueue_typography_styles', 21 );

/**
 * Preload primary critical font (Manrope) in <head> for fast FCP/LCP and seamless swap.
 */
function stories_preload_critical_fonts() {
	$font_path = STORIES_DIR . '/assets/fonts/manrope/Manrope-VariableFont_wght.woff2';
	if ( file_exists( $font_path ) ) {
		$font_url = STORIES_URI . '/assets/fonts/manrope/Manrope-VariableFont_wght.woff2';
		echo '<link rel="preload" href="' . esc_url( $font_url ) . '" as="font" type="font/woff2" crossorigin>' . "\n";
	}
}
add_action( 'wp_head', 'stories_preload_critical_fonts', 1 );

/**
 * Enqueue @font-face styles on admin options page for live previews.
 *
 * @param string $hook Admin page hook.
 */
function stories_admin_typography_styles( $hook ) {
	if ( false === strpos( $hook, 'stories_options' ) ) {
		return;
	}

	$font_faces = stories_generate_font_faces_css();
	if ( ! empty( $font_faces ) ) {
		wp_add_inline_style( 'stories-admin-options', $font_faces );
	}
}
add_action( 'admin_enqueue_scripts', 'stories_admin_typography_styles', 20 );

/**
 * Override theme.json typography settings dynamically for Gutenberg and block templates.
 *
 * @param WP_Theme_JSON_Data $theme_json Current theme.json data instance.
 * @return WP_Theme_JSON_Data Modified theme.json data instance.
 */
function stories_override_theme_json_typography( $theme_json ) {
	static $is_running = false;

	if ( $is_running ) {
		return $theme_json;
	}

	$is_running = true;

	$font_body      = stories_get_font_body();
	$font_headings  = stories_get_font_headings();
	$font_monospace = stories_get_font_monospace();

	$new_data = array(
		'version' => 3,
		'styles'  => array(
			'typography' => array(
				'fontFamily' => 'var:preset|font-family|' . $font_body,
			),
			'elements'   => array(
				'heading' => array(
					'typography' => array(
						'fontFamily' => 'var:preset|font-family|' . $font_headings,
					),
				),
			),
			'blocks'     => array(
				'core/code'         => array(
					'typography' => array(
						'fontFamily' => 'var:preset|font-family|' . $font_monospace,
					),
				),
				'core/preformatted' => array(
					'typography' => array(
						'fontFamily' => 'var:preset|font-family|' . $font_monospace,
					),
				),
			),
		),
	);

	$result     = $theme_json->update_with( $new_data );
	$is_running = false;

	return $result;
}
add_filter( 'wp_theme_json_data_theme', 'stories_override_theme_json_typography', 20 );
