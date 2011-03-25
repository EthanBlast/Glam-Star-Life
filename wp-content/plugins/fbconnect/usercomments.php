<?php 
		$fb_user ="";

		if (isset($_REQUEST['userid']) && $_REQUEST['userid']!=""){
			$fb_user = $_REQUEST['userid'];
			$comments = WPfbConnect_Logic::get_user_comments_byID($_REQUEST['userid']);
		}elseif (isset($_REQUEST['fbuserid']) && $_REQUEST['fbuserid']!=""){
			$comments = WPfbConnect_Logic::get_user_comments($_REQUEST['fbuserid']);
		}elseif(isset($_REQUEST['fb_sig_profile_user']) && $_REQUEST['fb_sig_profile_user']!=""){
			$comments = WPfbConnect_Logic::get_user_comments($_REQUEST['fb_sig_profile_user']);
		}else{
			global $userprofile;
			$comments = WPfbConnect_Logic::get_user_comments_byID($userprofile->ID);
		}
?>
<?php if ($comments) : ?>

	<ol class="commentlist">
	<?php global $comment;?>
	<?php foreach ($comments as $comment) : ?>

		<li <?php echo $oddcomment; ?>id="comment-<?php comment_ID() ?>">
			<h3 class="entry-title" style="margin-top: 5px;margin-bottom: 5px;"><a target="_blank" href="<?php echo get_permalink($comment->comment_post_ID) ?>" rel="bookmark" title="Permanent Link to <?php echo $comment->post_title;?>"><?php echo $comment->post_title;?></a></h3>
			
			<small class="commentmetadata"><a href="#comment-<?php comment_ID() ?>" title=""><?php comment_date('F jS, Y') ?> at <?php comment_time() ?></a> <?php edit_comment_link('edit','&nbsp;&nbsp;',''); ?></small>

			<?php comment_text() ?>

		</li>

	<?php
		/* Changes every other comment to a different class */
		$oddcomment = ( empty( $oddcomment ) ) ? 'class="alt" ' : '';
	?>

	<?php endforeach; /* end for each comment */ ?>

	</ol>
<?php endif; ?>