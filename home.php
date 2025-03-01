<?php 
/**
 * The template for displaying the blog page.
 * 
 * @package Stories
 * @since 1.0.0
 */
get_header(); ?>

<main id="main" class="site-main" role="main">
    <header class="container container--header">
        <section class="section">
            <div class="message">
                <h2 class="main-title"><?php esc_html_e( '"Espero curarme de ti en unos días. Debo dejar de fumarte, de beberte, de pensarte. Es posible. Siguiendo las prescripciones de la moral en turno. Me receto tiempo, abstinencia, soledad."', 'stories' ); ?></h2>
                <h3 class="reference"><?php esc_html_e( '—Jaime Sabines.', 'stories' ); ?></h3>
            </div>
            <img  class="tree" src="<?php echo get_theme_mod('bg_welcome', get_bloginfo('template_url') . '/assets/img/tree.webp'); ?>" alt="" srcset="">
            <div class="clouds">
                <div class="c1 one"></div>
                <div class="c1 two"></div>
                <div class="c1 three"></div>
                <div class="c1 four"></div>
                <div class="c2 one"></div>
                <div class="c2 two"></div>
                <div class="c2 three"></div>
                <div class="c2 four"></div>
            </div>
        </section>
    </header>
    <div class="container container--posts">
        <section class="section">
            <div class="posts">
                <?php

                    if ( have_posts() ) {
                        
                        while ( have_posts() ) {
                            the_post();
                            get_template_part( 'template-parts/content', 'archive' );
                        }
                        the_posts_pagination( array(
                            'mid_size'  => 2,
                            'prev_text' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-left" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0"/></svg>',
                            'next_text' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-right" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708"/></svg>'
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
        </section>
    </div><!-- .container--posts -->
</main><!-- #main -->

<?php get_footer(); ?>