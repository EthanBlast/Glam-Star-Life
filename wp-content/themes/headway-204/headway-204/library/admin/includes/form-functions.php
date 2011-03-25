<?php
function headway_build_admin_input($type, $name_array, $id, $text_left = false, $text_right = false, $value = false, $no_border = false, $show_pixels = false, $tooltip = false){
	if($no_border) $no_border = ' class="no-border"';
	
	$tooltip = isset($tooltip) ? $tooltip : false;
	
	if($type == 'check' || $type == 'checkbox'){
		$checked = ($value == 'true' || $value == 'on') ? ' checked' : NULL;
		$return = '
			<tr'.$no_border.'>					
				<th scope="row"><label for="'.$id.'">'.$text_left.'</label></th>					
				<td class="clearfix">
					<p class="radio-container">
						<input type="hidden" name="'.$id.'" value="DELETE" />
						<input type="checkbox" name="'.$id.'" id="'.$id.'" value="on" class="radio"'.$checked.' /><label for="'.$id.'">'.$text_right.'</label>						
					</p>';
					
		if($tooltip){
			$return .= '<span class="description">'.$tooltip.'</span></td>';
		}
					
		$return .'			
				</td>				
			</tr>
		';
		
		return $return;
	}
	
	
	elseif($type == 'radio'){
		$return = '
			<tr'.$no_border.'>					
				<th scope="row"><label for="'.$id.'">'.$text_left.'</label></th>					
				<td class="clearfix">';
		
		foreach($text_right as $item => $options){
			$checked = ($options['value'] == $value) ? ' checked="checked"' : NULL;
			$return .= '<p class="radio-container"><input type="radio" name="'.$id.'" id="'.$options['id'].'" value="'.$options['value'].'" class="radio"'.$checked.' /><label for="'.$options['id'].'">'.$item.'</label></p>';	
		}
		
		if($tooltip){
			$return .= '<span class="description">'.$tooltip.'</span></td>';
		}
				
		$return .= '</td>				
			</tr>
		';
		
		return $return;
	}
	
	
	elseif($type == 'text'){
		if($show_pixels) $show_pixels = '<small>px</small>';
		
		$return .= '
			<tr'.$no_border.'>					
				<th scope="row"><label for="'.$id.'">'.$text_left.'</label></th>					
				<td><input type="text" name="'.$id.'" id="'.$id.'" value="'.htmlentities($value).'" />'.$show_pixels;
				
		if($tooltip){
			$return .= '<span class="description">'.$tooltip.'</span></td>';
		}
				
		$return .= '</td>				
			</tr>
		';
		
		return $return;
	}
	
	
}

function headway_build_checkbox($id, $reverse = false, $disabled = false, $global_mu_setting = false){
	$checked[$id] = (headway_get_option($id) == 1 || ($global_mu_setting && get_site_option($id) == 1)) ? ' checked="checked"' : false;
	
	$name_hidden = $global_mu_setting ? 'multisite['.$id.'_unchecked'.']' : $id.'_unchecked';
	$name = $global_mu_setting ? 'multisite['.$id.']' : $id;
	
	if($reverse)
		echo '<input type="hidden" value="0" id="'.$id.'-hidden" name="'.$name_hidden.'"'.$disabled.' />';
			
	echo '<input type="checkbox" value="1" id="'.$id.'" name="'.$name.'"'.$checked[$id].$disabled.' />';
	
	if(!$reverse)
		echo '<input type="hidden" value="0" id="'.$id.'-hidden" name="'.$name_hidden.'"'.$disabled.' />';
	
}