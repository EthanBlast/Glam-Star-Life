<div id="fbconnect_widget_div">
		<div style="position:relative;text-align:right;">
	<img id="fbconnect_pinned" src="<?php echo FBCONNECT_PLUGIN_URL;?>/images/maxim.gif"/>
</div>
<?php

	$fb_user = fb_get_loggedin_user();

	$user = wp_get_current_user();
		
	$siteurl = get_option('siteurl');
	
	$uri = "";
	if (isset($_SERVER["REQUEST_URI"])){
			$uri = $_SERVER["REQUEST_URI"];			
	}
		
	if ( $fb_user && $user->ID) {
		echo '<div class="fbconnect_sidebar_miniprofile">';
		echo '<div class="fbconnect_userpicmain">';
		echo get_avatar( $user->ID,32 );
		echo '</div>';

		echo '<br/><a style="font-weight: bold;" href="'.$siteurl.'/?fbconnect_action=myhome&amp;userid='.$user->ID.'">'.$user->display_name.'</a>';

		if ( get_option('fb_show_reg_form') && get_option('fb_show_reg_form')!=""){
			echo '<br/><a href="#" onclick="show_regform();">'.__('Edit profile', 'fbconnect').'</a> |';
		}else{
			echo '<br/><a href="'.$siteurl.'/wp-admin/profile.php">'.__('Edit profile', 'fbconnect').'</a> |';
		}
		echo '<a href="'.$siteurl.'/?fbconnect_action=invite">'.__('Invite', 'fbconnect').'</a> | ';
		echo '<a href="#" onclick="FB.Connect.logout(function(result) { window.location = \''.$siteurl.'/?fbconnect_action=logout'.'\'; })">'.__('Logout', 'fbconnect').'</a>';
		
		echo "</div>";

	}else if ( $user->ID ) {
		echo '<form action="'.FBCONNECT_PLUGIN_URL.'/fbconnect_ajax.php" method="post" id="fbstatusform">';
		echo '<input type="hidden" name="fbstatus_postid" id="fbstatus_postid" value="'.WPfbConnect_Logic::get_status_postid().'"/>';
		echo '</form>';
		echo '<div class="fbconnect_miniprofile">';
		echo '<div class="fbconnect_userpicmain"><a onclick="location.href=\''.$siteurl.'/?fbconnect_action=myhome&amp;userid='.$user->ID.'\';" href="'.$siteurl.'/?fbconnect_action=myhome&amp;userid='.$user->ID.'"><fb:profile-pic uid="'.$user->fbconnect_userid.'" size="thumb" linked="false"></fb:profile-pic></a></div>';
		echo '<p>'.$welcometext;
		echo '<br/><a href="'.$siteurl.'/?fbconnect_action=myhome&amp;userid='.$user->ID.'">'.$user->display_name.'</a> |';
		if (get_option('fb_show_reg_form')){
			echo '<br/><a href="#" onclick="show_regform();">'.__('Edit profile', 'fbconnect').'</a> |';
		}else{
			echo '<br/><a href="'.$siteurl.'/wp-admin/profile.php">'.__('Edit profile', 'fbconnect').'</a> |';
		}
		echo '<br/><a href="'.$siteurl.'/?fbconnect_action=logout'.'">'.__('Logout', 'fbconnect').'</a>';
		echo '</p>';
		echo '</div>';
	}else{
		echo '<form action="'.FBCONNECT_PLUGIN_URL.'/fbconnect_ajax.php" method="post" id="fbstatusform">';
		echo '<input type="hidden" name="fbstatus_postid" id="fbstatus_postid" value="'.WPfbConnect_Logic::get_status_postid().'"/>';
		echo '</form>';
	}
	
	if ($fb_user && $user->ID){
		//echo '<div><fb:prompt-permission title="We don\'t store your email, and you can stop the notifications from Facebook" perms="email" class="FB_ElementReady"/><img src="'.FBCONNECT_PLUGIN_URL.'/images/sobre.gif"/> Allow notifications?</fb:prompt-permission></div>';
		
		//echo "<input type=\"button\" value=\"".$invitetext."\" style=\"width:100%;\" onclick=\"location.href='".$siteurl."/?fbconnect_action=invite'\"/>";
		//echo '<div id="fbinvitebutton"><a href="'.$siteurl.'/?fbconnect_action=invite">'.$invitetext.'</a></div>';
	}elseif (WPfbConnect_Logic::getMobileClient()!=""){
		echo "<div class=\"invitebutton\">";
		echo __('Login with Facebook:', 'fbconnect')."<br/>";	
		echo '<a href="'.fb_get_fbconnect_tos_url().'"><img src="'.FBCONNECT_PLUGIN_URL.'/images/Connect_with_facebook_iphone.png" /></a>';
		echo "</div>";
	}else{
		echo "<div class=\"invitebutton\">";
		echo __('Login with Facebook:', 'fbconnect')."<br/>";	
		if ( get_option('fb_show_reg_form') && get_option('fb_show_reg_form')!=""){
			echo "<fb:login-button size=\"medium\" length=\"".$loginbutton."\" onlogin=\"javascript:login_facebook2();\" ></fb:login-button>\n";
		}else{
			echo "<fb:login-button size=\"medium\" length=\"".$loginbutton."\" onlogin=\"javascript:login_facebook();\" ></fb:login-button>\n";
		}
		//echo '<a href="javascript:login_facebook();"><img src="'.FBCONNECT_PLUGIN_URL.'/images/Connect_light_medium_'.$loginbutton.'.gif" /></a>';
		echo "</div>";		
	}

?> 	
-------------------	
<ul>
<?php 	
dynamic_sidebar("FB_Community_Sidebar");
?>
</ul>
</div>
