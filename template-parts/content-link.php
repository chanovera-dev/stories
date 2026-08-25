<?php
/**
 * Template part for displaying Link post format in loops matching the standard content design
 *
 * @package Stories
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$link_data  = stories_get_link_post_metadata( get_the_ID() );
$target_url = ! empty( $link_data['url'] ) ? $link_data['url'] : get_permalink();
$has_thumb  = ! empty( $link_data['image'] );
$container_classes = 'stories-standard-container' . ( ! $has_thumb ? ' has-no-thumbnail' : '' );
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'story-card format-standard-card format-link-card' . ( ! $has_thumb ? ' has-no-thumbnail' : '' ) ); ?> data-id="<?php echo esc_attr( get_the_ID() ); ?>">
	<div class="<?php echo esc_attr( $container_classes ); ?>">
		<!-- Background / Pattern -->
		<div class="post-thumbnail-bg <?php echo ! $has_thumb ? 'no-thumbnail-pattern' : ''; ?>">
			<?php if ( $has_thumb ) : ?>
				<img src="<?php echo esc_url( $link_data['image'] ); ?>" alt="<?php echo esc_attr( $link_data['title'] ); ?>" loading="lazy">
			<?php endif; ?>
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

		<!-- Information Overlay Card showing Categories & Tags -->
		<div class="info-overlay quote-info-overlay">
			<header class="entry-header">
				<div class="entry-badge">
					<?php stories_post_type_badge(); ?>
				</div>
			</header>

			<div class="entry-body">
				<h2 class="entry-title">
					<a href="<?php echo esc_url( $target_url ); ?>" target="_blank" rel="noopener noreferrer">
						<?php echo esc_html( $link_data['title'] ); ?> &rarr;
					</a>
				</h2>

				<div class="entry-meta">
					<?php stories_posted_on(); ?>

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

					<?php if ( ! empty( $link_data['categories'] ) ) : ?>
						<span class="entry-categories">
							<?php stories_svg( 'folder', array( 'size' => 13 ) ); ?>
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
					<?php endif; ?>
				</div>

				<div class="entry-summary">
					<?php if ( ! empty( $link_data['excerpt'] ) ) : ?>
						<p><?php echo esc_html( $link_data['excerpt'] ); ?></p>
					<?php else : ?>
						<?php the_excerpt(); ?>
					<?php endif; ?>
				</div>
			</div>

			<footer class="entry-footer">
				<?php if ( ! empty( $link_data['tags'] ) ) : ?>
					<div class="post--tags__wrapper">
						<div class="tags post--tags">
							<?php
							foreach ( $link_data['tags'] as $tag ) {
								$tag_name = is_array( $tag ) ? $tag['name'] : $tag;
								$tag_url  = is_array( $tag ) && ! empty( $tag['url'] ) ? $tag['url'] : '';

								if ( $tag_url ) {
									echo '<a class="post-tag small" href="' . esc_url( $tag_url ) . '">' . stories_get_svg( 'tag', array( 'size' => 12 ) ) . esc_html( $tag_name ) . '</a>';
								} else {
									echo '<span class="post-tag small">' . stories_get_svg( 'tag', array( 'size' => 12 ) ) . esc_html( $tag_name ) . '</span>';
								}
							}
							?>
						</div>
					</div>
				<?php endif; ?>
			</footer>
		</div>

		<!-- Bottom Bar showing ONLY the Title -->
		<div class="standard-bottom-bar">
			<h2 class="entry-title">
				<a href="<?php echo esc_url( $target_url ); ?>" target="_blank" rel="noopener noreferrer">
					<?php echo esc_html( $link_data['title'] ); ?> &rarr;
				</a>
			</h2>
		</div>
	</div>
	<div class="post__overlay"></div>
</article>
