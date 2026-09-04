<?php
/**
 * Stories i18n & Language Switcher
 *
 * Handles multi-language support (ES/EN), locale switching, translation filter, and modern custom dropdown.
 *
 * @package Stories
 * @subpackage Inc
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get current active language code ('es' or 'en').
 *
 * @return string Current language code ('es' or 'en').
 */
function stories_get_current_language() {
	// If Polylang is active, delegate to Polylang.
	if ( function_exists( 'pll_current_language' ) ) {
		$pll_lang = pll_current_language( 'slug' );
		if ( ! empty( $pll_lang ) ) {
			return $pll_lang;
		}
	}

	// Check URL parameter first.
	if ( isset( $_GET['lang'] ) && in_array( $_GET['lang'], array( 'es', 'en' ), true ) ) {
		return sanitize_text_field( wp_unslash( $_GET['lang'] ) );
	}

	// Check POST parameter (e.g. for AJAX requests).
	if ( isset( $_POST['lang'] ) && in_array( $_POST['lang'], array( 'es', 'en' ), true ) ) {
		return sanitize_text_field( wp_unslash( $_POST['lang'] ) );
	}

	// If viewing a singular post on the frontend with an assigned language, honor it.
	if ( ! is_admin() && function_exists( 'is_singular' ) && is_singular() ) {
		$post_id = get_queried_object_id();
		if ( $post_id ) {
			$post_lang = get_post_meta( $post_id, '_stories_post_lang', true );
			if ( ! empty( $post_lang ) && in_array( $post_lang, array( 'es', 'en' ), true ) ) {
				return $post_lang;
			}
		}
	}

	// Check cookie.
	if ( isset( $_COOKIE['stories_lang'] ) && in_array( $_COOKIE['stories_lang'], array( 'es', 'en' ), true ) ) {
		return sanitize_text_field( wp_unslash( $_COOKIE['stories_lang'] ) );
	}

	// Default to site locale or Spanish.
	$site_locale = get_locale();
	if ( 0 === strpos( $site_locale, 'en' ) ) {
		return 'en';
	}

	return 'es';
}

/**
 * Filter WordPress determine_locale to switch locale dynamically.
 *
 * @param string $locale Current locale.
 * @return string Filtered locale.
 */
function stories_determine_locale( $locale ) {
	// Do not override in admin unless doing AJAX for frontend.
	if ( is_admin() && ! wp_doing_ajax() ) {
		return $locale;
	}

	// If Polylang is installed and handling translations, let it manage the locale.
	if ( function_exists( 'pll_current_language' ) ) {
		return $locale;
	}

	$lang = stories_get_current_language();

	if ( 'en' === $lang ) {
		return 'en_US';
	}

	return 'es_ES';
}
add_filter( 'determine_locale', 'stories_determine_locale', 20 );

/**
 * Set language cookie when requested via GET parameter.
 */
function stories_handle_language_cookie() {
	if ( isset( $_GET['lang'] ) && in_array( $_GET['lang'], array( 'es', 'en' ), true ) ) {
		$lang = sanitize_text_field( wp_unslash( $_GET['lang'] ) );
		$cookie_domain = defined( 'COOKIE_DOMAIN' ) && COOKIE_DOMAIN ? COOKIE_DOMAIN : '';

		setcookie(
			'stories_lang',
			$lang,
			array(
				'expires'  => time() + YEAR_IN_SECONDS,
				'path'     => COOKIEPATH ? COOKIEPATH : '/',
				'domain'   => $cookie_domain,
				'secure'   => is_ssl(),
				'httponly' => false,
				'samesite' => 'Lax',
			)
		);
		$_COOKIE['stories_lang'] = $lang;
	}
}
add_action( 'init', 'stories_handle_language_cookie', 1 );

/**
 * Return translation dictionary for a given language.
 *
 * @param string $lang Language code ('es' or 'en').
 * @return array Array of translated strings.
 */
function stories_get_translations( $lang ) {
	static $catalogs = null;

	if ( null === $catalogs ) {
		$catalogs = array(
			'en' => array(
				// Breadcrumbs & Navigation
				'Inicio'                                                                                                                                                                                                => 'Home',
				'Contenido más reciente'                                                                                                                                                                                => 'Latest content',
				'Página %1$d de %2$d'                                                                                                                                                                                   => 'Page %1$d of %2$d',
				'Página %d'                                                                                                                                                                                             => 'Page %d',
				'Página'                                                                                                                                                                                                => 'Page',
				'Páginas:'                                                                                                                                                                                              => 'Pages:',
				'Búsqueda: "%s"'                                                                                                                                                                                        => 'Search: "%s"',
				'Página no encontrada (404)'                                                                                                                                                                            => 'Page not found (404)',
				'Breadcrumb'                                                                                                                                                                                            => 'Breadcrumb',
				'Anterior'                                                                                                                                                                                              => 'Previous',
				'Siguiente'                                                                                                                                                                                             => 'Next',
				'Anteriores'                                                                                                                                                                                            => 'Previous',
				'Siguientes'                                                                                                                                                                                            => 'Next',
				'Timeline de entradas'                                                                                                                                                                                  => 'Posts timeline',
				'Contenido relacionado'                                                                                                                                                                                 => 'Related content',
				'%1$d de %2$d'                                                                                                                                                                                          => '%1$d of %2$d',

				// Header & Search
				'Buscar'                                                                                                                                                                                                => 'Search',
				'Buscar:'                                                                                                                                                                                               => 'Search for:',
				'Buscar por:'                                                                                                                                                                                           => 'Search for:',
				'Buscar &hellip;'                                                                                                                                                                                       => 'Search &hellip;',
				'Buscar...'                                                                                                                                                                                             => 'Search...',
				'submit button:::Buscar'                                                                                                                                                                                => 'Search',
				'submit button:::Search'                                                                                                                                                                                => 'Search',
				'label:::Buscar:'                                                                                                                                                                                       => 'Search for:',
				'label:::Buscar por:'                                                                                                                                                                                   => 'Search for:',
				'placeholder:::Buscar &hellip;'                                                                                                                                                                         => 'Search &hellip;',
				'Main header'                                                                                                                                                                                           => 'Main header',
				'Home'                                                                                                                                                                                                  => 'Home',
				'Skip to content'                                                                                                                                                                                       => 'Skip to content',
				'Open search'                                                                                                                                                                                           => 'Open search',
				'Close mobile search'                                                                                                                                                                                   => 'Close mobile search',
				'Activate the search'                                                                                                                                                                                   => 'Activate search',
				'Open mobile menu'                                                                                                                                                                                      => 'Open mobile menu',
				'Select language'                                                                                                                                                                                       => 'Select language',

				// Footer
				'Sobre '                                                                                                                                                                                                => 'About ',
				'Sobre Relatos & Cartas'                                                                                                                                                                                => 'About Relatos & Cartas',
				'Sobre Relatos &amp; Cartas'                                                                                                                                                                            => 'About Relatos &amp; Cartas',
				'Todos los Derechos Reservados'                                                                                                                                                                         => 'All Rights Reserved',
				'Diseñado y desarrollado por %s'                                                                                                                                                                        => 'Designed and developed by %s',
				'Relatos y Cartas es un espacio dedicado a la creatividad y la expresión a través de las palabras. Aquí encontrarás cuentos, microcuentos, poemas e historias que buscan inspirar, emocionar y conectar con los lectores.' => 'Relatos y Cartas is a space dedicated to creativity and expression through words. Here you will find short stories, micro-stories, poems, and tales that seek to inspire, excite, and connect with readers.',

				// Posts, Archives & Taxonomies
				'Resultados de búsqueda para: %s'                                                                                                                                                                       => 'Search results for: %s',
				'No se encontraron resultados'                                                                                                                                                                          => 'No results found',
				'Lo sentimos, no hubo resultados que coincidan con tu búsqueda. Por favor, intenta de nuevo con otras palabras clave.'                                                                                 => 'Sorry, but nothing matched your search terms. Please try again with different keywords.',
				'Parece que no podemos encontrar lo que estás buscando. Tal vez una búsqueda pueda ayudarte.'                                                                                                           => 'It seems we can’t find what you’re looking for. Perhaps searching can help.',
				'¿Listo para publicar tu primera historia? <a href="%1$s">Comienza aquí</a>.'                                                                                                                           => 'Ready to publish your first story? <a href="%1$s">Get started here</a>.',
				'hace %s'                                                                                                                                                                                               => '%s ago',
				'Publicado en %1$s'                                                                                                                                                                                     => 'Published in %1$s',
				'Etiquetado en %1$s'                                                                                                                                                                                    => 'Tagged in %1$s',
				'%d min de lectura'                                                                                                                                                                                     => '%d min read',
				'Categorías:'                                                                                                                                                                                           => 'Categories:',
				'Etiquetas:'                                                                                                                                                                                            => 'Tags:',
				'Categoría: %s'                                                                                                                                                                                         => 'Category: %s',
				'Etiqueta: %s'                                                                                                                                                                                          => 'Tag: %s',
				'Autor: %s'                                                                                                                                                                                             => 'Author: %s',
				'Año: %s'                                                                                                                                                                                               => 'Year: %s',
				'Mes: %s'                                                                                                                                                                                               => 'Month: %s',
				'Día: %s'                                                                                                                                                                                               => 'Day: %s',
				'Archivos: %s'                                                                                                                                                                                          => 'Archives: %s',
				'Archivos'                                                                                                                                                                                              => 'Archives',
				'Quitar me gusta a "%s"'                                                                                                                                                                                => 'Unlike "%s"',
				'Dar me gusta a "%s"'                                                                                                                                                                                   => 'Like "%s"',

				// Post Format Archive Titles (with context)
				'post format archive title:::Notas'                                                                                                                                                                     => 'Notes',
				'post format archive title:::Galerías'                                                                                                                                                                  => 'Galleries',
				'post format archive title:::Imágenes'                                                                                                                                                                  => 'Images',
				'post format archive title:::Vídeos'                                                                                                                                                                    => 'Videos',
				'post format archive title:::Citas'                                                                                                                                                                     => 'Quotes',
				'post format archive title:::Enlaces'                                                                                                                                                                   => 'Links',
				'post format archive title:::Estados'                                                                                                                                                                   => 'Statuses',
				'post format archive title:::Audios'                                                                                                                                                                    => 'Audios',
				'post format archive title:::Chats'                                                                                                                                                                     => 'Chats',

				// Single Templates Meta & Media
				'Nota por'                                                                                                                                                                                              => 'Note by',
				'Galería fotográfica y texto por'                                                                                                                                                                       => 'Photo gallery and text by',
				'Audio y texto por'                                                                                                                                                                                     => 'Audio and text by',
				'Fotografía y texto por'                                                                                                                                                                                => 'Photography and text by',
				'Ver vídeo'                                                                                                                                                                                             => 'Watch video',
				'Ver galería en pantalla completa'                                                                                                                                                                      => 'View gallery in fullscreen',
				'Pantalla completa'                                                                                                                                                                                     => 'Fullscreen',
				'Pantalla Completa'                                                                                                                                                                                     => 'Fullscreen',
				'Total de imágenes'                                                                                                                                                                                     => 'Total images',
				'%d Fotografías'                                                                                                                                                                                        => '%d Photographs',
				'Fecha de publicación'                                                                                                                                                                                  => 'Publication date',
				'Fecha'                                                                                                                                                                                                 => 'Date',
				'Autor'                                                                                                                                                                                                 => 'Author',
				'Resolución'                                                                                                                                                                                            => 'Resolution',
				'Tamaño de archivo'                                                                                                                                                                                     => 'File size',
				'Ir a comentarios'                                                                                                                                                                                      => 'Go to comments',
				'0 comentarios'                                                                                                                                                                                         => '0 comments',
				'1 comentario'                                                                                                                                                                                          => '1 comment',
				// Menus & Navigation Items
				'Otras páginas'                                                                                                                                                                                         => 'Other pages',
				'Menú principal'                                                                                                                                                                                        => 'Main menu',
				'Política de privacidad'                                                                                                                                                                                => 'Privacy Policy',
				'Aviso legal'                                                                                                                                                                                           => 'Legal Notice',
				'Política de cookies'                                                                                                                                                                                   => 'Cookie Policy',
				'Términos y condiciones'                                                                                                                                                                                => 'Terms and Conditions',
				'Contacto'                                                                                                                                                                                              => 'Contact',
				'Sobre mí'                                                                                                                                                                                              => 'About me',
				'Sobre nosotros'                                                                                                                                                                                        => 'About us',
				'Enlaces de interés'                                                                                                                                                                                    => 'Links of interest',
				'Enlaces útiles'                                                                                                                                                                                        => 'Useful links',
				'Redes sociales'                                                                                                                                                                                        => 'Social networks',
				'Prólogo'                                                                                                                                                                                               => 'Prologue',
				'Detrás del espejo'                                                                                                                                                                                     => 'Behind the mirror',
				'Blog'                                                                                                                                                                                                  => 'Blog',
				'Sin categoría'                                                                                                                                                                                         => 'Uncategorized',
			),
			'es' => array(
				// Menus & Navigation Items
				'Privacy Policy'                                                                                                                                                                                        => 'Política de privacidad',
				'Terms and Conditions'                                                                                                                                                                                  => 'Términos y condiciones',
				'Contact'                                                                                                                                                                                               => 'Contacto',
				'About me'                                                                                                                                                                                              => 'Sobre mí',
				'About us'                                                                                                                                                                                              => 'Sobre nosotros',
				'Other pages'                                                                                                                                                                                           => 'Otras páginas',
				'Main menu'                                                                                                                                                                                             => 'Menú principal',

				// General UI
				'Toggle Post Info'                                                                                                                                                                                      => 'Información de la entrada',
				'Toggle Post Content'                                                                                                                                                                                   => 'Contenido de la entrada',
				'Play / Pause'                                                                                                                                                                                          => 'Reproducir / Pausar',
				'Mute / Unmute'                                                                                                                                                                                         => 'Silenciar / Activar sonido',
				'Fullscreen'                                                                                                                                                                                            => 'Pantalla completa',
				'Video playback progress'                                                                                                                                                                               => 'Progreso del vídeo',
				'Audio playback progress'                                                                                                                                                                               => 'Progreso del audio',
				'View full image'                                                                                                                                                                                       => 'Ver imagen completa',
				'Pages:'                                                                                                                                                                                                => 'Páginas:',
				'Skip to content'                                                                                                                                                                                       => 'Saltar al contenido',
				'Oops! That page can&rsquo;t be found.'                                                                                                                                                                 => '¡Vaya! No se pudo encontrar esa página.',
				'It looks like nothing was found at this location. Maybe try a search?'                                                                                                                                 => 'Parece que no se encontró nada aquí. ¿Tal vez una búsqueda pueda ayudar?',
				'Main header'                                                                                                                                                                                           => 'Encabezado principal',
				'Select language'                                                                                                                                                                                       => 'Seleccionar idioma',
				'Open search'                                                                                                                                                                                           => 'Abrir búsqueda',
				'Close mobile search'                                                                                                                                                                                   => 'Cerrar búsqueda',
				'Activate the search'                                                                                                                                                                                   => 'Activar la búsqueda',
				'Open mobile menu'                                                                                                                                                                                      => 'Abrir menú móvil',

				// Search form
				'Search'                                                                                                                                                                                                => 'Buscar',
				'Search:'                                                                                                                                                                                               => 'Buscar:',
				'Search for:'                                                                                                                                                                                           => 'Buscar:',
				'Search &hellip;'                                                                                                                                                                                       => 'Buscar &hellip;',
				'Search...'                                                                                                                                                                                             => 'Buscar...',
				'submit button:::Search'                                                                                                                                                                                => 'Buscar',
				'label:::Search for:'                                                                                                                                                                                   => 'Buscar:',
				'placeholder:::Search &hellip;'                                                                                                                                                                         => 'Buscar &hellip;',
			),
		);
	}

	return isset( $catalogs[ $lang ] ) ? $catalogs[ $lang ] : array();
}

/**
 * Filter gettext calls for the 'stories' and 'default' domains to supply translations.
 *
 * @param string $translation Translated text.
 * @param string $text Text to translate.
 * @param string $domain Text domain.
 * @return string Translated text.
 */
function stories_filter_gettext( $translation, $text, $domain ) {
	if ( 'stories' !== $domain && 'default' !== $domain ) {
		return $translation;
	}

	$current_lang = stories_get_current_language();
	$dictionary   = stories_get_translations( $current_lang );

	if ( isset( $dictionary[ $text ] ) ) {
		return $dictionary[ $text ];
	}

	return $translation;
}
add_filter( 'gettext', 'stories_filter_gettext', 20, 3 );

/**
 * Filter gettext_with_context calls for the 'stories' and 'default' domains.
 *
 * @param string $translation Translated text.
 * @param string $text Text to translate.
 * @param string $context Context string.
 * @param string $domain Text domain.
 * @return string Translated text.
 */
function stories_filter_gettext_with_context( $translation, $text, $context, $domain ) {
	if ( 'stories' !== $domain && 'default' !== $domain ) {
		return $translation;
	}

	$current_lang = stories_get_current_language();
	$dictionary   = stories_get_translations( $current_lang );

	$context_key = $context . ':::' . $text;
	if ( isset( $dictionary[ $context_key ] ) ) {
		return $dictionary[ $context_key ];
	}

	if ( isset( $dictionary[ $text ] ) ) {
		return $dictionary[ $text ];
	}

	return $translation;
}
add_filter( 'gettext_with_context', 'stories_filter_gettext_with_context', 20, 4 );

/**
 * Filter search form HTML markup to ensure .search-submit and form fields are translated.
 *
 * @param string $form Search form HTML.
 * @return string Filtered search form HTML.
 */
function stories_filter_search_form( $form ) {
	if ( empty( $form ) || ! is_string( $form ) ) {
		return $form;
	}

	$current_lang = stories_get_current_language();

	if ( 'en' === $current_lang ) {
		// Input submit button: replace value="Buscar" with value="Search"
		$form = preg_replace( '/(class=["\'][^"\']*search-submit[^"\']*["\'][^>]*\bvalue=["\'])Buscar(["\'])/iu', '${1}Search${2}', $form );
		$form = preg_replace( '/(\bvalue=["\'])Buscar(["\'][^>]*class=["\'][^"\']*search-submit[^"\']*["\'])/iu', '${1}Search${2}', $form );
		// Button element: replace Buscar with Search
		$form = preg_replace( '/(<button\b[^>]*class=["\'][^"\']*search-submit[^"\']*["\'][^>]*>)\s*Buscar\s*(<\/button>)/iu', '${1}Search${2}', $form );

		// Placeholder & Labels
		$form = str_replace( 'placeholder="Buscar &hellip;"', 'placeholder="Search &hellip;"', $form );
		$form = str_replace( 'placeholder="Buscar..."', 'placeholder="Search..."', $form );
		$form = str_replace( 'placeholder="Buscar"', 'placeholder="Search"', $form );
		$form = str_replace( '>Buscar:<', '>Search for:<', $form );
		$form = str_replace( '>Buscar por:<', '>Search for:<', $form );
	} else {
		// Input submit button: replace value="Search" with value="Buscar"
		$form = preg_replace( '/(class=["\'][^"\']*search-submit[^"\']*["\'][^>]*\bvalue=["\'])Search(["\'])/iu', '${1}Buscar${2}', $form );
		$form = preg_replace( '/(\bvalue=["\'])Search(["\'][^>]*class=["\'][^"\']*search-submit[^"\']*["\'])/iu', '${1}Buscar${2}', $form );
		// Button element: replace Search with Buscar
		$form = preg_replace( '/(<button\b[^>]*class=["\'][^"\']*search-submit[^"\']*["\'][^>]*>)\s*Search\s*(<\/button>)/iu', '${1}Buscar${2}', $form );

		// Placeholder & Labels
		$form = str_replace( 'placeholder="Search &hellip;"', 'placeholder="Buscar &hellip;"', $form );
		$form = str_replace( 'placeholder="Search..."', 'placeholder="Buscar..."', $form );
		$form = str_replace( 'placeholder="Search"', 'placeholder="Buscar"', $form );
		$form = str_replace( '>Search for:<', '>Buscar:<', $form );
	}

	return $form;
}
add_filter( 'get_search_form', 'stories_filter_search_form', 20, 1 );
add_filter( 'render_block_core/search', 'stories_filter_search_form', 20, 1 );

/**
 * Filter ngettext calls for plural forms.
 *
 * @param string $translation Translated text.
 * @param string $single Single form.
 * @param string $plural Plural form.
 * @param int    $number Count number.
 * @param string $domain Text domain.
 * @return string Translated text.
 */
function stories_filter_ngettext( $translation, $single, $plural, $number, $domain ) {
	if ( 'stories' !== $domain ) {
		return $translation;
	}

	$current_lang = stories_get_current_language();
	if ( 'en' === $current_lang ) {
		if ( '%d min de lectura' === $single ) {
			return ( 1 === (int) $number ) ? '%d min read' : '%d mins read';
		}
	}

	return $translation;
}
add_filter( 'ngettext', 'stories_filter_ngettext', 20, 5 );

/**
 * Filter menu item titles to translate them automatically based on the active language.
 *
 * @param string   $title The menu item's title.
 * @param WP_Post  $item  The current menu item object.
 * @param stdClass $args  An object of wp_nav_menu() arguments.
 * @param int      $depth Depth of menu item.
 * @return string Filtered title.
 */
function stories_translate_menu_item_title( $title, $item, $args, $depth ) {
	$current_lang = stories_get_current_language();
	$dictionary   = stories_get_translations( $current_lang );

	if ( isset( $dictionary[ $title ] ) ) {
		return $dictionary[ $title ];
	}

	return $title;
}
add_filter( 'nav_menu_item_title', 'stories_translate_menu_item_title', 20, 4 );

/**
 * Filter nav menu item objects to ensure $item->title is translated early.
 *
 * @param array $items Array of menu item objects.
 * @return array Filtered menu item objects.
 */
function stories_translate_nav_menu_objects( $items ) {
	$current_lang = stories_get_current_language();
	$dictionary   = stories_get_translations( $current_lang );

	if ( ! empty( $items ) && is_array( $items ) ) {
		foreach ( $items as $item ) {
			if ( ! empty( $item->title ) && isset( $dictionary[ $item->title ] ) ) {
				$item->title = $dictionary[ $item->title ];
			}
		}
	}

	return $items;
}
add_filter( 'wp_nav_menu_objects', 'stories_translate_nav_menu_objects', 20, 1 );

/**
 * Filter nav menu object to translate menu name/title (e.g. in footer headings).
 *
 * @param WP_Term|false $menu_obj Object of the menu or false.
 * @param string|int    $menu     Menu ID, slug, name, or object.
 * @return WP_Term|false Filtered menu object.
 */
function stories_translate_menu_object( $menu_obj, $menu ) {
	if ( ! is_object( $menu_obj ) || empty( $menu_obj->name ) ) {
		return $menu_obj;
	}

	$current_lang = stories_get_current_language();
	$dictionary   = stories_get_translations( $current_lang );

	if ( isset( $dictionary[ $menu_obj->name ] ) ) {
		$cloned       = clone $menu_obj;
		$cloned->name = $dictionary[ $menu_obj->name ];
		return $cloned;
	}

	return $menu_obj;
}
add_filter( 'wp_get_nav_menu_object', 'stories_translate_menu_object', 20, 2 );

/**
 * Filter wp_nav_menu_args to support dedicated per-language menus if created in WP Admin.
 * For instance, if a menu with slug 'primary-en' or 'footer-1-en' exists, load it when in English.
 *
 * @param array $args Arguments for wp_nav_menu.
 * @return array Filtered arguments.
 */
function stories_filter_nav_menu_args( $args ) {
	$current_lang = stories_get_current_language();

	if ( 'en' === $current_lang && ! empty( $args['theme_location'] ) ) {
		$location = $args['theme_location'];
		// Check if a language-specific menu exists (e.g. 'primary-en' or 'primary_en').
		$en_candidates = array(
			$location . '-en',
			$location . '_en',
		);

		foreach ( $en_candidates as $candidate_slug ) {
			$candidate_menu = wp_get_nav_menu_object( $candidate_slug );
			if ( $candidate_menu ) {
				$args['menu'] = $candidate_menu->term_id;
				break;
			}
		}
	}

	return $args;
}
add_filter( 'wp_nav_menu_args', 'stories_filter_nav_menu_args', 20, 1 );

/**
 * Translate footer about title automatically based on active language.
 *
 * @param string $title Original title.
 * @return string Translated title.
 */
function stories_translate_footer_title( $title ) {
	$current_lang = stories_get_current_language();
	$dictionary   = stories_get_translations( $current_lang );

	$trimmed = trim( $title );
	if ( isset( $dictionary[ $trimmed ] ) ) {
		return $dictionary[ $trimmed ];
	}

	if ( 'en' === $current_lang ) {
		if ( preg_match( '/^Sobre\s+(.+)$/iu', $trimmed, $matches ) ) {
			return 'About ' . $matches[1];
		}
	} elseif ( 'es' === $current_lang ) {
		if ( preg_match( '/^About\s+(.+)$/iu', $trimmed, $matches ) ) {
			return 'Sobre ' . $matches[1];
		}
	}

	return apply_filters( 'stories_translated_footer_title', $title );
}

/**
 * Translate footer bio automatically based on active language.
 *
 * @param string $bio Original bio string.
 * @return string Translated bio string.
 */
function stories_translate_footer_bio( $bio ) {
	$current_lang = stories_get_current_language();
	$dictionary   = stories_get_translations( $current_lang );

	$trimmed = trim( wp_strip_all_tags( $bio ) );
	if ( isset( $dictionary[ $trimmed ] ) ) {
		return $dictionary[ $trimmed ];
	}

	$trimmed_raw = trim( $bio );
	if ( isset( $dictionary[ $trimmed_raw ] ) ) {
		return $dictionary[ $trimmed_raw ];
	}

	return apply_filters( 'stories_translated_footer_bio', $bio );
}

/**
 * Return inline SVG badge flag for a given language code.
 *
 * @param string $lang Language code ('es' or 'en').
 * @return string Inline SVG markup.
 */
function stories_get_language_flag( $lang ) {
	if ( 'es' === $lang ) {
		// Flag of Mexico (🇲🇽)
		return '<svg class="lang-flag" width="18" height="13" viewBox="0 0 18 13" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><rect width="6" height="13" fill="#006847"/><rect x="6" width="6" height="13" fill="#FFFFFF"/><rect x="12" width="6" height="13" fill="#CE1126"/><ellipse cx="9" cy="6.5" rx="1.5" ry="1.8" fill="#8B5A2B"/><ellipse cx="9" cy="6.3" rx="1.1" ry="1.3" fill="#B8860B"/><path d="M7.8 7.8c.4.6 2 .6 2.4 0" stroke="#006847" stroke-width="0.5" stroke-linecap="round" fill="none"/></svg>';
	}

	// Flag of United States (🇺🇸)
	return '<svg class="lang-flag" width="18" height="13" viewBox="0 0 18 13" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><rect width="18" height="13" fill="#B22234"/><path d="M0 1h18M0 3h18M0 5h18M0 7h18M0 9h18M0 11h18" stroke="#FFFFFF" stroke-width="1"/><rect width="8" height="7" fill="#3C3B6E"/><circle cx="2" cy="1.8" r="0.45" fill="#FFFFFF"/><circle cx="4" cy="1.8" r="0.45" fill="#FFFFFF"/><circle cx="6" cy="1.8" r="0.45" fill="#FFFFFF"/><circle cx="3" cy="3.5" r="0.45" fill="#FFFFFF"/><circle cx="5" cy="3.5" r="0.45" fill="#FFFFFF"/><circle cx="2" cy="5.2" r="0.45" fill="#FFFFFF"/><circle cx="4" cy="5.2" r="0.45" fill="#FFFFFF"/><circle cx="6" cy="5.2" r="0.45" fill="#FFFFFF"/></svg>';
}

/**
 * Output modern custom language switcher dropdown with rich styles and icons.
 */
function stories_language_switcher() {
	$current_lang = stories_get_current_language();

	// Determine URLs for each language.
	$url_es = '';
	$url_en = '';

	if ( function_exists( 'pll_the_languages' ) ) {
		$pll_langs = pll_the_languages( array( 'raw' => 1 ) );
		if ( ! empty( $pll_langs ) && is_array( $pll_langs ) ) {
			if ( isset( $pll_langs['es']['url'] ) ) {
				$url_es = $pll_langs['es']['url'];
			}
			if ( isset( $pll_langs['en']['url'] ) ) {
				$url_en = $pll_langs['en']['url'];
			}
		}
	}

	// Dynamic post-to-post swap for paired translations on singular views
	if ( empty( $url_es ) && empty( $url_en ) && is_singular() ) {
		$curr_id   = get_queried_object_id();
		$post_lang = get_post_meta( $curr_id, '_stories_post_lang', true );
		if ( empty( $post_lang ) ) {
			$post_lang = 'es';
		}
		$linked_id = get_post_meta( $curr_id, '_stories_translation_of', true );

		if ( ! empty( $linked_id ) && get_post_status( $linked_id ) ) {
			$curr_url   = get_permalink( $curr_id );
			$linked_url = get_permalink( $linked_id );

			if ( 'es' === $post_lang ) {
				// Current post is Spanish, counterpart is English
				$url_es = $curr_url;
				$url_en = $linked_url;
			} else {
				// Current post is English, counterpart is Spanish
				$url_en = $curr_url;
				$url_es = $linked_url;
			}
		}
	}

	// Fallback to query parameter if URLs are not resolved.
	if ( empty( $url_es ) ) {
		$url_es = add_query_arg( 'lang', 'es' );
	}
	if ( empty( $url_en ) ) {
		$url_en = add_query_arg( 'lang', 'en' );
	}

	$languages = array(
		'es' => array(
			'code' => 'ES',
			'name' => __( 'Español', 'stories' ),
			'url'  => $url_es,
		),
		'en' => array(
			'code' => 'EN',
			'name' => __( 'English', 'stories' ),
			'url'  => $url_en,
		),
	);
	?>
	<div class="language-switcher" id="language-switcher">
		<button type="button" 
				class="language-switcher__trigger" 
				id="language-switcher-trigger" 
				aria-haspopup="listbox" 
				aria-expanded="false" 
				aria-controls="language-switcher-menu" 
				aria-label="<?php esc_attr_e( 'Select language', 'stories' ); ?>" 
				onclick="storiesToggleLanguageDropdown(this)">
			<span class="language-switcher__flag" aria-hidden="true">
				<?php echo stories_get_language_flag( $current_lang ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</span>
			<span class="language-switcher__label"><?php echo esc_html( strtoupper( $current_lang ) ); ?></span>
			<span class="language-switcher__arrow" aria-hidden="true">
				<svg width="10" height="6" viewBox="0 0 10 6" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M1 1L5 5L9 1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
				</svg>
			</span>
		</button>

		<ul class="language-switcher__menu" 
			id="language-switcher-menu" 
			role="listbox" 
			aria-labelledby="language-switcher-trigger" 
			tabindex="-1">
			<?php foreach ( $languages as $lang_key => $lang_data ) : 
				$is_active = ( $lang_key === $current_lang );
				?>
				<li role="option" 
					class="language-switcher__option <?php echo $is_active ? 'is-active' : ''; ?>" 
					aria-selected="<?php echo $is_active ? 'true' : 'false'; ?>" 
					tabindex="0" 
					data-lang="<?php echo esc_attr( $lang_key ); ?>" 
					data-url="<?php echo esc_url( $lang_data['url'] ); ?>" 
					onclick="storiesSelectLanguage('<?php echo esc_js( $lang_key ); ?>', '<?php echo esc_url( $lang_data['url'] ); ?>')" 
					onkeydown="storiesHandleLanguageKey(event, '<?php echo esc_js( $lang_key ); ?>', '<?php echo esc_url( $lang_data['url'] ); ?>')">
					<span class="language-switcher__option-flag" aria-hidden="true">
						<?php echo stories_get_language_flag( $lang_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</span>
					<span class="language-switcher__option-name"><?php echo esc_html( $lang_data['name'] ); ?></span>
					<span class="language-switcher__option-code"><?php echo esc_html( $lang_data['code'] ); ?></span>
					<?php if ( $is_active ) : ?>
						<span class="language-switcher__option-check" aria-hidden="true">
							<svg width="14" height="14" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
								<path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>
							</svg>
						</span>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
	<?php
}

/* ==========================================================================
 * Post-to-Post Translation System (Cloning, Linking & Language Exchange)
 * ========================================================================== */

/**
 * Register translation metabox in the editor sidebar for all public post types.
 */
function stories_register_post_translation_metabox() {
	$post_types = get_post_types( array( 'public' => true ), 'names' );
	unset( $post_types['attachment'] );

	foreach ( $post_types as $post_type ) {
		add_meta_box(
			'stories_post_translation',
			__( '🌐 Idioma y Traducción / Language', 'stories' ),
			'stories_render_post_translation_metabox',
			$post_type,
			'side',
			'high'
		);
	}
}
add_action( 'add_meta_boxes', 'stories_register_post_translation_metabox' );

/**
 * Render post translation metabox in the post editor.
 *
 * @param WP_Post $post Current post object.
 */
function stories_render_post_translation_metabox( $post ) {
	wp_nonce_field( 'stories_save_post_translation', 'stories_translation_nonce' );

	$current_lang = get_post_meta( $post->ID, '_stories_post_lang', true );
	if ( empty( $current_lang ) ) {
		$current_lang = 'es';
	}

	$linked_id      = intval( get_post_meta( $post->ID, '_stories_translation_of', true ) );
	$linked_post    = ( $linked_id > 0 && get_post_status( $linked_id ) ) ? get_post( $linked_id ) : null;
	$opposite_lang  = ( 'es' === $current_lang ) ? 'en' : 'es';
	$opposite_label = ( 'en' === $opposite_lang ) ? __( 'Inglés (🇺🇸)', 'stories' ) : __( 'Español (🇲🇽)', 'stories' );

	$clone_nonce = wp_create_nonce( 'stories_clone_post_' . $post->ID );
	$clone_url   = admin_url( 'admin-post.php?action=stories_clone_post_translation&post_id=' . $post->ID . '&_wpnonce=' . $clone_nonce );
	?>
	<div class="stories-trans-metabox">
		<!-- Post Language Selection -->
		<div class="stories-trans-field-group">
			<label class="stories-trans-field-label">
				<?php esc_html_e( 'Idioma de esta publicación:', 'stories' ); ?>
			</label>
			<div class="stories-trans-lang-selector">
				<label class="stories-trans-lang-choice">
					<input type="radio" name="_stories_post_lang" value="es" <?php checked( 'es', $current_lang ); ?>>
					<span>🇲🇽 Español</span>
				</label>
				<label class="stories-trans-lang-choice">
					<input type="radio" name="_stories_post_lang" value="en" <?php checked( 'en', $current_lang ); ?>>
					<span>🇺🇸 English</span>
				</label>
			</div>
		</div>

		<hr class="stories-trans-divider">

		<!-- Translation Pairing Status -->
		<div class="stories-trans-field-group">
			<label class="stories-trans-field-label">
				<?php printf( esc_html__( 'Publicación vinculada en %s:', 'stories' ), esc_html( $opposite_label ) ); ?>
			</label>

			<?php if ( $linked_post ) : ?>
				<div class="stories-trans-linked-card">
					<div class="stories-trans-linked-title">
						<?php echo esc_html( $linked_post->post_title ); ?>
					</div>
					<div class="stories-trans-linked-meta">
						<?php printf( esc_html__( 'Estado: %s', 'stories' ), esc_html( ucfirst( get_post_status( $linked_post ) ) ) ); ?>
					</div>
					<div class="stories-trans-linked-actions">
						<a href="<?php echo esc_url( get_edit_post_link( $linked_post->ID ) ); ?>" class="button button-small button-primary">
							✏️ <?php esc_html_e( 'Editar', 'stories' ); ?>
						</a>
						<a href="<?php echo esc_url( get_permalink( $linked_post->ID ) ); ?>" target="_blank" class="button button-small">
							👁️ <?php esc_html_e( 'Ver', 'stories' ); ?>
						</a>
					</div>
				</div>
			<?php else : ?>
				<div class="stories-trans-field-group">
					<a href="<?php echo esc_url( $clone_url ); ?>" class="button button-primary stories-trans-clone-action-btn">
						📄 <?php printf( esc_html__( 'Clonar a versión en %s', 'stories' ), esc_html( $opposite_label ) ); ?>
					</a>
					<p class="stories-trans-helper-text">
						<?php esc_html_e( 'Copia todo el contenido, diseño y bloques actuales a una nueva entrada en borrador para traducir.', 'stories' ); ?>
					</p>
				</div>
			<?php endif; ?>

			<!-- Manual Linking Dropdown -->
			<div class="stories-trans-field-group" style="margin-top: 6px;">
				<label for="_stories_translation_of_select" class="stories-trans-field-label" style="font-size: 12px; color: #475569; font-weight: 500;">
					<?php esc_html_e( 'O asignar vinculación manualmente:', 'stories' ); ?>
				</label>
				<select name="_stories_translation_of" id="_stories_translation_of_select" class="widefat" style="font-size: 12px;">
					<option value=""><?php esc_html_e( '— Ninguna (sin vincular) —', 'stories' ); ?></option>
					<?php
					$available_posts = get_posts(
						array(
							'post_type'   => $post->post_type,
							'post_status' => array( 'publish', 'draft', 'pending', 'future', 'private' ),
							'numberposts' => 100,
							'exclude'     => array( $post->ID ),
							'orderby'     => 'title',
							'order'       => 'ASC',
						)
					);
					foreach ( $available_posts as $avail_p ) :
						$avail_lang = get_post_meta( $avail_p->ID, '_stories_post_lang', true );
						$avail_flag = ( 'en' === $avail_lang ) ? '🇺🇸' : '🇲🇽';
						?>
						<option value="<?php echo esc_attr( $avail_p->ID ); ?>" <?php selected( $linked_id, $avail_p->ID ); ?>>
							<?php echo esc_html( $avail_flag . ' ' . $avail_p->post_title ); ?>
						</option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
	</div>
	<?php
}

/**
 * Handle 1-click cloning of a post to create its counterpart in the other language.
 */
function stories_handle_clone_post_translation() {
	if ( ! isset( $_GET['post_id'], $_GET['_wpnonce'] ) ) {
		wp_die( esc_html__( 'Parámetros inválidos.', 'stories' ) );
	}

	$post_id = intval( $_GET['post_id'] );
	if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), 'stories_clone_post_' . $post_id ) ) {
		wp_die( esc_html__( 'Acceso denegado o enlace expirado.', 'stories' ) );
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		wp_die( esc_html__( 'No tienes permisos para editar esta publicación.', 'stories' ) );
	}

	$original = get_post( $post_id );
	if ( ! $original ) {
		wp_die( esc_html__( 'Publicación no encontrada.', 'stories' ) );
	}

	$orig_lang = get_post_meta( $post_id, '_stories_post_lang', true );
	if ( empty( $orig_lang ) ) {
		$orig_lang = 'es';
		update_post_meta( $post_id, '_stories_post_lang', 'es' );
	}
	$target_lang = ( 'es' === $orig_lang ) ? 'en' : 'es';
	$lang_suffix = ( 'en' === $target_lang ) ? ' (English)' : ' (Español)';

	// If already has a linked counterpart, redirect directly to it
	$existing_linked_id = intval( get_post_meta( $post_id, '_stories_translation_of', true ) );
	if ( $existing_linked_id > 0 && get_post_status( $existing_linked_id ) ) {
		wp_safe_redirect( get_edit_post_link( $existing_linked_id, 'raw' ) );
		exit;
	}

	// Insert cloned post with identical date to keep them side-by-side in WP Admin
	$new_post_args = array(
		'post_title'     => $original->post_title . $lang_suffix,
		'post_content'   => $original->post_content,
		'post_excerpt'   => $original->post_excerpt,
		'post_status'    => 'draft',
		'post_type'      => $original->post_type,
		'post_author'    => get_current_user_id(),
		'comment_status' => $original->comment_status,
		'ping_status'    => $original->ping_status,
		'post_date'      => $original->post_date,
		'post_date_gmt'  => $original->post_date_gmt,
	);

	$cloned_id = wp_insert_post( $new_post_args );

	if ( is_wp_error( $cloned_id ) || ! $cloned_id ) {
		wp_die( esc_html__( 'Error al duplicar la publicación.', 'stories' ) );
	}

	// Copy featured image/thumbnail
	$thumbnail_id = get_post_thumbnail_id( $post_id );
	if ( $thumbnail_id ) {
		set_post_thumbnail( $cloned_id, $thumbnail_id );
	}

	// Copy post format
	$format = get_post_format( $post_id );
	if ( $format ) {
		set_post_format( $cloned_id, $format );
	}

	// Copy page template
	if ( 'page' === $original->post_type ) {
		$template = get_post_meta( $post_id, '_wp_page_template', true );
		if ( $template ) {
			update_post_meta( $cloned_id, '_wp_page_template', $template );
		}
	}

	// Set bidirectional language pairing
	update_post_meta( $cloned_id, '_stories_post_lang', $target_lang );
	update_post_meta( $cloned_id, '_stories_translation_of', $post_id );
	update_post_meta( $post_id, '_stories_translation_of', $cloned_id );

	// Redirect to the cloned post editor
	wp_safe_redirect( get_edit_post_link( $cloned_id, 'raw' ) );
	exit;
}
add_action( 'admin_post_stories_clone_post_translation', 'stories_handle_clone_post_translation' );

/**
 * Save post translation meta fields and maintain bidirectional link.
 *
 * @param int $post_id The post ID.
 */
function stories_save_post_translation( $post_id ) {
	if ( ! isset( $_POST['stories_translation_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['stories_translation_nonce'] ) ), 'stories_save_post_translation' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( wp_is_post_revision( $post_id ) || wp_is_post_autosave( $post_id ) ) {
		return;
	}

	$post_type = isset( $_POST['post_type'] ) ? sanitize_text_field( wp_unslash( $_POST['post_type'] ) ) : 'post';
	if ( 'page' === $post_type ) {
		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return;
		}
	} else {
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
	}

	// Save Post Language
	if ( isset( $_POST['_stories_post_lang'] ) && in_array( $_POST['_stories_post_lang'], array( 'es', 'en' ), true ) ) {
		update_post_meta( $post_id, '_stories_post_lang', sanitize_text_field( wp_unslash( $_POST['_stories_post_lang'] ) ) );
	}

	// Save Translation Pairing (Bidirectional)
	if ( isset( $_POST['_stories_translation_of'] ) ) {
		$new_linked_id = intval( $_POST['_stories_translation_of'] );
		$old_linked_id = intval( get_post_meta( $post_id, '_stories_translation_of', true ) );

		if ( $new_linked_id > 0 && $new_linked_id !== $post_id ) {
			update_post_meta( $post_id, '_stories_translation_of', $new_linked_id );
			update_post_meta( $new_linked_id, '_stories_translation_of', $post_id );

			// Sync dates so they appear consecutive in WordPress Admin post table
			$curr_p   = get_post( $post_id );
			$linked_p = get_post( $new_linked_id );
			if ( $curr_p && $linked_p && $curr_p->post_date !== $linked_p->post_date ) {
				wp_update_post( array(
					'ID'            => $new_linked_id,
					'post_date'     => $curr_p->post_date,
					'post_date_gmt' => $curr_p->post_date_gmt,
				) );
			}

			// If old counterpart existed and was different, unlink it
			if ( $old_linked_id > 0 && $old_linked_id !== $new_linked_id ) {
				delete_post_meta( $old_linked_id, '_stories_translation_of' );
			}
		} else {
			// Unlinked
			delete_post_meta( $post_id, '_stories_translation_of' );
			if ( $old_linked_id > 0 ) {
				delete_post_meta( $old_linked_id, '_stories_translation_of' );
			}
		}
	}
}
add_action( 'save_post', 'stories_save_post_translation' );

/**
 * Filter frontend post queries to only show posts matching the active language.
 * Excludes English posts when browsing in Spanish, and excludes Spanish posts when browsing in English.
 *
 * @param WP_Query $query The WP_Query instance.
 */
function stories_filter_queries_by_language( $query ) {
	// Support admin language filter tabs in edit.php
	if ( is_admin() && $query->is_main_query() && isset( $_GET['lang_filter'] ) ) {
		$lang_filter = sanitize_text_field( wp_unslash( $_GET['lang_filter'] ) );
		if ( 'en' === $lang_filter ) {
			$query->set( 'meta_query', array(
				array(
					'key'     => '_stories_post_lang',
					'value'   => 'en',
					'compare' => '=',
				),
			) );
		} elseif ( 'es' === $lang_filter ) {
			$query->set( 'meta_query', array(
				'relation' => 'OR',
				array(
					'key'     => '_stories_post_lang',
					'value'   => 'en',
					'compare' => '!=',
				),
				array(
					'key'     => '_stories_post_lang',
					'compare' => 'NOT EXISTS',
				),
			) );
		}
		return;
	}

	// Never filter inside wp-admin (unless doing frontend AJAX)
	if ( is_admin() && ! wp_doing_ajax() ) {
		return;
	}

	// If Polylang is active, let Polylang manage queries
	if ( function_exists( 'pll_current_language' ) ) {
		return;
	}

	// Do not filter singular queries (WordPress needs to load the queried post/page directly)
	if ( $query->is_singular() || ( $query->is_main_query() && ( $query->is_single() || $query->is_page() ) ) ) {
		return;
	}

	$current_lang = stories_get_current_language();

	$meta_query = $query->get( 'meta_query' );
	if ( ! is_array( $meta_query ) ) {
		$meta_query = array();
	}

	if ( 'en' === $current_lang ) {
		// In English: Only show posts explicitly marked as English
		$meta_query[] = array(
			'key'     => '_stories_post_lang',
			'value'   => 'en',
			'compare' => '=',
		);
	} else {
		// In Spanish: Only show posts that are NOT marked as English
		// (This matches posts with '_stories_post_lang' = 'es' AND legacy posts without meta)
		$meta_query[] = array(
			'relation' => 'OR',
			array(
				'key'     => '_stories_post_lang',
				'value'   => 'en',
				'compare' => '!=',
			),
			array(
				'key'     => '_stories_post_lang',
				'compare' => 'NOT EXISTS',
			),
		);
	}

	$query->set( 'meta_query', $meta_query );
}
add_action( 'pre_get_posts', 'stories_filter_queries_by_language', 20 );

/**
 * Add language filter tabs at the top of admin post and page lists.
 *
 * @param array $views Existing views.
 * @return array Modified views.
 */
function stories_admin_language_views( $views ) {
	global $typenow;
	$post_type      = $typenow ? $typenow : 'post';
	$current_filter = isset( $_GET['lang_filter'] ) ? sanitize_text_field( wp_unslash( $_GET['lang_filter'] ) ) : '';

	// Count posts in Spanish
	$count_es = count( get_posts( array(
		'post_type'      => $post_type,
		'posts_per_page' => -1,
		'post_status'    => 'any',
		'fields'         => 'ids',
		'meta_query'     => array(
			'relation' => 'OR',
			array(
				'key'     => '_stories_post_lang',
				'value'   => 'en',
				'compare' => '!=',
			),
			array(
				'key'     => '_stories_post_lang',
				'compare' => 'NOT EXISTS',
			),
		),
	) ) );

	// Count posts in English
	$count_en = count( get_posts( array(
		'post_type'      => $post_type,
		'posts_per_page' => -1,
		'post_status'    => 'any',
		'fields'         => 'ids',
		'meta_query'     => array(
			array(
				'key'     => '_stories_post_lang',
				'value'   => 'en',
				'compare' => '=',
			),
		),
	) ) );

	$base_url = remove_query_arg( array( 'lang_filter', 'paged' ) );

	$class_es = ( 'es' === $current_filter ) ? ' class="current"' : '';
	$class_en = ( 'en' === $current_filter ) ? ' class="current"' : '';

	$views['lang_es'] = sprintf(
		'<a href="%s"%s>%s <span class="count">(%d)</span></a>',
		esc_url( add_query_arg( 'lang_filter', 'es', $base_url ) ),
		$class_es,
		'🇲🇽 Español',
		$count_es
	);

	$views['lang_en'] = sprintf(
		'<a href="%s"%s>%s <span class="count">(%d)</span></a>',
		esc_url( add_query_arg( 'lang_filter', 'en', $base_url ) ),
		$class_en,
		'🇺🇸 English',
		$count_en
	);

	return $views;
}
add_filter( 'views_edit-post', 'stories_admin_language_views' );
add_filter( 'views_edit-page', 'stories_admin_language_views' );

/**
 * Display language badge and counterpart reference in post title states in WP Admin.
 *
 * @param array   $post_states Existing post states.
 * @param WP_Post $post        Current post object.
 * @return array Modified post states.
 */
function stories_admin_display_post_states( $post_states, $post ) {
	$post_lang = get_post_meta( $post->ID, '_stories_post_lang', true );
	$linked_id = intval( get_post_meta( $post->ID, '_stories_translation_of', true ) );

	if ( 'en' === $post_lang ) {
		if ( $linked_id > 0 && get_post_status( $linked_id ) ) {
			$linked_post = get_post( $linked_id );
			$orig_title  = $linked_post ? $linked_post->post_title : '';
			$post_states['stories_lang'] = '🇺🇸 Versión en Inglés (de: ' . esc_html( wp_trim_words( $orig_title, 3 ) ) . ')';
		} else {
			$post_states['stories_lang'] = '🇺🇸 Versión en Inglés';
		}
	} elseif ( $linked_id > 0 && get_post_status( $linked_id ) ) {
		$linked_post = get_post( $linked_id );
		$orig_title  = $linked_post ? $linked_post->post_title : '';
		$post_states['stories_lang'] = '🇲🇽 Español (🇺🇸 ' . esc_html( wp_trim_words( $orig_title, 3 ) ) . ')';
	}

	return $post_states;
}
add_filter( 'display_post_states', 'stories_admin_display_post_states', 10, 2 );

/**
 * Add quick action links below the post title to jump straight to the counterpart or clone it.
 *
 * @param array   $actions Existing row actions.
 * @param WP_Post $post    Current post object.
 * @return array Modified row actions.
 */
function stories_admin_post_row_actions( $actions, $post ) {
	$linked_id = intval( get_post_meta( $post->ID, '_stories_translation_of', true ) );
	$post_lang = get_post_meta( $post->ID, '_stories_post_lang', true );
	if ( empty( $post_lang ) ) {
		$post_lang = 'es';
	}

	if ( $linked_id > 0 && get_post_status( $linked_id ) ) {
		$linked_post = get_post( $linked_id );
		$target_lang = ( 'en' === $post_lang ) ? '🇲🇽 ' . __( 'Ver/Editar en Español', 'stories' ) : '🇺🇸 ' . __( 'Ver/Editar en Inglés', 'stories' );
		$actions['stories_linked'] = '<a href="' . esc_url( get_edit_post_link( $linked_id ) ) . '" style="color:#2563eb; font-weight:600;" title="' . esc_attr( $linked_post ? $linked_post->post_title : '' ) . '">' . esc_html( $target_lang ) . '</a>';
	} else {
		$clone_nonce = wp_create_nonce( 'stories_clone_post_' . $post->ID );
		$clone_url   = admin_url( 'admin-post.php?action=stories_clone_post_translation&post_id=' . $post->ID . '&_wpnonce=' . $clone_nonce );
		$actions['stories_clone'] = '<a href="' . esc_url( $clone_url ) . '" style="color:#0284c7;">' . esc_html__( '📄 Clonar a versión en Inglés', 'stories' ) . '</a>';
	}

	return $actions;
}
add_filter( 'post_row_actions', 'stories_admin_post_row_actions', 10, 2 );
add_filter( 'page_row_actions', 'stories_admin_post_row_actions', 10, 2 );

/**
 * Add translation status column to admin posts, pages and CPT lists.
 *
 * @param array $columns Existing columns.
 * @return array Modified columns.
 */
function stories_add_translation_admin_column( $columns ) {
	$new_columns = array();
	foreach ( $columns as $key => $val ) {
		$new_columns[ $key ] = $val;
		if ( 'title' === $key ) {
			$new_columns['stories_trans'] = __( 'Idioma / Traducción', 'stories' );
		}
	}
	if ( ! isset( $new_columns['stories_trans'] ) ) {
		$new_columns['stories_trans'] = __( 'Idioma / Traducción', 'stories' );
	}
	return $new_columns;
}

/**
 * Output modern stylesheet for translation admin UI (post lists and editor metabox).
 */
function stories_admin_i18n_styles() {
	$screen = get_current_screen();
	if ( ! $screen || ! in_array( $screen->base, array( 'edit', 'post' ), true ) ) {
		return;
	}
	?>
	<style id="stories-admin-i18n-styles">
	/* --- Translation Column in Post List Table --- */
	.column-stories_trans {
		width: 250px;
		vertical-align: middle;
	}
	.stories-trans-cell {
		display: flex;
		align-items: center;
		gap: 8px;
		flex-wrap: wrap;
	}
	.stories-lang-badge {
		display: inline-flex;
		align-items: center;
		gap: 4px;
		padding: 3px 8px;
		font-size: 11px;
		font-weight: 700;
		line-height: 1.3;
		border-radius: 9999px;
		border: 1px solid transparent;
		white-space: nowrap;
		flex-shrink: 0;
	}
	.stories-lang-badge--es {
		background: #fefce8;
		color: #854d0e;
		border-color: #fde047;
	}
	.stories-lang-badge--en {
		background: #eff6ff;
		color: #1d4ed8;
		border-color: #bfdbfe;
	}
	.stories-trans-target-group {
		display: inline-flex;
		align-items: center;
		gap: 5px;
		background: #f8fafc;
		border: 1px solid #e2e8f0;
		border-radius: 6px;
		padding: 2px 6px;
	}
	.stories-trans-target-link {
		font-size: 12px;
		font-weight: 600;
		text-decoration: none;
		color: #2563eb;
		max-width: 120px;
		overflow: hidden;
		text-overflow: ellipsis;
		white-space: nowrap;
	}
	.stories-trans-target-link:hover {
		color: #1d4ed8;
		text-decoration: underline;
	}
	.stories-trans-btn-icon {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		width: 22px;
		height: 22px;
		border-radius: 4px;
		border: 1px solid #cbd5e1;
		background: #ffffff;
		font-size: 11px;
		text-decoration: none;
		line-height: 1;
		color: #475569;
		transition: all 0.15s ease;
	}
	.stories-trans-btn-icon:hover {
		background: #f1f5f9;
		border-color: #94a3b8;
		color: #0f172a;
	}
	.stories-trans-clone-link {
		display: inline-flex;
		align-items: center;
		gap: 4px;
		padding: 3px 8px;
		border-radius: 6px;
		font-size: 11px;
		font-weight: 600;
		text-decoration: none;
		background: #f0f9ff;
		color: #0284c7;
		border: 1px solid #bae6fd;
		white-space: nowrap;
		transition: all 0.15s ease;
	}
	.stories-trans-clone-link:hover {
		background: #e0f2fe;
		border-color: #7dd3fc;
		color: #0369a1;
	}

	/* --- Editor Metabox Styles --- */
	.stories-trans-metabox {
		display: flex;
		flex-direction: column;
		gap: 12px;
		font-size: 13px;
	}
	.stories-trans-field-group {
		display: flex;
		flex-direction: column;
		gap: 6px;
	}
	.stories-trans-field-label {
		font-weight: 600;
		color: #1e293b;
		margin: 0;
	}
	.stories-trans-lang-selector {
		display: flex;
		gap: 10px;
		align-items: center;
	}
	.stories-trans-lang-choice {
		display: flex;
		align-items: center;
		gap: 6px;
		padding: 6px 12px;
		border: 1px solid #cbd5e1;
		border-radius: 6px;
		background: #ffffff;
		cursor: pointer;
		font-size: 13px;
		font-weight: 500;
		transition: all 0.15s ease;
		user-select: none;
	}
	.stories-trans-lang-choice:hover {
		background: #f8fafc;
		border-color: #94a3b8;
	}
	.stories-trans-lang-choice input[type="radio"]:checked + span {
		font-weight: 700;
		color: #0f172a;
	}
	.stories-trans-divider {
		border: 0;
		border-top: 1px solid #e2e8f0;
		margin: 4px 0;
	}
	.stories-trans-linked-card {
		display: flex;
		flex-direction: column;
		gap: 8px;
		background: #f8fafc;
		border: 1px solid #cbd5e1;
		border-radius: 8px;
		padding: 10px 12px;
	}
	.stories-trans-linked-title {
		font-weight: 600;
		color: #0f172a;
		line-height: 1.35;
	}
	.stories-trans-linked-meta {
		font-size: 11px;
		color: #64748b;
	}
	.stories-trans-linked-actions {
		display: flex;
		gap: 8px;
		align-items: center;
	}
	.stories-trans-clone-action-btn {
		display: flex;
		align-items: center;
		justify-content: center;
		gap: 6px;
		width: 100%;
		padding: 8px 12px;
		border-radius: 6px;
		font-size: 13px;
		font-weight: 600;
		box-sizing: border-box;
	}
	.stories-trans-helper-text {
		font-size: 11.5px;
		color: #64748b;
		line-height: 1.35;
		margin: 0;
	}
	</style>
	<?php
}
add_action( 'admin_head', 'stories_admin_i18n_styles' );

/**
 * Display translation badge and linked post link in admin list column.
 *
 * @param string $column_name Column identifier.
 * @param int    $post_id     Current post ID.
 */
function stories_display_translation_admin_column( $column_name, $post_id ) {
	if ( 'stories_trans' !== $column_name ) {
		return;
	}

	$post_lang = get_post_meta( $post_id, '_stories_post_lang', true );
	if ( empty( $post_lang ) ) {
		$post_lang = 'es';
	}
	$linked_id = intval( get_post_meta( $post_id, '_stories_translation_of', true ) );
	$lang_code = strtoupper( $post_lang );
	$lang_flag = ( 'en' === $post_lang ) ? '🇺🇸 ' . $lang_code : '🇲🇽 ' . $lang_code;
	$pill_mod  = ( 'en' === $post_lang ) ? 'stories-lang-badge--en' : 'stories-lang-badge--es';

	echo '<div class="stories-trans-cell">';
	echo '<span class="stories-lang-badge ' . esc_attr( $pill_mod ) . '">' . esc_html( $lang_flag ) . '</span>';

	if ( $linked_id > 0 && get_post_status( $linked_id ) ) {
		$linked_post = get_post( $linked_id );
		$linked_flag = ( 'en' === $post_lang ) ? '🇲🇽' : '🇺🇸';
		echo '<div class="stories-trans-target-group">';
		echo '<a href="' . esc_url( get_edit_post_link( $linked_id ) ) . '" class="stories-trans-target-link" title="' . esc_attr__( 'Editar versión vinculada', 'stories' ) . '">' . esc_html( $linked_flag . ' ' . wp_trim_words( $linked_post->post_title, 4 ) ) . '</a>';
		echo '<a href="' . esc_url( get_edit_post_link( $linked_id ) ) . '" class="stories-trans-btn-icon" title="' . esc_attr__( 'Editar', 'stories' ) . '">✏️</a>';
		echo '<a href="' . esc_url( get_permalink( $linked_id ) ) . '" target="_blank" class="stories-trans-btn-icon" title="' . esc_attr__( 'Ver', 'stories' ) . '">👁️</a>';
		echo '</div>';
	} else {
		$clone_nonce = wp_create_nonce( 'stories_clone_post_' . $post_id );
		$clone_url   = admin_url( 'admin-post.php?action=stories_clone_post_translation&post_id=' . $post_id . '&_wpnonce=' . $clone_nonce );
		echo '<a href="' . esc_url( $clone_url ) . '" class="stories-trans-clone-link">➕ ' . esc_html__( 'Clonar a EN', 'stories' ) . '</a>';
	}

	echo '</div>';
}

/**
 * Register translation columns for all public post types.
 */
function stories_register_translation_columns_for_all_post_types() {
	$post_types = get_post_types( array( 'public' => true ), 'names' );
	foreach ( $post_types as $pt ) {
		add_filter( "manage_{$pt}_posts_columns", 'stories_add_translation_admin_column' );
		add_action( "manage_{$pt}_posts_custom_column", 'stories_display_translation_admin_column', 10, 2 );
	}
	add_filter( 'manage_pages_columns', 'stories_add_translation_admin_column' );
	add_action( 'manage_pages_custom_column', 'stories_display_translation_admin_column', 10, 2 );
}
add_action( 'admin_init', 'stories_register_translation_columns_for_all_post_types' );
