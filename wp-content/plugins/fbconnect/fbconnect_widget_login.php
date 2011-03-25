<?php
/**
 * @author: Javier Reyes Gomez (http://www.sociable.es)
 * @date: 23/12/2008
 * @license: GPLv2
 */
//WPfbConnect_Logic::get_friends_data();
?> 	
<?php
$fb_user = fb_get_loggedin_user();
$user = wp_get_current_user();
$siteurl = get_option('siteurl');
//print_r(fb_user_getInfo($fb_user));
	if ( $fb_user || $user->ID) {
		echo '<div class="fbconnect_miniprofile">';
		echo $welcometext;
		echo '<div style="margin:2px;clear:both;"></div>';
		echo '<div class="fbconnect_userpicmain_cont">';
		echo '<div class="fbconnect_userpicmain">'.get_avatar( $user->ID,50 ).'</div>';
		echo '</div>';
		$linked = get_option('fb_connect_avatar_link');
		if ($linked=="on"){
				echo '<a href="http://www.facebook.com/profile.php?id='.$fb_user.'"><b>'.$user->display_name.'</b></a>';
		}else{
				echo '<a href="'.$siteurl.'/?fbconnect_action=myhome&amp;userid='.$user->ID.'"><b>'.$user->display_name.'</b></a>';
		}
		if(get_option('fb_custom_reg_form') && get_option('fb_custom_reg_form')!=""){
			echo '<br/><a href="'.$siteurl.get_option('fb_custom_reg_form').'">[ '.__('Edit profile', 'fbconnect').' ]</a>';
		}elseif(FBCONNECT_CANVAS == "web" && get_option('fb_show_reg_form') && get_option('fb_connect_use_thick')){
			echo '<br/><a class="thickbox" href="'.$siteurl.'?fbconnect_action=register&amp;height='.FBCONNECT_TICKHEIGHT.'&amp;width='.FBCONNECT_TICKWIDTH.'">[ '.__('Edit profile', 'fbconnect').' ]</a>';
		}elseif (FBCONNECT_CANVAS != "web" || get_option('fb_show_reg_form')){
			echo '<br/><a href="'.$siteurl.'?fbconnect_action=register">[ '.__('Edit profile', 'fbconnect').' ]</a>';
		}else{
				echo '<br/><a href="'.$siteurl.'/wp-admin/profile.php">[ '.__('Edit profile', 'fbconnect').' ]</a>';
		}
		
		if ( $fb_user){
			echo '<br/> <a href="'.$siteurl.'/?fbconnect_action=invite">[ '.__('Invite', 'fbconnect').' ]</a>';
		}
		if (FBCONNECT_CANVAS == "web"){
			echo '<br/><a href="#" onclick="FB.logout(function(result) { window.location = \''.$siteurl.'/?fbconnect_action=logout'.'\'; });return false;">[ '.__('Logout', 'fbconnect').' ]</a>';
		}
		echo '</div>';
		
		//echo '<div style="text-align:center;"><a onclick="FB.Connect.showBookmarkDialog()"><img src="'.FBCONNECT_PLUGIN_URL.'/images/Bookmark.png"/></a></div>';

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
		echo "<div id=\"fbloginbutton\" class=\"invitebutton\">";
		//style=\"visibility:hidden;display:none;\"
		echo __('Login with Facebook:', 'fbconnect')."<br/>";	
		/*if ( get_option('fb_show_reg_form') && get_option('fb_show_reg_form')!=""){
			echo "<fb:login-button size=\"medium\" length=\"".$loginbutton."\" onlogin=\"javascript:login_facebook2();\" ></fb:login-button>\n";
		}else{*/
		$requestperms="";	
		if (get_option('fb_permsToRequestOnConnect')!=""){
				$requestperms = 'perms="'.get_option('fb_permsToRequestOnConnect').'"';
		}
			echo "<fb:login-button ".$requestperms." size=\"medium\" length=\"".$loginbutton."\" onlogin=\"javascript:login_facebook();\" ></fb:login-button>\n";
		//}
		//echo '<a href="javascript:login_facebook();"><img src="'.FBCONNECT_PLUGIN_URL.'/images/Connect_light_medium_'.$loginbutton.'.gif" /></a>';
		echo "</div>";		
	}

?> 	
<fb:facepile></fb:facepile>