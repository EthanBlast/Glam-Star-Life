<?php
/**
 * Adds custom font families to be used in the visual editor.
 * 
 * param string $name Name of font to be displayed in visual editor.
 * param string $family Font family to be used.
 *
 * @return void
 **/
function headway_register_font($name, $family, $group){
	global $headway_fonts;
	
	$headway_fonts[$group][$name] = strtolower($family);
}


headway_register_font('Georgia', 'georgia, serif', 'Serif');
headway_register_font('Cambria', 'cambria, georgia, serif', 'Serif');
headway_register_font('Palatino', 'palatino linotype, palatino, serif', 'Serif');
headway_register_font('Times', 'times, serif', 'Serif');
headway_register_font('Times New Roman', 'times new roman, serif', 'Serif');

headway_register_font('Arial', 'arial, sans-serif', 'Sans-Serif');
headway_register_font('Arial Black', 'arial black, sans-serif', 'Sans-Serif');
headway_register_font('Arial Narrow', 'arial narrow, sans-serif', 'Sans-Serif');
headway_register_font('Century Gothic', 'century gothic, sans-serif', 'Sans-Serif');
headway_register_font('Gill Sans', 'gill sans, sans-serif', 'Sans-Serif');
headway_register_font('Helvetica', 'helvetica, sans-serif', 'Sans-Serif');
headway_register_font('Lucida Grande', 'lucida grande, sans-serif', 'Sans-Serif');
headway_register_font('Tahoma', 'tahoma,  sans-serif', 'Sans-Serif');
headway_register_font('Trebuchet MS', 'trebuchet ms,  sans-serif', 'Sans-Serif');
headway_register_font('Verdana', 'verdana, sans-serif', 'Sans-Serif');

headway_register_font('Courier', 'courier, monospace', 'Monospace');
headway_register_font('Courier New', 'courier new, monospace', 'Monospace');

headway_register_font('Papyrus', 'papyrus, fantasy', 'Fancy');
headway_register_font('Copperplate', 'copperplate, copperplate gothic bold, fantasy', 'Fancy');


function headway_font_options($value = false, $echo = true){
	global $headway_fonts;
	
	//Get the total number of optgroups to determine separators later
	$group_max = count($headway_fonts);
	
	//Define variables so it doesn't throw a notice/error
	$group_count = 0;
	$return = '';
	
	foreach($headway_fonts as $group => $fonts){
		$group_count++;
		
		$return .= '<optgroup label="'.$group.'">'."\n";
		
		foreach($fonts as $name => $stack){
			$selected = ($value == $stack) ? ' selected' : null;
			
			$stack = addslashes(str_replace('"', '\'', $stack));
			$return .= '<option value="'.$stack.'"'.$selected.'>'.$name.'</option>'."\n"; 
		}
		
		$return .= "\n".'</optgroup>';

		//If the current group count is not the last one, add a separator option
		if($group_count <= $group_max-1)
			$return .= '<option value=""></option>';
	}
	
	if($echo === true)
		echo $return;
	else
		return $return;
}