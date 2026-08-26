<?php
/**
 * Template part for displaying Chat post format in loops (loop00 - Standard Industry Layout)
 *
 * @package Stories
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'story-card loop00-card format-chat-card' ); ?> data-id="<?php echo esc_attr( get_the_ID() ); ?>">
	<!-- 1. Chat Header -->
	<div class="loop00-card__header loop00-card__header--chat">
		<div class="loop00-card__badge-container">
			<?php stories_post_type_badge(); ?>
		</div>

		<div class="loop00-card__action-container">
			<?php stories_like_button(); ?>
		</div>
	</div>

	<!-- 2. Chat Body / Content -->
	<div class="loop00-card__body loop00-card__body--chat">
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

		<div class="loop00-card__chat-transcript chat-transcript entry-content">
			<?php the_content(); ?>
		</div>
	</div>

	<!-- 3. Chat Footer -->
	<footer class="loop00-card__footer entry-footer">
		<div class="loop00-card__author">
			<?php stories_posted_by(); ?>
		</div>
		<div class="loop00-card__read-more">
			<a href="<?php the_permalink(); ?>" class="loop00-read-more-btn">
				<?php esc_html_e( 'Ver chat', 'stories' ); ?> &rarr;
			</a>
		</div>
	</footer>
</article>
