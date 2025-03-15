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