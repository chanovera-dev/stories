<?php
/**
 * Template part for displaying Image post format
 *
 * @package Stories
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$img_data = stories_get_image_data();
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'story-card format-image-card' ); ?> data-id="<?php echo esc_attr( get_the_ID() ); ?>">
	<?php if ( has_post_thumbnail() ) : ?>
		<div class="stories-image-container">
			<!-- Top Actions Bar (Floating Glassmorphism) -->
			<div class="post-top-actions image-top-actions">
				<div class="top-buttons-wrapper">
					<div class="toggle-info-container inset-shadow-effect">
						<?php if ( ! empty( $img_data['src'] ) ) : ?>
							<button type="button" class="image-lightbox-trigger gallery-lightbox-trigger"
								data-lightbox-src="<?php echo esc_url( $img_data['src'] ); ?>"
								data-lightbox-title="<?php echo esc_attr( $img_data['title'] ); ?>"
								data-lightbox-author="<?php echo esc_attr( $img_data['author'] ); ?>"
								data-lightbox-date="<?php echo esc_attr( $img_data['date'] ); ?>"
								data-lightbox-dimensions="<?php echo esc_attr( $img_data['dimensions'] ); ?>"
								data-lightbox-filesize="<?php echo esc_attr( $img_data['filesize'] ); ?>"
								data-lightbox-camera="<?php echo esc_attr( $img_data['camera'] ); ?>"
								data-lightbox-aperture="<?php echo esc_attr( $img_data['aperture'] ); ?>"
								data-lightbox-shutter="<?php echo esc_attr( $img_data['shutter_speed'] ); ?>"
								data-lightbox-focal="<?php echo esc_attr( $img_data['focal_length'] ); ?>"
								data-lightbox-iso="<?php echo esc_attr( $img_data['iso'] ); ?>"
								data-lightbox-caption="<?php echo esc_attr( $img_data['caption'] ); ?>"
								data-lightbox-url="<?php echo esc_url( $img_data['url'] ); ?>"
								aria-label="<?php esc_attr_e( 'View full image', 'stories' ); ?>"
								title="<?php esc_attr_e( 'View full image', 'stories' ); ?>">
								<?php echo stories_get_svg( 'fullscreen', array( 'size' => 15 ) ); ?>
							</button>
						<?php endif; ?>

						<button type="button" class="toggle-info-btn" aria-label="<?php esc_attr_e( 'Toggle Post Info', 'stories' ); ?>" title="<?php esc_attr_e( 'Toggle Post Info', 'stories' ); ?>">
							<?php echo stories_get_svg( 'info', array( 'size' => 16 ) ); ?>
						</button>
					</div>

					<?php stories_like_button(); ?>
				</div>
			</div>

			<!-- Main Photography Media Focus -->
			<div class="post-thumbnail featured-image-focus">
				<?php the_post_thumbnail( 'large', array( 'loading' => 'lazy', 'alt' => esc_attr( get_the_title() ) ) ); ?>
			</div>

			<!-- Information Overlay Card (Toggleable via Info Button) -->
			<div class="info-overlay video-info-overlay">
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
		<!-- Fallback if no thumbnail found -->
		<div class="image-fallback-wrapper">
			<header class="entry-header">
				<div class="entry-badge">
					<?php stories_post_type_badge(); ?>
				</div>

				<?php the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>

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
	<?php endif; ?>
	<div class="post__overlay"></div>
</article>
