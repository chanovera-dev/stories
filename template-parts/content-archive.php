<article class="archive-post">
    <header class="archive-post--header">
        <?php
            if ( ! is_active_sidebar( 'sidebar-posts' ) && ! is_active_widget( false, false, 'categories', true ) ) {
                the_category();
            }
            
            if ( ! has_post_thumbnail() == false ) {
                echo '<img class="thumbnail" src="'; the_post_thumbnail_url( 'media' ); echo '" alt="Picture post" loading="lazy" width="300" height="200">';
            }
        ?>
    </header><!-- .archive-post--header -->
    <div class="archive-post--content">
        <a href="<?php the_permalink(); ?>" class="archive-post--permalink">
            <?php the_title( '<h3 class="archive-post--title">', '</h3>' ); ?>
        </a>
        <?php
            the_excerpt();

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
    </div><!-- .archive-post--content -->
</article><!-- .archive-post -->