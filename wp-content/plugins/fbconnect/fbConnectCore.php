<?php
/*
 Plugin Name: Facebook OpenGraph
 Plugin URI: http://www.sociable.es/facebook-connect
 Description: Facebook Open Graph Social Plugins (like,activity and recommendations), and Facebook Connect for account registration, authentication, and commenting. 
 Author: Javier Reyes
 Author URI: http://www.sociable.es/
 Version: 3.0.7
 License: GPL (http://www.fsf.org/licensing/licenses/info/GPLv2.html) 
 */
 //print_r($_REQUEST);
define('FBCONNECT_LOG_EMERG',    1);     /** System is unusable */
define('FBCONNECT_LOG_ERR',      2);     /** Error conditions */
define('FBCONNECT_LOG_WARNING',  3);     /** Warning conditions */
define('FBCONNECT_LOG_INFO',     4);     /** Informational */
define('FBCONNECT_LOG_DEBUG',    5);     /** Debug-level messages */

define('FBCONNECT_LOG_LEVEL', get_option('fb_connect_log_level')); 

define ( 'FBCONNECT_PLUGIN_REVISION', 99); 

define ( 'FBCONNECT_DB_REVISION', 15);
define ( 'FBCONNECT_TICKWIDTH', 370);
define ( 'FBCONNECT_TICKHEIGHT', 400);

if (! defined('WP_CONTENT_DIR'))
    define('WP_CONTENT_DIR', ABSPATH . 'wp-content');

if (! defined('WP_THEME_DIR'))
    define('WP_THEME_DIR', ABSPATH . 'wp-content/themes');

if (! defined('WP_CONTENT_URL'))
    define('WP_CONTENT_URL', get_option('siteurl') . '/wp-content');

if (! defined('WP_THEME'))
    define('WP_THEME', get_option('siteurl') . '/wp-content/themes');
	
if (! defined('WP_PLUGIN_DIR'))
    define('WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins');

if (! defined('WP_PLUGIN_URL'))
    define('WP_PLUGIN_URL', WP_CONTENT_URL . '/plugins');
	
define ('FBCONNECT_PLUGIN_BASENAME', plugin_basename(dirname(__FILE__)));
define ('FBCONNECT_PLUGIN_PATH', WP_PLUGIN_DIR."/".FBCONNECT_PLUGIN_BASENAME);
define ('FBCONNECT_PLUGIN_PATH_STYLE', FBCONNECT_PLUGIN_PATH."/fbconnect.css");
define ('FBCONNECT_PLUGIN_PATH_LOG', WP_PLUGIN_DIR."/".FBCONNECT_PLUGIN_BASENAME."/Log/fbconnectwp.txt");
define ('FBCONNECT_PLUGIN_URL_LOG', WP_PLUGIN_URL."/".FBCONNECT_PLUGIN_BASENAME."/Log/fbconnectwp.txt");
define ('FBCONNECT_PLUGIN_URL', WP_PLUGIN_URL."/".FBCONNECT_PLUGIN_BASENAME);
define ('FBCONNECT_PLUGIN_URL_IMG', WP_PLUGIN_URL."/".FBCONNECT_PLUGIN_BASENAME."/images");
define ('FBCONNECT_PLUGIN_LANG', FBCONNECT_PLUGIN_BASENAME."/lang");
define ('FBCONNECT_PAGE_URL', "http://".$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]);


set_include_path( dirname(__FILE__) . PATH_SEPARATOR . get_include_path() );   

if  (!class_exists('WPfbConnect')):
class WPfbConnect {
	var $store;
	var $consumer;

	var $status = array();

	var $message;	  // Message to be displayed to the user.
	var $action;	  // Internal action tag. 'success', 'warning', 'error', 'redirect'.

	var $response;

	var $enabled = true;

	var $bind_done = false;

	
	function WPfbConnect() {
		
	}

	function log($msg,$level=1){
		if (FBCONNECT_LOG_LEVEL!="" && $level<= FBCONNECT_LOG_LEVEL){
			$text_level = array('EMERG','ERROR','WARN ','INFO ','DEBUG');
			$date = date('d/m/Y H:i:s'); 
		    $msg = "[".$date."] [".$text_level[$level]."] ".$msg."\n"; 
			//echo $msg;
			error_log($msg, 3, FBCONNECT_PLUGIN_PATH_LOG);
		}
	}

	function netId() {
		return "facebook";
	}

	function textdomain() {
		load_plugin_textdomain('fbconnect', PLUGINDIR ."/".FBCONNECT_PLUGIN_LANG);
	}

	function table_prefix() {
		global $wpdb;
		return isset($wpdb->base_prefix) ? $wpdb->base_prefix : $wpdb->prefix;
	}

	function comments_table_name() { global $wpdb; return $wpdb->comments; }
	function usermeta_table_name() { global $wpdb; return $wpdb->usermeta; }
	function users_table_name() { global $wpdb; return $wpdb->users; }
	function friends_table_name() 
	{ 
		global $wpdb; 
		$aux=$wpdb->users;

        $pos = strpos($aux, "users");
        return substr($aux, 0, $pos) . 'fb_friends'; 
	}
	
	function lastlogin_table_name()
	{
		global $wpdb; 
		$aux=$wpdb->users;

        $pos = strpos($aux, "users");
        return substr($aux, 0, $pos) . 'fb_lastlogin'; 

	}
}
endif;

require_once('fbConnectLogic.php');

require_once('fbConnectInterface.php');

if(file_exists (FBCONNECT_PLUGIN_PATH.'/pro/fbConnectCorePro.php')){
	require_once(FBCONNECT_PLUGIN_PATH.'/pro/fbConnectCorePro.php');
}else{
	define ('FBCONNECT_CANVAS', "web");
}

restore_include_path();

@session_start();




	
WPfbConnect_Logic::updateplugin();

if (FBCONNECT_CANVAS=="web") {
	//wp_enqueue_script('jquery');
	//wp_enqueue_script('jquery-form');
	$rutaTemplate=get_bloginfo('stylesheet_directory', $filter = 'raw');
	
	//wp_enqueue_script("thickbox");
	/*if( FBCONNECT_CANVAS=="web" && (get_option('fb_add_post_share') || get_option('fb_add_post_head_share')) ) {
		wp_enqueue_script('fbconnect_shareload', 'http://static.ak.fbcdn.net/connect.php/js/FB.Loader');		
		wp_enqueue_script('fbconnect_share', 'http://static.ak.fbcdn.net/connect.php/js/FB.Share');

	}*/
	if( FBCONNECT_CANVAS=="web"){
		//wp_enqueue_script('fbconnect_core', 'http://static.ak.fbcdn.net/connect/en_US/core.js');
	}
	wp_enqueue_script('fbconnect_script', FBCONNECT_PLUGIN_URL.'/fbconnect.js?pluginver='.FBCONNECT_PLUGIN_REVISION); 
	if (get_option('fb_connect_use_thick')){
		add_thickbox();
	}
}

if (!function_exists('fbconnect_init')):
function fbconnect_init() {
	if ($GLOBALS['fbconnect'] && is_a($GLOBALS['fbconnect'], 'WPfbConnect')) {
		return;
	}
	
	$GLOBALS['fbconnect'] = new WPfbConnect();
}
endif;

if (!function_exists('fbconnect_title')):
function fbconnect_title($title) {
	if($_REQUEST['fbconnect_action']=="community"){
		return __('Community', 'fbconnect')." - ".$title;
	}else if($_REQUEST['fbconnect_action']=="myhome"){
		$userprofile = WPfbConnect_Logic::get_user();
		return $userprofile->display_name." - ".$title;
	}else if($_REQUEST['fbconnect_action']=="invite"){
		return _e('Invite your friends', 'fbconnect')." - ".$title;
	}
		
	return $title;
}
endif;

/*
Ver rewrite.php
if (!function_exists('fbconnect_add_custom_urls')):
function fbconnect_add_custom_urls() {
  add_rewrite_rule('(userprofile)/[/]?([0-9]*)[/]?([0-9]*)$', 
  'index.php?fbconnect_action=myhome&fbuserid=$matches[2]&var2=$matches[3]');
  add_rewrite_tag('%fbuserid%', '[0-9]+');
  add_rewrite_tag('%var2%', '[0-9]+');
}
endif;
*/
//wp_enqueue_script( 'prototype' );
// -- Register actions and filters -- //

add_filter('wp_title', 'fbconnect_title');

// runs the function in the init hook
//add_action('init', 'fbconnect_add_custom_urls');

add_filter('get_comment_author_url', array('WPfbConnect_Logic', 'get_comment_author_url'));
add_filter('get_comment_author_link', array('WPfbConnect_Logic','fbc_remove_nofollow'));
if (get_option('fb_hide_wpcomments') || get_option('fb_show_fbcomments')){
	add_filter('comments_template', array('WPfbConnect_Logic','fbc_comments_template'));
}

add_action('the_content', array( 'WPfbConnect_Interface', 'add_fbshare' ) );
add_filter('get_the_excerpt', array( 'WPfbConnect_Interface','remove_share'), 9); 

if(get_option('fb_add_main_image')){
	add_action('admin_menu', array( 'WPfbConnect_Interface','fbconnect_add_main_img_box') );
	add_action('save_post', array( 'WPfbConnect_Interface','fbconnect_save_post'));
}

if(get_option('fb_add_wpmain_image')){
	if(function_exists('add_theme_support')):
		add_theme_support( 'post-thumbnails' );	
	endif;
}
add_action( 'init', array( 'WPfbConnect','textdomain') ,1 ); // load textdomain

register_activation_hook(FBCONNECT_PLUGIN_BASENAME.'/fbConnectCore.php', array('WPfbConnect_Logic', 'activate_plugin'));
register_deactivation_hook(FBCONNECT_PLUGIN_BASENAME.'/fbConnectCore.php', array('WPfbConnect_Logic', 'deactivate_plugin'));

add_action( 'admin_menu', array( 'WPfbConnect_Interface', 'add_admin_panels' ) );

add_filter('language_attributes', array('WPfbConnect_Logic', 'html_namespace'));
add_filter('get_avatar', array('WPfbConnect_Logic', 'fb_get_avatar'),10,4);
// Add hooks to handle actions in WordPress

//add_action( 'wp_authenticate', array( 'WPfbConnect_Logic', 'wp_authenticate' ) );
add_action( 'wp_logout', array( 'WPfbConnect_Logic', 'fb_logout'),1);

add_action( 'init', array( 'WPfbConnect_Logic', 'wp_login_fbconnect' ),100 ); 


// Comment filtering
add_action( 'comment_post', array( 'WPfbConnect_Logic', 'comment_fbconnect' ), 5 );

//add_filter( 'comment_post_redirect', array( 'WPfbConnect_Logic', 'comment_post_redirect'), 0, 2);
if( get_option('fb_enable_approval') ) {
	add_filter( 'pre_comment_approved', array('WPfbConnect_Logic', 'comment_approval'));
}


// include internal stylesheet
add_action( 'wp_head', array( 'WPfbConnect_Interface', 'style'),1);
add_action( 'login_head', array( 'WPfbConnect_Interface', 'style'),1);

if( get_option('fb_enable_commentform') ) {
	add_action( 'comment_form', array( 'WPfbConnect_Interface', 'comment_form'), 10);
}

add_action( 'admin_init', 'fb_admin_init' ); 	
if(!function_exists('fb_admin_init')):
	function fb_admin_init(){
		wp_enqueue_script('jquery');
		//wp_enqueue_script('jquery-form');
		wp_enqueue_script("thickbox");
	}
endif;

add_action( 'admin_head', 'fb_admin_head' );
if(!function_exists('fb_admin_head')):
	function fb_admin_head(){
		echo '<link rel="stylesheet" href="'.get_option('siteurl').'/'.WPINC.'/js/thickbox/thickbox.css" type="text/css" media="screen" />';
	}
endif;

add_action('admin_head', array( 'WPfbConnect_Interface', 'style'),1);
add_action('admin_footer', array( 'WPfbConnect_Logic', 'fbconnect_init_scripts'), 1);

add_action( 'wp_footer', array( 'WPfbConnect_Logic', 'fbconnect_init_scripts'), 1);


if(!function_exists('carga_template')):
function carga_template() {
	
	if (isset($_REQUEST['fbconnect_action'])){
		set_include_path( TEMPLATEPATH . PATH_SEPARATOR . dirname(__FILE__) .PATH_SEPARATOR. WP_PLUGIN_DIR.'/'.FBCONNECT_PLUGIN_BASENAME. PATH_SEPARATOR . get_include_path() );   
		if($_REQUEST['fbconnect_action']=="community"){
			include( 'community.php');
		}else if($_REQUEST['fbconnect_action']=="register"){
			include( 'fbconnect_register.php');
		}else if($_REQUEST['fbconnect_action']=="register_update"){
			WPfbConnect_Logic::register_update();
			//include( 'pro/fbconnect_register.php');
			if(FBCONNECT_CANVAS=="web"){
				wp_redirect( get_option('siteurl') );
			}else{
				echo '<fb:redirect url="'.get_option('siteurl').'" />';
			}
		}else if($_REQUEST['fbconnect_action']=="myhome"){
			include( 'myhome.php');
		}else if($_REQUEST['fbconnect_action']=="sidebar"){
			include(FBCONNECT_PLUGIN_PATH.'/pro/fbconnect_sidebar.php');
		}else if($_REQUEST['fbconnect_action']=="cacheimage"){
			include(FBCONNECT_PLUGIN_PATH.'/pro/fbconnect_cacheimage.php');
		}else if($_REQUEST['fbconnect_action']=="tab"){
			include('fbconnect_tab.php');
		}else if($_REQUEST['fbconnect_action']=="invite"){
			include('invitefriends.php');
		}else if($_REQUEST['fbconnect_action']=="userpages"){
			include('fbUserPages.php');
		}else if($_REQUEST['fbconnect_action']=="invitereq"){
			print_r($_REQUEST);
		}else if($_REQUEST['fbconnect_action']=="logout"){
			if(function_exists('wp_logout')){
				wp_logout();
			}
			if(function_exists('wp_redirect')){
				if (isset($_SERVER["HTTP_REFERER"]) && $_SERVER["HTTP_REFERER"]!=""){
					wp_redirect( $_SERVER["HTTP_REFERER"]);
				}else{
					wp_redirect( get_option('siteurl') );
				}
			}
		}else if($_REQUEST['fbconnect_action']=="ajaxperms"){
			//echo "OK";
			$fb_user = fb_get_loggedin_user();
			if ($fb_user!=""){
				$perms = fb_get_userPrmisions($fb_user);
				//print_r($perms);
				if ($perms!="ERROR" && $perms["publish_stream"]&& $perms["read_stream"]){
					echo 'yes';
				}else{
					echo 'no';					
				}
			}else{
				echo 'no';
			}
			//print_r($_REQUEST);
		}else if($_REQUEST['fbconnect_action']=="fbfeed"){
			include( FBCONNECT_PLUGIN_PATH.'/pro/fbfeed.php');
		}else if($_REQUEST['fbconnect_action']=="stream"){
			include(FBCONNECT_PLUGIN_PATH.'/pro/fbStream.php');
		}else if($_REQUEST['fbconnect_action']=="friendsStream"){
			include(FBCONNECT_PLUGIN_PATH.'/pro/fbFriendsStream.php');
		}else if($_REQUEST['fbconnect_action']=="friendsSearch"){
			include(FBCONNECT_PLUGIN_PATH.'/pro/fbFriendsSearch.php');
		}else if($_REQUEST['fbconnect_action']=="publishStream"){
			include(FBCONNECT_PLUGIN_PATH.'/pro/fbPublishStream.php');
		}else if($_REQUEST['fbconnect_action']=="publisher"){
			//print_r($_REQUEST);
		}else if($_REQUEST['fbconnect_action']=="mainimage"){
			WPfbConnect_Interface::fbconnect_img_selector($_REQUEST["postid"]);		
		}

		restore_include_path();
		exit;
	}
}
endif;
add_action('template_redirect', 'carga_template');

/**
 * If the current comment was submitted with FacebookConnect, return true
 * useful for  <?php echo ( is_comment_fbconnect() ? 'Submitted with FacebookConnect' : '' ); ?>
 */
if(!function_exists('is_comment_fbconnect')):
function is_comment_fbconnect() {
	global $comment;
	return ( $comment->fbconnect == 1 );
}
endif;

/**
 * If the current user registered with FacebookConnect, return true
 */
if(!function_exists('is_user_fbconnect')):
function is_user_fbconnect($id = null) {
	global $current_user;
    $user = $current_user;
	if ($id != null) {
		$user = get_userdata($id);
	}
	if($user!=null && $user->fbconnect_userid){
		return true;
	}else{
		return false;
	}
}
endif;


//MAIN WIDGET
if(!function_exists('widget_FacebookConnector_init')):
function widget_FacebookConnector_init() {
if (!function_exists('register_sidebar_widget')) return;
function widget_FacebookConnector($args) {
		
		$options = get_option('widget_FacebookConnector');

		if (!isset($options) || $options==""){
			$before_title ="<h2>";
			$after_title ="</h2>";
			$options = widget_FacebookConnector_init_options($options);
		}
		$title = $options['title'];
		$welcometext = $options['welcometext'];
		$loginbutton = $options['loginbutton'];
		$maxlastusers = $options['maxlastusers'];
		
		extract($args);
		
		echo $before_widget;

		$fb_user = fb_get_loggedin_user();

		$user = wp_get_current_user();
		
		$users = WPfbConnect_Logic::get_lastusers_fbconnect($maxlastusers);
		$siteurl = get_option('siteurl');
	
		$uri = "";
		if (isset($_SERVER["REQUEST_URI"])){
			$uri = $_SERVER["REQUEST_URI"];			
		}
		
		set_include_path( TEMPLATEPATH . PATH_SEPARATOR . dirname(__FILE__) .PATH_SEPARATOR. WP_PLUGIN_DIR.'/'.FBCONNECT_PLUGIN_BASENAME. PATH_SEPARATOR . get_include_path() );   
		
		include( 'fbconnect_widget.php');
		
		restore_include_path();
		
		echo $after_widget;
	}

	function widget_FacebookConnector_init_options($options){
		if (!isset($options['title'])){
			$options['title'] = "Community";
		}
		if (!isset($options['welcometext'])){
			$options['welcometext'] = "Welcome to ".get_option('blogname')."!";
		}
		if (!isset($options['loginbutton'])){
			$options['loginbutton'] = "long";
		}
		if (!isset($options['maxlastusers'])){
			$options['maxlastusers'] = "9";
		}
		return $options;
	}
	
	function widget_FacebookConnector_control() {
		$options = get_option('widget_FacebookConnector');
		if ( $_POST['FacebookConnector-submit'] ) {
			$options['title'] = strip_tags(stripslashes($_POST['FacebookConnector-title']));
			$options['welcometext'] = stripslashes($_POST['FacebookConnector-welcometext']);
			$options['loginbutton'] = stripslashes($_POST['FacebookConnector-loginbutton']);
			$options['maxlastusers'] = (int)$_POST['FacebookConnector-maxlastusers'];
			update_option('widget_FacebookConnector', $options);
		}

		$options = widget_FacebookConnector_init_options($options);
		
		$title = htmlspecialchars($options['title'], ENT_QUOTES);
		$welcometext = htmlspecialchars($options['welcometext'], ENT_QUOTES);
		$loginbutton = htmlspecialchars($options['loginbutton'], ENT_QUOTES);
		$maxlastusers = htmlspecialchars($options['maxlastusers'], ENT_QUOTES);
		//get_option('blogname')

		echo '<p style="text-align:right;"><label for="FacebookConnector-title">'.__('Title:', 'fbconnect').' <input style="width: 180px;" id="FacebookConnector-title" name="FacebookConnector-title" type="text" value="'.$title.'" /></label></p>';
		echo '<p style="text-align:right;"><label for="FacebookConnector-welcometext">'.__('Welcome msg:', 'fbconnect').' <input style="width: 180px;" id="FacebookConnector-welcometext" name="FacebookConnector-welcometext" type="text" value="'.$welcometext.'" /></label></p>';
		echo '<p style="text-align:right;"><label for="FacebookConnector-maxlastusers">'.__('Max users photos:', 'fbconnect').' <input style="width: 180px;" id="FacebookConnector-maxlastusers" name="FacebookConnector-maxlastusers" type="text" value="'.$maxlastusers.'" /></label></p>';
		echo '<p style="text-align:right;"><label for="FacebookConnector-loginbutton">'.__('Login button:', 'fbconnect').' <SELECT style="width: 180px;" id="FacebookConnector-loginbutton" name="FacebookConnector-loginbutton">';
		echo '<OPTION ';
		if ($loginbutton=="long") echo "SELECTED";
		echo ' VALUE="long">long</OPTION>';
		echo ' <OPTION ';
		if ($loginbutton=="short") echo "SELECTED";
		echo ' VALUE="short">short</OPTION>';
		echo '</SELECT></label></p>';
		echo '<input type="hidden" id="FacebookConnector-submit" name="FacebookConnector-submit" value="1" />';
	}		

	register_sidebar_widget('FacebookConnector', 'widget_FacebookConnector');
	register_widget_control('FacebookConnector', 'widget_FacebookConnector_control', 300, 100);
}
endif;

add_action('plugins_loaded', 'widget_FacebookConnector_init');

//LAST USERS WIDGET
if(!function_exists('widget_FBConnector_LastUsers_init')):
function widget_FBConnector_LastUsers_init() {

if (!function_exists('register_sidebar_widget')) return;

function widget_FBConnector_LastUsers($args) {
		
		$options = get_option('widget_FBConnector_LastUsers');

		if (!isset($options) || $options==""){
			$before_title ="<h2>";
			$after_title ="</h2>";
			$options = widget_FBConnector_LastUsers_init_options($options);
		}
		$title = $options['title'];
		$welcometext = $options['welcometext'];
		$maxlastusers = $options['maxlastusers'];

		echo $before_widget;

		$users = WPfbConnect_Logic::get_lastusers_fbconnect($maxlastusers);
		$siteurl = get_option('siteurl');

		extract($args);
		echo $before_widget;	
		$uri = "";
		if (isset($_SERVER["REQUEST_URI"])){
			$uri = $_SERVER["REQUEST_URI"];			
		}
		
		set_include_path( TEMPLATEPATH . PATH_SEPARATOR . dirname(__FILE__) .PATH_SEPARATOR. WP_PLUGIN_DIR.'/'.FBCONNECT_PLUGIN_BASENAME. PATH_SEPARATOR . get_include_path() );   

		echo '<div id="fbconnect_widget_lastusers" >';
		echo $before_title . $title . $after_title;

		include( 'fbconnect_widget_lastusers.php');
		
		echo '</div>';
		
		restore_include_path();
		
		echo $after_widget;
	}

	function widget_FBConnector_LastUsers_init_options($options){
		if (!isset($options['title'])){
			$options['title'] = "Last Users";
		}
		if (!isset($options['welcometext'])){
			$options['welcometext'] = "Last users on ".get_option('blogname')."!";
		}
		if (!isset($options['maxlastusers'])){
			$options['maxlastusers'] = "9";
		}
		return $options;
	}
	
	function widget_FBConnector_LastUsers_control() {
		$options = get_option('widget_FBConnector_LastUsers');
		if ( $_POST['FBConnector_LastUsers-submit'] ) {
			$options['title'] = strip_tags(stripslashes($_POST['FBConnector_LastUsers-title']));
			$options['welcometext'] = stripslashes($_POST['FBConnector_LastUsers-welcometext']);
			$options['maxlastusers'] = (int)$_POST['FBConnector_LastUsers-maxlastusers'];
			update_option('widget_FBConnector_LastUsers', $options);
		}

		$options = widget_FBConnector_LastUsers_init_options($options);
		
		$title = htmlspecialchars($options['title'], ENT_QUOTES);
		$welcometext = htmlspecialchars($options['welcometext'], ENT_QUOTES);
		$maxlastusers = htmlspecialchars($options['maxlastusers'], ENT_QUOTES);
		//get_option('blogname')

		echo '<p style="text-align:right;"><label for="FBConnector_LastUsers-title">'.__('Title:', 'fbconnect').' <input style="width: 180px;" id="FBConnector_LastUsers-title" name="FBConnector_LastUsers-title" type="text" value="'.$title.'" /></label></p>';
		echo '<p style="text-align:right;"><label for="FBConnector_LastUsers-welcometext">'.__('Welcome msg:', 'fbconnect').' <input style="width: 180px;" id="FBConnector_LastUsers-welcometext" name="FBConnector_LastUsers-welcometext" type="text" value="'.$welcometext.'" /></label></p>';
		echo '<p style="text-align:right;"><label for="FBConnector_LastUsers-maxlastusers">'.__('Max users:', 'fbconnect').' <input style="width: 180px;" id="FBConnector_LastUsers-maxlastusers" name="FBConnector_LastUsers-maxlastusers" type="text" value="'.$maxlastusers.'" /></label></p>';
		echo '<input type="hidden" id="FBConnector_LastUsers-submit" name="FBConnector_LastUsers-submit" value="1" />';
	}		

	register_sidebar_widget('FBConnector_LastUsers', 'widget_FBConnector_LastUsers');
	register_widget_control('FBConnector_LastUsers', 'widget_FBConnector_LastUsers_control', 300, 100);
}
endif;

add_action('plugins_loaded', 'widget_FBConnector_LastUsers_init');


//LAST FRIENDS
if(!function_exists('widget_FBConnector_LastFriends_init')):
function widget_FBConnector_LastFriends_init() {

if (!function_exists('register_sidebar_widget')) return;

function widget_FBConnector_LastFriends($args) {
		
		$options = get_option('widget_FBConnector_LastFriends');

		if (!isset($options) || $options==""){
			$before_title ="<h2>";
			$after_title ="</h2>";
			$options = widget_FBConnector_LastFriends_init_options($options);
		}
		$title = $options['title'];
		$welcometext = $options['welcometext'];
		$maxlastusers = $options['maxlastusers'];
		
		extract($args);
		
		echo $before_widget;

		$fb_user = fb_get_loggedin_user();

		$user = wp_get_current_user();
		
		$users = WPfbConnect_Logic::get_lastusers_fbconnect($maxlastusers);
		$siteurl = get_option('siteurl');
	
		$uri = "";
		if (isset($_SERVER["REQUEST_URI"])){
			$uri = $_SERVER["REQUEST_URI"];			
		}
		
		set_include_path( TEMPLATEPATH . PATH_SEPARATOR . dirname(__FILE__) .PATH_SEPARATOR. WP_PLUGIN_DIR.'/'.FBCONNECT_PLUGIN_BASENAME. PATH_SEPARATOR . get_include_path() );   

		echo $before_title . $title . $after_title;

		include( 'fbconnect_widget_lastfriends.php');
		
		
		restore_include_path();
		
		echo $after_widget;
	}

	function widget_FBConnector_LastFriends_init_options($options){
		if (!isset($options['title'])){
			$options['title'] = "Last Friends";
		}
		if (!isset($options['welcometext'])){
			$options['welcometext'] = "Last friends on ".get_option('blogname')."!";
		}
		if (!isset($options['maxlastusers'])){
			$options['maxlastusers'] = "9";
		}
		return $options;
	}
	
	function widget_FBConnector_LastFriends_control() {
		$options = get_option('widget_FBConnector_LastFriends');
		if ( $_POST['FBConnector_LastFriends-submit'] ) {
			$options['title'] = strip_tags(stripslashes($_POST['FBConnector_LastFriends-title']));
			$options['welcometext'] = stripslashes($_POST['FBConnector_LastFriends-welcometext']);
			$options['maxlastusers'] = (int)$_POST['FBConnector_LastFriends-maxlastusers'];
			update_option('widget_FBConnector_LastFriends', $options);
		}

		$options = widget_FBConnector_LastFriends_init_options($options);
		
		$title = htmlspecialchars($options['title'], ENT_QUOTES);
		$welcometext = htmlspecialchars($options['welcometext'], ENT_QUOTES);
		$maxlastusers = htmlspecialchars($options['maxlastusers'], ENT_QUOTES);
		//get_option('blogname')

		echo '<p style="text-align:right;"><label for="FBConnector_LastFriends-title">'.__('Title:', 'fbconnect').' <input style="width: 180px;" id="FBConnector_LastFriends-title" name="FBConnector_LastFriends-title" type="text" value="'.$title.'" /></label></p>';
		echo '<p style="text-align:right;"><label for="FBConnector_LastFriends-welcometext">'.__('Welcome msg:', 'fbconnect').' <input style="width: 180px;" id="FBConnector_LastFriends-welcometext" name="FBConnector_LastFriends-welcometext" type="text" value="'.$welcometext.'" /></label></p>';
		echo '<p style="text-align:right;"><label for="FBConnector_LastFriends-maxlastusers">'.__('Max users:', 'fbconnect').' <input style="width: 180px;" id="FBConnector_LastFriends-maxlastusers" name="FBConnector_LastFriends-maxlastusers" type="text" value="'.$maxlastusers.'" /></label></p>';
		echo '<input type="hidden" id="FBConnector_LastFriends-submit" name="FBConnector_LastFriends-submit" value="1" />';
	}		

	register_sidebar_widget('FBConnector_LastFriends', 'widget_FBConnector_LastFriends');
	register_widget_control('FBConnector_LastFriends', 'widget_FBConnector_LastFriends_control', 300, 100);
}
endif;

add_action('plugins_loaded', 'widget_FBConnector_LastFriends_init');

//FRIENDSFEED
if(!function_exists('widget_FBConnector_FriendsFeed_init')):
function widget_FBConnector_FriendsFeed_init() {

if (!function_exists('register_sidebar_widget')) return;

function widget_FBConnector_FriendsFeed($args) {
		
		$options = get_option('widget_FBConnector_FriendsFeed');

		if (!isset($options) || $options==""){
			$before_title ="<h2>";
			$after_title ="</h2>";
			$options = widget_FBConnector_FriendsFeed_init_options($options);
		}
		$title = $options['title'];
		$welcometext = $options['welcometext'];
		$maxlastcomments = $options['maxlastcomments'];
		
		extract($args);
		
		echo $before_widget;

		$fb_user = fb_get_loggedin_user();

		$user = wp_get_current_user();
		
		$users = WPfbConnect_Logic::get_lastusers_fbconnect($maxlastcomments);
		$siteurl = get_option('siteurl');
	
		$uri = "";
		if (isset($_SERVER["REQUEST_URI"])){
			$uri = $_SERVER["REQUEST_URI"];			
		}
		
		set_include_path( TEMPLATEPATH . PATH_SEPARATOR . dirname(__FILE__) .PATH_SEPARATOR. WP_PLUGIN_DIR.'/'.FBCONNECT_PLUGIN_BASENAME. PATH_SEPARATOR . get_include_path() );   

		echo '<div id="fbconnect_widget_lastusers" >';
		echo $before_title . $title . $after_title;
		echo '<div  id="fbconnect_feed" class="fbconnect_LastComments" >';
		global $fbconnect_filter;
		$fbconnect_filter="fbFriendsComments";
		include( 'fbconnect_feed.php');
		echo '</div>';		
		echo '</div>';
		
		restore_include_path();
		
		echo $after_widget;
	}

	function widget_FBConnector_FriendsFeed_init_options($options){
		if (!isset($options['title'])){
			$options['title'] = "Last Friends Comments";
		}
		if (!isset($options['welcometext'])){
			$options['welcometext'] = "Last friends comments";
		}
		if (!isset($options['maxlastcomments'])){
			$options['maxlastcomments'] = "9";
		}
		return $options;
	}
	
	function widget_FBConnector_FriendsFeed_control() {
		$options = get_option('widget_FBConnector_FriendsFeed');
		if ( $_POST['FBConnector_FriendsFeed-submit'] ) {
			$options['title'] = strip_tags(stripslashes($_POST['FBConnector_FriendsFeed-title']));
			$options['welcometext'] = stripslashes($_POST['FBConnector_FriendsFeed-welcometext']);
			$options['maxlastcomments'] = (int)$_POST['FBConnector_FriendsFeed-maxlastcomments'];
			update_option('widget_FBConnector_FriendsFeed', $options);
		}

		$options = widget_FBConnector_FriendsFeed_init_options($options);
		
		$title = htmlspecialchars($options['title'], ENT_QUOTES);
		$welcometext = htmlspecialchars($options['welcometext'], ENT_QUOTES);
		$maxlastcomments = htmlspecialchars($options['maxlastcomments'], ENT_QUOTES);
		//get_option('blogname')

		echo '<p style="text-align:right;"><label for="FBConnector_FriendsFeed-title">'.__('Title:', 'fbconnect').' <input style="width: 180px;" id="FBConnector_FriendsFeed-title" name="FBConnector_FriendsFeed-title" type="text" value="'.$title.'" /></label></p>';
		echo '<p style="text-align:right;"><label for="FBConnector_FriendsFeed-welcometext">'.__('Welcome msg:', 'fbconnect').' <input style="width: 180px;" id="FBConnector_FriendsFeed-welcometext" name="FBConnector_FriendsFeed-welcometext" type="text" value="'.$welcometext.'" /></label></p>';
		echo '<p style="text-align:right;"><label for="FBConnector_FriendsFeed-maxlastcomments">'.__('Max comments:', 'fbconnect').' <input style="width: 180px;" id="FBConnector_FriendsFeed-maxlastcomments" name="FBConnector_FriendsFeed-maxlastcomments" type="text" value="'.$maxlastcomments.'" /></label></p>';
		echo '<input type="hidden" id="FBConnector_FriendsFeed-submit" name="FBConnector_FriendsFeed-submit" value="1" />';
	}		

	register_sidebar_widget('FBConnector_FriendsFeed', 'widget_FBConnector_FriendsFeed');
	register_widget_control('FBConnector_FriendsFeed', 'widget_FBConnector_FriendsFeed_control', 300, 100);
}
endif;

add_action('plugins_loaded', 'widget_FBConnector_FriendsFeed_init');

//LastComments
if(!function_exists('widget_FBConnector_CommentsFeed_init')):
function widget_FBConnector_CommentsFeed_init() {

if (!function_exists('register_sidebar_widget')) return;

function widget_FBConnector_CommentsFeed($args) {
		
		$options = get_option('widget_FBConnector_CommentsFeed');

		if (!isset($options) || $options==""){
			$before_title ="<h2>";
			$after_title ="</h2>";
			$options = widget_FBConnector_CommentsFeed_init_options($options);
		}
		$title = $options['title'];
		$welcometext = $options['welcometext'];
		$maxlastcomments = $options['maxlastcomments'];
		
		extract($args);
		
		echo $before_widget;

		$fb_user = fb_get_loggedin_user();

		$user = wp_get_current_user();
		
		$users = WPfbConnect_Logic::get_lastusers_fbconnect($maxlastcomments);
		$siteurl = get_option('siteurl');
	
		$uri = "";
		if (isset($_SERVER["REQUEST_URI"])){
			$uri = $_SERVER["REQUEST_URI"];			
		}
		
		set_include_path( TEMPLATEPATH . PATH_SEPARATOR . dirname(__FILE__) .PATH_SEPARATOR. WP_PLUGIN_DIR.'/'.FBCONNECT_PLUGIN_BASENAME. PATH_SEPARATOR . get_include_path() );   

		echo '<div id="fbconnect_widget_lastusers" >';
		echo $before_title . $title . $after_title;
		echo '<div  id="fbconnect_feed" class="fbconnect_LastComments" >';
		global $fbconnect_filter;
		$fbconnect_filter="fbFriendsComments";
		include( 'fbconnect_feed.php');
		echo '</div>';		
		echo '</div>';
		
		restore_include_path();
		
		echo $after_widget;
	}

	function widget_FBConnector_CommentsFeed_init_options($options){
		if (!isset($options['title'])){
			$options['title'] = "Last Comments";
		}
		if (!isset($options['welcometext'])){
			$options['welcometext'] = "Last comments";
		}
		if (!isset($options['maxlastcomments'])){
			$options['maxlastcomments'] = "9";
		}
		return $options;
	}
	
	function widget_FBConnector_CommentsFeed_control() {
		$options = get_option('widget_FBConnector_CommentsFeed');
		if ( $_POST['FBConnector_CommentsFeed-submit'] ) {
			$options['title'] = strip_tags(stripslashes($_POST['FBConnector_CommentsFeed-title']));
			$options['welcometext'] = stripslashes($_POST['FBConnector_CommentsFeed-welcometext']);
			$options['maxlastcomments'] = (int)$_POST['FBConnector_CommentsFeed-maxlastcomments'];
			update_option('widget_FBConnector_CommentsFeed', $options);
		}

		$options = widget_FBConnector_CommentsFeed_init_options($options);
		
		$title = htmlspecialchars($options['title'], ENT_QUOTES);
		$welcometext = htmlspecialchars($options['welcometext'], ENT_QUOTES);
		$maxlastcomments = htmlspecialchars($options['maxlastcomments'], ENT_QUOTES);
		//get_option('blogname')

		echo '<p style="text-align:right;"><label for="FBConnector_CommentsFeed-title">'.__('Title:', 'fbconnect').' <input style="width: 180px;" id="FBConnector_CommentsFeed-title" name="FBConnector_CommentsFeed-title" type="text" value="'.$title.'" /></label></p>';
		echo '<p style="text-align:right;"><label for="FBConnector_CommentsFeed-welcometext">'.__('Welcome msg:', 'fbconnect').' <input style="width: 180px;" id="FBConnector_CommentsFeed-welcometext" name="FBConnector_CommentsFeed-welcometext" type="text" value="'.$welcometext.'" /></label></p>';
		echo '<p style="text-align:right;"><label for="FBConnector_CommentsFeed-maxlastcomments">'.__('Max comments:', 'fbconnect').' <input style="width: 180px;" id="FBConnector_CommentsFeed-maxlastcomments" name="FBConnector_CommentsFeed-maxlastcomments" type="text" value="'.$maxlastcomments.'" /></label></p>';
		echo '<input type="hidden" id="FBConnector_CommentsFeed-submit" name="FBConnector_CommentsFeed-submit" value="1" />';
	}		

	register_sidebar_widget('FBConnector_CommentsFeed', 'widget_FBConnector_CommentsFeed');
	register_widget_control('FBConnector_CommentsFeed', 'widget_FBConnector_CommentsFeed_control', 300, 100);
}
endif;

add_action('plugins_loaded', 'widget_FBConnector_CommentsFeed_init');


//FANBOX
if(!function_exists('widget_FBConnector_FanBox_init')):
function widget_FBConnector_FanBox_init() {

if (!function_exists('register_sidebar_widget')) return;

function widget_FBConnector_FanBox($args) {
		$options = get_option('widget_FBConnector_FanBox');

		if (!isset($options) || $options==""){
			$before_title ="<h2>";
			$after_title ="</h2>";
			$options = widget_FBConnector_FanBox_init_options($options);
		}
		$title = $options['title'];
		$profile_id = $options['profile_id'];
		$page_stream = $options['page_stream'];
		$connections = $options['connections'];
		$width = $options['width'];
		$height = $options['height'];
		$css = $options['css'];
		extract($args);
				
		echo $before_widget;


		echo $before_title . $title . $after_title;

/*		if(file_exists (TEMPLATEPATH.'/fanbox.css')){
			$css = get_bloginfo ('template_url')."/fanbox.css";
		}elseif(file_exists (FBCONNECT_PLUGIN_PATH.'/fanbox.css')){
			$css = FBCONNECT_PLUGIN_URL."/fanbox.css";
		}
	*/	
		if ($css!=""){
			$css = get_bloginfo ('template_url')."/".$css;
		}
		echo '<fb:fan profile_id="'.$profile_id.'" stream="'.$page_stream.'" connections="'.$connections.'" width="'.$width.'" height="'.$height.'" css="'.$css.'"></fb:fan>';
		
		echo $after_widget;
	}

	function widget_FBConnector_FanBox_init_options($options){
		if (!isset($options['title'])){
			$options['title'] = "Facebook Fans";
		}
		if (!isset($options['profile_id'])){
			$options['profile_id'] = "";
		}
		if (!isset($options['connections'])){
			$options['connections'] = "10";
		}
		if (!isset($options['page_stream'])){
			$options['page_stream'] = "1";
		}
		if (!isset($options['width'])){
			$options['width'] = "300";
		}
		if (!isset($options['height'])){
			$options['height'] = "300";
		}

		return $options;
		
	}
	
	function widget_FBConnector_FanBox_control() {
		$options = get_option('widget_FBConnector_FanBox');
		if ( $_POST['FBConnector_FanBox-submit'] ) {
			$options['title'] = strip_tags(stripslashes($_POST['FBConnector_FanBox-title']));
			$options['css'] = $_POST['FBConnector_FanBox-css'];
			$options['profile_id'] = stripslashes($_POST['FBConnector_FanBox-profile_id']);
			$options['connections'] = (int)$_POST['FBConnector_FanBox-connections'];
			if (isset($_POST['FBConnector_FanBox-page_stream']))
				$options['page_stream'] = (int)$_POST['FBConnector_FanBox-page_stream'];
			else
				$options['page_stream'] = 0;
			$options['width'] = (int)$_POST['FBConnector_FanBox-width'];
			$options['height'] = (int)$_POST['FBConnector_FanBox-height'];
			update_option('widget_FBConnector_FanBox', $options);
		}

		$options = widget_FBConnector_FanBox_init_options($options);
		
		$title = htmlspecialchars($options['title'], ENT_QUOTES);
		$css = $options['css'];
		$profile_id = htmlspecialchars($options['profile_id'], ENT_QUOTES);
		$connections = htmlspecialchars($options['connections'], ENT_QUOTES);
		$page_stream = htmlspecialchars($options['page_stream'], ENT_QUOTES);
		$width = htmlspecialchars($options['width'] , ENT_QUOTES);
		$height = htmlspecialchars($options['height'], ENT_QUOTES);
		//get_option('blogname')
		$checked_stream = "";
		if (isset($page_stream) && $page_stream=="1")
			$checked_stream = "checked";
	?>		
			<script type='text/javascript'>
				function callbackSelectPage(pageid){
						jQuery(".FBConnector_FanBox_text").attr("value",pageid);
					tb_remove();
					jQuery(".FBConnector_FanBox_pagepic").html('<fb:profile-pic uid="'+pageid+'" linked="true" />');
					FB.XFBML.parse();
				}
				
				function selectFBPage(){
						tb_show("Select Page", "<?php echo get_option('siteurl'); ?>?fbconnect_action=userpages&height=450&width=630&callback=callbackSelectPage", "");
				}
			</script>	
		<?php	
		echo '<p style="text-align:right;"><label for="FBConnector_FanBox-title">'.__('Title:', 'fbconnect').' <input style="width: 208px;" id="FBConnector_FanBox-title" name="FBConnector_FanBox-title" type="text" value="'.$title.'" /></label></p>';
		echo '<p style="text-align:right;"><label for="FBConnector_FanBox-css">'.__('CSS:', 'fbconnect').' <input style="width: 208px;" id="FBConnector_FanBox-css" name="FBConnector_FanBox-css" type="text" value="'.$css.'" /></label></p>';
		echo '<div style="float:right;height:80px;width:260px;"><label style="float:left" for="FBConnector_FanBox-profile_id">'.__('Page ID:', 'fbconnect').' </label>';
		echo '<div style="float:left;width:125px;">';
		echo '<input type="text" size="15" class="FBConnector_FanBox_text" name="FBConnector_FanBox-profile_id" id="FBConnector_FanBox-profile_id" value="'.$profile_id.'"/>';
		echo '<br/>';
		echo '<span class="submit"><input class="button-primary" type="button" onclick="selectFBPage();" name="selectPage" value="'.__('Select page', 'fbconnect') .'&raquo;" /></span> ';
		echo '</div>';
		echo '<div class="FBConnector_FanBox_pagepic" id="FBConnector_FanBox-profile_id-pagepic" style="float:left;width:80px;">';
		echo '<fb:profile-pic uid="'.$options['profile_id'].'" linked="true" />';
		echo '</div></div>';
		echo '<p style="text-align:right;"><label for="FBConnector_FanBox-connections">'.__('Connections:', 'fbconnect').' <input style="width: 208px;" id="FBConnector_FanBox-connections" name="FBConnector_FanBox-connections" type="text" value="'.$connections.'" /></label></p>';
		echo '<p style="text-align:right;"><label for="FBConnector_FanBox-page_stream">'.__('Show stream:', 'fbconnect').'<input style="width: 208px;" id="FBConnector_FanBox-page_stream" name="FBConnector_FanBox-page_stream" type="checkbox" value="1" '.$checked_stream.' /></label></p>';
		echo '<p style="text-align:right;"><label for="FBConnector_FanBox-width">'.__('Width:', 'fbconnect').' <input style="width: 208px;" id="FBConnector_FanBox-page_stream" name="FBConnector_FanBox-width" type="text" value="'.$width.'" /></label></p>';
		echo '<p style="text-align:right;"><label for="FBConnector_FanBox-height">'.__('Height:', 'fbconnect').' <input style="width: 208px;" id="FBConnector_FanBox-height" name="FBConnector_FanBox-height" type="text" value="'.$height.'" /></label></p>';
		echo '<input type="hidden" id="FBConnector_FanBox-submit" name="FBConnector_FanBox-submit" value="1" />';
	}		

	register_sidebar_widget('FBConnector_FanBox', 'widget_FBConnector_FanBox');
	register_widget_control('FBConnector_FanBox', 'widget_FBConnector_FanBox_control', 300, 100);
}
endif;

add_action('plugins_loaded', 'widget_FBConnector_FanBox_init');

require_once('Widget_ActivityRecommend.php');
require_once('Widget_Recommend.php');
?>