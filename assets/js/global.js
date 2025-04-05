function openMenuMobile() {
    if ( window.innerWidth <= 767 ) {
        const btnMenuMobile = document.querySelector( '#menu-mobile__button' );
        const menuMobile = document.querySelector( '#primary' );

        btnMenuMobile.classList.toggle( 'open' );
        menuMobile.classList.toggle( 'show' );
    }
}

function openCustomSearchform() {
    const customSearchform = document.querySelector( '#custom-searchform' );
    const primaryMenu = document.querySelector( '#primary .menu' );
    const iconSearchBtn = document.querySelector( '#search-mobile__button .bi-search' );
    const iconCloseBtn = document.querySelector( '#search-mobile__button .bi-x-circle' );

    if (window.innerWidth <= 767) {
        if (customSearchform) customSearchform.classList.add('show');
    }

    if (window.innerWidth >= 768) {
        if (customSearchform) customSearchform.classList.toggle('show');
        if (primaryMenu) primaryMenu.classList.toggle('hide');
        if (iconSearchBtn) iconSearchBtn.classList.toggle('hide');
        if (iconCloseBtn) iconCloseBtn.classList.toggle('show');
    }
}

function closeCustomSearchform() {
    if ( window.innerWidth <= 767 ) {
        const customSearchform = document.querySelector( '#custom-searchform' );
        customSearchform.classList.remove( 'show' );
    }
}

// add class for scroll
function scrollActions() {
    const body = document.body
    const scrollUp = "scroll-up"
    const scrollDown = "scroll-down"
    let lastScroll = 0
    
    window.addEventListener("scroll", () => {
        const currentScroll = window.pageYOffset
        if (currentScroll <= 0) {
            body.classList.remove(scrollUp)
            return
        }
      
      if (currentScroll > lastScroll && !body.classList.contains(scrollDown)) {
        // down
        body.classList.remove(scrollUp)
        body.classList.add(scrollDown)
      } else if (currentScroll < lastScroll && body.classList.contains(scrollDown)) {
        // up
        body.classList.remove(scrollDown)
        body.classList.add(scrollUp)
      }
      lastScroll = currentScroll
    })
}
scrollActions();

// submenus for the main menu
function menuWithChildren() {
    let menuItems = document.querySelectorAll('.menu-item-has-children');

    // Iterar sobre cada elemento y agregar el botón con el SVG y texto
    menuItems.forEach(function(item) {
        // Obtener el enlace más cercano al elemento li
        var link = item.querySelector('a')

        if (!link) return; // Evitar errores si no hay enlace

        // Crear un nuevo div
        var div = document.createElement('div');
        div.classList.add('menu-toggle-container'); // Clase para estilos si es necesario

        // Crear un nuevo botón
        var button = document.createElement('button');
        button.classList.add('mobile-links__item-toggle');
        button.setAttribute('onclick', 'toggleSubMenu(this)');

        // Obtener el texto del enlace
        var linkText = link.innerText;

        // Crear un nuevo elemento de texto con el contenido del enlace
        var buttonText = document.createTextNode(linkText);

        // Agregar el texto al botón
        button.appendChild(buttonText);

        // Crear el elemento SVG
        var svg = document.createElementNS("http://www.w3.org/2000/svg", "svg");
        svg.setAttribute('width', '13');
        svg.setAttribute('height', '13');
        svg.setAttribute('fill', 'currentColor');
        svg.setAttribute('class', 'bi bi-chevron-down');
        svg.setAttribute('viewBox', '0 0 16 16');

        // Crear el elemento path dentro del SVG
        var path = document.createElementNS("http://www.w3.org/2000/svg", "path");
        path.setAttribute('fill-rule', 'evenodd');
        path.setAttribute('d', 'M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z');

        // Agregar el path al elemento SVG
        svg.appendChild(path);
        // Agregar el SVG al botón
        button.appendChild(svg);

        // Agregar el botón al div
        div.appendChild(button);

        // Reemplazar el enlace con el div
        link.replaceWith(div);
    });
}
menuWithChildren();