<?php
/**
 * Template part for displaying standard posts in loops (loop00 - Standard Industry Layout)
 *
 * @package Stories
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$has_thumb    = has_post_thumbnail();
$full_img_url = $has_thumb ? get_the_post_thumbnail_url( get_the_ID(), 'full' ) : '';
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'story-card loop00-card format-standard-card' . ( ! $has_thumb ? ' has-no-thumbnail' : '' ) ); ?> data-id="<?php echo esc_attr( get_the_ID() ); ?>">
	<!-- 1. Featured Media / Header -->
	<div class="loop00-card__media">
		<a href="<?php the_permalink(); ?>" class="loop00-card__thumbnail-link" tabindex="-1" aria-hidden="true">
			<?php if ( $has_thumb ) : ?>
				<?php the_post_thumbnail( 'medium_large', array( 'class' => 'loop00-card__img', 'loading' => 'lazy', 'alt' => esc_attr( get_the_title() ) ) ); ?>
			<?php else : ?>
				<div class="loop00-card__placeholder">
					<?php stories_svg( 'standard', array( 'size' => 40 ) ); ?>
				</div>
			<?php endif; ?>
		</a>

		<div class="loop00-card__badge-container">
			<?php stories_post_type_badge(); ?>
		</div>

		<div class="loop00-card__action-container">
			<?php if ( $has_thumb && ! empty( $full_img_url ) ) : ?>
				<button type="button" class="image-lightbox-trigger gallery-lightbox-trigger"
					data-lightbox-src="<?php echo esc_url( $full_img_url ); ?>"
					data-lightbox-title="<?php echo esc_attr( get_the_title() ); ?>"
					data-lightbox-author="<?php echo esc_attr( get_the_author() ); ?>"
					data-lightbox-date="<?php echo esc_attr( get_the_date( 'j F, Y' ) ); ?>"
					data-lightbox-caption="<?php echo esc_attr( has_excerpt() ? wp_strip_all_tags( get_the_excerpt() ) : '' ); ?>"
					data-lightbox-url="<?php echo esc_url( get_permalink() ); ?>"
					aria-label="<?php esc_attr_e( 'Ver imagen en pantalla completa', 'stories' ); ?>"
					title="<?php esc_attr_e( 'Ver imagen en lightbox', 'stories' ); ?>">
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
			<a href="<?php the_permalink(); ?>" class="loop00-read-more-btn" aria-label="<?php esc_attr_e( 'Leer historia', 'stories' ); ?>">
				<?php esc_html_e( 'Leer historia', 'stories' ); ?> &rarr;
			</a>
		</div>
	</footer>
</article>
