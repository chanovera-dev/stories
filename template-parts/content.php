<?php
/**
 * Template part for displaying standard posts in loops
 *
 * @package Stories
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$has_thumb = has_post_thumbnail();
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'story-card format-standard-card' ); ?> data-id="<?php echo esc_attr( get_the_ID() ); ?>">
	<?php if ( $has_thumb ) : ?>
		<div class="stories-standard-container">
			<!-- Background Image -->
			<div class="post-thumbnail-bg">
				<?php the_post_thumbnail( 'medium' ); ?>
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

			<!-- Information/Content Overlay Card (Opacity 0 by default, toggleable) -->
			<!-- Information Overlay Card showing Categories & Tags -->
		<div class="info-overlay quote-info-overlay">
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

			<!-- Bottom Bar showing ONLY the Title -->
			<div class="standard-bottom-bar">
				<?php the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>
			</div>
		</div>
	<?php else : ?>
		<!-- Fallback if no post thumbnail present -->
		<header class="entry-header">
			<div class="entry-badge">
				<?php stories_post_type_badge(); ?>
			</div>
			<?php the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>

			<?php if ( 'post' === get_post_type() ) : ?>
				<div class="entry-meta">
					<?php
					stories_posted_on();
					stories_posted_by();
					?>
				</div>
			<?php endif; ?>
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
	<?php endif; ?>
	<div class="post__overlay"></div>
</article>
