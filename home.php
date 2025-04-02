<?php 
/**
 * The template for displaying the blog page.
 * Template name: Blog
 * 
 * @package Stories
 * @since 1.0.0
 */
get_header(); ?>

<main id="main" class="site-main" role="main">
    <?php if ( ! is_paged() ) : ?>
    <header class="container container--header">
        <section class="section">
            <div class="message">
            <?php
                $args = array(
                    'post_type'      => 'quote',
                    'posts_per_page' => 3,
                    'orderby'        => 'date',
                    'order'          => 'DESC',
                );

                $query = new WP_Query($args);

                if ($query->have_posts()) : 
                    while ($query->have_posts()) : $query->the_post(); ?>
                        <article><?php the_content(); ?></article>
                    <?php endwhile;
                    wp_reset_postdata();
                else :
                    echo '<p>No hay quotes disponibles.</p>';
                endif;
            ?>
            </div>
            <img class="tree" src="<?php echo get_theme_mod('bg_welcome', get_bloginfo('template_url') . '/assets/img/tree-min.webp'); ?>" alt="Decorative tree" srcset="" loading="lazy">
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
    <?php else : ?>
        <header class="container post-header">
        <div class="container">
            <section class="section top">
                <div class="breadcrumbs">
                    <?php
                        if ( function_exists('wp_breadcrumbs') ) {
                            wp_breadcrumbs( '<p id="breadcrumbs">','</p>' );
                        }
                    ?>
                </div>
            </section>
        </div>
    </header><!-- .post-header -->
    <?php endif; ?>

    <div class="container container--posts">
        <section class="section">
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
        </section>
    </div><!-- .container--posts -->
</main><!-- #main -->

<?php get_footer(); ?>