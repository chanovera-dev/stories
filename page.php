<?php
/**
 * The template for displaying all static pages
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

