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
					<div class="toggle-info-container">
						<?php if ( ! empty( $img_data['src'] ) ) : ?>
							<button type="button" class="image-lightbox-trigger"
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

			<!-- Extended Information Overlay Card (Toggleable via info button) -->
			<div class="image-info-overlay">
				<header class="entry-header">
					<div class="entry-badge">
						<?php stories_post_type_badge(); ?>
					</div>

					<?php the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>

					<?php if ( ! empty( $img_data['caption'] ) ) : ?>
						<p class="image-overlay-caption"><?php echo esc_html( $img_data['caption'] ); ?></p>
					<?php elseif ( has_excerpt() ) : ?>
						<div class="entry-summary">
							<?php the_excerpt(); ?>
						</div>
					<?php endif; ?>
				</header>

				<div class="image-overlay-meta-grid">
					<?php if ( ! empty( $img_data['dimensions'] ) ) : ?>
						<div class="image-meta-badge badge-dimensions" title="<?php esc_attr_e( 'Resolución', 'stories' ); ?>">
							<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
							<span><?php echo esc_html( $img_data['dimensions'] ); ?></span>
						</div>
					<?php endif; ?>

					<?php if ( ! empty( $img_data['filesize'] ) ) : ?>
						<div class="image-meta-badge badge-filesize" title="<?php esc_attr_e( 'Tamaño de archivo', 'stories' ); ?>">
							<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
							<span><?php echo esc_html( $img_data['filesize'] ); ?></span>
						</div>
					<?php endif; ?>

					<?php if ( ! empty( $img_data['date'] ) ) : ?>
						<div class="image-meta-badge badge-date" title="<?php esc_attr_e( 'Fecha', 'stories' ); ?>">
							<?php echo stories_get_svg( 'calendar', array( 'size' => 12 ) ); ?>
							<span><?php echo esc_html( $img_data['date'] ); ?></span>
						</div>
					<?php endif; ?>

					<?php if ( ! empty( $img_data['author'] ) ) : ?>
						<div class="image-meta-badge badge-author" title="<?php esc_attr_e( 'Autor', 'stories' ); ?>">
							<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
							<span><?php echo esc_html( $img_data['author'] ); ?></span>
						</div>
					<?php endif; ?>

					<?php if ( ! empty( $img_data['camera'] ) ) : ?>
						<div class="image-meta-badge badge-camera" title="<?php esc_attr_e( 'Cámara', 'stories' ); ?>">
							<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path><circle cx="12" cy="13" r="4"></circle></svg>
							<span><?php echo esc_html( $img_data['camera'] ); ?></span>
						</div>
					<?php endif; ?>

					<?php if ( ! empty( $img_data['focal_length'] ) ) : ?>
						<div class="image-meta-badge badge-focal" title="<?php esc_attr_e( 'Distancia focal', 'stories' ); ?>">
							<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><circle cx="12" cy="12" r="4"></circle><line x1="4.93" y1="4.93" x2="9.17" y2="9.17"></line><line x1="14.83" y1="14.83" x2="19.07" y2="19.07"></line></svg>
							<span><?php echo esc_html( $img_data['focal_length'] ); ?></span>
						</div>
					<?php endif; ?>

					<?php if ( ! empty( $img_data['aperture'] ) ) : ?>
						<div class="image-meta-badge badge-aperture" title="<?php esc_attr_e( 'Apertura', 'stories' ); ?>">
							<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><path d="m14.31 8 5.74 9.94"></path><path d="M9.69 8h11.48"></path><path d="m7.38 12 5.74-9.94"></path><path d="M9.69 16 3.95 6.06"></path><path d="M14.31 16H2.83"></path><path d="m16.62 12-5.74 9.94"></path></svg>
							<span><?php echo esc_html( $img_data['aperture'] ); ?></span>
						</div>
					<?php endif; ?>

					<?php if ( ! empty( $img_data['shutter_speed'] ) ) : ?>
						<div class="image-meta-badge badge-shutter" title="<?php esc_attr_e( 'Velocidad de obturación', 'stories' ); ?>">
							<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
							<span><?php echo esc_html( $img_data['shutter_speed'] ); ?></span>
						</div>
					<?php endif; ?>

					<?php if ( ! empty( $img_data['iso'] ) ) : ?>
						<div class="image-meta-badge badge-iso" title="<?php esc_attr_e( 'Sensibilidad ISO', 'stories' ); ?>">
							<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path></svg>
							<span><?php echo esc_html( $img_data['iso'] ); ?></span>
						</div>
					<?php endif; ?>
				</div>

				<footer class="entry-footer">
					<?php stories_entry_footer(); ?>
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
				<?php stories_entry_footer(); ?>
			</footer>
		</div>
	<?php endif; ?>
	<div class="post__overlay"></div>
</article>
