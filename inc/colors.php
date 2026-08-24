<?php
/**
 * Stories Color Schemes and Palettes Engine
 *
 * Manages predefined color schemes and custom palette overrides for the Stories theme.
 *
 * @package Stories
 * @subpackage Inc
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get all available color schemes definitions.
 *
 * @return array Associative array of color schemes.
 */
function stories_get_color_schemes() {
	return array(
		'evergreen' => array(
			'label'       => __( 'Evergreen / Esmeralda (Por defecto)', 'stories' ),
			'description' => __( 'Tonos botánicos frescos, verdes naturales y contrastes oscuros profundos.', 'stories' ),
			'preview'     => array(
				'primary' => '#8bc34a',
				'accent'  => '#47b23c',
				'header'  => '#eaeeea',
				'footer'  => '#092327',
			),
			'vars'        => array(
				'--color-primary'              => '#8bc34a',
				'--color-primary-hover'        => '#7cb342',
				'--color-primary-light'        => '#eff1e1',
				'--color-primary-accent'       => '#47b23c',
				'--color-primary-accent-light' => '#9bae3a',
				'--color-secondary'            => '#dae3da',
				'--color-tertiary'             => '#a8b9a8',
				'--color-quaternary'           => '#b2c2b2',
				'--bg-body'                    => '#f5f7f5',
				'--header-background'          => '#eaeeea',
				'--footer-background'          => '#092327',
				'--accent-background-1'        => '#539067',
				'--accent-background-2'        => '#89c39d',
			),
		),
		'nordic'    => array(
			'label'       => __( 'Nordic Ocean / Azul Océano', 'stories' ),
			'description' => __( 'Gama ártica con azules celestes vibrantes y azul marino elegante.', 'stories' ),
			'preview'     => array(
				'primary' => '#0ea5e9',
				'accent'  => '#0284c7',
				'header'  => '#f0f7fc',
				'footer'  => '#0f172a',
			),
			'vars'        => array(
				'--color-primary'              => '#0ea5e9',
				'--color-primary-hover'        => '#0284c7',
				'--color-primary-light'        => '#e0f2fe',
				'--color-primary-accent'       => '#0369a1',
				'--color-primary-accent-light' => '#38bdf8',
				'--color-secondary'            => '#dbeafe',
				'--color-tertiary'             => '#93c5fd',
				'--color-quaternary'           => '#bfdbfe',
				'--bg-body'                    => '#f0f7fc',
				'--header-background'          => '#e8f2f8',
				'--footer-background'          => '#0f172a',
				'--accent-background-1'        => '#1e3a8a',
				'--accent-background-2'        => '#3b82f6',
			),
		),
		'sunset'    => array(
			'label'       => __( 'Sunset Amber / Ámbar & Terracota', 'stories' ),
			'description' => __( 'Calidez editorial inspirada en atardeceres, fuego y arcilla.', 'stories' ),
			'preview'     => array(
				'primary' => '#f97316',
				'accent'  => '#ea580c',
				'header'  => '#fff7ed',
				'footer'  => '#1c1917',
			),
			'vars'        => array(
				'--color-primary'              => '#f97316',
				'--color-primary-hover'        => '#ea580c',
				'--color-primary-light'        => '#ffedd5',
				'--color-primary-accent'       => '#c2410c',
				'--color-primary-accent-light' => '#fb923c',
				'--color-secondary'            => '#fed7aa',
				'--color-tertiary'             => '#fdba74',
				'--color-quaternary'           => '#fef3c7',
				'--bg-body'                    => '#faf6f0',
				'--header-background'          => '#fff7ed',
				'--footer-background'          => '#1c1917',
				'--accent-background-1'        => '#7c2d12',
				'--accent-background-2'        => '#ea580c',
			),
		),
		'midnight'  => array(
			'label'       => __( 'Velvet Midnight / Púrpura Nocturno', 'stories' ),
			'description' => __( 'Misterio y sofisticación con violetas eléctricos y carbón nocturno.', 'stories' ),
			'preview'     => array(
				'primary' => '#8b5cf6',
				'accent'  => '#7c3aed',
				'header'  => '#faf5ff',
				'footer'  => '#18181b',
			),
			'vars'        => array(
				'--color-primary'              => '#8b5cf6',
				'--color-primary-hover'        => '#7c3aed',
				'--color-primary-light'        => '#ede9fe',
				'--color-primary-accent'       => '#6d28d9',
				'--color-primary-accent-light' => '#a78bfa',
				'--color-secondary'            => '#ddd6fe',
				'--color-tertiary'             => '#c4b5fd',
				'--color-quaternary'           => '#f3e8ff',
				'--bg-body'                    => '#f8f5fc',
				'--header-background'          => '#faf5ff',
				'--footer-background'          => '#18181b',
				'--accent-background-1'        => '#4c1d95',
				'--accent-background-2'        => '#7c3aed',
			),
		),
		'rose'      => array(
			'label'       => __( 'Rose & Ruby / Rosa & Carmesí', 'stories' ),
			'description' => __( 'Estética audaz con tonos rubí, frambuesa y contrastes elegantes.', 'stories' ),
			'preview'     => array(
				'primary' => '#f43f5e',
				'accent'  => '#e11d48',
				'header'  => '#fff1f2',
				'footer'  => '#1f1315',
			),
			'vars'        => array(
				'--color-primary'              => '#f43f5e',
				'--color-primary-hover'        => '#e11d48',
				'--color-primary-light'        => '#ffe4e6',
				'--color-primary-accent'       => '#be123c',
				'--color-primary-accent-light' => '#fb7185',
				'--color-secondary'            => '#fecdd3',
				'--color-tertiary'             => '#fda4af',
				'--color-quaternary'           => '#fff1f2',
				'--bg-body'                    => '#faf5f6',
				'--header-background'          => '#fff1f2',
				'--footer-background'          => '#1f1315',
				'--accent-background-1'        => '#881337',
				'--accent-background-2'        => '#e11d48',
			),
		),
		'charcoal'  => array(
			'label'       => __( 'Charcoal & Slate / Monocromo Minimalista', 'stories' ),
			'description' => __( 'Diseño editorial sobrio y de alto contraste en escala de grises y pizarra.', 'stories' ),
			'preview'     => array(
				'primary' => '#334155',
				'accent'  => '#0f172a',
				'header'  => '#f8fafc',
				'footer'  => '#020617',
			),
			'vars'        => array(
				'--color-primary'              => '#334155',
				'--color-primary-hover'        => '#1e293b',
				'--color-primary-light'        => '#f1f5f9',
				'--color-primary-accent'       => '#0f172a',
				'--color-primary-accent-light' => '#64748b',
				'--color-secondary'            => '#e2e8f0',
				'--color-tertiary'             => '#cbd5e1',
				'--color-quaternary'           => '#f8fafc',
				'--bg-body'                    => '#f1f5f9',
				'--header-background'          => '#f8fafc',
				'--footer-background'          => '#020617',
				'--accent-background-1'        => '#1e293b',
				'--accent-background-2'        => '#475569',
			),
		),
		'dark'      => array(
			'label'       => __( 'Midnight Navy / Modo Oscuro Azul', 'stories' ),
			'description' => __( 'Elegante modo oscuro en tonos azul medianoche y cobalto con acentos celestes luminosos.', 'stories' ),
			'preview'     => array(
				'primary' => '#38bdf8',
				'accent'  => '#0ea5e9',
				'header'  => '#0b1329',
				'footer'  => '#030712',
			),
			'vars'        => array(
				'--color-primary'              => '#38bdf8',
				'--color-primary-hover'        => '#0ea5e9',
				'--color-primary-light'        => '#0f2744',
				'--color-primary-accent'       => '#0284c7',
				'--color-primary-accent-light' => '#7dd3fc',
				'--color-secondary'            => '#172554',
				'--color-tertiary'             => '#1e3a8a',
				'--color-quaternary'           => '#1e293b',
				'--color-text-heading'         => '#f0f9ff',
				'--color-text-body'            => '#cbd5e1',
				'--color-text-muted'           => '#94a3b8',
				'--bg-body'                    => '#060b14',
				'--bg-card'                    => '#0f172a',
				'--bg-dark'                    => '#030712',
				'--bg-quote-front'             => '#0f172a',
				'--border-light'               => '#1e293b',
				'--header-background'          => '#0b1329',
				'--footer-background'          => '#02040a',
				'--accent-background-1'        => '#1e3a8a',
				'--accent-background-2'        => '#0284c7',
			),
		),
		'custom'    => array(
			'label'       => __( 'Personalizado (Colores a Medida)', 'stories' ),
			'description' => __( 'Configura manualmente tu propia combinación de colores con selectores.', 'stories' ),
			'preview'     => array(
				'primary' => '#8bc34a',
				'accent'  => '#47b23c',
				'header'  => '#eaeeea',
				'footer'  => '#092327',
			),
			'vars'        => array(),
		),
	);
}

/**
 * Get active color scheme slug.
 *
 * @return string
 */
function stories_get_active_color_scheme() {
	$options = get_option( 'stories_theme_options', array() );
	if ( ! empty( $options['color_scheme'] ) ) {
		return sanitize_key( $options['color_scheme'] );
	}
	return 'evergreen';
}

/**
 * Generate CSS variables for the active color scheme.
 *
 * @return string CSS string.
 */
function stories_get_color_scheme_css() {
	$scheme_key = stories_get_active_color_scheme();
	$schemes    = stories_get_color_schemes();
	$options    = get_option( 'stories_theme_options', array() );

	$css_vars = array();

	if ( 'custom' === $scheme_key ) {
		if ( ! empty( $options['custom_color_primary'] ) ) {
			$primary                                 = sanitize_hex_color( $options['custom_color_primary'] );
			$css_vars['--color-primary']             = $primary;
			$css_vars['--color-primary-hover']       = $primary;
			$css_vars['--color-primary-light']       = 'color-mix(in srgb, ' . $primary . ' 15%, #ffffff)';
			$css_vars['--color-primary-accent-light'] = $primary;
		}
		if ( ! empty( $options['custom_color_accent'] ) ) {
			$accent                            = sanitize_hex_color( $options['custom_color_accent'] );
			$css_vars['--color-primary-accent'] = $accent;
			$css_vars['--accent-background-1'] = $accent;
			$css_vars['--accent-background-2'] = 'color-mix(in srgb, ' . $accent . ' 60%, #ffffff)';
		}
		if ( ! empty( $options['custom_bg_body'] ) ) {
			$css_vars['--bg-body'] = sanitize_hex_color( $options['custom_bg_body'] );
		}
		if ( ! empty( $options['custom_header_bg'] ) ) {
			$css_vars['--header-background'] = sanitize_hex_color( $options['custom_header_bg'] );
		}
		if ( ! empty( $options['custom_footer_bg'] ) ) {
			$css_vars['--footer-background'] = sanitize_hex_color( $options['custom_footer_bg'] );
		}
	} elseif ( isset( $schemes[ $scheme_key ] ) && ! empty( $schemes[ $scheme_key ]['vars'] ) ) {
		$css_vars = $schemes[ $scheme_key ]['vars'];
	}

	if ( empty( $css_vars ) ) {
		return '';
	}

	$css = ":root {\n";
	foreach ( $css_vars as $var => $value ) {
		$css .= "\t{$var}: {$value};\n";
	}
	$css .= "}\n";

	return $css;
}

/**
 * Enqueue dynamic color scheme CSS inline with stories-main.
 */
function stories_enqueue_color_scheme_css() {
	$css = stories_get_color_scheme_css();
	if ( ! empty( $css ) ) {
		wp_add_inline_style( 'stories-main', $css );
	}
}
add_action( 'wp_enqueue_scripts', 'stories_enqueue_color_scheme_css', 20 );
