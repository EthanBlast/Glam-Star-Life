<?php
/**
 * WPT1000AdminConfigAction.
 * @author Dave Ligthart <info@daveligthart.com>
 * @version 0.1
 * @package wp-top1000authors
 */
class WPT1000AdminConfigAction extends WPT1000WPPlugin{
	/**
	 * @var
	 */
	var $adminConfigForm = null;
	/**
	 * @var admin import form
	 */
	var $adminImportForm = null;
	/**
	 * __construct()
	 */
	function WPT1000AdminConfigAction($plugin_name, $plugin_base){
		$this->plugin_name = $plugin_name;
		$this->plugin_base = $plugin_base;
		$this->adminConfigForm = new WPT1000AdminConfigForm();
		$this->adminImportForm = new WPT1000AdminImportForm();
	}

	/**
	 * Render form.
	 */
	function render(){
		$this->render_admin('admin_config', array(
				'form'=>$this->adminConfigForm,
				'form_import'=>$this->adminImportForm,
				'plugin_base_url'=>$this->url(),
				'plugin_name'=>$this->plugin_name
			)
		);
	}
}