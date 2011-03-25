<?php
add_action('template_redirect', 'headway_triggers');
function headway_triggers() {
    if(isset($_GET['headway-css'])) {
		headway_gzip();
		header("Content-type: text/css");
	
		$expires = 60*60*24*30;
		header("Pragma: public");
		header("Cache-Control: maxage=".$expires);
		header('Expires: ' . gmdate('D, d M Y H:i:s', time()+$expires) . ' GMT');
	
		echo headway_generate('headway-css');
		
    	exit;
	} elseif(isset($_GET['headway-leafs-css'])) {
		headway_gzip();
		header("Content-type: text/css");
	
		$expires = 60*60*24*30;
		header("Pragma: public");
		header("Cache-Control: maxage=".$expires);
		header('Expires: ' . gmdate('D, d M Y H:i:s', time()+$expires) . ' GMT');
				
		echo headway_generate('leafs-css');
		
		exit;
    } elseif(isset($_GET['headway-js'])) {
		headway_gzip();
		header("content-type: application/x-javascript");
		
		$expires = 60*60*24*30;
		header("Pragma: public");
		header("Cache-Control: maxage=".$expires);
		header('Expires: ' . gmdate('D, d M Y H:i:s', time()+$expires) . ' GMT');
		
		echo headway_generate('scripts');

    	exit;
    } elseif(isset($_GET['headway-process'])){
		require_once TEMPLATEPATH.'/library/visual-editor/misc/process.php';
	
		if(!isset($_GET['die'])) exit;
	} elseif(isset($_GET['headway-visual-editor-action']) && headway_can_visually_edit()){
		require_once TEMPLATEPATH.'/library/visual-editor/form-action.php';
		
		exit;
	}
}