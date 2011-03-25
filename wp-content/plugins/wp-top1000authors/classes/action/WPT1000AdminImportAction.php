<?php
/**
 * WPT1000AdminImportAction.
 * @author Dave Ligthart <info@daveligthart.com>
 * @version 0.1
 * @package wp-top1000authors
 */
class WPT1000AdminImportAction extends WPT1000WPPlugin{
	/**
	 * @var
	 */
	var $adminImportForm = null;

	/**
	 * __construct()
	 */
	function WPT1000AdminImportAction($plugin_name, $plugin_base){
		$this->plugin_name = $plugin_name;
		$this->plugin_base = $plugin_base;
		$this->adminImportForm = new WPT1000AdminImportForm();
	}

	/**
	 * Render form.
	 */
	function render(){
		$this->render_admin('admin_import', array(
				'form'=>$this->adminImportForm,
				'plugin_base_url'=>$this->url(),
				'plugin_name'=>$this->plugin_name
			)
		);
	}
}