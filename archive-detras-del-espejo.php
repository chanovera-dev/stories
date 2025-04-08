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
                <?php the_content(); ?>
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