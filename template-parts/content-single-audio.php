<?php
/**
 * Template part for displaying Single Audio post format
 *
 * @package Stories
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$audio_data = stories_get_audio_data();
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'single-story single-audio-story' ); ?>>
	<header class="entry-header audio-entry-header">
		<div class="entry-header-top">
			<div class="entry-badges-group">
				<?php stories_post_type_badge(); ?>

				<?php
				$categories = get_the_category();
				if ( ! empty( $categories ) ) :
					$primary_cat = $categories[0];
					?>
					<a href="<?php echo esc_url( get_category_link( $primary_cat->term_id ) ); ?>" class="entry-category-badge">
						<?php echo esc_html( $primary_cat->name ); ?>
					</a>
				<?php endif; ?>
			</div>

			<div class="entry-meta-top-right">
				<div class="meta-item meta-likes">
					<?php stories_like_button(); ?>
				</div>
			</div>
		</div>

		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

		<?php if ( has_excerpt() ) : ?>
			<div class="entry-subtitle">
				<p><?php echo esc_html( get_the_excerpt() ); ?></p>
			</div>
		<?php endif; ?>

		<div class="entry-meta-card audio-meta-card">
			<div class="meta-author-side">
				<?php
				$author_id    = get_the_author_meta( 'ID' );
				$author_url   = get_author_posts_url( $author_id );
				$author_name  = get_the_author();
				$author_email = get_the_author_meta( 'email' );
				?>
				<a href="<?php echo esc_url( $author_url ); ?>" class="author-avatar-link" aria-label="<?php echo esc_attr( $author_name ); ?>">
					<?php echo get_avatar( $author_email, 48, '', esc_attr( $author_name ), array( 'class' => 'author-avatar' ) ); ?>
				</a>
				<div class="author-info">
					<span class="author-byline"><?php esc_html_e( 'Audio y texto por', 'stories' ); ?></span>
					<a href="<?php echo esc_url( $author_url ); ?>" class="author-name"><?php echo esc_html( $author_name ); ?></a>
				</div>
			</div>

			<div class="meta-details-side">
				<div class="meta-item meta-date">
					<?php echo stories_get_svg( 'calendar', array( 'size' => 14 ) ); ?>
					<time class="entry-date published" datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>">
						<?php echo esc_html( get_the_date( 'j F, Y' ) ); ?>
					</time>
				</div>

				<?php if ( comments_open() || get_comments_number() ) : ?>
					<a href="#comments" class="meta-item meta-comments" aria-label="<?php esc_attr_e( 'Ir a comentarios', 'stories' ); ?>">
						<?php echo stories_get_svg( 'chat', array( 'size' => 14 ) ); ?>
						<span><?php echo esc_html( get_comments_number_text( __( '0 comentarios', 'stories' ), __( '1 comentario', 'stories' ), __( '% comentarios', 'stories' ) ) ); ?></span>
					</a>
				<?php endif; ?>
			</div>
		</div>
	</header>

	<!-- Single Audio Player Hero Card -->
	<?php if ( $audio_data['has_audio'] || $audio_data['has_cover'] ) : ?>
		<div class="single-audio-player-hero stories-audio-container">
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
				<div class="audio-bg-notes">
					<span class="music-note note-1">♪</span>
					<span class="music-note note-2">♫</span>
					<span class="music-note note-3">♩</span>
					<span class="music-note note-4">♬</span>
					<span class="music-note note-5">♪</span>
				</div>
				<div class="audio-cover-backdrop-overlay"></div>
			</div>

			<!-- Central 3D Album Artwork & Vinyl Disc Presentation -->
			<div class="audio-artwork-wrapper">
				<div class="audio-artwork-3d">
					<div class="audio-vinyl-disc" aria-hidden="true">
						<div class="vinyl-center-label">
							<?php if ( $audio_data['has_cover'] ) : ?>
								<?php the_post_thumbnail( 'thumbnail', array( 'alt' => '' ) ); ?>
							<?php else : ?>
								<span class="vinyl-center-icon"><?php stories_svg( 'audio', array( 'size' => 16 ) ); ?></span>
							<?php endif; ?>
						</div>
					</div>

					<div class="audio-cover-art">
						<?php if ( $audio_data['has_cover'] ) : ?>
							<?php the_post_thumbnail( 'medium', array( 'loading' => 'eager', 'alt' => esc_attr( get_the_title() ) ) ); ?>
						<?php else : ?>
							<div class="audio-cover-placeholder">
								<?php stories_svg( 'audio', array( 'size' => 38 ) ); ?>
							</div>
						<?php endif; ?>
						<div class="audio-cover-title-badge">
							<span class="audio-cover-title-text"><?php the_title(); ?></span>
						</div>
					</div>
				</div>
			</div>

			<?php if ( ! empty( $audio_data['src'] ) ) : ?>
				<!-- Custom Unified Cross-Browser Controls Bar -->
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
				<div class="entry-audio-iframe">
					<?php echo $audio_data['iframe']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<div class="entry-content">
		<?php
		$raw_content = get_the_content();

		if ( has_blocks( $raw_content ) ) {
			$blocks          = parse_blocks( $raw_content );
			$filtered_blocks = array();
			foreach ( $blocks as $block ) {
				// Skip audio blocks since the audio is already presented in the custom hero player.
				if ( 'core/audio' === $block['blockName'] ) {
					continue;
				}
				$filtered_blocks[] = $block;
			}
			$rendered_content = '';
			foreach ( $filtered_blocks as $block ) {
				$rendered_content .= render_block( $block );
			}
			$filtered_content = apply_filters( 'the_content', $rendered_content );
		} else {
			// Strip standard classic audio shortcode and standalone audio tags.
			$filtered_content = preg_replace( '/\[audio[^\]]*\](?:\[\/audio\])?/i', '', $raw_content );
			$filtered_content = preg_replace( '/<audio[^>]*>.*?<\/audio>/is', '', $filtered_content );
			$filtered_content = apply_filters( 'the_content', $filtered_content );
		}

		echo $filtered_content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		wp_link_pages(
			array(
				'before' => '<div class="page-links">' . esc_html__( 'Páginas:', 'stories' ),
				'after'  => '</div>',
			)
		);
		?>
	</div>

	<footer class="entry-footer">
		<?php stories_entry_footer(); ?>
	</footer>
</article>
