<?php

if ( !function_exists('wpme_dec2sixtwo') ) {
	function wpme_dec2sixtwo( $num ) {
		$index = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$out = "";

		if ( $num < 0 ) {
			$out = '-';
			$num = abs($num);
		}

		for ( $t = floor( log10( $num ) / log10( 62 ) ); $t >= 0; $t-- ) {
			$a = floor( $num / pow( 62, $t ) );
			$out = $out . substr( $index, $a, 1 );
			$num = $num - ( $a * pow( 62, $t ) );
		}

		return $out;
	}
}

if ( ! function_exists('wpme_get_shortlink') ) :
function wpme_get_shortlink( $id = 0, $context = 'post', $allow_slugs = true ) {
	global $wp_query;

	$blog_id = stats_get_option('blog_id');

	if ( 'query' == $context ) {
		if ( is_singular() ) {
			$id = $wp_query->get_queried_object_id();
			$context = 'post';
		} elseif ( is_front_page() ) {
			$context = 'blog';
		} else {
			return '';
		}
	}

	if ( 'blog' == $context ) {
		if ( empty($id) )
			$id = $blog_id;
		return 'http://wp.me/' . wpme_dec2sixtwo($id);
	}

	$post = get_post($id);

	if ( empty($post) )
			return '';

	$post_id = $post->ID;
	$type = '';

	if ( $allow_slugs && 'publish' == $post->post_status && 'post' == $post->post_type && strlen($post->post_name) <= 8 && false === strpos($post->post_name, '%')
		&& false === strpos($post->post_name, '-') ) {
		$id = $post->post_name;
		$type = 's';
	} else {
		$id = wpme_dec2sixtwo($post_id);
		if ( 'page' == $post->post_type )
			$type = 'P';
		elseif ( 'post' == $post->post_type )
			$type = 'p';
		elseif ( 'attachment' == $post->post_type )
			$type = 'a';
	}

	if ( empty($type) )
		return '';

	return 'http://wp.me/' . $type . wpme_dec2sixtwo($blog_id) . '-' . $id;
}

function wpme_shortlink_wp_head() {
	global $wp_query;

	$shortlink = wpme_get_shortlink(0, 'query');
	echo '<link rel="shortlink" href="' . $shortlink . '" />';
}

function wpme_shortlink_header() {
	global $wp_query;

	if ( headers_sent() )
		return;

	$shortlink = wpme_get_shortlink(0, 'query');

	header('Link: <' . $shortlink . '>; rel=shortlink');
}

function wpme_get_shortlink_html($html, $post_id) {
	$url = wpme_get_shortlink($post_id);
	$html .= '<input id="shortlink" type="hidden" value="' . $url . '" /><a href="#" class="button" onclick="prompt(&#39;URL:&#39;, jQuery(\'#shortlink\').val()); return false;">' . __('Get Shortlink', 'stats') . '</a>';
	return $html;
}

function wpme_get_shortlink_handler($shortlink, $id, $context, $allow_slugs) {
	return wpme_get_shortlink($id, $context, $allow_slugs);
}

if ( stats_get_option('wp_me') ) {
	if ( ! function_exists('wp_get_shortlink') ) {
		// Register these only for WP < 3.0.
		add_action('wp_head', 'wpme_shortlink_wp_head');
		add_action('wp', 'wpme_shortlink_header');
		add_filter( 'get_sample_permalink_html', 'wpme_get_shortlink_html', 10, 2 );
	} else {
		// Register a shortlink handler for WP >= 3.0.
		add_filter('get_shortlink', 'wpme_get_shortlink_handler', 10, 4);
	}
}

endif;

