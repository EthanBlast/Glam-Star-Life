<?php
/**
 * Write cache.
 * @param string $output output to cache
 * @access public
 */
function wpt1000_write_cache($output = '') {

	$cache_filename = 'wordpress-top-1000-authors.html';

	$cache_dir = WPT1000_CACHE_PATH  . 'wp-top1000authors/';

	$filename =  $cache_dir . $cache_filename;

	if(!file_exists($cache_dir)){
		if(is_writable(WPT1000_CACHE_PATH)){
			mkdir($cache_dir);
		}
	}

	touch($filename);

	$f = fopen($filename, 'w' );
	fwrite($f, $output);
	fclose($f);
}
/**
 * Read cache.
 * @return cache results
 */
function wpt1000_read_cache() {

	$cache_filename = 'wordpress-top-1000-authors.html';

	$filename = WPT1000_CACHE_PATH  . 'wp-top1000authors/' . $cache_filename;

	$contents = '';

	if(file_exists($filename)) {
		if(filesize($filename) > 0) {
			$handle = fopen($filename, 'r');
			$contents = fread($handle, filesize($filename));
			fclose($handle);
		}
	}
	return $contents;
}

/**
 * Top 1000 already displayed on the same page?
 */
$wpt1000_displayed = false;

/**
 * Display top 1000 list.
 * @access public
 */
function wpt1000_display() {
	global $wpt1000_displayed;

	if(!$wpt1000_displayed){
		echo wpt1000_read_cache();
	}

	$wpt1000_displayed = true;
}

/**
 * Get my rank. x / 1000.
 * return rank
 * @access public
 */
function wpt1000_get_rank() {
	return get_option('wpt1000_my_rank');
}

/**
 * Get my download count.
 * return download count
 * @access public
 */
function wpt1000_get_downloads() {
	return get_option('wpt1000_my_download_count');
}
?>
