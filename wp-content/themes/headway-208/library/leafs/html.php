<?php
function text_content($leaf){
	$content = stripslashes(base64_decode($leaf['options']['text-content']));
	
	//Remove bad MailChimp code.
	$content = str_replace('<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.2.6/jquery.min.js"></script>', '', $content);
	
	if(isset($leaf['options']['dynamic-content']) && headway_get_write_box_value('dynamic-content')){
		echo headway_parse_php(do_shortcode(stripslashes(headway_get_write_box_value('dynamic-content'))));
	} else {
		echo headway_parse_php(do_shortcode($content));
	}
}

function text_inner($leaf){
	if(isset($leaf['new'])){
		$leaf['config']['show-title'] = 'on';
	}
	
?>
<ul class="clearfix tabs">
       <li><a href="#content-tab-<?php echo $leaf['id'] ?>">Content</a></li>
       <li><a href="#options-tab-<?php echo $leaf['id'] ?>">Options</a></li>
       <li><a href="#miscellaneous-tab-<?php echo $leaf['id'] ?>">Miscellaneous</a></li>
</ul>

<div id="content-tab-<?php echo $leaf['id'] ?>">
	<table class="tab-options">
		<tr class="no-border">
			<th scope="row" colspan="2"><label for="<?php echo $leaf['id'] ?>_text_content" style="text-align: left;">Text/HTML/PHP</label></th>
		</tr>
		
		<tr class="textarea no-border">
			<td colspan="2"><textarea class="text-content headway-visual-editor-input" name="leaf-options[<?php echo $leaf['id'] ?>][text-content]" id="<?php echo $leaf['id'] ?>_text_content"><?php echo stripslashes(htmlspecialchars(base64_decode($leaf['options']['text-content']))) ?></textarea></td>
		</tr>
		
		<p class="info-box-with-bg"><strong>Tip:</strong> You can resize this box for your convenience.</p>
	</table>
</div>

<div id="options-tab-<?php echo $leaf['id'] ?>">
	<table class="tab-options">
		<tr>
			<td colspan="2"><p class="info-box">By enabling dynamic content you can add a text leaf to a single post and change what is in the box for each post.  This is useful if you want to put a YouTube video in the sidebar for a certain post.  The options are limitless.</p></td>
		</tr>
	
		<tr class="no-border">	
			<th scope="row"><label for="<?php echo $leaf['id'] ?>[dynamic-content]">Dynamic Content</label></th>
			<td>
				<p class="radio-container">
					<input type="checkbox" class="radio headway-visual-editor-input" id="<?php echo $leaf['id'] ?>_dynamic_content" name="leaf-options[<?php echo $leaf['id'] ?>][dynamic-content]"<?php echo headway_checkbox_value($leaf['options']['dynamic-content']) ?>/>
					<label for="<?php echo $leaf['id'] ?>_dynamic_content">Enable Dynamic Content</label>
				</p>
			</td>	
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
		'id' => 'html',
		'name' => 'HTML/PHP',
		'default_leaf' => true,
		'options_callback' => 'text_inner',
		'content_callback' => 'text_content',
		'icon' => get_bloginfo('template_directory').'/library/leafs/icons/default.png',
		'options_width' => 500
	);

$text_leaf = new HeadwayLeaf($options);