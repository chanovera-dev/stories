<article class="post">
    <header class="post--header">
        <?php the_category(); ?>
    </header><!-- .micro-post--header -->
    <div class="post--content">
        <span class="micro"></span>
        <?php
            the_content();
            echo '<div class="date">' . get_the_date() . '</div>';
            echo '<div class="tags">' . get_the_tag_list() . '</div>';
        ?>
    </div><!-- .post--content -->
</article><!-- .post -->