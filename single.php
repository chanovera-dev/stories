<?php 
/**
 * Single Post Template
 * 
 * @package Stories
 * @since 1.0.0
 */
get_header();

if ( have_posts() ) {

    while( have_posts() ) {

        the_post();
        get_template_part( 'template-parts/content', 'single' );
        
    }

}

get_footer();