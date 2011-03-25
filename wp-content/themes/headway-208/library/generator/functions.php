<?php
include 'css.php';
include 'scripts.php';


function headway_generate_global_css(){
	$return = headway_css_global();
	$return .= headway_css_elements();
	$return .= headway_css_body_background();
	$return .= headway_css_border_radius_conditional();
	$return .= headway_css_comment_fix();
	$return .= headway_css_live();
	
	return $return;
}


function headway_generate_leafs_css(){
	return headway_css_leafs();
}


function headway_generate_scripts(){
	return headway_scripts();
}