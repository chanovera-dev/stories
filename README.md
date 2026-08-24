# 📖 Stories — WordPress Theme

**Stories** es un tema moderno, modular y de alto rendimiento para WordPress, diseñado específicamente para publicaciones visuales, relatos, artículos editoriales y experiencias multimedia inmersivas.

---

## ✨ Características Principales

### 🎭 Formatos de Entrada Personalizados
- 🎵 **Audio**: Reproductor de vinilo 3D con disco giratorio, espectrograma de frecuencias animado e iluminación ambiental pulsante.
- 🎬 **Video**: Reproductor integrado con soporte HTML5 y embeds, controles personalizados en hover y modo pantalla completa.
- 🖼️ **Galería**: Carrusel táctil interactivo con vista de teatro, navegación por puntos y conteo dinámico.
- 💬 **Cita**: Diseño tipográfico elegante con comillas de agua tridimensionales e inclinación sutil.
- 📝 **Aside**: Hoja de libreta interactiva con renglones calculados según el tamaño de fuente, línea de margen roja y marcas de perforación.
- 📷 **Imagen**: Enfoque fotográfico de alta calidad con visor Lightbox e información de metadatos.
- 📰 **Estándar**: Tarjeta con imagen de fondo a pantalla completa, degradado fotográfico progresivo (*cinematic scrim*) y panel desplegable de metadatos y extracto.

### 🎨 Sistema Dinámico de Temas de Color
Configurable directamente desde el panel de administración de WordPress (**Stories** 👉 **Estilos y Apariencia**):
- 🌿 **Evergreen / Esmeralda (Por defecto)**
- 🌊 **Nordic Ocean / Azul Océano**
- 🌅 **Sunset Amber / Ámbar & Terracota**
- 🔮 **Velvet Midnight / Púrpura Nocturno**
- 🌹 **Rose & Ruby / Rosa & Carmesí**
- 🖤 **Charcoal & Slate / Monocromo Minimalista**
- 🌌 **Midnight Navy / Modo Oscuro Azul**
- 🎨 **Personalizado**: Selectores interactivos de color (`wp-color-picker`) para definir el color primario, acento, cabecera, body y footer.

### 🧩 Arquitectura Modular y Extensible
- **Plantillas de Loops Dinámicos**: Soporte para múltiples diseños de bucle (`loop00`, `loop01`, etc.) intercambiables desde el panel de opciones.
- **Optimizaciones de Rendimiento**: Opciones en panel para desactivar emojis antiguos, scripts innecesarios de oEmbed y limpiar etiquetas de cabecera.
- **Integración con Google Tag Manager**: Activación y gestión sencilla de contenedor GTM desde el panel.
- **Soporte FSE & Gutenberg**: Totalmente compatible con `theme.json` y utilidades de tamaño de fuente fluido.
- **Efectos Squircle y Bordes**: Soporte de esquinas redondeadas modernas (`squircle`) para navegadores Chromium mediante `rounded.css`.

---

## 💻 Requisitos

- **WordPress**: 6.0 o superior
- **PHP**: 7.4 o superior
- **Navegadores**: Chrome, Firefox, Safari, Edge (Modernos)

---

## 🚀 Instalación

1. Clona o descarga este repositorio dentro del directorio de temas de tu instalación de WordPress:
   ```bash
   cd wp-content/themes/
   git clone https://github.com/tu-usuario/stories.git
   ```
2. Accede al panel de administración de WordPress: **Apariencia** 👉 **Temas**.
3. Busca **Stories** y haz clic en **Activar**.
4. Ve a la nueva sección **Stories** en el menú lateral para personalizar los estilos, paletas de color y opciones del tema.

---

## 📁 Estructura del Proyecto

```text
stories/
├── 404.php                     # Plantilla de página de error 404
├── archive.php                 # Plantilla para archivos y categorías
├── assets/
│   ├── css/                    # Hojas de estilo modulares (main, loop00, loop01, rounded, etc.)
│   ├── js/                     # Scripts de frontend, carruseles, audio y AJAX
│   └── icons/                  # Iconos SVG nativos
├── footer.php                  # Pie de página y cierre del sitio
├── functions.php               # Archivo maestro de arranque del tema
├── header.php                  # Cabecera, navegación y metadatos
├── inc/
│   ├── ajax.php                # Manejadores de peticiones AJAX
│   ├── colors.php              # Motor de esquemas de color y variables CSS
│   ├── core.php                # Registro de assets, soportes del tema y menús
│   ├── custom-blocks.php       # Registro de bloques Gutenberg
│   ├── extended.php            # Funciones y filtros extendidos
│   ├── icons.php               # Helper para renderizado de iconos SVG
│   ├── templates.php           # Funciones de plantilla y helpers
│   └── wp-panel.php            # Panel de configuración en WordPress Admin
├── index.php                   # Plantilla principal de respaldo
├── page.php                    # Plantilla para páginas individuales
├── search.php                  # Plantilla de resultados de búsqueda
├── single.php                  # Plantilla para entradas individuales
├── style.css                   # Metadatos del tema y estilos base
├── template-parts/             # Componentes de plantilla y subdirectorios de loop
├── templates/                  # Plantillas de página y componentes de single
└── theme.json                  # Configuración Gutenberg y FSE
```

---

## 📄 Licencia

Este proyecto está bajo la licencia **GNU General Public License v2.0 or later** (GPLv2). Consulta el archivo [LICENSE](LICENSE) para más detalles.
