<?php
/**
 * Template part for displaying Aside post format in loops (loop00 - Standard Industry Layout)
 *
 * @package Stories
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'story-card loop00-card format-aside-card' ); ?> data-id="<?php echo esc_attr( get_the_ID() ); ?>">
	<!-- 1. Aside Header -->
	<div class="loop00-card__header loop00-card__header--aside">
		<div class="loop00-card__badge-container">
			<?php stories_post_type_badge(); ?>
		</div>

		<div class="loop00-card__action-container">
			<?php stories_like_button(); ?>
		</div>
	</div>

	<!-- 2. Aside Body / Content -->
	<div class="loop00-card__body loop00-card__body--aside">
		<div class="loop00-card__meta">
			<?php if ( has_category() ) : ?>
				<span class="loop00-card__categories">
					<?php the_category( ', ' ); ?>
				</span>
				<span class="loop00-card__meta-separator" aria-hidden="true">&bull;</span>
			<?php endif; ?>
			<?php stories_posted_on(); ?>
		</div>

		<div class="loop00-card__aside-content entry-content">
			<?php the_content(); ?>
		</div>
	</div>

	<!-- 3. Aside Footer -->
	<footer class="loop00-card__footer entry-footer">
		<div class="loop00-card__author">
			<?php stories_posted_by(); ?>
		</div>
		<div class="loop00-card__read-more">
			<a href="<?php the_permalink(); ?>" class="loop00-read-more-btn">
				<?php esc_html_e( 'Ver nota', 'stories' ); ?> &rarr;
			</a>
		</div>
	</footer>
</article>
