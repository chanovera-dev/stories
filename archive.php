<?php
/**
 * The template for displaying archive pages (categories, tags, custom post types, authors)
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
		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<?php
				the_archive_title( '<h1 class="page-title">', '</h1>' );
				the_archive_description( '<div class="archive-description">', '</div>' );
				?>
			</header>

			<?php
			echo '<div class="posts-grid">';
			while ( have_posts() ) :
				the_post();

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
