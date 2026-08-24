<?php
/**
 * Related Posts Template Part
 *
 * Displays a carousel slideshow of up to 8 related posts based on categories or tags.
 *
 * @package Stories
 * @subpackage Templates
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$post_id    = get_the_ID();
$categories = wp_get_post_categories( $post_id );
$tags       = wp_get_post_tags( $post_id );

$tax_query = array( 'relation' => 'OR' );

if ( ! empty( $categories ) ) {
	$tax_query[] = array(
		'taxonomy' => 'category',
		'field'    => 'term_id',
		'terms'    => $categories,
	);
}

if ( ! empty( $tags ) ) {
	$tax_query[] = array(
		'taxonomy' => 'post_tag',
		'field'    => 'term_id',
		'terms'    => wp_list_pluck( $tags, 'term_id' ),
	);
}

$args = array(
	'post_type'           => 'post',
	'posts_per_page'      => 8,
	'post__not_in'        => array( $post_id ),
	'orderby'             => 'rand',
	'ignore_sticky_posts' => 1,
);

if ( count( $tax_query ) > 1 ) {
	$args['tax_query'] = $tax_query;
}

$related_posts = new WP_Query( $args );

if ( $related_posts->have_posts() ) :
	?>
	<section class="block posts--body container--related-posts">
		<div class="content related-posts--title">
			<h2 class="title-section"><?php esc_html_e( 'Contenido relacionado', 'stories' ); ?></h2>
		</div>
		<div class="content slideshow-wrapper">
			<div class="slideshow-mask-container">
				<div class="related-posts--list slideshow">
					<?php
					while ( $related_posts->have_posts() ) :
						$related_posts->the_post();
						stories_loop_template_part( get_post_format() );
					endwhile;
					?>
				</div>
			</div>
			<div class="navigation">
				<div class="slideshow-control-container inset-shadow-effect">
					<button id="related-products--backward-button" class="slide-prev btn-pagination small-pagination slideshow-control" aria-label="<?php esc_attr_e( 'Anteriores', 'stories' ); ?>">
						<?php echo stories_get_svg( 'arrow-left-circle', array( 'size' => 18 ) ); ?>
					</button>
				</div>
				<div class="related-bullets"></div>
				<div class="slideshow-control-container inset-shadow-effect">
					<button id="related-products--forward-button" class="slide-next btn-pagination small-pagination slideshow-control" aria-label="<?php esc_attr_e( 'Siguientes', 'stories' ); ?>">
						<?php echo stories_get_svg( 'arrow-right-circle', array( 'size' => 18 ) ); ?>
					</button>
				</div>
			</div>


		</div>
		<?php wp_reset_postdata(); ?>
	</section>
	<?php
endif;
