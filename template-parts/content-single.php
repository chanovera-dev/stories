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
        <div class="container">
            <section class="section bottom">
                <div class="date">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar3-event" viewBox="0 0 16 16">
                        <path d="M14 0H2a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2M1 3.857C1 3.384 1.448 3 2 3h12c.552 0 1 .384 1 .857v10.286c0 .473-.448.857-1 .857H2c-.552 0-1-.384-1-.857z"/>
                        <path d="M12 7a1 1 0 1 0 0-2 1 1 0 0 0 0 2"/>
                    </svg>
                    <?php echo get_the_date(); ?>
                </div>
                <p class="share">
                    <?php echo esc_html_e('Compartir:', 'renata'); ?>
                    <a href="https://www.facebook.com/sharer/sharer.php?u='<?php the_permalink(); ?>" target="_blank">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-facebook" viewBox="0 0 16 16">
                            <path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951"/>
                        </svg>
                    </a>
                    <a href="https://twitter.com/intent/tweet?text='<?php the_title(); echo '&url='; the_permalink(); echo '&hashtags='; bloginfo( 'title' ); ?>" target="_blank">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-twitter-x" viewBox="0 0 16 16">
                            <path d="M12.6.75h2.454l-5.36 6.142L16 15.25h-4.937l-3.867-5.07-4.425 5.07H.316l5.733-6.57L0 .75h5.063l3.495 4.633L12.601.75Zm-.86 13.028h1.36L4.323 2.145H2.865z"/>
                        </svg>
                    </a>
                    <a href="https://api.whatsapp.com/send?text='<?php the_permalink(); ?>" target="_blank">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-whatsapp" viewBox="0 0 16 16">
                            <path d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.71 1.916.81 2.049c.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232"/>
                        </svg>
                    </a>
                    <a href="tg://msg_url?url='<?php the_permalink(); ?>" target="_blank">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-telegram" viewBox="0 0 16 16">
                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.287 5.906q-1.168.486-4.666 2.01-.567.225-.595.442c-.03.243.275.339.69.47l.175.055c.408.133.958.288 1.243.294q.39.01.868-.32 3.269-2.206 3.374-2.23c.05-.012.12-.026.166.016s.042.12.037.141c-.03.129-1.227 1.241-1.846 1.817-.193.18-.33.307-.358.336a8 8 0 0 1-.188.186c-.38.366-.664.64.015 1.088.327.216.589.393.85.571.284.194.568.387.936.629q.14.092.27.187c.331.236.63.448.997.414.214-.02.435-.22.547-.82.265-1.417.786-4.486.906-5.751a1.4 1.4 0 0 0-.013-.315.34.34 0 0 0-.114-.217.53.53 0 0 0-.31-.093c-.3.005-.763.166-2.984 1.09"/>
                        </svg>
                    </a>
                </p>
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
    <div class="container post-footer container--posts">
        <?php
            if ( comments_open() ) :
                echo '
                <section class="section">';
                    comments_template();
                echo '
                </section>';
            else:
            endif;
        ?>
        <section class="section">
            <?php

                $categories = wp_get_post_categories(get_the_ID());
                $tags = wp_get_post_tags(get_the_ID());
                $args = array(
                    'post_type'      => 'post',
                    'posts_per_page' => 3,
                    'post__not_in'   => array(get_the_ID()),
                    'orderby'        => 'rand',
                    'tax_query'      => array(
                        'relation' => 'OR',
                        array(
                            'taxonomy' => 'category',
                            'field'    => 'term_id',
                            'terms'    => $categories,
                        ),
                        array(
                            'taxonomy' => 'post_tag',
                            'field'    => 'term_id',
                            'terms'    => wp_list_pluck($tags, 'term_id'),
                        ),
                    ),
                );

                $related_posts = new WP_Query($args);

                if ($related_posts->have_posts()) :
            
            ?>

            <div class="related-posts">
                <h2><?php echo esc_html_e( 'Artículos relacionados', 'stories' ); ?></h2>
                <div class="posts related-posts--list">
                    <?php 
                        while ($related_posts->have_posts()) : $related_posts->the_post();
                            get_template_part( 'template-parts/content', 'archive' );
                        endwhile;
                    ?>
                </div>
            </div>
            
            <?php
                wp_reset_postdata();
                endif;
            ?>

        </section>
    </div><!-- .post-footer -->
</article><!-- #main -->