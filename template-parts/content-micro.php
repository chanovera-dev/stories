<article class="micro-post">
    <header class="micro-post--header">
        <?php
            if ( ! is_active_sidebar( 'sidebar-posts' ) && ! is_active_widget( false, false, 'categories', true ) ) {
                the_category();
            }
        ?>
    </header><!-- .micro-post--header -->
    <div class="micro-post--content">
        <?php
            the_content();
            echo '<div class="date">' . get_the_date() . '</div>';

            $sidebars_widgets = wp_get_sidebars_widgets();
            $tag_cloud_active = false;

            if ( ! empty( $sidebars_widgets['sidebar-posts'] ) ) {
                foreach ( $sidebars_widgets['sidebar-posts'] as $widget_id ) {
                    if ( strpos( $widget_id, 'tag_cloud' ) === 0 ) { 
                        $tag_cloud_active = true;
                        break;
                    }
                }
            }

            if ( ! $tag_cloud_active ) {
                echo '<div class="tags">' . get_the_tag_list() . '</div>';
            }
        ?>
    </div><!-- .micro-post--content -->
</article><!-- .micro-post -->