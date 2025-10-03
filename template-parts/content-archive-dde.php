<article class="post">
    <div class="post--content">
        <a href="<?php the_permalink(); ?>" class="post--permalink">
            <?php the_title( '<h3 class="post--title">', '</h3>' ); ?>
        </a>
        <?php the_excerpt(); ?>
    </div><!-- .archive-post--content -->
</article><!-- .archive-post -->