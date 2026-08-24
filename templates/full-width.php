<?php
/**
 * Template Name: Full Width Page
 * Template Post Type: page, post
 *
 * Custom page template for full-width layouts.
 *
 * @package Stories
 * @subpackage Templates
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<section class="block full-width-block">
	<div class="content full-width-content">
		<?php
		while ( have_posts() ) :
			the_post();

			get_template_part( 'template-parts/content', 'page' );

		endwhile;
		?>
	</div>
</section>

<?php
if ( comments_open() ) :
	?>
	<section class="block comments-block">
		<div class="content content-comments">
			<?php comments_template(); ?>
		</div>
	</section>
	<?php
endif;

get_footer();

