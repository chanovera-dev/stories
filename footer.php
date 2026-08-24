<?php
/**
 * The Footer for the Stories theme (essentialis footer structure)
 *
 * Closes the main tag, displays the footer section, and calls wp_footer().
 *
 * @package Stories
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

	</main><!-- #main -->

	<footer id="main-footer" class="site-footer">
		<section class="block middle-footer">
			<div class="content">
				<div class="about">
					<?php
					$footer_logo  = get_option('essentialis_footer_logo', get_option('stories_footer_logo'));
					$footer_title = get_option('essentialis_footer_title', get_option('stories_footer_title', __('Sobre ', 'stories') . get_bloginfo('name')));

					if ($footer_logo): ?>
						<img class="footer-logo" src="<?php echo esc_url($footer_logo); ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>">
					<?php else: ?>
						<h3 class="title-section"><?php echo esc_html($footer_title); ?></h3>
					<?php endif; ?>
					<p class="site-bio">
						<?php
						$bio_default = __('Relatos y Cartas es un espacio dedicado a la creatividad y la expresión a través de las palabras. Aquí encontrarás cuentos, microcuentos, poemas e historias que buscan inspirar, emocionar y conectar con los lectores.', 'stories');
						$bio = get_option('essentialis_bio', get_option('stories_bio'));
						if (false === $bio || empty($bio)) {
							$bio = get_theme_mod('essentialis_bio', get_theme_mod('stories_bio', $bio_default));
						}
						echo wp_kses_post($bio);
						?>
					</p>
					<?php
					wp_nav_menu(
						array(
							'container_id'    => 'social',
							'container_class' => 'social',
							'theme_location'  => 'social',
							'fallback_cb'     => false,
						)
					);
					?>
				</div>
				<div class="other-links">
					<?php
					$footer_menus   = array('footer-1', 'footer-2', 'footer-3');
					$menu_locations = get_nav_menu_locations();

					foreach ($footer_menus as $location):
						if (isset($menu_locations[$location])):
							$menu_id    = $menu_locations[$location];
							$menu_obj   = wp_get_nav_menu_object($menu_id);
							$menu_items = wp_get_nav_menu_items($menu_id);

							if (!empty($menu_items)): ?>
								<div class="group-links">
									<h3 class="title-section"><?php echo esc_html($menu_obj->name); ?></h3>
									<?php
									wp_nav_menu(array(
										'container'       => 'nav',
										'container_class' => 'footer',
										'theme_location'  => $location,
									));
									?>
								</div>
							<?php endif;
						endif;
					endforeach;
					?>
				</div>
			</div>
		</section>
		<section class="block end-footer">
			<div class="content">
				<p>© <?php bloginfo('name'); echo ' ' . date("Y"); ?> • <?php esc_html_e('Todos los Derechos Reservados', 'stories'); ?></p>
				<div class="credit">
					<p>Diseñado y desarrollado por <a href="https://chano.dev/" target="_blank" rel="noopener noreferrer">@ChanoDEV</a></p>
				</div>
			</div>
		</section>
	</footer>
</div><!-- #page -->

<?php wp_footer(); ?>
</body>
</html>
