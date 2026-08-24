<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme.
 *
 * @package Stories
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<section class="block">
	<div class="content">
		<?php
		if ( have_posts() ) :

			if ( is_home() && ! is_front_page() ) :
				?>
				<header class="page-header">
					<h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
				</header>
				<?php
			endif;

			echo '<div class="posts-grid">';
			/* Start the Loop */
			while ( have_posts() ) :
				the_post();

				/*
				 * Include the Post-Format-specific template for the active loop design.
				 * Falls back to default template-parts/content-{$format}.php or content.php.
				 */
				stories_loop_template_part( get_post_format() );

			endwhile;
			echo '</div>';

			stories_pagination();

		else :

			get_template_part( 'template-parts/content', 'none' );

		endif;
		?>
	</div>
</section>

<?php
get_footer();
