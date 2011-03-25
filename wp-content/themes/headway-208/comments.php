<?php 
if (!empty($_SERVER['SCRIPT_FILENAME']) && basename($_SERVER['SCRIPT_FILENAME'] == 'comments.php'))
	die('Why, hello thar!  What are you doing here?');
if ( post_password_required() ) { ?>
	<p class="nocomments"><?php _e('This post is password protected.  Please enter the password to view the comments.', 'headway') ?></p>
<?php
	return;
}

if ( have_comments() ) : ?>

	<span id="comments" class="heading"><?php comments_number('No Responses', 'One Response', '% Responses' );?> <?php _e('to', 'headway') ?> <?php the_title(); ?></span>

	<ol class="commentlist">
		<?php wp_list_comments('callback='.apply_filters('headway_comments_callback', false)); ?>
	</ol>

	<div class="comments-navigation">
		<div class="alignleft"><?php paginate_comments_links() ?></div>
	</div>
	
<?php else : ?>

	<?php if ('open' == $post->comment_status) : ?>

		<p class="nocomments"><?php echo apply_filters('headway_no_comments', __('There are no comments yet.  Be the first and leave a response!', 'headway')); ?></p>
		
	<?php else : ?>

		<?php if(is_single()){ ?>
			<p class="nocomments"><?php echo apply_filters('headway_comments_closed', __('Sorry, comments are closed for this post.', 'headway')); ?></p>
		<?php } ?>

	<?php endif; ?>
	
<?php endif; ?>

<?php
if ($post->comment_status == 'open') {
?>
	<?php do_action('headway_before_comment_form', $post->ID); ?>
	
	<div id="respond">
		
	<span id="comment-form" class="heading"><?php comment_form_title(apply_filters('headway_reply_title', 'Leave a Reply'), apply_filters('headway_reply_title_specific', 'Leave a Reply to %s')); ?></span>


	<div id="cancel-comment-reply">
		<?php cancel_comment_reply_link(); ?>
	</div>

	<?php if ( get_option('comment_registration') && !$user_ID ) { ?>

		<p><?php _e('You must be logged in to post a comment.', 'headway') ?>  <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php echo urlencode(get_permalink()); ?>"><?php _e('Click here to log in.', 'headway') ?></a></p>

	<?php } else { ?>

		<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">

		<?php if ( $user_ID ) { ?>

			<p><?php echo sprintf(__('You are currently logged in as <a href="%1$s">%2$s</a>.', 'headway'), get_option('siteurl').'/wp-admin/profile.php', $user_identity); ?></p>
			
				<a href="<?php echo wp_logout_url( get_permalink() ); ?>" title="<?php _e('Log out.'); ?>" onclick="return confirm('<?php _e('Are you sure you want to logout?'); ?>');"><?php _e('Logout?', 'headway') ?></a></p>

		<?php } else { ?>

			<p>
				<label for="author"><?php _e('Name', 'headway') ?> <?php if ($req) echo '<span class="required">'.__('(required)', 'headway').'</span>'; ?></label>
				<input type="text" name="author" id="author" class="text" value="<?php echo $comment_author; ?>" size="22" tabindex="1" />
			</p>
			
			<p>
				<label for="email"><?php _e('E-Mail Address', 'headway') ?> <?php if ($req) echo '<span class="required">'.__('(required)', 'headway').'</span>'; ?></label>
				<input type="text" name="email" id="email" class="text" value="<?php echo $comment_author_email; ?>" size="22" tabindex="2" />
			</p>
			
			<p>
				<label for="url"><?php _e('Website', 'headway') ?></label>
				<input type="text" name="url" id="url" class="text" value="<?php echo $comment_author_url; ?>" size="22" tabindex="3" />
			</p>

		<?php } ?>

		<div>
			<?php comment_id_fields(); ?>
			<input type="hidden" name="redirect_to" value="<?php echo htmlspecialchars($_SERVER["REQUEST_URI"]); ?>" /></div>

			
			<p>
				<label for="comment"><?php _e('Comment', 'headway') ?></label>
				<textarea name="comment" id="comment" cols="10" rows="10" tabindex="4" class="text"></textarea>
			</p>
			
			<?php if(!headway_get_option('hide-comment-code-info')){ ?>
			<p id="comment-code-info-box">
				<?php _e('Wanting to leave an <em><a href="#" id="show-tags" onclick="document.getElementById(\'tags\').style.display = \'block\';return false;">&lt;em&gt;phasis</a></em> on your comment?', 'headway'); ?>
			</p>
			
			<p id="tags" style="display: none" class="comment-info-box">
				<?php _e('HTML is allowed in the comment box above.  You can use the following tags: <code>&lt;b&gt;&lt;/b&gt;</code>, <code>&lt;strong&gt;&lt;/strong&gt;</code>, <code>&lt;i&gt;&lt;/i&gt;</code>, <code>&lt;em&gt;&lt;/em&gt;</code>, <code>&lt;address&gt;&lt;/address&gt;</code>, <code>&lt;abbr&gt;&lt;/abbr&gt;</code>, <code>&lt;acronym&gt;&lt;/acronym&gt;</code>, <code>&lt;a href=&quot;&quot;&gt;&lt;/a&gt;</code>.', 'headway'); ?>
			</p>
			<?php } ?>

			<p><input name="submit" type="submit" id="submit" class="submit" tabindex="5" value="<?php _e('Submit Comment'); ?>" /></p>
			
			<?php do_action('comment_form', $post->ID); ?>

			</form>
	<?php 
	}
	?>
	</div>
	
	<?php if($post->ping_status == 'open' && !headway_get_option('hide-trackback-url')){ ?>
	<div id="trackback-box" class="comment-info-box">
		<span id="trackback"><?php _e('Trackback URL', 'headway') ?></span>
		<span id="trackback-url"><?php trackback_url(); ?></span>
	</div>
	<?php } ?>
	
<?php
}
?>