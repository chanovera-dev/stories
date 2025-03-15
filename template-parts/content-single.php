<article id="post-<?php the_ID(); ?>" <?php post_class('site-main'); ?> role="main">
    <header class="post-header">
        <div class="container">
            <section class="section top">
                <div class="breadcrumbs">
                    <?php
                        if ( function_exists('wp_breadcrumbs') ) {
                            wp_breadcrumbs( '<p id="breadcrumbs">','</p>' );
                        }
                    ?>
                </div>
                <?php echo the_category(); ?>
            </section>
        </div>
        <div class="container">
            <section class="section middle">
                <?php the_title( '<h1 class="post-header--title">', '</h1>' ); ?>
                <div class="author">
                    <?php
                        echo get_avatar( get_the_author_meta('email'), '43' ) . '
                        <p class="author-name">'; the_author(); echo '</p>' . '
                        <span class="author-position">'; the_author_meta('description'); echo '</span>';
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