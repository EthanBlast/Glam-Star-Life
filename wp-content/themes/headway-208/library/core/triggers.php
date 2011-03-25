<?php
add_action('template_redirect', 'headway_triggers', 1);
function headway_triggers(){
	//If trigger isn't active, just return.
	if(!isset($_GET['headway-trigger'])) return;
	
	//Deactivate redirect so the weird 301's don't happen
	remove_action('template_redirect', 'redirect_canonical');
	add_filter('wp_redirect', 'headway_deactivate_redirect', 12);

	//Cycle through
	switch($_GET['headway-trigger']){
		case 'global-css':
			headway_setup_css_headers();
			headway_setup_cache_headers();
			
			echo headway_generate_global_css();
		break;
		
		case 'leafs-css':
			headway_setup_css_headers();
			headway_setup_cache_headers();
		
			echo headway_generate_leafs_css();
		break;
		
		case 'js':
			headway_setup_js_headers();
			headway_setup_cache_headers();
		
			echo headway_generate_scripts();
		break;
		
		case 'process':
			require_once TEMPLATEPATH.'/library/visual-editor/misc/process.php';
		break;
		
		case 'visual-editor-action':
			require_once TEMPLATEPATH.'/library/visual-editor/form-action.php';
		break;
		
		case 'thumbnail':
			require_once HEADWAYRESOURCES.'/thumbnail.php';
		break;
	}
	
	exit;
}


function headway_setup_css_headers(){
	headway_gzip();
	header("Content-type: text/css");
}


function headway_setup_js_headers(){
	headway_gzip();
	header("content-type: application/x-javascript");
}


function headway_setup_cache_headers(){
	$expires = 60*60*24*30;
	header("Pragma: public");
	header("Cache-Control: maxage=".$expires);
	header('Expires: ' . gmdate('D, d M Y H:i:s', time()+$expires) . ' GMT');
}


function headway_deactivate_redirect(){
	return false;
}