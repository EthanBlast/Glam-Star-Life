<?php
global $post;
global $fb_old_comments_path;
// Do not delete these lines
	if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');

	if ( post_password_required() ) { ?>
		<p class="nocomments">This post is password protected. Enter the password to view comments.</p>
	<?php
		return;
	}

if (!get_option('fb_hide_wpcomments') && get_option('fb_show_fbcomments')){
?>
<div class="fbTabs">
<ul class="tabNavigation">
    <li><a id="fbFirstCommentsA" class="selected" href="#fbFirstComments" onclick="fb_showTabComments('fbFirstComments');return false;"><?php _e('Comments', 'fbconnect'); ?></a></li>
    <li><a id="fbSecondCommentsA" href="#fbSecondComments" onclick="fb_showTabComments('fbSecondComments');return false;"><?php _e('Facebook comments', 'fbconnect'); ?></a></li>
</ul>
<div id="fbFirstComments" class="fb_commentstab">
	<?php require( $fb_old_comments_path ); ?>
</div>
<div id="fbSecondComments" style="display:none;" class="fb_commentstab">
<fb:comments xid="<?php echo $post->ID;?>" showform="false" canpost="false"> </fb:comments>
</div>
</div>
<?php
}elseif (get_option('fb_show_fbcomments')){
?>
<fb:comments xid="<?php echo $post->ID;?>"> </fb:comments>
<?php
}elseif (!get_option('fb_hide_wpcomments')){
	require( $fb_old_comments_path );
}
?>