<?php 
/**
 * The template for displaying the blog page.
 * 
 * @package Stories
 * @since 1.0.0
 */
get_header(); ?>

<main id="main" class="site-main" role="main">
    <header class="container container--title">
        <section class="section">
            <h1 class="main-title"><?php esc_html_e( 'Blog', 'stories' ); ?></h1>
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
                        the_post_pagination();

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