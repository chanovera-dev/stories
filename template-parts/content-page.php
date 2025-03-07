<article id="post-<?php the_ID(); ?>" <?php post_class('site-main'); ?> role="main">
    <header class="container post-header">
        <section class="section">
            <?php the_title( '<h1 class="post-header--title">', '</h1>' ); ?>
        </section>
    </header><!-- .post-header -->
    <div class="container post-body">
        <section class="section">
            <div class="post-body--content is-layout-constrained">
                <?php the_content(); ?>
            </div>
            <?php
                if ( is_active_sidebar( 'sidebar-3' ) ) {
                    echo '
                    <aside class="post-body--sidebar">';
                    dynamic_sidebar( 'sidebar-3' ); echo '
                    </aside>';
                }
            ?>
        </section>
    </div><!-- .post-body -->
</article><!-- #main -->