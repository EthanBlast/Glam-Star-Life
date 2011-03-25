<?php

// WP < 2.5
function stats_activity() {
	if ( did_action( 'rightnow_end' ) )
		return;

	$options = stats_get_options();

	if ( $options['blog_id'] ) {
		?>
		<h3><?php _e('WordPress.com Site Stats', 'stats'); ?></h3>
		<p><?php printf(__('Visit <a href="%s">your Global Dashboard</a> to see your site stats.', 'stats'), 'https://dashboard.wordpress.com/wp-admin/index.php?page=stats&blog=' . $options['blog_id']); ?></p>
		<?php
	}
}

/* Dashboard Stuff: WP >= 2.5 */

function stats_register_dashboard_widget() {
	if ( ( !$blog_id = stats_get_option('blog_id') ) || !stats_get_api_key() || !current_user_can( 'manage_options' ) )
		return;

	// wp_dashboard_empty: we load in the content after the page load via JS
	wp_register_sidebar_widget( 'dashboard_stats', __('Stats', 'stats'), 'wp_dashboard_empty', array(
		'width' => 'full'
	) );
	wp_register_widget_control( 'dashboard_stats', __('Stats', 'stats'), 'stats_register_dashboard_widget_control', array(), array(
		'widget_id' => 'dashboard_stats',
	) );

	add_action( 'admin_head', 'stats_dashboard_head' );
}

function stats_dashboard_widget_options() {
	$defaults = array( 'chart' => 1, 'top' => 1, 'search' => 7, 'active' => 7 );
	if ( ( !$options = get_option( 'stats_dashboard_widget' ) ) || !is_array($options) )
		$options = array();

	// Ignore obsolete option values
	$intervals = array(1, 7, 31, 90, 365);
	foreach ( array('top', 'search', 'active') as $key )
		if ( isset($options[$key]) && !in_array($options[$key], $intervals) )
			unset($options[$key]);

	return array_merge( $defaults, $options );
}

function stats_register_dashboard_widget_control() {
	$periods   = array( '1' => __('day', 'stats'), '7' => __('week', 'stats'), '31' => __('month', 'stats') );
	$intervals = array( '1' => __('the past day', 'stats'), '7' => __('the past week', 'stats'), '31' => __('the past month', 'stats'), '90' => __('the past quarter', 'stats'), '365' => __('the past year', 'stats') );
	$options = stats_dashboard_widget_options();

	$defaults = array(
		'top' => 1,
		'search' => 7,
		'active' => 7,
	);

	if ( 'post' == strtolower($_SERVER['REQUEST_METHOD']) && isset( $_POST['widget_id'] ) && 'dashboard_stats' == $_POST['widget_id'] ) {
		if ( isset($periods[$_POST['chart']]) )
			$options['chart'] = $_POST['chart'];
		foreach ( array( 'top', 'search', 'active' ) as $key ) {
			if ( isset($intervals[$_POST[$key]]) )
				$options[$key] = $_POST[$key];
			else
				$options[$key] = $defaults[$key];
		}
		update_option( 'stats_dashboard_widget', $options );
	}
?>
	<p>
		<label for="chart"><?php _e( 'Chart stats by' , 'stats'); ?></label>
		<select id="chart" name="chart">
<?php foreach ( $periods as $val => $label ) : ?>
			<option value="<?php echo $val; ?>"<?php selected( $val, $options['chart'] ); ?>><?php echo esc_html( $label ); ?></option>
<?php endforeach; ?>
		</select>.
	</p>

	<p>
		<label for="top"><?php _e( 'Show top posts over' , 'stats'); ?></label>
		<select id="top" name="top">
<?php foreach ( $intervals as $val => $label ) : ?>
			<option value="<?php echo $val; ?>"<?php selected( $val, $options['top'] ); ?>><?php echo esc_html( $label ); ?></option>
<?php endforeach; ?>
		</select>.
	</p>

	<p>
		<label for="search"><?php _e( 'Show top search terms over' , 'stats'); ?></label>
		<select id="search" name="search">
<?php foreach ( $intervals as $val => $label ) : ?>
			<option value="<?php echo $val; ?>"<?php selected( $val, $options['search'] ); ?>><?php echo esc_html( $label ); ?></option>
<?php endforeach; ?>
		</select>.
	</p>

	<p>
		<label for="active"><?php _e( 'Show most active posts over' , 'stats'); ?></label>
		<select id="active" name="active">
<?php foreach ( $intervals as $val => $label ) : ?>
			<option value="<?php echo $val; ?>"<?php selected( $val, $options['active'] ); ?>><?php echo esc_html( $label ); ?></option>
<?php endforeach; ?>
		</select>.
	</p>

<?php
}

function stats_add_dashboard_widget( $widgets ) {
	global $wp_registered_widgets;
	if ( !isset($wp_registered_widgets['dashboard_stats']) || !current_user_can( 'manage_options' ) )
		return $widgets;

	array_splice( $widgets, 2, 0, 'dashboard_stats' );
	return $widgets;
}

// Javascript and CSS for dashboard widget
function stats_dashboard_head() { ?>
<script type="text/javascript">
/* <![CDATA[ */
jQuery( function($) {
	var dashStats = $('#dashboard_stats.postbox div.inside');
	if ( dashStats.find( '.dashboard-widget-control-form' ).size() ) {
		return;
	}

	if ( !dashStats.size() ) {
		dashStats = $('#dashboard_stats div.dashboard-widget-content');
		var h = parseInt( dashStats.parent().height() ) - parseInt( dashStats.prev().height() );
		var args = 'width=' + dashStats.width() + '&height=' + h.toString();
	} else {
		var args = 'width=' + ( dashStats.prev().width() * 2 ).toString();
	}

	dashStats.not( '.dashboard-widget-control' ).load('index.php?page=stats&noheader&dashboard&' + args );
} );
/* ]]> */
</script>
<style type="text/css">
/* <![CDATA[ */
#dashboard_stats .dashboard-widget-content {
	padding-top: 25px;
}
#stats-info h4 {
	font-size: 1em;
	margin: 0 0 .3em;
}
<?php if ( version_compare( '2.7-z', $GLOBALS['wp_version'], '<=' ) ) : ?>
#dashboard_stats {
	overflow-x: hidden;
}
#dashboard_stats #stats-graph {
	margin: 0;
}
#stats-info {
	border-top: 1px solid #ccc;
}
#stats-info .stats-section {
	width: 50%;
	float: left;
}
#stats-info .stats-section-inner {
	margin: 1em 0;
}
#stats-info div#active {
	border-top: 1px solid #ccc;
}
#stats-info p {
	margin: 0 0 .25em;
	color: #999;
}
#stats-info div#top-search p {
	color: #333;
}
#stats-info p a {
	display: block;
}
<?php else : ?>
#stats-graph {
	width: 50%;
	float: left;
}
#stats-info {
	width: 49%;
	float: left;
}
#stats-info div {
	margin: 0 0 1em 30px;
}
#stats-info div#active {
	margin-bottom: 0;
}
#stats-info p {
	margin: 0;
	color: #999;
}
<?php endif; ?>
/* ]]> */
</style>
<?php
}

function stats_dashboard_widget_content() {
	$blog_id = stats_get_option('blog_id');
	if ( ( !$width  = (int) ( $_GET['width'] / 2 ) ) || $width  < 250 )
		$width  = 370;
	if ( ( !$height = (int) $_GET['height'] - 36 )   || $height < 230 )
		$height = 230;

	$_width  = $width  - 5;
	$_height = $height - ( $GLOBALS['is_winIE'] ? 16 : 5 ); // hack!

	$options = stats_dashboard_widget_options();

	$q = array(
		'noheader' => 'true',
		'proxy' => '',
		'page' => 'stats',
		'blog' => $blog_id,
		'key' => stats_get_api_key(),
		'chart' => '',
		'unit' => $options['chart'],
		'width' => $_width,
		'height' => $_height,
	);

	$url = 'https://dashboard.wordpress.com/wp-admin/index.php';

	$url = add_query_arg($q, $url);

	$get = wp_remote_get($url, array('timeout'=>300));

	if ( is_wp_error($get) || empty($get['body']) ) {
		$http = $_SERVER['HTTPS'] ? 'https' : 'http';
		$src = clean_url( "$http://dashboard.wordpress.com/wp-admin/index.php?page=estats&blog=$blog_id&noheader=true&chart&unit=$options[chart]&width=$_width&height=$_height" );
		echo "<iframe id='stats-graph' class='stats-section' frameborder='0' style='width: {$width}px; height: {$height}px; overflow: hidden' src='$src'></iframe>";
	} else {
		$body = convert_swf_urls($get['body']);
		echo $body;
	}

	$post_ids = array();

	if ( version_compare( '2.7-z', $GLOBALS['wp_version'], '<=' ) ) {
		$csv_args = array( 'top' => '&limit=8', 'active' => '&limit=5', 'search' => '&limit=5' );
		/* translators: Stats dashboard widget postviews list: "$post_title $views Views" */
		$printf = __( '%1$s %2$s Views' , 'stats');
	} else {
		$csv_args = array( 'top' => '', 'active' => '', 'search' => '' );
		/* translators: Stats dashboard widget postviews list: "$post_title, $views Views" */
		$printf = __( '%1$s, %2$s views' , 'stats');
	}

	foreach ( $top_posts = stats_get_csv( 'postviews', "days=$options[top]$csv_args[top]" ) as $post )
		$post_ids[] = $post['post_id'];
	foreach ( $active_posts = stats_get_csv( 'postviews', "days=$options[active]$csv_args[active]" ) as $post )
		$post_ids[] = $post['post_id'];

	// cache
	get_posts( array( 'include' => join( ',', array_unique($post_ids) ) ) );

	$searches = array();
	foreach ( $search_terms = stats_get_csv( 'searchterms', "days=$options[search]$csv_args[search]" ) as $search_term )
		$searches[] = esc_html($search_term['searchterm']);

?>
<div id="stats-info">
	<div id="top-posts" class='stats-section'>
		<div class="stats-section-inner">
		<h4 class="heading"><?php _e( 'Top Posts' , 'stats'); ?></h4>
		<?php foreach ( $top_posts as $post ) : if ( !get_post( $post['post_id'] ) ) continue; ?>
		<p><?php printf(
			$printf,
			'<a href="' . get_permalink( $post['post_id'] ) . '">' . get_the_title( $post['post_id'] ) . '</a>',
//			'<a href="' . $post['post_permalink'] . '">' . $post['post_title'] . '</a>',
			number_format_i18n( $post['views'] )
		); ?></p>
		<?php endforeach; ?>
		</div>
	</div>
	<div id="top-search" class='stats-section'>
		<div class="stats-section-inner">
		<h4 class="heading"><?php _e( 'Top Searches' , 'stats'); ?></h4>
		<p><?php echo join( ',&nbsp; ', $searches );?></p>
		</div>
	</div>
	<div id="active" class='stats-section'>
		<div class="stats-section-inner">
		<h4 class="heading"><?php _e( 'Most Active' , 'stats'); ?></h4>
		<?php foreach ( $active_posts as $post ) : if ( !get_post( $post['post_id'] ) ) continue; ?>
		<p><?php printf(
			$printf,
			'<a href="' . get_permalink( $post['post_id'] ) . '">' . get_the_title( $post['post_id'] ) . '</a>',
//			'<a href="' . $post['post_permalink'] . '">' . $post['post_title'] . '</a>',
			number_format_i18n( $post['views'] )
		); ?></p>
		<?php endforeach; ?>
		</div>
	</div>
</div>
<br class="clear" />
<p class="textright">
	<a class="button" href="index.php?page=stats"><?php _e( 'View All' , 'stats'); ?></a>
</p>
<?php
	exit;
}

