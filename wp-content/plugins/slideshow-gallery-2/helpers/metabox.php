<?php

class GalleryMetaboxHelper extends GalleryPlugin {

	var $name = 'Metabox';
	
	function GalleryMetaboxHelper() {
		$url = explode("&", $_SERVER['REQUEST_URI']);
		$this -> url = $url[0];
	}

	function settings_submit() {
		$this -> render('metaboxes/settings-submit', false, true, 'admin');
	}
	
	function settings_general() {
		$this -> render('metaboxes/settings-general', false, true, 'admin');
	}
	
	function settings_styles() {
		$this -> render('metaboxes/settings-styles', false, true, 'admin');
	}
}

?>