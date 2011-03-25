<?php
/**
 * Builds inputs for design editor.
 **/
function headway_create_element_inputs($display, $array){	
	$return = '';
	
	foreach($array as $group => $elements){
		foreach($elements as $element){
			$nice_id = headway_selector_to_form_name($element[0]);
			
			$selector = (isset($element[5]) && $element[5]) ? $element[5] : $element[0];
			
			switch($display){
				case 'colors':	
					if(!isset($element[2]) || !is_array($element[2])) break;
							
					$return .= '<table id="colors-'.$nice_id.'" class="colors-inputs" style="display: none;">';
					
					$color_input_count = 1;

					if(strpos($element[0], ':hover') !== false){
						$color_input_count++;
						
						$class = ($color_input_count%2) ? NULL : ' class="alt"';
						
						//If the element does not have inherit colors property in the DB, set it to on
						if(!headway_get_element_property_value('color', $nice_id, 'inherit-colors')){
							headway_update_element_style($nice_id, 'color', 'inherit-colors', 'on');
							headway_get_elements_cache();
						}
						
						$return .= '<tr'.$class.'>
							<th scope="row"><label for="color-'.$nice_id.'-inherit-colors">Inherit Colors</label></th>
							<td>
								<input id="color-'.$nice_id.'-inherit-colors-hidden" type="hidden" value="off" class="headway-visual-editor-color-input" name="color['.$nice_id.'][inherit-colors]" />
								<input id="color-'.$nice_id.'-inherit-colors" type="checkbox" value="on" name="color['.$nice_id.'][inherit-colors]" selector="'.$selector.'" class="headway-visual-editor-input ve-check color-check inherit-colors headway-visual-editor-color-input"'.headway_checkbox_value_custom('on', headway_get_element_property_value('color', $nice_id, 'inherit-colors')).' />
								<label for="color-'.$nice_id.'-inherit-colors" class="ve-check-label">Enable</label>
							</td>
						</tr>';
					}

					foreach($element[2] as $color => $color_preset){
						
						//Check if leaf container
						if($color_preset == 'leaf-container'){
							$leaf_container = true; 
						} elseif(is_numeric($color)){
							$color = $color_preset;
							$color_preset = false;
							$leaf_container = false; 
						} else {
							$color_preset = ' color-'.$color_preset.'-preset';
							$leaf_container = false;
						}
						
						$english_color = ucwords(str_replace('-', ' ', $color));
						$property = $color;
						
						$color_input_count++;
						$alt_class = ($color_input_count%2) ? NULL : 'alt';
						
						$row_display = (headway_get_element_property_value('color', $nice_id, 'inherit-colors') == 'on') ? ' style="display: none;"' : null;
						
						$color_value = headway_get_element_property_value('color', $nice_id, $property);
																				
						//If Leaf Container Input Type		
						if($leaf_container){
							$color_input_count++;
							$alt_class = ($color_input_count%2) ? NULL : 'alt';
							
							$border_color = headway_get_skin_option('leaf-columns-border-color');
							if(!$border_color) $border_color = 'dddddd';
							
							$return .= '<tr class="'.$alt_class.'"'.$row_display.'>
											<th scope="row"><label for="leaf-columns-border-color">Border Colors</label></th>

											<td>
												<div class="color-picker no-color-picker" id="color-picker-leaf-columns-border-color"><div class="color-picker-color" style="background-color: #'.$border_color.'"></div></div>

												<input type="text" class="headway-visual-editor-color-input color-text" size="6" maxlength="6" value="'.$border_color.'" selector="div.leafs-column" id="leaf-columns-border-color" name="headway-config[leaf-columns-border-color]" />
											</td>
										</tr>';
										
							$color_input_count++;
							$alt_class = ($color_input_count%2) ? NULL : 'alt';

							$border_styles = array('no border', 'solid', 'dotted', 'dashed', 'double');
							$border_style = headway_get_skin_option('leaf-columns-border-style');

							foreach($border_styles as $style){
								$selected = ($style == $border_style || ($style == 'solid' && !$border_style)) ? ' selected' : false;

								$border_style_options[$nice_id] .= '<option value="'.str_replace(' ', '-', $style).'"'.$selected.'>'.ucwords($style).'</option>';
							}

							$return .= '<tr class="'.$alt_class.'"'.$row_display.'>
									<th scope="row"><label for="leaf-columns-border-style">Border Style</label></th>
									<td>
										<select id="leaf-columns-border-style" name="headway-config[leaf-columns-border-style]" class="headway-visual-editor-input">
												'.$border_style_options[$nice_id].'
										</select>
									</td>
								</tr>';

							continue;
						} else {
							$return .= '<tr class="'.$alt_class.'"'.$row_display.'>
											<th scope="row"><label for="color-'.$nice_id.'-'.$property.'">'.$english_color.'</label></th>

											<td>
												<div class="color-picker" id="color-picker-'.$nice_id.'-'.$property.'"><div class="color-picker-color" style="background-color: #'.$color_value.'"></div></div>
												<input type="text" class="headway-visual-editor-color-input color-text '.$property.$color_preset.'" size="6" maxlength="6" value="'.$color_value.'" selector="'.$selector.'" id="color-'.$nice_id.'-'.$property.'" name="color['.$nice_id.']['.$property.']" />';

							if(strpos($color, 'border') !== false && !$leaf_container){
								$width = headway_get_element_property_value('sizing', $nice_id, $property.'-width');
							
								$width = ($width != 'zero' && $width) ? $width : '0';
																											
								$return .=	'&nbsp;<input type="text" class="headway-visual-editor-border-input border-width '.$property.'" selector="'.$selector.'" size="2" maxlength="2" value="'.$width.'" id="width-'.$nice_id.'-'.$property.'" name="width['.$nice_id.']['.$property.'-width]" />px';
							}

							$return .= '</td>
									</tr>';
						}
														
						if(strpos($color, 'background') !== false){
							$color_input_count++;
							
							$alt_class = ($color_input_count%2) ? NULL : 'alt';
							
							$return .= '<tr class="'.$alt_class.'"'.$row_display.'>
								<th scope="row"><label for="color-'.$nice_id.'-background-transparent">No Background</label></th>
								<td>
									<input id="color-'.$nice_id.'-background-transparent-hidden" type="hidden" value="off" class="headway-visual-editor-color-input" name="color['.$nice_id.'][background-transparent]" />
									<input id="color-'.$nice_id.'-background-transparent" type="checkbox" value="on" name="color['.$nice_id.'][background-transparent]" selector="'.$selector.'" class="headway-visual-editor-input ve-check color-check background-transparent headway-visual-editor-color-input"'.headway_checkbox_value_custom('on', headway_get_element_property_value('color', $nice_id, 'background-transparent')).' />
									<label for="color-'.$nice_id.'-background-transparent" class="ve-check-label">Enable</label>
								</td>
							</tr>';
						}
					}

					$return .= '</table>';
				break;
					
				case 'fonts':
					$font_property_row_count = 1;
				
					if((isset($element[3]) && $element[3]) || (isset($element[4]) && $element[4])){
						$return .= '<table id="fonts-'.$nice_id.'" class="fonts-inputs" style="display: none;">';
						
						if(isset($element[3]) && $element[3]){
														
							//Font Family
							if(!is_array($element[3]) || (is_array($element[3]) && in_array('font-family', $element[3]))){
								$font_property_row_count++;
								
								$alt = $font_property_row_count%2 ? null : ' alt';
								
								$title_fonts = ((isset($element[3]['font-family']) && $element[3]['font-family'] === 'title') || isset($element[3]['title'])) ? ' title-fonts' : false;
												
								$return .= '<tr class="font-family'.$alt.'">
									<th scope="row"><label for="fonts-'.$nice_id.'-font-family">'.__('Font', 'headway').'</label></th>
									<td>
										'.headway_visual_font_options(headway_get_element_property_value('font', $nice_id, 'font-family'), false).'
										<select style="display: none;" id="font-'.$nice_id.'-font-family" name="fonts['.$nice_id.'][font-family]" class="font-select font-family headway-visual-editor-font-input'.$title_fonts.'" selector="'.$selector.'">
											'.headway_font_options(headway_get_element_property_value('font', $nice_id, 'font-family'), false).'
										</select>
									</td>
								</tr>';
							}

					
							//Font Size						
							if(!is_array($element[3]) || (is_array($element[3]) && in_array('font-size', $element[3]))){
								$font_property_row_count++;
								
								$alt = $font_property_row_count%2 ? null : ' class="alt"';
								
								for($i = 6; $i <= 72; $i++){
									if(!isset($font_size_options[$nice_id])) $font_size_options[$nice_id] = '';

									$font_size_options[$nice_id] .= '<option value="'.$i.'"'.headway_option_value($i, headway_get_element_property_value('font', $nice_id, 'font-size')).'>'.$i.'px</option>';

									if($i >= 20) $i++;						
								}
								
								$return .= '<tr'.$alt.'>
									<th scope="row"><label for="fonts-'.$nice_id.'-font-size">Font Size</label></th>
									<td>
										<select id="font-'.$nice_id.'-font-size" name="fonts['.$nice_id.'][font-size]" class="font-select font-size headway-visual-editor-font-input" selector="'.$selector.'">
												'.$font_size_options[$nice_id].'
										</select>
									</td>
								</tr>';
							}
							
							
							//Line Height
							if(!is_array($element[3]) || (is_array($element[3]) && in_array('line-height', $element[3]))){
								$font_property_row_count++;
								
								$alt = $font_property_row_count%2 ? null : ' class="alt"';
								
								for($i = 5; $i <= 30; $i++){
									$percent = $i*10;

									if(!isset($line_height_options[$nice_id])) $line_height_options[$nice_id] = '';

									if(headway_get_element_property_value('font', $nice_id, 'line-height')){
										$value = headway_get_element_property_value('font', $nice_id, 'line-height');
									} else {
										$value = 100;
									}

									$line_height_options[$nice_id] .= '<option value="'.$percent.'"'.headway_option_value($percent, $value).'>'.$percent.'%</option>';
								}
								
								$return .= '<tr'.$alt.'>
									<th scope="row"><label for="fonts-'.$nice_id.'-line-height">Line Height</label></th>
									<td>
										<select id="font-'.$nice_id.'-line-height" name="fonts['.$nice_id.'][line-height]" class="font-select line-height headway-visual-editor-font-input" selector="'.$selector.'">
												'.$line_height_options[$nice_id].'
										</select>
									</td>
								</tr>';
							}
							
							
							
						}
						
						if(isset($element[4]) && $element[4]){														
							$icon['bold'] = (headway_get_element_property_value('font', $nice_id, 'font-weight') == 'bold') ? ' ve-icon-depressed' : '';
							$icon['italic'] = (headway_get_element_property_value('font', $nice_id, 'font-style') == 'italic') ? ' ve-icon-depressed' : '';
							$icon['underline'] = (headway_get_element_property_value('font', $nice_id, 'text-decoration') == 'underline') ? ' ve-icon-depressed' : '';
							$icon['small-caps'] = (headway_get_element_property_value('font', $nice_id, 'font-variant') == 'small-caps') ? ' ve-icon-depressed' : '';
							$icon['uppercase'] = (headway_get_element_property_value('font', $nice_id, 'text-transform') == 'uppercase') ? ' ve-icon-depressed' : '';
							$icon['lowercase'] = (headway_get_element_property_value('font', $nice_id, 'text-transform') == 'lowercase') ? ' ve-icon-depressed' : '';
							
							$icon['align-left'] = (headway_get_element_property_value('font', $nice_id, 'text-align') == 'left') ? ' ve-icon-depressed' : '';
							$icon['align-center'] = (headway_get_element_property_value('font', $nice_id, 'text-align') == 'center') ? ' ve-icon-depressed' : '';
							$icon['align-right'] = (headway_get_element_property_value('font', $nice_id, 'text-align') == 'right') ? ' ve-icon-depressed' : '';
							
							if(!is_array($element[4]) || (is_array($element[4]) && in_array('styling', $element[4]))){
								$font_property_row_count++;
								
								$alt = $font_property_row_count%2 ? null : ' alt';
								
								$return .= '
								<tr class="additional-font-properties'.$alt.'">
									<th scope="row"><label>Font Styling</label></th>
									<td>
										<span element="'.$nice_id.'" title="Bold" class="ve-tooltip ve-icon-style-toggle ve-icon-bold'.$icon['bold'].'"></span>
										<span element="'.$nice_id.'" title="Italics" class="ve-tooltip ve-icon-style-toggle ve-icon-italic'.$icon['italic'].'"></span>					
										<span element="'.$nice_id.'" title="Underline" class="ve-tooltip ve-icon-style-toggle ve-icon-underline'.$icon['underline'].'"></span>					
									</td>
								
								
									<td style="display: none;">
									<input id="font-'.$nice_id.'-font-weight-hidden" type="hidden" value="normal" class="headway-visual-editor-font-input" name="fonts['.$nice_id.'][font-weight]" />
									<input id="font-'.$nice_id.'-font-weight" selector="'.$selector.'" type="checkbox" value="bold" name="fonts['.$nice_id.'][font-weight]" class="headway-visual-editor-font-input ve-check font-check font-weight"'.headway_checkbox_value_custom('bold', headway_get_element_property_value('font', $nice_id, 'font-weight')).' />
							
									<input id="font-'.$nice_id.'-font-style-hidden" type="hidden" value="normal" class="headway-visual-editor-font-input" name="fonts['.$nice_id.'][font-style]" />
									<input id="font-'.$nice_id.'-font-style" selector="'.$selector.'" type="checkbox" value="italic" name="fonts['.$nice_id.'][font-style]" class="headway-visual-editor-font-input ve-check font-check font-style"'.headway_checkbox_value_custom('italic', headway_get_element_property_value('font', $nice_id, 'font-style')).' />

									<input id="font-'.$nice_id.'-text-decoration-hidden" type="hidden" value="none" class="headway-visual-editor-font-input" name="fonts['.$nice_id.'][text-decoration]" />
									<input id="font-'.$nice_id.'-text-decoration" selector="'.$selector.'" type="checkbox" value="underline" name="fonts['.$nice_id.'][text-decoration]" class="headway-visual-editor-font-input ve-check font-check text-decoration"'.headway_checkbox_value_custom('underline', headway_get_element_property_value('font', $nice_id, 'text-decoration')).' />
								
									</td>
								
								</tr>';
							}
							
							if(!is_array($element[4]) || (is_array($element[4]) && in_array('capitalization', $element[4]))){
								$font_property_row_count++;
								
								$alt = $font_property_row_count%2 ? null : ' alt';
								
								$return .= '
								<tr class="additional-font-properties'.$alt.'">
									<th scope="row"><label>Capitalization</label></th>
							
									<td>
										<span element="'.$nice_id.'" title="Small-Caps" class="ve-tooltip ve-icon-style-toggle ve-icon-small-caps'.$icon['small-caps'].'"></span>
										<span element="'.$nice_id.'" title="Uppercase" class="ve-tooltip ve-icon-style-toggle ve-icon-uppercase'.$icon['uppercase'].'"></span>
										<span element="'.$nice_id.'" title="Lowercase" class="ve-tooltip ve-icon-style-toggle ve-icon-lowercase'.$icon['lowercase'].'"></span>									
									</td>
								
									<td style="display: none;">
										<select id="font-'.$nice_id.'-text-transform" name="fonts['.$nice_id.'][text-transform]" class="font-select text-transform headway-visual-editor-font-input" selector="'.$selector.'">
												<option value="none"'.headway_option_value('none', headway_get_element_property_value('font', $nice_id, 'text-transform')).'>None</option>
												<option value="uppercase"'.headway_option_value('uppercase', headway_get_element_property_value('font', $nice_id, 'text-transform')).'>Uppercase</option>
												<option value="lowercase"'.headway_option_value('lowercase', headway_get_element_property_value('font', $nice_id, 'text-transform')).'>Lowercase</option>
										</select>


										<input id="font-'.$nice_id.'-font-variant-hidden" type="hidden" class="headway-visual-editor-font-input" value="normal" name="fonts['.$nice_id.'][font-variant]" />
										<input id="font-'.$nice_id.'-font-variant" selector="'.$selector.'" type="checkbox" value="small-caps" name="fonts['.$nice_id.'][font-variant]" class="headway-visual-editor-font-input ve-check font-check font-variant"'.headway_checkbox_value_custom('small-caps', headway_get_element_property_value('font', $nice_id, 'font-variant')).' />
									</td>
								</tr>';
							}
							
							if(!is_array($element[4]) || (is_array($element[4]) && in_array('align', $element[4]))){
								$font_property_row_count++;
								
								$alt = $font_property_row_count%2 ? null : ' alt';
								
								$return .= '
								<tr class="additional-font-properties'.$alt.'">
									<th scope="row"><label>Text Align</label></th>
							
									<td>
										<span element="'.$nice_id.'" title="Align Left" class="ve-tooltip ve-icon-style-toggle ve-icon-align-toggle ve-icon-align-left'.$icon['align-left'].'"></span>
										<span element="'.$nice_id.'" title="Align Center" class="ve-tooltip ve-icon-style-toggle ve-icon-align-toggle ve-icon-align-center'.$icon['align-center'].'"></span>
										<span element="'.$nice_id.'" title="Align Right" class="ve-tooltip ve-icon-style-toggle ve-icon-align-toggle ve-icon-align-right'.$icon['align-right'].'"></span>										
									</td>
								
									<td style="display: none;">
										<select id="font-'.$nice_id.'-text-align" name="fonts['.$nice_id.'][text-align]" class="font-select text-align headway-visual-editor-font-input" selector="'.$selector.'">
												<option value="left"'.headway_option_value('left', headway_get_element_property_value('font', $nice_id, 'text-align')).'>Left</option>
												<option value="center"'.headway_option_value('center', headway_get_element_property_value('font', $nice_id, 'text-align')).'>Center</option>
												<option value="right"'.headway_option_value('right', headway_get_element_property_value('font', $nice_id, 'text-align')).'>Right</option>
										</select>
									</td>
								</tr>';
							}
						
							if(!is_array($element[4]) || (is_array($element[4]) && in_array('letter-spacing', $element[4]))){
								$font_property_row_count++;
								
								$alt = $font_property_row_count%2 ? null : ' alt';
								
								$return .= '
								<tr class="additional-font-properties'.$alt.'">
									<th scope="row"><label>Letter Spacing</label></th>
							
									<td>
										<select id="font-'.$nice_id.'-letter-spacing" name="fonts['.$nice_id.'][letter-spacing]" class="font-select letter-spacing headway-visual-editor-font-input" selector="'.$selector.'">
												<option value="0px"'.headway_option_value('0px', headway_get_element_property_value('font', $nice_id, 'letter-spacing')).'>None</option>
												<option value="1px"'.headway_option_value('1px', headway_get_element_property_value('font', $nice_id, 'letter-spacing')).'>1px</option>
												<option value="2px"'.headway_option_value('2px', headway_get_element_property_value('font', $nice_id, 'letter-spacing')).'>2px</option>
												<option value="3px"'.headway_option_value('3px', headway_get_element_property_value('font', $nice_id, 'letter-spacing')).'>3px</option>
												<option value="4px"'.headway_option_value('4px', headway_get_element_property_value('font', $nice_id, 'letter-spacing')).'>4px</option>
												<option value="-1px"'.headway_option_value('-1px', headway_get_element_property_value('font', $nice_id, 'letter-spacing')).'>-1px</option>
												<option value="-2px"'.headway_option_value('-2px', headway_get_element_property_value('font', $nice_id, 'letter-spacing')).'>-2px</option>
										</select>								
									</td>
								</tr>
								';
							}
						}
						
						$return .= '</table>';
					}
					
				break;
			}
			
		}
	}
	
	return $return;
}


function headway_create_visual_editor_widget($id, $title, $content){
	echo '<div id="'.$id.'-widget" class="collapsable collapsed">
		<h4 class="collapsable-header"><a href="#">'.$title.'</a></h4>

		<div class="collapsable-content">';
		
		call_user_func($content);
			
	echo '</div>
	</div>';
}

function headway_build_visual_editor_input($args){
	$defaults = array(
		'text_left' => false,
		'text_right' => false,
		'value' => false,
		'border_top' => false,
		'unit' => false,
		'tooltip' => false,
		'name_array' => 'headway-config',
		'hidden' => false,
		'input_style' => false,
		'input_class' => 'headway-visual-editor-input',
		'row_id' => false
	);
	
	extract($defaults);
	extract($args, EXTR_OVERWRITE);
	
	if($hidden) $hidden = ' style="display:none;"';
	
	if($border_top) $border_top = ' class="border-top"';
	
	if($tooltip){
		$tooltip_class = ' class="ve-tooltip"';
		$tooltip = ' title="'.$tooltip.'"';
	}		
	
	if(!$value) $value = headway_get_option($id);
	
	if(!$row_id) $row_id = $id;
	
	if($type == 'check' || $type == 'checkbox'){
		$checked = ($value == 'true' || $value == 'on' || $value == '1') ? ' checked' : NULL;
		echo '
			<tr'.$border_top.$hidden.' id="'.$row_id.'">					
				<th scope="row"><label for="'.$id.'"'.$tooltip.$tooltip_class.'>'.$text_left.'</label></th>					
				<td class="clearfix">
					<p class="radio-container">
						<input type="hidden" class="'.$input_class.'" name="'.$name_array.headway_disabled_input_name($id).'['.$id.']" value="DELETE" />
						<input type="checkbox" '.$input_style.'name="'.$name_array.headway_disabled_input_name($id).'['.$id.']" id="'.$id.'" value="on" class="radio headway-visual-editor-input"'.$checked.headway_disabled_input($id).' /><label for="'.$id.'">'.$text_right.'</label>						
					</p>	
				</td>				
			</tr>
		';
	}
	
	
	elseif($type == 'check-alt' || $type == 'checkbox-alt'){
		$checked = ($value == 'true' || $value == 'on' || $value == '1') ? ' checked' : NULL;
		echo '
					<p class="radio-container"'.$hidden.'>
						<input type="hidden" class="'.$input_class.'" name="'.$name_array.headway_disabled_input_name($id).'['.$id.']" value="DELETE" />
						<input type="checkbox" '.$input_style.'name="'.$name_array.headway_disabled_input_name($id).'['.$id.']" id="'.$id.'" value="on" class="headway-visual-editor-input radio"'.$checked.headway_disabled_input($id).' /><label for="'.$id.'">'.$text_right.'</label>						
					</p>	
		';
	}
	
	
	elseif($type == 'radio'){
		$return = '
			<tr'.$border_top.$hidden.' id="'.$row_id.'">					
				<th scope="row"><label for="'.$id.'"'.$tooltip.$tooltip_class.'>'.$text_left.'</label></th>					
				<td class="clearfix">
				<input type="hidden" class="'.$input_class.'" name="'.$name_array.headway_disabled_input_name($id).'['.$id.']" value="'.$value.'" id="'.$id.'-hidden" />';
		
		foreach($text_right as $item => $options){
			$checked = ($options['value'] == $value) ? ' checked="checked"' : NULL;
			$return .= '<p class="radio-container"'.$hidden.'><input '.$input_style.'type="radio" id="'.$options['id'].'" name="'.$id.'" onclick="jQuery(\'#\' + jQuery(this).attr(\'name\') + \'-hidden\').val(jQuery(this).val());" value="'.$options['value'].'" class="radio"'.$checked.headway_disabled_input($id).' /><label for="'.$options['id'].'">'.$item.'</label></p>';	
		}
				
		$return .= '</td>				
			</tr>
		';
		
		echo $return;
	}
	
	
	elseif($type == 'text'){
		if($unit) $unit = '<small>'.$unit.'</small>';
		
		echo '
			<tr'.$border_top.$hidden.' id="'.$row_id.'">					
				<th scope="row"><label for="'.$id.'"'.$tooltip.$tooltip_class.'>'.$text_left.'</label></th>					
				<td><input type="text" class="'.$input_class.'" '.$input_style.'name="'.$name_array.headway_disabled_input_name($id).'['.$id.']" id="'.$id.'" value="'.stripslashes(htmlspecialchars($value)).'"'.headway_disabled_input($id).' />'.$unit.'</td>				
			</tr>
		';
	}
	
}

