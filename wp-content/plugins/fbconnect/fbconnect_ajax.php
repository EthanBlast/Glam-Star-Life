<?php

require_once("../../../wp-config.php");
global $new_fb_user;
global $fb_ajaxcall;
$fb_ajaxcall = true;

$wall_page = get_option('fb_wall_page');
$user = wp_get_current_user();
//print_r($user);
$fb_user = fb_get_loggedin_user();

if (isset($_GET['checklogin'])){
	if ($fb_user!=""){
			$userprofile = WPfbConnect_Logic::get_userbyFBID($fb_user);
		   if (isset($userprofile) && $new_fb_user && (!isset($_GET['hide_regform']) || $_GET['hide_regform']=="false") && isset($_GET['login_mode']) && $_GET['login_mode']=="themeform"){
		   			$profileurl = get_option('siteurl')."/?fbconnect_action=myhome&amp;userid=%USERID%";
					$fb_custom_reg_form = get_option('fb_custom_reg_form');
					if (isset($fb_custom_reg_form) && $fb_custom_reg_form!=""){
						$profileurl = get_option('siteurl').$fb_custom_reg_form;
					}
					$profileurl = str_replace('%USERID%',$user->ID,$profileurl);
					echo "<script type='text/javascript'>\n";
					//echo "alert('".get_option('siteurl')."/ver_usuario/?idUsuSing=".$user->ID."');\n";
					echo "window.location= '".$profileurl."';\n";
					echo "</script>";
			}elseif(isset($userprofile) && $userprofile!=""){
				WPfbConnect_Logic::set_lastlogin_fbconnect($userprofile->ID);
				global $current_user;
				$current_user = null;
				WPfbConnect_Logic::fb_set_current_user($userprofile);
				if (isset($_REQUEST['refreshpage']) && $_REQUEST['refreshpage']=="fbconnect_refresh_commentslogin"){
					if (isset($_REQUEST['fbstatus_postid'])){
						global $postID;
						$postID = $_REQUEST['fbstatus_postid'];
					}
					$show_refresh = true;
					include(FBCONNECT_PLUGIN_PATH.'/pro/fbconnect_widget_logincomm.php');
					
				}elseif (isset($_GET['refreshpage']) && $_GET['refreshpage']!=""){
?>				
					<div class="fbconnect_miniprofile">
						<div class="fbconnect_userpicmain"><fb:profile-pic uid="<?php echo $fb_user; ?>" size="square" facebook-logo="true" linked="false"></fb:profile-pic></div>
						<div>
						<?php echo $user->display_name;?>
						<?php if (isset($_GET['links']) && $_GET['links']=="all"){ ?>	
							<br/> <a href="<?php echo get_option('siteurl'); ?>/?fbconnect_action=invite">[ Invita a tus amigos ]</a>
							<br/><a href="<?php echo get_option('siteurl'); ?>/?fbconnect_action=community">[ Ver amigos Facebook ]</a>
						<?php }else{
							echo "<br/>";
						} ?>	
						<br/><a href="#" onclick="FB.Connect.logout(function(result) { window.location = '<?php echo get_option('siteurl'); ?>/?fbconnect_action=logout'; })">[ Desconectar ]</a>
						</div>
						
					</div>
	
					<script type='text/javascript'>
						FB.XFBML.Host.parseDomTree();
						<?php echo $_GET['refreshpage'];?>('<?php echo $fb_user;?>' , '<?php echo $_SESSION["facebook_usersinfo"]["first_name"]; ?>', '<?php echo $_SESSION["facebook_usersinfo"]["birthday_date"]; ?>');
					</script>
<?php			}else{
?>
					<script type='text/javascript'>
						window.location.reload(true);
					</script>
<?php			}
			}else if(!isset($userprofile) || $userprofile==""){	
?>
					<script type='text/javascript'>
						tb_show("Registration", "<?php echo get_option('siteurl')."?fbconnect_action=register&height=380&width=380&modal=true"; ?>", "");
					</script>
<?php				
				}
	}
}elseif (isset($_GET['logout'])){
	wp_logout();
	$show_refresh = true;
	if (isset($_REQUEST['fbstatus_postid'])){
		global $postID;
		$postID = $_REQUEST['fbstatus_postid'];
	}
	include(FBCONNECT_PLUGIN_PATH.'/pro/fbconnect_widget_logincomm.php');
}elseif (isset($_GET['refresh'])){
	nocache_headers();
	global $comment_post_ID;
	$comment_post_ID=$wall_page; 
	if (isset($_REQUEST['fbstatus_postid']) && $_REQUEST['fbstatus_postid']!=""){
		$comment_post_ID= $_REQUEST['fbstatus_postid'];
	}
	global $fbconnect_page;
	global $fbconnect_filter;
	$fbconnect_page = $_GET['refresh'];
	$fbconnect_filter = $_GET['filter'];
	if(file_exists (TEMPLATEPATH.'/fbconnect_feed.php')){
		include( TEMPLATEPATH.'/fbconnect_feed.php');
	}else{
		include( FBCONNECT_PLUGIN_PATH.'/fbconnect_feed.php');
	}
}else if ($_POST['submit_status']){
	if (isset($_POST['fbstatus_postid']) && $_POST['fbstatus_postid']!=""){
		$comment_post_ID= $_POST['fbstatus_postid'];
	}else{
		$comment_post_ID=$wall_page; 
	}
	$actual_post=get_post($comment_post_ID);

	if (!$comment_post_ID || !$actual_post || ($comment_post_ID!=$actual_post->ID) )
	{
		echo 'Sorry, there was a problem. Please try again.';
		exit;
	}


	$comment_author       = "";
	$comment_content      = substr(strip_tags(trim($_POST['fbstatus_comment'])),0,5000);
	$comment_author_email = "";

	if ( $user->ID ) {
	  $comment_author  = $wpdb->escape($user->display_name);		
	  $comment_author_email = $wpdb->escape($user->user_email);
	}else{
		echo 'Sorry, you must be logged in to post a comment.';
		exit;
	}

	if ( '' == $comment_content ){
		echo 'Please type a comment.';
		exit;
	}
	
		// insert the comment
	$comment_type = "";
	$commentdata = compact('comment_post_ID', 'comment_author', 'comment_author_email', 'comment_content', 'user_ID','comment_type');
	$_REQUEST["sendToFacebook"] = "on";
	$comment_id = wp_new_comment( $commentdata );
	if (isset($_REQUEST["sendToFacebookAjax"]) && $_REQUEST["sendToFacebookAjax"]!=""){
		$template_data = $_SESSION["template_data"];
		$fb_user = fb_get_loggedin_user();
		if ($fb_user!=""){
			$result = fb_stream_publish($comment_content,fb_json_encode($template_data["attachment"]),fb_json_encode($template_data["action_links"]));
			if ($result=="ERROR"){
				fb_users_setStatus($comment_content,$fb_user);
			}
		}
		$_SESSION["template_data"] = "";
	}
		
	nocache_headers();
	if(file_exists (TEMPLATEPATH.'/fbconnect_feed.php')){
		include( TEMPLATEPATH.'/fbconnect_feed.php');
	}else{
		include( FBCONNECT_PLUGIN_PATH.'/fbconnect_feed.php');
	}
}
?>
<?php if ($show_refresh) : ?>
<script type='text/javascript'>
		FB.XFBML.Host.parseDomTree();
</script>
<?php endif; ?>


