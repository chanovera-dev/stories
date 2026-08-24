<?php
/**
 * Template part for displaying Audio post format cards with custom cross-browser player and cover art
 *
 * @package Stories 
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$audio_data = stories_get_audio_data();
$has_media  = ! empty( $audio_data['src'] ) || ! empty( $audio_data['iframe'] ) || $audio_data['has_cover'];
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'story-card format-audio-card' ); ?> data-id="<?php echo esc_attr( get_the_ID() ); ?>">
	<?php if ( $has_media ) : ?>
		<div class="stories-container stories-audio-container">
			<!-- Background Music-Themed Decorative Backdrop -->
			<div class="audio-cover-bg" aria-hidden="true">
				<div class="audio-bg-glow"></div>
				<div class="audio-bg-frequency-rings"></div>
				<div class="audio-bg-bars" aria-hidden="true">
					<div class="audio-bars-spectrum">
						<span class="spectrum-bar bar-1"></span>
						<span class="spectrum-bar bar-2"></span>
						<span class="spectrum-bar bar-3"></span>
						<span class="spectrum-bar bar-4"></span>
						<span class="spectrum-bar bar-5"></span>
						<span class="spectrum-bar bar-6"></span>
						<span class="spectrum-bar bar-7"></span>
						<span class="spectrum-bar bar-8"></span>
						<span class="spectrum-bar bar-9"></span>
						<span class="spectrum-bar bar-10"></span>
						<span class="spectrum-bar bar-11"></span>
						<span class="spectrum-bar bar-12"></span>
						<span class="spectrum-bar bar-13"></span>
						<span class="spectrum-bar bar-14"></span>
						<span class="spectrum-bar bar-15"></span>
						<span class="spectrum-bar bar-16"></span>
						<span class="spectrum-bar bar-17"></span>
						<span class="spectrum-bar bar-18"></span>
						<span class="spectrum-bar bar-19"></span>
						<span class="spectrum-bar bar-20"></span>
						<span class="spectrum-bar bar-21"></span>
						<span class="spectrum-bar bar-22"></span>
						<span class="spectrum-bar bar-23"></span>
						<span class="spectrum-bar bar-24"></span>
						<span class="spectrum-bar bar-25"></span>
						<span class="spectrum-bar bar-26"></span>
						<span class="spectrum-bar bar-27"></span>
						<span class="spectrum-bar bar-28"></span>
					</div>
				</div>
				<div class="audio-cover-backdrop-overlay"></div>
			</div>
			<!-- Top Actions (Info Toggle & Like Button) -->
			<div class="post-top-actions audio-top-actions">
				<div class="toggle-info-container inset-shadow-effect">
					<button type="button" class="toggle-info-btn" aria-label="<?php esc_attr_e( 'Toggle Post Info', 'stories' ); ?>" title="<?php esc_attr_e( 'Toggle Post Info', 'stories' ); ?>">
						<?php stories_svg( 'info', array( 'size' => 18 ) ); ?>
					</button>
				</div>
				<?php stories_like_button(); ?>
			</div>
			<!-- Central 3D Album Artwork & Vinyl Disc Presentation -->
			<div class="audio-artwork-wrapper">
				<div class="audio-artwork-3d">
					<div class="audio-vinyl-disc" aria-hidden="true">
						<div class="vinyl-center-label">
							<?php if ( $audio_data['has_cover'] ) : ?>
								<?php the_post_thumbnail( 'thumbnail', array( 'alt' => '' ) ); ?>
							<?php else : ?>
								<span class="vinyl-center-icon"><?php stories_svg( 'volume', array( 'size' => 16 ) ); ?></span>
							<?php endif; ?>
						</div>
					</div>

					<div class="audio-cover-art">
						<?php if ( $audio_data['has_cover'] ) : ?>
							<?php the_post_thumbnail( 'medium', array( 'loading' => 'lazy', 'alt' => esc_attr( get_the_title() ) ) ); ?>
						<?php else : ?>
							<div class="audio-cover-placeholder">
								<?php stories_svg( 'volume', array( 'size' => 38 ) ); ?>
							</div>
						<?php endif; ?>
						<div class="audio-cover-title-badge">
							<span class="audio-cover-title-text"><?php the_title(); ?></span>
						</div>
					</div>
				</div>
			</div>
			<?php if ( ! empty( $audio_data['src'] ) ) : ?>
				<!-- Custom Audio Controls Bar (Cross-Browser Unified Experience) -->
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

				<!-- Hidden Native Audio for Cross-Browser Audio Control -->
				<audio class="stories-native-audio" src="<?php echo esc_url( $audio_data['src'] ); ?>" preload="metadata"></audio>
			<?php elseif ( ! empty( $audio_data['iframe'] ) ) : ?>
				<!-- External Embed / Iframe Player Fallback -->
				<div class="entry-audio-iframe">
					<?php echo $audio_data['iframe']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
			<?php endif; ?>
			<!-- Information Overlay Card (Toggleable via Info Button) -->
			<div class="info-overlay audio-info-overlay">
				<header class="entry-header">
					<div class="entry-badge">
						<?php stories_post_type_badge(); ?>
					</div>
				</header>

				<div class="entry-body">
					<?php
					if ( is_singular() ) :
						the_title( '<h1 class="entry-title">', '</h1>' );
					else :
						the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
					endif;
					?>

					<div class="entry-meta">
						<?php
						stories_posted_on();
						stories_posted_by();
						?>
						<?php if ( has_category() ) : ?>
							<span class="entry-categories">
								<?php stories_svg( 'folder', array( 'size' => 13 ) ); ?>
								<?php the_category( ', ' ); ?>
							</span>
						<?php endif; ?>
					</div>

					<div class="entry-summary">
						<?php the_excerpt(); ?>
					</div>
				</div>

				<footer class="entry-footer">
					<?php
					$tags = get_the_tags();
					if ( $tags ) :
						?>
						<div class="post--tags__wrapper">
							<div class="tags post--tags">
								<?php
								foreach ( $tags as $tag ) {
									echo '<a class="post-tag small" href="' . esc_url( get_tag_link( $tag->term_id ) ) . '">' . stories_get_svg( 'tag', array( 'size' => 12 ) ) . esc_html( $tag->name ) . '</a>';
								}
								?>
							</div>
						</div>
					<?php endif; ?>
				</footer>
			</div>
		</div>
	<?php else : ?>
		<!-- Fallback if no media found -->
		<p>No se encontró archivo de audio.</p>
	<?php endif; ?>
	<div class="post__overlay"></div>
</article>
