<?php
/**
 * Template part for displaying Link post format
 *
 * @package Stories
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$link_url = has_post_format( 'link' ) ? get_url_in_content( get_the_content() ) : get_permalink();
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'story-card format-link-card' ); ?>>
	<div class="post-top-actions">
		<?php stories_like_button(); ?>
	</div>
	<header class="entry-header">
		<div class="entry-badge">
			<?php stories_post_type_badge(); ?>
		</div>

		<h2 class="entry-title">
			<a href="<?php echo esc_url( $link_url ? $link_url : get_permalink() ); ?>" target="_blank" rel="noopener noreferrer">
				<?php the_title(); ?> &rarr;
			</a>
		</h2>

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
	<div class="post__overlay"></div>
</article>
