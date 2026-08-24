<?php
/**
 * Template part for displaying Video post format
 *
 * @package Stories
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$content = apply_filters( 'the_content', get_the_content() );
$video   = get_media_embedded_in_content( $content, array( 'video', 'object', 'embed', 'iframe' ) );
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'story-card format-video-card' ); ?> data-id="<?php echo esc_attr( get_the_ID() ); ?>">
	<?php if ( ! empty( $video ) || has_post_thumbnail() ) : ?>
		<div class="stories-video-container">
			<!-- Top Actions (Info Toggle & Like Button) -->
			<div class="post-top-actions">
				<div class="toggle-info-container">
					<button type="button" class="toggle-info-btn" aria-label="<?php esc_attr_e( 'Toggle Post Info', 'stories' ); ?>" title="<?php esc_attr_e( 'Toggle Post Info', 'stories' ); ?>"><?php stories_svg( 'info', array( 'size' => 18 ) ); ?></button>
				</div>
				<?php stories_like_button(); ?>
			</div>

			<div class="entry-video">
				<?php
				if ( ! empty( $video ) ) :
					echo $video[0]; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				elseif ( has_post_thumbnail() ) :
					the_post_thumbnail( 'full' );
				endif;
				?>
			</div>

			<!-- Custom Video Controls Bar (Shown on Hover across all browsers) -->
			<div class="custom-video-controls">
				<button type="button" class="video-btn play-pause-btn" aria-label="<?php esc_attr_e( 'Play/Pause', 'stories' ); ?>"><?php stories_svg( 'play', array( 'size' => 18 ) ); ?></button>

				<div class="video-progress-container">
					<div class="video-progress-bar"></div>
				</div>

				<span class="video-time-display">0:00 / 0:00</span>

				<button type="button" class="video-btn mute-btn" aria-label="<?php esc_attr_e( 'Mute/Unmute', 'stories' ); ?>"><?php stories_svg( 'mute', array( 'size' => 18 ) ); ?></button>
				<button type="button" class="video-btn fullscreen-btn" aria-label="<?php esc_attr_e( 'Fullscreen', 'stories' ); ?>"><?php stories_svg( 'fullscreen', array( 'size' => 18 ) ); ?></button>
			</div>

			<!-- Information Overlay Card (Opacity 0 by default, toggleable) -->
			<div class="video-info-overlay">
				<header class="entry-header">
					<div class="entry-badge">
						<?php stories_post_type_badge(); ?>
					</div>

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
					</div>
				</header>

				<div class="entry-summary">
					<?php the_excerpt(); ?>
				</div>

				<footer class="entry-footer">
					<?php stories_entry_footer(); ?>
				</footer>
			</div>
		</div>
	<?php else : ?>
		<!-- Fallback if no media found -->
		<header class="entry-header">
			<div class="entry-badge">
				<?php stories_post_type_badge(); ?>
			</div>

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
			</div>
		</header>

		<div class="entry-summary">
			<?php the_excerpt(); ?>
		</div>

		<footer class="entry-footer">
			<?php stories_entry_footer(); ?>
		</footer>
	<?php endif; ?>
	<div class="post__overlay"></div>
</article>
