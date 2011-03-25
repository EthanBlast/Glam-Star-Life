<?php
    global $fbconnect_page; 
    global $fbconnect_filter;
	if (!isset($fbconnect_page))
		$fbconnect_page =0;
	global $comment_post_ID;

	if ($fbconnect_filter=="fbAllComments" || $fbconnect_filter=="fbAllFriendsComments"){
		$comment_post_ID="";
	}else if (!isset($comment_post_ID)){
		$comment_post_ID = WPfbConnect_Logic::get_status_postid();
		if ($comment_post_ID=="")
			$comment_post_ID=get_option('fb_wall_page');	//<----- LEER DE CONFIGURACIÓN
	}
	$user = wp_get_current_user();
	
	if ($fbconnect_filter=="fbFriendsComments" || $fbconnect_filter=="fbAllFriendsComments"){
		$count = WPfbConnect_Logic::count_post_friends_comments($user->ID,$comment_post_ID);
	}else{ 
		$count = WPfbConnect_Logic::count_post_comments($comment_post_ID);
		//$count = wp_count_comments($comment_post_ID);
	}
	$maxcomments = 5; //max comments per page
	if ($count > $maxcomments){
			$nav=1;
			$pages=ceil($count/$maxcomments)-1;
	}else {
		$nav=0;
		$pages=0;
	}
	

	$limit=$maxcomments*$fbconnect_page;
	$cursorposition = $limit+$maxcomments;
	if ($cursorposition > $count){
		$cursorposition = $count;
	}
	//echo '<div id="commentscount">{'.$cursorposition.'/'.$count.' comments}</div>';	
	if ($fbconnect_filter=="fbFriendsComments" || $fbconnect_filter=="fbAllFriendsComments"){
		$comments = WPfbConnect_Logic::get_post_friends_comments($user->ID,$maxcomments,$comment_post_ID,$limit);
	}else{
		$comments = WPfbConnect_Logic::get_post_comments($maxcomments,$comment_post_ID,$limit);
	}
	/* This variable is for alternating comment background */
	$oddcomment = 'class="fbalt" ';
?>

<?php if ($comments) : ?>
	<ol style="list-style: none !important;margin:0px;">
	<?php global $comment;?>
	<?php foreach ($comments as $comment) : ?>

		<li style="list-style: none !important;" <?php echo $oddcomment; ?> id="comment-<?php comment_ID() ?>">
			<div class="fb_userpic">
			<?php
			echo get_avatar( $comment, 30 ); ?>
			</div>
			<?php if ($fbconnect_filter=="fbAllFriendsComments" || $fbconnect_filter=="fbAllComments"): ?>
			<b><a href="<?php echo get_permalink($comment->comment_post_ID) ?>" rel="bookmark" title="Permanent Link to <?php echo $comment->post_title;?>"><?php echo $comment->post_title;?></a></b>
			<br/>
			<?php endif; ?>
			<cite><?php _e('By', 'fbconnect'); ?> <?php comment_author_link() ?></cite>
			<br/>
			<small class="commentmetadata">
				<?php comment_date('d/m/Y') ?> <?php comment_time() ?>
			</small>
			<?php comment_text() ?>
		</li>

	<?php
		/* Changes every other comment to a different class */
		$oddcomment = ( $oddcomment != 'class="fbalt" ' ) ? 'class="fbalt" ' : 'class="fbalt2" ';
	?>

	<?php endforeach; /* end for each comment */ ?>

	</ol>
<?php endif; ?>
<?php
echo '<input type="hidden" name="fbconnect_pageleft" id="fbconnect_pageleft" value="'.(($fbconnect_page > 0) ? ($fbconnect_page-1) : $fbconnect_page).'" />';
echo '<input type="hidden" name="fbconnect_pageright" id="fbconnect_pageright" value="'.(($fbconnect_page < $pages) ? ($fbconnect_page+1) : $fbconnect_page).'" />';
?>