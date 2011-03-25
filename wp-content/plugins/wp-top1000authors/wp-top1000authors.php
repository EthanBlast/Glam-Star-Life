<?php
/*
Plugin Name: WP-Top1000Authors
Plugin URI: http://wordpress.org/extend/plugins/wp-top1000authors/
Description:
Version: 1.0
Author: Dave Ligthart
Author URI: http://www.daveligthart.com
*/

if(!class_exists('Services_JSON')) {
	include_once(dirname(__FILE__) . '/classes/util/Services_JSON.php');
}

if(!defined('WPT1000_CACHE_PATH')) {
	define('WPT1000_CACHE_PATH', dirname(__FILE__) . '/../../cache/');
}

if(!defined('WPT1000_PROFILE_URL')) {
	define('WPT1000_PROFILE_URL', 'http://wordpress.org/extend/plugins/profile/');
}

if (!function_exists('plugins_api')){
	include(ABSPATH . 'wp-admin/includes/plugin-install.php');
}

// Includes.
include_once(dirname(__FILE__) . '/classes/util/WPT1000Utils.php');
include_once(dirname(__FILE__) . '/classes/model/WPT1000AdminConfigForm.php');
include_once(dirname(__FILE__) . '/classes/model/WPT1000AdminImportForm.php');
include_once(dirname(__FILE__) . '/classes/util/WPT1000WPPlugin.php');
include_once(dirname(__FILE__) . '/classes/action/WPT1000AdminAction.php');
include_once(dirname(__FILE__) . '/classes/action/WPT1000AdminConfigAction.php');
include_once(dirname(__FILE__) . '/classes/action/WPT1000FrontEndAction.php');
include_once(dirname(__FILE__) . '/classes/util/com.daveligthart.php');
include_once(dirname(__FILE__) . '/classes/util/com.daveligthart.util.wordpress.php');

/**
 * WP-Top1000Authors Main.
 * @author dligthart <info@daveligthart.com>
 * @version 1.0
 */
class WPT1000Main extends WPT1000WPPlugin {

	/**
	 * @var AdminAction admin action handler
	 */
	var $adminAction = null;

	/**
	 * @var FrontEndAction frontend action handler
	 */
	var $frontEndAction = null;

	 /**
	  * __construct()
	  */
	function WPT1000Main($path) {
		$this->register_plugin('wp-top1000authors', $path);
		if (is_admin()) {
			$this->adminAction = new WPT1000AdminAction($this->plugin_name, $this->plugin_base);
		} else {
			$this->frontEndAction = new WPT1000FrontEndAction($this->plugin_name, $this->plugin_base);
	 	}
	}
}

// Start app.
$wp_storytweeting = new WPT1000Main(__FILE__);
?>