<?php
function headway_get_include_contents($filename) {
    if(is_file($filename)){
        ob_start();
        include $filename;
        $contents = ob_get_contents();
        ob_end_clean();

        return $contents;
    } else { 
		return false;
	}
}

function headway_generate($what){
	switch($what){
		case 'headway-css':
			$return = headway_get_include_contents(TEMPLATEPATH.'/media/css/utility.css');
			$return .= headway_get_include_contents(TEMPLATEPATH.'/media/css/includes/layout.php');
			$return .= headway_get_include_contents(TEMPLATEPATH.'/media/css/includes/headway.php');
			
			if((!headway_get_option('enable-developer-mode') && (!headway_get_option('active-skin') || headway_get_option('active-skin') == 'none') && !isset($_GET['headway-skin-preview'])) || (isset($_GET['headway-skin-preview']) && $_GET['headway-skin-preview'] == 'none'))
				$return .= headway_get_include_contents(TEMPLATEPATH.'/media/css/includes/element-styling.php');
			else
				$return .= headway_get_include_contents(TEMPLATEPATH.'/media/css/includes/elements-default.css');
			
			if(!headway_get_skin_option('disable-body-background-image') && headway_get_option('body-background-image', true, true)){
				$url = (strpos(headway_get_option('body-background-image', true, true), 'http') !== false) ? headway_get_option('body-background-image', true, true) : headway_upload_url().'/background-uploads/'.rawurlencode(headway_get_option('body-background-image', true, true));
				
				$return .= '

body {
	background-image: url('.$url.');
	background-repeat: '.headway_get_option('body-background-repeat').'; }
	
body.headway-visual-editor-open { background: none; }
	
body.headway-visual-editor-open div#headway-visual-editor {
	background-image: url('.$url.');
	background-repeat: '.headway_get_option('body-background-repeat').'; }

				';
			}
			
			$return .= "\n".'ol.commentlist, ol.commentlist li ul.children { border-top: '.headway_get_element_property_value('sizing', 'ol.commentlist li', 'bottom-border-width').'px solid #'.headway_get_element_property_value('color', 'ol.commentlist li', 'bottom-border').'; }';

			if(!isset($_GET['visual-editor-open']))
		 		$return .= "\n".stripslashes(headway_get_option('live-css'));
		break;
		
		
		
		
		
		case 'leafs-css':
			
$return = '/* ------------------------- */
/* -------Leaf Sizing------- */
/* ------------------------- */'."\n";

			$leafs = headway_get_all_leafs();

			if(count($leafs) > 0){
				
				// Start foreach loop for every leaf/box.												    	
				foreach($leafs as $leaf){ 	
					
					//Store pages to build columns, if necessary
					if(!isset($pages)) $pages = array();
					$pages[] = $leaf['page'];
					
					$leaf = array_map('maybe_unserialize', $leaf);

					$leaf_config = $leaf['config'];
					$leaf_options = $leaf['options'];
										
					$leaf_config['width'] = ($leaf_config['width'] > headway_get_skin_option('wrapper-width')) ? (int)headway_get_skin_option('wrapper-width')-((int)headway_get_skin_option('leaf-container-horizontal-padding')*2)-((int)headway_get_skin_option('leaf-margins')*2)-((int)headway_get_skin_option('leaf-padding')*2) : $leaf_config['width'];


$return .= '
div#leaf-'.$leaf['id'].' { 
	width:'.$leaf_config['width'].'px;
	height:'.$leaf_config['height'].'px;
	min-height:'.$leaf_config['height'].'px; }'."\n";

				}
								
				$return .= "\n";			
							
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
			}
		break;
		
		
		
		
		
		case 'scripts':
		
			$leafs = headway_get_all_leafs();

			if(count($leafs) > 0){					
				$return = '';
															    	
				foreach($leafs as $leaf){ 													// Start foreach loop for every leaf/box.
					$leaf = array_map('maybe_unserialize', $leaf);

					$leaf_config = $leaf['config'];
					$leaf_options = $leaf['options'];
					
					if($leaf['type'] == 'featured'){
						if($leaf_options['rotate-posts'] == 'on'){
							$animation_speed = $leaf_options['animation-speed']*1000;
							$animation_timeout = $leaf_options['animation-timeout']*1000;	
							
$return .= "
jQuery(document).ready(function(){
	if(typeof jQuery().cycle != 'function') return false;
	
	jQuery('div#leaf-$leaf[id] div.featured-leaf-content').cycle({";
	
if($leaf_options['next-prev-location'] == 'inside'){
	$return .= "
			prev: '.$leaf[id]_featured_prev',
			next: '.$leaf[id]_featured_next',";

}elseif($leaf_options['next-prev-location'] == 'outside'){
	$return .= "		
			prev: '#$leaf[id]_featured_prev',
			next: '#$leaf[id]_featured_next',";

}

$return .= '
	    speed:    '.$animation_speed.', 
	    timeout:  '.$animation_timeout.'
	});

	if(typeof visual_editor != \'undefined\'){
		jQuery(\'a:not(a#close-editor)\').click(function(){ return false; });
	}
});';
						}
					}

					elseif($leaf['type'] == 'rotator' && count($leaf_options['images']) > 1){
						$animation_speed = $leaf_options['animation-speed']*1000;
						$animation_timeout = $leaf_options['animation-timeout']*1000;
						
$return .= "
jQuery(document).ready(function(){
	if(typeof jQuery().cycle != 'function') return false;
	
	jQuery('div#leaf-$leaf[id] div.rotator-images').cycle({ 
	    fx:      '".$leaf_options['animation-type']."', 
	    speed:    ".$animation_speed.", 
	    timeout:  ".$animation_timeout."
	});

	if(typeof visual_editor != 'undefined'){
		jQuery('.rotator-images a').attr('href', '#');
	}
});";
					} else {
						$custom_leaf = apply_filters('headway_custom_leaf_js_'.$leaf['type'], $leaf);
						
						 if(!is_array($custom_leaf))
							$return .= "\n".$custom_leaf;
					}
								 
				}
			}
			
		break;
	}
		
	return trim($return);
}