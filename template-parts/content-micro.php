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
            $tag_cloud_active = ! empty( $sidebars_widgets['sidebar-posts'] ) && in_array( 'tag_cloud', array_map( function( $widget ) {
                return preg_replace( '/-\d+$/', '', $widget );
            }, $sidebars_widgets['sidebar-posts'] ) );

            if ( ! $tag_cloud_active ) {
                echo '<div class="tags">' . get_the_tag_list() . '</div>';
            }
        ?>
    </div><!-- .micro-post--content -->
</article><!-- .micro-post -->