<?php
/**
 * Template part for displaying a message that posts cannot be found
 *
 * @package Stories
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<section class="no-results not-found">
	<header class="page-header">
		<h1 class="page-title"><?php esc_html_e( 'No se encontraron resultados', 'stories' ); ?></h1>
	</header>

	<div class="page-content">
		<?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>
			<p>
				<?php
				printf(
					wp_kses(
						/* translators: 1: link to WP admin new post page. */
						__( '¿Listo para publicar tu primera historia? <a href="%1$s">Comienza aquí</a>.', 'stories' ),
						array(
							'a' => array(
								'href' => array(),
							),
						)
					),
					esc_url( admin_url( 'post-new.php' ) )
				);
				?>
			</p>
		<?php elseif ( is_search() ) : ?>
			<p><?php esc_html_e( 'Lo sentimos, no hubo resultados que coincidan con tu búsqueda. Por favor, intenta de nuevo con otras palabras clave.', 'stories' ); ?></p>
			<?php get_search_form(); ?>
		<?php else : ?>
			<p><?php esc_html_e( 'Parece que no podemos encontrar lo que estás buscando. Tal vez una búsqueda pueda ayudarte.', 'stories' ); ?></p>
			<?php get_search_form(); ?>
		<?php endif; ?>
	</div>
</section>
