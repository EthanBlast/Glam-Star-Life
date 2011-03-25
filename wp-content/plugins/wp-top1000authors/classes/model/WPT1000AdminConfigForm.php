<?php
/**
 * WPT1000AdminConfigForm model object.
 * @author Dave Ligthart <info@daveligthart.com>
 * @version 0.1
 * @package wp-top1000authors
 */
include_once('WPT1000BaseForm.php');
class WPT1000AdminConfigForm extends WPT1000BaseForm{

	var $wpt1000_profile_uri = '';
	var $wpt1000_profile_name;

	function WPT1000AdminConfigForm(){
		parent::WPT1000BaseForm();
		if($this->setFormValues()){

			$this->saveOptions();
		}
		$this->loadOptions();
	}

	function getProfileName() {
		return $this->wpt1000_profile_name;
	}

	function getProfileUri() {
		return WPT1000_PROFILE_URL . $this->getProfileName();
	}
}
?>