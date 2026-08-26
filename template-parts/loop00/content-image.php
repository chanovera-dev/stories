<?php
/**
 * Template part for displaying Image post format in loops (loop00 - Standard Industry Layout)
 *
 * @package Stories
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$image_data = stories_get_image_data();
$has_thumb  = has_post_thumbnail() || ! empty( $image_data['src'] );
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'story-card loop00-card format-image-card' . ( ! $has_thumb ? ' has-no-thumbnail' : '' ) ); ?> data-id="<?php echo esc_attr( get_the_ID() ); ?>">
	<!-- 1. Featured Media / Image Container -->
	<div class="loop00-card__media">
		<a href="<?php the_permalink(); ?>" class="loop00-card__thumbnail-link" tabindex="-1" aria-hidden="true">
			<?php if ( has_post_thumbnail() ) : ?>
				<?php the_post_thumbnail( 'medium_large', array( 'class' => 'loop00-card__img', 'loading' => 'lazy', 'alt' => esc_attr( get_the_title() ) ) ); ?>
			<?php elseif ( ! empty( $image_data['src'] ) ) : ?>
				<img src="<?php echo esc_url( $image_data['src'] ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" loading="lazy" class="loop00-card__img">
			<?php else : ?>
				<div class="loop00-card__placeholder">
					<?php stories_svg( 'image', array( 'size' => 40 ) ); ?>
				</div>
			<?php endif; ?>
		</a>

		<div class="loop00-card__badge-container">
			<?php stories_post_type_badge(); ?>
		</div>

		<div class="loop00-card__action-container">
			<?php if ( ! empty( $image_data['src'] ) ) : ?>
				<button type="button" class="image-lightbox-trigger gallery-lightbox-trigger"
					data-lightbox-src="<?php echo esc_url( $image_data['src'] ); ?>"
					data-lightbox-title="<?php echo esc_attr( $image_data['title'] ); ?>"
					data-lightbox-author="<?php echo esc_attr( $image_data['author'] ); ?>"
					data-lightbox-date="<?php echo esc_attr( $image_data['date'] ); ?>"
					data-lightbox-dimensions="<?php echo esc_attr( $image_data['dimensions'] ); ?>"
					data-lightbox-filesize="<?php echo esc_attr( $image_data['filesize'] ); ?>"
					data-lightbox-camera="<?php echo esc_attr( $image_data['camera'] ); ?>"
					data-lightbox-aperture="<?php echo esc_attr( $image_data['aperture'] ); ?>"
					data-lightbox-shutter="<?php echo esc_attr( $image_data['shutter_speed'] ); ?>"
					data-lightbox-focal="<?php echo esc_attr( $image_data['focal_length'] ); ?>"
					data-lightbox-iso="<?php echo esc_attr( $image_data['iso'] ); ?>"
					data-lightbox-caption="<?php echo esc_attr( $image_data['caption'] ); ?>"
					data-lightbox-url="<?php echo esc_url( $image_data['url'] ); ?>"
					aria-label="<?php esc_attr_e( 'View full image', 'stories' ); ?>"
					title="<?php esc_attr_e( 'View full image', 'stories' ); ?>">
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
				<?php esc_html_e( 'Ver imagen', 'stories' ); ?> &rarr;
			</a>
		</div>
	</footer>
</article>
