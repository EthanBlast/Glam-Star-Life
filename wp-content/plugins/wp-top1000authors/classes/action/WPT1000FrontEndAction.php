<?php
/**
 * WPT1000FrontEndAction.
 * @author Dave Ligthart <info@daveligthart.com>
 * @version 0.1
 * @package wp-top1000authors
 */
class WPT1000FrontEndAction extends WPT1000WPPlugin{

	/** @var ApacheLogParser apache log parser instance */
	var $alp = null;

	/**
	 * __construct()
	 * @param String $plugin_name
	 * @param String $plugin_base
	 */
	function WPT1000FrontEndAction($plugin_name, $plugin_base){
		$this->plugin_name = $plugin_name;
		$this->plugin_base = $plugin_base;

		$this->add_action('wp_head');
		$this->add_action('init');
		$this->add_action('wp_footer');
	}

	 /**
	  * init.
	  */
	function init() {

	}

	/**
	 * Render header.
	 * @access private
	 */
	function wp_head(){
		$this->render('header', array('plugin_name'=>$this->plugin_name));
	}

	/** Render footer */
	function wp_footer() {
		$this->render('footer', array('plugin_name'=>$this->plugin_name));
	}
}
?>