<?php
/**
 * Template part for displaying Video post format in loops (loop00 - Standard Industry Layout)
 *
 * @package Stories
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$content   = apply_filters( 'the_content', get_the_content() );
$video     = get_media_embedded_in_content( $content, array( 'video', 'object', 'embed', 'iframe' ) );
$has_thumb = has_post_thumbnail();
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'story-card loop00-card format-video-card' ); ?> data-id="<?php echo esc_attr( get_the_ID() ); ?>">
	<!-- 1. Featured Media / Video Container -->
	<div class="loop00-card__media loop00-card__media--video">
		<?php if ( ! empty( $video ) ) : ?>
			<div class="loop00-card__video-embed">
				<?php echo $video[0]; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
		<?php elseif ( $has_thumb ) : ?>
			<a href="<?php the_permalink(); ?>" class="loop00-card__thumbnail-link" tabindex="-1" aria-hidden="true">
				<?php the_post_thumbnail( 'medium_large', array( 'class' => 'loop00-card__img', 'loading' => 'lazy' ) ); ?>
				<div class="loop00-card__play-badge">
					<?php stories_svg( 'play', array( 'size' => 28 ) ); ?>
				</div>
			</a>
		<?php else : ?>
			<a href="<?php the_permalink(); ?>" class="loop00-card__thumbnail-link" tabindex="-1" aria-hidden="true">
				<div class="loop00-card__placeholder">
					<?php stories_svg( 'video', array( 'size' => 40 ) ); ?>
				</div>
			</a>
		<?php endif; ?>

		<div class="loop00-card__badge-container">
			<?php stories_post_type_badge(); ?>
		</div>

		<div class="loop00-card__action-container">
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
				<?php esc_html_e( 'Ver vídeo', 'stories' ); ?> &rarr;
			</a>
		</div>
	</footer>
</article>
