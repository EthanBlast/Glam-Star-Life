<?php

/*

Store all of your custom functions, actions, and so on in this file.



For more on hooks go here: http://headwaythemes.com/documentation/customizing-your-headway-site/using-hooks/



**Here are examples of an action and filter.**



add_action('some_action', 'an_example_action_function');

function an_example_action_function(){

	echo 'This will echo something.';

}



add_filter('some_filter', 'an_example_filter_function');

function an_example_filter_function($content){

	return $content;

}



///////////////////////////////



add_action('init', 'register_custom_stuff');

function register_custom_stuff(){

    //Add a custom element to be styled with the visual editor.

	headway_register_custom_element(array('selector' => 'div#footer a:hover', 'name' => 'Footer &mdash; Hyperlinks (Hover)', 'color_options' => array('color'), 'fonts' => true));

	

	//Add two custom font families to be used by the visual editor.

	headway_register_custom_font('Futura', 'Futura, "Century Gothic", AppleGothic, sans-serif');

	headway_register_custom_font('Geneva', 'Lucida Sans, "Lucida Grande", "Lucida Sans Unicode", Verdana, sans-serif');

}

*/

/*
* function call to homepage main content
*/
function gsl_homepage_content_block() {
	include_once('custom_sg_content.php');
	$no_posts = 10;
	$dsb = sg_validate_hp_cb_qrystr($_GET['d']) ? $_GET['d'] : 1;
	$display = array('recent'=>array('1','LATEST',1),
					'featured'=>array('1','FEATURED',2),
					'popular'=>array('0','MOST POPULAR',3),
					'comments'=>array('1','MOST DISCUSSED',4),
					);
	$content_args = array('dsb'=>$dsb,'display'=>$display,'no_posts'=>$no_posts);
	sg_homepage_content_block($content_args);
}
/*
* function to display the homepage main content
*/
function _homepage_content_display($content) {
//var_dump($content);
	//include_once('wp-content/themes/headway-204/library/leafs/content.php');
	
	print '<div id = "ct-box">';
	print '<div class="ct-header-box">'.$content['bl_header'];
	print '<span class="ct-header-link">';
	if (function_exists('post_from_site')) {post_from_site();}
	print '</span></div>';
	
	if ($content['dposts']) {
		 global $post;
		 foreach ($content['dposts'] as $post) {
			setup_postdata($post);
			
			echo "\n".'<div id="post-'.get_the_id().'" class="post post-ct '.headway_post_class(false).' small-post'.$small_excerpts_class.' clearfix">'."\n";
			
			//category
			$category_list = array();
			foreach((get_the_category($post->ID)) as $category) { 
				$category_list[] = '<a rel="category" href="'.get_category_link($category->cat_ID) .'" title="'.$category->name.'">'.$category->name.'</a>';
			} 
			print ('<div class="category-list"><span class="category-list">category: '.implode(', ',$category_list).'</span>
			</div>');
			
			//title
			echo '<h2 class="entry-title"><a href="'.get_permalink($post->ID).'" title="Link to '.esc_html(get_the_title($post->ID), 1).'" rel="bookmark">'.get_the_title($post->ID).'</a></h2>'."\n";
			
			//author
			$author_name = get_the_author_meta(display_name, $post->post_author); 
			$avatar = get_avatar($post->post_author,50);
			print ('<div class="ct-avatar clearfix"><div class="ct-avatar-image">'.$avatar.'</div>');
			print ('<div class="ct-author clearfix"><span class="ct-author-label">USER: </span><a class="author-link" href="'.get_author_posts_url($post->post_author) .'" title="'.$author_name.'">'.$author_name.'</a></div></div>');
			
			//content
			echo '<div class="entry-content">'."\n";
			_sg_getImage('1',$post->ID); 
			the_excerpt();

			echo '</div><!-- .entry-content -->'."\n";
			//display_more_link
			echo '<p class="more-link"><a href="'.get_permalink($post->ID).'" class="more-link">'.headway_get_option('read-more-text','MORE').'</a></p>';

			echo '</div><!-- .post-'.get_the_id().' -->'."\n\n";
		 }
	}
	else{
		print ('<h2 class="center">Not Found</h2>
		<p class="center">Sorry, there are no items to display.</p>');
	}
	//close id="ct-box" div
	print '</div>';

	/*foreach($content['dposts'] as $post) {
		setup_postdata($post);
		//category
		$category_list = array();
		foreach((get_the_category($post->ID)) as $category) { 
			$category_list[] = '<a rel="category" href="'.get_category_link($category->cat_ID) .'" title="'.$category->name.'">'.$category->name.'</a>';
		} 
		print ('<div class="entry-meta">CATEGORY '.implode(', ',$category_list).'</div>');
		
		//title
		print ('<h2 class="entry-title"><a rel="bookmark" href="'.get_permalink($post->ID).'" title="'.$post->post_title.'">'.$post->post_title.'</a></h2>');
		
		//author
		$author_name = get_the_author_meta(display_name, $post->post_author); 
		print ('<div><a class="author-link" href="'.get_author_posts_url($post->post_author); .'" title="'.$author_name.'">'.$author_name.'</a></div>');
		the_content();
 	}*/
}
/* content header */
function _sg_ct_header($t,$q) {
	return '<span class="ct-header-link"><a href="?d='.$q.'">'.$t.'</a></span>';
	}