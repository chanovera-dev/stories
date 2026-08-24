<?php
/**
 * Template part for displaying Gallery post format as a Slideshow
 *
 * @package Stories
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$post_id             = get_the_ID();
$gallery_images      = stories_get_gallery_images( $post_id, 'medium' );
$gallery_images_full = stories_get_gallery_images( $post_id, 'full' );
if ( empty( $gallery_images_full ) ) {
	$gallery_images_full = $gallery_images;
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'story-card format-gallery-card' ); ?> data-id="<?php echo esc_attr( $post_id ); ?>">
	<?php if ( ! empty( $gallery_images ) ) : ?>
		<div class="stories-slideshow" data-post-id="<?php the_ID(); ?>">
			<!-- Top Actions (Info Toggle & Like Button) -->
			<div class="post-top-actions">
				<div class="top-buttons-wrapper">
					<div class="toggle-info-container">
						<button type="button" class="image-lightbox-trigger gallery-lightbox-trigger"
							data-lightbox-src="<?php echo esc_url( ! empty( $gallery_images_full[0] ) ? $gallery_images_full[0] : $gallery_images[0] ); ?>"
							data-lightbox-title="<?php echo esc_attr( get_the_title() ); ?>"
							data-lightbox-author="<?php echo esc_attr( get_the_author() ); ?>"
							data-lightbox-date="<?php echo esc_attr( get_the_date( 'j F, Y' ) ); ?>"
							data-lightbox-caption="<?php echo esc_attr( has_excerpt() ? wp_strip_all_tags( get_the_excerpt() ) : '' ); ?>"
							data-lightbox-url="<?php echo esc_url( get_permalink() ); ?>"
							data-gallery-images="<?php echo esc_attr( wp_json_encode( array_values( $gallery_images_full ) ) ); ?>"
							data-current-index="0"
							aria-label="<?php esc_attr_e( 'Ver galería en pantalla completa', 'stories' ); ?>"
							title="<?php esc_attr_e( 'Ver galería en lightbox', 'stories' ); ?>">
							<?php echo stories_get_svg( 'fullscreen', array( 'size' => 15 ) ); ?>
						</button>

						<button type="button" class="toggle-info-btn" aria-label="<?php esc_attr_e( 'Toggle Post Info', 'stories' ); ?>" title="<?php esc_attr_e( 'Toggle Post Info', 'stories' ); ?>">
							<?php echo stories_get_svg( 'info', array( 'size' => 16 ) ); ?>
						</button>
					</div>

					<?php stories_like_button( $post_id ); ?>
				</div>
			</div>

			<div class="slides-wrapper">
				<?php foreach ( $gallery_images as $index => $image_url ) : 
					$full_url = isset( $gallery_images_full[ $index ] ) ? $gallery_images_full[ $index ] : $image_url;
				?>
					<div class="slide-item <?php echo 0 === $index ? 'is-active' : ''; ?>" data-slide-index="<?php echo esc_attr( $index ); ?>" data-full-src="<?php echo esc_url( $full_url ); ?>">
						<img src="<?php echo esc_url( $image_url ); ?>" data-full-src="<?php echo esc_url( $full_url ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" loading="lazy" decoding="async">
					</div>
				<?php endforeach; ?>
			</div>

			<!-- Information Overlay Card (Opacity 0 by default, toggleable) -->
			<div class="gallery-info-overlay">
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

			<!-- Bottom Bar Controls (Z-Index highest, always on top) -->
			<?php
			$total_images = count( $gallery_images );
			if ( $total_images > 1 ) :
				?>
				<div class="slideshow-bottom-bar">
					<div class="slideshow-control-container">
						<button type="button" class="slideshow-control prev-slide" aria-label="<?php esc_attr_e( 'Previous Image', 'stories' ); ?>"><?php stories_svg( 'arrow-left-circle', array( 'size' => 18 ) ); ?></button>
					</div>

					<?php if ( $total_images <= 5 ) : ?>
						<div class="slideshow-dots">
							<?php foreach ( $gallery_images as $index => $image_url ) : ?>
								<span class="dot-nav <?php echo 0 === $index ? 'is-active' : ''; ?>" data-slide-target="<?php echo esc_attr( $index ); ?>"></span>
							<?php endforeach; ?>
						</div>
					<?php else : ?>
						<div class="slideshow-counter-container">
							<div class="slideshow-counter">
								<span class="current-slide">1</span> / <span class="total-slides"><?php echo esc_html( $total_images ); ?></span>
							</div>
						</div>
					<?php endif; ?>

					<div class="slideshow-control-container">
						<button type="button" class="slideshow-control next-slide" aria-label="<?php esc_attr_e( 'Next Image', 'stories' ); ?>"><?php stories_svg( 'arrow-right-circle', array( 'size' => 18 ) ); ?></button>
					</div>
				</div>
			<?php endif; ?>
		</div>
	<?php else : ?>
		<!-- Fallback if no images found -->
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
