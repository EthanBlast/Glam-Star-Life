<?php
/**
 * Adds custom font families to be used in the visual editor.
 * 
 * param string $name Name of font to be displayed in visual editor.
 * param string $family Font family to be used.
 *
 * @return void
 **/


headway_register_font('Georgia', 'georgia, serif');
headway_register_font('Cambria', 'cambria, georgia, serif');
headway_register_font('Palatino', 'palatino linotype, palatino, serif');
headway_register_font('Times', 'times, serif');
headway_register_font('Times New Roman', 'times new roman, serif');

headway_register_font('Arial', 'arial, sans-serif');
headway_register_font('Arial Black', 'arial black, sans-serif');
headway_register_font('Arial Narrow', 'arial narrow, sans-serif');
headway_register_font('Century Gothic', 'century gothic, sans-serif');
headway_register_font('Gill Sans', 'gill sans, sans-serif');
headway_register_font('Helvetica', 'helvetica, sans-serif');
headway_register_font('Impact', 'impact, sans-serif');
headway_register_font('Lucida Grande', 'lucida grande, sans-serif');
headway_register_font('Tahoma', 'tahoma,  sans-serif');
headway_register_font('Trebuchet MS', 'trebuchet ms,  sans-serif');
headway_register_font('Verdana', 'verdana, sans-serif');

headway_register_font('Courier', 'courier, monospace');
headway_register_font('Courier New', 'courier new, monospace');

headway_register_font('Papyrus', 'papyrus, fantasy');
headway_register_font('Copperplate', 'copperplate, copperplate gothic bold, fantasy');


function headway_register_font($name, $family){
	global $headway_fonts;
	global $headway_font_families;
	
	$id = str_replace(' ', '-', strtolower($name));
	
	$headway_fonts[$id] = array('family' => strtolower($family), 'name' => $name);
	$headway_font_families[$family] = array('id' => $id, 'name' => $name);
	
	//Use special sorting so Arial Black and Narrow come after Arial
	uksort($headway_fonts, 'headway_font_sort');
}


function headway_font_sort($a, $b){
	$a = str_replace('-', ' ', $a);
	$b = str_replace('-', ' ', $b);
	
	return strcasecmp($a, $b);
}


function headway_get_font_family($id){
	global $headway_fonts;
	return $headway_fonts[$id]['family'];
}


function headway_get_font_name($id){
	global $headway_fonts;
	return $headway_fonts[$id]['name'];
}


function headway_get_font_id($family){
	global $headway_font_families;
	return $headway_font_families[$family]['id'];
}


function headway_font_options($value = false, $echo = true){
	global $headway_fonts;
	
	$return = '';
	
	//Add support for old fonts
	if(strpos($value, ',') !== false){
		$value = headway_get_font_id($value);
	}
	
	foreach($headway_fonts as $id => $options){
		$selected = ($id === $value) ? ' selected' : null;

		$return .= '<option value="'.$id.'"'.$selected.'>'.$options['name'].'</option>'."\n"; 
	}
	
	if($echo === true)
		echo $return;
	else
		return $return;
}


function headway_visual_font_options($value = 'georgia', $echo = true, $class = false){
	global $headway_fonts;
			
	//Define variables so it doesn't throw a notice/error
	$return = '';
	
	//Add support for old fonts
	if(strpos($value, ',') !== false){
		$value = headway_get_font_id($value);
	}
	
	$button_classes = $class ? 'font-family-select-button '.$class : 'font-family-select-button';
		
	$return .= '<span class="'.$button_classes.'" style="font-family: '.headway_get_font_family($value).';">'.headway_get_font_name($value).'</span>';
		
	$select_classes = $class ? 'font-family-select-options '.$class : 'font-family-select-options';
	
	$return .= '<div class="'.$select_classes.'">';
	
	foreach($headway_fonts as $id => $options){		
		$stack = addslashes(str_replace('  ', ' ', str_replace('"', '\'', $options['family'])));
		
		$class = ($id == $value) ? 'class="'.$id.' selected"' : 'class="'.$id.'"';
		
		$return .= '<span style="font-family: '.$stack.';" '.$class.'>'.$options['name'].'</span>'; 
	}
	
	$return .= '</div>';
	
	if($echo === true)
		echo $return;
	else
		return $return;
}