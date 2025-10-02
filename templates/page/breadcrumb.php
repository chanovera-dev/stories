<section class="block">
    <div class="background-breadcrumbs"></div>
    <div class="content top">
        <div class="breadcrumbs">
            <?php
                if ( function_exists('wp_breadcrumbs') ) {
                    wp_breadcrumbs( '<p id="breadcrumbs">','</p>' );
                }
            ?>
        </div>
    </div>
</section>