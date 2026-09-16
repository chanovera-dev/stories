<?php
/**
 * Template part for displaying posts in a Justified Grid (AJAX)
 *
 * Copies native Stories content-* card design inside the justified grid wrapper.
 *
 * @package Stories
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$post_id = get_the_ID();
$format  = get_post_format( $post_id );

// Calculate Aspect Ratio based on primary media
$ratio = 1.0;
if ( has_post_thumbnail( $post_id ) ) {
	$img_data = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), 'large' );
	if ( $img_data && ! empty( $img_data[2] ) ) {
		$ratio = $img_data[1] / $img_data[2];
	}
} elseif ( 'gallery' === $format ) {
	if ( ! function_exists( 'stories_extract_gallery_images' ) ) {
		require_once get_template_directory() . '/templates/helpers/extract-gallery-images.php';
	}
	$gallery_ids = stories_extract_gallery_images( $post_id );
	if ( ! empty( $gallery_ids ) ) {
		$img_data = wp_get_attachment_image_src( $gallery_ids[0], 'large' );
		if ( $img_data && ! empty( $img_data[2] ) ) {
			$ratio = $img_data[1] / $img_data[2];
		}
	}
}

// Ensure sane bounds for ratio to avoid breaking layout
if ( $ratio <= 0.1 || $ratio > 5 ) {
	$ratio = 1.0;
}
?>

<!-- Justified Grid Item Wrapper -->
<div class="ajax-item-wrapper" 
	style="--ratio: <?php echo esc_attr( round( $ratio, 4 ) ); ?>; flex-grow: <?php echo esc_attr( round( $ratio * 100 ) ); ?>; flex-basis: calc( var(--row-height, 250px) * <?php echo esc_attr( round( $ratio, 4 ) ); ?> ); aspect-ratio: <?php echo esc_attr( round( $ratio, 4 ) ); ?>;" 
	data-ratio="<?php echo esc_attr( round( $ratio, 4 ) ); ?>"
	data-year="<?php echo esc_attr( get_the_date( 'Y' ) ); ?>">

	<?php
	// Render format-specific Stories content card
	if ( 'gallery' === $format ) {
		get_template_part( 'template-parts/content', 'gallery' );
	} elseif ( 'image' === $format ) {
		get_template_part( 'template-parts/content', 'image' );
	} else {
		get_template_part( 'template-parts/content', $format ? $format : null );
	}
	?>

</div>
