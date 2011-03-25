<?php
/**
 * Miscellaneous functions for Headway.
 *
 * @package Headway
 * @subpackage Core Functions
 * @author Clay Griffiths
 **/

if(function_exists('add_theme_support')){
	add_theme_support('post-thumbnails');
	add_theme_support('menus');
}


/**
 * Simple function to return the feed URL.
 *
 * @return string If there's a Headway custom feed URL, then return it.  Otherwise return the WordPress rss2_url option.
 **/
function headway_rss(){
	if(headway_get_option('feed-url')) return headway_get_option('feed-url');
	return get_bloginfo('rss2_url');
}


/**
 * Starts the GZIP output buffer.
 *
 * @return void
 **/
function headway_gzip(){
	if(headway_get_option('gzip') == 1 && !headway_is_plugin_caching() && !class_exists('All_in_One_SEO_Pack')) if ( extension_loaded('zlib') ) ob_start('ob_gzhandler');
}


/**
 * Detects if the browser is Internet Explorer.  Will also check if a specific version of MSIE.
 * 
 * @param int $version
 *
 * @return bool
 **/
function is_ie($version = false){
	if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') && !$version) return true;
	if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 6.0') && $version == 6) return true;
	if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 7.0') && $version == 7) return true;
	if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 8.0') && $version == 8) return true;

	
	return false;
}


/**
 * Parses PHP using eval.
 *
 * @param string $content PHP to be parsed.
 * 
 * @return mixed PHP that has been parsed.
 **/
function headway_parse_php($content){
	ob_start();
	eval("?>$content<?php ");
	$parsed = ob_get_contents();
	ob_end_clean();
	return $parsed;
}


/**
 * Builds the current URL.
 *
 * @return string
 **/
function headway_current_url(){
	$url = 'http';
	if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') $url .= "s";
	$url .= "://";
	
	if ($_SERVER["SERVER_PORT"] != "80") {
		$url .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	} else {
		$url .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	}
	
	return $url;
}


/**
 * Retrieves the Headway directory.
 * 
 * @param bool $print
 * @param bool $absolute
 *
 * @return void|string If $print, then echo, otherwise return the path.
 **/
function headway_directory($print = true, $absolute = false){
	if($print):
		echo ($absolute) ? TEMPLATEPATH : get_bloginfo('template_directory');
	else:
		return ($absolute) ? TEMPLATEPATH : get_bloginfo('template_directory');
	endif;
}


/**
 * Returns the current page.  Will also test if system page.
 *
 * @uses headway_get_write_box_value()
 * @uses headway_get_option()
 * 
 * @global object $post
 * 
 * @param bool $get_real_page If true, ignore the leaf template and return the real page that is being viewed.
 * 
 * @return bool|string If $system_page_test, return a boolean value, otherwise return a string.
 **/
function headway_current_page($get_real_page = false){
		
	if(!isset($_GET['visual-editor-system-page']) || (isset($_GET['visual-editor-system-page']) && is_numeric($_GET['visual-editor-system-page']))){
				
		if(is_home()) $system_page = 'index';
		
		if(get_option('show_on_front') == 'posts' && (is_front_page()))
			$system_page = 'index';
		elseif(is_front_page())
			$current_page = get_option('page_on_front');
		
		is_single()    		? $system_page = 'single'	  : NULL;
		is_date()   		? $system_page = 'archives'  : NULL;
		is_tag()   			? $system_page = 'tag' 	  : NULL;
		is_category()  		? $system_page = 'category'  : NULL;
		is_author()    		? $system_page = 'author' 	  : NULL;
		is_search()    		? $system_page = 'search' 	  : NULL;
		is_404()    		? $system_page = 'four04'    : NULL;
	
		is_attachment()    	? $system_page = 'single'    : NULL;

		if(get_post_type() !== 'post' && get_post_type() !== 'page' && is_singular()){
			$system_page = 'custom-single-'.get_post_type();
		}
		
		if(isset($system_page)){
			$current_page = $system_page;
						
			if(!$get_real_page){
				if(headway_get_option('leaf-template-system-page-'.$system_page)) 
					$current_page = headway_get_option('leaf-template-system-page-'.$system_page);
				elseif(headway_get_option('leaf-template-page-'.$system_page)) 
					$current_page = headway_get_option('leaf-template-page-'.$system_page);
			}
		} else {
			global $wp_query;
			$current_page = $wp_query->post->ID;
			
			if(!$get_real_page){
				if(headway_get_write_box_value('leaf_template', false, $current_page))
					$current_page = headway_get_write_box_value('leaf_template', false, $current_page);
				elseif(headway_get_write_box_value('leaf_system_template', false, $current_page))
					$current_page = headway_get_write_box_value('leaf_system_template', false, $current_page);
			}
		}
	
		return $current_page;
		
	} else {
		
		if(!$get_real_page){
			if(headway_get_option('leaf-template-page-'.$_GET['visual-editor-system-page'])){
				return headway_get_option('leaf-template-page-'.$_GET['visual-editor-system-page']);
			} elseif(headway_get_option('leaf-template-system-page-'.$_GET['visual-editor-system-page'])){
				return headway_get_option('leaf-template-system-page-'.$_GET['visual-editor-system-page']);
			} else {
				return $_GET['visual-editor-system-page'];
			}
		} else {
			return $_GET['visual-editor-system-page'];
		}
		
	}
}


function headway_is_system_page($exceptions = false, $check_real = false){
	$page = $check_real ? headway_current_page(true) : headway_current_page();
	
	if(is_numeric($page) || (is_array($exceptions) && in_array($page, $exceptions)))
		return false;
	else
		return true;
}


function headway_nice_page_name($page_id){
	if(is_numeric($page_id)){
		return strip_tags(get_the_title($page_id));
	} else {
		
		switch($page_id){
			default:
				if(strpos($page_id, 'custom-single-') === false) return false;
								
				$type = str_replace('custom-single-', '', $page_id);
				
				$query = get_post_types(array('name' => $type), 'objects');
				
				foreach($query as $post_type){
					return 'Post Type &ndash; '.$post_type->labels->singular_name.' &ndash; Single';
				}
			break;
			
			case 'index':
				return 'Blog Index';
			break;
			
			case 'single':
				return 'Single Post Template';
			break;
			
			case 'archives':
				return 'Archives';
			break;
			
			case 'tag':
				return 'Tag Archives';
			break;
			
			case 'category':
				return 'Category Archives';
			break;
			
			case 'author':
				return 'Author Archives';
			break;
			
			case 'search':
				return 'Search Results';
			break;
			
			case 'four04':
				return '404 Page';
			break;
		}
		
	}
}


/**
 * Displays an admin link or admin login.
 * 
 * @uses headway_get_option()
 *
 * @return void
 **/
function headway_login(){
	if(headway_get_option('show-admin-link')){
		global $user_level;
		get_currentuserinfo();
		
		if(is_user_logged_in()){
		    echo '<a href="'.get_bloginfo('wpurl').'/wp-admin" class="footer-right" id="footer-admin-link">'.__('Administration Panel', 'headway').'</a>';
		} else {
		    echo '<a href="'.get_bloginfo('wpurl').'/wp-admin" class="footer-right" id="footer-admin-link">'.__('Administration Login', 'headway').'</a>';
		}
	}
}


/**
 * Shows an edit link.
 * 
 * @global int $user_level
 *
 * @return void
 **/
function headway_edit(){
	global $user_level;
	get_currentuserinfo();
	
	if(is_page() && !is_front_page()) edit_post_link('Edit This Page', '<span class="edit-link footer-right" id="footer-edit-link">', '</span>');
	if(is_single()) edit_post_link('Edit This Post', '<span class="edit-link footer-right" id="footer-edit-link">', '</span>');
}


/**
 * Echos the Powered By Headway link.
 * 
 * @uses headway_get_option()
 *
 * @param string $text The name of the program to be displayed.  Defaults to Headway (obviously).
 * 
 * @return void
 **/
function headway_link(){
	if(strstr(html_entity_decode(headway_get_option('affiliate-link')), 'shareasale.com')){
		$location = headway_get_option('affiliate-link');
	}
	else
	{
		$location = 'http://www.headwaythemes.com/';	
	}
	$return = apply_filters('headway_link', '<p class="footer-left" id="footer-headway-link">'.__('Powered By', 'headway').' <a href="'.$location.'" title="Headway Premium WordPress Theme">Headway</a></p>');
	
	echo $return;
}


/**
 * Echos a simple copyright paragraph.
 *
 * @return void
 **/
function headway_copyright(){
	$copyright = (headway_get_option('custom-copyright') && !isset($_GET['safe-mode'])) ? stripslashes(headway_get_option('custom-copyright')) : __('Copyright', 'headway').' &copy; '.date('Y').' '.get_bloginfo('name');
	
	$return = apply_filters('headway_copyright', '<p class="copyright" id="footer-copyright">'.$copyright.'</p>');
	
	echo $return;
}


/**
 * Echos a simple go to top link.
 *
 * @return void
 **/
function headway_go_to_top(){
	$return = apply_filters('headway_go_to_top', '<a href="#top" class="footer-right" id="footer-go-to-top-link">'.__('Go To Top', 'headway').'</a>');
	
	echo $return;
}


/**
 * Checks a value to see if it's equal to on.  If so, return checked.
 *
 * @param string $value The value to be checked.
 * 
 * @see headway_checkbox_value_custom()
 * 
 * @return string|void
 **/
function headway_checkbox_value($value){
	return ($value == 'on' || $value == '1') ? ' checked' : NULL;
}


/**
 * If the value of the checkbox is different than on, then use this function instead of headway_checkbox_value()
 * 
 * @param string $variable The desired value.
 * @param string $value Value to be checked.
 * 
 * @see headway_checkbox_value()
 *
 * @return string|void
 **/
function headway_checkbox_value_custom($variable, $value){
	return ($variable == $value) ? ' checked' : NULL;
}


/**
 * Checks two variables and if they're the same, return selected.
 * 
 * @param string $variable The desired value.
 * @param string $value Value to be checked.
 * 
 * @see headway_checkbox_value_custom()
 * @see headway_radio_value()
 *
 * @return string|void
 **/
function headway_option_value($variable, $value){
	return ($variable == $value) ? ' selected' : NULL;
}


/**
 * Checks two variables and if they're the same, return checked.
 * 
 * @param string $variable The desired value.
 * @param string $value Value to be checked.
 * 
 * @see headway_checkbox_value()
 * @see headway_option_value()
 *
 * @return string|void
 **/
function headway_radio_value($variable, $value){
	return ($variable == $value) ? ' checked' : NULL;
}


/**
 * Creates the upload folders that Headway uses.
 *
 * @return void
 **/
function headway_create_uploads_folders(){
	$upload_path = wp_upload_dir();
	
	if(!is_dir($upload_path['basedir'])){
		@mkdir($upload_path['basedir']);
		@chmod($upload_path['basedir'], 0777);
	}
	if(!is_dir($upload_path['basedir'].'/headway')){
		@mkdir($upload_path['basedir'].'/headway');
		@chmod($upload_path['basedir'].'/headway', 0777);
	}
	if(!is_dir($upload_path['basedir'].'/headway/header-uploads')){
		@mkdir($upload_path['basedir'].'/headway/header-uploads');
		@chmod($upload_path['basedir'].'/headway/header-uploads', 0777);
	}
	if(!is_dir($upload_path['basedir'].'/headway/background-uploads')){
		@mkdir($upload_path['basedir'].'/headway/background-uploads');
		@chmod($upload_path['basedir'].'/headway/background-uploads', 0777);
	}
	if(!is_dir($upload_path['basedir'].'/headway/gallery')){
		@mkdir($upload_path['basedir'].'/headway/gallery');
		@chmod($upload_path['basedir'].'/headway/gallery', 0777);
	}
	
	return;
}


/**
 * Generates the Headway upload path.
 *
 * @return string The upload path.
 **/
function headway_upload_path($absolute = false, $url = false){	
	$upload_dir = wp_upload_dir();
	
	if($absolute){
		return $upload_dir['basedir'].'/headway';
	} else {		
		return str_replace($_SERVER['DOCUMENT_ROOT'], '', $upload_dir['basedir'].'/headway');
	}
}


/**
 * Generates the Headway upload path as a URL.
 *
 * @return string The upload path URL.
 **/
function headway_upload_url(){	
	$upload_dir = wp_upload_dir();
	
	return $upload_dir['baseurl'].'/headway';
}


/**
 * Returns the relative path for Headway's theme directory.  This is everything after the .com (or whatever ending) of the site.
 *
 * @return string
 **/
function headway_relative_path(){
	 $site_url = 'http';
	 if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') $page_url .= 's';
	 $site_url .= "://";
	
	 if ($_SERVER["SERVER_PORT"] != "80") {
	  $site_url .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"];
	 } else {
	  $site_url .= $_SERVER["SERVER_NAME"];
	 }
	
	$theme_path = get_bloginfo('template_directory');
	
	$relative_path = str_replace($site_url, '', $theme_path);
		
	$path_to_theme = (strpos($relative_path, '/') == 0) ? substr($relative_path, 1) : $relative_path;
	
	return $path_to_theme;
}


/**
 * Similar to headway_relative_path(), but instead returns the relative path to only the website.  This will most likely be a slash.
 * 
 * @see headway_relative_path()
 *
 * @return string
 **/
function headway_relative_site_path(){
	$site_url = 'http';
	 if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') $page_url .= 's';
	 $site_url .= "://";
	
	 if ($_SERVER["SERVER_PORT"] != "80") {
	  $site_url .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"];
	 } else {
	  $site_url .= $_SERVER["SERVER_NAME"];
	 }
	
	$relative_site_path = substr(str_replace($site_url, '', get_bloginfo('template_directory')), 1);
	
	$themes = strpos($relative_site_path, '/wp-content/');
	$relative_site_path = substr($relative_site_path, 0, $themes);
	
	return $relative_site_path;
}


/**
 * Fetches the WordPress user's level.
 *
 * @return int
 **/
function headway_user_level(){
	global $user_level;
	get_currentuserinfo();
	return $user_level;
}


/**
 * Allows version numbers to be compared.  Turns version numbers into integers.  The first number is multiplied by 1000, the second is multiplied by 100, and the last is added to the previous sum. 
 *
 * @param mixed Version number to be changed to integer.
 * 
 * @return int
 **/
function headway_versionify($version){
	if($version){
		$version = explode('.', $version);
		$version[1] = isset($version[1]) ? $version[1] : 0;
		$version[2] = isset($version[2]) ? $version[2] : 0;
		
		$versionified_version = $version[0]*1000 + $version[1]*100 + $version[2];
	
		return $versionified_version;
	} else {
		return false;
	}
}


function headway_current_version(){
	if(defined('HEADWAYBETAVERSION')){
		return HEADWAYVERSION.' Beta '.HEADWAYBETAVERSION;
	} else {
		return HEADWAYVERSION;
	}
}


function headway_latest_version($english = false){
	global $latest_version;
	
	if($latest_version) return $latest_version;
	
	//Fetch regular release from Headway
	$latest_version_get = wp_remote_get('http://headwaythemes.com/upgrade/latest-release.txt', array('timeout' => 2));
	
	if(is_wp_error($latest_version_get)) return false;
	
	$latest_version = array($latest_version_get['body'], 'final', false);
	
	//Check against 404s
	if(wp_remote_retrieve_response_code($latest_version_get) !== 200)
		return false;
	
	//If the running version isn't a beta, go ahead and return the regular version		
	if(!defined('HEADWAYBETAVERSION')) return $latest_version;
	
	//Fetch beta from Headway
	$latest_beta_version_get = wp_remote_get('http://headwaythemes.com/upgrade/latest-beta.txt', array('timeout' => 2));
	
	if(is_wp_error($latest_beta_version_get) || is_wp_error($latest_version_get)) return false;
		
	$latest_beta_version = $latest_beta_version_get['body'];
	
	$beta_version_array = explode('b', $latest_beta_version);
	
	//Check to make sure the beta is newer than the release
	if(headway_versionify($beta_version_array[0]) > headway_versionify($latest_version[0])){	
		$latest_beta_version = array($beta_version_array[0], 'beta', (int)$beta_version_array[1]);
		
		$latest_version = $latest_beta_version;
	}
	
	//Check against 404s
	if(wp_remote_retrieve_response_code($latest_version_get) !== 200 || wp_remote_retrieve_response_code($latest_beta_version_get) !== 200)
		return false;
	
	return $latest_version;
}


function headway_latest_version_nice(){
	global $latest_version;
	
	if(!$latest_version)
		$latest_version = headway_latest_version();
	
	if($latest_version[1] == 'final'){
		return $latest_version[0];
	} elseif($latest_version[1] == 'beta'){
		return $latest_version[0].' Beta '.$latest_version[2];
	}
}


function headway_check_for_updates(){
	$latest_version = headway_latest_version();
	
	//If latest version is regular release and simply newer
	if($latest_version[1] == 'final' && headway_versionify($latest_version[0]) > headway_versionify(HEADWAYVERSION)){
		return 'final';
	} else {
		//If the latest version online is a release that's the same major release as the beta
		if(defined('HEADWAYBETAVERSION') && headway_versionify($latest_version[0]) === headway_versionify(HEADWAYVERSION) && $latest_version[1] == 'final')
			return 'final';
		
		//If the type isn't beta or final, stop
		if($latest_version[1] != 'beta') 
			return false;
		
		//If it's the same major release, but newer beta
		if(headway_versionify($latest_version[0]) === headway_versionify(HEADWAYVERSION) && headway_versionify($latest_version[2]) > headway_versionify(HEADWAYBETAVERSION))
			return 'beta';
			
		//If it's a different major beta release
		if(headway_versionify($latest_version[0]) > headway_versionify(HEADWAYVERSION))
			return 'beta';
		
		return false;
	}
}

/**
 * Converts an array into a JSON string.
 * 
 * @param array $array Array to be converted.
 *
 * @return string JSON string.
 **/
function headway_json_encode($array){
	if(function_exists('json_encode')){
		return json_encode($array);
	} else {
		require_once HEADWAYLIBRARY.'/resources/json.php';
		$json = new Services_JSON(SERVICES_JSON_LOOSE_TYPE);
		return $json->encode($array);
	}
}


/**
 * Converts a JSON string back to an array.
 * 
 * @param string $string JSON to be converted.
 *
 * @return array
 **/
function headway_json_decode($string, $obj_to_arr = false){
	if(function_exists('json_decode')){
		$return = json_decode($string, true);
	} else {
		require_once HEADWAYLIBRARY.'/resources/json.php';
		$json = new Services_JSON(SERVICES_JSON_LOOSE_TYPE);
		
		$return = $json->decode($string);
	}
	
	if($obj_to_arr){
		return headway_object_to_array($return);
	} else {
		return $return;
	}
}


/**
 * Merges arrays.
 *
 * @param array
 * 
 * @return array
 **/
function headway_json_array_fix($arrays){
	if($arrays):
		$fixArray = array();
		foreach($arrays as $array){
			if(!array_key_exists($array['name'], $fixArray)):     //Adds Support For Multi-selects, etc.
				$fixArray[$array['name']] = $array['value'];
			elseif($array['name'] == 'categories'):
				$fixArray[$array['name']] .= ' | '.$array['value'];
			endif;
			
		}
		return $fixArray;
	endif;
}


/**
 * Converts Object to Array
 *
 * @param obj
 * 
 * @return array
 **/
function headway_object_to_array($obj){
    if(!is_object($obj) && !is_array($obj)){
        return $obj;
    } elseif(is_object($obj)){
        $obj = get_object_vars($obj);
    }

    return array_map('headway_object_to_array', $obj);
}


/**
 * Checks if the user can access the visual editor.
 * 
 * @uses headway_user_level()
 * @uses headway_get_option()
 *
 * @return bool
 **/
function headway_can_visually_edit(){
	if(current_user_can('manage_options') || is_super_admin()){
		return true;
	} else {
		return false;
	}
}


/**
 * Checks if the visual editor is open.
 *
 * @uses headway_can_visually_edit
 * 
 * @return bool
 **/
function headway_visual_editor_open(){
	if(isset($_GET['visual-editor']) && headway_can_visually_edit()){
		return true;
	} else {
		return false;
	}
}

/**
 * Checks if header image exists and will be loaded.
 *
 * @return bool
 **/
 function headway_use_header_image(){
	if(!headway_get_skin_option('disable-header-image') && headway_get_option('header-image') && headway_get_option('header-image') != 'DELETE'){
		return true;
	} else {
		return false;
	}
}


/**
 * Checks if header image is local.
 *
 * @return bool
 **/
 function headway_is_header_image_local(){
	if(strpos(strtolower(headway_get_option('header-image')), 'http') === false){
		return true;
	} else {
		return false;
	}
}


/**
 * Generates the URL for the image resizer.
 * 
 * @param string $url URL to original image.
 * @param int $w Width to resize to.
 * @param int $h Height to resize to.
 * @param int $zc Determines whether or not to zoom/crop the image.
 *
 * @return string The URL to the image.
 **/
function headway_thumbnail($url, $w = false, $h = false, $zc = 1){
	if($zc) $zc = 1;
	
	if($w && $h){
		return get_bloginfo('template_directory').'/library/resources/timthumb/thumbnail.php?src='.urlencode($url).'&amp;q=90&amp;w='.$w.'&amp;h='.$h.'&amp;zc='.$zc;
	}
	elseif($w && !$h){
		return get_bloginfo('template_directory').'/library/resources/timthumb/thumbnail.php?src='.urlencode($url).'&amp;q=90&amp;w='.$w.'&amp;zc='.$zc;
	}
	elseif(!$w && $h){
		return get_bloginfo('template_directory').'/library/resources/timthumb/thumbnail.php?src='.urlencode($url).'&amp;q=90&amp;h='.$h.'&amp;zc='.$zc;
	}
}


/**
 * Check if W3 Total Cache or if WP Super Cache are running.
 *
 * @return bool
 **/
function headway_is_plugin_caching(){
	if(class_exists('W3_Plugin_TotalCache')){
		return true;
	} elseif(function_exists('wp_cache_manager')){
		return true;
	} else {
		return false;
	}
}


/**
 * Removes anything that's not a letter or number.  To be used as a callback function.
 *
 * @param mixed Piece of array to be filtered.
 * 
 * @return mixed
 **/
 function headway_filter_array_piece($piece){
	return preg_replace("/[^a-zA-Z0-9]/", '', $piece);
}


function headway_nav_menu_check(){
	if(function_exists('wp_get_nav_menus') && count(wp_get_nav_menus()) > 0)
		return true;
	else
		return false;
}


function headway_cache_exists($what = 'headway.css'){
	if(is_writable(HEADWAYCACHE.'/'.$what) && filesize(HEADWAYCACHE.'/'.$what) > 0)
		return true;
	else
		return false;
}

function headway_generate_cache($what = false){
	if(!$what){
		$what = array('headway', 'leafs', 'scripts');
	}
		
	if(!headway_allow_caching()) return false;	
	
	if(!is_dir(TEMPLATEPATH.'/media/cache/sites')) @mkdir(TEMPLATEPATH.'/media/cache/sites');
	if(!is_dir(HEADWAYCACHE)) @mkdir(HEADWAYCACHE);
	
	if(in_array('headway', $what)){
		//Fetch CSS from DB
		$headway_css = headway_generate('headway-css');	
	
		//If CSS contains warning, do not cache.
		if(strpos($headway_css, '<b>Warning</b>:') !== false)
			return false;
						
		$headway_css_handle = @fopen(HEADWAYCACHE.'/headway.css', 'w');
		@fwrite($headway_css_handle, $headway_css);
		@fclose($headway_css_handle);
	}
	
	
	if(in_array('leafs', $what)){
		$leafs_css = headway_generate('leafs-css');
		$leafs_css_handle = @fopen(HEADWAYCACHE.'/leafs.css', 'w');
				
		@fwrite($leafs_css_handle, $leafs_css);
		@fclose($leafs_css_handle);
	}
	
	
	if(in_array('scripts', $what)){
		$scripts_js = headway_generate('scripts');
		$scripts_handle = @fopen(HEADWAYCACHE.'/scripts.js', 'w');
				
		@fwrite($scripts_handle, $scripts_js);
		@fclose($scripts_handle);
	}
	
	headway_clear_plugin_caches();
	
	headway_update_option('css-last-updated', mktime());
}


/**
 * Clears the cache.
 **/
function headway_clear_cache($what = array()){	
	if(count($what) === 0){
		$what = array('headway', 'leafs', 'scripts');
	}
	
	if(in_array('headway', $what)){
		@unlink(HEADWAYCACHE.'/headway.css');
		$headway_css_handle = @fopen(HEADWAYCACHE.'/headway.css', 'w');
		@fclose($headway_css_handle);
	}
	
	if(in_array('leafs', $what)){
		@unlink(HEADWAYCACHE.'/leafs.css');
		$leafs_css_handle = @fopen(HEADWAYCACHE.'/leafs.css', 'w');
		@fclose($leafs_css_handle);
	}
	
	if(in_array('scripts', $what)){
		@unlink(HEADWAYCACHE.'/scripts.js');
		$scripts_handle = @fopen(HEADWAYCACHE.'/scripts.js', 'w');
		@fclose($scripts_handle);
	}
	
	headway_clear_plugin_caches();
}


/**
 * Clear Super Cache and W3 Total Cache
 **/
function headway_clear_plugin_caches(){
	if(function_exists('prune_super_cache')){
		global $cache_path;
		prune_super_cache($cache_path . 'supercache/', true );
		prune_super_cache($cache_path, true );
	}

	if(class_exists('w3_plugin_totalcache')){
		global $w3_plugin_totalcache;
	
		if(function_exists(array('w3_plugin_totalcache', 'flush_memcached'))) $w3_plugin_totalcache->flush_memcached();
	    if(function_exists(array('w3_plugin_totalcache', 'flush_opcode'))) $w3_plugin_totalcache->flush_opcode();
	    if(function_exists(array('w3_plugin_totalcache', 'flush_file'))) $w3_plugin_totalcache->flush_file();
	}
}


function headway_allow_caching(){
	//If WP_DEBUG is true, don't allow caching
	if(defined('WP_DEBUG') && WP_DEBUG === true) 
		return false;
		
	//If HEADWAY_DEBUG is true, don't allow caching
	if(defined('HEADWAY_DEBUG') && HEADWAY_DEBUG === true) 
		return false;
		
	//If previewing skin, don't use cache.
	if(isset($_GET['headway-skin-preview'])) 
		return false;
		
	//If caching is disabled... don't cache.
	if(headway_get_option('disable-caching')) 
		return false;
		
	//If cache folder and headway.css are both non-writable, don't cache.
	if(is_dir(HEADWAYCACHE) && file_exists(HEADWAYCACHE.'/headway.css') && !(is_writable(HEADWAYCACHE) || is_writable(HEADWAYCACHE.'/headway.css'))) 
		return false;

	return true;
}
	

/**
 * Array walk recursive for PHP 4
 **/
if(!function_exists('array_walk_recursive')){
	function array_walk_recursive(&$input, $funcname, $userdata = "") {
	    if (!is_callable($funcname)) {
	        return false;
	    }

	    if (!is_array($input)) {
	        return false;
	    }

	    foreach ($input AS $key => $value) {
	        if (is_array($input[$key])) {
	            array_walk_recursive($input[$key], $funcname, $userdata);
	        } else {
	            $saved_value = $value;
	            $saved_key = $key;
	            if (!empty($userdata)) {
	                $funcname($value, $key, $userdata);
	            } else {
	                $funcname($value, $key);
	            }

	            if ($value != $saved_value || $saved_key != $key) {
	                unset($input[$saved_key]);
	                $input[$key] = $value;
	            }
	        }
	    }
	    return true;
	}
}


/**
 * Print MySQL debugging information
 **/
function headway_print_debug_output(){
	if((defined('WP_DEBUG') && WP_DEBUG === true) || (defined('HEADWAY_DEBUG') && HEADWAY_DEBUG === true)){
		echo '<p class="copyright">'.get_num_queries().' queries.<br />';
		echo timer_stop(0).' seconds.</p>';
	}
	
	if(defined('SAVEQUERIES') && SAVEQUERIES === true){
		global $wpdb; 
	    
		echo '<p style="display: none;">';
		var_dump($wpdb->queries);
		echo '</p>';
	}
}


/**
 * Used to clean styles for elements that need to be removed.
 * 
 * @todo Add more parameters
 **/
function headway_clean_style($style_path, $elements_to_remove){
	$styles = headway_json_decode(file_get_contents($style_path));
		
	foreach($styles['styles'] as $key => $style){
		if(in_array(headway_form_name_to_selector($style['element']), $elements_to_remove)) unset($styles['styles'][$key]);
	}
	
	sort($styles['styles']);
	
	echo headway_json_encode($styles);
	die();
}