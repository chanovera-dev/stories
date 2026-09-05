<?php
/**
 * The sidebar containing the main widget area
 *
 * @package Stories
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$sidebar_id = 'sidebar-1';
if ( is_page() && is_active_sidebar( 'sidebar-page' ) ) {
	$sidebar_id = 'sidebar-page';
}

if ( ! is_active_sidebar( $sidebar_id ) ) {
	return;
}
?>

<aside id="secondary" class="widget-area" role="complementary" aria-label="<?php esc_attr_e( 'Barra lateral', 'stories' ); ?>">
	<?php dynamic_sidebar( $sidebar_id ); ?>
</aside>
