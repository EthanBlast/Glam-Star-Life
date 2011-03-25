<?php
function headway_scripts(){
	$leafs = headway_get_all_leafs();

	if(count($leafs) > 0){					
		$return = '';
													    	
		foreach($leafs as $leaf){
			$leaf = array_map('maybe_unserialize', $leaf);

			$leaf_config = $leaf['config'];
			$leaf_options = $leaf['options'];
						
			if($leaf['type'] == 'featured'){
				$return .= headway_scripts_featured($leaf);
			} elseif($leaf['type'] == 'rotator'){
				$return .= headway_scripts_rotator($leaf);
			} else {
				$custom_leaf = apply_filters('headway_custom_leaf_js_'.$leaf['type'], $leaf);
				
				 if(!is_array($custom_leaf))
					$return .= "\n".$custom_leaf;
			}
		}
	}
	
	return $return;
}


function headway_scripts_featured($leaf){
	if($leaf['type'] != 'featured' || $leaf['options']['rotate-posts'] != 'on') return;
	
	$animation_speed = $leaf['options']['animation-speed']*1000;
	$animation_timeout = $leaf['options']['animation-timeout']*1000;	
		
	$return = "jQuery(document).ready(function(){if(typeof jQuery().cycle != 'function') return false;jQuery('div#leaf-$leaf[id] div.featured-leaf-content').cycle({";

	if($leaf['options']['next-prev-location'] == 'inside'){
		$return .= "prev: '.$leaf[id]_featured_prev',next: '.$leaf[id]_featured_next',";
	} elseif($leaf['options']['next-prev-location'] == 'outside'){
		$return .= "prev: '#$leaf[id]_featured_prev',next: '#$leaf[id]_featured_next',";
	}

	$return .= 'speed: '.$animation_speed.', timeout:  '.$animation_timeout.'});if(typeof visual_editor != \'undefined\'){jQuery(\'a:not(a#close-editor)\').click(function(){ return false; });}});';
	
	return $return;
}


function headway_scripts_rotator($leaf){
	if(!count($leaf['options']['images'])) return;
	
	$animation_speed = $leaf['options']['animation-speed']*1000;
	$animation_timeout = $leaf['options']['animation-timeout']*1000;
		
$return = "
jQuery(document).ready(function(){
if(typeof jQuery().cycle != 'function') return false;

jQuery('div#leaf-$leaf[id] div.rotator-images').cycle({ 
fx:      '".$leaf['options']['animation-type']."', 
speed:    ".$animation_speed.", 
timeout:  ".$animation_timeout."
});

if(typeof visual_editor != 'undefined'){
jQuery('.rotator-images a').attr('href', '#');
}
});";

	return $return;
}