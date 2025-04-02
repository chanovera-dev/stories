<article class="image-post">
    <header class="image-post--header">
        <?php
            the_category();
            
            if ( ! has_post_thumbnail() == false ) {
                echo '<img class="thumbnail" src="'; the_post_thumbnail_url( 'media' ); echo '" alt="Picture post" loading="lazy" width="300" height="200">';
            }
        ?>
    </header><!-- .image-post--header -->
    <div class="image-post--content">
        <?php
            echo '<div class="date">' . get_the_date() . '</div>';
            echo '<div class="tags">' . get_the_tag_list() . '</div>';
        ?>
    </div><!-- .image-post--content -->
</article><!-- .image-post -->