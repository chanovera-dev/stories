<?php
/**
 * Template part for displaying post author in cards/single
 *
 * @package Stories
 * @since 1.0.0
 */
?>
<div class="post--author">
	<?php
	$author_email = get_the_author_meta( 'email' );
	$author_name  = get_the_author();
	$author_desc  = get_the_author_meta( 'description' );

	echo get_avatar( $author_email, 70, '', esc_attr( $author_name ), array( 'class' => 'avatar' ) );
	echo '<h3 class="author-name">' . esc_html( $author_name ) . '</h3>';
	if ( ! empty( $author_desc ) ) {
		echo '<span class="author-description">' . esc_html( $author_desc ) . '</span>';
	}
	?>
</div>
