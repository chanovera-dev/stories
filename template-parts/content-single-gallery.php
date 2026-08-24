<?php
/**
 * Template part for displaying Single Gallery post format
 *
 * @package Stories
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$post_id             = get_the_ID();
$gallery_images      = stories_get_gallery_images( $post_id, 'large' );
$gallery_images_full = stories_get_gallery_images( $post_id, 'full' );
if ( empty( $gallery_images_full ) ) {
	$gallery_images_full = $gallery_images;
}
$total_images = count( $gallery_images );
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'single-story single-gallery-story' ); ?>>
	<header class="entry-header gallery-entry-header">
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
					<?php stories_like_button( $post_id ); ?>
				</div>
			</div>
		</div>

		<?php if ( has_excerpt() ) : ?>
			<div class="entry-subtitle">
				<p><?php echo esc_html( get_the_excerpt() ); ?></p>
			</div>
		<?php endif; ?>

		<div class="entry-meta-card gallery-meta-card">
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
					<span class="author-byline"><?php esc_html_e( 'Galería fotográfica y texto por', 'stories' ); ?></span>
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

				<?php if ( comments_open() ) : ?>
					<a href="#comments" class="meta-item meta-comments" aria-label="<?php esc_attr_e( 'Ir a comentarios', 'stories' ); ?>">
						<?php echo stories_get_svg( 'chat', array( 'size' => 14 ) ); ?>
						<span><?php echo esc_html( get_comments_number_text( __( '0 comentarios', 'stories' ), __( '1 comentario', 'stories' ), __( '% comentarios', 'stories' ) ) ); ?></span>
					</a>
				<?php endif; ?>
			</div>
		</div>
	</header>

	<?php if ( ! empty( $gallery_images ) ) : ?>
		<!-- Gallery Theater Showcase -->
		<div class="gallery-theater-section">
			<figure class="theater-media-frame">
				<div class="stories-slideshow gallery-theater-slideshow" data-post-id="<?php the_ID(); ?>">
					<div class="slides-wrapper theater-slides-wrapper">
						<?php foreach ( $gallery_images as $index => $image_url ) : 
							$full_url = isset( $gallery_images_full[ $index ] ) ? $gallery_images_full[ $index ] : $image_url;
						?>
							<div class="slide-item <?php echo 0 === $index ? 'is-active' : ''; ?>" data-slide-index="<?php echo esc_attr( $index ); ?>" data-full-src="<?php echo esc_url( $full_url ); ?>">
								<img src="<?php echo esc_url( $full_url ); ?>" class="theater-image" alt="<?php echo esc_attr( get_the_title() ); ?>" loading="<?php echo 0 === $index ? 'eager' : 'lazy'; ?>" decoding="async">
							</div>
						<?php endforeach; ?>
					</div>

					<?php if ( $total_images > 1 ) : ?>
						<div class="slideshow-bottom-bar theater-slideshow-bottom-bar">
							<div class="slideshow-control-container inset-shadow-effect">
								<button type="button" class="slideshow-control prev-slide" aria-label="<?php esc_attr_e( 'Anterior', 'stories' ); ?>">
									<?php echo stories_get_svg( 'arrow-left-circle', array( 'size' => 18 ) ); ?>
								</button>
							</div>

							<?php if ( $total_images <= 6 ) : ?>
								<div class="slideshow-dots">
									<?php foreach ( $gallery_images as $index => $image_url ) : ?>
										<span class="dot-nav <?php echo 0 === $index ? 'is-active' : ''; ?>" data-slide-target="<?php echo esc_attr( $index ); ?>"></span>
									<?php endforeach; ?>
								</div>
							<?php else : ?>
								<div class="slideshow-counter-container inset-shadow-effect">
									<div class="slideshow-counter">
										<span class="current-slide">1</span> / <span class="total-slides"><?php echo esc_html( $total_images ); ?></span>
									</div>
								</div>
							<?php endif; ?>

							<div class="slideshow-control-container inset-shadow-effect">
								<button type="button" class="slideshow-control next-slide" aria-label="<?php esc_attr_e( 'Siguiente', 'stories' ); ?>">
									<?php echo stories_get_svg( 'arrow-right-circle', array( 'size' => 18 ) ); ?>
								</button>
							</div>
						</div>
					<?php endif; ?>
				</div>

				<div class="theater-info-bar">
					<div class="theater-header">
						<h3 class="theater-title"><?php the_title(); ?></h3>
						<?php if ( has_excerpt() ) : ?>
							<p class="theater-caption">
								<?php echo esc_html( get_the_excerpt() ); ?>
							</p>
						<?php endif; ?>
					</div>

					<div class="theater-meta-group">
						<div class="theater-meta-item meta-count" title="<?php esc_attr_e( 'Total de imágenes', 'stories' ); ?>">
							<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
							<span><?php echo sprintf( esc_html__( '%d Fotografías', 'stories' ), $total_images ); ?></span>
						</div>

						<div class="theater-meta-item meta-date" title="<?php esc_attr_e( 'Fecha de publicación', 'stories' ); ?>">
							<?php echo stories_get_svg( 'calendar', array( 'size' => 13 ) ); ?>
							<span><?php echo esc_html( get_the_date( 'j F, Y' ) ); ?></span>
						</div>

						<div class="theater-meta-item meta-author" title="<?php esc_attr_e( 'Autor', 'stories' ); ?>">
							<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
							<span><?php echo esc_html( get_the_author() ); ?></span>
						</div>

						<button type="button" class="theater-btn image-lightbox-trigger gallery-lightbox-trigger"
							data-lightbox-src="<?php echo esc_url( $gallery_images_full[0] ); ?>"
							data-lightbox-title="<?php echo esc_attr( get_the_title() ); ?>"
							data-lightbox-author="<?php echo esc_attr( get_the_author() ); ?>"
							data-lightbox-date="<?php echo esc_attr( get_the_date( 'j F, Y' ) ); ?>"
							data-lightbox-caption="<?php echo esc_attr( has_excerpt() ? wp_strip_all_tags( get_the_excerpt() ) : '' ); ?>"
							data-gallery-images="<?php echo esc_attr( wp_json_encode( array_values( $gallery_images_full ) ) ); ?>"
							data-current-index="0"
							aria-label="<?php esc_attr_e( 'Ver galería en pantalla completa', 'stories' ); ?>"
							title="<?php esc_attr_e( 'Pantalla completa', 'stories' ); ?>">
							<?php echo stories_get_svg( 'fullscreen', array( 'size' => 14 ) ); ?>
							<span><?php esc_html_e( 'Pantalla Completa', 'stories' ); ?></span>
						</button>
					</div>
				</div>
			</figure>
		</div>
	<?php endif; ?>

	<div class="entry-content gallery-story-content">
		<?php
		$raw_content = get_the_content();

		if ( has_blocks( $raw_content ) ) {
			$blocks          = parse_blocks( $raw_content );
			$filtered_blocks = array();
			foreach ( $blocks as $block ) {
				// Skip gallery blocks since they are already presented in the theater showcase.
				if ( 'core/gallery' === $block['blockName'] ) {
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
			// Strip standard classic gallery shortcode.
			$filtered_content = strip_shortcodes( $raw_content );
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
