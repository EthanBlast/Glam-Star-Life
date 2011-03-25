<?php 
if(!function_exists("_e"))
{
	echo "Error";
}
else
{
?>
<a target="_blank" href="options-general.php?page=gigya-socialize&help=1#widget"><?php _e( 'Get Help' ); ?></a>
<p><label for="gs-for-wordpress-title"><?php	_e( 'Title' ); ?>: <input class="widefat" id="gs-for-wordpress-title" name="gs-for-wordpress-title" type="text" value="<?php echo $title; ?>" /></label><br /><small><?php _e( 'This title is only displayed if the user is not logged in.' ); ?></small></p>
<p><label for="gs-for-wordpress-login-message"><?php	_e( 'WordPress Login Text' ); ?>: <input class="widefat" id="gs-for-wordpress-login-message" name="gs-for-wordpress-login-message" type="text" value="<?php echo $wordpressHeader; ?>" /></label><br /><small><?php _e( 'This text is shown above the WordPress login link.' ); ?></small></p>
<p><label for="gs-for-wordpress-socialize-login-message"><?php	_e( 'Socialize Login Text' ); ?>: <input class="widefat" id="gs-for-wordpress-socialize-login-message" name="gs-for-wordpress-socialize-login-message" type="text" value="<?php echo $socializeHeader; ?>" /></label><br /><small><?php _e( 'This text is shown above the Socialize login component provider icons.' ); ?></small></p>
<p><label for="gs-for-wordpress-invite-friends-message"><?php	_e( 'Invite Friends Text' ); ?>: <input class="widefat" id="gs-for-wordpress-invite-friends-message" name="gs-for-wordpress-invite-friends-message" type="text" value="<?php echo $inviteFriends; ?>" /></label><br /><small><?php _e( 'This text is used to prompt individuals to invite their friends to the site.' ); ?></small></p>
<input type="hidden" id="gs-for-wordpress-submit" name="gs-for-wordpress-submit" value="1" />

<?php } ?>