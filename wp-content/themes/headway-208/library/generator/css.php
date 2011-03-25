<?php
function headway_css_global(){
	$global = file_get_contents(TEMPLATEPATH.'/media/css/global.css');
	$global .= file_get_contents(TEMPLATEPATH.'/media/css/wrapper.css');
	$global .= file_get_contents(TEMPLATEPATH.'/media/css/forms.css');
	$global .= file_get_contents(TEMPLATEPATH.'/media/css/header.css');
	$global .= file_get_contents(TEMPLATEPATH.'/media/css/navigation.css');
	$global .= file_get_contents(TEMPLATEPATH.'/media/css/breadcrumbs.css');
	$global .= file_get_contents(TEMPLATEPATH.'/media/css/leafs.css');
	$global .= file_get_contents(TEMPLATEPATH.'/media/css/specific-leafs.css');
	$global .= file_get_contents(TEMPLATEPATH.'/media/css/entries.css');
	$global .= file_get_contents(TEMPLATEPATH.'/media/css/comments.css');
	$global .= file_get_contents(TEMPLATEPATH.'/media/css/widgets.css');
	$global .= file_get_contents(TEMPLATEPATH.'/media/css/footer.css');
	$global .= file_get_contents(TEMPLATEPATH.'/media/css/plugins.css');
	
	$options['template_directory'] = get_bloginfo('template_directory');
	
	$options['wrapper_width'] = str_replace('px', '', headway_get_skin_option('wrapper-width'));
	$options['wrapper_margin'] = (headway_get_skin_option('wrapper-margin', true) && headway_get_skin_option('wrapper-vertical-margin') == 30) ? headway_get_skin_option('wrapper-margin') : headway_get_skin_option('wrapper-vertical-margin').'px auto';
	
	$options['leaf_container_width'] = (str_replace('px', '', headway_get_skin_option('wrapper-width'))-str_replace('px', '', headway_get_skin_option('leaf-container-horizontal-padding'))*2);
	$options['leaf_container_padding'] = str_replace('px', '', headway_get_skin_option('leaf-container-vertical-padding')).'px '.str_replace('px', '', headway_get_skin_option('leaf-container-horizontal-padding')).'px';
	
	$options['leaf_margins'] = str_replace('px', '', headway_get_skin_option('leaf-margins'));
	$options['leaf_padding'] = str_replace('px', '', headway_get_skin_option('leaf-padding'));
	
	$options['leaf_resize_border'] = $options['leaf_margins']-1;

	$options['header_image_margin'] = (headway_get_skin_option('header-image-margin') || headway_get_skin_option('header-image-margin') == '0') ? headway_get_skin_option('header-image-margin') : '15px';

	$options['sub_nav_width'] = str_replace('px', '', headway_get_skin_option('sub-nav-width'));
	$options['sub_nav_margin_left'] = $options['sub_nav_width'] + 1;
	
	$options['wrapper_minus_twenty'] = $options['wrapper_width']-20;
	
	$options['placeholder_padding'] = $options['leaf_margins']-2+$options['leaf_padding'];	
	
	$options['font'] = headway_get_font_family(headway_get_element_property_value('font', 'div-period-entry-content', 'font-family', true));
	
	$leaf_containers_border_style_query = (headway_get_skin_option('leaf-columns-border-style') == 'no border') ? 'none' : headway_get_skin_option('leaf-columns-border-style');
	$leaf_containers_border_color_query = headway_get_skin_option('leaf-columns-border-color');

	$options['leaf_containers_border_style'] = ($leaf_containers_border_style_query) ? $leaf_containers_border_style_query : 'solid';
	$options['leaf_containers_border_color'] = ($leaf_containers_border_color_query) ? $leaf_containers_border_color_query : 'dddddd';

	$options['leaf_containers_border_width'] = ($leaf_containers_border_style == 'none') ? 0 : ($leaf_containers_border_style == 'double') ? 3 : 1;

	$options['leaf_containers_padding'] = ($leaf_containers_border_style_query == 'double') ? 13 : 15;
	$options['leaf_columns_padding'] = ($leaf_containers_border_style == 'none') ? 10 : ($leaf_containers_border_style == 'double') ? 7 : 9;
	
	$options['resize_column_placeholder_padding'] = ($options['leaf_margins']*2)-4;
	
	$options['leafs_column_leaf_bottom_margin'] = str_replace('px', '', headway_get_skin_option('leaf-margins'))*2;
	$options['leafs_column_placeholder_bottom_margin'] = (str_replace('px', '', headway_get_skin_option('leaf-margins'))*2)-2;

	
	$conditionals['wrapper_border_top_fluid'] = (headway_get_skin_option('wrapper-vertical-margin') === 0) ? 'body.header-fluid div#wrapper { border-top: none !important; }' : null;
	$conditionals['meta_wordwrapping'] = (headway_get_option('post-below-title-left') == 'Written on %date% by %author% in %categories%' && headway_get_option('post-below-title-right') == '%comments% - %respond%') ? "div.meta-below-title div.left { width: 65%; }\ndiv.meta-below-title div.right { width: 30%; text-align: right; }\n" : null;
	
	foreach($options as $option => $value){
		$global = str_replace('%%'.$option.'%%', $value, $global);
	}
	
	foreach($conditionals as $conditional => $value){
		$global = str_replace('$$'.$conditional.'$$', $value, $global);
	}
	
	return $global;
}


function headway_css_leafs(){
	$leafs = headway_get_all_leafs();
	
	//If there are no leafs, stop now.
	if(!count($leafs)) return false;
	
	$return = "/* ------------------------- */\n/* -------Leaf Sizing------- */\n/* ------------------------- */\n\n";

	// Start foreach loop for every leaf/box.												    	
	foreach($leafs as $leaf){ 	

		//Store pages to build columns, if necessary
		if(!isset($pages)) $pages = array();
		$pages[] = $leaf['page'];

		$leaf = array_map('maybe_unserialize', $leaf);

		$leaf_config = $leaf['config'];
		$leaf_options = $leaf['options'];

		//If leaf is more than max width, fix it.
		if($leaf_config['width'] > headway_get_skin_option('wrapper-width')){
			$leaf_container = (int)headway_get_skin_option('wrapper-width')-((int)headway_get_skin_option('leaf-container-horizontal-padding')*2);
			$leaf_margins_padding = ((int)headway_get_skin_option('leaf-margins')*2)-((int)headway_get_skin_option('leaf-padding')*2);
			
			$leaf_config['width'] =	$leaf_container - $leaf_margins_padding;
		}


		$return .= 'div#leaf-'.$leaf['id'].' {'."\n".'	width:'.$leaf_config['width'].'px;'."\n".'	height:'.$leaf_config['height'].'px;'."\n".'	min-height:'.$leaf_config['height'].'px; }'."\n\n";
	}

	$return .= "\n";			

	$return .= headway_css_leaf_columns($pages);
	
	return $return;
}


function headway_css_leaf_columns($pages){
	if(count($pages) === 0) return;
	
	$return = "/* ------------------------- */\n/* -------Leaf Columns------- */\n/* ------------------------- */\n\n";
		
	foreach(array_unique($pages) as $page){
		$leaf_columns = headway_get_page_option($page, 'leaf-columns');

		if($leaf_columns){						
			$total_width = 0;
			$fixed_width = 0;

			for($i = $leaf_columns+1; $i <= 4; $i++){
				headway_delete_page_option($page, 'column-'.$i.'-width');
			}

			//Add up column widths to get total of all widths
			for($i = 1; $i <= $leaf_columns; $i++){
				$total_width = $total_width + headway_get_page_option($page, 'column-'.$i.'-width') + 20;
			}				

			//If the total is within 10px of the wrapper width, add the difference to the last column							
			if($total_width < headway_get_skin_option('wrapper-width') && $total_width >= headway_get_skin_option('wrapper-width')-10){							
				$difference = headway_get_skin_option('wrapper-width') - $total_width;

				headway_update_page_option($page, 'column-'.$leaf_columns.'-width', headway_get_page_option($page, 'column-'.$leaf_columns.'-width')+$difference);
			}

			//If the total doesn't match the wrapper width by a long shot, make all columns equal to match wrapper.					
			if((int)$total_width !== 0 && !($total_width <= headway_get_skin_option('wrapper-width') && $total_width >= headway_get_skin_option('wrapper-width')-10)){
				$divide_from_this = headway_get_skin_option('wrapper-width') - ($leaf_columns*20);

				$fixed_width = $divide_from_this / $leaf_columns;
			}

			for($i = 1; $i <= $leaf_columns; $i++){
				if($fixed_width === 0){
					$width = headway_get_page_option($page, 'column-'.$i.'-width');
				} else {
					$width = $fixed_width;
					headway_update_page_option($page, 'column-'.$i.'-width', $fixed_width);
				}

				$return .= "\n".'div#wrapper div#column-'.$i.'-page-'.$page.' { width: '.$width.'px; }';
			}
		}
	}
	
	return $return;
}


function headway_css_elements(){
	$return = "\n/* ------------------------- */\n/* -----Element Styling----- */\n/* ------------------------- */\n\n";
	
	//If skin is active, use default
	if(headway_get_option('active-skin') && headway_get_option('active-skin') != 'none') return file_get_contents(TEMPLATEPATH.'/media/css/misc/bare-elements.css');
	
	//If developer mode is enabled, use default
	if(headway_get_option('enable-developer-mode')) return file_get_contents(TEMPLATEPATH.'/media/css/misc/bare-elements.css');
	
	//If skin preview is being used, use default
	if(isset($_GET['headway-skin-preview']) && $_GET['headway-skin-preview'] != 'none') return file_get_contents(TEMPLATEPATH.'/media/css/misc/bare-elements.css');
	
	include 'elements.php';
	
	return headway_css_elements_generated();
}


function headway_css_body_background(){
	if(headway_get_skin_option('disable-body-background-image') || !headway_get_option('body-background-image', true, true)) return false;
	
	//Check if image is a URL or hosted locally
	if(strpos(headway_get_option('body-background-image', true, true), 'http') !== false){
		$url = headway_get_option('body-background-image', true, true);
	} else {
		$url = headway_upload_url().'/background-uploads/'.rawurlencode(headway_get_option('body-background-image', true, true));
	}

	$css = "/* Headway Body Background */\n";
	$css .= "body.headway-visual-editor-open { background: none; }\n\n";
	$css .= 'body, body.headway-visual-editor-open div#headway-visual-editor { background-image: url('.$url.'); background-repeat: '.headway_get_option('body-background-repeat').'; }'."\n";
	
	return $css;
}


function headway_css_live(){
	if(isset($_GET['visual-editor-open']) || !headway_get_option('live-css')) return false;
	
 	return "\n\n/* Live CSS */\n\n".stripslashes(headway_get_option('live-css'));
}


function headway_css_comment_fix(){
	$bottom_border_color = headway_get_element_property_value('color', 'ol.commentlist li', 'bottom-border');
	$border_width = headway_get_element_property_value('sizing', 'ol.commentlist li', 'bottom-border-width');
	
	return "\n".'ol.commentlist, ol.commentlist li ul.children { border-top: '.$border_width.'px solid #'.$bottom_border_color.'; }';
}


function headway_css_border_radius_conditional(){
	$wrapper_border_radius = headway_get_skin_option('wrapper-border-radius');
	$leaf_border_radius = headway_get_skin_option('leaf-border-radius');
	
	$return = '';
	
	if($wrapper_border_radius > 0){
		$return .= 'div#wrapper {
			-webkit-border-radius: '.$wrapper_border_radius.'px;
			-moz-border-radius: '.$wrapper_border_radius.'px;
			border-radius: '.$wrapper_border_radius.'px;
		}

		body.header-fluid div#top-container {
			-webkit-border-top-left-radius: '.$wrapper_border_radius.'px;
			-webkit-border-top-right-radius: '.$wrapper_border_radius.'px;
			-moz-border-radius-topleft: '.$wrapper_border_radius.'px;
			-moz-border-radius-topright: '.$wrapper_border_radius.'px;
			border-top-left-radius: '.$wrapper_border_radius.'px;
			border-top-right-radius: '.$wrapper_border_radius.'px;
		}';

		if(headway_get_element_styles(array('element' => 'div#wrapper', 'property' => 'border-width')) <= 5){
			$return .= '
			div.header-rearrange-item-1 {
				-webkit-border-top-left-radius: '.$wrapper_border_radius.'px;
				-webkit-border-top-right-radius: '.$wrapper_border_radius.'px;
				-moz-border-radius-topleft: '.$wrapper_border_radius.'px;
				-moz-border-radius-topright: '.$wrapper_border_radius.'px;
				border-top-left-radius: '.$wrapper_border_radius.'px;
				border-top-right-radius: '.$wrapper_border_radius.'px;
			}

			div.header-rearrange-item-1 ul.navigation li:first-child a {
				-webkit-border-top-left-radius: '.$wrapper_border_radius.'px;
				-moz-border-radius-topleft: '.$wrapper_border_radius.'px;
				border-top-left-radius: '.$wrapper_border_radius.'px;
			}

			div.header-rearrange-item-1 ul.navigation-right li:last-child a {
				-moz-border-radius: 0;
				-webkit-border-radius: 0;
				border-radius: 0;

				-webkit-border-top-right-radius: '.$wrapper_border_radius.'px;
				-moz-border-radius-topright: '.$wrapper_border_radius.'px;
				border-top-right-radius: '.$wrapper_border_radius.'px;
			}';
		} 

		$return .= 'div#footer {
			-webkit-border-bottom-left-radius: '.$wrapper_border_radius.'px;
			-webkit-border-bottom-right-radius: '.$wrapper_border_radius.'px;
			-moz-border-radius-bottomleft: '.$wrapper_border_radius.'px;
			-moz-border-radius-bottomright: '.$wrapper_border_radius.'px;
			border-bottom-left-radius: '.$wrapper_border_radius.'px;
			border-bottom-right-radius: '.$wrapper_border_radius.'px;
		}';
	}
	
	
	if($leaf_border_radius > 0){
		$return .= 'div.headway-leaf {
			-webkit-border-radius: '.$leaf_border_radius.'px;
			-moz-border-radius: '.$leaf_border_radius.'px;
			border-radius: '.$leaf_border_radius.'px;
		}';
	}
	
	return $return;
}