<?php
/**
 * Stories AJAX Handlers
 *
 * Handles AJAX endpoints and script localization for frontend dynamic requests.
 *
 * @package Stories
 * @subpackage Inc
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Localize AJAX script parameters.
 */
function stories_localize_ajax_script() {
	if ( file_exists( STORIES_DIR . '/assets/js/ajax.js' ) ) {
		stories_enqueue_script( 'stories-ajax', '/assets/js/ajax.js', array( 'jquery', 'stories-main' ), true );
	}
}
add_action( 'wp_enqueue_scripts', 'stories_localize_ajax_script' );

/**
 * AJAX Handler for filtering posts/stories.
 */
function stories_ajax_filter_posts() {
	// Verify AJAX nonce for security.
	check_ajax_referer( 'stories_ajax_nonce', 'nonce' );

	$post_type = isset( $_POST['post_type'] ) ? sanitize_key( $_POST['post_type'] ) : 'post';
	$paged     = isset( $_POST['page'] ) ? absint( $_POST['page'] ) : 1;

	$args = array(
		'post_type'      => $post_type,
		'posts_per_page' => 12,
		'paged'          => $paged,
		'post_status'    => 'publish',
	);

	$query = new WP_Query( $args );

	ob_start();

	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			stories_loop_template_part( get_post_format() );
		}
		wp_reset_postdata();
	} else {
		stories_loop_template_part( 'none' );
	}

	$response = array(
		'html'     => ob_get_clean(),
		'max_page' => $query->max_num_pages,
	);

	wp_send_json_success( $response );
}
add_action( 'wp_ajax_stories_filter_posts', 'stories_ajax_filter_posts' );
add_action( 'wp_ajax_nopriv_stories_filter_posts', 'stories_ajax_filter_posts' );

/**
 * AJAX Handler for toggling likes on a post.
 */
function stories_ajax_like_post() {
	$post_id = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : 0;

	if ( ! $post_id ) {
		wp_send_json_error( __( 'ID de post no válido.', 'stories' ) );
	}

	// Retrieve existing likes from _avante_likes_count or _stories_likes_count
	$likes = get_post_meta( $post_id, '_avante_likes_count', true );
	if ( '' === $likes || false === $likes ) {
		$likes = get_post_meta( $post_id, '_stories_likes_count', true );
	}
	$likes = $likes ? (int) $likes : 0;

	$cookie_stories = 'stories_liked_' . $post_id;
	$cookie_avante  = 'avante_liked_' . $post_id;
	$is_liked       = isset( $_COOKIE[ $cookie_stories ] ) || isset( $_COOKIE[ $cookie_avante ] );

	$cookie_path   = defined( 'COOKIEPATH' ) && COOKIEPATH ? COOKIEPATH : '/';
	$cookie_domain = defined( 'COOKIE_DOMAIN' ) ? COOKIE_DOMAIN : '';

	if ( $is_liked ) {
		// Unlike
		$likes = max( 0, $likes - 1 );
		setcookie( $cookie_stories, '', time() - 3600, $cookie_path, $cookie_domain );
		setcookie( $cookie_avante, '', time() - 3600, $cookie_path, $cookie_domain );
		$action = 'unliked';
	} else {
		// Like
		$likes++;
		setcookie( $cookie_stories, '1', time() + ( 86400 * 30 ), $cookie_path, $cookie_domain );
		setcookie( $cookie_avante, '1', time() + ( 86400 * 30 ), $cookie_path, $cookie_domain );
		$action = 'liked';
	}

	update_post_meta( $post_id, '_avante_likes_count', $likes );
	update_post_meta( $post_id, '_stories_likes_count', $likes );

	$icon_key = ( 'liked' === $action || $likes > 0 ) ? 'heart-fill' : 'heart';

	wp_send_json_success(
		array(
			'likes'  => $likes,
			'action' => $action,
			'icon'   => stories_get_svg( $icon_key, array( 'size' => 16 ) ),
		)
	);
}
add_action( 'wp_ajax_avante_post_like', 'stories_ajax_like_post' );
add_action( 'wp_ajax_nopriv_avante_post_like', 'stories_ajax_like_post' );
add_action( 'wp_ajax_stories_post_like', 'stories_ajax_like_post' );
add_action( 'wp_ajax_nopriv_stories_post_like', 'stories_ajax_like_post' );
add_action( 'wp_ajax_stories_like_post', 'stories_ajax_like_post' );
add_action( 'wp_ajax_nopriv_stories_like_post', 'stories_ajax_like_post' );

/**
 * AJAX Handler for loading more timeline posts dynamically.
 */
function stories_ajax_load_more_timeline() {
	$last_post_id = isset( $_POST['last_post_id'] ) ? absint( $_POST['last_post_id'] ) : 0;
	$count        = isset( $_POST['count'] ) ? absint( $_POST['count'] ) : 6;

	if ( ! $last_post_id ) {
		wp_send_json_error( array( 'message' => 'Invalid post ID' ) );
	}

	$last_post = get_post( $last_post_id );
	if ( ! $last_post ) {
		wp_send_json_error( array( 'message' => 'Post not found' ) );
	}

	// Get all published post IDs in chronological order to calculate entry numbers
	$all_post_ids = get_posts( array(
		'post_type'      => 'post',
		'posts_per_page' => -1,
		'post_status'    => 'publish',
		'orderby'        => 'date',
		'order'          => 'ASC',
		'fields'         => 'ids',
	) );
	$total_posts_count = count( $all_post_ids );

	global $post;
	$current     = $last_post;
	$items       = array();
	$new_last_id = $last_post_id;

	for ( $i = 0; $i < $count; $i++ ) {
		$post = $current;
		setup_postdata( $post );
		$p = get_previous_post();
		if ( $p ) {
			$post = $p;
			setup_postdata( $post );
			$post_url      = get_permalink( $p->ID );
			$time_label    = stories_get_timeline_date_label( $p->ID );
			$post_pos      = array_search( $p->ID, $all_post_ids, true );
			$entry_num     = ( false !== $post_pos ) ? ( $post_pos + 1 ) : 1;
			$counter_label = sprintf( __( '%1$d de %2$d', 'stories' ), $entry_num, $total_posts_count );

			ob_start();
			?>
			<div class="nav-card-item" data-id="<?php echo esc_attr( 'slide-ajax-' . $p->ID ); ?>">
				<div class="nav-card-header">
					<a href="<?php echo esc_url( $post_url ); ?>" class="nav-badge time-badge" title="<?php echo esc_attr( get_the_title( $p->ID ) ); ?>">
						<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
						<span><?php echo esc_html( $time_label ); ?></span>
					</a>
					<span class="nav-badge count-badge">
						<?php echo esc_html( $counter_label ); ?>
					</span>
				</div>
				<?php stories_loop_template_part( get_post_format( $p ) ); ?>
			</div>
			<?php
			$html = ob_get_clean();

			$items[]     = array(
				'id'   => $p->ID,
				'html' => $html,
			);
			$new_last_id = $p->ID;
			$current     = $p;
		} else {
			break;
		}
	}
	wp_reset_postdata();

	$post = get_post( $new_last_id );
	if ( $post ) {
		setup_postdata( $post );
		$has_more = (bool) get_previous_post();
		wp_reset_postdata();
	} else {
		$has_more = false;
	}

	wp_send_json_success(
		array(
			'items'        => $items,
			'last_post_id' => $new_last_id,
			'has_more'     => $has_more,
		)
	);
}
add_action( 'wp_ajax_stories_load_more_timeline', 'stories_ajax_load_more_timeline' );
add_action( 'wp_ajax_nopriv_stories_load_more_timeline', 'stories_ajax_load_more_timeline' );





