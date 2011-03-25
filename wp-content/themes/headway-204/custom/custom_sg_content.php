<?php

/*

This file was created to hold GSL functions

This is called mainly from custom_functions.php

*/

/*
*  Homepage content block
*
* $content_args is an array and has (dsb,display,no_posts)
*/
function sg_homepage_content_block($content_args) {
	_homepage_content_display(_assemble_homepage_content_block($content_args));
}
function _assemble_homepage_content_block($content_args) {
	switch($content_args['dsb']) {
		case 2;
			$dposts = sg_featured_posts($content_args['no_posts']);
			break;
		case 3;
			break;
		case 4;
			$dposts = sg_most_comments_posts($content_args['no_posts']);
			break;
		case 1:
		default:
			$dposts = sg_most_recent_posts($content_args['no_posts']);
			break;
	}
	//block header
	$bl_header = _assemble_hp_ct_header($content_args['display']);
	
	return array('bl_header'=>$bl_header,'dposts'=>$dposts);
}
/*most recent */
function sg_most_recent_posts($no_posts,$start=0) {

	global $wpdb;
	
	$request = _sg_hp_ct_common_select();
	$request .= " FROM $wpdb->posts ";
	$request .= _sg_hp_ct_common_where();
	$request .= " ORDER BY post_date DESC ";
	$request .= " LIMIT ".$start.",".$no_posts;
	$posts = $wpdb->get_results($request);

 return $posts;
}

/*most recent */
function sg_featured_posts($no_posts,$start=0) {
	$fp = get_featured_posts(array('max_posts'=>$no_posts,'method'=>'the_loop'));

	global $wpdb;
	
	$request = _sg_hp_ct_common_select();
	$request .= " FROM $wpdb->posts ";
	$request .= _sg_hp_ct_common_where();
	$request .= " AND $wpdb->posts.ID IN (".implode(',',$fp).")";
	$request .= " ORDER BY post_date DESC ";
	$request .= " LIMIT ".$start.",".$no_posts;
	$posts = $wpdb->get_results($request);

 return $posts;
}

/*most discussed */
function sg_most_comments_posts($no_posts, $start=0) {
	$show_pass_post = false;
	global $wpdb;
	
	$request = _sg_hp_ct_common_select();
	$request .= ", COUNT($wpdb->comments.comment_post_ID) AS 'comment_count' ";
	$request .= " FROM $wpdb->posts , $wpdb->comments";
	$request .= _sg_hp_ct_common_where();
	$request .= " AND comment_approved = '1' AND $wpdb->posts.ID=$wpdb->comments.comment_post_ID ";
	$request .= " GROUP BY $wpdb->comments.comment_post_ID ORDER BY comment_count DESC LIMIT $no_posts";
	
	$posts = $wpdb->get_results($request);
	
	return $posts;
} 
/* assemble the header for homepage content */
function _assemble_hp_ct_header($display) {
	foreach($display as $item) {
		if ($item[0] == 1) { $output .= _sg_ct_header($item[1],$item[2]);}
		}
	return $output;
	}
/* validate query string */
function sg_validate_hp_cb_qrystr($qs) {
	if (is_numeric($qs) && strlen($qs) == 1) { return true;}
	else {return false; }
}
/* common select for hp content */
function _sg_hp_ct_common_select() {
	return "SELECT ID, post_title, post_author, post_content ";
}
function _sg_hp_ct_common_where() {
	return " WHERE post_status = 'publish' AND post_type = 'post' AND post_password ='' ";
}
/* get image */
function _sg_getImage($num,$ID) {
global $more;
$more = 1;
$link = get_permalink($ID);
$content = get_the_content($ID);
$count = substr_count($content, '<img');
$start = 0;
for($i=1;$i<=$count;$i++) {
$imgBeg = strpos($content, '<img', $start);
$post = substr($content, $imgBeg);
$imgEnd = strpos($post, '>');
$postOutput = substr($post, 0, $imgEnd+1);
$postOutput = preg_replace('/width="([0-9]*)" height="([0-9]*)"/', '',$postOutput);;
$image[$i] = $postOutput;
$start=$imgEnd+1;
}
if(stristr($image[$num],'<img')) { echo '<a href="'.$link.'">'.$image[$num]."</a>"; }
$more = 0;
} 
