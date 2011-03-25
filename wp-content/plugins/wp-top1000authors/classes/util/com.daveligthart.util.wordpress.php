<?php
/**
 * Utils Extra WordPress Functions.
 * @author dligthart <info@daveligthart.com>
 * @version 0.1
 * @package com.daveligthart.util.wordpress
 */

if(!function_exists('dl_load_admin_block')):
/**
 * Block template loader.
 * @param string $name
 * @param array $vars
 * @access public
 */
function dl_load_admin_block($name, $vars = array()) {
	if(count($vars) > 0 &&  is_array($vars)) {
		foreach($vars as $key=>$value){
			$$key = $value;
		}
	}
	include(dirname(__FILE__) . '/../../view/admin/blocks/' . $name . '.php');
}
endif;

if(!function_exists('dl_mkdirr')):
function dl_mkdirr($pathname, $mode = 0777) { // Recursive, Hat tip: PHP.net
	// Check if directory already exists
	if ( is_dir($pathname) || empty($pathname) )
		return true;

	// Ensure a file does not already exist with the same name
	if ( is_file($pathname) )
		return false;

	// Crawl up the directory tree
	$next_pathname = substr( $pathname, 0, strrpos($pathname, DIRECTORY_SEPARATOR) );
	if ( dl_mkdirr($next_pathname, $mode) ) {
		if (!file_exists($pathname))
			return mkdir($pathname, $mode);
	}

	return false;
}
endif;
?>