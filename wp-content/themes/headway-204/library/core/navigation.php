<?php 
function headway_navigation($echo = false){
	
	$breadcrumb_class = headway_breadcrumbs_test() ? ' breadcrumb-active' : false;

	$navigation_position = headway_get_skin_option('navigation-position');
	
	$nav_query = array('echo' => false, 'container' => false, 'menu_class' => 'navigation'.$breadcrumb_class.' navigation-'.$navigation_position, 'fallback_cb' => 'headway_legacy_menu');
	if(!headway_get_option('show-navigation-subpages') || !headway_get_skin_option('show-navigation-subpages')) $nav_query['depth'] = 1;
	
	$nav_menu_meta = headway_get_write_box_value('nav-menu');
	
	if($nav_menu_meta){
		$nav_query['menu'] = $nav_menu_meta;
	} elseif(headway_get_option('nav-menu')){
		$nav_query['menu'] = headway_get_option('nav-menu');
	}

	add_filter('wp_nav_menu_items', 'headway_home_link');
		
	if(function_exists('wp_nav_menu'))
		$return = wp_nav_menu(apply_filters('headway_nav_menu_query', $nav_query));
	else
		$return = headway_legacy_menu();
			
	remove_filter('wp_nav_menu_items', 'headway_home_link');
	
	if($echo){ 
		echo $return;
	} else {
		return $return;
	}
	
}


function headway_home_link($menu){
	if(get_option('show_on_front') == 'posts'){
		
		if(headway_get_option('nofollow-home')){
			$nofollow['home'] = ' rel="nofollow" ';
		} else {
			$nofollow['home'] = false;
		}	
		
		if(is_home() || is_front_page()){
			$current['home'] = ' current_page_item';
		} else {
			$current['home'] = false;
		}
		
		$home_text = (headway_get_option('home-link-text')) ? headway_get_option('home-link-text') : 'Home';
		
		if(!headway_get_option('hide-home-link')) $home_link = '<li class="page-item-1'.$current['home'].'"><a href="'.get_option('home').'"'.$nofollow['home'].'>'.$home_text.'</a></li>';
	}
	
	$menu = $home_link.$menu;
	
	return $menu;
}


function headway_legacy_menu(){	
	$breadcrumb_class = headway_breadcrumbs_test() ? ' breadcrumb-active' : false;
	$navigation_position = headway_get_skin_option('navigation-position');
	$search_bar_class = headway_get_option('show-header-search-bar') ? ' search-active' : false;
	
	$nav_query = array('echo' => false, 'title_li' => false);
	if(!headway_get_option('show-navigation-subpages') || !headway_get_skin_option('show-navigation-subpages')) $nav_query['depth'] = 1;
	
	add_filter('wp_nav_menu_items', 'headway_home_link');
	
	$items = apply_filters('wp_nav_menu_items', wp_list_pages($nav_query));
	
	remove_filter('wp_nav_menu_items', 'headway_home_link');
	
	return '<ul class="navigation'.$breadcrumb_class.' navigation-'.$navigation_position.$search_bar_class.'">'."\n".$items."\n</ul>";
}


function headway_exclude_pages($content){
	if($content){
		$excluded_pages = array_merge($content, headway_get_option('excluded_pages'));		
	} else {
		$excluded_pages = headway_get_option('excluded_pages');
	}
		
	return $excluded_pages;
}
if(headway_get_option('excluded_pages')) add_filter('wp_list_pages_excludes', 'headway_exclude_pages');


function headway_woo_menu_filters($content){
	return apply_filters( 'headway_navigation_inside_open', '' ).$content.apply_filters( 'headway_navigation_inside_close', '' );
}
add_filter('wp_nav_menu_items', 'headway_woo_menu_filters', 12);