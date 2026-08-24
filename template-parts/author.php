<?php
/**
 * Template part for displaying post author (Avante design)
 *
 * @package Stories
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$author_id    = get_the_author_meta( 'ID' );
$author_url   = get_author_posts_url( $author_id );
$author_name  = get_the_author();
$author_desc  = get_the_author_meta( 'description' );
$author_email = get_the_author_meta( 'email' );
?>
<div class="post--author">
	<?php echo get_avatar( $author_email, 70, '', esc_attr( $author_name ), array( 'class' => 'avatar' ) ); ?>
	<h3 class="author-name">
		<a href="<?php echo esc_url( $author_url ); ?>"><?php echo esc_html( $author_name ); ?></a>
	</h3>
	<?php if ( ! empty( $author_desc ) ) : ?>
		<span class="author-description"><?php echo esc_html( $author_desc ); ?></span>
	<?php endif; ?>
</div>
