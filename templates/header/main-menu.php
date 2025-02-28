<div class="main-menu">
    <?php
        $menu_id = get_nav_menu_locations()[ 'primary' ];
        $items = wp_get_nav_menu_items( $menu_id );
        if ( ! empty( $items ) ) {
            wp_nav_menu(
                array(
                    'container' => 'nav',
                    'container_id' => 'primary', 
                    'theme_location' => 'primary',
                ) 
            );
        }
    ?>
    <form role="search" method="get" id="custom-searchform" action="<?php echo home_url( '/' ); ?>">
        <div class="section">
            <label class="screen-reader-text" for="s"><?php esc_html__('Buscar', 'stories'); ?></label>
            <input class="wp-block-search__input" type="text" value="" name="s" id="s" placeholder="<?php esc_html_e('Buscar', 'stories'); ?>">
            <div class="buttons-container">
                <button type="submit" id="searchsubmit" value="Search" aria-label="Activate the search">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                    </svg>
                </button>
                <div class="close-custom-searchform" onclick="closeCustomSearchform()" aria-label="Close the searchform mobile">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle" viewBox="0 0 16 16">
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                        <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
                    </svg>
                </div>
            </div>
        </div>
    </form>
</div>