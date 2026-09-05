# 📖 Stories — WordPress Theme

**Stories** es un tema moderno, modular y de alto rendimiento para WordPress, diseñado específicamente para publicaciones visuales, relatos, artículos editoriales y experiencias multimedia inmersivas.

---

## ✨ Características Principales

### 🎭 Formatos de Entrada Personalizados

Soporte completo para los 9 formatos de entrada de WordPress, cada uno con su propia plantilla de tarjeta en el loop y su propia vista `single` dedicada:

| Formato | Descripción |
|---------|------------|
| 🎵 **Audio** | Reproductor de vinilo 3D con disco giratorio, espectrograma de frecuencias animado e iluminación ambiental pulsante. Soporta archivos locales (mp3/ogg/wav), shortcodes `[audio]` y embeds externos (Spotify, SoundCloud, Bandcamp). |
| 🎬 **Video** | Reproductor integrado con soporte HTML5 y embeds (YouTube, Vimeo, etc.), controles personalizados en hover y modo pantalla completa. |
| 🖼️ **Galería** | Carrusel táctil interactivo con vista de teatro, navegación por puntos y conteo dinámico. Soporta bloques Gutenberg, shortcodes y archivos adjuntos. |
| 💬 **Cita** | Diseño tipográfico elegante con comillas de agua tridimensionales e inclinación sutil. |
| 📝 **Aside** | Hoja de libreta interactiva con renglones calculados según el tamaño de fuente, línea de margen roja y marcas de perforación. |
| 📷 **Imagen** | Enfoque fotográfico de alta calidad con visor Lightbox, metadatos EXIF (cámara, apertura, velocidad de obturación, focal, ISO) e información de dimensiones y peso de archivo. |
| 🔗 **Link** | Tarjeta de enlace externo con obtención automática de metadatos remotos vía OpenGraph, Twitter Cards y JSON-LD Schema. Cachea los resultados en `post_meta` para evitar peticiones HTTP repetidas. |
| 💬 **Chat** | Formato de conversación/diálogo con diseño de burbuja. |
| 📰 **Estándar** | Tarjeta con imagen de fondo a pantalla completa, degradado fotográfico progresivo (*cinematic scrim*) y panel desplegable de metadatos y extracto. |

---

### 🎨 Sistema Dinámico de Temas de Color

Configurable desde el panel (**Stories** → **Estilos y Apariencia**). Las variables CSS se inyectan en línea con `wp_add_inline_style` para evitar peticiones adicionales:

| Esquema | Slug | Descripción |
|---------|------|-------------|
| 🌿 **Evergreen / Esmeralda** | `evergreen` | Tonos botánicos frescos, verdes naturales y contrastes oscuros profundos. *(Por defecto)* |
| 🌊 **Azure Ocean / Azul Zafiro** | `nordic` | Gama oceánica con azul eléctrico vibrante y azul marino elegante. |
| 🌅 **Sunset Amber / Ámbar & Terracota** | `sunset` | Calidez editorial inspirada en atardeceres, fuego y arcilla. |
| 🔮 **Velvet Midnight / Púrpura Nocturno** | `midnight` | Misterio y sofisticación con violetas eléctricos y carbón nocturno. |
| 🌹 **Rose & Ruby / Rosa & Carmesí** | `rose` | Estética audaz con tonos rubí, frambuesa y contrastes elegantes. |
| 🖤 **Charcoal & Slate / Monocromo Minimalista** | `charcoal` | Diseño editorial sobrio y de alto contraste en escala de grises. |
| 🌌 **Midnight Navy / Modo Oscuro Azul** | `dark` | Modo oscuro en tonos azul medianoche y cobalto con acentos celestes luminosos. |
| 🎨 **Personalizado** | `custom` | Selectores interactivos (`wp-color-picker`) para color primario, acento, fondo body, cabecera y footer. |

---

### 🌐 Soporte Multilenguaje (ES/EN)

Sistema nativo de doble idioma (Español / Inglés), activable desde el panel de opciones. **Desactivado por defecto.**

- **Selector de idioma en cabecera**: Menú desplegable (🇲🇽 Español / 🇺🇸 English).
- **Detección automática de idioma**: Mediante URL (`?lang=en`), cookie de sesión (`stories_lang`), `post_meta` por entrada (`_stories_post_lang`), o locale de WordPress.
- **Cambio dinámico de locale**: Filtra `determine_locale` para switchear entre `es_ES` y `en_US` en tiempo de ejecución.
- **Diccionario de traducción interno** (`inc/i18n.php`): Más de 300 strings de interfaz traducidos sin necesidad de plugins externos.
- **Campos bilingües en el Footer**: Título y biografía del sitio en español e inglés con edición lado a lado en el panel de opciones.
- **Compatibilidad con Polylang**: Si Polylang está activo, delega automáticamente la gestión de idioma al plugin.
- **Filtrado de loops por idioma**: Los bucles de entradas respetan el idioma activo.

---

### 🏗️ Layouts de Loop Intercambiables

El diseño de las tarjetas del loop es completamente intercambiable desde el panel de opciones sin tocar código:

- **Por defecto** (`template-parts/`): Tarjetas estándar adaptadas por formato de entrada.
- **Loop00** (`template-parts/loop00/`): Diseño alternativo compacto.
- **Loop01** (`template-parts/loop01/`): Diseño editorial con mayor énfasis visual.
- **Detección automática**: El sistema escanea `template-parts/` y añade automáticamente cualquier carpeta con prefijo `loop*`, `theme*` o `layout*` a la lista del panel.
- **Gap configurable**: Espaciado entre tarjetas ajustable en cualquier unidad CSS válida (`rem`, `px`, etc.).

---

### 👍 Sistema de Likes (Me Gusta)

Sistema de reacciones sin plugins externos:

- **Botón de like** por entrada con animación de corazón SVG.
- **Contador persistente** almacenado en `post_meta` (`_stories_likes_count`).
- **Cookie de sesión** (30 días) para evitar likes duplicados sin requerir login.
- **Compatibilidad retroactiva** con el meta `_avante_likes_count` del tema antecesor Avante.
- **Endpoint AJAX** seguro con nonce verificado (`wp_ajax_stories_like_post`).

---

### 📡 Posts Relacionados con Carrusel AJAX

- Carrusel de posts relacionados al final de cada entrada singular.
- **Carga asíncrona** vía AJAX (`stories_load_more_timeline`) para mejor rendimiento.
- Timeline de navegación entre entradas con etiquetas de fecha relativa (ej. "hace 3 meses") para posts recientes, y fecha absoluta para posts con más de 2 años de antigüedad.
- Script propio (`related.js`) cargado sólo en vistas singulares.

---

### ⚡ Optimizaciones de Rendimiento

| Función | Descripción |
|---------|------------|
| **Preload de CSS crítico** | Inyecta `<link rel="preload">` para `main.css` en el `<head>` para mejorar el FCP. |
| **Defer de scripts** | Añade el atributo `defer` automáticamente a todos los scripts del tema, eliminando JavaScript que bloquee el renderizado. |
| **CSS asíncrono** | Los estilos no críticos (`rounded.css`, `comments.css`, `related.css`) se cargan de forma asíncrona con `media="print" onload`. |
| **Versionado por mtime** | Los assets CSS/JS se versionan con `filemtime()` para cache-busting automático. |
| **Desactivar emojis** | Elimina scripts y estilos de emojis de WordPress para usar emojis nativos del sistema. |
| **Desactivar CSS de bloques** | Evita cargar `wp-block-library` y `global-styles` en el frontend. |
| **Limpiar meta tags** | Oculta versión de WordPress, enlaces RSD, Windows Live Writer y shortlinks del `<head>`. |
| **Desactivar oEmbed** | Desactiva scripts de incrustación de oEmbed y enlaces de descubrimiento. |

---

### 🔍 SEO Automático

- **Meta description dinámica** generada automáticamente para posts, páginas, archivos, autores y búsquedas cuando no hay un plugin SEO activo.
- **Compatibilidad con SEO plugins**: Detecta Yoast, Rank Math, AIOSEO y SEOPress automáticamente y omite su propio meta description para evitar duplicados.
- **Breadcrumbs semánticos** con soporte para posts, páginas, categorías, etiquetas, taxonomías personalizadas, autores y archivos por fecha. SVG icons nativos en cada separador.
- **Schema JSON-LD** extraído automáticamente de URLs externas para el formato Link.

---

### 🎛️ Paginación Configurable

Tres estilos de paginación seleccionables desde el panel de opciones:

- **Clásico** (`default`): Tarjetas con línea inferior.
- **Cápsula Flotante** (`capsule`): Estilo glassmorphism.
- **Control Segmentado** (`segmented`): Barra compacta tipo iOS.

El estilo activo se aplica como clase CSS al `<body>` (`pagination-style-*`) y al elemento `.navigation.pagination`.

---

### 🧩 Arquitectura Modular y Extensible

- **Panel de administración propio** con Settings API: secciones GTM, optimización del HEAD, multilenguaje, estilos, loop y footer.
- **Bloques Gutenberg personalizados** registrados en `inc/custom-blocks.php`.
- **Soporte FSE & Gutenberg**: Compatible con `theme.json` y tipografía fluida (`fluid` font sizes).
- **Menús de navegación registrados**: `primary`, `footer`, `social`, `footer-1`, `footer-2`, `footer-3`.
- **Logo personalizado** en cabecera (flexible en ancho y alto) y en footer (subido vía Media Library).
- **Soporte SVG**: Carga de SVG en la Biblioteca de Medios habilitada nativamente, con fix de MIME type.
- **Iconos SVG inline**: Sistema nativo en `inc/icons.php` mediante `stories_get_svg()` / `stories_get_icon()`.
- **Comentarios HTML5** con formulario estilizado y avatar a 70px.
- **Badges de formato** de entrada con icono SVG junto a cada tarjeta del loop.
- **Tiempo estimado de lectura** calculado dinámicamente (200 palabras/minuto).
- **Efectos Squircle y bordes redondeados**: `rounded.css` con soporte para navegadores Chromium mediante detección JS de clase `.is-chromium`.
- **Soporte para Sidebars y Widgets**: Dos áreas de widgets registradas (`sidebar-1` para blog/entradas/archivos y `sidebar-page` para páginas estáticas). Si no contienen widgets, el contenido se expande automáticamente al 100% sin dejar huecos vacíos (`.no-sidebar`); con widgets activos (`.has-sidebar`), activa el layout adaptable de dos columnas con estilo card, squircles, y soporte completo para widgets nativos y bloques de Gutenberg.

---

### 🌐 Google Tag Manager & Analytics

- Activación del contenedor GTM desde el panel de opciones con toggle macOS.
- Soporte para IDs `GTM-XXXXXXX` (Google Tag Manager) y `G-XXXXXXXX` / `UA-XXXXXXX` (Google Analytics gtag.js).
- Inyección correcta de snippet en `<head>` y noscript en `<body>` vía `wp_body_open`.

---

## 💻 Requisitos

- **WordPress**: 6.0 o superior
- **PHP**: 7.4 o superior (recomendado 8.1+)
- **Navegadores**: Chrome, Firefox, Safari, Edge (modernos)

---

## 🚀 Instalación

1. Clona o descarga este repositorio dentro del directorio de temas de tu instalación de WordPress:
   ```bash
   cd wp-content/themes/
   git clone https://github.com/tu-usuario/stories.git
   ```
2. Accede al panel de administración de WordPress: **Apariencia** → **Temas**.
3. Busca **Stories** y haz clic en **Activar**.
4. Ve a la sección **Stories** en el menú lateral para configurar estilos, paletas de color y demás opciones.

---

## 📁 Estructura del Proyecto

```text
stories/
├── 404.php                          # Plantilla de página de error 404
├── archive.php                      # Plantilla para archivos y categorías
├── assets/
│   ├── css/
│   │   ├── admin-options.css        # Estilos del panel de opciones en WP Admin
│   │   ├── comments.css             # Estilos de sección de comentarios
│   │   ├── custom-forms.css         # Estilos para formularios personalizados
│   │   ├── loop.css                 # Estilos del loop principal (por defecto)
│   │   ├── loop00.css               # Estilos para el diseño de loop Loop00
│   │   ├── loop01.css               # Estilos para el diseño de loop Loop01
│   │   ├── main.css                 # Hoja de estilos principal (crítica)
│   │   ├── pagination.css           # Estilos de paginación (3 variantes)
│   │   ├── posts.css                # Estilos de tarjetas de posts
│   │   ├── related.css              # Estilos del carrusel de posts relacionados
│   │   ├── rounded.css              # Bordes redondeados y squircle (Chromium)
│   │   └── single.css               # Estilos de vista singular de entrada
│   ├── js/
│   │   ├── ajax.js                  # Llamadas AJAX del frontend
│   │   ├── main.js                  # Script principal del tema (Vanilla JS)
│   │   └── related.js               # Carrusel y timeline de posts relacionados
│   ├── fonts/                       # Fuentes locales del tema
│   ├── icons/                       # Iconos SVG nativos
│   └── images/                      # Imágenes estáticas del tema
├── footer.php                       # Pie de página con menús y widgets
├── functions.php                    # Archivo maestro de arranque (carga inc/)
├── header.php                       # Cabecera, navegación y selector de idioma
├── inc/
│   ├── ajax.php                     # Handlers AJAX (filtros, likes, timeline)
│   ├── colors.php                   # Motor de esquemas de color y variables CSS
│   ├── core.php                     # Registro de assets, soportes y menús
│   ├── custom-blocks.php            # Registro de bloques Gutenberg personalizados
│   ├── extended.php                 # Filtros extendidos, GTM, SEO, rendimiento, Link meta
│   ├── i18n.php                     # Sistema nativo ES/EN: locale, diccionario, cookie
│   ├── icons.php                    # Helper para renderizado de iconos SVG inline
│   ├── templates.php                # Template tags: likes, breadcrumbs, paginación, EXIF
│   └── wp-panel.php                 # Panel de configuración en WordPress Admin
├── index.php                        # Plantilla principal de respaldo
├── page.php                         # Plantilla para páginas individuales
├── search.php                       # Plantilla de resultados de búsqueda
├── sidebar.php                      # Barra lateral y área de widgets (sidebar-1 / sidebar-page)
├── single.php                       # Plantilla para entradas individuales
├── style.css                        # Metadatos del tema y estilos base
├── template-parts/
│   ├── author.php                   # Bloque de autor de la entrada
│   ├── content.php                  # Tarjeta estándar (loop por defecto)
│   ├── content-audio.php            # Tarjeta formato Audio
│   ├── content-aside.php            # Tarjeta formato Aside/Nota
│   ├── content-chat.php             # Tarjeta formato Chat
│   ├── content-gallery.php          # Tarjeta formato Galería
│   ├── content-image.php            # Tarjeta formato Imagen
│   ├── content-link.php             # Tarjeta formato Link (metadatos remotos)
│   ├── content-quote.php            # Tarjeta formato Cita
│   ├── content-status.php           # Tarjeta formato Status
│   ├── content-video.php            # Tarjeta formato Video
│   ├── content-single.php           # Vista singular estándar
│   ├── content-single-audio.php     # Vista singular Audio (reproductor vinilo 3D)
│   ├── content-single-aside.php     # Vista singular Aside
│   ├── content-single-gallery.php   # Vista singular Galería (carrusel táctil)
│   ├── content-single-image.php     # Vista singular Imagen (Lightbox + EXIF)
│   ├── content-none.php             # Plantilla de resultado vacío
│   ├── content-search.php           # Tarjeta de resultado de búsqueda
│   ├── loop00/                      # Variante de diseño de loop 00
│   └── loop01/                      # Variante de diseño de loop 01
├── templates/
│   ├── full-width.php               # Plantilla de página en ancho completo
│   └── single/
│       ├── post-navigation.php      # Navegación entre entradas (anterior/siguiente)
│       └── related-posts.php        # Carrusel de posts relacionados con carga AJAX
└── theme.json                       # Configuración Gutenberg, FSE y tipografía fluida
```

---

## ⚙️ Panel de Administración

Accede desde **Stories** en el menú lateral de WordPress Admin. Las secciones disponibles son:

| Sección | Opciones |
|---------|---------|
| **Google Tag Manager** | Activar GTM/Analytics, ID del contenedor (`GTM-XXXX` o `G-XXXX`) |
| **Optimización del HEAD** | Desactivar emojis, CSS de bloques, meta tags innecesarios, oEmbed |
| **Soporte Multilenguaje** | Activar selector de idioma ES/EN, loops filtrados por idioma |
| **Estilos y Apariencia** | Detección Chromium, `rounded.css`, esquema de color, colores personalizados, diseño de loop, gap del loop, estilo de paginación |
| **Pie de Página** | Logo del footer (Media Library), título bilingüe, biografía bilingüe |

---

## 📄 Licencia

Este proyecto está bajo la licencia **GNU General Public License v2.0 or later** (GPLv2). Consulta el archivo [LICENSE](LICENSE) para más detalles.
