<?php
/**
 * @author: Javier Reyes Gomez (http://www.sociable.es)
 * @date: 05/10/2008
 * @license: GPLv2
 */

include_once 'fbConfig.php';

if (!class_exists('WPfbConnect_Interface')):
class WPfbConnect_Interface {

	/**
	 * Enqueue required javascript libraries.
	 *
	 * @action: init
	 **/
	function js_setup() {
	}

	// Remove the filter excerpts
	function remove_share($content) {
	    remove_action('the_content', array( 'WPfbConnect_Interface', 'add_fbshare' ));
	    return $content;
	}
	
	
	/**
	 * Add Facebook Share
	 *get_post_meta($id, 'fbconnect_short_url', true);
	 * @action: the_content
	 **/
	function add_fbshare($content) {
		global $post;
		//$content = '<div class="fbconnect_head_share"><fb:share-button class="url" type="box_count" href="'.get_permalink($post->ID).'" /></div>'.$content;
		if( get_option('fb_add_post_head_share') && FBCONNECT_CANVAS=="web" ) {
			
			$content = '<div class="fbconnect_head_share"><fb:like layout="button_count" href="'.get_permalink($post->ID).'"></fb:like></div>'.$content;
			//$content = '<div class="fbconnect_head_share"><fb:like layout="box_count" href="'.get_permalink($post->ID).'"></fb:like></div>';
			
			//$content = '<div class="fbconnect_head_share"><a name="fb_share" type="box_count" share_url="'.get_permalink($post->ID).'" href="http://www.facebook.com/sharer.php">'.__('Share', 'fbconnect').'</a></div>'.$content;
			//$content = '<div class="fbconnect_head_share"><a name="fb_share" type="box_count" share_url="'.get_permalink($post->ID).'" href="http://www.facebook.com/sharer.php">'.__('Share', 'fbconnect').'</a></div>'.$content;
			//$content = '<div class="fbconnect_head_share"><fb:share-button class="url" type="box_count" href="'.get_permalink($post->ID).'" /></div>'.$content;
		}
		if( get_option('fb_add_post_share') ) {
			$content .= '<div class="fbconnect_share">';
			$hidefaces = "";
			if (get_option('fb_like_show_faces')){
				$hidefaces = 'show_faces="false"';				
			}
			$content .= '<fb:like href="'.get_permalink($post->ID).'" '.$hidefaces.'></fb:like>';
			//$content .= '<div style="float:right;margin-left:10px;"><a rel="nofollow" target="_blank" href="http://www.google.com/reader/link?url='.urlencode(get_permalink($post->ID)).'&amp;title='.urlencode($post->post_title).'&amp;srcURL='.urlencode(get_option('siteurl')).'" class="google_buzz"><img alt="Google Buzz" src="'.FBCONNECT_PLUGIN_URL_IMG.'/buzzp.jpg"></a></div>';
			/*if( FBCONNECT_CANVAS=="web") {
				$content .= '<div style="float:right;"><a name="fb_share" type="button_count" share_url="'.get_permalink($post->ID).'" href="http://www.facebook.com/sharer.php">'.__('Share', 'fbconnect').'</a></div>';
				//$content .= '<p class="fbconnect_share"><fb:share-button class="url" type="button_count" href="'.get_permalink($post->ID).'" /></p>';
			}else{
				$content .= '<div style="float:right;"><fb:share-button class="url" href="'.get_permalink($post->ID).'" /></div>';			
			}*/
			$content .= '</div>';
			
		}
		/*if( FBCONNECT_CANVAS=="web" && (get_option('fb_add_post_share') || get_option('fb_add_post_head_share')) ) {
			$content .= '<script src="http://static.ak.fbcdn.net/connect.php/js/FB.Share" type="text/javascript"></script>';
		}*/
		//$content .= '<div class="fbconnect_share"><fb:share-button class="url" type="button_count" href="'.get_permalink($post->ID).'" /></div>';
		return $content;
	}
	
	
	/**
	 * Include internal stylesheet.
	 *
	 * @action: wp_head, login_head
	 **/
	function style() {
		if ( file_exists( TEMPLATEPATH . '/fbconnect.css') ){
			$css_path =  get_template_directory_uri() . '/fbconnect.css?ver='.FBCONNECT_PLUGIN_REVISION;
		}else{
			$css_path = FBCONNECT_PLUGIN_URL . '/fbconnect.css?ver='.FBCONNECT_PLUGIN_REVISION;
		}

		//echo '<link rel="stylesheet" type="text/css" href="http://www.escire.com/wp-content/plugins/fbconnect/fbconnect.css" />';

		echo '<link rel="stylesheet" type="text/css" href="'.$css_path.'" />';
		//echo '<link rel="stylesheet" href="'.get_option('siteurl').'/'.WPINC.'/js/thickbox/thickbox.css" type="text/css" media="screen" />';
		if (is_single() || is_page()){
			global $post;
			$postID="";
			if (isset($post))
				$postID=$post->ID;

			$imgurl = WPfbConnect_Logic::get_post_image($postID);
			$pos = strrpos($imgurl, "default_logo.gif");
			if ($pos === false) {
				echo '<link rel="image_src" href="'.$imgurl.'" />';
			}
			if(function_exists('fbconnect_getvideourl_post')):	
				$video_src = fbconnect_getvideourl_post($postID);
				if ($video_src!=""){
					echo '<link rel="video_src" href="'.$video_src.'" />';
				}
			endif;
			echo '<meta name="og:site_name" content="'.htmlentities(get_bloginfo('name')).'"/>';
			echo '<meta name="og:title" content="'.htmlentities($post->post_title).'"/>';
			echo '<link rel="og:url" href="'.get_permalink($post->ID).'" />';
			

		}elseif(get_option('fb_comments_logo')!=""){
			echo '<link rel="image_src" href="'.get_option('fb_comments_logo').'" />';
		}
	}


	/**
	 *  Modify comment form.
	 *
	 * @action: comment_form
	 **/
	function comment_form() {
		$fb_user = fb_get_loggedin_user();
		$user = wp_get_current_user();
		if (is_user_logged_in() && $fb_user) {
			echo '<img class="icon-text-middle" src="'.FBCONNECT_PLUGIN_URL .'/images/facebook_24.png"/>';
			echo '<input style="width:20px;" type="checkbox" name="sendToFacebook" id="sendToFacebook" checked="checked" />'.__('Publish this comment to Facebook', 'fbconnect');
		}
		if(get_option('fb_connect_comments_login')){
			echo '<div id="fbconnect_commentslogin">';
			include("fbconnect_widget_login.php");
			echo '</div>';
			echo "<script type='text/javascript'>\n";
			echo 'showCommentsLogin();';
			echo "</script>\n";
		}
	}


	
	function fbconnect_add_main_img_box(){
		if( function_exists( 'add_meta_box' )) {
			add_meta_box( 'fbconnect_main_img', __( 'Main post image', 'fbconnect' ), 
		                array( 'WPfbConnect_Interface','fbconnect_main_img_box'), 'post', 'side','high' );						
			add_meta_box( 'fbconnect_main_img', __( 'Main post image', 'fbconnect' ), 
		                array( 'WPfbConnect_Interface','fbconnect_main_img_box'), 'page', 'side','high' );						
		    						
	   } 
	}
	
	function fbconnect_main_img_box(){
		global $post;
		$post_id = $post;
		if (is_object($post_id)){
			$post_id = $post_id->ID;
		}
		echo "<script type='text/javascript'>\n";
		echo "function fbconnect_changeimg(url,imgid){\n";
		echo "jQuery(document).ready(function($) {\n";
		echo "$('#fb_mainimg').attr('src', url);\n";
		echo "$('#fb_mainimg_url').attr('value', url);\n";
		echo "$('#fb_mainimg_id').attr('value', imgid);});\n";
		echo "tb_remove();\n";
		echo "\n}\n";
		echo "function fbconnect_imgselect(){\n";
		echo '	tb_show("Main post image", "'.get_option('siteurl').'?fbconnect_action=mainimage&modal=true&postid='.$post_id.'", "");';
		echo "\n}";
	   	echo "</script>\n";
		echo '<div style="text-align:center;width:250px;height:150px;margin-top:5px;">';
		echo '<a href="#" onclick="fbconnect_imgselect()"><b>Change main post image</b></a>';
		echo '<div style="margin:5px;width:250px;height:150px;margin-top:5px;">';
		
		$imgurl = WPfbConnect_Logic::get_post_image($post_id);
		$imgid = get_post_meta($post_id , 'fb_mainimg_id', true);
		$currentimgurl = get_post_meta($post_id , 'fb_mainimg_url', true);
		//$thumb="http://www.sociable.es/wp-content/themes/sociable/images/sociable_logo.gif";
       	echo '<img src="'.$imgurl.'" id="fb_mainimg" width=100>';
		echo '<input type="hidden" id="fb_mainimg_url" name="fb_mainimg_url" value="'.$currentimgurl.'"/>';
		echo '<input type="hidden" id="fb_mainimg_id" name="fb_mainimg_id" value="'.$imgid.'"/>';
		echo '</div>';
		echo '</div>';
	}
	
	function fbconnect_img_selector($post_id=""){
		if ($post_id==""){
			echo "Error: Select a post";
			return;
		}	
		

		echo '<div style="width:100%;margin:10px;">';
		echo '<h2>Select an image</h2>';
		$files = get_children("post_parent=$post_id&post_type=attachment&post_mime_type=image");
		//$files = get_children("post_parent=$post_id&post_type=attachment");
		if ($files!="" && count($files)>0){
			foreach($files as $num=>$value){
				echo '<div style="text-align:center;margin:5px;width:50px;height:70px;float:left;">';
				echo '<div style="width:50px;height:50px;float:left;">';
		        $thumb=wp_get_attachment_thumb_url($num);
				//$thumb="http://www.sociable.es/wp-content/themes/sociable/images/sociable_logo.gif";
		       	$img = "<img src='$thumb' width=50 align=right/>";
				//$thumb = wp_get_attachment_image( $post->ID);
				echo $img;
				echo '</div>';
				echo '<input type="radio" name="fb_publish_imgid" onclick="fbconnect_changeimg(\''.$thumb.'\',\''.$num.'\')" value="'.$num.'"/>';
				echo '</div>';
			}
		}
		echo '</div>';
		echo '<div style="width:100%;margin:10px;clear:both;text-align:center;">';
		echo '<label for="fb_current_url">Main image URL:</label>';
		echo '<input type="text" size=75 id="fb_current_url" name="fb_current_url" value="'.WPfbConnect_Logic::get_post_image($post_id).'"/><br/><br/>';
		echo '<input type="button" name="fb_save" id="fb_save" onclick="fbconnect_changeimg(document.getElementById(\'fb_current_url\').value,\'0\')" value="'.__('Save', 'fbconnect').'">'; 
		echo '<input type="button" name="fb_cancel" id="fb_cancel" onclick="tb_remove();" value="'.__('Cancel', 'fbconnect').'">'; 
		echo '</div>';
		//echo '<input type="button" name="fb_select" id="fb_cancel" onclick="fb_changeimg();" value="'.__('Select', 'fbconnect').'">'; 
	}
	
	function fbconnect_save_post($post_id ){ 
		if ( 'page' == $_POST['post_type'] ) {
		    if ( !current_user_can( 'edit_page', $post_id ))
		      return $post_id;
		} else {
		    if ( !current_user_can( 'edit_post', $post_id ))
		      return $post_id;
		}

		if ( isset($_POST['fb_mainimg_url']) && $_POST['fb_mainimg_url']!="" ) {
			update_post_meta($post_id , 'fb_mainimg_url', $_POST['fb_mainimg_url']);
		}
		if ( isset($_POST['fb_mainimg_id']) && $_POST['fb_mainimg_id']!="" ) {
			update_post_meta($post_id , 'fb_mainimg_id', $_POST['fb_mainimg_id']);
		}

	}
	

	/**
	 * Setup admin menus for fbconnect options and ID management.
	 *
	 * @action: admin_menu
	 **/
	function add_admin_panels() {
		if (function_exists('add_menu_page')) {
			add_menu_page("Facebook Connector", "Facebook", 8, 'fbconnect/fbConnectInterface.php', array( 'WPfbConnect_Interface', 'options_page'), plugins_url('fbconnect/images/facebook.png'));
		}
		if (function_exists('add_submenu_page')) {
			add_submenu_page('fbconnect/fbConnectInterface.php', __('Main options', 'fbconnect'), __('Main options', 'fbconnect'), 8, 'fbconnect/fbConnectInterface.php',array( 'WPfbConnect_Interface', 'options_page'));
			add_submenu_page('fbconnect/fbConnectInterface.php', __('Feed Templates', 'fbconnect'), __('Feed Templates', 'fbconnect'), 8, 'fbconnect/fbConnectTemplates.php');		
			//add_submenu_page('fbconnect/fbConnectInterface.php', __('Campaigns', 'fbconnect'), __('Campaigns', 'fbconnect'), 8, 'fbconnect/pro/list-obj.php');		
			if(file_exists (FBCONNECT_PLUGIN_PATH.'/pro/fbConnectAdvanced.php')){
				add_submenu_page('fbconnect/fbConnectInterface.php', __('Pro options', 'fbconnect'), __('Pro options', 'fbconnect'), 8, 'fbconnect/pro/fbConnectAdvanced.php');		
				add_submenu_page('fbconnect/fbConnectInterface.php', __('Pro options', 'fbconnect'), __('Offline options', 'fbconnect'), 8, 'fbconnect/pro/fbConnectOfflineOptions.php');		
			}
			//if(file_exists (FBCONNECT_PLUGIN_PATH.'fbConnectInfo.php')){
				add_submenu_page('fbconnect/fbConnectInterface.php', __('Sociable!', 'fbconnect'), __('Sociable!', 'fbconnect'), 8, 'fbconnect/fbConnectInfo.php');		
			//}
		}

	}


	function register_feed_forms($fb_online_stories,$fb_short_stories_title,$fb_short_stories_body,$fb_full_stories_title,$fb_full_stories_body) {
	  $one_line_stories = $short_stories = $full_stories = array();
	
	  $one_line_stories[] = $fb_online_stories;
	  $short_stories[] = array('template_title' => $fb_short_stories_title,
	                         'template_body' => $fb_short_stories_body);
	  $full_stories = array('template_title' => $fb_full_stories_title,
	                         'template_body' => $fb_full_stories_body);
	  $form_id = fb_feed_registerTemplateBundle($one_line_stories,$short_stories,$full_stories);
		
	  return $form_id;
	}

	/*
	 * Display and handle updates from the Admin screen options page.
	 *
	 * @options_page
	 */
	function options_page() {
		global $wp_version, $fbconnect,$fb_reg_formfields;

			// if we're posted back an update, let's set the values here
			if ( isset($_POST['clean_log']) ) {
				unlink(FBCONNECT_PLUGIN_PATH_LOG);
			}elseif ( isset($_POST['migrate_data']) ) {
				$store =& WPfbConnect_Logic::getStore();
				$migrationresponse = $store->migration_adamplugin();
				echo '<div class="updated"><p><strong>'.__('Migration done...', 'fbconnect').'</strong></p>';
				echo $migrationresponse;
				echo '</div>';
			}elseif ( isset($_POST['info_update']) ) {
				check_admin_referer('wp-fbconnect-info_update');

				$error = '';
				update_option( 'fb_api_key', $_POST['fb_api_key'] );
				update_option( 'fb_appId', $_POST['fb_appId'] );
				update_option( 'fb_api_secret', $_POST['fb_api_secret'] );
				update_option( 'fb_enable_commentform', isset($_POST['enable_commentform']) ? true : false );
				update_option( 'fb_enable_approval', isset($_POST['enable_approval']) ? true : false );
				update_option( 'fb_add_post_share', isset($_POST['fb_add_post_share']) ? true : false );
				update_option( 'fb_add_post_head_share', isset($_POST['fb_add_post_head_share']) ? true : false );
				update_option( 'fb_like_show_faces', isset($_POST['fb_like_show_faces']) ? true : false );
				//update_option( 'fb_use_ssl', isset($_POST['fb_use_ssl']) ? true : false );
				//update_option( 'fb_wall_page', $_POST['fb_wall_page'] );
				//update_option( 'fb_short_urls', $_POST['fb_short_urls'] );
				update_option('fb_connect_avatar_link',$_POST['fb_connect_avatar_link']);
				update_option('fb_connect_use_thick',$_POST['fb_connect_use_thick']);
				update_option('fb_connect_avatar_logo',$_POST['fb_connect_avatar_logo']);
				update_option('fb_connect_comments_login',$_POST['fb_connect_comments_login']);
				$loglevel = $_POST['fb_connect_log_level'];
				update_option('fb_connect_log_level',$loglevel);				
				update_option('fb_permsToRequestOnConnect',$_POST['fb_permsToRequestOnConnect']);				
				update_option( 'fb_show_reg_form',$_POST['fb_show_reg_form'] );
				
				if ($error !== '') {
					echo '<div class="error"><p><strong>'.__('At least one of Facebook Connector options was NOT updated', 'fbconnect').'</strong>'.$error.'</p></div>';
				} else {
					echo '<div class="updated"><p><strong>'.__('Facebook Connector options updated', 'fbconnect').'</strong></p></div>';
				}

			
			}
			
			// Display the options page form
			$siteurl = get_option('home');
			if( substr( $siteurl, -1, 1 ) !== '/' ) $siteurl .= '/';
			?>
			<div class="wrap">
				<h2><?php _e('Facebook Open Graph Options', 'fbconnect') ?></h2>

				<form method="post">


					<h3><?php _e('Facebook Application Configuration', 'fbconnect') ?></h3>
     				<table class="form-table" cellspacing="2" cellpadding="5" width="100%">
						<tr valign="top">
							<th style="width: 33%" scope="row"><?php _e('Facebook App. Config.', 'fbconnect') ?></th>
							<td>
							<a href="http://developers.facebook.com/setup/" target="_blank"><?php _e('Create a new Facebook Application', 'fbconnect') ?></a><br/>
							<a href="http://www.facebook.com/developers/" target="_blank"><?php _e('Go to Facebook Apps Admin', 'fbconnect') ?></a>
							</td>
						</tr>
						<?php if( get_option('fbc_app_key_option')!="") : ?>
     					<tr valign="top">
							<th style="width: 33%" scope="row"><?php _e('Migrate user data', 'fbconnect') ?></th>
							<td>
							<input class="button-primary" type="submit" name="migrate_data" value="<?php _e('Migrate from wp-facebookconnect', 'fbconnect') ?>"/> <?php _e('(It is strongly recommended to do a backup before)', 'fbconnect'); ?>
							</td>
						</tr>
						<?php endif; ?>
						<tr valign="top">
							<th style="width: 33%" scope="row"><label for="fb_appId"><?php _e('Facebook App ID:', 'fbconnect') ?></label></th>
							<td>
							<input type="text" name="fb_appId" id="fb_appId" size="50" value="<?php echo get_option('fb_appId');?>"/>
							</td>
						</tr>
						<tr valign="top">
							<th style="width: 33%" scope="row"><label for="fb_api_key"><?php _e('Facebook API Key:', 'fbconnect') ?></label></th>
							<td>
							<input type="text" name="fb_api_key" id="fb_api_key" size="50" value="<?php echo get_option('fb_api_key');?>"/>
							</td>
						</tr>
						<tr valign="top">
							<th style="width: 33%" scope="row"><label for="fb_api_secret"><?php _e('Facebook API Secret:', 'fbconnect') ?></label></th>
							<td>
							<input type="text" name="fb_api_secret" size="50" id="fb_api_secret" value="<?php echo get_option('fb_api_secret');?>"/>
							</td>
						</tr>							
						<tr valign="top">
							<th style="width: 33%" scope="row"><label for="enable_approval"><?php _e('Automatic Approval:', 'fbconnect') ?></label></th>
							<td>
								<p><input type="checkbox" name="enable_approval" id="enable_approval" <?php 
									echo get_option('fb_enable_approval') ? 'checked="checked"' : ''; ?> />
									<label for="enable_approval"><?php _e('Enable comment auto-approval', 'fbconnect') ?></label>
							</td>
						</tr>
						<tr valign="top">
							<th style="width: 33%" scope="row"><label for="fb_add_post_share"><?php _e('Add like box:', 'fbconnect') ?></label></th>
							<td>
								<p><input type="checkbox" name="fb_add_post_head_share" id="fb_add_post_head_share" <?php 
									echo get_option('fb_add_post_head_share') ? 'checked="checked"' : ''; ?> />
									<label for="fb_add_post_head_share"><?php _e('Add Facebook like button to post head', 'fbconnect') ?></label>
							</td>
						</tr>
						<tr valign="top">
							<th style="width: 33%" scope="row"><label for="fb_add_post_share"><?php _e('Add footer like box button:', 'fbconnect') ?></label></th>
							<td>
								<p><input type="checkbox" name="fb_add_post_share" id="fb_add_post_share" <?php 
									echo get_option('fb_add_post_share') ? 'checked="checked"' : ''; ?> />
									<label for="fb_add_post_share"><?php _e('Add Facebook like button to post footer', 'fbconnect') ?></label>
							</td>
						</tr>
						<tr valign="top">
							<th style="width: 33%" scope="row"><label for="fb_like_show_faces"><?php _e('Hide faces under like:', 'fbconnect') ?></label></th>
							<td>
								<p><input type="checkbox" name="fb_like_show_faces" id="fb_like_show_faces" <?php 
									echo get_option('fb_like_show_faces') ? 'checked="checked"' : ''; ?> />
									<label for="fb_like_show_faces"><?php _e('Hide friends faces under like footer button', 'fbconnect') ?></label>
							</td>
						</tr>
						<tr valign="top">
							<th style="width: 33%" scope="row"><?php _e('Comment Form:', 'fbconnect') ?></th>
							<td>
								<p><input type="checkbox" name="enable_commentform" id="enable_commentform" <?php
								if( get_option('fb_enable_commentform') ) echo 'checked="checked"'
								?> />
									<label for="enable_commentform"><?php _e('Allow send user comments to Facebook.', 'fbconnect') ?></label></p>

							</td>
						</tr>
						<tr valign="top">
							<th style="width: 33%" scope="row"><?php _e('Show comments login:', 'fbconnect') ?></th>
							<td>
								<p><input type="checkbox" name="fb_connect_comments_login" id="fb_connect_comments_login" <?php
								if( get_option('fb_connect_comments_login') ) echo 'checked="checked"'
								?> />
									<label for="fb_connect_comments_login"><?php _e('Show Facebook login button at comments.', 'fbconnect') ?></label></p>

							</td>
						</tr>

						<tr valign="top">
							<th style="width: 33%" scope="row"><?php _e('Avatar link:', 'fbconnect') ?></th>
							<td>
								<p><input type="checkbox" name="fb_connect_avatar_link" id="fb_connect_avatar_link" <?php
								if( get_option('fb_connect_avatar_link') ) echo 'checked="checked"'
								?> />
									<label for="fb_connect_avatar_link"><?php _e('Link user avatars to Facebook profiles.', 'fbconnect') ?></label></p>

							</td>
						</tr>
						<tr valign="top">
							<th style="width: 33%" scope="row"><?php _e('Use ThickBox:', 'fbconnect') ?></th>
							<td>
								<p><input type="checkbox" name="fb_connect_use_thick" id="fb_connect_use_thick" <?php
								if( get_option('fb_connect_use_thick') ) echo 'checked="checked"'
								?> />
									<label for="fb_connect_use_thick"><?php _e('Load user info on a ThickBox.', 'fbconnect') ?></label></p>

							</td>
						</tr>
						
						<tr valign="top">
							<th style="width: 33%" scope="row"><?php _e('Avatar FB logo:', 'fbconnect') ?></th>
							<td>
								<p><input type="checkbox" name="fb_connect_avatar_logo" id="fb_connect_avatar_logo" <?php
								if( get_option('fb_connect_avatar_logo') ) echo 'checked="checked"'
								?> />
									<label for="fb_connect_avatar_logo"><?php _e('Show Facebook logo in user avatars.', 'fbconnect') ?></label></p>

							</td>
						</tr>
						<tr valign="top">
							<th style="width: 33%" scope="row"><label for="fb_permsToRequestOnConnect"><?php _e('Perms to request:', 'fbconnect') ?></label></th>
							<td>
							<input type="text" name="fb_permsToRequestOnConnect" id="fb_api_key" size="50" value="<?php echo get_option('fb_permsToRequestOnConnect');?>"/>
							<label for="fb_permsToRequestOnConnect"><?php _e('Perms to request on user first login (comma separated list) (email,user_about_me,user_birthday,user_location).', 'fbconnect') ?></label>
							</td>
						</tr>
						<tr valign="top">
							<th style="width: 33%" scope="row"><label for="fb_show_reg_form"><?php _e('Show Registration:', 'fbconnect') ?></label></th>
							<td>
								<p><input type="checkbox" name="fb_show_reg_form" id="fb_show_reg_form" <?php 
									echo get_option('fb_show_reg_form') ? 'checked="checked"' : ''; ?> />
									<label for="fb_show_reg_form"><?php _e('Show registration form', 'fbconnect') ?></label>
							</td>
						</tr>
						<tr valign="top">
							<th style="width: 33%" scope="row"><?php _e('Log level:', 'fbconnect') ?></th>
							<td>
								<select name="fb_connect_log_level" id="fb_connect_log_level">
								<option value="-1" <?php if( get_option('fb_connect_log_level')=="") echo 'selected="selected"'; ?>></option>
								<option value="1" <?php if( get_option('fb_connect_log_level')=='1' ) echo 'selected="selected"'; ?>>Emergency</option>
								<option value="2" <?php if( get_option('fb_connect_log_level')=='2' ) echo 'selected="selected"'; ?>>Error</option>
								<option value="3" <?php if( get_option('fb_connect_log_level')=='3' ) echo 'selected="selected"'; ?>>Warning</option>
								<option value="4" <?php if( get_option('fb_connect_log_level')=='4' ) echo 'selected="selected"'; ?>>Info</option>
								<option value="5" <?php if( get_option('fb_connect_log_level')=='5' ) echo 'selected="selected"'; ?>>Debug</option>
								</select>

								<a class="button" target="_blank" href="<?php echo FBCONNECT_PLUGIN_URL_LOG; ?>"><?php _e('View log', 'fbconnect') ?></a> <input class="button" type="submit" name="clean_log" value="<?php _e('Clean log', 'fbconnect') ?> &raquo;" />

							</td>
						</tr>

     				</table>

					
					<?php wp_nonce_field('wp-fbconnect-info_update'); ?>
					
     				<p class="submit"><input class="button-primary" type="submit" name="info_update" value="<?php _e('Update Configuration', 'fbconnect') ?> &raquo;" /></p>
     			</form>
				
			</div>
    			<?php
	} // end function options_page


}
endif;

?>
