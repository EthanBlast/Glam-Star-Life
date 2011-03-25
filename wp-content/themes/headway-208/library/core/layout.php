<?php
function headway_page_top(){		
	echo '<body class="'.headway_body_class().'">'."\n\n";

	do_action('headway_before_everything');

	if(headway_get_skin_option('header-style') == 'fixed'){
		echo "\n\n".'<div id="whitewrap">'."\n";
		do_action('headway_whitewrap_open');
		echo '<div id="wrapper">'."\n\n";
		do_action('headway_wrapper_open');
	}
	
	$header_order = headway_get_skin_option('header-order');
	
	$header_check = array();
	
	if(is_array($header_order) && count($header_order) > 0){
		$header_order_count = 0;
		
		foreach($header_order as $header_item){
			
			$header_order_count++;
			
			if($header_item == 'header' && !in_array('header', $header_check)){
				if(!headway_get_write_box_value('hide_header')) headway_show_header($header_order_count);
				array_push($header_check, 'header');
			}
			
			if($header_item == 'navigation' && !in_array('navigation', $header_check)){
				if(!headway_get_write_box_value('hide_navigation')) headway_show_navigation($header_order_count);
				array_push($header_check, 'navigation');
			} 
			
			if($header_item == 'breadcrumbs' && !in_array('breadcrumbs', $header_check)){
				headway_show_breadcrumbs($header_order_count);
				array_push($header_check, 'breadcrumbs');
			} 
			
		}
	} else {
		if(!headway_get_write_box_value('hide_header')) headway_show_header(1);
		if(!headway_get_write_box_value('hide_navigation')) headway_show_navigation(2);
		headway_show_breadcrumbs(3);
	}
	
	if(!in_array('navigation', $header_check) && headway_get_skin_option('show-navigation')) headway_show_navigation(2);
	if(!in_array('breadcrumbs', $header_check) && headway_get_skin_option('show-breadcrumbs') && !is_front_page()) headway_show_breadcrumbs(3);
	
	if(headway_get_skin_option('header-style') == 'fluid'){
		echo "\n".'<div id="whitewrap">';
		do_action('headway_whitewrap_open');
		echo "\n".'<div id="wrapper">'."\n";
		do_action('headway_wrapper_open');
	} 
	
	do_action('headway_page_start');
}


function headway_show_navigation($item = false){	
	if(headway_get_skin_option('show-navigation')){
		do_action('headway_before_navigation');
		
		if(headway_get_skin_option('header-style') == 'fluid'){
			$position = 'outside';
		} else {
			$position = 'inside';
			$rearrange_item_class = ' header-rearrange-item-'.$item;	
		}
		
		
		if($position == 'outside'){
			echo '<div id="navigation-container">'."\n";
		}
		
		$navigation_position = ' navigation-'.headway_get_skin_option('navigation-position');
		
		
		echo '<div id="navigation" class="clearfix'.$rearrange_item_class.$navigation_position.'">'."\n";
		do_action('headway_navigation_open');
		echo headway_navigation()."\n";
		do_action('headway_navigation_close');
		echo "\n".'</div><!-- #navigation -->'."\n\n";
		
		if($position == 'outside'){
			echo '</div><!-- #navigation-container -->'."\n\n";
		}
	
		do_action('headway_after_navigation');
	}
}


function headway_show_header($item = false){
		if(headway_get_skin_option('header-style') == 'fixed'):
			//For wrapper border radius
			$rearrange_class = ' header-rearrange-item-'.$item;	
			
			$header_image_class = headway_use_header_image() ? 'header-image' : 'header-text';
		
			$header_open = '<div id="header" class="'.$header_image_class.$rearrange_class.'">'."\n";
			$header_close = '</div><!-- #header -->'."\n\n";
			$position = 'inside';
		elseif(headway_get_skin_option('header-style') == 'fluid'):
			$header_image_class = headway_use_header_image() ? 'header-image' : 'header-text';
		
			$header_open = '<div id="header-container">'."\n".'<div id="header" class="'.$header_image_class.'">'."\n";
			$header_close = '</div><!-- #header -->'."\n\n".'</div><!-- #header-container -->'."\n\n";
			$position = 'outside';
		endif;
	
		echo $header_open;
		
		do_action('headway_header_open');
		
		//If header image exists, is uploaded, and resizing disabled.
		if(headway_use_header_image() && headway_is_header_image_local() && headway_get_option('enable-header-resizing')){
			$header_image = headway_thumbnail(headway_upload_url().'/header-uploads/'.headway_get_option('header-image'), str_replace('px', '', headway_get_skin_option('wrapper-width')), false, 0);
			$header_link_content = '<img src="'.$header_image.'" alt="'.apply_filters('headway_header_alt', get_bloginfo('name')).'" />'."\n"; 
			
		//If exists, is uploaded, and resizing ENABLED.
		}elseif(headway_use_header_image() && !headway_get_option('enable-header-resizing') && headway_is_header_image_local()){
			$header_link_content = '<img src="'.headway_upload_url().'/header-uploads/'.headway_get_option('header-image').'" alt="'.apply_filters('headway_header_alt', get_bloginfo('name')).'" />'."\n";

		//If direct link to image.
		}elseif(headway_use_header_image() && !headway_is_header_image_local()){
			$header_link_content = '<img src="'.headway_get_option('header-image').'" alt="'.apply_filters('headway_header_alt', get_bloginfo('name')).'" />'."\n";
			
		//Else no image.
		}else{
			$header_link_content = get_bloginfo('name');
		}
		
			
			$header_link_class = (headway_get_option('header-image') && headway_get_option('header-image') != 'DELETE' && !headway_get_skin_option('disable-header-image')) ? 'header-link-image' : 'header-link-text';
			$header_link_class_inside = (headway_get_option('header-image') && headway_get_option('header-image') != 'DELETE' && !headway_get_skin_option('disable-header-image')) ? 'header-link-image-inside' : 'header-link-text-inside';
			
			do_action('headway_before_header_link');
			
			if(headway_get_option('nofollow-home')) $nofollow['home'] = ' nofollow';
	
			echo '<div id="top" class="'.$header_link_class.' header-link-top clearfix"><a href="'.get_option('home').'" title="'.apply_filters('headway_header_title', get_bloginfo('name')).'" rel="home'.$nofollow['home'].'" class="'.$header_link_class_inside.'">'.$header_link_content.'</a>';
			do_action('headway_after_header_link');
			echo '</div>'."\n";
			
	
	
			if(headway_get_skin_option('show-tagline') && (is_front_page() || is_home()) && get_option('show_on_front') != 'page'){
				echo '<h1 id="tagline">'.get_bloginfo('description').'</h1>'."\n";
			} elseif(headway_get_skin_option('show-tagline')) {
				echo '<span id="tagline">'.get_bloginfo('description').'</span>'."\n";
			}
	
			do_action('headway_after_tagline');
	
		do_action('headway_header_close');
	
		echo $header_close;
		
		do_action('headway_after_header');
}


function headway_show_breadcrumbs($item = false){
	$position = (headway_get_skin_option('header-style') == 'fluid') ? 'outside' : 'inside';	
	
	$breadcrumbs = '';
	
	if(headway_breadcrumbs_test()){
		do_action('headway_before_breadcrumbs');
	
		if($position == 'outside') 
			echo '<div id="breadcrumbs-container">'."\n";
	
		do_action('headway_breadcrumbs_open');
	
		echo (function_exists('yoast_breadcrumb')) ? yoast_breadcrumb(false, false, false) : headway_breadcrumbs($item, false, false);

		do_action('headway_breadcrumbs_close');
		
		if($position == 'outside') 
			echo "\n".'</div><!-- #breadcrumbs-container -->'."\n";
		
		echo $breadcrumbs;
		
		do_action('headway_after_breadcrumbs');
				
		return true;
	} else {
		return false;
	}
}


function headway_breadcrumbs($item = false, $echo = false, $force_display = false){	
	if(headway_breadcrumbs_test() || $force_display){
		$rearrange_item = (headway_get_option('header-style') == 'fixed') ? ' class="header-rearrange-item-'.$item.'"' : false;	
	
		$return = '<div id="breadcrumbs"'.$rearrange_item.'><p>'.__('You Are Here:', 'headway').' &nbsp; <a href="'.home_url().'">'.__('Home', 'headway').'</a>';
			if(get_option('page_for_posts') != get_option('page_on_front') && get_option('show_on_front') == 'page'){
				if(is_home()){
					$blog = ' &raquo; '.get_the_title(get_option('page_for_posts'));
				} else {
					$blog = ' &raquo; <a href="'.get_page_link(get_option('page_for_posts')).'">'.get_the_title(get_option('page_for_posts')).'</a>';
				}
			} else {
				$blog = '';
			}
			
			if(is_page()){
				global $post;
				$current_page = array($post);
				$parent = $post;
				
				if(isset($parent->post_parent)){
					while($parent->post_parent){
						$parent = get_post($parent->post_parent);
						array_unshift($current_page, $parent);
					}
				}
				
				foreach ( $current_page as $page){
					if($page->ID != get_the_id()):
						$link_open[$page->ID] = '<a href="' . get_page_link( $page->ID ) . '">';
						$link_close[$page->ID] = '</a>';
						
						$page_title = $page->post_title;
					else:
						$link_open[$page->ID] = false;
						$link_close[$page->ID] = false;
						
						$separator = false;
					
						$page_title = '<span id="current-breadcrumb">'.$page->post_title.'</span>';
					endif;
					
					$return .= ' &raquo; '.$link_open[$page->ID].$page_title.$link_close[$page->ID].$separator;
				}				
			}		 
			elseif(is_category()){$return .= $blog.' &raquo; <span id="current-breadcrumb">'.single_cat_title('', false).'</span>'; }
			elseif(is_single() && get_post_type() == 'post'){$return .= $blog.' &raquo; '.get_the_category_list(', ').' &raquo; <span id="current-breadcrumb">'.get_the_title().'</span>'; }
			elseif(is_search()){$return .= $blog.' &raquo; <span id="current-breadcrumb">'.__('Search Results For:', 'headway').' '.get_search_query().'</span>'; }
			elseif(is_author()){
				if(get_query_var('author_name')) :
					$authordata = get_userdatabylogin(get_query_var('author_name'));
				else :
					$authordata = get_userdata(get_query_var('author'));
				endif;
				$return .= $blog.' &raquo; <span id="current-breadcrumb">'.__('Author Archives:', 'headway').' '.$authordata->display_name.'</span>';
			}
			elseif(is_404()){$return .= ' &raquo; <span id="current-breadcrumb">'.__('404 Error!', 'headway').'</span>';}
			elseif(is_tag()){$return .= $blog.' &raquo; <span id="current-breadcrumb">'.__('Tag Archives:', 'headway').' '.single_tag_title('', false).'</span>'; }
			elseif(is_date()){$return .= $blog.' &raquo; <span id="current-breadcrumb">'.__('Archives:', 'headway').' '.get_the_time('F Y').'</span>'; }
			else{$return .= $blog.' &raquo; <span id="current-breadcrumb">'.get_the_title().'</span>'; }
		$return .= "\n".'</p></div><!-- #breadcrumbs -->'."\n\n";
		
		
		if($echo) echo $return;
		if(!$echo) return $return;
	
	}	
}


function headway_breadcrumbs_test(){
	global $breadcrumbs_enabled;
	
	if(!is_front_page() && headway_get_skin_option('show-breadcrumbs') == 'on'){
		if(get_post_meta(get_the_id(), '_hide_breadcrumbs', true) != '1'){ //If statement was being mean to me so I had to nest it :-(
			$breadcrumbs_enabled = true;			
			return $breadcrumbs_enabled;  //Allows this function to be called upon to test if breadcrumbs are enabled
		} else {
			$breadcrumbs_enabled = false;
			return $breadcrumbs_enabled;
		}
	} else {
		$breadcrumbs_enabled = false;
		return $breadcrumbs_enabled;
	}
}


function headway_wrapper_close(){
	if(headway_get_page_option(false, 'leaf-columns') > 1){
		echo '<div id="columns-clear" class="clear"></div>';
	}
	
	do_action('headway_wrapper_close');
	
	echo "\n".'</div><!-- #wrapper -->'."\n";
	
	do_action('headway_whitewrap_close');
	
	echo '</div><!-- #whitewrap -->'."\n";
}


function headway_footer(){			
	if(headway_get_skin_option('footer-style') == 'fluid'){
		headway_wrapper_close();
		
		if(!headway_get_write_box_value('hide_footer')){
			do_action('headway_before_footer');
			echo "\n".'<div id="footer-container">'."\n";
			echo "\n".'<div id="footer">'."\n";
			do_action('headway_footer_open');
			do_action('headway_footer_close');
			echo "\n".'</div><!-- #footer -->';
			echo "\n".'</div><!-- #footer-container -->';
			do_action('headway_after_footer');
		}
	} else {
		if(!headway_get_write_box_value('hide_footer')){
			do_action('headway_before_footer');
			echo "\n".'<div id="footer">'."\n";
			do_action('headway_footer_open');
			do_action('headway_footer_close');
			echo "\n".'</div><!-- #footer -->';
			do_action('headway_after_footer');
		}
		
		headway_wrapper_close();
	}
}


function headway_footer_hooks(){
	if(!headway_get_option('hide-headway-attribution')) add_action('headway_footer_open', 'headway_link', 2);
		
	if(headway_get_option('show-go-to-top-link')) add_action('headway_footer_open', 'headway_go_to_top', 4);
	if(headway_get_option('show-edit-link')) add_action('headway_footer_open', 'headway_edit', 6);
 	if(headway_get_option('show-admin-link')) add_action('headway_footer_open', 'headway_login', 8);

	add_action('headway_footer_open', 'headway_before_copyright', 9);

	if(headway_get_option('show-copyright')) add_action('headway_footer_open', 'headway_copyright', 10);
	
	add_action('headway_footer_close', 'headway_print_debug_output');
}
add_action('init', 'headway_footer_hooks');


function headway_before_copyright(){
	do_action('headway_before_copyright');
}


function headway_header_searchbar(){
	if(headway_get_option('show-header-search-bar')){
		$header_search_display = false;
	} elseif(headway_visual_editor_open()){
		$header_search_display = ' style="display: none;"';
	} else {
		return false;
	}
	
	$search_input_text = apply_filters('headway_search_text', __('Type here to search...', 'headway'));
	
do_action('headway_before_header_search');
?>
<form id="header-search-bar" method="get" action="<?php bloginfo('url') ?>"<?php echo $header_search_display; ?>>
		<input id="header-search-input" name="s" type="text" value="<?php echo (get_search_query() == NULL) ? $search_input_text : get_search_query(); ?>" onblur="if(this.value == '') {this.value = '<?php echo $search_input_text; ?>';}" onclick="if(this.value == '<?php echo $search_input_text; ?>') {this.value = '';}" accesskey="S" />
</form>
<?php
do_action('headway_after_header_search');
}
add_action('headway_navigation_close', 'headway_header_searchbar');


function headway_rss_link(){
	if(headway_get_option('show-header-rss-link')){
		echo apply_filters('headway_header_rss_link', '<a id="header-rss-link" href="'.headway_rss().'">'.__('Subscribe via RSS', 'headway').'</a>');
	} elseif(headway_visual_editor_open()){
		echo apply_filters('headway_header_rss_link', '<a id="header-rss-link" href="'.headway_rss().'" style="display: none;">'.__('Subscribe via RSS', 'headway').'</a>');
	}
}
add_action('headway_header_open', 'headway_rss_link');


function headway_html_open(){
	headway_gzip();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">
<?php
}


function headway_html_close(){
	do_action('headway_after_everything');
	
	echo '</body>';
		
	echo "\n".'</html>';
}