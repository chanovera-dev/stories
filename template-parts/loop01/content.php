<?php
/**
 * Template part for displaying standard posts in loops (loop01)
 *
 * @package Stories
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$has_thumb = has_post_thumbnail();
$container_classes = 'stories-standard-container' . ( ! $has_thumb ? ' has-no-thumbnail' : '' );
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'story-card format-standard-card' . ( ! $has_thumb ? ' has-no-thumbnail' : '' ) ); ?> data-id="<?php echo esc_attr( get_the_ID() ); ?>">
	<div class="<?php echo esc_attr( $container_classes ); ?>">
		<!-- Background / Pattern -->
		<div class="post-thumbnail-bg <?php echo ! $has_thumb ? 'no-thumbnail-pattern' : ''; ?>">
			<?php if ( $has_thumb ) : ?>
				<?php the_post_thumbnail( 'medium' ); ?>
			<?php endif; ?>
		</div>

		<!-- Top Actions (Info Toggle & Like Button) -->
		<div class="post-top-actions">
			<div class="toggle-info-container">
				<button type="button" class="toggle-info-btn" aria-label="<?php esc_attr_e( 'Toggle Post Content', 'stories' ); ?>" title="<?php esc_attr_e( 'Toggle Post Content', 'stories' ); ?>"><?php stories_svg( 'info', array( 'size' => 18 ) ); ?></button>
			</div>
			<?php stories_like_button(); ?>
		</div>

		<!-- Information/Content Overlay Card (Opacity 0 by default, toggleable) -->
		<div class="standard-info-overlay">
			<header class="entry-header">
				<div class="entry-badge">
					<?php stories_post_type_badge(); ?>
				</div>

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

		<!-- Bottom Bar showing ONLY the Title -->
		<div class="standard-bottom-bar">
			<?php the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>
		</div>
	</div>
	<div class="post__overlay"></div>
</article>
