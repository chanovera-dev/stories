<?php 
/**
 * The template for displaying the archive page.
 * 
 * @package Stories
 * @since 1.0.0
 */
get_header(); ?>

<main id="main" class="site-main" role="main">
    <header class="container container--title">
        <section class="section">
            <?php the_archive_title( '<h1 class="main-title">', '</h1>' ); ?>
        </section>
    </header><!-- .container--title -->
    <div class="container container--posts">
        <section class="section">
            <div class="posts">
                <?php

                    if ( have_posts() ) {
                        
                        while ( have_posts() ) {
                            the_post();
                            get_template_part( 'template-parts/content', 'archive' );
                        }
                        the_posts_pagination();

                    } else {

                        echo '<p>' . esc_html_e( 'No posts found', 'stories' ) . '</p>';

                    }
                ?>
            </div>
            <?php
                if ( is_active_sidebar( 'sidebar-posts' ) ) {
                    
                    echo '
                    <aside>';
                    dynamic_sidebar( 'sidebar-posts' ); echo '
                    </aside>';

                }
            ?>
        </section>
    </div><!-- .container--posts -->
</main><!-- #main -->

<?php get_footer(); ?>