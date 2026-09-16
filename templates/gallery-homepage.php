<?php
/**
 * Template Name: Galería con filtros por categorías
 *
 * Homepage template with AJAX category filters and NSFW toggle.
 * Replicates Avante's gallery homepage layout with full Stories theme integration.
 *
 * @package Stories
 */

get_header(); ?>

	<!-- JUSTIFIED GRID RESULTS SECTION -->
	<section class="block posts--body">
		<div class="content">
			<div id="ajax-posts-container">
				<?php
				// 1. Featured image from theme options if defined
				$f_img_url = get_option( 'stories_home_featured_image' );
				if ( empty( $f_img_url ) ) {
					$f_img_url = get_option( 'avante_home_featured_image' );
				}

				if ( ! empty( $f_img_url ) ) :
					$ratio  = 1;
					$width  = 300;
					$height = 300;

					$f_img_id = attachment_url_to_postid( $f_img_url );

					if ( $f_img_id ) {
						$img_data = wp_get_attachment_image_src( $f_img_id, 'medium_large' );
						if ( $img_data ) {
							$width  = $img_data[1];
							$height = $img_data[2];
							if ( $height > 0 ) {
								$ratio = $width / $height;
							}
						}
					}
					?>
					<div class="ajax-item-wrapper featured-home-item" 
						style="--ratio: <?php echo esc_attr( round( $ratio, 4 ) ); ?>; flex-grow: <?php echo esc_attr( round( $ratio * 100 ) ); ?>; flex-basis: calc( var(--row-height, 250px) * <?php echo esc_attr( round( $ratio, 4 ) ); ?> ); aspect-ratio: <?php echo esc_attr( round( $ratio, 4 ) ); ?>;" 
						data-ratio="<?php echo esc_attr( round( $ratio, 4 ) ); ?>"
						data-year="<?php echo esc_attr( gmdate( 'Y' ) ); ?>">
						
						<article class="story-card featured-card">
							<div class="stories-image-container">
								<div class="post-thumbnail featured-image-focus">
									<?php if ( $f_img_id ) : ?>
										<?php
										echo wp_get_attachment_image(
											$f_img_id,
											'large',
											false,
											array( 'class' => 'post-thumbnail' )
										);
										?>
									<?php else : ?>
										<img src="<?php echo esc_url( $f_img_url ); ?>" class="post-thumbnail" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
									<?php endif; ?>
								</div>
							</div>
						</article>
					</div>
				<?php endif; ?>

				<?php
				// 2. Initial query for standard posts with image or gallery formats
				$initial_args = array(
					'post_type'           => 'post',
					'post_status'         => 'publish',
					'posts_per_page'      => 24,
					'orderby'             => 'date',
					'order'               => 'DESC',
					'ignore_sticky_posts' => 1,
					'tax_query'           => array(
						array(
							'taxonomy' => 'post_format',
							'field'    => 'slug',
							'terms'    => array( 'post-format-image', 'post-format-gallery' ),
							'operator' => 'IN',
						),
					),
				);
				$initial_query = new WP_Query( $initial_args );

				if ( $initial_query->have_posts() ) :
					while ( $initial_query->have_posts() ) :
						$initial_query->the_post();

						$loop_design = function_exists( 'stories_get_loop_design' ) ? stories_get_loop_design() : 'default';
						if ( empty( $loop_design ) ) {
							$loop_design = 'default';
						}

						if ( 'default' !== $loop_design && locate_template( "template-parts/{$loop_design}/content-ajax.php" ) ) {
							get_template_part( "template-parts/{$loop_design}/content", 'ajax' );
						} elseif ( locate_template( 'template-parts/content-ajax.php' ) ) {
							get_template_part( 'template-parts/content', 'ajax' );
						} elseif ( locate_template( 'template-parts/loop00/content-ajax.php' ) ) {
							get_template_part( 'template-parts/loop00/content', 'ajax' );
						}
					endwhile;
				else :
					echo '<div class="no-results">' . esc_html__( 'No hay contenido para mostrar.', 'stories' ) . '</div>';
				endif;

				$max_pages = $initial_query->max_num_pages;
				wp_reset_postdata();
				?>
			</div>

			<!-- AJAX LOAD MORE PAGINATION BUTTON -->
			<div class="pagination-wrapper" style="text-align: center; margin-top: 2rem;">
				<button id="load-more-btn" class="btn primary" style="margin-inline: auto; <?php echo ( $max_pages <= 1 ) ? 'display: none;' : ''; ?>" 
					data-page="1" 
					data-max-pages="<?php echo esc_attr( $max_pages ); ?>">
					<span class="btn-text"><?php esc_html_e( 'Cargar más', 'stories' ); ?></span>
					<span class="btn-loader spinner" style="display: none;"></span>
				</button>
			</div>
		</div>
	</section>

<?php get_footer(); ?>
