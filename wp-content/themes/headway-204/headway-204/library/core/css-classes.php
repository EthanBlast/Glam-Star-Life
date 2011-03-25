<?php
/**
 * Classes for the body element and posts.
 *
 * @package Headway
 * @subpackage CSS Classes
 **/


/**
 * Assembles the classes for the body element.
 *
 * @global object $wp_query
 * @global object $current_user
 * 
 * @return string $c The body classes.
 **/
function headway_body_class() {
	global $wp_query, $current_user;

	is_front_page()  ? $c[] = 'home'         : null;
	is_home()        ? $c[] = 'blog'         : null;
	is_date() 	     ? $c[] = 'archive'      : null;
	is_date()        ? $c[] = 'date'         : null;
	is_search()      ? $c[] = 'search'       : null;
	is_paged()       ? $c[] = 'paged'        : null;
	is_attachment()  ? $c[] = 'attachment'   : null;
	is_404()         ? $c[] = 'four-oh-four' : null;
	is_tag()		 ? $c[] = 'tag-archive'  : null;
	
	if(is_ie(6) && !headway_is_plugin_caching()){
		$c[] = 'ie6'; 
		$c[] = 'ie';
	} elseif(is_ie(7) && !headway_is_plugin_caching()){ 
		$c[] = 'ie7'; 
		$c[] = 'ie';
	} elseif(is_ie(8) && !headway_is_plugin_caching()){
		$c[] = 'ie8'; 
	} elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Safari') !== false){
		$c[] = 'safari';
	} elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox') !== false){
		$c[] = 'firefox';
	} elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') !== false){
		$c[] = 'chrome';
	} 
	
	if(strpos($_SERVER['HTTP_USER_AGENT'], 'WebKit') !== false){
		$c[] = 'webkit';
	} elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Gecko') !== false){
		$c[] = 'gecko';
	}
	
	if(headway_visual_editor_open()) $c[] = 'headway-visual-editor-open';
		
	if(headway_get_option('active-skin') && !isset($_GET['headway-skin-preview'])){
		$c[] = 'skin-'.headway_get_option('active-skin');
	} elseif(isset($_GET['headway-skin-preview']) && $_GET['headway-skin-preview'] != 'none'){
		$c[] = 'skin-'.$_GET['headway-skin-preview'];
	}
		
	$c[] = 'custom';
	$c[] = 'header-'.headway_get_skin_option('header-style');
	$c[] = 'footer-'.headway_get_skin_option('footer-style');	
		
	if ( is_single() ) {
		$postID = $wp_query->post->ID;
		the_post();

		$c[] = 'single';

		if ( $cats = get_the_category() )
			foreach ( $cats as $cat )
				$c[] = 's-category-' . $cat->slug;

		$c[] = 's-author-' . sanitize_title_with_dashes(strtolower(get_the_author_meta('login')));
		rewind_posts();
	}

	elseif ( is_author() ) {
		$author = $wp_query->get_queried_object();
		$c[] = 'author';
		$c[] = 'author-' . $author->user_nicename;
	}

	elseif ( is_category() ) {
		$cat = $wp_query->get_queried_object();
		$c[] = 'category';
		$c[] = 'category-' . $cat->slug;
	}


	elseif ( is_page() ) {
		$pageID = $wp_query->post->ID;
		$page_children = wp_list_pages("child_of=$pageID&echo=0");
		the_post();
		$c[] = 'page pageid-' . $pageID;
		$c[] = 'page-author-' . sanitize_title_with_dashes(strtolower(get_the_author('login')));
		if ( $page_children != '' )
			$c[] = 'page-parent';
		if ( $wp_query->post->post_parent )
			$c[] = 'page-child parent-pageid-' . $wp_query->post->post_parent;
		rewind_posts();
	}

	if ( $current_user->ID )
		$c[] = 'loggedin';


	$c = join( ' ', apply_filters( 'body_class',  $c ) );

	return $c;
}


/**
 * Assembles the classes for the posts.
 *
 * @global object $post
 * @global int $blog_post_alt
 * 
 * @param bool $print Determines whether or not to echo the post classes.
 * 
 * @return bool|string If $print is true, then echo the classes, otherwise just return them as a string. 
 **/
function headway_post_class($print = true) {
	global $post, $blog_post_alt, $authordata;

	$c = array( 'hentry', $post->post_type );

	$c[] = 'author-' . sanitize_title_with_dashes(strtolower($authordata->user_login));

	foreach ( (array) get_the_category() as $cat )
		$c[] = 'category-' . $cat->slug;

	if ( $post->post_password )
		$c[] = 'protected';


	if ( ++$blog_post_alt % 2 )
		$c[] = 'alt';
		
	if(is_sticky()) $c[] = 'sticky';

	$c = join( ' ', apply_filters( 'post_class', $c ) );

	return $print ? print($c) : $c;
}

$blog_post_alt = 1;