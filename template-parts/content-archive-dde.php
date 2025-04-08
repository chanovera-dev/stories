<article class="archive-post">
    <div class="archive-post--content">
        <a href="<?php the_permalink(); ?>" class="archive-post--permalink">
            <?php the_title( '<h3 class="archive-post--title">', '</h3>' ); ?>
        </a>
        <?php the_excerpt(); ?>
    </div><!-- .archive-post--content -->
</article><!-- .archive-post -->