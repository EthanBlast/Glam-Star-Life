<?php
function about_leaf_content($leaf){
	if(urldecode($leaf['options']['image'])):
	
	if($leaf['options']['image-width'] && $leaf['options']['image-height']):
		if(strpos($leaf['options']['image'], home_url()) !== false){
			echo '<img src="'.home_url().'/?headway-trigger=thumbnail&amp;src='.urlencode($leaf['options']['image']).'&amp;w='.str_replace('px', '', $leaf['options']['image-width']).'&amp;h='.str_replace('px', '', $leaf['options']['image-height']).'&amp;zc=1" alt="" class="about-image align-'.$leaf['options']['image-align'].'" width="'.str_replace('px', '', $leaf['options']['image-width']).'px" height="'.str_replace('px', '', $leaf['options']['image-height']).'px" />';
		} else {
			echo '<img src="'.$leaf['options']['image'].'" alt="" class="about-image align-'.$leaf['options']['image-align'].'" width="'.str_replace('px', '', $leaf['options']['image-width']).'px" height="'.str_replace('px', '', $leaf['options']['image-height']).'px" />';
		} 
	else:
		echo '<img src="'.urldecode($leaf['options']['image']).'" alt="" class="about-image align-'.$leaf['options']['image-align'].'" />';
	endif;
	
	
	endif;	

	echo headway_parse_php(html_entity_decode(stripslashes(base64_decode($leaf['options']['blurb']))));

	if($leaf['options']['show-read-more']):
		if($leaf['options']['read-more-page']):
			$url = get_permalink($leaf['options']['read-more-page']);
		else:
			$url = urldecode($leaf['options']['read-more-location']);
		endif;
	
		echo '<a href="'.$url.'" class="about-read-more">'.html_entity_decode($leaf['options']['read-more-text']).'</a>';
	endif;
}

function about_leaf_inner($leaf){
	
	if(isset($leaf['new'])){
		$leaf['config']['show-title'] = 'on';
		
		$leaf['options']['image-width'] = '48';
		$leaf['options']['image-height'] = '48';
		
		$leaf['options']['read-more-text'] = 'Read More About Us &raquo;';
	}
	
	$pages_select = '<option value=""></option>';
	$page_select_query = get_pages();
	foreach($page_select_query as $page){ 
		if($page->ID == $leaf['options']['read-more-page']) $selected[$page->ID] = ' selected';
		$pages_select .= '<option value="'.$page->ID.'"'.$selected[$page->ID].'>'.$page->post_title.'</option>';
	}
?>
	<ul class="clearfix tabs">
        <li><a href="#blurb-tab-<?php echo $leaf['id'] ?>">Blurb</a></li>
        <li><a href="#image-tab-<?php echo $leaf['id'] ?>">Image/Portrait</a></li>
        <li><a href="#read-more-tab-<?php echo $leaf['id'] ?>">More Link</a></li>
        <li><a href="#miscellaneous-tab-<?php echo $leaf['id'] ?>">Miscellaneous</a></li>
    </ul>

	<div id="blurb-tab-<?php echo $leaf['id'] ?>">
		<table class="tab-options" id="leaf-options-<?php echo $leaf['id'] ?>-blurb">
			<tr class="no-border">
				<th scope="row" colspan="2"><label for="<?php echo $leaf['id'] ?>_blurb" style="text-align: left;">About Blurb</label></th>
			</tr>
			<tr class="textarea no-border">
				<td colspan="2"><textarea class="text-content headway-visual-editor-input" name="leaf-options[<?php echo $leaf['id'] ?>][blurb]" id="<?php echo $leaf['id'] ?>_blurb"><?php echo stripslashes(base64_decode($leaf['options']['blurb'])) ?></textarea></td>
			</tr>
		</table> 
	</div>
	
	
	
	<div id="image-tab-<?php echo $leaf['id'] ?>">
		<table class="tab-options" id="leaf-options-<?php echo $leaf['id'] ?>-image">
		
			<tr>					
				<th scope="row"><label for="<?php echo $leaf['id'] ?>_image">Image/Portrait URL</label></th>					
				<td><input type="text" class="headway-visual-editor-input" name="leaf-options[<?php echo $leaf['id'] ?>][image]" id="<?php echo $leaf['id'] ?>_image" value="<?php echo $leaf['options']['image'] ?>" /></td>				
			</tr>
			
			<tr>
				<th scope="row"><label for="<?php echo $leaf['id'] ?>_image_align">Image Align</label></th>
				<td>
					<select id="<?php echo $leaf['id'] ?>_image_align" class="headway-visual-editor-input" name="leaf-options[<?php echo $leaf['id'] ?>][image-align]">
						<option value="left"<?php headway_option_value($leaf['options']['image-align'], 'left') ?>>Left</option>
						<option value="right"<?php headway_option_value($leaf['options']['image-align'], 'right') ?>>Right</option>
					</select>
				</td>
			</tr>
			
			
			<tr>					
				<th scope="row"><label for="<?php echo $leaf['id'] ?>_image_width">Image Width</label></th>
				<td><input type="text" class="headway-visual-editor-input" name="leaf-options[<?php echo $leaf['id'] ?>][image-width]" id="<?php echo $leaf['id'] ?>_image_width" value="<?php echo $leaf['options']['image-width'] ?>" /><small><code>px</code></small></td>	
			</tr>
			
			<tr class="no-border">					
				<th scope="row"><label for="<?php echo $leaf['id'] ?>_image_height">Image Height</label></th>
				<td><input type="text" class="headway-visual-editor-input" name="leaf-options[<?php echo $leaf['id'] ?>][image-height]" id="<?php echo $leaf['id'] ?>_image_height" value="<?php echo $leaf['options']['image-height'] ?>" /><small><code>px</code></small></td>	
			</tr>
			
		
		
		</table>
	</div>
	
	
	
	<div id="read-more-tab-<?php echo $leaf['id'] ?>">
		<table class="tab-options" id="leaf-options-<?php echo $leaf['id'] ?>-read-more">
			<tr>
				<th scope="row"><label>Read More Link</label></th>
				<td>
					<p class="radio-container">
						<input type="checkbox" class="radio headway-visual-editor-input" value="on" id="<?php echo $leaf['id'] ?>_show_read_more" name="leaf-options[<?php echo $leaf['id'] ?>][show-read-more]"<?php echo headway_checkbox_value($leaf['options']['show-read-more']) ?> /><label for="<?php echo $leaf['id'] ?>_show_read_more">Show Read More Link</label>
					</p>
				</td>	
			</tr>
			
			
			<tr>					
				<th scope="row"><label for="<?php echo $leaf['id'] ?>_read_more_location">Read More Location</label></th>
				<td><input type="text" class="headway-visual-editor-input" name="leaf-options[<?php echo $leaf['id'] ?>][read-more-location]" id="<?php echo $leaf['id'] ?>_read_more_location" value="<?php echo $leaf['options']['read-more-location'] ?>" /></td>	
			</tr>
			
			<tr>
				<th scope="row"><label><strong>OR</strong> Link To Page</label></th>
				<td>
					<select class="headway-visual-editor-input" name="leaf-options[<?php echo $leaf['id'] ?>][read-more-page]" id="<?php echo $leaf['id'] ?>_read_more_page">
						<?php echo $pages_select ?>
					</select>
				</td>
			</tr>

			<tr class="no-border">
				<th scope="row"><label for="<?php echo $leaf['id'] ?>_read_more_text">Read More Text</label></th>
				<td><input type="text" value="<?php echo $leaf['options']['read-more-text'] ?>" class="headway-visual-editor-input" name="leaf-options[<?php echo $leaf['id'] ?>][read-more-text]" id="<?php echo $leaf['id'] ?>_read_more_text" /></td>
			</tr>
						
		</table>
	</div>
<?php
	HeadwayLeafsHelper::open_tab('miscellaneous', $leaf['id']);
		HeadwayLeafsHelper::create_show_title_checkbox($leaf['id'], $leaf['config']['show-title']);
		HeadwayLeafsHelper::create_title_link_input($leaf['id'], $leaf['config']['title-link']);
		HeadwayLeafsHelper::create_classes_input($leaf['id'], $leaf['config']['custom-classes'], true);
	HeadwayLeafsHelper::close_tab();
}

$options = array(
		'id' => 'about',
		'name' => 'About',
		'default_leaf' => true,
		'options_callback' => 'about_leaf_inner',
		'content_callback' => 'about_leaf_content',
		'icon' => get_bloginfo('template_directory').'/library/leafs/icons/about.png',
		'options_width' => 500
	);

$about_leaf = new HeadwayLeaf($options);