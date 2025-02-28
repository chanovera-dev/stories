<div class="site-brand">
    <?php
        if ( ! has_custom_logo() ) {
            echo '<a href="' . get_home_url() . '" aria-label="link to home page">'; bloginfo( 'title' ); echo '</a>';
        } else {
            the_custom_logo();
        }
    ?>
</div>