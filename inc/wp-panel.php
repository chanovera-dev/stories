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

	wp_enqueue_media();
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

			// Media uploader for footer logo & images
			$(document).on("click", ".stories-media-upload-btn", function(e) {
				e.preventDefault();
				var button = $(this);
				var targetId = button.data("target");
				var wrapper = $("#wrapper_" + targetId);
				var input = $("#" + targetId);
				var preview = wrapper.find(".stories-media-preview");
				var removeBtn = wrapper.find(".stories-media-remove-btn");

				var frame = wp.media({
					title: "Seleccionar o subir imagen",
					button: { text: "Usar esta imagen" },
					multiple: false
				});

				frame.on("select", function() {
					var attachment = frame.state().get("selection").first().toJSON();
					input.val(attachment.url);
					preview.find("img").attr("src", attachment.url);
					preview.show();
					removeBtn.show();
					button.text("Cambiar Imagen");
				});

				frame.open();
			});

			$(document).on("click", ".stories-media-remove-btn", function(e) {
				e.preventDefault();
				var button = $(this);
				var targetId = button.data("target");
				var wrapper = $("#wrapper_" + targetId);
				var input = $("#" + targetId);
				var preview = wrapper.find(".stories-media-preview");
				var uploadBtn = wrapper.find(".stories-media-upload-btn");

				input.val("");
				preview.hide().find("img").attr("src", "");
				button.hide();
				uploadBtn.text("Seleccionar Imagen");
			});
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
	 * Section 3: Soporte Multilenguaje (i18n)
	 * ------------------------------------------------------------------------- */
	add_settings_section(
		'stories_i18n_section',
		__( 'Soporte Multilenguaje', 'stories' ),
		'__return_empty_string',
		'stories_options'
	);

	add_settings_field(
		'enable_multilingual',
		__( 'Activar Soporte Multilenguaje', 'stories' ),
		'stories_render_toggle_field',
		'stories_options',
		'stories_i18n_section',
		array(
			'id'          => 'enable_multilingual',
			'default'     => 0,
			'description' => __( 'Activa el selector de idiomas en la cabecera (🇲🇽 Español / 🇺🇸 English), la clonación y emparejamiento de entradas/páginas y el filtrado por idioma en los loops. Desactivado por defecto.', 'stories' ),
		)
	);

	/* -------------------------------------------------------------------------
	 * Section 4: Estilos y Apariencia
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

	/* -------------------------------------------------------------------------
	 * Section 5: Pie de Página (Footer)
	 * ------------------------------------------------------------------------- */
	add_settings_section(
		'stories_footer_section',
		__( 'Ajustes del Pie de Página (Footer)', 'stories' ),
		'__return_empty_string',
		'stories_options'
	);

	add_settings_field(
		'footer_logo',
		__( 'Logo del Pie de Página', 'stories' ),
		'stories_render_media_field',
		'stories_options',
		'stories_footer_section',
		array(
			'id'          => 'footer_logo',
			'description' => __( 'Opcional. Si se define un logo, se mostrará en lugar del título de la sección Sobre nosotros.', 'stories' ),
		)
	);

	add_settings_field(
		'footer_title',
		__( 'Título de la Sección Sobre Nosotros', 'stories' ),
		'stories_render_bilingual_title_field',
		'stories_options',
		'stories_footer_section'
	);

	add_settings_field(
		'footer_bio',
		__( 'Descripción / Biografía del Sitio', 'stories' ),
		'stories_render_bilingual_bio_field',
		'stories_options',
		'stories_footer_section'
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
	$toggle_keys = array( 'gtm_enable', 'disable_emojis', 'disable_block_styles', 'clean_meta_tags', 'disable_oembed', 'enable_is_chromium', 'enable_rounded', 'enable_multilingual' );
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

	// Footer fields
	if ( isset( $input['footer_title'] ) ) {
		$sanitized['footer_title'] = sanitize_text_field( trim( $input['footer_title'] ) );
	}
	if ( isset( $input['footer_title_en'] ) ) {
		$sanitized['footer_title_en'] = sanitize_text_field( trim( $input['footer_title_en'] ) );
	}
	if ( isset( $input['footer_bio'] ) ) {
		$sanitized['footer_bio'] = wp_kses_post( trim( $input['footer_bio'] ) );
	}
	if ( isset( $input['footer_bio_en'] ) ) {
		$sanitized['footer_bio_en'] = wp_kses_post( trim( $input['footer_bio_en'] ) );
	}
	if ( isset( $input['footer_logo'] ) ) {
		$sanitized['footer_logo'] = esc_url_raw( trim( $input['footer_logo'] ) );
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
 * Render text input field.
 *
 * @param array $args Field arguments.
 */
function stories_render_text_field( $args ) {
	$options       = get_option( 'stories_theme_options', array() );
	$id            = $args['id'];
	$default       = isset( $args['default'] ) ? $args['default'] : '';
	$current_value = isset( $options[ $id ] ) ? $options[ $id ] : $default;
	$placeholder   = isset( $args['placeholder'] ) ? $args['placeholder'] : '';
	?>
	<input type="text" name="stories_theme_options[<?php echo esc_attr( $id ); ?>]" id="stories_<?php echo esc_attr( $id ); ?>" value="<?php echo esc_attr( $current_value ); ?>" placeholder="<?php echo esc_attr( $placeholder ); ?>" class="regular-text">
	<?php if ( ! empty( $args['description'] ) ) : ?>
		<p class="description"><?php echo esc_html( $args['description'] ); ?></p>
	<?php endif; ?>
	<?php
}

/**
 * Render textarea field.
 *
 * @param array $args Field arguments.
 */
function stories_render_textarea_field( $args ) {
	$options       = get_option( 'stories_theme_options', array() );
	$id            = $args['id'];
	$default       = isset( $args['default'] ) ? $args['default'] : '';
	$current_value = isset( $options[ $id ] ) ? $options[ $id ] : $default;
	$rows          = isset( $args['rows'] ) ? intval( $args['rows'] ) : 4;
	?>
	<textarea name="stories_theme_options[<?php echo esc_attr( $id ); ?>]" id="stories_<?php echo esc_attr( $id ); ?>" rows="<?php echo esc_attr( $rows ); ?>" class="large-text"><?php echo esc_textarea( $current_value ); ?></textarea>
	<?php if ( ! empty( $args['description'] ) ) : ?>
		<p class="description"><?php echo esc_html( $args['description'] ); ?></p>
	<?php endif; ?>
	<?php
}

/**
 * Render bilingual title field (Spanish & English side-by-side).
 */
function stories_render_bilingual_title_field() {
	$options        = get_option( 'stories_theme_options', array() );
	$title_es       = isset( $options['footer_title'] ) ? $options['footer_title'] : '';
	$title_en       = isset( $options['footer_title_en'] ) ? $options['footer_title_en'] : '';
	$site_name      = get_bloginfo( 'name' );
	$placeholder_es = __( 'Sobre ', 'stories' ) . $site_name;
	$placeholder_en = __( 'About ', 'stories' ) . $site_name;

	if ( ! function_exists( 'stories_is_multilingual_enabled' ) || ! stories_is_multilingual_enabled() ) {
		?>
		<input type="text" name="stories_theme_options[footer_title]" id="stories_footer_title" value="<?php echo esc_attr( $title_es ); ?>" placeholder="<?php echo esc_attr( $placeholder_es ); ?>" class="regular-text">
		<p class="description">
			<?php esc_html_e( 'Título que se muestra en la primera columna del footer si no hay logo asignado.', 'stories' ); ?>
		</p>
		<?php
		return;
	}
	?>
	<div class="stories-bilingual-container">
		<div class="stories-bilingual-col">
			<label class="stories-bilingual-label" for="stories_footer_title">
				<span class="stories-bilingual-flag" aria-hidden="true">🇲🇽</span>
				<span class="stories-bilingual-lang"><?php esc_html_e( 'Español (Principal)', 'stories' ); ?></span>
			</label>
			<input type="text" name="stories_theme_options[footer_title]" id="stories_footer_title" value="<?php echo esc_attr( $title_es ); ?>" placeholder="<?php echo esc_attr( $placeholder_es ); ?>" class="regular-text stories-bilingual-input">
			<p class="description">
				<?php esc_html_e( 'Título que se muestra en la primera columna del footer si no hay logo asignado.', 'stories' ); ?>
			</p>
		</div>
		<div class="stories-bilingual-col">
			<label class="stories-bilingual-label" for="stories_footer_title_en">
				<span class="stories-bilingual-flag" aria-hidden="true">🇺🇸</span>
				<span class="stories-bilingual-lang"><?php esc_html_e( 'Inglés / English', 'stories' ); ?></span>
			</label>
			<input type="text" name="stories_theme_options[footer_title_en]" id="stories_footer_title_en" value="<?php echo esc_attr( $title_en ); ?>" placeholder="<?php echo esc_attr( $placeholder_en ); ?>" class="regular-text stories-bilingual-input">
			<p class="description">
				<?php esc_html_e( 'Opcional. Si se deja vacío, se traduce automáticamente.', 'stories' ); ?>
			</p>
		</div>
	</div>
	<?php
}

/**
 * Render bilingual bio/description field (Spanish & English side-by-side).
 */
function stories_render_bilingual_bio_field() {
	$options    = get_option( 'stories_theme_options', array() );
	$default_es = __( 'Relatos y Cartas es un espacio dedicado a la creatividad y la expresión a través de las palabras. Aquí encontrarás cuentos, microcuentos, poemas e historias que buscan inspirar, emocionar y conectar con los lectores.', 'stories' );
	$bio_es     = isset( $options['footer_bio'] ) ? $options['footer_bio'] : $default_es;
	$bio_en     = isset( $options['footer_bio_en'] ) ? $options['footer_bio_en'] : '';

	if ( ! function_exists( 'stories_is_multilingual_enabled' ) || ! stories_is_multilingual_enabled() ) {
		?>
		<textarea name="stories_theme_options[footer_bio]" id="stories_footer_bio" rows="5" class="large-text"><?php echo esc_textarea( $bio_es ); ?></textarea>
		<p class="description">
			<?php esc_html_e( 'Texto descriptivo o biografía que aparece debajo del título/logo. Acepta etiquetas HTML básicas.', 'stories' ); ?>
		</p>
		<?php
		return;
	}
	?>
	<div class="stories-bilingual-container">
		<div class="stories-bilingual-col">
			<label class="stories-bilingual-label" for="stories_footer_bio">
				<span class="stories-bilingual-flag" aria-hidden="true">🇲🇽</span>
				<span class="stories-bilingual-lang"><?php esc_html_e( 'Español (Principal)', 'stories' ); ?></span>
			</label>
			<textarea name="stories_theme_options[footer_bio]" id="stories_footer_bio" rows="5" class="large-text stories-bilingual-textarea"><?php echo esc_textarea( $bio_es ); ?></textarea>
			<p class="description">
				<?php esc_html_e( 'Texto descriptivo o biografía que aparece debajo del título/logo. Acepta etiquetas HTML básicas.', 'stories' ); ?>
			</p>
		</div>
		<div class="stories-bilingual-col">
			<label class="stories-bilingual-label" for="stories_footer_bio_en">
				<span class="stories-bilingual-flag" aria-hidden="true">🇺🇸</span>
				<span class="stories-bilingual-lang"><?php esc_html_e( 'Inglés / English', 'stories' ); ?></span>
			</label>
			<textarea name="stories_theme_options[footer_bio_en]" id="stories_footer_bio_en" rows="5" class="large-text stories-bilingual-textarea" placeholder="<?php esc_attr_e( 'Si se deja vacío, se traduce automáticamente desde el texto en español.', 'stories' ); ?>"><?php echo esc_textarea( $bio_en ); ?></textarea>
			<p class="description">
				<?php esc_html_e( 'Opcional. Si se deja vacío, se traduce automáticamente.', 'stories' ); ?>
			</p>
		</div>
	</div>
	<?php
}

/**
 * Render media uploader field.
 *
 * @param array $args Field arguments.
 */
function stories_render_media_field( $args ) {
	$options       = get_option( 'stories_theme_options', array() );
	$id            = $args['id'];
	$current_value = isset( $options[ $id ] ) ? $options[ $id ] : '';
	?>
	<div class="stories-media-field-wrapper" id="wrapper_stories_<?php echo esc_attr( $id ); ?>">
		<input type="hidden" name="stories_theme_options[<?php echo esc_attr( $id ); ?>]" id="stories_<?php echo esc_attr( $id ); ?>" value="<?php echo esc_url( $current_value ); ?>">
		<div class="stories-media-preview" style="margin-bottom: 10px; max-width: 220px; <?php echo empty( $current_value ) ? 'display:none;' : ''; ?>">
			<img src="<?php echo esc_url( $current_value ); ?>" style="max-width: 100%; height: auto; display: block; border: 1px solid #ccd0d4; border-radius: 6px; padding: 6px; background: #fff;" alt="">
		</div>
		<button type="button" class="button stories-media-upload-btn" data-target="stories_<?php echo esc_attr( $id ); ?>">
			<?php echo empty( $current_value ) ? esc_html__( 'Seleccionar Imagen', 'stories' ) : esc_html__( 'Cambiar Imagen', 'stories' ); ?>
		</button>
		<button type="button" class="button button-link-delete stories-media-remove-btn" data-target="stories_<?php echo esc_attr( $id ); ?>" style="<?php echo empty( $current_value ) ? 'display:none;' : ''; ?>">
			<?php esc_html_e( 'Eliminar Imagen', 'stories' ); ?>
		</button>
		<?php if ( ! empty( $args['description'] ) ) : ?>
			<p class="description"><?php echo esc_html( $args['description'] ); ?></p>
		<?php endif; ?>
	</div>
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
