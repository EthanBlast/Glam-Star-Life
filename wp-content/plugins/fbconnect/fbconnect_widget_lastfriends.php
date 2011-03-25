<?php
/**
 * @author: Javier Reyes Gomez (http://www.sociable.es)
 * @date: 23/12/2008
 * @license: GPLv2
 */
?> 	

<div class="fbconnect_LastUsers">
	<div class="fbconnect_title"><?php echo $welcometext; ?></div>
	
<?php

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
		if  ( FBCONNECT_CANVAS=="web") {
			_e("To see your friends on this site, you must be logged in with Facebook:", 'fbconnect');
			echo "<fb:login-button size=\"medium\" length=\"short\" onlogin=\"window.location = '".$uri."';\"></fb:login-button>\n";
		}
	}

	echo '</div>';
	echo '<div style="text-align:right;"><a href="'.$siteurl.'/?fbconnect_action=community'.'">'.__('view more...', 'fbconnect').' </a></div>';
?> 
</div>
