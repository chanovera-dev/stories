<?php
/**
 * Stories WP Admin Panel Options
 *
 * Configures the custom theme administration options panel in WordPress admin.
 *
 * @package Stories
 * @subpackage Inc
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register theme options page in WordPress Admin menu.
 */
function stories_add_admin_menu() {
	add_menu_page(
		__( 'Opciones del tema "Stories"', 'stories' ),
		__( 'Stories', 'stories' ),
		'manage_options',
		'stories_options',
		'stories_render_options_page',
		'dashicons-admin-generic',
		59
	);
}
add_action( 'admin_menu', 'stories_add_admin_menu' );

/**
 * Enqueue Admin Styles for Stories Options page.
 *
 * @param string $hook The current admin page hook.
 */
function stories_admin_options_scripts( $hook ) {
	if ( false === strpos( $hook, 'stories_options' ) ) {
		return;
	}

	wp_enqueue_style( 'wp-color-picker' );
	wp_enqueue_script( 'wp-color-picker' );

	wp_enqueue_style(
		'stories-admin-options',
		STORIES_URI . '/assets/css/admin-options.css',
		array( 'wp-color-picker' ),
		STORIES_VERSION . '.' . time()
	);

	wp_add_inline_script(
		'wp-color-picker',
		'jQuery(document).ready(function($){
			$(".stories-color-picker").wpColorPicker();

			function toggleCustomColors() {
				var selected = $("input[name=\"stories_theme_options[color_scheme]\"]:checked").val() || $("#stories_color_scheme_select").val();
				if (selected === "custom") {
					$(".stories-custom-color-row").closest("tr").show();
				} else {
					$(".stories-custom-color-row").closest("tr").hide();
				}
			}

			$("input[name=\"stories_theme_options[color_scheme]\"], #stories_color_scheme_select").on("change", function() {
				$(".stories-color-scheme-card").removeClass("is-selected");
				$(this).closest(".stories-color-scheme-card").addClass("is-selected");
				toggleCustomColors();
			});

			toggleCustomColors();
		});'
	);
}
add_action( 'admin_enqueue_scripts', 'stories_admin_options_scripts' );

/**
 * Register theme settings using the Settings API.
 */
function stories_settings_init() {
	register_setting(
		'stories_options_group',
		'stories_theme_options',
		array(
			'type'              => 'array',
			'sanitize_callback' => 'stories_sanitize_theme_options',
		)
	);

	/* -------------------------------------------------------------------------
	 * Section 1: Google Tag Manager
	 * ------------------------------------------------------------------------- */
	add_settings_section(
		'stories_gtm_section',
		__( 'Google Tag Manager', 'stories' ),
		'__return_empty_string',
		'stories_options'
	);

	add_settings_field(
		'gtm_enable',
		__( 'Activar Google Tag Manager', 'stories' ),
		'stories_render_toggle_field',
		'stories_options',
		'stories_gtm_section',
		array(
			'id'          => 'gtm_enable',
			'description' => '',
		)
	);

	add_settings_field(
		'gtm_id',
		__( 'Google Tag Manager ID', 'stories' ),
		'stories_gtm_id_render',
		'stories_options',
		'stories_gtm_section'
	);

	/* -------------------------------------------------------------------------
	 * Section 2: Optimización y Limpieza del <head>
	 * ------------------------------------------------------------------------- */
	add_settings_section(
		'stories_head_cleanup_section',
		__( 'Optimización y Limpieza del HEAD', 'stories' ),
		'__return_empty_string',
		'stories_options'
	);

	add_settings_field(
		'disable_emojis',
		__( 'Desactivar Emojis', 'stories' ),
		'stories_render_toggle_field',
		'stories_options',
		'stories_head_cleanup_section',
		array(
			'id'          => 'disable_emojis',
			'description' => __( 'Elimina scripts y estilos de emojis antiguos para usar emojis nativos del sistema y reducir peticiones JS.', 'stories' ),
		)
	);

	add_settings_field(
		'disable_block_styles',
		__( 'Desactivar CSS de Bloques', 'stories' ),
		'stories_render_toggle_field',
		'stories_options',
		'stories_head_cleanup_section',
		array(
			'id'          => 'disable_block_styles',
			'description' => __( 'Evita cargar la hoja de estilos de Gutenberg (wp-block-library y global-styles) en el frontend.', 'stories' ),
		)
	);

	add_settings_field(
		'clean_meta_tags',
		__( 'Limpiar Meta Tags innecesarios', 'stories' ),
		'stories_render_toggle_field',
		'stories_options',
		'stories_head_cleanup_section',
		array(
			'id'          => 'clean_meta_tags',
			'description' => __( 'Oculta la versión de WordPress (wp_generator), enlaces RSD, Windows Live Writer y shortlinks.', 'stories' ),
		)
	);

	add_settings_field(
		'disable_oembed',
		__( 'Desactivar scripts de oEmbed', 'stories' ),
		'stories_render_toggle_field',
		'stories_options',
		'stories_head_cleanup_section',
		array(
			'id'          => 'disable_oembed',
			'description' => __( 'Desactiva los scripts de incrustación de oEmbed y enlaces de descubrimiento en el head.', 'stories' ),
		)
	);

	/* -------------------------------------------------------------------------
	 * Section 3: Estilos y Apariencia
	 * ------------------------------------------------------------------------- */
	add_settings_section(
		'stories_appearance_section',
		__( 'Estilos y Apariencia', 'stories' ),
		'__return_empty_string',
		'stories_options'
	);

	add_settings_field(
		'enable_is_chromium',
		__( 'Activar clase .is-chromium', 'stories' ),
		'stories_render_toggle_field',
		'stories_options',
		'stories_appearance_section',
		array(
			'id'          => 'enable_is_chromium',
			'default'     => 1,
			'description' => __( 'Activa o desactiva la función en JavaScript que detecta navegadores Chromium y añade la clase .is-chromium al documento.', 'stories' ),
		)
	);

	add_settings_field(
		'enable_rounded',
		__( 'Cargar rounded.css', 'stories' ),
		'stories_render_toggle_field',
		'stories_options',
		'stories_appearance_section',
		array(
			'id'          => 'enable_rounded',
			'default'     => 1,
			'description' => __( 'Activa o desactiva la carga de la hoja de estilos rounded.css (bordes redondeados y soporte de squircle para Chromium).', 'stories' ),
		)
	);

	add_settings_field(
		'color_scheme',
		__( 'Esquema y Paleta de Color', 'stories' ),
		'stories_render_color_scheme_field',
		'stories_options',
		'stories_appearance_section'
	);

	add_settings_field(
		'custom_color_primary',
		__( 'Color Primario Personalizado', 'stories' ),
		'stories_render_color_picker_field',
		'stories_options',
		'stories_appearance_section',
		array(
			'id'          => 'custom_color_primary',
			'default'     => '#8bc34a',
			'description' => __( 'Color principal para botones, bordes activos y elementos destacados.', 'stories' ),
		)
	);

	add_settings_field(
		'custom_color_accent',
		__( 'Color de Acento Personalizado', 'stories' ),
		'stories_render_color_picker_field',
		'stories_options',
		'stories_appearance_section',
		array(
			'id'          => 'custom_color_accent',
			'default'     => '#47b23c',
			'description' => __( 'Color secundario/acento para estados hover y destellos visuales.', 'stories' ),
		)
	);

	add_settings_field(
		'custom_bg_body',
		__( 'Fondo General del Sitio (Body)', 'stories' ),
		'stories_render_color_picker_field',
		'stories_options',
		'stories_appearance_section',
		array(
			'id'          => 'custom_bg_body',
			'default'     => '#f5f7f5',
			'description' => __( 'Color de fondo general para el body/página.', 'stories' ),
		)
	);

	add_settings_field(
		'custom_header_bg',
		__( 'Fondo de Cabecera Personalizado', 'stories' ),
		'stories_render_color_picker_field',
		'stories_options',
		'stories_appearance_section',
		array(
			'id'          => 'custom_header_bg',
			'default'     => '#eaeeea',
			'description' => __( 'Color de fondo para la cabecera principal y barras superiores.', 'stories' ),
		)
	);

	add_settings_field(
		'custom_footer_bg',
		__( 'Fondo de Pie de Página Personalizado', 'stories' ),
		'stories_render_color_picker_field',
		'stories_options',
		'stories_appearance_section',
		array(
			'id'          => 'custom_footer_bg',
			'default'     => '#092327',
			'description' => __( 'Color de fondo para el footer y superposiciones oscuras.', 'stories' ),
		)
	);

	add_settings_field(
		'loop_design',
		__( 'Diseño del Loop (Tarjetas de Posts)', 'stories' ),
		'stories_render_loop_design_field',
		'stories_options',
		'stories_appearance_section'
	);

	add_settings_field(
		'loop_gap',
		__( 'Espaciado del Loop (Gap)', 'stories' ),
		'stories_render_loop_gap_field',
		'stories_options',
		'stories_appearance_section'
	);

	add_settings_field(
		'pagination_style',
		__( 'Estilo de Paginación', 'stories' ),
		'stories_render_select_field',
		'stories_options',
		'stories_appearance_section',
		array(
			'id'          => 'pagination_style',
			'default'     => 'default',
			'options'     => array(
				'default'   => __( 'Clásico (Tarjetas con línea inferior)', 'stories' ),
				'capsule'   => __( 'Cápsula Flotante (Glassmorphism)', 'stories' ),
				'segmented' => __( 'Control Segmentado (Barra compacta)', 'stories' ),
			),
			'description' => __( 'Selecciona el diseño visual aplicado a la barra de paginación (.navigation.pagination).', 'stories' ),
		)
	);
}
add_action( 'admin_init', 'stories_settings_init' );

/**
 * Sanitize theme options array.
 *
 * @param array $input Raw input data.
 * @return array Sanitized options.
 */
function stories_sanitize_theme_options( $input ) {
	$sanitized = array();

	// Checkbox toggles
	$toggle_keys = array( 'gtm_enable', 'disable_emojis', 'disable_block_styles', 'clean_meta_tags', 'disable_oembed', 'enable_is_chromium', 'enable_rounded' );
	foreach ( $toggle_keys as $key ) {
		$sanitized[ $key ] = ! empty( $input[ $key ] ) ? 1 : 0;
	}

	// Text fields
	if ( isset( $input['gtm_id'] ) ) {
		$sanitized['gtm_id'] = sanitize_text_field( trim( $input['gtm_id'] ) );
	}

	// Color Scheme
	$available_schemes = function_exists( 'stories_get_color_schemes' ) ? array_keys( stories_get_color_schemes() ) : array( 'evergreen' );
	if ( isset( $input['color_scheme'] ) && in_array( $input['color_scheme'], $available_schemes, true ) ) {
		$sanitized['color_scheme'] = $input['color_scheme'];
	} else {
		$sanitized['color_scheme'] = 'evergreen';
	}

	// Custom Colors
	$color_keys = array( 'custom_color_primary', 'custom_color_accent', 'custom_bg_body', 'custom_header_bg', 'custom_footer_bg' );
	foreach ( $color_keys as $color_key ) {
		if ( ! empty( $input[ $color_key ] ) ) {
			$sanitized[ $color_key ] = sanitize_hex_color( $input[ $color_key ] );
		}
	}

	// Loop design
	$available_loop_designs = function_exists( 'stories_get_available_loop_designs' ) ? array_keys( stories_get_available_loop_designs() ) : array( 'default' );
	if ( isset( $input['loop_design'] ) && in_array( $input['loop_design'], $available_loop_designs, true ) ) {
		$sanitized['loop_design'] = $input['loop_design'];
	} else {
		$sanitized['loop_design'] = 'default';
	}

	// Loop gap
	if ( isset( $input['loop_gap'] ) ) {
		$gap = sanitize_text_field( trim( $input['loop_gap'] ) );
		if ( '' !== $gap ) {
			if ( is_numeric( $gap ) ) {
				$gap = floatval( $gap ) <= 5 ? $gap . 'rem' : $gap . 'px';
			}
			$sanitized['loop_gap'] = $gap;
		} else {
			$sanitized['loop_gap'] = '1rem';
		}
	}

	// Pagination style
	$allowed_pagination_styles = array( 'default', 'capsule', 'segmented' );
	if ( isset( $input['pagination_style'] ) && in_array( $input['pagination_style'], $allowed_pagination_styles, true ) ) {
		$sanitized['pagination_style'] = $input['pagination_style'];
	} else {
		$sanitized['pagination_style'] = 'default';
	}

	return $sanitized;
}

/**
 * Render visual selector field for Color Schemes.
 */
function stories_render_color_scheme_field() {
	$options       = get_option( 'stories_theme_options', array() );
	$current_value = isset( $options['color_scheme'] ) ? $options['color_scheme'] : 'evergreen';
	$schemes       = function_exists( 'stories_get_color_schemes' ) ? stories_get_color_schemes() : array();
	?>
	<div class="stories-color-schemes-grid">
		<?php foreach ( $schemes as $slug => $scheme ) : 
			$is_selected = ( $current_value === $slug );
			?>
			<label class="stories-color-scheme-card <?php echo $is_selected ? 'is-selected' : ''; ?>" for="scheme_<?php echo esc_attr( $slug ); ?>">
				<input type="radio" name="stories_theme_options[color_scheme]" id="scheme_<?php echo esc_attr( $slug ); ?>" value="<?php echo esc_attr( $slug ); ?>" <?php checked( $current_value, $slug ); ?>>
				<div class="scheme-preview-bar">
					<span class="preview-swatch" style="background-color: <?php echo esc_attr( $scheme['preview']['primary'] ); ?>;" title="<?php esc_attr_e( 'Color Primario', 'stories' ); ?>"></span>
					<span class="preview-swatch" style="background-color: <?php echo esc_attr( $scheme['preview']['accent'] ); ?>;" title="<?php esc_attr_e( 'Color Acento', 'stories' ); ?>"></span>
					<span class="preview-swatch" style="background-color: <?php echo esc_attr( $scheme['preview']['header'] ); ?>;" title="<?php esc_attr_e( 'Cabecera', 'stories' ); ?>"></span>
					<span class="preview-swatch" style="background-color: <?php echo esc_attr( $scheme['preview']['footer'] ); ?>;" title="<?php esc_attr_e( 'Footer', 'stories' ); ?>"></span>
				</div>
				<div class="scheme-info">
					<strong class="scheme-title"><?php echo esc_html( $scheme['label'] ); ?></strong>
					<span class="scheme-desc"><?php echo esc_html( $scheme['description'] ); ?></span>
				</div>
			</label>
		<?php endforeach; ?>
	</div>
	<?php
}

/**
 * Render field for WP Color Picker.
 *
 * @param array $args Field arguments.
 */
function stories_render_color_picker_field( $args ) {
	$options = get_option( 'stories_theme_options', array() );
	$id      = $args['id'];
	$default = isset( $args['default'] ) ? $args['default'] : '#000000';
	$value   = ! empty( $options[ $id ] ) ? $options[ $id ] : $default;
	?>
	<div class="stories-custom-color-row">
		<input type="text" name="stories_theme_options[<?php echo esc_attr( $id ); ?>]" value="<?php echo esc_attr( $value ); ?>" class="stories-color-picker" data-default-color="<?php echo esc_attr( $default ); ?>">
		<?php if ( ! empty( $args['description'] ) ) : ?>
			<p class="description"><?php echo esc_html( $args['description'] ); ?></p>
		<?php endif; ?>
	</div>
	<?php
}

/**
 * Render field for Loop Design selection.
 */
function stories_render_loop_design_field() {
	$options       = get_option( 'stories_theme_options', array() );
	$current_value = isset( $options['loop_design'] ) ? $options['loop_design'] : 'default';
	$choices       = function_exists( 'stories_get_available_loop_designs' ) ? stories_get_available_loop_designs() : array( 'default' => __( 'Por defecto (template-parts/)', 'stories' ) );
	?>
	<select name="stories_theme_options[loop_design]" id="stories_loop_design" class="stories-select-field">
		<?php foreach ( $choices as $val => $label ) : ?>
			<option value="<?php echo esc_attr( $val ); ?>" <?php selected( $current_value, $val ); ?>>
				<?php echo esc_html( $label ); ?>
			</option>
		<?php endforeach; ?>
	</select>
	<p class="description">
		<?php esc_html_e( 'Selecciona el tema/plantilla para los posts en el loop. Puedes crear nuevas carpetas dentro de template-parts/ (ej: loop00, loop01, loop-minimal) y aparecerán automáticamente en esta lista.', 'stories' ); ?>
	</p>
	<?php
}

/**
 * Render field for Loop Gap selection.
 */
function stories_render_loop_gap_field() {
	$options       = get_option( 'stories_theme_options', array() );
	$current_value = ! empty( $options['loop_gap'] ) ? $options['loop_gap'] : '1rem';
	?>
	<input type="text" name="stories_theme_options[loop_gap]" id="stories_loop_gap" value="<?php echo esc_attr( $current_value ); ?>" class="stories-select-field stories-gap-input" placeholder="1rem">
	<p class="description">
		<?php esc_html_e( 'Espacio de separación entre las tarjetas del loop (ej: 1rem, 20px).', 'stories' ); ?>
	</p>
	<?php
}

/**
 * Generic render function for macOS toggle fields.
 *
 * @param array $args Field arguments.
 */
function stories_render_toggle_field( $args ) {
	$options = get_option( 'stories_theme_options', array() );
	$id      = $args['id'];
	$default = isset( $args['default'] ) ? $args['default'] : 0;
	$enabled = isset( $options[ $id ] ) ? ! empty( $options[ $id ] ) : (bool) $default;
	?>
	<div class="stories-toggle-wrapper">
		<label class="stories-toggle" for="stories_<?php echo esc_attr( $id ); ?>">
			<input type="checkbox" name="stories_theme_options[<?php echo esc_attr( $id ); ?>]" value="1" <?php checked( true, $enabled ); ?> id="stories_<?php echo esc_attr( $id ); ?>">
			<span class="slider"></span>
		</label>
	</div>
	<?php if ( ! empty( $args['description'] ) ) : ?>
		<p class="description"><?php echo esc_html( $args['description'] ); ?></p>
	<?php endif; ?>
	<?php
}

/**
 * Generic render function for select dropdown fields.
 *
 * @param array $args Field arguments.
 */
function stories_render_select_field( $args ) {
	$options       = get_option( 'stories_theme_options', array() );
	$id            = $args['id'];
	$default       = isset( $args['default'] ) ? $args['default'] : '';
	$current_value = isset( $options[ $id ] ) ? $options[ $id ] : $default;
	$choices       = isset( $args['options'] ) ? (array) $args['options'] : array();
	?>
	<select name="stories_theme_options[<?php echo esc_attr( $id ); ?>]" id="stories_<?php echo esc_attr( $id ); ?>" class="stories-select-field">
		<?php foreach ( $choices as $val => $label ) : ?>
			<option value="<?php echo esc_attr( $val ); ?>" <?php selected( $current_value, $val ); ?>>
				<?php echo esc_html( $label ); ?>
			</option>
		<?php endforeach; ?>
	</select>
	<?php if ( ! empty( $args['description'] ) ) : ?>
		<p class="description"><?php echo esc_html( $args['description'] ); ?></p>
	<?php endif; ?>
	<?php
}

/**
 * Render field for GTM ID.
 */
function stories_gtm_id_render() {
	$options = get_option( 'stories_theme_options', array() );
	$value   = isset( $options['gtm_id'] ) ? $options['gtm_id'] : '';
	?>
	<input type="text" name="stories_theme_options[gtm_id]" value="<?php echo esc_attr( $value ); ?>" class="regular-text" placeholder="GTM-XXXXXXX">
	<p class="description"><?php esc_html_e( 'Ingresa el ID de Google Tag Manager (ej. GTM-XXXXXXX).', 'stories' ); ?></p>
	<?php
}

/**
 * Render HTML for the Theme Options page using native WordPress layout.
 */
function stories_render_options_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	?>
	<div class="wrap">
		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
		<form action="options.php" method="post">
			<?php
			settings_fields( 'stories_options_group' );
			do_settings_sections( 'stories_options' );
			submit_button();
			?>
		</form>
	</div>
	<?php
}
