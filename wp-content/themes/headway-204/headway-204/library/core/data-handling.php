<?php
/**
 * Functions to get, update, and delete data from the database.
 *
 * @package Headway
 * @subpackage Data Handling
 * @author Clay Griffiths
 **/


/**
 * Simplifies the get_post_meta function for the Headway custom write box class.
 *
 * @uses get_post_meta()
 * 
 * @param string $name Meta row to be queried.
 * @param bool $echo
 * @param int $id
 * 
 * @return void|mixed If $echo, then return the meta value.
 **/
function headway_get_write_box_value($name, $echo = false, $id = false){	
	if($id === false){
		if(headway_is_system_page(array('single'))) return false;
		
		global $post;
		$id = headway_current_page(true) != 'single' ? headway_current_page(true) : $post->ID;
	}
	
	if($echo){
		echo get_post_meta($id, '_'.$name, true);
	} else {
		return (get_post_meta($id, '_'.$name, true) != 'DELETE') ? get_post_meta($id, '_'.$name, true) : false;
	}
}

/**
 * Simply changes "zero" to 0
 *
 * @param array Array to be filtered.
 * @return array
 **/
function headway_change_zeros($array){
	$array['value'] = ($array['value'] == 'zero') ? '0' : $array['value'];
	
	return $array;
}


function headway_get_page_option($page = false, $option){
	$page = $page ? $page : headway_current_page();
	
	if(is_numeric($page)){
		$return = get_post_meta($page, '_headway_'.$option, true);
				
		if($return == 'DELETE'){
			delete_post_meta($page, '_headway_'.$option);
			
			return false;
		} else {
			return $return;
		}
	} else {
		$return = headway_get_option('page_'.$page.'_option_'.$option);
		
		if($return == 'DELETE'){
			headway_delete_option('page_'.$page.'_option_'.$option);
			
			return false;
		} else {
			return $return;
		}
	}
}


function headway_update_page_option($page, $option, $value){
	$page = isset($page) ? $page : headway_current_page();	
	
	if(is_numeric($page)){
		return update_post_meta($page, '_headway_'.$option, $value);
	} else {		
		return headway_update_option('page_'.$page.'_option_'.$option, $value);
	}
}


function headway_delete_page_option($page, $option){
	$page = isset($page) ? $page : headway_current_page();
	
	if(is_numeric($page)){
		return delete_post_meta($page, '_headway_'.$option);
	} else {
		return headway_delete_option('page_'.$page.'_option_'.$option);
	}
}


/**
 * Queries the Headway options table for the desired option.
 *
 * @global object $wpdb
 * 
 * @uses headway_delete_option()
 * 
 * @param string $option Option to be queried.
 * @param bool $unserialize Whether or not to unserialize the value returned.
 * 
 * @return mixed $data The value row.
 **/
function headway_get_option($option, $unserialize = true, $force_query = false){
	global $wpdb;
	global $headway_force_queries;
	
	$headway_options_table = $wpdb->prefix.'headway_options';
	
	if(!$force_query && !$headway_force_queries){
		//If cache doesn't exist, generate it.		
		if(!wp_cache_get('options', 'headway')){
			$headway_options_result = $wpdb->get_results("SELECT * FROM $headway_options_table ORDER BY id ASC", ARRAY_A);
	
			if($headway_options_result){
				foreach($headway_options_result as $row){						
					$headway_options_cached[$row['option']] = $row['value'];
					
					if($row['value'] == 'DELETE') headway_delete_option($row['option']);
				}
				
				wp_cache_set('options', $headway_options_cached, 'headway');
			}
		} else {
			$headway_options_cached = wp_cache_get('options', 'headway');
		}
	
	
		//Setup data veriable
		if($unserialize && isset($headway_options_cached[$option])){
			$data = $unserialize ? maybe_unserialize(stripslashes($headway_options_cached[$option])) : stripslashes($headway_options_cached[$option]);
		} else {
			$data = false;
		}
		
	} else {
		//If the query is forced, don't check the cache, but query the database directly.
		
		$option = stripslashes($wpdb->get_var("SELECT `value` FROM $headway_options_table WHERE `option`='$option'"));
		$data = ($unserialize) ? maybe_unserialize($option) : $option;
	}
	
	return ($data == 'off' || strtolower($data == 'DELETE') || $data == '') ? false : $data;			
}


/**
 * Updates the Headway options table.
 *
 * @global object $wpdb
 * 
 * @param string $option Option to update.
 * @param mixed $value Value to update the row with.
 * 
 * @return bool
 **/
function headway_update_option($option, $value){
	global $wpdb;
	global $headway_options_cached;

	$headway_options_table = $wpdb->prefix.'headway_options';
	
	if(!$wpdb->get_var("SELECT `option` FROM $headway_options_table WHERE `option`='$option'")){
		if(!is_array($value)){
			$value = $wpdb->escape((string)$value);
		} else {
			$value = serialize($value);
		}		
		
		return headway_add_option($option, $value);
	} else {
		$value = (!is_array($value)) ? $wpdb->escape($value) : serialize($value);

		return $wpdb->update($headway_options_table, array('value' => $value), array('option' => $option));
	}
	
	$headway_options_cached = false;
}


/**
 * Adds a row to the Headway options table.
 * 
 * @global object $wpdb
 * 
 * @param string $option Option to be inserted.
 * @param mixed $value Value to be inserted.
 *
 * @return bool
 **/
function headway_add_option($option, $value = false){
	global $wpdb;

	$headway_options_table = $wpdb->prefix.'headway_options';	
	
	$insert_data = array('option' => $option, 'value' => $value);
	
	return $wpdb->insert($headway_options_table, $insert_data);
}


/**
 * Deletes a row from the Headway options table.
 * 
 * @global object $wpdb
 * 
 * @param string $options Option to be deleted.
 *
 * @return bool
 **/
function headway_delete_option($option){
	global $wpdb;

	$headway_options_table = $wpdb->prefix.'headway_options';
	
	return $wpdb->query("DELETE FROM $headway_options_table WHERE `option`='$option'");
}


/**
 * Queries the database for the element rows.
 * 
 * If all params are false, it will return everything from the table.  If the property type param is the only one, then it will only fetch those.  Likewise with the element param.  If all three params are present, it will return the value for the specific row.
 * 
 * @global object $wpdb
 * 
 * @uses headway_change_zeros()
 * 
 * @param string $element
 * @param string $property_type
 * @param string $property
 *
 * @return mixed
 **/
function headway_get_element_styles($args = false){	
	global $wpdb;
	$headway_elements_table = $wpdb->prefix.'headway_elements';
	
	if(is_array($args)) extract($args);
		
	$elements = wp_cache_get('elements', 'headway');
		
	if(!$elements){
		$elements = $wpdb->get_results("SELECT * FROM $headway_elements_table", ARRAY_A);
				
		foreach($elements as $style){
			$elements_restructured[$style['element']][$style['property_type']][$style['property']] = $style['value'];
		}
				
		wp_cache_set('elements', $elements, 'headway');
		wp_cache_set('elements_multidimensional', $elements_restructured, 'headway');
	}
		
	if(!$args || (is_array($args) && count($args) === 0)){				
		return array_map('headway_change_zeros', $elements);
	} else {
		$i = 0;
		
		//Filter elements
		foreach($elements as $element_array){	
			if(isset($property_type) && $element_array['property_type'] != $property_type) unset($elements[$i]);
			if(isset($element) && $element_array['element'] != headway_form_name_to_selector($element)) unset($elements[$i]);
			if(isset($property) && $element_array['property'] != $property) unset($elements[$i]);
			
			$i++;
		}
				
		//If the elements matches only one
		if(is_array($elements) && count($elements) === 1){
			sort($elements);
			
			$value = $elements[0]['value'];
			
			return ($value == 'zero') ? '0' : $value;
		} elseif(is_array($elements) && count($elements) > 1) {
			return $elements;
		} else {
			return false;
		}
	}
}


/**
 * Simplifies headway_get_element_styles() to return one value.
 *
 * @uses headway_get_element_styles()
 * 
 * @param string $property_type
 * @param string $element
 * @param string $property
 * 
 * @return mixed
 **/
function headway_get_element_property_value($property_type, $element, $property, $change_font_family = false){
	$element = headway_form_name_to_selector($element);
	
	$cache = wp_cache_get('elements_multidimensional', 'headway'); 
	
	if(!$cache){
		$cache = headway_get_elements_cache('multi');
	}
		
	$return = (string)$cache[$element][$property_type][$property];
	
	if(!isset($return)) return false;
	
	if($change_font_family){
		//Add quotes around fonts
		if($property == 'font-family'){
			$fonts = explode(', ', $return);
		
			$fixed_fonts = array();
		
			foreach($fonts as $font){
				if(strpos($font, ' ') !== false){
					$fixed_fonts[] = '"'.$font.'"';
				} else {
					$fixed_fonts[] = $font;
				}
			}
		
			$return = implode(', ', $fixed_fonts);
		}
	}
	
	if($return === 'zero') $return = '0';
	
	return (string)$return;
}


/**
 * Caches elements table into WP cache
 **/
function headway_get_elements_cache($return = 'elements'){
	global $wpdb;
	$headway_elements_table = $wpdb->prefix.'headway_elements';
	
	$elements = $wpdb->get_results("SELECT * FROM $headway_elements_table", ARRAY_A);
		
	foreach($elements as $style){
		$elements_restructured[$style['element']][$style['property_type']][$style['property']] = $style['value'];
	}
			
	wp_cache_set('elements', $elements, 'headway');
	wp_cache_set('elements_multidimensional', $elements_restructured, 'headway');
	
	if($return == 'elements'){
		return wp_cache_get('elements', 'headway');
	} elseif($return == 'multi'){
		return wp_cache_get('elements_multidimensional', 'headway');
	} else {
		return false;
	}
}


/**
 * Updates or inserts an element row.
 * 
 * @global object $wpdb
 * 
 * @uses headway_get_element_styles()
 * 
 * @param string $element
 * @param string $property_type
 * @param string $property
 * @param mixed $value
 *
 * @return bool
 **/
function headway_update_element_style($element = false, $property_type = false, $property = false, $value = false, $convert_to_selector = true){
	global $wpdb;
				
	$headway_elements_table = $wpdb->prefix.'headway_elements';
	
	if($value){	
		$value = (string)$value;
		
		if($convert_to_selector) $element = headway_form_name_to_selector($element);
		$wpdb->query("DELETE FROM $headway_elements_table WHERE `element`='$element' AND `property_type`='$property_type' AND `property`='$property'");			
		
		$insert_data = array('element' => $element, 'property_type' => $property_type, 'property' => $property, 'value' => $value);
		return $wpdb->insert($headway_elements_table, $insert_data);
	}
}


function headway_queue_element_style($element = false, $property_type = false, $property = false, $value = false, $convert_to_selector = true){
	global $wpdb;
				
	$headway_elements_table = $wpdb->prefix.'headway_elements';
	
	if($value){	
		$value = (string)$value;
		
		if($convert_to_selector) $element = headway_form_name_to_selector($element);
		
		global $headway_element_insert_data;
		$headway_element_insert_data[] = array('element' => $element, 'property_type' => $property_type, 'property' => $property, 'value' => $value);
		
		return;
	}
}


function headway_run_element_style_queue($truncate = false){
	global $headway_element_insert_data;
		
	if(!$headway_element_insert_data) return false;
	
	global $wpdb;
	
	$headway_elements_table = $wpdb->prefix.'headway_elements';

	$sql = array();
	foreach($headway_element_insert_data as $row){
		$row = array_map('mysql_real_escape_string', $row);

	    $sql[] = "('{$row['element']}', '{$row['property_type']}', '{$row['property']}', '{$row['value']}')";
	}

	if($truncate === true)
		$wpdb->query("TRUNCATE TABLE $headway_elements_table");

	return $wpdb->query('INSERT INTO '.$headway_elements_table.' (element, property_type, property, value) VALUES '.implode(',', $sql));
}


function headway_delete_element_style($element = false, $property_type = false, $property = false){
	global $wpdb;
				
	$headway_elements_table = $wpdb->prefix.'headway_elements';
		
	return $wpdb->query("DELETE FROM $headway_elements_table WHERE `element`='$element' AND `property_type`='$property_type' AND `property`='$property'");	
}


function headway_delete_element_styles($element){
	global $wpdb;
				
	$headway_elements_table = $wpdb->prefix.'headway_elements';
		
	return $wpdb->query("DELETE FROM $headway_elements_table WHERE `element`='$element'");	
}


/**
 * Quick way to add multiple styles for elements.
 *
 * @uses headway_update_element_style()
 **/
function headway_add_element_styles($array){
	foreach($array as $element => $options){
		$element = headway_selector_to_form_name($element);
		
		foreach($options as $option => $value){
			if(strpos($option, 'background') !== false || strpos($option, 'color') !== false || (strpos($option, 'border') && strpos($option, '-width') === false)) $property_type = 'color';
			if(strpos($option, '-width') !== false) $property_type = 'sizing';
			if(strpos($option, 'text') !== false || strpos($option, 'font') !== false || strpos($option, 'line') !== false || strpos($option, 'letter') !== false) $property_type = 'font';

			
			headway_update_element_style($element, $property_type, $option, $value);
		}
	}
	
	headway_clear_cache();
	
	headway_update_option('cleared-cache', 'true');
}