<article class="post">
    <header class="post--header">
        <?php
            the_category();
        ?>
        <div class="thumbnail--wrapper">
            <?php 
                if ( ! has_post_thumbnail() == false ) {
                    echo '<img class="thumbnail" src="'; the_post_thumbnail_url( 'media' ); echo '" alt="Picture post" loading="lazy" width="300" height="200">';
                }
            ?>
        </div>
    </header><!-- .archive-post--header -->
    <div class="post--content">
        <a href="<?php the_permalink(); ?>" class="post--permalink">
            <?php the_title( '<h3 class="post--title">', '</h3>' ); ?>
        </a>
        <?php
            the_excerpt();
            echo '<div class="date">' . get_the_date() . '</div>';
            echo '<div class="tags">' . get_the_tag_list() . '</div>';
        ?>
    </div><!-- .archive-post--content -->
</article><!-- .archive-post -->