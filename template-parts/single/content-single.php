<?php
/**
 * Template part for displaying single post content
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Stories
 * @since 1.0.0
 */
?>
<div id="main" class="site-main" role="main">
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <?php wp_breadcrumbs(); ?>
        <header class="block">
            <div class="content">
                <?php
                the_title('<h1 class="page-title">', '</h1>');
                echo '<div class="metadata"><div class="date">' . stories_get_icon('date') . get_the_date() . '</div>';
                if (get_comments_number() > 0):
                    echo '<div class="comments">';
                    if (get_comments_number() == 1) {
                        echo stories_get_icon('comment') . get_comments_number() . '<span></span>' . esc_html__('Comentario', 'stories');
                    } else {
                        echo stories_get_icon('comment') . get_comments_number() . '<span></span>' . esc_html__('Comentarios', 'stories');
                    }
                    echo '</div>';
                endif;
                ?>
            </div>
        </header>
        <section class="block">
            <div class="content">
                <div class="is-layout-constrained">
                    <?php
                    foreach (['content', 'tags', 'author'] as $part) {
                        get_template_part('templates/single/' . $part);
                    }
                    ?>
                </div>
                <?php
                if (is_active_sidebar('sidebar-2')) {
                    echo '<aside class="sidebar page-body_sidebar">';
                    dynamic_sidebar('sidebar-2');
                    echo '</aside>';
                }
                ?>
            </div>
        </section>
        <section class="block">
            <?php get_template_part('templates/single/single-post-pagination'); ?>
        </section>
        <?php
        get_template_part('templates/single/related', 'posts');
        if (comments_open()):
            ?>
            <section class="block">
                <div class="content content-comments">
                    <?php comments_template(); ?>
                </div>
            </section>
        <?php endif; ?>
    </article>
</div>