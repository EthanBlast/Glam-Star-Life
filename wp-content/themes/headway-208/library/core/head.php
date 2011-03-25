<?php
/**
 * Content to be displayed in the <head> of the site.
 * 
 * @package Headway
 * @subpackage Site Output
 * @author Clay Griffiths
 */


/**
 * Displays the title.  Parses the variables.
 * 
 * @param bool $print Whether or not to echo the title. 
 *
 * @return string|void
 **/
add_filter('wp_title', 'headway_title', 9);
function headway_title($title){
	
	$tagline = get_option('blogdescription');
	$blogname = get_option('blogname');
	$page = get_the_title();
	$category_description = category_description();
	$category = single_cat_title('', false);
	$tag = single_tag_title('', false);
	
	if ( is_day() ) :
		$archive = get_the_time(get_option('date_format'));
 	elseif ( is_month() ) :
		$archive = get_the_time('F Y');
	elseif ( is_year() ) : 
		$archive = get_the_time('Y');
    endif; 

	$postname = get_the_title();
	$search = get_search_query();
	
	
	if(get_query_var('author_name')){
		$authordata = get_userdata(get_query_var('author_name'));
	} else {
		$authordata = get_userdata(get_query_var('author'));
	}
	
	if($authordata){
		$author_name = $authordata->display_name;
		$author_description = isset($authordata->user_description) ? $authordata->user_description : false;
	}
	
	if(is_home() && get_option('page_for_posts') != get_option('page_on_front')){
		// If statement to get rid of pipe character for folks with no tagline.
		
		if(headway_get_option('title-posts-page') == '%tagline% | %blogname%' && !$tagline){
			$title = '%blogname%';
		} else {
			$title = headway_get_option('title-posts-page');
		}
		
	} elseif(is_front_page() && get_option('page_for_posts') != get_option('page_on_front')){
		// If statement to get rid of pipe character for folks with no tagline.
		
		if(headway_get_option('title-home') == '%tagline% | %blogname%' && !$tagline){
			$title = '%blogname%';
		} else {
			$title = headway_get_option('title-home');
		}
		
	} elseif(is_home() || is_front_page()){
		// If statement to get rid of pipe character for folks with no tagline.
	
		if(headway_get_option('title-home') == '%tagline% | %blogname%' && !$tagline){
			$title = '%blogname%';
		} else {
			$title = headway_get_option('title-home');
		}
		
	} elseif(is_single()){
		
		global $post;
	
		if(get_post_meta($post->ID, 'title', true) && !headway_get_write_box_value('title')){
			$title = stripslashes(get_post_meta($post->ID, 'title', true));
		}
		elseif(headway_get_write_box_value('title')){
			$title = stripslashes(headway_get_write_box_value('title'));
		}
		else
		{	
			$title = headway_get_option('title-single');
			$title = str_replace('%postname%', $postname, $title);
		}
		
		
	} elseif(is_page()){
		global $post;
	
		if(get_post_meta($post->ID, 'title', true) && !headway_get_write_box_value('title')){
			$title = stripslashes(get_post_meta($post->ID, 'title', true));
		}
		elseif(headway_get_write_box_value('title')){
			$title = stripslashes(headway_get_write_box_value('title'));
		}
		else
		{	
			$title = headway_get_option('title-page');
			$title = str_replace('%page%', $page, $title);
		}
		
		
	} elseif(is_category()){
		$title = headway_get_option('title-category');
		$title = str_replace('%category_description%', $category_description, $title);
		$title = str_replace('%category%', $category, $title);
		
	} elseif(is_404()){ 
		$title = headway_get_option('title-404');
		
	} elseif(is_date()){
		$title = headway_get_option('title-archives');
		$title = str_replace('%archive%', $archive, $title);
		
	} elseif(is_tag()){
		$title = headway_get_option('title-tag');
		$title = str_replace('%tag%', $tag, $title);
		
	} elseif(is_search()){
		$title = headway_get_option('title-search');
		$title = str_replace('%search%', $search, $title);
		
	} elseif(is_author()){
		$title = headway_get_option('title-author-archives');
		$title = str_replace('%author_name%', $author_name, $title);
		$title = str_replace('%author_description%', $author_description, $title);
	}	
	
	
	$title = str_replace('%tagline%', $tagline, $title);
	$title = str_replace('%blogname%', $blogname, $title);
	
	return $title;
	
}


/**
 * Builds the SEO meta for the <head>. 
 **/
function headway_seo_meta(){
	if(class_exists('All_in_One_SEO_Pack')) return false;
	
	global $post;
	
	$meta = '<!-- Headway SEO Juice -->';
	
	if((is_home() && headway_get_option('home-keywords')) || (is_front_page() && headway_get_option('home-keywords'))){
		$meta .= "\n".'<meta name="keywords" content="'.headway_get_option('home-keywords').'" />';
	} else {
		if(headway_get_write_box_value('keywords')){
			$keywords = explode(',', headway_get_write_box_value('keywords'));
		} else {
			$keywords = array();
		}
		
		if(get_post_meta($post->ID, 'thesis_keywords', true)){
			global $post;
			$keywords = array_merge($keywords, explode(',', get_post_meta($post->ID, 'thesis_keywords', true)));
		}
		
		if(get_post_meta($post->ID, 'keywords', true)){
			global $post;
			$keywords = array_merge($keywords, explode(',', get_post_meta($post->ID, 'keywords', true)));
		}
		
		if(headway_get_option('categories-meta') == 1){
			$categories = (!is_page()) ? get_the_category($post->ID) : NULL;
			
			if($categories){
				foreach($categories as $category) { 
				    array_push($keywords, $category->cat_name);
				} 
			}
		}
		
		if(headway_get_option('tags-meta') == 1){
			if(get_the_tags($post->ID)){
				$tags = get_the_tags($post->ID);
			
				foreach($tags as $tag) { 
				    array_push($keywords, $tag->name);
				} 
			}
		}
		
		$keywords = str_replace('  ', ' ', implode(', ', array_unique($keywords)));
		$meta .= (!is_home() && !is_front_page() && $keywords) ? "\n".'<meta name="keywords" content="'.$keywords.'" />' : NULL;
	}
	
	if((is_home() && headway_get_option('home-description')) || (is_front_page() && headway_get_option('home-description')))
		$meta .= "\n".'<meta name="description" content="'.stripslashes(headway_get_option('home-description')).'" />';
	elseif(headway_get_write_box_value('description', false, $post->ID) != '')
		$meta .= "\n".'<meta name="description" content="'.stripslashes(headway_get_write_box_value('description', false, $post->ID)).'" />';
	elseif(get_post_meta($post->ID, 'thesis_description', true))
		$meta .= "\n".'<meta name="description" content="'.stripslashes(get_post_meta($post->ID, 'thesis_description', true)).'" />';
	elseif(get_post_meta($post->ID, 'description', true))
		$meta .= "\n".'<meta name="description" content="'.stripslashes(get_post_meta($post->ID, 'description', true)).'" />';
	
	
	if(headway_get_option('canonical') == 1 && (is_page() || is_single()) && !function_exists('get_the_post_thumbnail'))
		$meta .= "\n".'<link rel="canonical" href="'.get_permalink().'" />';	
	
	//Make sure WordPress privacy isn't enabled to avoid redundancy
	if(get_option('blog_public') != '0'){
		if(is_category() && headway_get_option('noindex-category-archives')) $meta .= "\n".'<meta name="robots" content="noindex" />';
		if(is_date() && headway_get_option('noindex-archives')) $meta .= "\n".'<meta name="robots" content="noindex" />';
		if(is_tag() && headway_get_option('noindex-tag-archives')) $meta .= "\n".'<meta name="robots" content="noindex" />';
		if(is_author() && headway_get_option('noindex-author-archives')) $meta .= "\n".'<meta name="robots" content="noindex" />';
		if(headway_get_write_box_value('noindex') && is_singular()) $meta .= "\n".'<meta name="robots" content="noindex" />';
	}	
	
	echo "\n".$meta."\n";
	
	do_action('headway_seo_meta');
}



function headway_head_extras(){
?>

<!-- Extras -->
<link rel="alternate" type="application/rss+xml" href="<?php echo headway_rss() ?>" title="<?php echo get_bloginfo('name')?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url') ?>" />
<?php
	do_action('headway_head_extras');
}


/**
 * Enqueues the Headway JS for leafs.
 * 
 * @uses wp_enqueue_script()
 **/
function headway_enqueue_scripts(){	
	global $wp_query;
	global $headway_custom_leaf_js;
	
	$pageID = headway_current_page();
		
	$leafs = headway_get_page_leafs($pageID);

	headway_build_default_leafs($pageID);
	
	if(count($leafs) > 0){			
		foreach($leafs as $leaf){ 													// Start foreach loop for every leaf/box.
			$leaf = array_map('maybe_unserialize', $leaf);

			if($leaf['type'] == 'featured' && $leaf['options']['rotate-posts']) $featured_check = true;
			if($leaf['type'] == 'rotator' && count($leaf['options']['images']) > 1) $rotator_check = true;
									
			if(isset($headway_custom_leaf_js[$leaf['type']]))
				$headway_custom_leaf_js_active = true;
		}
	}
	
	$load_scripts = array();

	if(isset($rotator_check) || isset($featured_check))
		array_push($load_scripts, 'jquery.cycle.js');
	
	$path_to_theme = headway_relative_path();
	
	$last_updated = headway_get_option('css-last-updated');
	$last_updated_cache = (headway_is_plugin_caching()) ? null : '?'.$last_updated;
			
	if(count($load_scripts) > 0 || isset($headway_custom_leaf_js_active)){
		if(in_array('jquery.cycle.js', $load_scripts)) wp_enqueue_script('jquery_cycle', get_bloginfo('template_directory').'/media/js/libraries/jquery.cycle.js', array('jquery'));
	
		if(headway_cache_exists('scripts.js') && !headway_visual_editor_open() && !$cleared_cache && headway_allow_caching())
			wp_enqueue_script('headway_js_settings', get_bloginfo('template_directory').'/media/'.HEADWAYCACHEDIR.'/scripts.js'.$last_updated_cache, array('jquery'));
		else
			wp_enqueue_script('headway_js_settings', get_bloginfo('siteurl').'/?headway-trigger=js&amp;'.$last_updated, array('jquery'));
	}
	
	if(count($load_scripts) > 0 || isset($headway_custom_leaf_js_active)){
		$cleared_cache = headway_get_option('cleared-cache');

		if($cleared_cache){
			headway_delete_option('cleared-cache');
		} elseif(!headway_cache_exists('scripts.js')){		
			headway_generate_cache(array('scripts'));
		}
	}
	
	$libraries = headway_get_option('js-libraries');
	
	if(is_array($libraries)){
		$manual_js = array('unitpngfix');

		$wp_libraries = array_diff($libraries, $manual_js);

		array_shift($wp_libraries);
		array_shift($libraries);

		foreach($wp_libraries as $library){
			wp_enqueue_script($library);
		}
	}
	
	if(headway_get_page_option(false, 'leaf-columns') > 1 && !headway_get_skin_option('disable-equal-column-heights')){
		wp_enqueue_script('headway_equal_columns', get_bloginfo('template_directory').'/media/js/equal-columns.js', false, false, true);
	}
	
	if(is_singular() && comments_open(get_the_id()) && !(get_post_type() == 'page' && !headway_get_option('page-comments'))) wp_enqueue_script('comment-reply');
}


function headway_print_scripts(){
	global $wp_scripts;	
	
	$libraries = headway_get_option('js-libraries');
	
	echo "\n<!-- Scripts -->\n";
		
	do_action('headway_scripts');
			
	if(is_array($libraries)){
		if(in_array('unitpngfix', $libraries))		
			echo '
<!--[if lt IE 7]>
	<script type="text/javascript">
		theme_path = \''.get_bloginfo('template_directory').'\';
	</script>

	<script type="text/javascript" src="'.get_bloginfo('template_directory').'/media/js/libraries/unitpngfix/unitpngfix.js"></script>
<![endif]-->

';

	echo "\n\n";
	}
}


/**
 * Adds all of the links for the Headway stylesheets.
 **/
function headway_print_stylesheets(){	
	echo "\n\n".'<!-- Stylesheets -->'."\n";
	
	$css_media = (!headway_get_option('print-css')) ? null : ' media="screen, projection"';
	
	do_action('headway_stylesheets');
	do_action('headway_skins_stylesheets');
	
	if(headway_get_option('additional-stylesheet')) echo '<link rel="stylesheet" type="text/css" href="'.headway_get_option('additional-stylesheet').'"'.$css_media.' />'."\n";
	
	if(headway_get_option('print-css')) echo '<link rel="stylesheet" type="text/css" href="'.get_bloginfo('template_directory').'/media/css/misc/print.css" media="print" /><!-- Headway Print Styles -->'."\n";
			
		echo '
<!--[if lte IE 7]>
	<link rel="stylesheet" type="text/css" href="'.get_bloginfo('template_directory').'/media/css/ie/ie.css" />
<![endif]-->

<!--[if IE 6]>
	<link rel="stylesheet" type="text/css" href="'.get_bloginfo('template_directory').'/media/css/ie/ie6.css" />
	
	<script type="text/javascript" src="'.get_bloginfo('template_directory').'/media/js/ie6.js"></script>
<![endif]-->

<!--[if IE 7]>
	<link rel="stylesheet" type="text/css" href="'.get_bloginfo('template_directory').'/media/css/ie/ie7.css" />
<![endif]-->

';
}


function headway_template_stylesheet(){
	echo '<link rel="stylesheet" type="text/css" href="'.get_bloginfo('stylesheet_url').'" id="style" />'."\n";
}
add_action('headway_stylesheets', 'headway_template_stylesheet', 0);


function headway_primary_stylesheets(){
	$cleared_cache = headway_get_option('cleared-cache');
						
	if($cleared_cache){
		headway_delete_option('cleared-cache');
	}
	
	if(!headway_cache_exists('headway.css')){
		$generate[] = 'headway';
	}
	
	if(!headway_cache_exists('leafs.css')){
		$generate[] = 'leafs';
	}		
	
	if(isset($generate) && is_array($generate)){
		headway_generate_cache($generate);
	}
	
	$pageID = headway_current_page();
	$path_to_theme = headway_relative_path();
	$css_media = (!headway_get_option('print-css')) ? null : ' media="screen, projection"';
	
	$last_updated = headway_get_option('css-last-updated');
	$last_updated_cache = (headway_is_plugin_caching()) ? null : '?'.$last_updated;
	
	if(headway_cache_exists('headway.css') && !headway_visual_editor_open() && !$cleared_cache && headway_allow_caching()){
		echo '<link rel="stylesheet" type="text/css" href="'.get_bloginfo('template_directory').'/media/'.HEADWAYCACHEDIR.'/headway.css'.$last_updated_cache.'"'.$css_media.' /><!-- Headway Elements -->'."\n";
	} else {
		$skin_preview = (isset($_GET['headway-skin-preview'])) ? '&amp;headway-skin-preview='.$_GET['headway-skin-preview'] : false;

		$visual_editor_open = headway_visual_editor_open() ? '&amp;visual-editor-open=true' : false;
		
		echo '<link rel="stylesheet" type="text/css" href="'.get_bloginfo('siteurl').'/?headway-trigger=global-css&amp;'.$last_updated.$skin_preview.$visual_editor_open.'"'.$css_media.' /><!-- Headway Elements -->'."\n";
	}	
	
	if(headway_cache_exists('leafs.css') && !headway_visual_editor_open() && !$cleared_cache && headway_allow_caching()){	
		echo '<link rel="stylesheet" type="text/css" href="'.get_bloginfo('template_directory').'/media/'.HEADWAYCACHEDIR.'/leafs.css'.$last_updated_cache.'"'.$css_media.' /><!-- Headway Leaf Sizing -->'."\n";
	} else {
		echo '<link rel="stylesheet" type="text/css" href="'.get_bloginfo('siteurl').'/?headway-trigger=leafs-css&amp;'.$last_updated.'"'.$css_media.' /><!-- Headway Leaf Sizing -->'."\n";
	}
}
add_action('headway_stylesheets', 'headway_primary_stylesheets', 1);


function headway_custom_css(){	
	if(is_main_site() && file_exists(get_stylesheet_directory().'/custom.css')){
		$custom_modified = filemtime(get_stylesheet_directory().'/custom.css'); 
		echo '<link rel="stylesheet" type="text/css" href="'.get_bloginfo('template_directory').'/custom.css?'.$custom_modified.'" /><!-- Headway Custom CSS -->'."\n\n";
	} elseif(file_exists(get_stylesheet_directory().'/custom/sites/'.SITE.'/custom.css')) {
		$custom_modified = filemtime(get_stylesheet_directory().'/custom/sites/'.SITE.'/custom.css'); 
		echo '<link rel="stylesheet" type="text/css" href="'.get_bloginfo('template_directory').'/custom/sites/'.SITE.'/custom.css?'.$custom_modified.'" /><!-- Headway Custom CSS -->'."\n\n";		
	}
}
add_action('headway_stylesheets', 'headway_custom_css');


/**
 * Adds the link to the favicon to the <head>.
 **/
function headway_favicon(){
	if(headway_get_option('favicon'))
		echo "\n<!-- Favicon -->\n".'<link rel="shortcut icon" type="image/ico" href="'.headway_get_option('favicon').'" />'."\n";
}


/**
 * Forward the user is the page is set to forward.
 **/
function headway_forward(){
	global $post;
	
	if(headway_get_write_box_value('headway_category_forward') && !is_admin() && !headway_get_write_box_value('navigation_url')) header('Location: '.get_category_link(headway_get_write_box_value('headway_category_forward')), true, 301);
	if(headway_get_write_box_value('navigation_url') && !is_admin() && !(get_option('page_on_front') === $post->ID && get_option('show_on_front') == 'page')) header('Location: '.headway_get_write_box_value('navigation_url'), true, 301);
}


/**
 * Callback function to be used for displaying the header scripts.
 * 
 * @uses headway_parse_php()
 **/
function headway_header_scripts(){
	echo headway_parse_php(stripslashes(html_entity_decode(headway_get_option('header-scripts'))));
}


/**
 * Callback function to be used for displaying the footer scripts.
 * 
 * @uses headway_parse_php()
 **/
function headway_footer_scripts(){
	echo headway_parse_php(stripslashes(html_entity_decode(headway_get_option('footer-scripts'))));
}


add_action('wp_head', 'headway_favicon', 9);

if(!is_admin()){
	//Remove actions
	remove_action('wp_head', 'wp_print_styles', 8);
	remove_action('wp_head', 'wp_print_head_scripts', 9);
	remove_action('wp_head', 'rel_canonical');
	remove_action('wp_head', 'feed_links', 2);
	remove_action('wp_head', 'feed_links_extra', 3);
	
	//Set Up Actions
	add_action('wp', 'headway_enqueue_scripts');
	add_action('wp', 'headway_forward');
	
	add_action('wp_head', 'headway_seo_meta', 0);
	add_action('wp_head', 'headway_print_stylesheets', 7);
	add_action('wp_head', 'headway_print_scripts', 8);
	add_action('wp_head', 'headway_head_extras', 9);
	
	add_action('headway_stylesheets', 'wp_print_styles');
	add_action('headway_scripts', 'wp_print_head_scripts');
	add_action('headway_scripts', 'headway_header_scripts');
	add_action('headway_seo_meta', 'rel_canonical');
	add_action('headway_head_extras', 'feed_links');
	add_action('headway_head_extras', 'feed_links_extra');
		
	add_action('wp_footer', 'headway_footer_scripts');
}