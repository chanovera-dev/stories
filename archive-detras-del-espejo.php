<?php 
/**
 * The template for displaying the detras del espejo page.
 * Template name: Detrás del espejo
 * 
 * @package Stories
 * @since 1.0.0
 */
get_header(); ?>

<main id="main" class="site-main" role="main">

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

    <div class="container container--posts">
        <section class="section">
        <div class="posts">
            <?php
                $args = array(
                    'post_type' => 'detras-del-espejo',
                    'post_status' => 'publish',
                    'paged' => get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1,
                );
                $query = new WP_Query( $args );

                if ( $query->have_posts() ) {
                    while ( $query->have_posts() ) {
                        $query->the_post();
                        get_template_part( 'template-parts/content', 'archive' );
                    }

                    the_posts_pagination( array(
                        'mid_size'  => 2,
                        'prev_text' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-left" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0"/></svg>Anterior',
                        'next_text' => 'Siguiente <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-right" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708"/></svg>'
                    ) );

                } else {
                    echo '<p>' . esc_html__( 'No posts found', 'stories' ) . '</p>';
                }
                wp_reset_postdata();
                ?>
            </div>
            <?php
                if ( is_active_sidebar( 'sidebar-detras' ) ) {
                    
                    echo '
                    <aside>';
                    dynamic_sidebar( 'sidebar-detras' ); echo '
                    </aside>';

                }
            ?>
        </section>
    </div><!-- .container--posts -->
</main><!-- #main -->

<?php get_footer(); ?>