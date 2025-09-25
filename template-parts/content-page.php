<article id="post-<?php the_ID(); ?>" <?php post_class('site-main'); ?> role="main">
    <header class="post-header">
        <?php
            if ( has_post_thumbnail() == true ) : echo '<img class="thumbnail" src="'; the_post_thumbnail_url( 'full' ); echo '" alt="Imagen del artículo" loading="lazy" width="300" height="200">'; endif;
        ?>
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
        <section class="block">
            <div class="content middle">
                <?php the_title( '<h1 class="post-header--title">', '</h1>' ); ?>
                <div class="author">
                    <?php
                        if ( get_the_modified_time('d/m/Y') ) {
                            echo '<p>' . esc_html__( 'Este archivo fue modificado por última vez el ', 'stories' ) . get_the_modified_time('d/m/Y') . '</p>';
                        }
                    ?>
                </div>
            </div>
        </section>
    </header><!-- .post-header -->
    <section class="block post-body">
        <div class="content">
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
        </div>
    </section><!-- .post-body -->
</article><!-- #main -->