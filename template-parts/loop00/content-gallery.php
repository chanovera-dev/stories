<?php
/**
 * Template part for displaying Gallery post format in loops (loop00 - Standard Industry Layout)
 *
 * @package Stories
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$gallery_images      = stories_get_gallery_images( get_the_ID(), 'medium_large' );
$gallery_images_full = stories_get_gallery_images( get_the_ID(), 'full' );
if ( empty( $gallery_images_full ) ) {
	$gallery_images_full = $gallery_images;
}
$image_count      = is_array( $gallery_images ) ? count( $gallery_images ) : 0;
$has_thumb        = has_post_thumbnail() || ! empty( $gallery_images );
$primary_full_src = ! empty( $gallery_images_full[0] ) ? ( is_array( $gallery_images_full[0] ) ? $gallery_images_full[0]['url'] : $gallery_images_full[0] ) : ( has_post_thumbnail() ? get_the_post_thumbnail_url( get_the_ID(), 'full' ) : '' );
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'story-card loop00-card format-gallery-card' ); ?> data-id="<?php echo esc_attr( get_the_ID() ); ?>">
	<!-- 1. Featured Media / Gallery Container -->
	<div class="loop00-card__media">
		<a href="<?php the_permalink(); ?>" class="loop00-card__thumbnail-link" tabindex="-1" aria-hidden="true">
			<?php if ( has_post_thumbnail() ) : ?>
				<?php the_post_thumbnail( 'medium_large', array( 'class' => 'loop00-card__img', 'loading' => 'lazy', 'alt' => esc_attr( get_the_title() ) ) ); ?>
			<?php elseif ( ! empty( $gallery_images[0] ) ) : ?>
				<img src="<?php echo esc_url( is_array( $gallery_images[0] ) ? $gallery_images[0]['url'] : $gallery_images[0] ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" loading="lazy" class="loop00-card__img">
			<?php else : ?>
				<div class="loop00-card__placeholder">
					<?php stories_svg( 'gallery', array( 'size' => 40 ) ); ?>
				</div>
			<?php endif; ?>

			<?php if ( $image_count > 1 ) : ?>
				<div class="loop00-card__gallery-count">
					<?php stories_svg( 'gallery', array( 'size' => 14 ) ); ?>
					<span><?php printf( esc_html__( '%d fotos', 'stories' ), $image_count ); ?></span>
				</div>
			<?php endif; ?>
		</a>

		<div class="loop00-card__badge-container">
			<?php stories_post_type_badge(); ?>
		</div>

		<div class="loop00-card__action-container">
			<?php if ( ! empty( $primary_full_src ) ) : ?>
				<button type="button" class="image-lightbox-trigger gallery-lightbox-trigger"
					data-lightbox-src="<?php echo esc_url( $primary_full_src ); ?>"
					data-lightbox-title="<?php echo esc_attr( get_the_title() ); ?>"
					data-lightbox-author="<?php echo esc_attr( get_the_author() ); ?>"
					data-lightbox-date="<?php echo esc_attr( get_the_date( 'j F, Y' ) ); ?>"
					data-lightbox-caption="<?php echo esc_attr( has_excerpt() ? wp_strip_all_tags( get_the_excerpt() ) : '' ); ?>"
					data-lightbox-url="<?php echo esc_url( get_permalink() ); ?>"
					data-gallery-images="<?php echo esc_attr( wp_json_encode( array_values( $gallery_images_full ) ) ); ?>"
					data-current-index="0"
					aria-label="<?php esc_attr_e( 'Ver galería en pantalla completa', 'stories' ); ?>"
					title="<?php esc_attr_e( 'Ver galería en lightbox', 'stories' ); ?>">
					<?php echo stories_get_svg( 'fullscreen', array( 'size' => 15 ) ); ?>
				</button>
			<?php endif; ?>
			<?php stories_like_button(); ?>
		</div>
	</div>

	<!-- 2. Card Body / Content -->
	<div class="loop00-card__body">
		<div class="loop00-card__meta">
			<?php if ( has_category() ) : ?>
				<span class="loop00-card__categories">
					<?php the_category( ', ' ); ?>
				</span>
				<span class="loop00-card__meta-separator" aria-hidden="true">&bull;</span>
			<?php endif; ?>
			<?php stories_posted_on(); ?>
		</div>

		<h2 class="loop00-card__title entry-title">
			<a href="<?php the_permalink(); ?>" rel="bookmark">
				<?php the_title(); ?>
			</a>
		</h2>

		<div class="loop00-card__excerpt entry-summary">
			<?php the_excerpt(); ?>
		</div>
	</div>

	<!-- 3. Card Footer -->
	<footer class="loop00-card__footer entry-footer">
		<div class="loop00-card__author">
			<?php stories_posted_by(); ?>
		</div>
		<div class="loop00-card__read-more">
			<a href="<?php the_permalink(); ?>" class="loop00-read-more-btn">
				<?php esc_html_e( 'Ver galería', 'stories' ); ?> &rarr;
			</a>
		</div>
	</footer>
</article>
