<?php
/**
 * Template part for displaying Single Aside post format
 *
 * @package Stories
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'single-story single-aside-story' ); ?>>
	<header class="entry-header aside-entry-header">
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

		<div class="entry-meta-card aside-meta-card">
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
					<span class="author-byline"><?php esc_html_e( 'Nota por', 'stories' ); ?></span>
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
		<figure class="post-thumbnail aside-post-thumbnail">
			<?php the_post_thumbnail( 'full' ); ?>
			<?php if ( get_the_post_thumbnail_caption() ) : ?>
				<figcaption class="thumbnail-caption">
					<?php the_post_thumbnail_caption(); ?>
				</figcaption>
			<?php endif; ?>
		</figure>
	<?php endif; ?>

	<div class="aside-single-card stories-aside-container">
		<div class="aside-decor" aria-hidden="true"></div>
		<div class="entry-content aside-front-content aside-single-content">
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
	</div>

	<footer class="entry-footer">
		<?php stories_entry_footer(); ?>
	</footer>
</article>
