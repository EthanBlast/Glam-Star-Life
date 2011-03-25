<?php
function headway_css_elements_generated(){
	$return = '';
	
	$query = headway_get_element_styles();

	//Change the elements from a multi-dimensional array to a single-dimension array.
	$elements = array();

	foreach(headway_get_elements() as $group => $group_elements){
		foreach($group_elements as $element){
			$elements[$element[0]] = $element;
		}
	}

	foreach($query as $style){
		$styling[$style['element']][$style['property']] = $style['value'];
	}

	$i = 0;

	foreach($styling as $selector => $properties){	
		$i++;

		//Check for specific selector
		$css_selector = (isset($elements[$selector][5]) && $elements[$selector][5]) ? $elements[$selector][5] : $elements[$selector][0];

		if(!$css_selector || (strpos($css_selector, '-period-') !== false || strpos($css_selector, '-pound-') !== false)) continue;

		$compiled_properties = '';
		
		foreach($properties as $property => $value){
			$tab = (count($properties) != 1) ? "\n\t" : ' ';

			if($property == 'background'){
				$property = 'background-color';

				if($properties['background-transparent'] == 'on'){
					continue;
				}
			}

			if($property == 'text-align' && $value == 'left') continue;

			if($property == 'background-transparent'){ 
				$property = 'background';
				$value = ($value == 'on') ? 'transparent' : false;

				unset($properties['background']);
			}

			if($value == 'zero') $value = '0';

			if($property == 'border') $property = 'border-color';
			if($property == 'top-border' || $property == 'border-top') $property = 'border-top-color';
			if($property == 'right-border' || $property == 'border-right') $property = 'border-right-color';
			if($property == 'bottom-border' || $property == 'border-bottom') $property = 'border-bottom-color';
			if($property == 'left-border' || $property == 'border-left') $property = 'border-left-color';

			if($property == 'border-width') $property = 'border-width';
			if($property == 'top-border-width') $property = 'border-top-width';
			if($property == 'right-border-width') $property = 'border-right-width';
			if($property == 'bottom-border-width') $property = 'border-bottom-width';
			if($property == 'left-border-width') $property = 'border-left-width';

			if($property == 'line-height'){
				if((int)$value > 500){
					$value = '120';

					headway_update_element_style($elements[$selector][0], 'font', 'line-height', $value);
				}

				$value = $value.'%';	
			}

			if($property == 'font-size') $value = $value.'px';		
			if(strpos($property, '-width')) $value = $value.'px';

			if($property == 'font-family'){
				//Check if old
				if(strpos($value, ',') !== false){
					$fonts = explode(', ', $value);

					$fixed_fonts = array();

					foreach($fonts as $font){
						if(strpos($font, ' ') !== false){
							$fixed_fonts[] = '"'.$font.'"';
						} else {
							$fixed_fonts[] = $font;
						}
					}

					$value = implode(', ', $fixed_fonts);
				} else {
					$value = headway_get_font_family($value);
				}
			}

			if(strlen($value) == 6 && strpos($property, 'color') !== false) $value = '#'.$value;

			if(isset($properties['inherit-colors']) && $properties['inherit-colors'] == 'on'){
				if($property == 'color' || $property == 'background' || $property == 'background-color'){
					continue;
				}
			}

			$compiled_properties .= (!isset($no_echo[$value]) && $value && $property != 'inherit-colors') ? $tab.$property.':'.$value.'; ' : NULL;		
		}
		
		if($compiled_properties !== ''){
			$return .= $css_selector.' {';
		
			$return .= $compiled_properties;
		
			$return .= '}';
		}

		if($i != count($styling)) $return .= "\n\n";
	}
	
	return $return;
}