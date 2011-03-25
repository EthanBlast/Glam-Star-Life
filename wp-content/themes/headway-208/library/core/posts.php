<?php
function headway_post_meta($where, $above_below, $small_posts = false, $custom_content = false){
	global $post;

	$content_left = (is_array($custom_content) && $custom_content['left']) ? stripslashes($custom_content['left']) : stripslashes(headway_get_option('post-'.$above_below.'-'.$where.'-left'));
	$content_right = (is_array($custom_content) && $custom_content['right']) ? stripslashes($custom_content['right']) : stripslashes(headway_get_option('post-'.$above_below.'-'.$where.'-right'));	
	
	if(stripslashes(headway_get_option('post-date-format')) == '1') $date_format = 'F j, Y';
	if(stripslashes(headway_get_option('post-date-format')) == '2') $date_format = 'm/d/y';
	if(stripslashes(headway_get_option('post-date-format')) == '3') $date_format = 'd/m/y';
	if(stripslashes(headway_get_option('post-date-format')) == '4') $date_format = 'M j';
	if(stripslashes(headway_get_option('post-date-format')) == '5') $date_format = 'M j, Y';
	if(stripslashes(headway_get_option('post-date-format')) == '6') $date_format = 'F j';
	if(stripslashes(headway_get_option('post-date-format')) == '7') $date_format = 'F jS';
	if(stripslashes(headway_get_option('post-date-format')) == '8') $date_format = 'F jS, Y';
	if(stripslashes(headway_get_option('post-date-format')) == 'wp') $date_format = false;


	if(get_comments_number($post->ID) == 0) $comments_format = stripslashes(headway_get_option('post-comment-format-0'));
	if(get_comments_number($post->ID) == 1) $comments_format = stripslashes(headway_get_option('post-comment-format-1'));
	if(get_comments_number($post->ID) > 1) $comments_format = stripslashes(headway_get_option('post-comment-format'));
		$comments_format = str_replace('%num%', get_comments_number($post->ID), $comments_format);
	
	$respond_format = stripslashes(headway_get_option('post-respond-format'));
	
	$datetime = $date_format ? get_the_time($date_format) : get_the_date();

	global $authordata;

	$date = '<span class="entry-date published">'.$datetime.'</span>';
	$comments = $comments_format;
	$comments_link = '<a href="'.get_comments_link().'" title="'.get_the_title().' Comments" class="entry-comments">'.$comments.'</a>';
	$respond = '<a href="'.get_permalink().'#respond" title="Respond to '.get_the_title().'" class="entry-respond">'.$respond_format.'</a>';
	$author_no_link = $authordata->display_name;
	$author = '<a class="author-link fn nickname url" href="'.get_author_posts_url($authordata->ID).'" title="View all posts by '.$authordata->display_name.'">'.$authordata->display_name.'</a>';
	$categories = get_the_category_list(', ');
	$tags = (get_the_tags() != NULL) ? get_the_tag_list('<span class="tag-links"><span>Tags:</span> ',', ','</span>') : '';
	$edit = ( current_user_can( 'edit_post', $post->ID ) ) ? '<span class="edit"><a class="post-edit-link" href="' . get_edit_post_link( $post->ID ) . '" title="' . esc_attr( __( 'Edit post' , 'headway') ) . '">Edit</a></span>' : '';
	
	
	$meta[$where][$above_below]['left'] = $content_left;	
		$meta[$where][$above_below]['left'] = str_replace('%date%', $date, $meta[$where][$above_below]['left']);
		$meta[$where][$above_below]['left'] = str_replace('%comments%', $comments, $meta[$where][$above_below]['left']);
		$meta[$where][$above_below]['left'] = str_replace('%comments_link%', $comments_link, $meta[$where][$above_below]['left']);
		$meta[$where][$above_below]['left'] = str_replace('%respond%', $respond, $meta[$where][$above_below]['left']);
		$meta[$where][$above_below]['left'] = str_replace('%author%', $author, $meta[$where][$above_below]['left']);
		$meta[$where][$above_below]['left'] = str_replace('%author_no_link%', $author_no_link, $meta[$where][$above_below]['left']);
		$meta[$where][$above_below]['left'] = str_replace('%categories%', $categories, $meta[$where][$above_below]['left']);
		$meta[$where][$above_below]['left'] = str_replace('%tags%', $tags, $meta[$where][$above_below]['left']);
		$meta[$where][$above_below]['left'] = str_replace('%edit%', $edit, $meta[$where][$above_below]['left']);
	
	$meta[$where][$above_below]['right'] = $content_right;	
		$meta[$where][$above_below]['right'] = str_replace('%date%', $date, $meta[$where][$above_below]['right']);
		$meta[$where][$above_below]['right'] = str_replace('%comments%', $comments, $meta[$where][$above_below]['right']);
		$meta[$where][$above_below]['right'] = str_replace('%comments_link%', $comments_link, $meta[$where][$above_below]['right']);
		$meta[$where][$above_below]['right'] = str_replace('%respond%', $respond, $meta[$where][$above_below]['right']);
		$meta[$where][$above_below]['right'] = str_replace('%author%', $author, $meta[$where][$above_below]['right']);
		$meta[$where][$above_below]['right'] = str_replace('%author_no_link%', $author_no_link, $meta[$where][$above_below]['right']);
		$meta[$where][$above_below]['right'] = str_replace('%categories%', $categories, $meta[$where][$above_below]['right']);
		$meta[$where][$above_below]['right'] = str_replace('%tags%', $tags, $meta[$where][$above_below]['right']);
		$meta[$where][$above_below]['right'] = str_replace('%edit%', $edit, $meta[$where][$above_below]['right']);
	
		
		 if($meta[$where][$above_below]['left'] || $meta[$where][$above_below]['right'] ): 
			if($where == 'title'){
				$clearfix[$where][$above_below] = ' clearfix-title';
			} else {
				$clearfix[$where][$above_below] = false;
			}
			
			echo "\n".'<div class="meta-'.$above_below.'-'.$where.' entry-meta clearfix'.$clearfix[$where][$above_below].'">'."\n";
			if($meta[$where][$above_below]['left']): 
				echo '<div class="left">'."\n";
					echo $meta[$where][$above_below]['left']."\n";
				echo '</div><!-- .left -->'."\n";
			endif; 

			if($meta[$where][$above_below]['right'] && $small_posts == false): 
				echo "\n".'<div class="right">'."\n";
						echo $meta[$where][$above_below]['right']."\n";
				echo '</div><!-- .right -->'."\n";
			endif; 
			echo '</div><!-- .entry-meta -->'."\n";
		endif; 
	
}


function headway_excerpt_more(){
	return '...';
}
add_filter('excerpt_more', 'headway_excerpt_more');


function headway_nofollow_links_in_post($text){
	if(headway_get_write_box_value('nofollow_links')){
	
		preg_match_all("/<a.*? href=\"(.*?)\".*?>(.*?)<\/a>/i", $text, $links);
		$match_count = count($links[0]);
		for($i=0; $i < $match_count; $i++)
		{
			if(!preg_match("/rel=[\"\']*nofollow[\"\']*/",$links[0][$i]))
			{
				preg_match_all("/<a.*? href=\"(.*?)\"(.*?)>(.*?)<\/a>/i", $links[0][$i], $link_text);
				$text = str_replace(">".$link_text[3][0]."</a>"," rel='nofollow'>".$link_text[3][0]."</a>",$text);
			}
		}
		
		return $text;
	} else {
		return $text;
	}
}
add_action('the_content', 'headway_nofollow_links_in_post');