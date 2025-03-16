<article id="post-<?php the_ID(); ?>" <?php post_class('site-main'); ?> role="main">
    <header class="post-header">
        <?php
            if ( has_post_thumbnail() == true ) : echo '<img class="thumbnail" src="'; the_post_thumbnail_url( 'full' ); echo '" alt="Imagen del artículo" loading="lazy" width="300" height="200">'; endif;
        ?>
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
        <div class="container">
            <section class="section middle">
                <?php the_title( '<h1 class="post-header--title">', '</h1>' ); ?>
                <div class="author">
                    <?php
                        if ( get_the_modified_time() ) {
                            echo '<p>' . esc_html__( 'Última modificación vez en ', 'stories' ) . get_the_modified_time() . '</p>';
                        }
                    ?>
                </div>
            </section>
        </div>
    </header><!-- .post-header -->
    <div class="container post-body">
        <section class="section">
            <div class="post-body--content is-layout-constrained">
                <?php the_content(); ?>
            </div>
            <?php
                if ( is_active_sidebar( 'sidebar-2' ) ) {
                    echo '
                    <aside class="post-body--sidebar">';
                    dynamic_sidebar( 'sidebar-2' ); echo '
                    </aside>';
                }
            ?>
        </section>
    </div><!-- .post-body -->
</article><!-- #main -->