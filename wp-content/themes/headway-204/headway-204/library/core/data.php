<?php
function headway_import_style($args = array()){
	$defaults = array('file' => false, 'file_contents' => false, 'no_delete' => false, 'switch_to_style' => false, 'add_upload_path' => true);
	extract($defaults);
	extract($args, EXTR_OVERWRITE);
	
	if($file){
		if($add_upload_path){
			$upload_path = wp_upload_dir();
			$file = $upload_path['basedir'].'/'.$file;
		}
						
		//Open and delete file
		$handle = fopen($file, "rb");
		$file_contents = fread($handle, filesize($file));

		fclose($handle);
		if(!$no_delete) @unlink($file);
	} elseif(!$file && !$file_contents){
		return false;
	}

	//Echo the small JSON array for VE to display new style
	$contents = headway_json_decode($file_contents, true);

	//Generate random ID for style
	$random = rand(15, 9999);

	$trimmed['style-id'] = $random;
	$trimmed['style-name'] = $contents['style-name'] ? $contents['style-name'] : 'Unnamed Style';
	$trimmed['color-primary'] = $contents['color-primary'] ? $contents['color-primary'] : false;
	$trimmed['color-secondary'] = $contents['color-secondary'] ? $contents['color-secondary'] : false;
	$trimmed['color-tertiary'] = $contents['color-tertiary'] ? $contents['color-tertiary'] : false;

	//Add style into DB
	$styles = headway_get_option('styles', true, true);

	if(!$styles){
		headway_update_option('styles', array($trimmed['style-name'].'-'.$random => $trimmed));
	} else {
		$styles[$trimmed['style-name'].'-'.$random] = $trimmed;

		headway_update_option('styles', $styles);
	}	

	headway_update_option('style-'.$trimmed['style-name'].'-'.$random, $file_contents);
	
	
	if($switch_to_style){		
		foreach($contents['styles'] as $style){
			headway_update_element_style(headway_form_name_to_selector($style['element']), $style['property_type'], $style['property'], $style['value']);
		}
		
		headway_clear_cache();
	}
	
	//Return JSON
	return headway_json_encode($trimmed);
}


function headway_import_leaf_template($args = array()){
	$defaults = array('file' => false, 'file_contents' => false);
	extract($defaults);
	extract($args, EXTR_OVERWRITE);
		
	if($file){
		$upload_path = wp_upload_dir();
		
		$file = $upload_path['basedir'].'/'.$file;
		
		//Open and delete file
		$handle = fopen($file, "rb");
		$file_contents = fread($handle, filesize($file));
	
		fclose($handle);
		@unlink($file);
	} elseif(!$file_contents && !$file) {
		return false;
	}

	//Fetch the small JSON array for VE to display new template
	$contents = headway_json_decode($file_contents, true);

	//Generate random ID for style
	$random = rand(15, 9999);

	$trimmed['id'] = $random;
	$trimmed['name'] = $contents['name'] ? $contents['name'] : 'Unnamed Template';
	
	//Add template into DB
	$templates = headway_get_option('leaf-templates', true, true);

	if(!$templates){
		headway_update_option('leaf-templates', array($trimmed['name'].'-'.$random => $trimmed));
	} else {
		$templates[$trimmed['name'].'-'.$random] = $trimmed;

		headway_update_option('leaf-templates', $templates);
	}
	

	headway_update_option('leaf-template-'.$trimmed['name'].'-'.$random, $file_contents);
	
	//Return JSON
	return headway_json_encode($trimmed);
}