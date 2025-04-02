<article class="image-post">
    <?php
        if ( has_post_thumbnail() == true ) : echo '<img class="thumbnail" src="'; the_post_thumbnail_url( 'full' ); echo '" alt="Imagen del artículo" loading="lazy" width="300" height="200">'; endif;
    ?>
</article><!-- .image-post -->