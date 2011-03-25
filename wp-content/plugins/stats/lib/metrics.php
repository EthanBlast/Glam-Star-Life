<?php

function stats_footer() {
	global $wp_the_query, $current_user;

	$options = stats_get_options();

	echo "<!--stats_footer_test-->";

	if ( !$options['footer'] )
		stats_set_option('footer', true);

	if ( empty($options['blog_id']) )
		return;

	if ( !$options['reg_users'] && !empty($current_user->ID) )
		return;

	$a['blog'] = $options['blog_id'];
	$a['v'] = 'ext';
	if ( $wp_the_query->is_single || $wp_the_query->is_page )
		$a['post'] = $wp_the_query->get_queried_object_id();
	else
		$a['post'] = '0';

	$http = $_SERVER['HTTPS'] ? 'https' : 'http';
?>
<script src="<?php echo $http; ?>://stats.wordpress.com/e-<?php echo gmdate('YW'); ?>.js" type="text/javascript"></script>
<script type="text/javascript">
st_go({<?php echo stats_array($a); ?>});
var load_cmc = function(){linktracker_init(<?php echo "{$a['blog']},{$a['post']},2"; ?>);};
if ( typeof addLoadEvent != 'undefined' ) addLoadEvent(load_cmc);
else load_cmc();
</script>
<?php
}

function stats_array($kvs) {
	$kvs = apply_filters('stats_array', $kvs);
	$kvs = array_map('addslashes', $kvs);
	foreach ( $kvs as $k => $v )
		$jskvs[] = "$k:'$v'";
	return join(',', $jskvs);
}

function stats_reports_load() {
	add_action('admin_head', 'stats_reports_head');
}

function stats_reports_head() {
?>
<style type="text/css">
	body { height: 100%; }
	#statsreport { height: 2500px; width: 100%; }
</style>
<?php
}

function stats_reports_page() {
	if ( isset( $_GET['dashboard'] ) )
		return stats_dashboard_widget_content();
	$blog_id = stats_get_option('blog_id');
	$key = stats_get_api_key();
	$day = isset( $_GET['day'] ) && preg_match( '/^\d{4}-\d{2}-\d{2}$/', $_GET['day'] ) ? $_GET['day'] : false;
	$q = array(
		'noheader' => 'true',
		'proxy' => '',
		'page' => 'stats',
		'key' => $key,
		'day' => $day,
		'blog' => $blog_id,
		'charset' => get_option('blog_charset'),
	);
	$args = array(
		'view' => array('referrers', 'postviews', 'searchterms', 'clicks', 'post', 'table'),
		'numdays' => 'int',
		'day' => 'date',
		'unit' => array(1, 7, 31),
		'summarize' => null,
		'post' => 'int',
		'width' => 'int',
		'height' => 'int',
		'data' => 'data',
	);
	foreach ( $args as $var => $vals ) {
		if ( ! isset($_GET[$var]) )
			continue;
		if ( is_array($vals) ) {
			if ( in_array($_GET[$var], $vals) )
				$q[$var] = $_GET[$var];
		} elseif ( $vals == 'int' ) {
			$q[$var] = intval($_GET[$var]);
		} elseif ( $vals == 'date' ) {
			if ( preg_match('/^\d{4}-\d{2}-\d{2}$/', $_GET[$var]) )
				$q[$var] = $_GET[$var];
		} elseif ( $vals == null ) {
			$q[$var] = '';
		} elseif ( $vals == 'data' ) {
			if ( substr($_GET[$var], 0, 9) == 'index.php' )
				$q[$var] = $_GET[$var];
		}
	}

	if ( isset( $_GET['chart'] ) ) {
		if ( preg_match('/^[a-z0-9-]+$/', $_GET['chart']) )
			$url = "https://dashboard.wordpress.com/wp-includes/charts/{$_GET['chart']}.php";
	} else {
		$url = "https://dashboard.wordpress.com/wp-admin/index.php";
	}

	$url = add_query_arg($q, $url);

	$get = wp_remote_get($url, array('timeout'=>300));

	if ( is_wp_error($get) || empty($get['body']) ) {
		$http = $_SERVER['HTTPS'] ? 'https' : 'http';
		$day = $day ? "&amp;day=$day" : '';
		echo "<iframe id='statsreport' frameborder='0' src='$http://dashboard.wordpress.com/wp-admin/index.php?page=estats&amp;blog=$blog_id&amp;noheader=true$day'></iframe>";
	} else {
		$body = convert_post_titles($get['body']);
		$body = convert_swf_urls($body);
		echo $body;
	}
	if ( isset( $_GET['noheader'] ) )
		die;
}

function convert_swf_urls($html) {
	$swf_url = trailingslashit( plugins_url( '', STATS_FILE ) ) . 'open-flash-chart.swf?data=';
	$html = preg_replace('!(<param name="movie" value="|<embed src=")(.+?)&data=!', "$1$swf_url", $html);
	return $html;
}

function convert_post_titles($html) {
	global $wpdb, $stats_posts;
	$pattern = "<span class='post-(\d+)-link'>.*?</span>";
	if ( ! preg_match_all("!$pattern!", $html, $matches) )
		return $html;
	$posts = get_posts(array(
		'include' => implode(',', $matches[1]),
		'post_type' => 'any',
		'numberposts' => -1,
	));
	foreach ( $posts as $post )
		$stats_posts[$post->ID] = $post;
	$html = preg_replace_callback("!$pattern!", 'convert_post_title', $html);
	return $html;
}

function convert_post_title($matches) {
	global $stats_posts;
	$post_id = $matches[1];
	if ( isset($stats_posts[$post_id]) )
		return '<a href="'.get_permalink($post_id).'" target="_blank">'.get_the_title($post_id).'</a>';
	return $matches[0];
}

function stats_xmlrpc_methods( $methods ) {
	$my_methods = array(
		'wpStats.get_posts' => 'stats_get_posts',
		'wpStats.get_blog' => 'stats_get_blog'
	);

	return array_merge( $methods, $my_methods );
}

function stats_get_posts( $args ) {
	list( $post_ids ) = $args;
	
	$post_ids = array_map( 'intval', (array) $post_ids );
	$r = 'include=' . join(',', $post_ids);
	$posts = get_posts( $r );
	$_posts = array();

	foreach ( $post_ids as $post_id )
		$_posts[$post_id] = stats_get_post($post_id);

	return $_posts;
}

function stats_get_blog( ) {
	$home = parse_url( get_option('home') );
	$blog = array(
		'host' => $home['host'],
		'path' => $home['path'],
		'name' => get_option('blogname'),
		'description' => get_option('blogdescription'),
		'siteurl' => get_option('siteurl'),
		'gmt_offset' => get_option('gmt_offset'),
		'version' => STATS_VERSION
	);
	return array_map('esc_html', $blog);
}

function stats_get_post( $post_id ) {
	$post = get_post( $post_id );
	if ( empty( $post ) )
		$post = get_page( $post_id );
	$_post = array(
		'id' => $post->ID,
		'permalink' => get_permalink($post->ID),
		'title' => $post->post_title,
		'type' => $post->post_type
	);
	return array_map('esc_html', $_post);
}

function stats_update_bloginfo() {
	stats_add_call(
		'wpStats.update_bloginfo',
		stats_get_api_key(),
		stats_get_option('blog_id'),
		stats_get_blog()
	);
}

function stats_update_post( $post_id ) {
	if ( !in_array( get_post_type($post_id), array('post', 'page', 'attachment') ) )
		return;

	stats_add_call(
		'wpStats.update_postinfo',
		stats_get_api_key(),
		stats_get_option('blog_id'),
		stats_get_post($post_id)
	);
}

function stats_flush_posts() {
	stats_add_call(
		'wpStats.flush_posts',
		stats_get_api_key(),
		stats_get_option('blog_id')
	);
}

function stats_check_key($api_key) {
	$options = stats_get_options();

	require_once( ABSPATH . WPINC . '/class-IXR.php' );

	$client = new IXR_Client( STATS_XMLRPC_SERVER );

	$client->query( 'wpStats.check_key', $api_key, stats_get_blog() );

	if ( $client->isError() ) {
		if ( $client->getErrorCode() == -32300 )
			$options['error'] = __('Your blog was unable to connect to WordPress.com. Please ask your host for help. (' . $client->getErrorMessage() . ')', 'stats');
		else
			$options['error'] = $client->getErrorMessage();
		stats_set_options( $options );
		return false;
	} else {
		$options['error'] = false;
	}

	$options['key_check'] = $client->getResponse();
	stats_set_options($options);

	return true;
}

function stats_get_blog_id($api_key) {
	$options = stats_get_options();

	require_once( ABSPATH . WPINC . '/class-IXR.php' );

	$client = new IXR_Client( STATS_XMLRPC_SERVER );

	extract( parse_url( get_option( 'home' ) ) );

	$path = rtrim( $path, '/' );

	if ( empty( $path ) )
		$path = '/';

	$client->query( 'wpStats.get_blog_id', $api_key, stats_get_blog() );

	if ( $client->isError() ) {
		if ( $client->getErrorCode() == -32300 )
			$options['error'] = __('Your blog was unable to connect to WordPress.com. Please ask your host for help. (' . $client->getErrorMessage() . ')', 'stats');
		else
			$options['error'] = $client->getErrorMessage();
		stats_set_options( $options );
		return false;
	} else {
		$options['error'] = false;
	}

	$response = $client->getResponse();

	$blog_id = isset($response['blog_id']) ? (int) $response['blog_id'] : false;

	$options[ 'host' ] = $host;
	$options[ 'path' ] = $path;
	$options[ 'blog_id' ] = $blog_id;

	stats_set_options( $options );

	stats_set_api_key( $api_key );

	return $blog_id;
}

function stats_get_csv( $table, $args = null ) {
	$blog_id = stats_get_option('blog_id');
	$key = stats_get_api_key();

	if ( !$blog_id || !$key )
		return array();

	$defaults = array( 'end' => false, 'days' => false, 'limit' => 3, 'post_id' => false, 'summarize' => '' );

	$args = wp_parse_args( $args, $defaults );
	$args['table'] = $table;
	$args['blog_id'] = $blog_id;
	$args['api_key'] = $key;

	$stats_csv_url = add_query_arg( $args, 'http://stats.wordpress.com/csv.php' );

	$key = md5( $stats_csv_url );

	// Get cache
	$stats_cache = get_option( 'stats_cache' );
	if ( !$stats_cache || !is_array($stats_cache) )
		$stats_cache = array();

	// Return or expire this key
	if ( isset($stats_cache[$key]) ) {
		$time = key($stats_cache[$key]);
		if ( time() - $time < 300 )
			return $stats_cache[$key][$time];
		unset( $stats_cache[$key] );
	}

	$stats_rows = array();
	do {
		if ( !$stats = stats_get_remote_csv( $stats_csv_url ) )
			break;

		$labels = array_shift( $stats );

		if ( 0 === stripos( $labels[0], 'error' ) )
			break;

		$stats_rows = array();
		for ( $s = 0; isset($stats[$s]); $s++ ) {
			$row = array();
			foreach ( $labels as $col => $label )
				$row[$label] = $stats[$s][$col];
			$stats_rows[] = $row;
		}
	} while(0);

	// Expire old keys
	foreach ( $stats_cache as $k => $cache )
		if ( !is_array($cache) || 300 < time() - key($cache) )
			unset($stats_cache[$k]);

	// Set cache
	$stats_cache[$key] = array( time() => $stats_rows );
	update_option( 'stats_cache', $stats_cache );

	return $stats_rows;
}

function stats_get_remote_csv( $url ) {
	$url = clean_url( $url, null, 'url' );

	// Yay!
	if ( ini_get('allow_url_fopen') ) {
		$fp = @fopen($url, 'r');
		if ( $fp ) {
			//stream_set_timeout($fp, $timeout); // Requires php 4.3
			$data = array();
			while ( $remote_read = fgetcsv($fp, 1000) )
				$data[] = $remote_read;
			fclose($fp);
			return $data;
		}
	}

	// Boo - we need to use wp_remote_fopen for maximium compatibility
	if ( !$csv = wp_remote_fopen( $url ) )
		return false;

	return stats_str_getcsv( $csv );
}

// rather than parsing the csv and its special cases, we create a new file and do fgetcsv on it.
function stats_str_getcsv( $csv ) {
	if ( !$temp = tmpfile() ) // tmpfile() automatically unlinks
		return false;

	$data = array();

	fwrite($temp, $csv, strlen($csv));
	fseek($temp, 0);
	while ( false !== $row = fgetcsv($temp, 1000) )
		$data[] = $row;
	fclose($temp);

	return $data;
}

if ( !function_exists('number_format_i18n') ) {
	function number_format_i18n( $number, $decimals = null ) { return number_format( $number, $decimals ); }
}

if ( !function_exists( 'esc_html' ) ):
	function esc_html( $string ) {
		return wp_specialchars( $string );
	}
endif;

function stats_load_translations() {
	load_plugin_textdomain( 'stats', null, basename( dirname( __FILE__ ) ) . '/languages' );
}

