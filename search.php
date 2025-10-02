<?php 
/**
 * The template for displaying the search page.
 * 
 * @package Stories
 * @since 1.0.0
 */
get_header(); ?>

<main id="main" class="site-main" role="main">
    <header class="block post-header">
        <?php get_template_part( 'templates/page/breadcrumb' ); ?>
        <section class="block container--title">
            <div class="content">
                <h1 class="main-title"><?php echo esc_html__('Búsqueda de "', 'stories'); echo the_search_query(); echo esc_html__('"', 'stories') ?></h1>
            </div>
        </section><!-- .container--title -->
    </header><!-- .post-header -->
    
    <section class="block container--posts">
        <div class="content">
            <div class="posts">
                <?php

                    if ( have_posts() ) {
                        
                        while ( have_posts() ) {
                            the_post();
                            if ( get_post_format() === 'aside' ) { 
                                get_template_part( 'template-parts/content', 'micro' );
                            } elseif ( get_post_format() === 'image' ) {
                                get_template_part( 'template-parts/content', 'image' );
                            } else { 
                                get_template_part( 'template-parts/content', 'archive' );
                            }
                        }
                        the_posts_pagination( array(
                            'mid_size'  => 2,
                            'prev_text' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-left" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0"/></svg>Anterior',
                            'next_text' => 'Siguiente <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-right" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708"/></svg>'
                        ) );

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
        </div>
    </section><!-- .container--posts -->
</main><!-- #main -->

<?php get_footer(); ?>