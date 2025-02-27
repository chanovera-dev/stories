<article id="main" class="site-main post" role="main">
    <header class="container post-header">
        <section class="section">
            <?php the_title( '<h1 class="post-header--title">', '</h1>' ); ?>
        </section>
    </header><!-- .post-header -->
    <div class="container post-body">
        <section class="section">
            <div class="post-body--content">
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
    <div class="container post-footer">
        <section class="section">
            <?php 
            comments_template();
            if ( is_active_sidebar( 'sidebar-2' ) ) {
                    echo '
                    <aside class="post-footer--sidebar">';
                    
                    echo '
                    </aside>';
                }
            ?>
        </section>
    </div><!-- .post-footer -->
</article><!-- #main -->