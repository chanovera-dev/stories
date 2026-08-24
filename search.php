<?php
/**
 * The template for displaying search results pages
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
				<h1 class="page-title">
					<?php
					/* translators: %s: search query. */
					printf( esc_html__( 'Resultados de búsqueda para: %s', 'stories' ), '<span>' . get_search_query() . '</span>' );
					?>
				</h1>
			</header>

			<?php
			echo '<div class="posts-grid">';
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
