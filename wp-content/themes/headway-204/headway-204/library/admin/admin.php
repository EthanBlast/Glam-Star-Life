<?php
require_once HEADWAYADMIN.'/form-action.php';

require_once HEADWAYADMIN.'/includes/write-screen.php';
require_once HEADWAYADMIN.'/includes/form-functions.php';
require_once HEADWAYADMIN.'/includes/hooks.php';

add_action('admin_menu', 'headway_add_menus');
function headway_add_menus(){
	global $menu; 
	global $headway_skin_name; 
	
	if(defined('HEADWAYHIDEMENUS') && HEADWAYHIDEMENUS === true) return false;
	
	$menu[57] = array('', 'read', 'separator-headway', '', 'wp-menu-separator');
	
	$headway_menu = add_menu_page('Headway Configuration', 'Headway '.HEADWAYVERSION, 9, 'headway', 'headway_configuration', HEADWAYURL.'/library/admin/images/headway_16.png', 58); 
	add_action("admin_print_scripts-$headway_menu", 'headway_admin_panel_head');

	$configuration_page = add_submenu_page('headway', 'Configuration', 'Configuration', 9, 'headway', 'headway_configuration');
	add_action("admin_print_scripts-$configuration_page", 'headway_admin_panel_head');
	
	if(headway_get_option('active-skin', true, true) && headway_get_option('active-skin', true, true) != 'none'){
		$skins_options_page = add_submenu_page('headway', $headway_skin_name.' Options', $headway_skin_name.' Options', 9, 'headway-skin-options', 'headway_skin_options');
		add_action("admin_print_scripts-$skins_options_page", 'headway_admin_panel_head');
	}	
		
	if(is_main_site() || (is_multisite() && !get_site_option('disable-tools'))){
		$tools_page = add_submenu_page('headway', 'Tools', 'Tools', 9, 'headway-tools', 'headway_tools');
		add_action("admin_print_scripts-$tools_page", 'headway_admin_panel_head');
	}
	
	if(is_main_site() || (is_multisite() && !get_site_option('disable-easy-hooks'))){
		$hooks_page = add_submenu_page('headway', 'Easy Hooks', 'Easy Hooks', 9, 'headway-easy-hooks', 'headway_easy_hooks');
		add_action("admin_print_scripts-$hooks_page", 'headway_admin_panel_head');
	}

	$visual_editor_page = add_submenu_page('headway', 'Visual Editor', 'Visual Editor', 9, 'headway-visual-editor', 'headway_visual_editor_forward' );
	add_action("admin_print_scripts-$visual_editor_page", 'headway_admin_panel_head');
}


function headway_admin_panel_head(){	
	echo '<link rel="stylesheet" href="'.get_bloginfo('template_directory').'/library/admin/css/admin-tabs.css" type="text/css" media="all" />'."\n";
	
	wp_enqueue_script('headway_admin_js', get_bloginfo('template_directory').'/library/admin/js/admin.js', array('jquery', 'jquery-ui-draggable', 'jquery-ui-tabs', 'jquery-ui-droppable'));
}


add_action('admin_init', 'headway_admin_global');
function headway_admin_global(){
	wp_enqueue_style('headway_admin_global', get_bloginfo('template_directory').'/library/admin/css/admin-global.css');
	wp_enqueue_script('headway_admin_global_js', get_bloginfo('template_directory').'/library/admin/js/admin-global.js', array('jquery'));
	
	if(isset($_GET['dismiss-headway-nag'])){
		switch($_GET['dismiss-headway-nag']){
			case 'headway-folder-nag':
				headway_update_option('ignore-perms-warning', true);
			break;

			case 'cache-folder-nag':
				headway_update_option('ignore-cache-warning', true);
			break;
		}
		
		$current_url = str_replace(array('dismiss-headway-nag=', 'headway-folder-nag', 'cache-folder-nag'), '', $_SERVER['REQUEST_URI']);
		
		header('Location: '.$current_url);
	}
}


function headway_admin_header($title = false){
	global $headway_force_queries;
	$headway_force_queries = true;
	
	echo '<div id="wrapper" class="wrap headway-page">';
	echo '<div id="headway-admin-top">
			<a target="_blank" href="http://www.headwaythemes.com/members">Headway Members Area</a>
			<a target="_blank" href="http://www.headwaythemes.com/documentation">Headway Documentation</a>
			<a target="_blank" href="http://support.headwaythemes.com">Headway Support Forums</a>
		 </div><div class="icon32" id="icon-headway"><br />'."\n".'</div>';
		
	if($title) echo '<h2>'.$title.'</h2>';
}


function headway_admin_footer(){
	echo '</div><!-- #wrapper -->';
}


function headway_configuration(){
	headway_admin_header('Headway Configuration');
		require_once HEADWAYADMIN.'/configuration.php';	
	headway_admin_footer();
}


function headway_tools(){
	headway_admin_header('Headway Tools');
		if(isset($_GET['activate-theme'])){
			require_once HEADWAYADMIN.'/activate-theme.php';
    	} else {
			require_once HEADWAYADMIN.'/tools.php';	
		}
	headway_admin_footer();
}


function headway_skin_options(){
	global $headway_skin_name;
	
	headway_admin_header($headway_skin_name.' Options');
		require_once HEADWAYADMIN.'/skin-options.php';	
	headway_admin_footer();
}


function headway_easy_hooks(){
	headway_admin_header('Headway Easy Hooks');
		require_once HEADWAYADMIN.'/easy-hooks.php';
	headway_admin_footer();
}


function headway_visual_editor_forward(){
	headway_admin_header('Headway Visual Editor');
		echo 'You are now being redirected.  If you are not redirected within 5 seconds, click <a href="'.get_bloginfo('url').'/?visual-editor=true"><strong>here</strong></a>.';
		echo '<meta http-equiv="refresh" content="0;URL='.get_bloginfo('url').'/?visual-editor=true">';
	headway_admin_footer();
}


add_action('admin_notices', 'headway_update_notice');
function headway_update_notice(){
	if(!current_user_can('manage_options')) return false;
	
	if(headway_check_for_updates()){
		$latest_version = headway_latest_version();
		
		echo '<div id="update-nag">Headway '.headway_latest_version_nice().' is available, you\'re running '.headway_current_version().'! &nbsp;<a href="'.get_bloginfo('wpurl').'/wp-admin/admin.php?page=headway-tools">Click here to update</a> or head on over to the Headway site to <a href="http://headwaythemes.com/members" target="_blank">download</a> the latest version!</div>';
	}
	
	$current_url = (strpos($_SERVER['REQUEST_URI'], '?') !== false) ? $_SERVER['REQUEST_URI'].'&' : $_SERVER['REQUEST_URI'].'?';
	
	if(substr(decoct(fileperms(TEMPLATEPATH)), 1) == '0775' && !headway_get_option('ignore-headway-perms-warning')){
		echo '<div class="updated fade" id="message"><p  style="line-height: 1.4"><strong>Warning!</strong> For security reasons and to avoid errors, your Headway folder must not have permissions of 775.  <a href="'.$current_url.'dismiss-headway-nag=headway-folder-nag" class="button" style="float:right;margin:-3px 0 0 0;">Dismiss Warning</a></p></div>';
	}
	
	if(file_exists(HEADWAYCACHE) && !is_writable(HEADWAYCACHE) && !is_writable(HEADWAYCACHE.'/headway.css') && !headway_get_option('ignore-cache-warning')){
		echo '<div class="updated fade" id="message"><p  style="line-height: 1.4"><strong>Warning!</strong> The Headway cache folder (wp-content/themes/'.get_option('template').'/media/cache) and all sub-files should be writable.  <a href="'.$current_url.'dismiss-headway-nag=cache-folder-nag" class="button" style="float:right;margin:-3px 0 0 0;">Dismiss Warning</a></p></div>';
	}	
	
	//If on the theme editor, display a nag.
	if(strpos(headway_current_url(), 'wp-admin/theme-editor.php') !== false){
		echo '<div class="error fade" id="message"><p  style="line-height: 1.4"><strong>Warning!</strong> Do <strong>NOT</strong> modify any core Headway files (anything but custom.css and the custom folder is a core file).  If you have any questions or if a plugin instructs you to modify a file, please contact <a href="mailto:support@headwaythemes.com">support@headwaythemes.com</a>.</p></div>';
	}
}