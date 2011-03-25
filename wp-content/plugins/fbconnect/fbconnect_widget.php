<?php
/**
 * @author: Javier Reyes Gomez (http://www.sociable.es)
 * @date: 23/12/2008
 * @license: GPLv2
 */
$fb_user = fb_get_loggedin_user();
/*<fb:loginbutton></fb:loginbutton>
<fb:social-bar></fb:social-bar>
<fb:connect-bar></fb:connect-bar>
<fb:connect-bar autorefresh="true" onNotDisplay="alert('NO');" onClose="alert('close');" onDisplay="alert('display')"></fb:connect-bar>

<fb:add-profile-tab></fb:add-profile-tab>*/
//print_r(fb_get_objectinfo("http://www.sociable.es"));
?> 	
<div id="fbconnect_widget_div" >
	<?php echo $before_title . $title . $after_title; ?>
	<div style="position:relative;text-align:right;">
	<a href="#" onclick="pinnedChange();return false;"><img id="fbconnect_pinned" src="<?php echo FBCONNECT_PLUGIN_URL;?>/images/maxim.gif"/></a>
</div>
<?php

	include("fbconnect_widget_login.php");

?> 	


<div class="fbTabs">
        <ul class="tabNavigation">
            <li><a id="fbFirstA" class="selected" href="#fbFirst" onclick="fb_showTab('fbFirst');return false;"><?php _e('Visitors', 'fbconnect'); ?></a></li>
            <li><a id="fbSecondA" href="#fbSecond" onclick="fb_showTab('fbSecond');return false;"><?php _e('Friends', 'fbconnect'); ?></a></li>
			<li><a id="fbThirdA" href="#fbThird" onclick="fb_showTab('fbThird');return false;"><?php _e('Comments', 'fbconnect'); ?></a></li>
        </ul>

	<div id="fbFirst" class="fbtabdiv">
	<div class="fbconnect_LastUsers">
	<div class="fbconnect_title"><?php _e('Last visitors', 'fbconnect'); ?></div>
	<div class="fbconnect_userpics">
	
<?php
	foreach($users as $las_user){
			echo get_avatar( $las_user->ID,50 );
	}

	echo '</div>';
	echo '<div style="text-align:right;">';
	if(get_option('fb_connect_use_thick')){
		echo '<a title="'.__("Community","fbconnect").'" class="thickbox" href="'.$siteurl.'/?fbconnect_action=community&amp;height=400&amp;width=450">'.__('view more...', 'fbconnect').' </a>';
	}else{
		echo '<a href="'.$siteurl.'/?fbconnect_action=community'.'">'.__('view more...', 'fbconnect').' </a>';
	}
	echo '</div>';
	echo '</div>';
	echo '</div>';
		
	echo '<div id="fbSecond" style="display:none;visibility:hidden;" class="fbtabdiv">';
	echo '<div class="fbconnect_LastUsers">';
	echo '<div class="fbconnect_title">'.__('Friends on this site', 'fbconnect').'</div>';

	if(isset($fb_user) && $fb_user!=""){
		//$friends = WPfbConnect_Logic::get_connected_friends();
		$friends = WPfbConnect_Logic::get_friends($user->ID,0,$maxlastusers);
		if(count($friends)>0){
			echo '<div class="fbconnect_userpics">';
			foreach($friends as $user){
						echo get_avatar( $user->ID,50 );
			}
		}else{
			echo '<div>';
			_e("You don't have friends on this site", 'fbconnect');
			echo ': <b><a href="'.$siteurl.'/?fbconnect_action=invite">'.__('Invite your friends!', 'fbconnect').'</a> </b> ';
		}
	}else{
		echo '<div>';
		_e("To see your friends on this site, you must be logged in with Facebook:", 'fbconnect');
		echo "<fb:login-button size=\"medium\" length=\"short\" onlogin=\"window.location = '".$uri."';\"></fb:login-button>\n";
	}

	echo '</div>';
	echo '<div style="text-align:right;"><a href="'.$siteurl.'/?fbconnect_action=community'.'">'.__('view more...', 'fbconnect').' </a></div>';
	echo '</div>';
	echo '</div>';

	echo '<div id="fbThird" style="display:none;visibility:hidden;" class="fbtabdiv">';
	echo '<div id="fbconnect_feedhead">';
	echo '<div class="fbTabs_feed">';
	echo '        <ul class="tabNavigation_feed">';
	if(isset($fb_user) && $fb_user!=""){
		echo '<li><a id="fbAllFriendsCommentsA" href="#fbAllFriendsComments" onclick="fb_showComments(\'fbAllFriendsComments\');return false;">'.__('Friends', 'fbconnect').'</a> </li>';
		echo '<li><a id="fbAllCommentsA" class="selected" href="#fbAllComments" onclick="fb_showComments(\'fbAllComments\');return false;">'.__('Full site', 'fbconnect').'</a></li>';	
	}
	echo '</ul>	';
	echo '</div>';
	echo '</div>';

	echo '<div id="fbAllComments" class="fbconnect_LastComments">';
	global $fbconnect_filter;
	$fbconnect_filter="fbAllComments";
	include( FBCONNECT_PLUGIN_PATH.'/fbconnect_feed.php');
	echo '</div>';
	if(isset($fb_user) && $fb_user!=""){
		echo '<div id="fbAllFriendsComments" style="display:none;visibility:hidden;" class="fbconnect_LastComments">';
		$fbconnect_filter="fbAllFriendsComments";
		include( FBCONNECT_PLUGIN_PATH.'/fbconnect_feed.php');
		echo '</div>';
	}
	echo '</div>';
?> 
</div>
<div style="font-size:9px;color:#404040;"><?php _e('Powered by', 'fbconnect'); ?> <a href="http://www.sociable.es">Sociable!</a></div>
</div>
<script type="text/javascript">
	fb_showTab('fbFirst');
</script>