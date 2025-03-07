        <footer id="main-footer">
            <div class="container middle-footer">
                <section class="section">
                    <div class="other-links">
                        <div class="group-links">
                            <?php
                                $menu_id_pages = get_nav_menu_locations()['pages'];
                                $menu_pages = wp_get_nav_menu_object($menu_id_pages);
                                $items_pages = wp_get_nav_menu_items($menu_id_pages);

                                if ( ! empty($items_pages) ) {
                                    echo '
                                    <div>
                                        <h3 class="title-menu">' . $menu_pages->name . '</h3>';
                                        wp_nav_menu(
                                            array(
                                                'container' => 'nav',
                                                'container_class' => 'pages',
                                                'theme_location' => 'pages',
                                            )
                                        );
                                    echo '
                                    </div>';
                                }
                            ?>
                        </div>
                    </div>
                </section>
            </div>
            <div class="container end-footer">
                <section class="section">
                    <p>© <?php bloginfo( 'name' ); echo ' ' . date("Y"); ?> • <?= __('Todos los Derechos Reservados', 'stories') ?></p>
                </section>
            </div>
        </footer>
        <?php wp_footer(); ?>
    </body>
</html>