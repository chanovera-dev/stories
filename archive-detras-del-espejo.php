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

    <header class="block post-header">
        <section class="block">
            <div class="content top">
                <div class="breadcrumbs">
                    <?php
                        if ( function_exists('wp_breadcrumbs') ) {
                            wp_breadcrumbs( '<p id="breadcrumbs">','</p>' );
                        }
                    ?>
                </div>
            </div>
        </section>
    </header><!-- .post-header -->

    <section class="block container--posts">
        <div class="content">
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
        </div>
    </section><!-- .container--posts -->
</main><!-- #main -->

<?php get_footer(); ?>