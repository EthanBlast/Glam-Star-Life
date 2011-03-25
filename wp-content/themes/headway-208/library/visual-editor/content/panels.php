<?php
function headway_visual_editor_content(){	
	$body_background_image_delete_display = (headway_get_option('body-background-image') && headway_get_option('body-background-image') != 'DELETE') ? NULL : 'display: none;';
	$body_background_image = headway_get_option('body-background-image');
	
	$elements_options = '';
	
	foreach(headway_get_elements() as $opt_group => $elements){
		$elements_options .= '<optgroup label="'.$opt_group.'">';
		
			foreach($elements as $element){
				$colors = (isset($element[2]) && $element[2]) ? 'true' : 'false';
				$fonts = (isset($element[3]) && $element[3] || isset($element[4]) && $element[4]) ? 'true' : 'false';
				$noclick = (isset($element[6]) && $element[6]) ? 'true' : 'false';
				
				$selector = (isset($element[5]) && $element[5] !== false) ? $element[5] : $element[0];
				
				$elements_options .= '<option value="'.$element[0].'" id="option-'.headway_selector_to_form_name($element[0]).'" colors="'.$colors.'" fonts="'.$fonts.'" noclick="'.$noclick.'" selector="'.$selector.'">'.$element[1].'</option>';
			}
	
		$elements_options .= '</optgroup>';
	}
	
	headway_get_elements_cache();
?>
<div class="tabs">
    <ul class="clearfix tabs">
		<li><a href="#de-tab"><?php _e('Design Editor', 'headway'); ?></a></li>
		<li><a href="#de-styles-tab"><?php _e('Styles', 'headway'); ?></a></li>
		<?php if(!headway_get_skin_option('disable-body-background-image')){ ?>
		<li><a href="#de-backgrounds-tab"><?php _e('Background', 'headway'); ?></a></li>
		<?php } ?>		    
    </ul>

	<div id="de-tab">
		<?php if(!headway_get_option('disable-inspector')){ ?>
		<div id="inspector-container" class="sub-box">
			<span class="sub-box-heading"><?php _e('Inspector', 'headway'); ?></span>
			<div class="sub-box-content">
				<div id="inspector"><?php _e('Simply hover an element to get more information about it.  Click it to style it.', 'headway'); ?></div>
			</div>
		</div>
		<?php } ?>

		<div id="dropdown-container" class="sub-box">
			<span class="sub-box-heading"><?php _e('Element Selector', 'headway'); ?></span>
			<div class="sub-box-content">
				<p><?php _e('Select an element below that you would like to style.', 'headway'); ?></p>
				
				<?php if(!headway_get_option('disable-inspector')){ ?><p><strong><?php _e('Tip:', 'headway'); ?></strong> <?php _e('Use the inspector above and click on any element you would like to style and the settings will show up.', 'headway'); ?></p><?php } ?>
				
				<select name="element-selector" id="element-selector">
					<option value="" id="element-selector-blank"></option>
					<?php echo $elements_options; ?>
				</select>
				<p id="callout" style="display:none;"><a href="#" class="button small-button"><?php _e('Call This Element Out', 'headway'); ?></a></p>
			</div>
		</div>

		<div id="colors" style="display: none;" class="sub-box">
			<span class="sub-box-heading"><?php _e('Colors:', 'headway'); ?> <span id="colors-heading"></span></span>
			<div class="sub-box-content">
				<div id="colors-inputs">
					<?php echo headway_create_element_inputs('colors', headway_get_elements()); ?>
				</div>
			</div>
		</div>

		<div id="fonts" style="display: none;" class="sub-box">
			<span class="sub-box-heading"><?php _e('Fonts:', 'headway'); ?> <span id="fonts-heading"></span></span>
			<div class="sub-box-content">
				<div id="fonts-inputs">
					<?php echo headway_create_element_inputs('fonts', headway_get_elements()); ?>
				</div>
			</div>
		</div>
	</div>

	<div id="de-styles-tab">				
		<input type="hidden" value="" name="load-style" id="load-style-hidden" />
		
		<?php
		$styles = headway_get_option('styles');
		
		$hide_message = is_array($styles) ? ' style="display: none;"' : false;
		$hide_load_button = !is_array($styles) ? ' style="display: none;"' : false;
		?>
		
		<p class="info-box"><?php _e('To view a style, click on the style to select it then click <strong>Load Style</strong>.  If you are satisfied with how a style looks, you must save the visual editor using the "Save All Changes" button in the bottom-right.', 'headway'); ?></p>
		
		<div id="style-selector" class="headway-custom-select">
			<?php
			if(is_array($styles)){

				foreach($styles as $style => $options){					
					if($options['color-primary']){
						$colors = '<div class="color-preview" style="background:#'.$options['color-tertiary'].';"></div><div class="color-preview" style="background:#'.$options['color-secondary'].';"></div><div class="color-preview" style="background:#'.$options['color-primary'].';"></div>';
					}
										
					echo '<div class="select-option" id="style-'.$options['style-id'].'"><span class="select-option-text">'.preg_replace('/%u0*([0-9a-fA-F]{1,5})/', '&#x\1;', $options['style-name']).'</span>'.$colors.'<a href="#" class="style-select-edit select-edit">Edit</a></div>';
					
					unset($colors);
				}

			}
			?>
		</div>
			
		<a href="" id="load-style" style="display: none;" class="button"<?php echo $hide_load_button; ?>><?php _e('Load Style', 'headway'); ?></a>
		
		<p class="info-box" id="no-styles"<?php echo $hide_message; ?>><?php _e('You do not have any styles.  Use the import functionality (under Tools) to import a style or use the Save Current Style button below to save your current design preferences.', 'headway'); ?></p>
		
		<a href="" id="save-style" class="button"><?php _e('Save Current Style', 'headway'); ?></a>
				
		<p class="clearfix headway-small-text"><?php _e('For more styles, use the import feature under <strong>Tools &raquo; Import</strong>.', 'headway'); ?></p>
	</div>

	<div id="de-backgrounds-tab">
		<div class="sub-box minimize" id="body-background-image-options">
			<span class="sub-box-heading"><?php _e('Body Background Image', 'headway'); ?></span>

			<div class="sub-box-content">
				<table class="tab-options full-width-table">
					<tr>
						<td class="clearfix">
							<input type="hidden" class="headway-visual-editor-input" name="headway-config[body-background-image]" id="body-background-image-hidden" value="<?php echo $body_background_image ?>" />
							<div style="margin: 0 0 5px;" id="body-background-image"></div>	
						</td> 
					</tr>

					<?php
					headway_build_visual_editor_input(array(
						'type' => 'text',
						'id' => 'body-background-image-url',
						'text_left' => __('OR Link Directly To Background Image', 'headway')
					));
					?>

					<tr id="body-background-image-current-row" style="<?php echo $body_background_image_delete_display ?>">
						<td colspan="2">
							<span id="body-background-image-current"><?php echo headway_get_option('body-background-image') ?></span> 
							<a id="body-background-image-delete" href="#"><?php _e('Delete', 'headway'); ?></a>
						</td>
					</tr>

					<?php 
					$repeat_value = headway_get_option('body-background-repeat') ? headway_get_option('body-background-repeat') : 'repeat';

					headway_build_visual_editor_input(array(
						'type' => 'radio', 
						'id' => 'body-background-repeat', 
						'text_left' => __('Background Tiling', 'headway'), 
						'text_right' => array(
											__('Tile', 'headway') => array('id' => 'background-repeat', 'value' => 'repeat'),
											__('Tile Horizontally', 'headway') => array('id' => 'background-repeat-x', 'value' => 'repeat-x'),
											__('Tile Vertically', 'headway') => array('id' => 'background-repeat-y', 'value' => 'repeat-y'),
											__('No Tiling', 'headway') => array('id' => 'background-no-repeat', 'value' => 'no-repeat')
										), 
						'value' => $repeat_value, 
						'tooltip' => __('Choose how you would like the body background image to be tiled.', 'headway')
					)); 
					?>
				</table>
			</div>
		</div>
	</div>
</div>
<?php
}


function headway_skins_content(){	
	$no_active_skin = !headway_is_skin_active() ? ' class="selected"' : false;
?>	
<div id="skins-tab">
	<select id="skins-selector" name="headway-config[active-skin]" class="headway-visual-editor-input">
		<option value="none">&mdash;<?php _e('No Skin', 'headway'); ?>&mdash;</option>
		<?php do_action('headway_skins_selector'); ?>
	</select>
					
	<ul class="thumbnail-grid clearfix">
		<li id="none"<?php echo $no_active_skin; ?>>
			<a href="#">
				<img src="<?php echo get_bloginfo('template_directory') ?>/library/visual-editor/images/headway_default.png"/><em><?php _e('No Skin (Default)', 'headway'); ?><br /><small><?php _e('Use Design Editor', 'headway'); ?></small></em>
			</a>
		</li>
									
		<?php do_action('headway_skins_thumbnails'); ?>
	</ul>
	
	<a class="button box-button" target="_blank" href="" id="preview-skin"><?php _e('Preview Skin', 'headway'); ?></a>
	<a class="button box-button" target="_blank" href="" id="activate-skin"><?php _e('Activate Skin', 'headway'); ?></a>
	
	<p class="info-box clearfix" style="display: none;" id="skin-notification"><?php _e('To finish activating the skin you have selected, save your changes by clicking the "Save All Changes" button the bottom right then reload the visual editor.', 'headway'); ?></p>
		
	<p class="info-box clearfix"><?php _e('For more skins, go to the <a class="keep-active" target="_blank" href="http://headwaythemes.com/members">Members\' Dashboard</a>.', 'headway'); ?></p>		
</div>
<?php
}


function headway_leafs_panel_content(){
?>
<div class="tabs">

	<ul class="clearfix tabs">
	    <li><a href="#leafs-actions-tab"><?php _e('Actions', 'headway'); ?></a></li>
	    <?php if(!headway_is_page_linked()){ ?>
		<li><a href="#leafs-add-tab"><?php _e('Add', 'headway'); ?></a></li>
		<li><a href="#leafs-columns-tab"><?php _e('Columns', 'headway'); ?></a></li>
		<li><a href="#leafs-templates-tab"><?php _e('Templates', 'headway'); ?></a></li>
		<?php } ?>
	</ul>
<?php	
	if(headway_is_page_linked()){
?>
	<div id="leafs-actions-tab">
		<p class="info-box clearfix"><?php _e('If you wish to customize the layout for this page, go to the linking options in the top menu and disable linking (set both select boxes to the <em>Do Not Link</em> option then save). To finish the process, save and reload the visual editor.', 'headway'); ?></p>
	</div>
<?php
	} else {
?>
	<div id="leafs-actions-tab">
		<ul class="list-buttons actions-buttons">
			<li class="arrange"><span><?php _e('Arrange Leafs', 'headway'); ?></span> <a href="" id="toggle-arrange" class="button">Enable</a></li>
			<li class="resize"><span><?php _e('Resize Leafs', 'headway'); ?></span> <a href="" id="toggle-resize" class="button">Enable</a></li>
			<?php if((int)headway_get_page_option(headway_current_page(true), 'leaf-columns') > 1){ ?>
			<li class="column-arrange-resize"><span><?php _e('Arrange &amp; Resize Columns', 'headway'); ?></span> <a href="" id="toggle-column-arrange-resize" class="button">Enable</a></li>
			<?php } ?>
		</ul>
	</div>


	<div id="leafs-add-tab">
		<ul class="add-leafs list-buttons list-small-buttons">
			<li class="content"><img src="<?php echo get_bloginfo('template_directory'); ?>/library/leafs/icons/content.png" width="16px" height="16px" /><span>Content</span> <a href="" id="add-content" class="button small-button"><?php _e('Add', 'headway'); ?></a></li>
			
			<li class="sidebar alt"><img src="<?php echo get_bloginfo('template_directory'); ?>/library/leafs/icons/sidebar.png" width="16px" height="16px" /><span>Widget Ready Sidebar</span> <a href="" id="add-sidebar" class="button small-button"><?php _e('Add', 'headway'); ?></a></li>
			
			<?php
			global $default_leafs;
			global $custom_leafs;

			$leaf_count = 0;

			$leafs = '';
			
			asort($default_leafs);
			asort($custom_leafs);
			
			function display_leaf_buttons($input){
				if(!is_array($input)) return false;

				foreach($input as $leaf_type => $leaf_button_options){
					//If leaf is content or sidebar, skip it so it is treated as a special leaf.
					if($leaf_type == 'content' || $leaf_type == 'sidebar') continue;

					//Add alt class on events.
					$alt[$leaf_type] = ($leaf_count%2) ? ' alt' : false;

					//Set default icons
					if(!$leaf_button_options['icon']) $leaf_button_options['icon'] = get_bloginfo('template_directory').'/library/leafs/icons/default.png';

					$leafs .= '<li class="'.$leaf_type.$alt[$leaf_type].'"><img src="'.$leaf_button_options['icon'].'" width="16px" height="16px" /><span>'.$leaf_button_options['name'].'</span> <a href="" id="add-'.$leaf_type.'" class="button small-button">'.__('Add', 'headway').'</a></li>';

					$leaf_count++;
				}
				
				echo $leafs;
			}

			display_leaf_buttons($default_leafs);
			display_leaf_buttons($custom_leafs);
			?>
		</ul>

		<p class="info-box clearfix"><?php _e('For more leafs, go to the <a class="keep-active" target="_blank" href="http://headwaythemes.com/members">Members\' Dashboard</a>.', 'headway'); ?></p>		
	</div>


	<div id="leafs-columns-tab">
		
		<p class="info-box" style="margin-top: 0;"><?php _e('Changing these settings will change the setup for this page only.', 'headway'); ?><br /><br /><strong><?php _e('NOTE:', 'headway'); ?></strong> <?php _e('Modifying these settings may change your layout drastically.  If so, rearrange your site using the new columns.', 'headway'); ?></p>
	
		<p class="info-box" id="leaf-page-setup-reload-notice" style="margin-top: 0; display: none;"><?php _e('In order to use the new columns and containers, you must <strong>click save in the bottom-right</strong> and <strong>reload the visual editor</strong>.', 'headway'); ?></p>
		
		<div class="sub-box minimize" id="leaf-columns-options">
			<span class="sub-box-heading"><?php _e('Columns', 'headway'); ?></span>

			<div class="sub-box-content">
				<table class="tab-options full-width-table">

					<tr id="leafs-columns">					
						<th scope="row"><label><?php _e('Leaf Columns', 'headway'); ?></label></th>					
						<td>
							<p class="radio-container">
								<select name="page-config[leaf-columns]" id="leaf-columns" class="headway-visual-editor-input">
									<?php
									$columns = (int)headway_get_page_option(headway_current_page(), 'leaf-columns');

									$leaf_containers_subbox_display = (!$columns || $columns === 1) ? ' style="display: none;"' : false;

									for($i = 1; $i <= 4; $i++){								
										$selected = ($i == (int)$columns) ? ' selected' : false;
										echo '<option value="'.$i.'"'.$selected.'>'.$i.'</option>';
									}
									?>
								</select>
							</p>
						</td>				
					</tr>
										
					<tr>					
						<td style="padding: 10px 0;">
							<?php 
							headway_build_visual_editor_input(array(
								'type' => 'check-alt', 
								'id' => 'disable-equal-column-heights', 
								'text_right' => __('Disable Equal Column Heights', 'headway'), 
								'value' => headway_get_skin_option('disable-equal-column-heights')
							));
							?>
						</td>
					</tr>

				</table>
			</div>
		</div>
				
		<div class="sub-box minimize leaf-column-settings" id="leaf-columns-widths"<?php echo $leaf_containers_subbox_display; ?>>
			<span class="sub-box-heading"><?php _e('Column Widths', 'headway'); ?></span>

			<div class="sub-box-content">				
				<table class="tab-options" id="column-widths">
										
					<?php
					$total_width = 0;
					$fixed_width = 0;

					for($i = headway_get_page_option(headway_current_page(), 'leaf-columns')+1; $i <= 4; $i++){
						headway_delete_page_option(headway_current_page(), 'column-'.$i.'-width');
					}
					
					//Add up column widths to get total
					for($i = 1; $i <= headway_get_page_option(headway_current_page(), 'leaf-columns'); $i++){
						$total_width = $total_width + headway_get_page_option(headway_current_page(), 'column-'.$i.'-width') + 20;
					}
					
					//If the total is within 10px of the wrapper width, add the difference to the last column							
					if($total_width < headway_get_skin_option('wrapper-width') && $total_width >= headway_get_skin_option('wrapper-width')-10){							
						$difference = headway_get_skin_option('wrapper-width') - $total_width;
													
						headway_update_page_option($page, 'column-'.$leaf_columns.'-width', headway_get_page_option($page, 'column-'.$leaf_columns.'-width')+$difference);
					}
					
					//If the total doesn't match the wrapper width by a long shot, make all columns equal to match wrapper.					
					if((int)$total_width !== 0 && !($total_width <= headway_get_skin_option('wrapper-width') && $total_width >= headway_get_skin_option('wrapper-width')-10)){
						$divide_from_this = headway_get_skin_option('wrapper-width') - (headway_get_page_option($page, 'leaf-columns')*20);
						$fixed_width = $divide_from_this / headway_get_page_option($page, 'leaf-columns');
					}
					
					for($i = 1; $i <= (int)headway_get_page_option(headway_current_page(), 'leaf-columns'); $i++){
						$width_value = headway_get_page_option(headway_current_page(), 'column-'.$i.'-width');
						
						if($fixed_width !== 0){
							headway_update_page_option(headway_current_page(), 'column-'.$i.'-width', $fixed_width);
							
							$width_value = $fixed_width;
						}
						
						headway_build_visual_editor_input(array(
							'name_array' => 'page-config',
							'type' => 'text', 
							'id' => 'column-'.$i.'-width', 
							'row_id' => 'column-'.$i.'-width-row',
							'text_left' => 'Column '.$i,
							'value' => $width_value,
							'input_style' => 'style="width:35px;"',
							'input_class' => 'headway-visual-editor-input column-width-input',
							'unit' => 'px'
						));
					}
					?>
				
				</table>
			</div>
		</div>
		
		<div class="sub-box minimize leaf-column-settings" id="leaf-containers-options"<?php echo $leaf_containers_subbox_display; ?>>
			<span class="sub-box-heading"><?php _e('Extra Leaf Containers', 'headway'); ?></span>

			<div class="sub-box-content">
				<table class="tab-options full-width-table">

					<tr>					
						<td>
							<?php
							headway_build_visual_editor_input(array(
								'name_array' => 'page-config',
								'type' => 'check-alt', 
								'id' => 'show-top-leafs-container', 
								'text_right' => __('Show Top Leafs Container', 'headway'), 
								'value' => headway_get_page_option(headway_current_page(), 'show-top-leafs-container')
							));
							
							headway_build_visual_editor_input(array(
								'name_array' => 'page-config',
								'type' => 'check-alt', 
								'id' => 'show-bottom-leafs-container', 
								'text_right' => __('Show Bottom Leafs Container', 'headway'), 
								'value' => headway_get_page_option(headway_current_page(), 'show-bottom-leafs-container')
							));
							?>
						</td>				
					</tr>

				</table>
			</div>
		</div>

	</div>
	
	
	<div id="leafs-templates-tab">
		<?php
		$templates = headway_get_option('leaf-templates');
		
		$hide_message = (is_array($templates) && count($templates) >= 1) ? ' style="display: none;"' : false;
		$hide_info = (!is_array($templates) || count($templates) === 0) ? ' style="display: none;"' : false;
		?>
		
		<p class="info-box" id="load-template-message" <?php echo $hide_info; ?>><?php _e('To load a leaf template, click on the template to select it then click <strong>Load Template</strong>.  Clicking Load Template will automatically save all changes and reload the visual editor.', 'headway'); ?></p>
		
		<div id="template-selector" class="headway-custom-select">
			<?php
			if(is_array($templates)){
				$default = headway_get_option('default-leafs-template');
				
				foreach($templates as $template => $options){		
					$selected = ($options['id'] == $default['id']) ? '<small id="default-template">Default</small>' : false;
					
					echo '<div class="select-option" id="template-'.$options['id'].'"><span class="select-option-text">'.preg_replace('/%u0*([0-9a-fA-F]{1,5})/', '&#x\1;', $options['name']).$selected.'</span><a href="#" class="template-select-edit select-edit">Edit</a></div>';
				}
			}
			?>
		</div>
			
		<a href="" id="load-template" class="button" style="display: none;"><?php _e('Load Template', 'headway'); ?></a>
		
		<p class="info-box" id="no-templates"<?php echo $hide_message; ?>><?php _e('You do not have any leaf templates.  Use the import functionality (under Tools) to import a leaf template or use the Save Current Leafs button below to save the leafs on this page as a template.', 'headway'); ?></p>
		
		<a href="" id="save-template" class="button"><?php _e('Save Current Page Layout', 'headway'); ?></a>
	</div>
<?php
	}
?>
	
</div>
<?php
}


function headway_site_dimensions_content(){	
?>
<div class="tabs">
    <ul class="clearfix tabs">
		<?php if(!(headway_get_skin_option('wrapper-width', true) && headway_get_skin_option('wrapper-vertical-margin', true) && headway_get_skin_option('wrapper-border-radius', true))){ ?>
        <li><a href="#wrapper-tab"><?php _e('Wrapper', 'headway'); ?></a></li>
		<?php } ?>
		
		<?php if(!(headway_get_skin_option('leaf-margins', true) && headway_get_skin_option('leaf-padding', true) && headway_get_skin_option('leaf-container-horizontal-padding', true) && headway_get_skin_option('leaf-container-vertical-padding', true) && headway_get_skin_option('leaf-border-radius', true))){ ?>
		<li><a href="#leafs-options-tab"><?php _e('Leafs', 'headway'); ?></a></li>
		<?php } ?>
    </ul>
				
    <div id="wrapper-tab">
		<table class="tab-options" id="site-dimensions-options">	
			
			<?php if(!headway_get_skin_option('wrapper-width', true)){ ?>					
			<tr>
				<th scope="row"><label for="wrapper-margin"><?php _e('Wrapper Width', 'headway'); ?></label></th>
				
				<td>								
					<div id="wrapper-width-slider"></div>
					
					<p class="slider-value">
						<input type="text" style="width: 35px;" class="headway-visual-editor-input" value="<?php echo str_replace('px', '', headway_get_skin_option('wrapper-width')) ?>" name="headway-config[wrapper-width]" id="wrapper-width" />
						<span class="unit">px</span>
					</p>
				</td>
			</tr>
			<?php } ?>
			
			
			<?php if(!headway_get_skin_option('wrapper-vertical-margin', true)){ ?>					
			<tr>
				<th scope="row"><label for="wrapper-vertical-margin"><?php _e('Wrapper Vertical Margin', 'headway'); ?></label></th>
				
				<td>								
					<div id="wrapper-vertical-margin-slider" class="horizontal-slider"></div>
					
					<p class="slider-value">
						<input type="text" style="width: 35px;" class="headway-visual-editor-input" value="<?php echo str_replace('px', '', headway_get_skin_option('wrapper-vertical-margin')) ?>" name="headway-config[wrapper-vertical-margin]" id="wrapper-vertical-margin" />
						<span class="unit">px</span>
					</p>
				</td>
			</tr>
			<?php } ?>
			
			<?php if(!headway_get_skin_option('wrapper-border-radius', true)){ ?>					
			<tr>
				<th scope="row"><label for="wrapper-border-radius"><?php _e('Wrapper Rounded Corners Radius', 'headway'); ?></label></th>
		
				<td>								
					<div id="wrapper-border-radius-slider" class="horizontal-slider"></div>
			
					<p class="slider-value">
						<input type="text" style="width: 35px;" class="headway-visual-editor-input" value="<?php echo str_replace('px', '', headway_get_skin_option('wrapper-border-radius')) ?>" name="headway-config[wrapper-border-radius]" id="wrapper-border-radius" />
						<span class="unit">px</span>
					</p>
				</td>
			</tr>
			<?php } ?>

		</table>
    </div>
		
	<div id="leafs-options-tab">
		<p class="info-box clearfix"><?php _e('<strong>Hint:</strong> Enable Leaf resizing to see the padding and margins displayed visually.', 'headway'); ?></p>
		
		<table class="full-width-table">
			
				<?php if(!headway_get_skin_option('leaf-margins', true)){ ?>
				<tr>
					<th scope="row"><label for="leaf-margins"><?php _e('Leaf Margins', 'headway'); ?></label></th>

					<td>								
						<div id="leaf-margins-slider" class="horizontal-slider"></div>

						<p class="slider-value">
							<input type="text" style="width: 35px;" class="headway-visual-editor-input" value="<?php echo str_replace('px', '', headway_get_skin_option('leaf-margins')) ?>" name="headway-config[leaf-margins]" id="leaf-margins" />
							<span class="unit">px</span>
						</p>
					</td>
				</tr>
				<?php } ?>
		
				<?php if(!headway_get_skin_option('leaf-padding', true)){ ?>
				<tr class="margin-bottom">
					<th scope="row"><label for="leaf-padding"><?php _e('Leaf Padding', 'headway'); ?></label></th>

					<td>								
						<div id="leaf-padding-slider" class="horizontal-slider"></div>

						<p class="slider-value">
							<input type="text" style="width: 35px;" class="headway-visual-editor-input" value="<?php echo str_replace('px', '', headway_get_skin_option('leaf-padding')) ?>" name="headway-config[leaf-padding]" id="leaf-padding" />
							<span class="unit">px</span>
						</p>
					</td>
				</tr>
				<?php } ?>
			
				<?php if(!headway_get_skin_option('leaf-container-horizontal-padding', true)){ ?>
				<tr class="border-top">
					<th scope="row"><label for="leaf-container-padding"><?php _e('Leaf Container Horizontal Padding', 'headway'); ?></label></th>
			
					<td>								
						<div id="leaf-container-horizontal-padding-slider" class="horizontal-slider"></div>
				
						<p class="slider-value">
							<input type="text" style="width: 35px;" class="headway-visual-editor-input" value="<?php echo str_replace('px', '', headway_get_skin_option('leaf-container-horizontal-padding')) ?>" name="headway-config[leaf-container-horizontal-padding]" id="leaf-container-horizontal-padding" />
							<span class="unit">px</span>
						</p>
					</td>
				</tr>
				<?php } ?>
		
				<?php if(!headway_get_skin_option('leaf-container-vertical-padding', true)){ ?>
				<tr>
					<th scope="row"><label for="leaf-container-padding"><?php _e('Leaf Container Vertical Padding', 'headway'); ?></label></th>
			
					<td>								
						<div id="leaf-container-vertical-padding-slider" class="horizontal-slider"></div>
				
						<p class="slider-value">
							<input type="text" style="width: 35px;" class="headway-visual-editor-input" value="<?php echo str_replace('px', '', headway_get_option('leaf-container-vertical-padding')) ?>" name="headway-config[leaf-container-vertical-padding]" id="leaf-container-vertical-padding" />
							<span class="unit">px</span>
						</p>
					</td>
				</tr>
				<?php } ?>
				
				<?php if(!headway_get_skin_option('leaf-border-radius', true)){ ?>
				<tr class="border-top">
					<th scope="row"><label for="leaf-border-radius"><?php _e('Leafs Rounded Corners Radius', 'headway'); ?></label></th>

					<td>								
						<div id="leaf-border-radius-slider" class="horizontal-slider"></div>

						<p class="slider-value">
							<input type="text" style="width: 35px;" class="headway-visual-editor-input" value="<?php echo str_replace('px', '', headway_get_skin_option('leaf-border-radius')) ?>" name="headway-config[leaf-border-radius]" id="leaf-border-radius" />
							<span class="unit">px</span>
						</p>
					</td>
				</tr>
				<?php } ?>
		</table>
	</div>	
					
</div>
<?php
}


function headway_header_panel_content(){
	$header_image_delete_display = (headway_get_option('header-image') && headway_get_option('header-image') != 'DELETE') ? NULL : 'display: none;';
	$header_image = headway_get_option('header-image');
	
	$header_rearranging = (!headway_get_skin_option('header-order', true)) ? true : false;
?>
<div class="tabs">
	<ul class="clearfix tabs">
		<?php if(!headway_get_skin_option('disable-header-image')){ ?>
        	<li><a href="#header-image-tab"><?php _e('Header Image', 'headway'); ?></a></li>
		<?php } ?>
		
			<li><a href="#header-options-tab"><?php _e('Options', 'headway'); ?></a></li>
			
		<?php if($header_rearranging){ ?>
			<li><a href="#header-actions-tab"><?php _e('Actions', 'headway'); ?></a></li>
		<?php } ?>
    </ul>
		
	<?php if(!headway_get_skin_option('disable-header-image')){ ?>
	<div id="header-image-tab">
		<table class="tab-options full-width-table">
	
			<tr>
				<td>
					<p class="info-box info-box-with-bg" style="margin-bottom: 5px;font-size:12px;line-height:17px;padding-bottom:7px;">
						<?php _e('Recommended Image Size:', 'headway'); ?> <?php echo str_replace('px', '', headway_get_skin_option('wrapper-width')); ?>px by 150px
					</p>
				</td>
			</tr>

			<tr>
				<td class="clearfix">
					<div style="margin: 0 0 5px;" id="header-image"></div>	
					<input type="hidden" class="headway-visual-editor-input" name="headway-config[header-image]" id="header-image-hidden" value="<?php echo $header_image ?>" />
				</td>
			</tr>
			
			<tr>
				<td>
					<small style="margin:-3px 0 15px 7px;float:left;color:#999;">Maximum upload file size: <?php echo headway_convert_bytes_to_hr(headway_upload_max()); ?></small>
				</td>
			</tr>

			<?php
			headway_build_visual_editor_input(array(
				'type' => 'text',
				'id' => 'header-image-url',
				'text_left' => __('OR Link Directly To Header Image', 'headway')
			));
			?>

			<tr id="header-image-current-row" style="<?php echo $header_image_delete_display ?>">
				<td colspan="2">
					<span id="header-image-current"><?php echo headway_get_option('header-image') ?></span> 
					<a id="header-image-delete" href="#"><?php _e('Delete', 'headway'); ?></a>
				</td>
			</tr>

			<?php 
			$deactivate_resizing = (strpos($header_image, 'http') === false) ? false : true;

			headway_build_visual_editor_input(array(
				'hidden' => $deactivate_resizing,
				'type' => 'check',
				'id' => 'enable-header-resizing',
				'text_left' => __('Header Resizing', 'headway'),
				'text_right' => __('Enable Header Resizing', 'headway'),
				'value' => headway_get_skin_option('enable-header-resizing'),
				'border_top' => true,
				'tooltip' => __('If your header image is already the size you desire, disable header imager resizing.', 'headway')
			));

			headway_build_visual_editor_input(array(
				'type' => 'text',
				'id' => 'header-image-margin',
				'text_left' => __('Header Image Margin', 'headway'),
				'value' => headway_get_skin_option('header-image-margin'),
				'border_top' => $deactivate_resizing,
				'tooltip' => __('Set the margin or space around the header image.  If you are sure you\'re header image is the correct size, be sure to make sure this is set to 0px.', 'headway')
			));
			?>

		</table>
	</div>
	<?php } ?>	
			
		
	<div id="header-options-tab">
		<table class="tab-options full-width-table">
			
			<?php if(!headway_is_system_page()){ ?>
			<tr>
				<th scope="row"><label>Page Specific Settings</label></th>
				
				<td class="clearfix">
					<?php 
					headway_build_visual_editor_input(array(
						'name_array' => 'page-config',
						'type' => 'check-alt', 
						'id' => 'hide_header', 
						'text_right' => __('Hide Header On This Page', 'headway'), 
						'value' => headway_get_write_box_value('hide_header')
					));
					
					headway_build_visual_editor_input(array(
						'name_array' => 'page-config',
						'type' => 'check-alt', 
						'id' => 'hide_breadcrumbs', 
						'text_right' => __('Hide Breadcrumbs On This Page', 'headway'), 
						'value' => headway_get_write_box_value('hide_breadcrumbs')
					));
					?>
				</td>
			</tr>
			<?php } ?>
			
			<tr>					
				<th scope="row"><label class="ve-tooltip" title="<?php _e('Show or hide elements in the header.', 'headway'); ?>" style="cursor: help;"><?php _e('Header Elements', 'headway'); ?></label></th>					
				<td class="clearfix">
					<?php 
					headway_build_visual_editor_input(array(
						'type' => 'check-alt', 
						'id' => 'show-tagline', 
						'text_right' => __('Show Tagline', 'headway'), 
						'value' => headway_get_skin_option('show-tagline')
					)); 
					
				    headway_build_visual_editor_input(array(
						'type' => 'check-alt', 
						'id' => 'show-navigation', 
						'text_right' => __('Show Navigation', 'headway'), 
						'value' => headway_get_skin_option('show-navigation')
					)); 
					
					headway_build_visual_editor_input(array(
						'type' => 'check-alt', 
						'id' => 'show-header-search-bar', 
						'text_right' => __('Show Navigation Search Bar', 'headway'), 
						'value' => headway_get_skin_option('show-header-search-bar')
					)); 
					
					headway_build_visual_editor_input(array(
						'type' => 'check-alt', 
						'id' => 'show-header-rss-link', 
						'text_right' => __('Show Subscribe Link', 'headway'), 
						'value' => headway_get_skin_option('show-header-rss-link')
					)); 
					
					headway_build_visual_editor_input(array(
						'type' => 'check-alt', 
						'id' => 'show-breadcrumbs', 
						'text_right' => __('Show Breadcrumbs', 'headway'), 
						'value' => headway_get_skin_option('show-breadcrumbs')
					)); 
					?>
				</td>				
			</tr>


			<?php 
			headway_build_visual_editor_input(array(
				'type' => 'radio',
				'id' => 'header-style',
				'text_left' => __('Header Style', 'headway'),
				'text_right' => array(
									__('Fixed Header', 'headway') => array('id' => 'header-style-fixed', 'value' => 'fixed'), 
									__('Fluid Header', 'headway') => array('id' => 'header-style-fluid', 'value' => 'fluid')
								),
				'value' => headway_get_skin_option('header-style'),
				'tooltip' => __('Fluid: The header is outside the wrapper and spans the whole width of the page.  Fixed: Header stays inside wrapper.', 'headway')
			));
			?>
		</table>
	</div>


	<?php if($header_rearranging){ ?>
	<div id="header-actions-tab">
		<ul class="list-buttons actions-buttons">
			<li class="header-arrange alt"><span><?php _e('Header Rearranging', 'headway'); ?></span> <a href="" id="toggle-header-arrange" class="button ve-tooltip" title="<?php _e('Enable header rearranging to change the order of the header, navigation, and breadcrumbs with drag and drop.  Cool, eh?', 'headway'); ?>">Enable</a></li>
		</ul>
	</div>
	<?php } ?>

</div>					
<?php 
}


function headway_footer_panel_content(){
?>
		<table class="tab-options margin-top  full-width-table" id="footer-options" style="width: 200px;">
			
			<?php
			if(!headway_is_system_page()){
			?>
			<tr>
				<th scope="row"><label class="ve-tooltip" for="hide_footer">Page Specific Settings</label></th>
				
				<td class="clearfix">
					<?php 
					headway_build_visual_editor_input(array(
						'name_array' => 'page-config',
						'type' => 'check-alt', 
						'id' => 'hide_footer', 
						'text_right' => __('Hide Footer On This Page', 'headway'), 
						'value' => headway_get_write_box_value('hide_footer')
					));
					?>
				</td>
				
				
			</tr>
			<?php } ?>

			<?php 
			headway_build_visual_editor_input(array(
				'type' => 'radio',
				'id' => 'footer-style', 
				'text_left' => __('Footer Style', 'headway'), 
				'text_right' => array(
									__('Fixed Footer', 'headway') => array('id' => 'footer-style-fixed', 'value' => 'fixed'), 
									__('Fluid Footer', 'headway') => array('id' => 'footer-style-fluid', 'value' => 'fluid')
								), 
				'value' => headway_get_skin_option('footer-style'),
				'tooltip' => __('Much like the header, you can choose if you want to footer to be fluid or fixed.  Fluid is where the footer spans the width of the page and fixed is where the footer stays inside the page wrapper.', 'headway')
			)); 
			?>

	
				<tr class="no-border">					
					<th scope="row"><label class="ve-tooltip" title="<?php _e('Show or hide elements in the footer.', 'headway'); ?>" style="cursor: help;"><?php _e('Footer Elements', 'headway'); ?></label></th>					
					<td>
						<?php
						headway_build_visual_editor_input(array(
							'type' => 'check-alt', 
							'id' => 'show-admin-link', 
							'text_right' => __('Show Admin Link/Login', 'headway'), 
							'value' => headway_get_skin_option('show-admin-link')
						));
						
						headway_build_visual_editor_input(array(
							'type' => 'check-alt', 
							'id' => 'show-edit-link', 
							'text_right' => __('Show Edit Link', 'headway'), 
							'value' => headway_get_skin_option('show-edit-link')
						));
						
						headway_build_visual_editor_input(array(
							'type' => 'check-alt', 
							'id' => 'show-go-to-top-link', 
							'text_right' => __('Show Go To Top Link', 'headway'), 
							'value' => headway_get_skin_option('show-go-to-top-link')
						));
						
						headway_build_visual_editor_input(array(
							'type' => 'check-alt', 
							'id' => 'show-copyright', 
							'text_right' => __('Show Copyright', 'headway'), 
							'value' => headway_get_skin_option('show-copyright')
						));
						
						headway_build_visual_editor_input(array(
							'type' => 'check-alt', 
							'id' => 'hide-headway-attribution', 
							'text_right' => __('Hide Headway Attribution', 'headway'), 
							'value' => headway_get_skin_option('hide-headway-attribution')
						));
						?>
					</td>				
				</tr>
		
				<?php 
				headway_build_visual_editor_input(array(
					'type' => 'text', 
					'id' => 'custom-copyright', 
					'text_left' => __('Custom Copyright', 'headway'),
					'tooltip' => __('If you wish to have something different than the regular copyright, enter it here.  You MUST use HTML entities in this field.', 'headway')
				)); 
				?>
		
	
		</table>
<?php
}


function headway_navigation_panel_content(){
	$excluded_pages = headway_get_option('excluded_pages');	
		
	$inactive_pages = (is_array($excluded_pages) && $excluded_pages[0] != NULL) ? wp_list_pages(array('echo' => false, 'include' => implode(',', $excluded_pages), 'title_li' => false)) : NULL;

?>
	<div class="tabs">
			    <ul class="clearfix tabs">
					<?php if(!headway_nav_menu_check()){ ?>
			        <li><a href="#nav-actions-tab"><?php _e('Actions', 'headway'); ?></a></li>
			    	<li><a href="#nav-options-tab"><?php _e('Options', 'headway'); ?></a></li>
		            <li><a href="#nav-inactive-tab"><?php _e('Inactive Tabs', 'headway'); ?></a></li>
					<?php } else { ?>
					<li><a href="#nav-menus-tab"><?php _e('Menus', 'headway'); ?></a></li>						
				    <li><a href="#nav-options-tab"><?php _e('Options', 'headway'); ?></a></li>						
					<?php } ?>
			    </ul>
			
				<?php if(headway_nav_menu_check()){ ?>
				<div id="nav-menus-tab">
					
					<p class="info-box clearfix"><?php _e('Headway has detected you are using WordPress\' menu functionality.', 'headway'); ?><br /><br /><?php _e('You can select which menu you would like to show in the navigation bar.  You can also change which menu you would like to use for individual pages in the WordPress page editor.', 'headway'); ?></p>
					
					<p>
					<select name="headway-config[nav-menu]" class="headway-visual-editor-input">
						<?php
						$menus = wp_get_nav_menus();
						foreach ( $menus as $menu ) {							
							if ( wp_get_nav_menu_items($menu->term_id) ) {
								$nav_menu_selected = (headway_get_option('nav-menu') == $menu->slug) ? ' selected' : null;
								
								echo '<option value="'.$menu->slug.'"'.$nav_menu_selected.'>'.$menu->name.'</option>';
							}
						}
						?>
					</select>
					</p>
					
			    </div>
				<?php } else { ?>
				<div id="nav-actions-tab">
					<ul class="list-buttons navigation-options-buttons">
						<li class="navigation"><span><?php _e('Modify Navigation', 'headway'); ?></span> <a href="" id="toggle-navigation" class="button">Enable</a></li>						
					</ul>
			    </div>
				<?php } ?>
			
			    <div id="nav-options-tab">
					<div id="navigation-options">
						
						<table class="tab-options full-width-table">
						
							<?php
							if(!headway_is_system_page()){
							?>
							<tr>
								<th scope="row"><label class="ve-tooltip" for="hide_navigation">Page Specific Settings</label></th>

								<td class="clearfix">
									<?php 
									headway_build_visual_editor_input(array(
										'name_array' => 'page-config',
										'type' => 'check-alt', 
										'id' => 'hide_navigation', 
										'text_right' => __('Hide Navigation On This Page', 'headway'), 
										'value' => headway_get_write_box_value('hide_navigation')
									));
									?>
								</td>
							</tr>
							<?php } ?>
						
							<?php headway_build_visual_editor_input(array(
								'type' => 'radio',
								'id' => 'navigation-position',
								'text_left' => __('Navigation Position', 'headway'),
								'text_right' => array(
													__('Left', 'headway') => array('id' => 'navigation-position-left', 'value' => 'left'), 
													__('Right', 'headway') => array('id' => 'navigation-position-right', 'value' => 'right')
												),
								'value' => headway_get_skin_option('navigation-position')
							));
							?>
						
						</table>
					
						<div class="sub-box minimize" id="sub-navigation-sub-box">
							<span class="sub-box-heading"><?php _e('Sub-Navigation', 'headway'); ?></span>
						
							<div class="sub-box-content">
								<!-- <p class="radio-container">
																	<input type="hidden" value="DELETE" class="headway-visual-editor-input" name="headway-config<?php echo headway_disabled_input_name('show-navigation-subpages') ?>[show-navigation-subpages]"/>
																	<input type="checkbox"<?php echo headway_checkbox_value(headway_get_skin_option('show-navigation-subpages')) ?> class="radio headway-visual-editor-input" value="on" id="show-navigation-subpages" name="headway-config<?php echo headway_disabled_input_name('show-navigation-subpages') ?>[show-navigation-subpages]"<?php echo headway_disabled_input('show-navigation-subpages') ?> /><label for="show-navigation-subpages" class="ve-tooltip" title="<?php _e('Enable or disable subpages from being shown in the navigation bar.', 'headway'); ?>"><?php _e('Show Navigation Subpages', 'headway'); ?></label>						
																</p> -->

								<?php 
								headway_build_visual_editor_input(array(
									'type' => 'check-alt',
									'id' => 'show-navigation-subpages', 
									'text_right' => __('Show Navigation Subpages', 'headway'), 
									'value' => headway_get_skin_option('show-navigation-subpages')
								));
								?>

								<table class="tab-options full-width-table">

									<?php 
									headway_build_visual_editor_input(array(
										'type' => 'text',
										'id' => 'sub-nav-width',
										'text_left' => __('Sub-Navigation Width', 'headway'),
										'value' => str_replace('px', '', headway_get_skin_option('sub-nav-width')),
										'unit' => 'px',
										'tooltip' => __('Change how wide the navigation is when you hover over a parent.', 'headway')
									));
									?>

								</table>
							</div>
						</div>
					
						
						
						<div class="sub-box minimize" id="home-link-sub-box">
							<span class="sub-box-heading"><?php _e('Home Link', 'headway'); ?></span>
						
							<div class="sub-box-content">
								<?php 
								headway_build_visual_editor_input(array(
									'type' => 'check-alt', 
									'id' => 'hide-home-link', 
									'text_right' => __('Hide Navigation Home Link', 'headway'), 
									'tooltip' => __('Hide or show the home link from being shown.', 'headway')
								));
								?>

								<table class="tab-options full-width-table">
									<?php
									headway_build_visual_editor_input(array(
										'type' => 'text', 
										'id' => 'home-link-text', 
										'text_left' => __('Home Link Text', 'headway')
									));
									?>
								</table>
							</div>
						</div>
						
					</div>
					
			    </div>
			
				<?php if(!headway_nav_menu_check()){ ?>
			    <div id="nav-inactive-tab">
					<ul class="navigation" id="inactive-navigation">
						<?php echo $inactive_pages ?>
					</ul>
			    </div>
				<?php } ?>

			</div>
<?php
}