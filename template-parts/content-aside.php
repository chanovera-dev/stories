<?php
/**
 * Template part for displaying Aside post format using Gutenberg block styles
 *
 * @package Stories
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'story-card format-aside-card' ); ?> data-id="<?php echo esc_attr( get_the_ID() ); ?>">
	<div class="stories-aside-container">
		<div class="aside-decor" aria-hidden="true">
			<div class="aside-holes">
				<span class="aside-hole"></span>
				<span class="aside-hole"></span>
				<span class="aside-hole"></span>
				<span class="aside-hole"></span>
				<span class="aside-hole"></span>
				<span class="aside-hole"></span>
				<span class="aside-hole"></span>
				<span class="aside-hole"></span>
			</div>
		</div>
		<!-- Top Actions (Info Toggle & Like Button) -->
		<div class="post-top-actions">
			<div class="toggle-info-container inset-shadow-effect">
				<button type="button" class="toggle-info-btn" aria-label="<?php esc_attr_e( 'Toggle Post Info', 'stories' ); ?>" title="<?php esc_attr_e( 'Toggle Post Info', 'stories' ); ?>">
					<?php stories_svg( 'info', array( 'size' => 18 ) ); ?>
				</button>
			</div>
			<?php stories_like_button(); ?>
		</div>

		<!-- Front Content (Aside) -->
		<div class="aside-front-content">

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
		<div class="info-overlay aside-info-overlay">
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
	<div class="post__overlay"></div>
</article>
