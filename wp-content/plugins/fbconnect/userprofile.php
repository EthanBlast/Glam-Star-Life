<?php
	global $userprofile;
	$userprofile = WPfbConnect_Logic::get_user();
	$sizeimg="small";
	if(get_option('fb_connect_use_thick') && FBCONNECT_CANVAS=="web"){
			$sizeimg="square";
			$style='style="border:0;line-height:14px;color:#404040;font-size:10px;font-family:"Arial,Lucida Grande",Verdana,sans-serif;"';
	}
?>	
	
<div <?php echo $style;?> class="fbconnect_userprofile">
	<div style="min-height:55px;margin-right:5px;" class="alignleft fbconnect_userpicmain">
		<fb:profile-pic uid="<?php echo $userprofile->fbconnect_userid;?>" size="<?php echo $sizeimg;?>" linked="true"></fb:profile-pic>
	</div>

	    <b><?php _e('Status:', 'fbconnect') ?></b> <fb:user-status uid="<?php echo $userprofile->fbconnect_userid;?>" linked="true"></fb:user-status>
		<br/><b><?php _e('Name:', 'fbconnect') ?> </b><?php echo $userprofile->display_name; ?>
<!--		<br/><b><?php _e('Nickname:', 'fbconnect') ?> </b><?php echo $userprofile->nickname; ?> -->
		<br/><b><?php _e('Member since:', 'fbconnect') ?> </b><?php echo $userprofile->user_registered; ?>
		<br/><b><?php _e('Website URL:', 'fbconnect') ?> </b><a href="<?php echo $userprofile->user_url; ?>" rel="external nofollow"><?php echo $userprofile->user_url; ?></a>
		<br/><b><?php _e('About me:', 'fbconnect') ?> </b><?php echo $userprofile->description; ?><br/>
		<?php if (isset($userprofile->fbconnect_userid) && $userprofile->fbconnect_userid!="" && $userprofile->fbconnect_userid!="0") : ?>
			<br/><b><a target="_blank" href="http://www.facebook.com/profile.php?id=<?php echo $userprofile->fbconnect_userid; ?>"><img class="icon-text-middle" src="<?php echo FBCONNECT_PLUGIN_URL; ?>/images/facebook_24.png"/><?php _e('Facebook profile', 'fbconnect') ?> </a></b>
			<a href="#" onclick="FB.Connect.showAddFriendDialog(<?php echo $userprofile->fbconnect_userid; ?>, null);">[ Add as friend ]</a>
		<?php endif; ?>
	
</div>
