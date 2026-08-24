<?php
/**
 * Template part for displaying Single Image post format
 *
 * @package Stories
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$img_data = stories_get_image_data();
$img_url  = ! empty( $img_data['src'] ) ? $img_data['src'] : '';
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'single-story single-image-story' ); ?>>
	<header class="entry-header image-entry-header">
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

		<?php if ( has_excerpt() ) : ?>
			<div class="entry-subtitle">
				<p><?php echo esc_html( get_the_excerpt() ); ?></p>
			</div>
		<?php endif; ?>

		<div class="entry-meta-card image-meta-card">
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
					<span class="author-byline"><?php esc_html_e( 'Fotografía y texto por', 'stories' ); ?></span>
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

	<?php if ( has_post_thumbnail() ) : ?>
		<!-- Photography Theater Showcase -->
		<div class="image-theater-section">
			<figure class="theater-media-frame">
				<div class="theater-image-wrapper">
					<?php the_post_thumbnail( 'full', array( 'class' => 'theater-image', 'loading' => 'eager' ) ); ?>
				</div>

				<div class="theater-info-bar">
					<div class="theater-header">
						<h3 class="theater-title"><?php the_title(); ?></h3>
						<?php if ( ! empty( $img_data['caption'] ) ) : ?>
							<p class="theater-caption">
								<?php echo esc_html( $img_data['caption'] ); ?>
							</p>
						<?php endif; ?>
					</div>

					<div class="theater-meta-group">
						<?php if ( ! empty( $img_data['dimensions'] ) ) : ?>
							<div class="theater-meta-item meta-dimensions" title="<?php esc_attr_e( 'Resolución', 'stories' ); ?>">
								<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
								<span><?php echo esc_html( $img_data['dimensions'] ); ?></span>
							</div>
						<?php endif; ?>

						<?php if ( ! empty( $img_data['filesize'] ) ) : ?>
							<div class="theater-meta-item meta-filesize" title="<?php esc_attr_e( 'Tamaño de archivo', 'stories' ); ?>">
								<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
								<span><?php echo esc_html( $img_data['filesize'] ); ?></span>
							</div>
						<?php endif; ?>

						<?php if ( ! empty( $img_data['date'] ) ) : ?>
							<div class="theater-meta-item meta-date" title="<?php esc_attr_e( 'Fecha', 'stories' ); ?>">
								<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
								<span><?php echo esc_html( $img_data['date'] ); ?></span>
							</div>
						<?php endif; ?>

						<?php if ( ! empty( $img_data['author'] ) ) : ?>
							<div class="theater-meta-item meta-author" title="<?php esc_attr_e( 'Autor', 'stories' ); ?>">
								<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
								<span><?php echo esc_html( $img_data['author'] ); ?></span>
							</div>
						<?php endif; ?>

						<?php if ( ! empty( $img_data['camera'] ) ) : ?>
							<div class="theater-meta-item meta-camera" title="<?php esc_attr_e( 'Cámara', 'stories' ); ?>">
								<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path><circle cx="12" cy="13" r="4"></circle></svg>
								<span><?php echo esc_html( $img_data['camera'] ); ?></span>
							</div>
						<?php endif; ?>

						<?php if ( ! empty( $img_data['focal_length'] ) ) : ?>
							<div class="theater-meta-item meta-focal" title="<?php esc_attr_e( 'Distancia focal', 'stories' ); ?>">
								<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><circle cx="12" cy="12" r="4"></circle><line x1="4.93" y1="4.93" x2="9.17" y2="9.17"></line><line x1="14.83" y1="14.83" x2="19.07" y2="19.07"></line></svg>
								<span><?php echo esc_html( $img_data['focal_length'] ); ?></span>
							</div>
						<?php endif; ?>

						<?php if ( ! empty( $img_data['aperture'] ) ) : ?>
							<div class="theater-meta-item meta-aperture" title="<?php esc_attr_e( 'Apertura', 'stories' ); ?>">
								<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><path d="m14.31 8 5.74 9.94"></path><path d="M9.69 8h11.48"></path><path d="m7.38 12 5.74-9.94"></path><path d="M9.69 16 3.95 6.06"></path><path d="M14.31 16H2.83"></path><path d="m16.62 12-5.74 9.94"></path></svg>
								<span><?php echo esc_html( $img_data['aperture'] ); ?></span>
							</div>
						<?php endif; ?>

						<?php if ( ! empty( $img_data['shutter_speed'] ) ) : ?>
							<div class="theater-meta-item meta-shutter" title="<?php esc_attr_e( 'Velocidad de obturación', 'stories' ); ?>">
								<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
								<span><?php echo esc_html( $img_data['shutter_speed'] ); ?></span>
							</div>
						<?php endif; ?>

						<?php if ( ! empty( $img_data['iso'] ) ) : ?>
							<div class="theater-meta-item meta-iso" title="<?php esc_attr_e( 'Sensibilidad ISO', 'stories' ); ?>">
								<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path></svg>
								<span><?php echo esc_html( $img_data['iso'] ); ?></span>
							</div>
						<?php endif; ?>

						<button type="button" class="theater-btn image-lightbox-trigger"
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
							aria-label="<?php esc_attr_e( 'Ver en pantalla completa', 'stories' ); ?>"
							title="<?php esc_attr_e( 'Pantalla completa', 'stories' ); ?>">
							<?php echo stories_get_svg( 'fullscreen', array( 'size' => 14 ) ); ?>
							<span><?php esc_html_e( 'Pantalla Completa', 'stories' ); ?></span>
						</button>
					</div>
				</div>
			</figure>
		</div>
	<?php endif; ?>

	<div class="entry-content image-story-content">
		<?php
		the_content(
			sprintf(
				wp_kses(
					/* translators: %s: Name of current post. */
					__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'stories' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				wp_kses_post( get_the_title() )
			)
		);

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
