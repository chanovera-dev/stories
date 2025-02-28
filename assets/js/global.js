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

    if ( window.innerWidth <= 767 ) {
        customSearchform.classList.add( 'show' );
    }

    if ( window.innerWidth >=767 ) {
        customSearchform.classList.toggle( 'show' );
        primaryMenu.classList.toggle( 'hide' );
        iconSearchBtn.classList.toggle( 'hide' );
        iconCloseBtn.classList.toggle( 'show' );
    }
}

function closeCustomSearchform() {
    if ( window.innerWidth <= 767 ) {
        const customSearchform = document.querySelector( '#custom-searchform' );
        customSearchform.classList.remove( 'show' );
    }
}