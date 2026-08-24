<?php
/**
 * Post Navigation Template Part
 *
 * Displays a timeline carousel of posts starting with 1 newer post followed by subsequent timeline posts.
 * Displays relative/exact date on the left and the entry counter ("X de Y") on the right in nav-card-header.
 *
 * @package Stories
 * @subpackage Templates
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $post;
$original_post = $post;

// Get all published post IDs in chronological order (oldest to newest) to calculate positions
$all_post_ids = get_posts( array(
	'post_type'      => 'post',
	'posts_per_page' => -1,
	'post_status'    => 'publish',
	'orderby'        => 'date',
	'order'          => 'ASC',
	'fields'         => 'ids',
) );

$total_posts_count = count( $all_post_ids );

if ( empty( $all_post_ids ) ) {
	return;
}

// Build timeline: all posts in reverse chronological order (newest first), excluding the current post
$nav_post_ids = array_reverse( $all_post_ids );
$nav_post_ids = array_values( array_filter( $nav_post_ids, function( $id ) use ( $original_post ) {
	return $id !== $original_post->ID;
} ) );

if ( empty( $nav_post_ids ) ) {
	return;
}

$last_post_id = end( $nav_post_ids );
?>

<section class="block posts--body container--related-posts post-navigation-block">
	<div class="post-nav-bg-decor" aria-hidden="true"></div>
	<div class="content related-posts--title">
		<h2 class="title-section"><?php esc_html_e( 'Timeline de entradas', 'stories' ); ?></h2>
		<div class="navigation post-nav-title-controls">
			<div class="slideshow-control-container">
				<button class="slide-prev btn-pagination small-pagination slideshow-control" aria-label="<?php esc_attr_e( 'Anteriores', 'stories' ); ?>">
					<?php echo stories_get_svg( 'arrow-left-circle', array( 'size' => 18 ) ); ?>
				</button>
			</div>
			<div class="slideshow-control-container">
				<button class="slide-next btn-pagination small-pagination slideshow-control" aria-label="<?php esc_attr_e( 'Siguientes', 'stories' ); ?>">
					<?php echo stories_get_svg( 'arrow-right-circle', array( 'size' => 18 ) ); ?>
				</button>
			</div>
		</div>
	</div>
	<div class="content slideshow-wrapper post-navigation-slideshow" data-last-post-id="<?php echo esc_attr( $last_post_id ); ?>" data-has-more="true">
		<div class="slideshow-mask-container">
			<div class="related-posts--list slideshow">
				<?php
				foreach ( $nav_post_ids as $nav_id ) :
					$post = get_post( $nav_id );
					if ( ! $post ) {
						continue;
					}
					setup_postdata( $post );
					$post_url      = get_permalink( $post->ID );
					$time_label    = stories_get_timeline_date_label( $post->ID );
					$post_pos      = array_search( $post->ID, $all_post_ids, true );
					$entry_num     = ( false !== $post_pos ) ? ( $post_pos + 1 ) : 1;
					$counter_label = sprintf( __( '%1$d de %2$d', 'stories' ), $entry_num, $total_posts_count );
					?>
					<div class="nav-card-item" data-id="<?php echo esc_attr( 'slide-' . $post->ID ); ?>">
						<div class="nav-card-header">
							<a href="<?php echo esc_url( $post_url ); ?>" class="nav-badge time-badge" title="<?php echo esc_attr( get_the_title( $post->ID ) ); ?>">
								<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
								<span><?php echo esc_html( $time_label ); ?></span>
							</a>
							<span class="nav-badge count-badge">
								<?php echo esc_html( $counter_label ); ?>
							</span>
						</div>
						<?php stories_loop_template_part( get_post_format( $post ) ); ?>
					</div>
				<?php
				endforeach;
				wp_reset_postdata();
				?>
			</div>
		</div>
	</div>
</section>
