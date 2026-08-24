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
					printf( esc_html__( 'Search Results for: %s', 'stories' ), '<span>' . get_search_query() . '</span>' );
					?>
				</h1>
			</header>

			<?php
			echo '<div class="posts-grid">';
			while ( have_posts() ) :
				the_post();

				stories_loop_template_part( 'search' );

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
