<?php
/**
 * WPT1000AdminAction.
 * @author Dave Ligthart <info@daveligthart.com>
 * @version 0.1
 * @package wp-top1000authors
 */
class WPT1000AdminAction extends WPT1000WPPlugin{


	function WPT1000AdminAction($plugin_name, $plugin_base){
		global $wp_version;

		$this->plugin_name = $plugin_name;
		$this->plugin_base = $plugin_base;

		/**
		 * Handle wordpress actions.
		 */
		$this->add_action('activate_'.trim($_GET['plugin']) ,'activate'); //plugin activation.
		$this->add_action('admin_head'); // header rendering.
		$this->add_action('admin_menu'); // menu rendering.

	}

	/**
	 * Render admin views.
	 * Called by admin_menu.
	 * @access private
	 */
	function renderView() {
		$sub = $this->getAction();
		$url = $this->getActionUrl();

		// Display submenu
		$this->render_admin('admin_submenu', array ('url' => $url, 'sub' => $sub));

		/**
		 * Show view.
		 */
		switch($sub){
			default:
			case 'main':
				$this->admin_start();
			break;
		}
	}

	/**
	 * Activate plugin.
	 * @access private
	 */
	function activate() {

	}

	/**
	 * Render header.
	 * @access private
	 */
	function admin_head(){
		$this->render_admin('admin_head', array('plugin_name'=>$this->plugin_name));
	}


	/**
	 * Create menu entry for admin.
	 * @return	void
	 * @access private
	 */
	function admin_menu(){
		if (function_exists('add_options_page')) {
			add_options_page(__('WP-Top1000Authors', 'wpt1000'),
			 	__('WP-Top1000Authors', 'wpt1000'),
				 10,
			 	basename ($this->dir()),
			 	array (&$this, 'renderView')
			 );
		}
	}

	/**
	 * Display the configuration settings.
	 * @access protected
	 */
	function admin_start(){
		$adminConfigAction = new WPT1000AdminConfigAction($this->plugin_name, $this->plugin_base);
		$adminConfigAction->render();
	}
}
?>