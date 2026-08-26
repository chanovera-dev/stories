<?php
/**
 * Template part for displaying Link post format in loops (loop00 - Standard Industry Layout)
 *
 * @package Stories
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$link_data  = stories_get_link_post_metadata( get_the_ID() );
$target_url = ! empty( $link_data['url'] ) ? $link_data['url'] : get_permalink();
$has_thumb  = ! empty( $link_data['image'] );
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'story-card loop00-card format-link-card' . ( ! $has_thumb ? ' has-no-thumbnail' : '' ) ); ?> data-id="<?php echo esc_attr( get_the_ID() ); ?>">
	<!-- 1. Featured Media / Header -->
	<div class="loop00-card__media">
		<a href="<?php echo esc_url( $target_url ); ?>" target="_blank" rel="noopener noreferrer" class="loop00-card__thumbnail-link" tabindex="-1" aria-hidden="true">
			<?php if ( $has_thumb ) : ?>
				<img src="<?php echo esc_url( $link_data['image'] ); ?>" alt="<?php echo esc_attr( $link_data['title'] ); ?>" loading="lazy" class="loop00-card__img">
			<?php else : ?>
				<div class="loop00-card__placeholder">
					<?php stories_svg( 'link', array( 'size' => 40 ) ); ?>
				</div>
			<?php endif; ?>
		</a>

		<div class="loop00-card__badge-container">
			<?php stories_post_type_badge(); ?>
		</div>

		<div class="loop00-card__action-container">
			<?php stories_like_button(); ?>
		</div>
	</div>

	<!-- 2. Card Body / Content -->
	<div class="loop00-card__body">
		<div class="loop00-card__meta">
			<?php if ( ! empty( $link_data['categories'] ) ) : ?>
				<span class="loop00-card__categories">
					<?php
					$cat_output = array();
					foreach ( $link_data['categories'] as $cat ) {
						if ( is_array( $cat ) && ! empty( $cat['url'] ) ) {
							$cat_output[] = '<a href="' . esc_url( $cat['url'] ) . '">' . esc_html( $cat['name'] ) . '</a>';
						} elseif ( is_array( $cat ) && ! empty( $cat['name'] ) ) {
							$cat_output[] = esc_html( $cat['name'] );
						} elseif ( is_string( $cat ) ) {
							$cat_output[] = esc_html( $cat );
						}
					}
					echo implode( ', ', $cat_output ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					?>
				</span>
				<span class="loop00-card__meta-separator" aria-hidden="true">&bull;</span>
			<?php endif; ?>
			<?php stories_posted_on(); ?>
		</div>

		<h2 class="loop00-card__title entry-title">
			<a href="<?php echo esc_url( $target_url ); ?>" target="_blank" rel="noopener noreferrer">
				<?php echo esc_html( $link_data['title'] ); ?> &rarr;
			</a>
		</h2>

		<div class="loop00-card__excerpt entry-summary">
			<?php if ( ! empty( $link_data['excerpt'] ) ) : ?>
				<p><?php echo esc_html( $link_data['excerpt'] ); ?></p>
			<?php else : ?>
				<?php the_excerpt(); ?>
			<?php endif; ?>
		</div>
	</div>

	<!-- 3. Card Footer -->
	<footer class="loop00-card__footer entry-footer">
		<div class="loop00-card__author">
			<?php if ( ! empty( $link_data['author'] ) ) : ?>
				<div class="post--author">
					<span class="avatar avatar-icon"><?php echo stories_get_svg( 'user', array( 'size' => 20 ) ); ?></span>
					<h3 class="author-name">
						<span><?php echo esc_html( $link_data['author'] ); ?></span>
					</h3>
				</div>
			<?php else : ?>
				<?php stories_posted_by(); ?>
			<?php endif; ?>
		</div>

		<div class="loop00-card__read-more">
			<a href="<?php echo esc_url( $target_url ); ?>" target="_blank" rel="noopener noreferrer" class="loop00-read-more-btn">
				<?php esc_html_e( 'Visitar enlace', 'stories' ); ?> &rarr;
			</a>
		</div>
	</footer>
</article>
