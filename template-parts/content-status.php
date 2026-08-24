<?php
/**
 * Template part for displaying Status post format
 *
 * @package Stories
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'story-card format-status-card' ); ?>>
	<div class="post-top-actions">
		<?php stories_like_button(); ?>
	</div>
	<div class="status-container">
		<header class="status-header">
			<div class="author-avatar">
				<?php echo get_avatar( get_the_author_meta( 'ID' ), 48 ); ?>
			</div>
			<div class="status-meta">
				<?php stories_posted_by(); ?>
				<?php stories_posted_on(); ?>
			</div>
			<div class="entry-badge">
				<?php stories_post_type_badge(); ?>
			</div>
		</header>

		<div class="entry-content">
			<?php the_content(); ?>
		</div>
	</div>
</article>
