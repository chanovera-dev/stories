<?php
/**
 * Stories Template Tags & Helper Functions
 *
 * Custom template tags and helpers for post meta, pagination, and theme templates.
 *
 * @package Stories
 * @subpackage Inc
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'stories_posted_on' ) ) :
	/**
	 * Prints HTML with meta information and calendar SVG icon for the current post-date/time.
	 */
	function stories_posted_on() {
		$time_string = sprintf(
			'<time class="entry-date published" datetime="%1$s">%2$s</time>',
			esc_attr( get_the_date( DATE_W3C ) ),
			esc_html( get_the_date() )
		);

		$icon = stories_get_svg( 'calendar', array( 'size' => 14 ) );

		echo '<span class="posted-on">' . $icon . '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a></span>';
	}
endif;

if ( ! function_exists( 'stories_posted_by' ) ) :
	/**
	 * Prints HTML with meta information for the current author (Avante design).
	 */
	function stories_posted_by() {
		get_template_part( 'template-parts/author' );
	}
endif;

if ( ! function_exists( 'stories_get_timeline_date_label' ) ) :
	/**
	 * Returns the formatted date for timeline navigation.
	 * Shows relative time ("hace X tiempo") for posts <= 2 years old,
	 * and exact date (e.g. "12 de agosto de 2023") for posts > 2 years old.
	 *
	 * @param int $post_id Post ID.
	 * @return string Formatted date string.
	 */
	function stories_get_timeline_date_label( $post_id = 0 ) {
		if ( ! $post_id ) {
			$post_id = get_the_ID();
		}

		$post_timestamp    = get_post_time( 'U', true, $post_id );
		$current_timestamp = current_time( 'timestamp' );
		$two_years_seconds = 2 * 365 * 86400; // 2 years in seconds

		if ( ( $current_timestamp - $post_timestamp ) > $two_years_seconds ) {
			return get_the_date( 'j \d\e F \d\e Y', $post_id );
		}

		$time_diff = human_time_diff( $post_timestamp, $current_timestamp );
		return sprintf( __( 'hace %s', 'stories' ), $time_diff );
	}
endif;

if ( ! function_exists( 'stories_entry_footer' ) ) :
	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 */
	function stories_entry_footer() {
		// Hide category and tag text for pages.
		if ( 'post' === get_post_type() ) {
			$categories_list = get_the_category_list( ' ' );
			if ( $categories_list ) {
				/* translators: 1: list of categories. */
				printf( '<span class="cat-links">' . esc_html__( 'Publicado en %1$s', 'stories' ) . '</span>', $categories_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}

			$tags_list = get_the_tag_list( '', ' ' );
			if ( $tags_list ) {
				/* translators: 1: list of tags. */
				printf( '<span class="tags-links">' . esc_html__( 'Etiquetado en %1$s', 'stories' ) . '</span>', $tags_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}
	}
endif;

if ( ! function_exists( 'stories_pagination' ) ) :
	/**
	 * Displays numerical pagination.
	 */
	function stories_pagination() {
		$theme_options    = get_option( 'stories_theme_options', array() );
		$pagination_style = ! empty( $theme_options['pagination_style'] ) ? $theme_options['pagination_style'] : 'default';

		$filter_cb = function( $template ) use ( $pagination_style ) {
			return '<nav class="navigation pagination pagination--' . esc_attr( $pagination_style ) . '" aria-label="%4$s">' . "\n\t\t" .
				'<h2 class="screen-reader-text">%2$s</h2>' . "\n\t\t" .
				'<div class="nav-links">%3$s</div>' . "\n\t" .
			'</nav>';
		};

		add_filter( 'navigation_markup_template', $filter_cb, 10, 1 );

		the_posts_pagination(
			array(
				'prev_text'          => stories_get_svg( 'arrow-left-circle', array( 'size' => 18 ) ),
				'next_text'          => stories_get_svg( 'arrow-right-circle', array( 'size' => 18 ) ),
				'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'stories' ) . ' </span>',
			)
		);

		remove_filter( 'navigation_markup_template', $filter_cb, 10 );
	}
endif;

if ( ! function_exists( 'stories_post_type_badge' ) ) :
	/**
	 * Displays a badge marking the post format or post type with an inline SVG icon.
	 */
	function stories_post_type_badge() {
		$format    = get_post_format();
		$post_type = get_post_type();

		if ( $format ) {
			$format_name = get_post_format_string( $format );
			$badge_class = 'post-type-badge post-format-' . esc_attr( $format );
			$badge_label = esc_html( $format_name );
			$icon_key    = $format;
		} else {
			$post_type_obj = get_post_type_object( $post_type );
			$type_name     = $post_type_obj ? $post_type_obj->labels->singular_name : ucfirst( $post_type );
			$badge_class   = 'post-type-badge post-type-' . esc_attr( $post_type );
			$badge_label   = esc_html( $type_name );
			if ( 'post' === $post_type ) {
				$icon_key = 'standard';
			} elseif ( 'page' === $post_type ) {
				$icon_key = 'aside';
			} else {
				$icon_key = 'tag';
			}
		}

		$icon_svg = stories_get_svg( $icon_key, array( 'size' => 12 ) );
		if ( empty( $icon_svg ) ) {
			$icon_svg = stories_get_svg( 'tag', array( 'size' => 12 ) );
		}

		echo '<span class="' . esc_attr( $badge_class ) . '">' . $icon_svg . '<span class="badge-text">' . $badge_label . '</span></span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
endif;

if ( ! function_exists( 'stories_reading_time' ) ) :
	/**
	 * Calculates and returns estimated reading time for the current post.
	 *
	 * @param int $post_id Post ID.
	 * @return string Formatted reading time string.
	 */
	function stories_reading_time( $post_id = 0 ) {
		if ( ! $post_id ) {
			$post_id = get_the_ID();
		}
		$content    = get_post_field( 'post_content', $post_id );
		$word_count = str_word_count( wp_strip_all_tags( $content ) );
		$minutes    = max( 1, ceil( $word_count / 200 ) );

		return sprintf( _n( '%d min de lectura', '%d min de lectura', $minutes, 'stories' ), $minutes );
	}
endif;

if ( ! function_exists( 'stories_get_gallery_images' ) ) :
	/**
	 * Retrieves an array of image URLs attached or embedded in a gallery post format.
	 *
	 * @param int    $post_id Post ID.
	 * @param string $size    Image size to retrieve. Defaults to 'medium'.
	 * @return array List of image URLs.
	 */
	function stories_get_gallery_images( $post_id = null, $size = 'medium' ) {
		if ( ! $post_id ) {
			$post_id = get_the_ID();
		}

		$images = array();

		// 1. Check for gallery block or shortcode using get_post_gallery to get attachment IDs.
		$gallery = get_post_gallery( $post_id, false );
		if ( ! empty( $gallery ) && is_array( $gallery ) ) {
			if ( ! empty( $gallery['ids'] ) ) {
				$attachment_ids = is_array( $gallery['ids'] ) ? $gallery['ids'] : explode( ',', $gallery['ids'] );
				foreach ( $attachment_ids as $attachment_id ) {
					$attachment_id = absint( trim( $attachment_id ) );
					if ( $attachment_id ) {
						$src = wp_get_attachment_image_url( $attachment_id, $size );
						if ( $src ) {
							$images[] = $src;
						}
					}
				}
			} elseif ( ! empty( $gallery['src'] ) && is_array( $gallery['src'] ) ) {
				foreach ( $gallery['src'] as $img_url ) {
					$attachment_id = attachment_url_to_postid( $img_url );
					if ( $attachment_id ) {
						$src = wp_get_attachment_image_url( $attachment_id, $size );
						$images[] = $src ? $src : $img_url;
					} else {
						$images[] = $img_url;
					}
				}
			}
		}

		// 2. Check for attached media images if gallery list is empty.
		if ( empty( $images ) ) {
			$attachments = get_attached_media( 'image', $post_id );
			if ( ! empty( $attachments ) ) {
				foreach ( $attachments as $attachment ) {
					$src = wp_get_attachment_image_url( $attachment->ID, $size );
					if ( $src ) {
						$images[] = $src;
					}
				}
			}
		}

		// 3. Fallback to featured image if present.
		if ( empty( $images ) && has_post_thumbnail( $post_id ) ) {
			$featured_url = get_the_post_thumbnail_url( $post_id, $size );
			if ( $featured_url ) {
				$images[] = $featured_url;
			}
		}

		return array_values( array_unique( $images ) );
	}
endif;

if ( ! function_exists( 'stories_breadcrumbs' ) ) :
	/**
	 * Renders breadcrumb navigation for theme templates.
	 */
	function stories_breadcrumbs() {
		global $wp_query;

		$separator = '<span class="stories-breadcrumbs-separator" aria-hidden="true">' . stories_get_svg( 'chevron-right', array( 'size' => 16 ) ) . '</span>';
		$items     = array();

		$paged     = get_query_var( 'paged' ) ? (int) get_query_var( 'paged' ) : ( get_query_var( 'page' ) ? (int) get_query_var( 'page' ) : 1 );
		$max_pages = isset( $wp_query->max_num_pages ) ? (int) $wp_query->max_num_pages : 1;

		$home_icon = stories_get_svg( 'home', array( 'size' => 16 ) );

		if ( is_front_page() || is_home() ) {
			$items[] = sprintf(
				'<li class="stories-breadcrumbs-item"><a href="%1$s">%2$s <span>%3$s</span></a></li>',
				esc_url( home_url( '/' ) ),
				$home_icon,
				esc_html__( 'Inicio', 'stories' )
			);

			if ( $paged <= 1 ) {
				$items[] = sprintf(
					'<li class="stories-breadcrumbs-item is-current" aria-current="page">%s</li>',
					esc_html__( 'Contenido más reciente', 'stories' )
				);
			} else {
				$label = $max_pages > 1
					? sprintf( __( 'Página %1$d de %2$d', 'stories' ), $paged, $max_pages )
					: sprintf( __( 'Página %d', 'stories' ), $paged );

				$items[] = sprintf(
					'<li class="stories-breadcrumbs-item is-current" aria-current="page">%s</li>',
					esc_html( $label )
				);
			}
		} else {
			// Home Link
			$items[] = sprintf(
				'<li class="stories-breadcrumbs-item"><a href="%1$s">%2$s <span>%3$s</span></a></li>',
				esc_url( home_url( '/' ) ),
				$home_icon,
				esc_html__( 'Inicio', 'stories' )
			);

			if ( is_single() ) {
				$post_type = get_post_type();
				if ( 'post' === $post_type ) {
					$categories = get_the_category();
					if ( ! empty( $categories ) ) {
						$cat     = $categories[0];
						$parents = array_reverse( get_ancestors( $cat->term_id, 'category' ) );
						foreach ( $parents as $parent_id ) {
							$parent = get_term( $parent_id, 'category' );
							if ( $parent && ! is_wp_error( $parent ) ) {
								$items[] = sprintf(
									'<li class="stories-breadcrumbs-item"><a href="%1$s">%2$s</a></li>',
									esc_url( get_category_link( $parent->term_id ) ),
									esc_html( $parent->name )
								);
							}
						}
						$items[] = sprintf(
							'<li class="stories-breadcrumbs-item"><a href="%1$s">%2$s</a></li>',
							esc_url( get_category_link( $cat->term_id ) ),
							esc_html( $cat->name )
						);
					}
				} else {
					$post_type_obj = get_post_type_object( $post_type );
					if ( $post_type_obj && $post_type_obj->has_archive ) {
						$items[] = sprintf(
							'<li class="stories-breadcrumbs-item"><a href="%1$s">%2$s</a></li>',
							esc_url( get_post_type_archive_link( $post_type ) ),
							esc_html( $post_type_obj->labels->singular_name )
						);
					}
				}

				$items[] = sprintf(
					'<li class="stories-breadcrumbs-item is-current" aria-current="page">%s</li>',
					esc_html( get_the_title() )
				);
			} elseif ( is_page() ) {
				global $post;
				if ( $post && $post->post_parent ) {
					$anc = array_reverse( get_post_ancestors( $post->ID ) );
					foreach ( $anc as $ancestor ) {
						$items[] = sprintf(
							'<li class="stories-breadcrumbs-item"><a href="%1$s">%2$s</a></li>',
							esc_url( get_permalink( $ancestor ) ),
							esc_html( get_the_title( $ancestor ) )
						);
					}
				}
				$items[] = sprintf(
					'<li class="stories-breadcrumbs-item is-current" aria-current="page">%s</li>',
					esc_html( get_the_title() )
				);
			} elseif ( is_category() || is_tag() || is_tax() ) {
				$term = get_queried_object();
				if ( $term && ! is_wp_error( $term ) ) {
					if ( $paged > 1 ) {
						$items[] = sprintf(
							'<li class="stories-breadcrumbs-item"><a href="%1$s">%2$s</a></li>',
							esc_url( get_term_link( $term ) ),
							esc_html( single_term_title( '', false ) )
						);
						$label = $max_pages > 1
							? sprintf( __( 'Página %1$d de %2$d', 'stories' ), $paged, $max_pages )
							: sprintf( __( 'Página %d', 'stories' ), $paged );
						$items[] = sprintf(
							'<li class="stories-breadcrumbs-item is-current" aria-current="page">%s</li>',
							esc_html( $label )
						);
					} else {
						$items[] = sprintf(
							'<li class="stories-breadcrumbs-item is-current" aria-current="page">%s</li>',
							esc_html( single_term_title( '', false ) )
						);
					}
				}
			} elseif ( is_archive() ) {
				$title = wp_strip_all_tags( get_the_archive_title() );
				if ( $paged > 1 ) {
					$items[] = sprintf(
						'<li class="stories-breadcrumbs-item"><a href="%1$s">%2$s</a></li>',
						esc_url( get_post_type_archive_link( get_post_type() ) ),
						esc_html( $title )
					);
					$label = $max_pages > 1
						? sprintf( __( 'Página %1$d de %2$d', 'stories' ), $paged, $max_pages )
						: sprintf( __( 'Página %d', 'stories' ), $paged );
					$items[] = sprintf(
						'<li class="stories-breadcrumbs-item is-current" aria-current="page">%s</li>',
						esc_html( $label )
					);
				} else {
					$items[] = sprintf(
						'<li class="stories-breadcrumbs-item is-current" aria-current="page">%s</li>',
						esc_html( $title )
					);
				}
			} elseif ( is_search() ) {
				/* translators: %s: search query */
				$title = sprintf( __( 'Búsqueda: "%s"', 'stories' ), get_search_query() );
				if ( $paged > 1 ) {
					$items[] = sprintf(
						'<li class="stories-breadcrumbs-item"><a href="%1$s">%2$s</a></li>',
						esc_url( get_search_link() ),
						esc_html( $title )
					);
					$label = $max_pages > 1
						? sprintf( __( 'Página %1$d de %2$d', 'stories' ), $paged, $max_pages )
						: sprintf( __( 'Página %d', 'stories' ), $paged );
					$items[] = sprintf(
						'<li class="stories-breadcrumbs-item is-current" aria-current="page">%s</li>',
						esc_html( $label )
					);
				} else {
					$items[] = sprintf(
						'<li class="stories-breadcrumbs-item is-current" aria-current="page">%s</li>',
						esc_html( $title )
					);
				}
			} elseif ( is_404() ) {
				$items[] = sprintf(
					'<li class="stories-breadcrumbs-item is-current" aria-current="page">%s</li>',
					esc_html__( 'Página no encontrada (404)', 'stories' )
				);
			}
		}

		echo '<section class="block block-breadcrumbs">';
		echo '<div class="content">';
		echo '<nav class="stories-breadcrumbs" aria-label="' . esc_attr__( 'Breadcrumb', 'stories' ) . '">';
		echo '<ol class="stories-breadcrumbs-list">';
		echo implode( '<li class="stories-breadcrumbs-separator-item" aria-hidden="true">' . $separator . '</li>', $items );
		echo '</ol>';
		echo '</nav>';
		echo '</div>';
		echo '</section>';
	}
endif;

if ( ! function_exists( 'stories_get_likes_count' ) ) :
	/**
	 * Returns the total likes count for a post.
	 *
	 * @param int $post_id Post ID.
	 * @return int Total likes count.
	 */
	function stories_get_likes_count( $post_id ) {
		$likes = get_post_meta( $post_id, '_avante_likes_count', true );
		if ( '' === $likes || false === $likes ) {
			$likes = get_post_meta( $post_id, '_stories_likes_count', true );
		}
		return $likes ? (int) $likes : 0;
	}
endif;

if ( ! function_exists( 'stories_user_has_liked' ) ) :
	/**
	 * Checks whether the current visitor has liked a given post.
	 *
	 * @param int $post_id Post ID.
	 * @return bool True if liked, false otherwise.
	 */
	function stories_user_has_liked( $post_id ) {
		return isset( $_COOKIE[ 'stories_liked_' . $post_id ] ) || isset( $_COOKIE[ 'avante_liked_' . $post_id ] );
	}
endif;

if ( ! function_exists( 'stories_has_user_liked' ) ) :
	/**
	 * Alias for stories_user_has_liked.
	 *
	 * @param int $post_id Post ID.
	 * @return bool True if liked, false otherwise.
	 */
	function stories_has_user_liked( $post_id ) {
		return stories_user_has_liked( $post_id );
	}
endif;

if ( ! function_exists( 'stories_render_like_button' ) ) :
	/**
	 * Renders the HTML for the post like button with full accessibility matching Avante theme.
	 *
	 * @param int $post_id Optional. Post ID. Defaults to current post.
	 * @return string Like button HTML.
	 */
	function stories_render_like_button( $post_id = 0 ) {
		if ( ! $post_id ) {
			$post_id = get_the_ID();
		}
		if ( ! $post_id ) {
			return '';
		}

		$likes_count = stories_get_likes_count( $post_id );
		$has_liked   = stories_user_has_liked( $post_id );

		$is_active = ( $has_liked || $likes_count > 0 );
		$class     = 'button__like' . ( $is_active ? ' liked' : '' );
		$icon_key  = $is_active ? 'heart-fill' : 'heart';
		$icon      = stories_get_svg( $icon_key, array( 'size' => 16 ) );

		$post_title = get_the_title( $post_id );
		$aria_label = $has_liked
			? sprintf( __( 'Quitar me gusta a "%s"', 'stories' ), $post_title )
			: sprintf( __( 'Dar me gusta a "%s"', 'stories' ), $post_title );

		ob_start();
		?>
		<div class="inset-shadow-effect like-btn-container<?php echo $is_active ? ' is-liked' : ''; ?>" data-post-id="<?php echo esc_attr( $post_id ); ?>">
			<button type="button"
					class="<?php echo esc_attr( $class ); ?>"
					aria-label="<?php echo esc_attr( $aria_label ); ?>"
					aria-pressed="<?php echo $has_liked ? 'true' : 'false'; ?>"
					data-post-id="<?php echo esc_attr( $post_id ); ?>"
					data-post-title="<?php echo esc_attr( $post_title ); ?>">
				<?php echo $icon; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php if ( $likes_count > 0 ) : ?>
					<span class="like-count"><?php echo esc_html( $likes_count ); ?></span>
				<?php endif; ?>
			</button>
		</div>
		<?php
		return ob_get_clean();
	}
endif;

if ( ! function_exists( 'stories_like_button' ) ) :
	/**
	 * Helper function to output like button directly.
	 *
	 * @param int $post_id Optional. Post ID. Defaults to current post.
	 */
	function stories_like_button( $post_id = 0 ) {
		echo stories_render_like_button( $post_id ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
endif;

if ( ! function_exists( 'stories_get_image_data' ) ) :
	/**
	 * Retrieves formatted image and EXIF metadata for post format image.
	 *
	 * @param int $post_id Optional. Post ID. Defaults to current post.
	 * @return array Formatted metadata array.
	 */
	function stories_get_image_data( $post_id = 0 ) {
		if ( ! $post_id ) {
			$post_id = get_the_ID();
		}

		$thumb_id = get_post_thumbnail_id( $post_id );
		if ( ! $thumb_id ) {
			return array();
		}

		$meta      = wp_get_attachment_metadata( $thumb_id );
		$img_src   = wp_get_attachment_image_src( $thumb_id, 'full' );
		$exif      = isset( $meta['image_meta'] ) ? $meta['image_meta'] : array();
		$file_path = get_attached_file( $thumb_id );
		$filesize  = ( $file_path && file_exists( $file_path ) ) ? size_format( filesize( $file_path ) ) : ( isset( $meta['filesize'] ) ? size_format( $meta['filesize'] ) : '' );

		$width  = isset( $meta['width'] ) ? $meta['width'] : ( $img_src ? $img_src[1] : 0 );
		$height = isset( $meta['height'] ) ? $meta['height'] : ( $img_src ? $img_src[2] : 0 );

		// Shutter speed formatting
		$shutter = '';
		if ( ! empty( $exif['shutter_speed'] ) ) {
			$speed = (float) $exif['shutter_speed'];
			if ( $speed > 0 ) {
				$shutter = ( $speed < 1 ) ? '1/' . round( 1 / $speed ) . 's' : $speed . 's';
			}
		}

		// Aperture formatting
		$aperture = ! empty( $exif['aperture'] ) ? 'f/' . $exif['aperture'] : '';

		// Focal length formatting
		$focal = ! empty( $exif['focal_length'] ) ? $exif['focal_length'] . ' mm' : '';

		// ISO formatting
		$iso = ! empty( $exif['iso'] ) ? 'ISO ' . $exif['iso'] : '';

		// Camera model formatting
		$camera = ! empty( $exif['camera'] ) ? $exif['camera'] : '';

		$author_id = get_post_field( 'post_author', $post_id );

		return array(
			'src'           => $img_src ? $img_src[0] : '',
			'title'         => get_the_title( $post_id ),
			'author'        => get_the_author_meta( 'display_name', $author_id ),
			'date'          => get_the_date( 'j F, Y', $post_id ),
			'dimensions'    => ( $width && $height ) ? "{$width} × {$height} px" : '',
			'filesize'      => $filesize,
			'camera'        => $camera,
			'aperture'      => $aperture,
			'shutter_speed' => $shutter,
			'focal_length'  => $focal,
			'iso'           => $iso,
			'caption'       => get_the_post_thumbnail_caption( $post_id ) ?: ( has_excerpt( $post_id ) ? wp_strip_all_tags( get_the_excerpt( $post_id ) ) : '' ),
			'url'           => get_permalink( $post_id ),
		);
	}
endif;

if ( ! function_exists( 'stories_get_audio_data' ) ) :
	/**
	 * Extracts audio source URL, iframe embeds, and artwork cover for audio post formats.
	 *
	 * @param int $post_id Optional. Post ID. Defaults to current post.
	 * @return array Audio data array containing src, iframe, cover, title, and artist.
	 */
	function stories_get_audio_data( $post_id = 0 ) {
		if ( ! $post_id ) {
			$post_id = get_the_ID();
		}

		$audio_src    = '';
		$audio_iframe = '';
		$post_obj     = get_post( $post_id );
		$raw_content  = $post_obj ? $post_obj->post_content : '';

		// 1. Check embedded media in filtered content
		$content        = apply_filters( 'the_content', $raw_content );
		$embedded_media = get_media_embedded_in_content( $content, array( 'audio', 'iframe' ) );

		if ( ! empty( $embedded_media ) ) {
			foreach ( $embedded_media as $media_item ) {
				// Direct audio src attribute
				if ( preg_match( '/<audio[^>]*src=["\']([^"\']+)["\']/i', $media_item, $match ) ) {
					$audio_src = $match[1];
					break;
				}
				// Nested <source src="..."> inside <audio>
				if ( preg_match( '/<source[^>]*src=["\']([^"\']+)["\']/i', $media_item, $match ) ) {
					$audio_src = $match[1];
					break;
				}
				// External iframe (e.g. Spotify, SoundCloud, Bandcamp)
				if ( empty( $audio_iframe ) && strpos( $media_item, '<iframe' ) !== false ) {
					$audio_iframe = $media_item;
				}
			}
		}

		// 2. Check audio shortcode [audio src="..."] or [audio "..."]
		if ( empty( $audio_src ) && ! empty( $raw_content ) ) {
			if ( preg_match( '/\[audio[^\]]*src=["\']([^"\']+)["\']/i', $raw_content, $match ) ) {
				$audio_src = $match[1];
			} elseif ( preg_match( '/\[audio\s+["\']?([^"\'\]\s]+)["\']?\]/i', $raw_content, $match ) ) {
				$audio_src = $match[1];
			}
		}

		// 3. Check attached audio files in media library
		if ( empty( $audio_src ) ) {
			$attached_audio = get_attached_media( 'audio', $post_id );
			if ( ! empty( $attached_audio ) ) {
				$first_audio = reset( $attached_audio );
				$audio_src   = wp_get_attachment_url( $first_audio->ID );
			}
		}

		// 4. Check post enclosure meta
		if ( empty( $audio_src ) ) {
			$enclosure = get_post_meta( $post_id, 'enclosure', true );
			if ( ! empty( $enclosure ) ) {
				$enclosure_parts = explode( "\n", $enclosure );
				if ( ! empty( $enclosure_parts[0] ) ) {
					$audio_src = trim( $enclosure_parts[0] );
				}
			}
		}

		// Featured Image as Cover Art
		$has_cover = has_post_thumbnail( $post_id );
		$cover_url = $has_cover ? get_the_post_thumbnail_url( $post_id, 'large' ) : '';

		$author_id = get_post_field( 'post_author', $post_id );

		return array(
			'src'       => $audio_src,
			'iframe'    => $audio_iframe,
			'has_audio' => ! empty( $audio_src ) || ! empty( $audio_iframe ),
			'has_cover' => $has_cover,
			'cover_url' => $cover_url,
			'title'     => get_the_title( $post_id ),
			'author'    => get_the_author_meta( 'display_name', $author_id ),
			'permalink' => get_permalink( $post_id ),
		);
	}
endif;

if ( ! function_exists( 'stories_get_loop_design' ) ) :
	/**
	 * Get active loop design slug from theme options.
	 *
	 * @return string Active loop design folder/slug (e.g. 'default', 'loop00', 'loop01').
	 */
	function stories_get_loop_design() {
		$options = get_option( 'stories_theme_options', array() );
		if ( ! empty( $options['loop_design'] ) ) {
			return sanitize_key( $options['loop_design'] );
		}
		return 'default';
	}
endif;

if ( ! function_exists( 'stories_get_available_loop_designs' ) ) :
	/**
	 * Dynamically scan template-parts/ directory for available loop folders.
	 *
	 * Scans for folders matching 'loop*', 'theme*', or 'layout*'.
	 *
	 * @return array Array of loop slug => human-readable label.
	 */
	function stories_get_available_loop_designs() {
		$designs = array(
			'default' => __( 'Por defecto (template-parts/)', 'stories' ),
		);

		$template_parts_dir = STORIES_DIR . '/template-parts';
		if ( is_dir( $template_parts_dir ) ) {
			$items = scandir( $template_parts_dir );
			if ( false !== $items ) {
				foreach ( $items as $item ) {
					if ( '.' !== $item && '..' !== $item && is_dir( $template_parts_dir . '/' . $item ) ) {
						if ( 0 === strpos( $item, 'loop' ) || 0 === strpos( $item, 'theme' ) || 0 === strpos( $item, 'layout' ) ) {
							$designs[ $item ] = sprintf( __( 'Diseño: %s', 'stories' ), ucfirst( $item ) );
						}
					}
				}
			}
		}

		return $designs;
	}
endif;

if ( ! function_exists( 'stories_loop_template_part' ) ) :
	/**
	 * Render a loop template part according to the active loop design.
	 *
	 * Searches first in template-parts/{loop_design}/{slug}-{format}.php,
	 * then template-parts/{loop_design}/{slug}.php,
	 * and falls back to template-parts/{slug}-{format}.php or template-parts/{slug}.php.
	 *
	 * @param string|null $format Post format or sub-slug (e.g. 'audio', 'video', 'search', 'none').
	 * @param string      $slug   Base slug (default 'content').
	 */
	function stories_loop_template_part( $format = null, $slug = 'content' ) {
		$loop_design = stories_get_loop_design();

		if ( 'default' !== $loop_design ) {
			// 1. Try specific format inside active loop directory: template-parts/{loop_design}/{slug}-{format}.php
			if ( ! empty( $format ) && locate_template( "template-parts/{$loop_design}/{$slug}-{$format}.php" ) ) {
				get_template_part( "template-parts/{$loop_design}/{$slug}", $format );
				return;
			}

			// 2. Try generic content inside active loop directory: template-parts/{loop_design}/{slug}.php
			if ( locate_template( "template-parts/{$loop_design}/{$slug}.php" ) ) {
				get_template_part( "template-parts/{$loop_design}/{$slug}" );
				return;
			}
		}

		// 3. Fallback to default template-parts/{slug}-{format}.php or template-parts/{slug}.php
		get_template_part( "template-parts/{$slug}", $format );
	}
endif;
