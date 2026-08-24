<?php
/**
 * Template part for displaying Quote post format using Gutenberg block styles
 *
 * @package Stories
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'story-card format-quote-card' ); ?> data-id="<?php echo esc_attr( get_the_ID() ); ?>">
	<div class="stories-quote-container">
		<div class="quote-decor" aria-hidden="true"></div>
		<!-- Top Actions (Info Toggle & Like Button) -->
		<div class="post-top-actions">
			<div class="toggle-info-container">
				<button type="button" class="toggle-info-btn" aria-label="<?php esc_attr_e( 'Toggle Post Info', 'stories' ); ?>" title="<?php esc_attr_e( 'Toggle Post Info', 'stories' ); ?>"><?php stories_svg( 'info', array( 'size' => 18 ) ); ?></button>
			</div>
			<?php stories_like_button(); ?>
		</div>

		<!-- Front Content (Quote) -->
		<div class="quote-front-content">

			<div class="entry-content">
				<?php
				the_content();

				wp_link_pages(
					array(
						'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'stories' ),
						'after'  => '</div>',
					)
				);
				?>
			</div>
		</div>

		<!-- Information Overlay Card showing Categories & Tags -->
		<div class="quote-info-overlay">
			<header class="entry-header">
				<div class="entry-badge">
					<?php stories_post_type_badge(); ?>
				</div>
			</header>

			<div class="entry-taxonomies">
				<?php
				$categories_list = get_the_category_list( ', ' );
				if ( $categories_list ) :
					?>
					<div class="entry-categories">
						<strong><?php esc_html_e( 'Categorías:', 'stories' ); ?></strong>
						<div class="taxonomy-links"><?php echo $categories_list; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
					</div>
				<?php endif; ?>

				<?php
				$tags_list = get_the_tag_list( '', ', ' );
				if ( $tags_list ) :
					?>
					<div class="entry-tags">
						<strong><?php esc_html_e( 'Etiquetas:', 'stories' ); ?></strong>
						<div class="taxonomy-links"><?php echo $tags_list; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<div class="post__overlay"></div>
</article>
