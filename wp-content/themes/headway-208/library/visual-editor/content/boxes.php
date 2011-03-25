<?php
function headway_mass_font_change_box(){
?>
<div id="mass-font-change-box" class="floaty-box floaty-box-close floaty-box-center">
	<h4 class="floaty-box-header"><?php _e('Mass Font Change', 'headway'); ?></h4>

	<div class="float-left clear-left select-container">
		<label><?php _e('Titles', 'headway'); ?></label>			
		<select style="display: none;" id="mass-font-select-titles" class="mass-font-select">
			<?php headway_font_options(); ?>
		</select>
		
		<?php headway_visual_font_options('georgia', true, 'mass-font-select-titles'); ?>
		
		<small><?php _e('Header title, post/page titles, widget headings, post meta', 'headway'); ?></small>
	</div>
	
	<div class="float-left clear-left select-container">
		<label><?php _e('Content', 'headway'); ?></label>
		<select style="display: none;" id="mass-font-select-content" class="mass-font-select">
			<?php headway_font_options(); ?>
		</select>
		
		<?php headway_visual_font_options('georgia', true, 'mass-font-select-content'); ?>
		
		<small><?php _e('Post/page content, hyperlinks, tagline', 'headway'); ?></small>
	</div>

</div>
<?php
}
add_action('headway_visual_editor_top', 'headway_mass_font_change_box');


function headway_add_dropdown_pages_select_class($data){
	return str_replace('<select name="', '<select class="headway-visual-editor-input" name="', $data);
}


function headway_linking_options_box(){
	add_filter('wp_dropdown_pages', 'headway_add_dropdown_pages_select_class');
	
	if(headway_is_system_page(false, true)){
		$current_page_link = headway_get_option('leaf-template-page-'.headway_current_page(true));
		$current_system_page_link[headway_get_option('leaf-template-system-page-'.headway_current_page(true))] = true;
	} else {
		$current_page_link = get_post_meta(headway_current_page(true), '_leaf_template', true);
		$current_system_page_link[get_post_meta(headway_current_page(true), '_leaf_system_template', true)] = true;
	}
?>	
<div id="linking-options-box" class="floaty-box floaty-box-close floaty-box-center">
  <h4 class="floaty-box-header"><?php _e('Linking Options', 'headway'); ?></h4>

  <p class="info-box clearfix"><?php _e('Select which page you would like to copy the leafs from.', 'headway'); ?></p>
  	
  <table class="tab-options">  	
  	
  	<tr>
  		<th scope="row"><label for="leafs-link-page"><?php _e('Pages', 'headway'); ?></label></th>
  		<td>
			<?php
			$exclude[] = headway_current_page(true);

			wp_dropdown_pages(array('exclude' => implode(',', $exclude), 'selected' => $current_page_link, 'name' => 'leafs-link-page', 'class' => 'headway-visual-editor-input', 'show_option_none' => __('&mdash; Do Not Link &mdash;', 'headway'), 'sort_column'=> 'menu_order, post_title', 'echo' => true));
			
			remove_filter('wp_dropdown_pages', 'headway_add_dropdown_pages_select_class');
			?>
  		</td>
  	</tr>
  	
  	<tr class="no-border">
  		<th scope="row"><label for="leafs-link-system-page"><?php _e('System Pages', 'headway'); ?></label></th>
  		<td>
  			<select name="leafs-link-system-page" id="leafs-link-system-page" class="headway-visual-editor-input">
  				<option value="DELETE">&mdash; Do Not Link &mdash;</option>
				<?php
				$system_pages = array(
					'index' => 'Blog Index', 
					'single' => 'Single Post', 
					'category' => 'Category Archive',
					'archives' => 'Archives', 
					'tag' => 'Tag Archive', 
					'author' => 'Author Archive',
					'search' => 'Search Results', 
					'four04' => '404 Page');
					
				foreach($system_pages as $id => $name){
					$current = isset($current_system_page_link[$id]) ? ' selected' : false;
					
					if($id == headway_current_page(true)) continue;
					 
					echo '<option value="'.$id.'"'.$current.'>'.$name.'</option>'."\n";
				}
				?>
  			</select>
  		</td>
  	</tr>
  	
  </table>
  
</div>
<?php
}
add_action('headway_visual_editor_top', 'headway_linking_options_box');


function headway_live_css_box(){
?>
<div id="live-css-box" class="floaty-box floaty-box-close floaty-box-center">
  <h4 class="floaty-box-header"><?php _e('Live CSS', 'headway'); ?></h4>

  <p class="info-box-with-bg"><?php _e('To use Live CSS, start typing your CSS below. Most changes will appear immediately. Be sure to click the Save button to keep your changes. Reload the Visual Editor to see any other changes. Live CSS settings will automatically transfer when you upgrade your version of Headway.', 'headway'); ?></p>

  <textarea id="live-css" name="headway-config[live-css]" class="headway-visual-editor-input"><?php echo stripslashes(htmlspecialchars(headway_get_option('live-css'))); ?></textarea>

  <style id="live-css-holder">
	<?php echo stripslashes(headway_get_option('live-css')); ?>
  </style>
  
</div>
<?php
}
add_action('headway_visual_editor_top', 'headway_live_css_box');


function headway_export_box(){
?>
<div id="export-window-box" class="floaty-box floaty-box-close floaty-box-center">
  <h4 class="floaty-box-header"><?php _e('Export', 'headway'); ?></h4>

	<h5><?php _e('Styles', 'headway'); ?> <a href="#export-styles" class="floaty-box-expandable-toggle">[+]</a></h5>

	<div class="floaty-box-expandable" id="export-styles">
		<p class="info-box clearfix"><?php _e('Choose the style you would like to export with the select box below and click Export Style!', 'headway'); ?></p>
		
		<select id="export-style-selector" style="margin-bottom: 10px;">
			<?php
			$styles = headway_get_option('styles');

			if(is_array($styles)){

				foreach($styles as $style => $options){					
					$style = str_replace($options['style-name'].'-', '', $style);

					echo '<option value="style-'.$style.'">'.$options['style-name'].'</option>';
				}

			}
			?>
		</select>

	  <a href="" id="export-style-button" class="button"><?php _e('Export Style', 'headway'); ?></a>
	</div>
	
	<?php if(!headway_is_page_linked()){ ?>
	<h5><?php _e('Leaf Templates', 'headway'); ?> <a href="#export-leaf-template" class="floaty-box-expandable-toggle">[+]</a></h5>

	<div class="floaty-box-expandable" id="export-leaf-template">
		<p class="info-box clearfix"><?php _e('Choose a template using the select box below and click <em>Export Leaf Template</em> to export the template you select.', 'headway'); ?></p>
		
		<select id="export-template-selector" style="margin-bottom: 10px;">
			<?php
			$templates = headway_get_option('leaf-templates');

			if(is_array($templates)){

				foreach($templates as $template => $options){					
					$template = str_replace($options['name'].'-', '', $template);

					echo '<option value="template-'.$template.'">'.$options['name'].'</option>';
				}

			}
			?>
		</select>

	  	<a href="" id="export-template-button" class="button"><?php _e('Export Leaf Template', 'headway'); ?></a>
	</div>
	<?php } ?>
	

<p class="info-box-with-bg clearfix"><?php _e('If you are looking to export site/header configurations, SEO settings, or Headway configuration settings, go to the Headway Configuration panel in the WordPress admin.', 'headway'); ?></p>


</div>
<?php
}
add_action('headway_visual_editor_top', 'headway_export_box');


function headway_import_box(){
?>	
<div id="import-window-box" class="floaty-box floaty-box-close floaty-box-center">
	<h4 class="floaty-box-header"><?php _e('Import', 'headway'); ?></h4>

	<h5><?php _e('Styles', 'headway'); ?> <a href="#import-styles" class="floaty-box-expandable-toggle">[+]</a></h5>

	<div class="floaty-box-expandable" id="import-styles">
		<p class="info-box clearfix"><?php _e('Browse to the style you wish to import on your computer. Once the style has been uploaded, it will appear in the style selector.  Find the style and click Load Style to view it.', 'headway'); ?></p>

		<div id="import-style"></div>
	</div>
	
	<h5><?php _e('Leaf Templates', 'headway'); ?> <a href="#import-leaf-templates" class="floaty-box-expandable-toggle">[+]</a></h5>

	<div class="floaty-box-expandable" id="import-leaf-templates">
		<p class="info-box clearfix"><?php _e('Browse to the leaf template you wish to import on your computer.  The template will automatically show up in the leaf templates dropdown under Leafs &amp; Columns &raquo; Templates.', 'headway'); ?></p>

		<div id="import-leaf-template"></div>
	</div>
	
	<div class="border-top clearfix">
		<p class="info-box-with-bg clearfix" style="margin-bottom: -10px;"><?php _e('If you are looking to import site/header configurations, SEO settings, or Headway configuration settings, go to the Headway Configuration panel in the WordPress admin.', 'headway'); ?></p>
	</div>

  
</div>
<?php
}
add_action('headway_pre_visual_editor', 'headway_import_box');


function headway_save_style_box(){
?>	
<div id="save-style-box" class="floaty-box floaty-box-close floaty-box-center" style="padding: 10px 10px 20px;">
  <h4 class="floaty-box-header"><?php _e('Save Style', 'headway'); ?></h4>

	<p class="info-box clearfix"><?php _e('Enter the name you would like to name the style you are saving, then click Save Style.', 'headway'); ?></p>
	
	<input type="text" id="style-name" value="<?php _e('Style Name', 'headway'); ?>" onfocus="if(this.value=='<?php _e('Style Name', 'headway'); ?>'){this.value=''}" onblur="if(this.value==''){this.value='<?php _e('Style Name', 'headway'); ?>'}" />
  
	<a id="save-style-submit" href="" class="button box-button"><?php _e('Save Style', 'headway'); ?></a>  

</div>
<?php
}
add_action('headway_pre_visual_editor', 'headway_save_style_box');


function headway_save_template_box(){
?>	
<div id="save-template-box" class="floaty-box floaty-box-close floaty-box-center" style="padding: 10px 10px 20px;">
  <h4 class="floaty-box-header"><?php _e('Save Template', 'headway'); ?></h4>

	<p class="info-box clearfix"><?php _e('Enter the name you would like to name the template you are saving, then click Save Template.', 'headway'); ?></p>
	
	<p class="notice" style="margin: 0 0 15px 0;"><?php _e('<strong>Note:</strong> If you have made changes to the layout and haven\'t saved the changes in the visual editor, you must do so before saving the leafs as a template.', 'headway'); ?></p>
	
	<input type="text" id="template-name" value="<?php _e('Template Name', 'headway'); ?>" onfocus="if(this.value=='<?php _e('Template Name', 'headway'); ?>'){this.value=''}" onblur="if(this.value==''){this.value='<?php _e('Template Name', 'headway'); ?>'}" />
  
	<a id="save-template-submit" href="" class="button box-button"><?php _e('Save Template', 'headway'); ?></a>  

</div>
<?php
}
add_action('headway_pre_visual_editor', 'headway_save_template_box');


function headway_edit_style(){
?>
<div id="edit-style-box" class="floaty-box floaty-box-close floaty-box-center">
  <h4 class="floaty-box-header"><?php _e('Edit Style', 'headway'); ?></h4>
  
	<table class="tab-options">

	  	<tr class="no-border">
	  		<th scope="row"><label for="rename-style"><?php _e('Style Name', 'headway'); ?></label></th>
	  		<td>
	  			<input type="text" id="rename-style" value="<?php _e('Style Name', 'headway'); ?>" />
	  		</td>
	  	</tr>

	</table>
	
  <p style="margin-bottom: 0;">
	<input type="hidden" id="style-settings-style-id" value="" />
	<input type="hidden" id="style-settings-style-name" value="" />

	<a href="" id="save-style-settings-button" class="button"><?php _e('Save Style Settings', 'headway'); ?></a>
  	<a href="" id="delete-style-button" class="button"><?php _e('Delete Style', 'headway'); ?></a>
  </p>

</div>
<?php
}
add_action('headway_visual_editor_top', 'headway_edit_style');


function headway_edit_template(){
?>
<div id="edit-template-box" class="floaty-box floaty-box-close floaty-box-center">
  <h4 class="floaty-box-header"><?php _e('Edit Template', 'headway'); ?></h4>
  
	<table class="tab-options">

	  	<tr class="no-border">
	  		<th scope="row"><label for="rename-template"><?php _e('Template Name', 'headway'); ?></label></th>
	  		<td>
	  			<input type="text" id="rename-template" value="<?php _e('Template Name', 'headway'); ?>" />
	  		</td>
	  	</tr>

	</table>

  	<p style="margin-left: 5px;" id="delete-template-container"><a href="" id="delete-template-link"><?php _e('Delete Template', 'headway'); ?></a></p>

	<p style="margin-bottom: 0;">
		<input type="hidden" id="template-settings-template-id" value="" />
		<input type="hidden" id="template-settings-template-name" value="" />


		<a href="" id="save-template-settings-button" class="button"><?php _e('Save Template Settings', 'headway'); ?></a>
		<a href="" id="set-template-as-default" class="button"><?php _e('Set Template As Default', 'headway'); ?></a>
		<a href="" id="remove-template-as-default" class="button"><?php _e('Remove Template As Default', 'headway'); ?></a>	  	
	</p>

</div>
<?php
}
add_action('headway_visual_editor_top', 'headway_edit_template');


function headway_switch_layout_overlay(){
?>
<div id="visual-editor-menu-right">
	<form id="layout-chooser" method="POST">
		<?php 
		global $post;
		
		$exclude = array();
		
		//Query all pages to check for children
		$all_pages_query = new WP_Query();
		$all_pages = $all_pages_query->query(array('post_type' => 'page'));
		
		if(!headway_is_system_page()){
			//Remove current page (only if it doesn't have children)
			if(count(get_page_children(headway_current_page(true), $all_pages)) == 0) $exclude[] = headway_current_page(true);
		}
		
		//Remove front page from pages list
		if(get_option('page_for_posts') && count(get_page_children(get_option('page_for_posts'), $all_pages)) == 0) $exclude[] = get_option('page_for_posts');
		
		wp_dropdown_pages(array('exclude' => implode(',', $exclude), 'name' => 'layout-page', 'show_option_none' => '-'.__('Pages', 'headway').'-', 'sort_column'=> 'menu_order, post_title', 'echo' => true)); 
		?>
		<select id="show-system-page" name="layout-system-page">
			<option value="">-<?php _e('System Pages', 'headway'); ?>-</option>
			<?php
			$tags = get_tags(array('number' => 1));
			
			$system_pages = array();
			
			$system_pages['index'] = __('Blog Index');
			$system_pages['single'] = __('Single Post');
			$system_pages['category'] = __('Category Archives');
			$system_pages['archives'] = __('Archives');
			
			if(count($tags) > 0)
				$system_pages['tag'] = __('Tag Archives');
				
			$system_pages['author'] = __('Author Archives');
			$system_pages['search'] = __('Search Results');
			$system_pages['four04'] = __('404 Page');

			$ignore = array('post', 'page', 'mediapage', 'attachment', 'revision', 'nav_menu_item');

			foreach(get_post_types(array('publicly_queryable' => true), 'objects') as $post_type){
				if(in_array($post_type->name, $ignore)) continue;
				
				$system_pages['custom-single-'.$post_type->name] = __('Post Type &ndash; '.$post_type->labels->singular_name.' &ndash; Single');
			}
			
			foreach($system_pages as $id => $name){
				if($id == headway_current_page(true)) continue;
				
				echo '<option value="'.$id.'">'.$name.'</option>';
			}
			?>
		</select>

		<input type="submit" class="button-wordpress button-right" name="switch-layout" value="<?php _e('Switch To Layout', 'headway'); ?>"/>
	</form>
</div>
<?php
}
add_action('headway_pre_visual_editor', 'headway_switch_layout_overlay', 1);


function headway_save_and_link_box(){
	if(function_exists('get_pages')){
		$link_pages = '';
		$link_pages_query = get_pages();
		
		foreach($link_pages_query as $link_page){ 
			$page = (isset($_POST['layout-page']) && $_POST['layout-page'] == true) ? $_POST['layout-page'] : false;
			
			$link_pages .= ($link_page->ID != $page) ? '<option value="'.$link_page->ID.'">'.$link_page->post_title.'</option>' : '';
		}
	}
?>
<div id="save-and-link-box" class="floaty-box floaty-box-close floaty-box-center" style="z-index: 15002;">
	<h4 class="floaty-box-header"><?php _e('Save &amp; Link', 'headway'); ?></h4>

	<div style="width: 160px;float:left;margin: 0 10px 15px 0;padding-right:10px;border-right: 1px solid #ccc;">
		<strong><?php _e('Link to Pages:', 'headway'); ?></strong>
		
		<select name="link-pages[pages][]" id="link-pages" size="10" style="width: 160px;" class="headway-visual-editor-input" multiple>';
			<?php echo $link_pages; ?>
		</select>
	</div>

	<div style="width: 160px;float:left;margin: 0 0 15px;">
		<strong><?php _e('Link to System Pages:', 'headway'); ?></strong>

		<select name="link-pages[system-pages][]" id="link-system-pages" size="10" class="headway-visual-editor-input" style="width: 160px;" multiple>
			<option value="index">Blog Index</option>
			<option value="single">Single Post</option>
			<option value="category">Category Archive</option>
			<option value="archives">Archives</option>
			<option value="tag">Tag Archive</option>
			<option value="author">Author Archive</option>
			<option value="search">Search</option>
			<option value="four04">404 Page</option>
		</select>
	</div>

	<p><input type="submit" value="<?php _e('Save and Link', 'headway'); ?>" class="button" name="headway-ve-save-and-link" id="save-and-link-button" /></p>

</div>
<?php
}
add_action('headway_visual_editor_top', 'headway_save_and_link_box');


function headway_help_box(){
?>
<div id="help-box" class="floaty-box floaty-box-close" style="display: none;">
	<h4 class="floaty-box-header">Help!</h4>

	<div class="floaty-box-bar" id="help-box-bar">
		<div id="help-box-bar-left">
			<p>Loading...</p>
		</div>
	</div>

	<div class="floaty-box-content" id="help-box-content">
		<p>Welcome to the Headway help panel!  Select a topic above to get started.</p>
	</div>
</div>
<?php
}
add_action('headway_visual_editor_end', 'headway_help_box');


function headway_skin_preview_box(){
?>
<div id="skin-preview-box" class="floaty-box floaty-box-close">
	<h4 class="floaty-box-header"><?php _e('Skin Preview', 'headway'); ?></h4>

	<iframe id="skin-preview" width="100%" height="100%" src=""></iframe>
</div>
<?php
}
add_action('headway_visual_editor_end', 'headway_skin_preview_box');


function headway_ie_box(){
?>
<div id="ie-box" class="floaty-box">
	<h4 class="floaty-box-header">Warning!</h4>
	
	<div class="floaty-box-content">
		<p>The Headway Visual Editor is not optimized for Internet Explorer.  Please upgrade to a better browser such as <a href="http://www.google.com/chrome" target="_blank">Google Chrome</a> or <a href="http://getfirefox.com" target="_blank">Mozilla Firefox</a>.</p>
		
		<p style="margin: 15px 0;">
			<a href="http://www.google.com/chrome" target="_blank"><img src="<?php bloginfo('template_directory'); ?>/library/visual-editor/images/browsers/chrome.png" alt="Google Chrome" style="margin-left: 20px;" /></a>
			<a href="http://getfirefox.com" target="_blank"><img src="<?php bloginfo('template_directory'); ?>/library/visual-editor/images/browsers/firefox.png" alt="Mozilla Firefox" style="margin-left: 20px;" /></a>
		</p>
		
		<p>Feeling risky?  <a href="<?php echo home_url(); ?>/?visual-editor=true" onclick="jQuery.cookie('headway-visual-editor-ie', 1); window.location='<?php echo home_url() ?>/?visual-editor=true'; return false;" style="font-size:11px;margin-left:5px;" class="button">Continue on your on parole.</a></p>
	</div>
</div>
<?php
}


function headway_navigation_item_options($id, $name = 'Navigation Item'){
	$nice_id = str_replace('page-item-', '', $id);
	
	$categories = get_post_meta($nice_id, '_headway_category_forward', true);
	$categories_select_query = get_categories();
	foreach($categories_select_query as $category){ 
		if($category->term_id == $categories) $select_selected[$category->term_id] = ' selected';

		$categories_select .= '<option value="'.$category->term_id.'"'.$select_selected[$category->term_id].'>'.$category->name.'</option>';
	}
?>
<h4 class="floaty-box-header"><span><?php echo $name ?></span></h4>
	<table class="navigation-options" id="navigation-item-options-<?php echo $id ?>">
		
		<tr>					
			<th scope="row"><label for="nav-item_<?php echo $id ?>_name">Navigation Item Text</label></th>
			<td><input type="text" class="headway-visual-editor-input" name="nav-item[<?php echo $id ?>][name]" id="nav-item_<?php echo $id ?>_name" value="<?php echo $name ?>" /></td>	
		</tr>


		<tr>
			<th scope="row"><label for="nav-item_<?php echo $id ?>_category">Link To Category</label></th>
			<td>
				<select name="nav-item[<?php echo $id ?>][category]" id="nav-item_<?php echo $id ?>_category" class="headway-visual-editor-input">
					<option value=""></option>
					<?php echo $categories_select ?>
				</select>
			</td>
		</tr>
		
		<tr class="no-border">					
			<th scope="row"><label for="nav-item_<?php echo $id ?>_forward_url"><strong>Or</strong> Redirect URL</label></th>
			<td><input type="text" class="headway-visual-editor-input" name="nav-item[<?php echo $id ?>][forward-url]" id="nav-item_<?php echo $id ?>_forward_url" value="<?php echo get_post_meta($nice_id, '_navigation_url', true) ?>" /></td>	
		</tr>
	
	</table> 
<?php 
}


function headway_loading_overlay(){
?>
<div id="overlay" class="overlay" style="opacity:1;z-index: 15002;"></div><div id="visual-editor-loader" style="z-index:15003;">
	<p class="loading loading-image-big"><img src="<?php echo get_bloginfo('template_directory'); ?>/library/visual-editor/images/loading-big.gif" class="loading-image loading-image-big" /></p>
				
	<p id="visual-editor-loader-text"><?php _e('If the visual editor takes longer than 20 seconds to load, please contact Headway Support at <a href="mailto:support@headwaythemes.com">support@headwaythemes.com</a> or visit the Headway forums.<br /><br />We make every effort to respond to all support requests within 24 hours, Monday-Friday US Central Time.', 'headway'); ?></p>
</div>
<?php
}
add_action('headway_visual_editor_top', 'headway_loading_overlay');


function headway_ve_working_overlay(){
?>
<div id="ve-working-overlay" class="overlay" style="opacity:0;z-index: 12000;display: none;"></div>
<div id="visual-editor-working" class="loader" style="display: none;">
	<p class="loading loading-image-big"><img src="<?php echo get_bloginfo('template_directory'); ?>/library/visual-editor/images/loading-big.gif" class="loading-image loading-image-big" /></p>
</div>
<?php
}
add_action('headway_visual_editor_before_end', 'headway_ve_working_overlay');


function headway_wizard_box(){

if(!headway_get_option('ran-wizard')){
$wizard_class = ' no-drag';
?>
<div id="wizard-overlay" class="overlay" style="opacity:1;z-index: 15000;"></div>
<?php 
} else {
$wizard_display = ' style="display: none;"';
$wizard_class = ' ran-wizard';	
}
?>

<div id="wizard-box" class="floaty-box floaty-box-close<?php echo $wizard_class; ?>"<?php echo $wizard_display; ?>>
	<h4 class="floaty-box-header"><?php _e('Quick Start Wizard', 'headway'); ?></h4>

	<div class="floaty-box-content">
		<div id="wizard-panel-1" class="wizard-panel">
			<select name="wizard[layout]" id="wizard-layout-select" style="display: none;" class="headway-visual-editor-input">
				<option value="content-sidebar" selected="selected">Content | Sidebar</option>
				<option value="sidebar-content">Sidebar | Content</option>
				<option value="content-sidebar-sidebar">Content | Sidebar | Sidebar</option>
				<option value="sidebar-content-sidebar">Sidebar | Content | Sidebar</option>
				<option value="content">Content</option>
			</select>
			
			<h5><?php _e('Select a Layout', 'headway'); ?></h5>
			<p><?php _e('Using the icons below, choose the layout you would like your site to resemble.  This will set <strong>every page</strong> on your website to the layout you select below.', 'headway'); ?></p>
			
			<?php 
			$warning = (!headway_get_option('ran-wizard')) ? ' display: none;' : false;
			?>
			<p class="notice warning wizard-warning" style="margin: 17px 5px 0;<?php echo $warning; ?>"><?php _e('<strong>Warning!</strong> This will replace EVERY page layout on your site.  If you wish to preserve one or some of your leaf templates, save them using the Leaf Templates saving functionality.  Otherwise, click the checkbox below to not change any layouts.', 'headway'); ?></p>
			
			<p class="radio-container wizard-warning" style="margin: 20px 0pt -45px; width: 100%;<?php echo $warning; ?>">
				<input type="checkbox" class="radio headway-visual-editor-input" value="on" id="wizard-skip-layout" name="wizard[skip-layout]" /><label for="wizard-skip-layout"><?php _e('Skip this step (do not change any Page Layouts)', 'headway'); ?></label>
			</p>						
			
			<div id="wizard-layout-controls">
				<span class="wizard-layout-selector wizard-layout-selector-first wizard-layout-selected ve-tooltip" title="Content | Sidebar"><img src="<?php bloginfo('template_directory'); ?>/library/visual-editor/images/wizard/content-sidebar.png" alt="content-sidebar" /></span>
				<span class="wizard-layout-selector ve-tooltip" title="Sidebar | Content"><img src="<?php bloginfo('template_directory'); ?>/library/visual-editor/images/wizard/sidebar-content.png" alt="sidebar-content" /></span>
				<span class="wizard-layout-selector ve-tooltip" title="Content | Sidebar | Sidebar"><img src="<?php bloginfo('template_directory'); ?>/library/visual-editor/images/wizard/content-sidebar-sidebar.png" alt="content-sidebar-sidebar" /></span>
				<span class="wizard-layout-selector ve-tooltip" title="Sidebar | Content | Sidebar"><img src="<?php bloginfo('template_directory'); ?>/library/visual-editor/images/wizard/sidebar-content-sidebar.png" alt="sidebar-content-sidebar" /></span>
				<span class="wizard-layout-selector ve-tooltip" title="Content"><img src="<?php bloginfo('template_directory'); ?>/library/visual-editor/images/wizard/content.png" alt="content" /></span>
			
				<p class="radio-container" id="columns-system-checkbox-container">
					<input type="checkbox" class="radio headway-visual-editor-input" value="on" id="wizard-use-columns-system" name="wizard[use-columns-system]" checked /><label for="wizard-use-columns-system"><?php _e('Use Columns System (Recommended)', 'headway'); ?></label>						
				</p>
			</div>
			
			<?php if(!headway_get_option('ran-wizard')){ ?>
			<a href="#" class="skip-wizard"><?php _e('Skip Wizard', 'headway'); ?></a>
			<?php } ?>
			
			<a href="#2" class="button wizard-go">Next Step &raquo;</a>
		</div>
		
		<div id="wizard-panel-2" class="wizard-panel wizard-panel-hidden">
			<h5><?php _e('Upload Header Image', 'headway'); ?></h5>
				
			<p><?php _e('If you have a banner, logo, or any other image that you would like to use, upload it using the button below.  Otherwise, go to the next step.', 'headway'); ?></p>
		
			<p><?php _e('After you upload your image, we will also analyze it for the next step in this wizard to build a matching color scheme.', 'headway'); ?></p>
			
			<div style="width: 100%; margin: 30px 30px 0;">
				<p><small class="grey"><?php _e('Recommended Size:', 'headway'); ?> <?php echo str_replace('px', '', headway_get_skin_option('wrapper-width')); ?>px by 150px</small></p>
				
				<div id="wizard-header-image-upload"></div>
			</div>
			
			<div class="success" id="wizard-uploaded-header-image"><span><?php _e('Image Uploaded!', 'headway'); ?></span> <p><?php _e('Go to the next step to continue customization.', 'headway'); ?></p></div>
			
			<?php if(!headway_get_option('ran-wizard')){ ?>
			<a href="#" class="skip-wizard"><?php _e('Skip Wizard', 'headway'); ?></a>
			<?php } ?>
			
			<a href="#1" class="button wizard-go wizard-previous">&laquo; <?php _e('Previous Step', 'headway'); ?></a>			
			<a href="#3" class="button wizard-go"><?php _e('Next Step', 'headway'); ?> &raquo;</a>
		</div>
		
		<div id="wizard-panel-3" class="wizard-panel wizard-panel-hidden">
			<h5><?php _e('Color Scheme', 'headway'); ?></h5>
			
			<?php
			//Preset colors.  Colors from header image will be added via JavaScript.			
			$colors[] = '5a1f00';
			$colors[] = '95ab63';
			$colors[] = 'bdd684';
			
			$colors[] = '8e001c';
			$colors[] = '424242';
			$colors[] = 'd3ceaa';
			
			$colors[] = 'eb7f00';
			$colors[] = '1695a3';
			$colors[] = '225378';
			
			$colors[] = 'd4ff00';
			$colors[] = '222222';
			$colors[] = 'ffffff';
			?>
			<p><?php _e('To set up the color scheme of your website, drag the following colors onto the spots below <span style="font-weight: bold;color: #aa0000;">(primary, secondary, and tertiary are required!)</span>.  Or, manually select the color by clicking on the color box.  <span id="colors-from-header-image" style="display: none; font-weight: bold;">The colors below were automatically generated from the header image you uploaded in the previous step.</span><span id="colors-from-headway">The colors below are a few colors you can use to build a color scheme quickly.', 'headway'); ?></span>
			</p>

			<div id="style-generator-available-colors">
				<strong><?php _e('Colors', 'headway'); ?></strong>

				<div class="colors">
					<?php
					if(is_array($colors)){
						$i = 0;

						foreach($colors as $color){
							$i++;
							
							echo '<div class="color-preview color-preview-black-border color-draggable wizard-available-color" style="background-color:#'.$color.';">'.$color.'</div>';
						}
					}
					?>
				</div>
			</div>

			<div id="style-generator-palette">
				<strong><?php _e('Selected Colors', 'headway'); ?></strong>

				<ul>
					<li>
						<span><?php _e('Primary Color', 'headway'); ?></span>
						<small><?php _e('Header title, post/page titles, hyperlinks', 'headway'); ?></small>
						<div class="color-preview color-preview-black-border color-selector ve-tooltip" title="Click To Edit Color" id="primary-color-box" style="background-color:#fff;">ffffff</div>
					</li>

					<li>
						<span><?php _e('Secondary Color', 'headway'); ?></span>
						<small><?php _e('Navigation, heading 1 and 2\'s in content', 'headway'); ?></small>
						<div class="color-preview color-preview-black-border color-selector ve-tooltip" title="Click To Edit Color" id="secondary-color-box" style="background-color:#fff;">ffffff</div>
					</li>

					<li>
						<span><?php _e('Tertiary (Third) Color', 'headway'); ?></span>
						<small><?php _e('Tagline, leaf titles, widget titles', 'headway'); ?></small>
						<div class="color-preview color-preview-black-border color-selector ve-tooltip" title="Click To Edit Color" id="tertiary-color-box" style="background-color:#fff;">ffffff</div>
					</li>

					<li>
						<span><?php _e('Background Color', 'headway'); ?></span>
						<div class="color-preview color-preview-black-border color-selector ve-tooltip" title="Click To Edit Color" id="background-color-box" style="background-color:#fff;">ffffff</div>
					</li>
				</ul>
			</div>
			
			
			<?php if(!headway_get_option('ran-wizard')){ ?>
			<a href="#" class="skip-wizard"><?php _e('Skip Wizard', 'headway'); ?></a>
			<?php } ?>
			
			<a href="#2" class="button wizard-go wizard-previous">&laquo; <?php _e('Previous Step', 'headway'); ?></a>			
			<a href="#4" class="button wizard-go"><?php _e('Next Step', 'headway'); ?> &raquo;</a>
		</div>
		
		<div id="wizard-panel-4" class="wizard-panel wizard-panel-hidden">
			<h5><?php _e('Fonts', 'headway'); ?></h5>
			
			<p><?php _e('Choose which fonts you would like for the following.', 'headway'); ?></p>
			
			<div class="float-left clear-left select-container">
				<label><?php _e('Titles', 'headway'); ?></label>			
				
				<select style="display: none;" id="wizard-fonts-titles" class="font-family">
					<?php headway_font_options(); ?>
				</select>
				
				<?php headway_visual_font_options('georgia', true, 'wizard-fonts-titles'); ?>
				
				<small><?php _e('Header title, post/page titles, widget headings, post meta', 'headway'); ?></small>
			</div>
			
			<div class="float-left clear-left select-container">
				<label><?php _e('Content', 'headway'); ?></label>
				
				<select style="display: none;" id="wizard-fonts-content" class="font-family">
					<?php headway_font_options(); ?>
				</select>
				
				<?php headway_visual_font_options('georgia', true, 'wizard-fonts-content'); ?>

				<small><?php _e('Post/page content, hyperlinks, tagline', 'headway'); ?></small>
			</div>
			
			<a href="#3" class="button wizard-go wizard-previous">&laquo; <?php _e('Previous Step', 'headway'); ?></a>			
			<a href="#wizard-finish" class="button wizard-finish"><?php _e('Build Site &amp; Finish', 'headway'); ?> &raquo;</a>
		</div>
	</div>
</div>
<?php
}
add_action('headway_visual_editor_top', 'headway_wizard_box');