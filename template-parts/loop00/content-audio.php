<?php
/**
 * Template part for displaying Audio post format in loops (loop00 - Standard Industry Layout)
 * Featuring custom cross-browser audio player with waveform equalizer.
 *
 * @package Stories
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$audio_data = stories_get_audio_data();
$has_media  = ! empty( $audio_data['src'] ) || ! empty( $audio_data['iframe'] ) || $audio_data['has_cover'];
$has_thumb  = has_post_thumbnail();
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'story-card loop00-card format-audio-card' ); ?> data-id="<?php echo esc_attr( get_the_ID() ); ?>">
	<!-- 1. Featured Media / Custom Audio Player Header -->
	<div class="loop00-card__media loop00-card__media--audio stories-audio-container">
		<?php if ( ! empty( $audio_data['iframe'] ) ) : ?>
			<div class="loop00-card__audio-embed">
				<?php echo $audio_data['iframe']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
		<?php else : ?>
			<div class="loop00-card__audio-header">
				<?php if ( $has_thumb ) : ?>
					<?php the_post_thumbnail( 'medium_large', array( 'class' => 'loop00-card__img', 'loading' => 'lazy', 'alt' => esc_attr( get_the_title() ) ) ); ?>
				<?php else : ?>
					<div class="loop00-card__audio-backdrop">
						<div class="loop00-card__audio-icon">
							<?php stories_svg( 'audio', array( 'size' => 48 ) ); ?>
						</div>
					</div>
				<?php endif; ?>

				<?php if ( ! empty( $audio_data['src'] ) ) : ?>
					<!-- Custom Unified Audio Player Bar -->
					<div class="custom-audio-controls custom-controls">
						<button type="button" class="audio-btn play-pause-btn" aria-label="<?php esc_attr_e( 'Play / Pause', 'stories' ); ?>" title="<?php esc_attr_e( 'Play / Pause', 'stories' ); ?>">
							<?php stories_svg( 'play', array( 'size' => 18 ) ); ?>
						</button>

						<div class="audio-progress-container" role="progressbar" aria-label="<?php esc_attr_e( 'Audio playback progress', 'stories' ); ?>" tabindex="0">
							<div class="audio-buffer-bar"></div>
							<div class="audio-progress-bar">
								<span class="audio-progress-handle"></span>
							</div>
						</div>

						<span class="audio-time-display">0:00 / 0:00</span>

						<div class="audio-wave-equalizer" aria-hidden="true">
							<span class="eq-bar eq-bar-1"></span>
							<span class="eq-bar eq-bar-2"></span>
							<span class="eq-bar eq-bar-3"></span>
							<span class="eq-bar eq-bar-4"></span>
						</div>

						<button type="button" class="audio-btn mute-btn" aria-label="<?php esc_attr_e( 'Mute / Unmute', 'stories' ); ?>" title="<?php esc_attr_e( 'Mute / Unmute', 'stories' ); ?>">
							<?php stories_svg( 'unmute', array( 'size' => 18 ) ); ?>
						</button>
					</div>

					<!-- Hidden Native Audio Controller -->
					<audio class="stories-native-audio" src="<?php echo esc_url( $audio_data['src'] ); ?>" preload="metadata"></audio>
				<?php endif; ?>
			</div>
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
				<?php esc_html_e( 'Escuchar audio', 'stories' ); ?> &rarr;
			</a>
		</div>
	</footer>
</article>
