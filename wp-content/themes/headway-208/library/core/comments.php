<?php
/**
 * Callbacks functions and filters for the comment display.
 *
 * @package Headway
 * @subpackage Comments
 **/


/**
 * Filter that removes nofollow from the comment author URLs.
 *
 * @param string $url URL to be filtered.
 * 
 * @return string $url URL after being filtered.
 **/
function headway_comment_rel_nofollow($url) {
	if(headway_get_option('nofollow-comment-author')) return $url;
	
	$url = str_replace("rel='external nofollow'","rel='external'", $url);
	return $url;
}
add_filter('get_comment_author_link', 'headway_comment_rel_nofollow');


/**
 * Callback function for listing pingbacks.
 *
 * @param object $comment
 * @param array $args
 * @param int $depth
 *
 * @return void
 **/
function headway_list_pings($comment, $args, $depth) {
       $GLOBALS['comment'] = $comment;
	   if(get_comment_ID()-1 & 1){
		 $class_ping = ' class="alt"';
	   }
	   
?>
        <li id="comment-<?php comment_ID(); ?>"<?php echo $class_ping?>><?php comment_author_link(); ?>
<?php 
} 

/**
 * Callback function for listing comments.
 *
 * @param object $comment
 * @param array $args
 * @param int $depth
 * 
 * @return void
 **/
function headway_list_comments($comment, $args, $depth) {
       $GLOBALS['comment'] = $comment;
?>
        <li <?php comment_class() ?> id="comment-<?php comment_ID() ?>">
				<div id="div-comment-<?php comment_ID() ?>">
					<div class="comment-meta">
						<span class="comment-author"><?php echo get_comment_author_link()?></span>
						
						<?php if(!headway_get_option('hide-comment-dates')){ ?>
						<div class="comment-date"><?php printf('%1$s | %2$s', get_comment_date('F j, Y'),  get_comment_time()) ?><?php edit_comment_link('Edit', '&nbsp;&nbsp;<span class="comment-edit">', '</span>') ?></div>
						<?php } ?>
						
						<?php 
							$size = (headway_get_option('avatar-size')) ? str_replace('px', '', strtolower(headway_get_option('avatar-size'))) : '48';
							$avatar = (headway_get_option('default-avatar')) ? get_avatar( get_comment_author_email(), $size, headway_get_option('default-avatar') ) :  get_avatar( get_comment_author_email(), $size );
					 		echo (headway_get_option('show-avatars')) ? $avatar : NULL; 
						?>
						
					</div> 
					<div class="comment-body">
					
							<?php if ($comment->comment_approved == '0') : ?>
									<p class="unapproved">Your comment is awaiting moderation.</p>
							<?php endif; ?>    


						<?php comment_text() ?>
				

						<div class="reply">
							<?php comment_reply_link(array_merge( $args, array('add_below' => 'div-comment', 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
						</div>
					</div>   
					<div class="clear"></div>
				</div> 
				
<?php 
}


function headway_default_comments_callback(){
	return 'headway_list_comments';
}
add_filter('headway_comments_callback', 'headway_default_comments_callback');