<?php
function sidebar_leaf_inner($leaf){	
	if(isset($leaf['new'])){
		$leaf['options']['sidebar-name'] = 'Sidebar '.$leaf['id'];
	}
?>	
	<ul class="clearfix tabs">
        <li><a href="#options-tab-<?php echo $leaf['id'] ?>">Options</a></li>
        <li><a href="#look-feel-tab-<?php echo $leaf['id'] ?>">Look &amp; Feel</a></li>
        <li><a href="#miscellaneous-tab-<?php echo $leaf['id'] ?>">Miscellaneous</a></li>
    </ul>

	<div id="options-tab-<?php echo $leaf['id'] ?>">
		<table class="tab-options">
			<tr>					
				<th scope="row"><label for="<?php echo $leaf['id'] ?>_duplicate_id">Sidebar Content</label></th>
				<td>
					<select name="leaf-options[<?php echo $leaf['id']; ?>][duplicate-id]" id="<?php echo $leaf['id'] ?>_duplicate_id" class="headway-visual-editor-input">
						<option value="">Create New Sidebar<option>
						<optgroup label="Available Sidebars">
							<?php
							$sidebars = headway_get_all_leafs('sidebar');
															
							foreach($sidebars as $sidebar){
								$id = $sidebar['id'];
								$options = maybe_unserialize($sidebar['options']);
								$config = maybe_unserialize($sidebar['config']);
								$name = $options['sidebar-name'] ? $options['sidebar-name'] : $config['title'];
								
								$selected = ($id == $leaf['options']['duplicate-id']) ? ' selected="selected"' : false;
								
								if($options['duplicate-id'] || $id == $leaf['id']) continue;
								
								echo '<option value="'.$id.'"'.$selected.'>'.$name.' &mdash; ID: '.$id.'</option>';
							}
							?>
						</optgroup>
					</select>
				</td>	
			</tr>
			
			<tr class="no-border">					
				<th scope="row"><label for="<?php echo $leaf['id'] ?>_sidebar_name">Sidebar Name (Shows in widgets panel)</label></th>
				<td><input type="text" class="headway-visual-editor-input" name="leaf-options[<?php echo $leaf['id'] ?>][sidebar-name]" id="<?php echo $leaf['id'] ?>_sidebar_name" value="<?php echo $leaf['options']['sidebar-name'] ?>" /></td>	
			</tr>
		</table>
	</div>

	<div id="look-feel-tab-<?php echo $leaf['id'] ?>">
		<table class="tab-options">
			<tr class="no-border">	
				<td colspan="2"><p class="radio-container"><input type="checkbox" class="radio headway-visual-editor-input" id="<?php echo $leaf['id'] ?>_horizontal_sidebar" name="leaf-options[<?php echo $leaf['id'] ?>][horizontal-sidebar]"<?php echo headway_checkbox_value($leaf['options']['horizontal-sidebar']) ?> /><label for="<?php echo $leaf['id'] ?>_horizontal_sidebar">Flip this sidebar horizontally.  Check this if you want a "widgetized" footer.</label></p></td>	
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

function sidebar_leaf_content($leaf){
	$sidebar_class = isset($leaf['options']['horizontal-sidebar']) ? 'sidebar horizontal-sidebar' : 'sidebar';
?>
	<ul class="<?php echo $sidebar_class; ?>">
	<?php
	if($leaf['options']['duplicate-id']) $leaf['id'] = $leaf['options']['duplicate-id'];
	
	if(!function_exists('dynamic_sidebar') || !dynamic_sidebar('sidebar-'.$leaf['id'])){
	?>
		<li class="widget">
			<span class="widget-title">No widgets!</span>
			<p>Add widgets to this sidebar in the <a href="<?php bloginfo('wpurl') ?>/wp-admin/widgets.php">Widgets panel</a> under Appearance in the WordPress Admin.</p>
		</li>
	<?php
	} 
	?>
	</ul>
<?php
}
$options = array(
		'id' => 'sidebar',
		'name' => 'Widget Ready Sidebar',
		'default_leaf' => true,
		'options_callback' => 'sidebar_leaf_inner',
		'content_callback' => 'sidebar_leaf_content'
	);

$sidebar_leaf = new HeadwayLeaf($options);