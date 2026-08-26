<?php
/**
 * Template part for displaying Status post format in loops (loop00 - Standard Industry Layout)
 *
 * @package Stories
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'story-card loop00-card format-status-card' ); ?> data-id="<?php echo esc_attr( get_the_ID() ); ?>">
	<!-- 1. Status Header with Author Avatar & Badge -->
	<div class="loop00-card__header loop00-card__header--status">
		<div class="loop00-card__status-user">
			<div class="loop00-card__status-avatar">
				<?php echo get_avatar( get_the_author_meta( 'ID' ), 40, '', get_the_author(), array( 'class' => 'avatar' ) ); ?>
			</div>
			<div class="loop00-card__status-author-info">
				<h3 class="author-name"><?php the_author(); ?></h3>
				<?php stories_posted_on(); ?>
			</div>
		</div>

		<div class="loop00-card__badge-container">
			<?php stories_post_type_badge(); ?>
		</div>

		<div class="loop00-card__action-container">
			<?php stories_like_button(); ?>
		</div>
	</div>

	<!-- 2. Status Content -->
	<div class="loop00-card__body loop00-card__body--status">
		<div class="loop00-card__status-content entry-content">
			<?php the_content(); ?>
		</div>
	</div>

	<!-- 3. Status Footer -->
	<footer class="loop00-card__footer entry-footer">
		<div class="loop00-card__read-more">
			<a href="<?php the_permalink(); ?>" class="loop00-read-more-btn">
				<?php esc_html_e( 'Ver estado', 'stories' ); ?> &rarr;
			</a>
		</div>
	</footer>
</article>
