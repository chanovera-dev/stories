<?php
/**
 * Template part for displaying Chat post format
 *
 * @package Stories
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'story-card format-chat-card' ); ?>>
	<div class="post-top-actions">
		<?php stories_like_button(); ?>
	</div>
	<header class="entry-header">
		<div class="entry-badge">
			<?php stories_post_type_badge(); ?>
		</div>

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
		</div>
	</header>

	<div class="entry-content chat-transcript">
		<?php the_content(); ?>
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
</article>
