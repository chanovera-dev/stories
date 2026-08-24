<?php
/**
 * The template for displaying all single posts and custom post types
 *
 * @package Stories
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<section class="block single-post-block">
	<div class="content">
		<?php
		while ( have_posts() ) :
			the_post();

			$format = get_post_format();
			get_template_part( 'template-parts/content-single', $format ? $format : get_post_type() );

		endwhile;
		?>
	</div>
</section>

<?php
// Render post navigation section.
get_template_part( 'templates/single/post', 'navigation' );

// Render related posts carousel section.
get_template_part( 'templates/single/related', 'posts' );

if ( comments_open() || get_comments_number() ) :
	?>
	<section class="block comments-block">
		<div class="content content-comments">
			<?php comments_template(); ?>
		</div>
	</section>
	<?php
endif;

get_footer();



