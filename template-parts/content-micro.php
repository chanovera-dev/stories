<article class="micro-post">
    <header class="micro-post--header">
        <?php the_category(); ?>
    </header><!-- .micro-post--header -->
    <div class="micro-post--content">
        <?php
            the_content();
            echo '<div class="date">' . get_the_date() . '</div>';
            echo '<div class="tags">' . get_the_tag_list() . '</div>';
        ?>
    </div><!-- .micro-post--content -->
</article><!-- .micro-post -->