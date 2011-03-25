<?php
/*
Plugin Name: Quick Post Widget
Plugin URI: http://www.famvanakkeren.nl/downloads/quick-post-widget/
Description: This plugin provides a widget to post directly from the frontpanel of your site without going into the backend.
Author: Perry van Akkeren
Version: 1.7.1
Author URI: http://www.famvanakkeren.nl/
*/

/*
Copyright 2010  Perry van Akkeren  (email : vanakkeren@live.nl)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if(!isset($_SESSION)) { @session_start(); }
$_SESSION['quick-post-widget']=true;
$plugin_url = WP_PLUGIN_URL . '/' . str_replace(basename(__FILE__), "", plugin_basename(__FILE__));
$qpw_locale = ( '' == get_locale() ) ? 'en' : strtolower( substr(get_locale(), 0, 2) );
if (!in_array($qpw_locale, array('de','en','es','fr','nl','pt','it','pl'))) $qpw_locale='en';
$_SESSION['qpw_locale'] = $qpw_locale;

function upload_dir() {
	$siteurl = get_option( 'siteurl' );
	$upload_path = get_option( 'upload_path' );
	$upload_path = trim($upload_path);
	if ( empty($upload_path) ) {
		$dir = WP_CONTENT_DIR . '/uploads';
	} else {
		$dir = $upload_path;
		if ( 'wp-content/uploads' == $upload_path ) {
			$dir = WP_CONTENT_DIR . '/uploads';
		} elseif ( 0 !== strpos($dir, ABSPATH) ) {
			$dir = path_join( ABSPATH, $dir );
		}
	}
	if ( defined('UPLOADS') ) {
		$dir = ABSPATH . UPLOADS;
	}
	return str_replace($_SERVER['DOCUMENT_ROOT'], '', str_replace('\\', '/', $dir));
}

class Quick_Post_Widget extends WP_Widget
{

	function Quick_Post_Widget()
	{
		$widget_ops = array('description' => __('The Quick Post Widget provides a quick way to post directly from your site.', 'quick-post-widget'));
		$this->WP_Widget('quick_post_widget', 'Quick Post Widget', $widget_ops);
	}

	function form($instance)
	{
		global $plugin_url;

		$title = esc_attr($instance['title']);
		$post_title_label = esc_attr($instance['post_title_label']);
		$post_content_label = esc_attr($instance['post_content_label']);
		$post_tags_label = esc_attr($instance['post_tags_label']);
		$category_label = esc_attr($instance['category_label']);
		$new_cat_label = esc_attr($instance['new_cat_label']);
		$submit_label = esc_attr($instance['submit_label']);
		$post_title_default = esc_attr($instance['post_title_default']);
		$post_content_default = esc_attr($instance['post_content_default']);
		$post_cat_parent_default = esc_attr($instance['post_cat_parent_default']);
		$post_tag_list_default = esc_attr($instance['post_tag_list_default']);
		$widget_style = esc_attr($instance['widget_style']);
		$post_title_style = esc_attr($instance['post_title_style']);
		$post_content_style = esc_attr($instance['post_content_style']);
		$post_tags_style = esc_attr($instance['post_tags_style']);
		$cat_checklist_style = esc_attr($instance['cat_checklist_style']);
		$label_style = esc_attr($instance['label_style']);
		$button_style = esc_attr($instance['button_style']);
		$rb_style = esc_attr($instance['rb_style']);
		$new_cat_style = esc_attr($instance['new_cat_style']);
		$error_color = esc_attr($instance['error_color']);
		$cat_list_type = esc_attr($instance['cat_list_type']);
		$publish_status = esc_attr($instance['publish_status']);
		$disable_new_cat = esc_attr($instance['disable_new_cat']);
		$show_tags = esc_attr($instance['show_tags']);
		$disable_editor = esc_attr($instance['disable_editor']);
		$disable_plugins = esc_attr($instance['disable_plugins']);
		$editor_label = esc_attr($instance['editor_label']);
		$new_lines = esc_attr($instance['new_lines']);
		$disable_media_upload = esc_attr($instance['disable_media_upload']);
		$shared_upload_dirs = esc_attr($instance['shared_upload_dirs']);
		$allow_guests = esc_attr($instance['allow_guests']);
		$guest_account = esc_attr($instance['guest_account']);
		$post_confirmation = esc_attr($instance['post_confirmation']);
		?>
		<div class="quick_post_option_form">
		<a href="javascript: void(0)" onclick="popup('<?php echo $plugin_url . 'quick-post-widget-help.html'?>')"><?php _e('Need help? Just click here!', 'quick-post-widget') ?></a>
		<br/>
		<br/>
		<label class="quick_post_option_label" for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Widget title', 'quick-post-widget') ?>:</label>
		<input class="quick_post_option" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" title="<?php _e('Widget title to display', 'quick-post-widget') ?>" />
		<br/>
		<label class="quick_post_option_label" for="<?php echo $this->get_field_id('cat_lst_type'); ?>"><?php _e('Category list type', 'quick-post-widget') ?>:</label>
		<select class="quick_post_droplist" id="<?php echo $this->get_field_id('cat_list_type'); ?>" name="<?php echo $this->get_field_name('cat_list_type'); ?>" title="<?php _e('Category list type', 'quick-post-widget') ?>">
			<option value="droplist" <?php if ($cat_list_type != 'chekclist') echo "selected=\"selected\""; ?> ><?php _e('Droplist', 'quick-post-widget') ?></option> 
			<option value="checklist" <?php if ($cat_list_type == 'checklist') echo "selected=\"selected\""; ?> ><?php _e('Checklist', 'quick-post-widget') ?></option> 
		</select>
		<br/>
		<label class="quick_post_option_label" for="<?php echo $this->get_field_id('publish_status'); ?>"><?php _e('Publish status', 'quick-post-widget') ?>:</label>
		<select class="quick_post_droplist" id="<?php echo $this->get_field_id('publish_status'); ?>" name="<?php echo $this->get_field_name('publish_status'); ?>" title="<?php _e('Publish status', 'quick-post-widget') ?>">
			<option value="publish" <?php if ($publish_status == 'publish') echo "selected=\"selected\""; ?> ><?php _e('Publish', 'quick-post-widget') ?></option> 
			<option value="pending" <?php if ($publish_status == 'pending') echo "selected=\"selected\""; ?> ><?php _e('Pending', 'quick-post-widget') ?></option> 
			<option value="draft" <?php if ($publish_status == 'draft') echo "selected=\"selected\""; ?> ><?php _e('Draft', 'quick-post-widget') ?></option> 
		</select>
		<br/>
		<label class="quick_post_option_label" for="<?php echo $this->get_field_id('error_color'); ?>"><?php _e('Border error color', 'quick-post-widget') ?>:</label>
		<select class="quick_post_droplist" id="<?php echo $this->get_field_id('error_color'); ?>" name="<?php echo $this->get_field_name('error_color'); ?>" title="<?php _e('Bordercolor to use in case a required field is empty', 'quick-post-widget') ?>" > 
		<?php
		 $color_arr = array (
			"#FF0000," . __('Red', 'quick-post-widget') . ",#000000", 
			"#FFFFFF," . __('White', 'quick-post-widget') . ",#000000", 
			"#FFFF00," . __('Yellow', 'quick-post-widget') . ",#000000", 
			"#FF00FF," . __('Fuchsia', 'quick-post-widget') . ",#000000", 
			"#C0C0C0," . __('Silver', 'quick-post-widget') . ",#000000", 
			"#808080," . __('Gray', 'quick-post-widget') . ",#000000", 
			"#808000," . __('Olive', 'quick-post-widget') . ",#000000", 
			"#800080," . __('Purple', 'quick-post-widget') . ",#FFFFFF", 
			"#800000," . __('Maroon', 'quick-post-widget') . ",#FFFFFF", 
			"#00FFFF," . __('Aqua', 'quick-post-widget') . ",#000000", 
			"#00FF00," . __('Lime', 'quick-post-widget') . ",#000000", 
			"#008080," . __('Teal', 'quick-post-widget') . ",#000000", 
			"#008000," . __('Green', 'quick-post-widget') . ",#000000", 
			"#0000FF," . __('Blue', 'quick-post-widget') . ",#FFFFFF", 
			"#000080," . __('Navy', 'quick-post-widget') . ",#FFFFFF", 
			"#000000," . __('Black', 'quick-post-widget') . ",#FFFFFF"
		);
		for($i=0; $i<count($color_arr); $i++) {
			$line = explode(",", $color_arr[$i]);
			$value = $line[0];
			$color = $line[1];
			$textcolor = $line[2];
			echo "<option value=\"".$value."\" style=\"background-color:".$value."; color:".$textcolor."\"";
			if ($value == $error_color) echo "selected=\"selected\"";
			echo ">".$color."</option>\n";
		}
		?>
		</select>
		<br/>
		<label class="quick_post_option_label" for="<?php echo $this->get_field_id('disable_new_cat'); ?>"><?php _e('Disable new cat.', 'quick-post-widget') ?>:</label>
		<input type="checkbox" value="yes" <?php if ($disable_new_cat == 'yes') echo "checked=\"yes\""; ?> id="<?php echo $this->get_field_id('disable_new_cat'); ?>" name="<?php echo $this->get_field_name('disable_new_cat'); ?>" type="text" value="<?php echo $disable_new_cat; ?>" title="<?php _e('Disable creation of new categories (despite of role)', 'quick-post-widget') ?>" />
		<br/>
		<label class="quick_post_option_label" for="<?php echo $this->get_field_id('show_tags'); ?>"><?php _e('Show tags field', 'quick-post-widget') ?>:</label>
		<input type="checkbox" value="yes" <?php if ($show_tags == 'yes') echo "checked=\"yes\""; ?> id="<?php echo $this->get_field_id('show_tags'); ?>" name="<?php echo $this->get_field_name('show_tags'); ?>" type="text" value="<?php echo $show_tags; ?>" title="<?php _e('Show tags input field', 'quick-post-widget') ?>" />
		<br/>
		<label class="quick_post_option_label" for="<?php echo $this->get_field_id('disable_editor'); ?>"><?php _e('Disable visual editor', 'quick-post-widget') ?>:</label>
		<input type="checkbox" value="yes" <?php if ($disable_editor == 'yes') echo "checked=\"yes\""; ?> id="<?php echo $this->get_field_id('disable_editor'); ?>" name="<?php echo $this->get_field_name('disable_editor'); ?>" type="text" value="<?php echo $disable_editor; ?>" title="<?php _e('Disable the visual editor', 'quick-post-widget') ?>" />
		<br/>
		<label class="quick_post_option_label" for="<?php echo $this->get_field_id('disable_plugins'); ?>"><?php _e('Disable editor plugins', 'quick-post-widget') ?>:</label>
		<input type="checkbox" value="yes" <?php if ($disable_plugins == 'yes') echo "checked=\"yes\""; ?> id="<?php echo $this->get_field_id('disable_plugins'); ?>" name="<?php echo $this->get_field_name('disable_plugins'); ?>" type="text" value="<?php echo $disable_plugins; ?>" title="<?php _e('Disable the visual editor plugins', 'quick-post-widget') ?>" />
		<br/>
		<label class="quick_post_option_label" for="<?php echo $this->get_field_id('disable_media_upload'); ?>"><?php _e('Disable media upload', 'quick-post-widget') ?>:</label>
		<input type="checkbox" value="yes" <?php if ($disable_media_upload == 'yes') echo "checked=\"yes\""; ?> id="<?php echo $this->get_field_id('disable_media_upload'); ?>" name="<?php echo $this->get_field_name('disable_media_upload'); ?>" type="text" value="<?php echo $disable_media_upload; ?>" title="<?php _e('Disable media uploading in the visual editor', 'quick-post-widget') ?>" />
		<br/>
		<label class="quick_post_option_label" for="<?php echo $this->get_field_id('shared_upload_dirs'); ?>"><?php _e('Shared upload directories', 'quick-post-widget') ?>:</label>
		<input type="checkbox" value="yes" <?php if ($shared_upload_dirs == 'yes') echo "checked=\"yes\""; ?> id="<?php echo $this->get_field_id('shared_upload_dirs'); ?>" name="<?php echo $this->get_field_name('shared_upload_dirs'); ?>" type="text" value="<?php echo $shared_upload_dirs; ?>" title="<?php _e('Shared instead of private upload directories', 'quick-post-widget') ?>" />
		<br/>
		<label class="quick_post_option_label" for="<?php echo $this->get_field_id('disable_plugins'); ?>"><?php _e('Newlines tag', 'quick-post-widget') ?>:</label>
		<select class="quick_post_droplist" id="<?php echo $this->get_field_id('new_lines'); ?>" name="<?php echo $this->get_field_name('new_lines'); ?>" title="<?php _e('Use P or BR tag for new lines', 'quick-post-widget') ?>">
			<option value="P" <?php if ($new_lines != 'BR') echo "selected=\"selected\""; ?> >&lt;P&gt;</option> 
			<option value="BR" <?php if ($new_lines == 'BR') echo "selected=\"selected\""; ?> >&lt;BR&gt;</option> 
		</select>
		<br/>
		<label class="quick_post_option_label" for="<?php echo $this->get_field_id('allow_guests'); ?>"><?php _e('Allow guests (not logged-in)', 'quick-post-widget') ?>:</label>
		<input type="checkbox" value="yes" <?php if ($allow_guests == 'yes') echo "checked=\"yes\""; ?> id="<?php echo $this->get_field_id('allow_guests'); ?>" name="<?php echo $this->get_field_name('allow_guests'); ?>" type="text" value="<?php echo $allow_guests; ?>" title="<?php _e('Allow guest access (without being logged in, use with care!)', 'quick-post-widget') ?>" />
		<br/>
		<label class="quick_post_option_label" for="<?php echo $this->get_field_id('guest_account'); ?>"><?php _e('Guest account', 'quick-post-widget') ?>:</label>
		<span title="<?php _e('Dedicated account to use for non-logged-in guests', 'quick-post-widget') ?>"><?php wp_dropdown_users('class=quick_post_droplist&name=' . $this->get_field_name('guest_account') . '&selected=' . $guest_account); ?></span>
		<br/>
		<label class="quick_post_option_label" for="<?php echo $this->get_field_id('post_confirmation'); ?>"><?php _e('Message', 'quick-post-widget') ?>:</label>
		<input class="quick_post_option" id="<?php echo $this->get_field_id('post_confirmation'); ?>" name="<?php echo $this->get_field_name('post_confirmation'); ?>" type="text" value="<?php echo $post_confirmation; ?>" title="<?php _e('Optional message after a successful post', 'quick-post-widget') ?>" />
		<br/>
		<h3 class="quick_post_option_header"><?php _e('Labels', 'quick-post-widget') ?></h3>
		<label class="quick_post_option_label" for="<?php echo $this->get_field_id('post_title_label'); ?>"><?php _e('Post title', 'quick-post-widget') ?>:</label>
		<input class="quick_post_option" id="<?php echo $this->get_field_id('post_title_label'); ?>" name="<?php echo $this->get_field_name('post_title_label'); ?>" type="text" value="<?php echo $post_title_label; ?>" title="<?php _e('Label for the field [Post title]', 'quick-post-widget') ?>" />
		<br/>
		<label class="quick_post_option_label" for="<?php echo $this->get_field_id('post_content_label'); ?>"><?php _e('Post content', 'quick-post-widget') ?>:</label>
		<input class="quick_post_option" id="<?php echo $this->get_field_id('post_content_label'); ?>" name="<?php echo $this->get_field_name('post_content_label'); ?>" type="text" value="<?php echo $post_content_label; ?>" title="<?php _e('Label for the field [Post content]', 'quick-post-widget') ?>" />
		<br/>
		<label class="quick_post_option_label" for="<?php echo $this->get_field_id('post_tags_label'); ?>"><?php _e('Tags', 'quick-post-widget') ?>:</label>
		<input class="quick_post_option" id="<?php echo $this->get_field_id('post_tags_label'); ?>" name="<?php echo $this->get_field_name('post_tags_label'); ?>" type="text" value="<?php echo $post_tags_label; ?>" title="<?php _e('Label for the field [Tags]', 'quick-post-widget') ?>" />
		<br/>
		<label class="quick_post_option_label" for="<?php echo $this->get_field_id('category_label'); ?>"><?php _e('Category', 'quick-post-widget') ?>:</label>
		<input class="quick_post_option" id="<?php echo $this->get_field_id('category_label'); ?>" name="<?php echo $this->get_field_name('category_label'); ?>" type="text" value="<?php echo $category_label; ?>" title="<?php _e('Label of the [Categories] droplist', 'quick-post-widget') ?>" />
		<br/>
		<label class="quick_post_option_label" for="<?php echo $this->get_field_id('new_cat_label'); ?>"><?php _e('New cat.', 'quick-post-widget') ?>:</label>
		<input class="quick_post_option" id="<?php echo $this->get_field_id('new_cat_label'); ?>" name="<?php echo $this->get_field_name('new_cat_label'); ?>" type="text" value="<?php echo $new_cat_label; ?>" title="<?php _e('Label of the field [New category]', 'quick-post-widget') ?>" />
		<br/>
		<label class="quick_post_option_label" for="<?php echo $this->get_field_id('submit_label'); ?>"><?php _e('Post button', 'quick-post-widget') ?>:</label>
		<input class="quick_post_option" id="<?php echo $this->get_field_id('submit_label'); ?>" name="<?php echo $this->get_field_name('submit_label'); ?>" type="text" value="<?php echo $submit_label; ?>" title="<?php _e('Label of the [Post] button', 'quick-post-widget') ?>" />
		<br/>
		<label class="quick_post_option_label" for="<?php echo $this->get_field_id('editor_label'); ?>"><?php _e('Editor button', 'quick-post-widget') ?>:</label>
		<input class="quick_post_option" id="<?php echo $this->get_field_id('editor_label'); ?>" name="<?php echo $this->get_field_name('editor_label'); ?>" type="text" value="<?php echo $editor_label; ?>" title="<?php _e('Label of the [Editor] button', 'quick-post-widget') ?>" />
		<br/>
		<h3 class="quick_post_option_header"><?php _e('Defaults', 'quick-post-widget') ?></h3>
		<label class="quick_post_option_label" for="<?php echo $this->get_field_id('post_title_default'); ?>"><?php _e('Post title', 'quick-post-widget') ?>:</label>
		<input class="quick_post_option" id="<?php echo $this->get_field_id('post_title_default'); ?>" name="<?php echo $this->get_field_name('post_title_default'); ?>" type="text" value="<?php echo $post_title_default; ?>" title="<?php _e('Default content of the field [Post title]', 'quick-post-widget') ?>" />
		<br/>
		<label class="quick_post_option_label" for="<?php echo $this->get_field_id('post_content_default'); ?>"><?php _e('Post content', 'quick-post-widget') ?>:</label>
		<input class="quick_post_option" id="<?php echo $this->get_field_id('post_content_default'); ?>" name="<?php echo $this->get_field_name('post_content_default'); ?>" type="text" value="<?php echo $post_content_default; ?>" title="<?php _e('Default content of the field [Post content]', 'quick-post-widget') ?>" />
		<br/>
		<label class="quick_post_option_label" for="<?php echo $this->get_field_id('post_tag_list_default'); ?>"><?php _e('Tag list', 'quick-post-widget') ?>:</label>
		<input class="quick_post_option" id="<?php echo $this->get_field_id('post_tag_list_default'); ?>" name="<?php echo $this->get_field_name('post_tag_list_default'); ?>" type="text" value="<?php echo $post_tag_list_default; ?>" title="<?php _e('Default content of the Tag listbox', 'quick-post-widget') ?>" />
		<br/>
		<label class="quick_post_option_label" for="<?php echo $this->get_field_id('post_cat_parent_default'); ?>"><?php _e('Parent cat.', 'quick-post-widget') ?>:</label>
		<input class="quick_post_option" id="<?php echo $this->get_field_id('post_cat_parent_default'); ?>" name="<?php echo $this->get_field_name('post_cat_parent_default'); ?>" type="text" value="<?php echo $post_cat_parent_default; ?>" title="<?php _e('Default content of the field [Parent category]', 'quick-post-widget') ?>" />
		<br/>
		<h3 class="quick_post_option_header"><?php _e('Styles (override CSS)', 'quick-post-widget') ?></h3>
		<label class="quick_post_option_label" for="<?php echo $this->get_field_id('widget_style'); ?>"><?php _e('Widget', 'quick-post-widget') ?>:</label>
		<input class="quick_post_option" id="<?php echo $this->get_field_id('widget_style'); ?>" name="<?php echo $this->get_field_name('widget_style'); ?>" type="text" value="<?php echo $widget_style; ?>" title="<?php _e('Styling for the widget (use valid CSS syntax)', 'quick-post-widget') ?>" />
		<br/>
		<label class="quick_post_option_label" for="<?php echo $this->get_field_id('post_title_style'); ?>"><?php _e('Post title', 'quick-post-widget') ?>:</label>
		<input class="quick_post_option" id="<?php echo $this->get_field_id('post_title_style'); ?>" name="<?php echo $this->get_field_name('post_title_style'); ?>" type="text" value="<?php echo $post_title_style; ?>" title="<?php _e('Styling for the field [Post title] (use valid CSS syntax)', 'quick-post-widget') ?>" />
		<br/>
		<label class="quick_post_option_label" for="<?php echo $this->get_field_id('post_content_style'); ?>"><?php _e('Post content', 'quick-post-widget') ?>:</label>
		<input class="quick_post_option" id="<?php echo $this->get_field_id('post_content_style'); ?>" name="<?php echo $this->get_field_name('post_content_style'); ?>" type="text" value="<?php echo $post_content_style; ?>" title="<?php _e('Styling for the field [Post content] (use valid CSS syntax)', 'quick-post-widget') ?>" />
		<br/>
		<label class="quick_post_option_label" for="<?php echo $this->get_field_id('post_tags_style'); ?>"><?php _e('Post tags', 'quick-post-widget') ?>:</label>
		<input class="quick_post_option" id="<?php echo $this->get_field_id('post_tags_style'); ?>" name="<?php echo $this->get_field_name('post_tags_style'); ?>" type="text" value="<?php echo $post_tags_style; ?>" title="<?php _e('Styling for the field [Tags] (use valid CSS syntax)', 'quick-post-widget') ?>" />
		<br/>
		<label class="quick_post_option_label" for="<?php echo $this->get_field_id('cat_checklist_style'); ?>"><?php _e('Cat. checklist', 'quick-post-widget') ?>:</label>
		<input class="quick_post_option" id="<?php echo $this->get_field_id('cat_checklist_style'); ?>" name="<?php echo $this->get_field_name('cat_checklist_style'); ?>" type="text" value="<?php echo $cat_checklist_style; ?>" title="<?php _e('Styling for the [Category checklist] (use valid CSS syntax)', 'quick-post-widget') ?>" />
		<br/>
		<label class="quick_post_option_label" for="<?php echo $this->get_field_id('label_style'); ?>"><?php _e('Labels', 'quick-post-widget') ?>:</label>
		<input class="quick_post_option" id="<?php echo $this->get_field_id('label_style'); ?>" name="<?php echo $this->get_field_name('label_style'); ?>" type="text" value="<?php echo $label_style; ?>" title="<?php _e('Styling for labels (use valid CSS syntax)', 'quick-post-widget') ?>" />
		<br/>
		<label class="quick_post_option_label" for="<?php echo $this->get_field_id('rb_style'); ?>"><?php _e('Radio', 'quick-post-widget') ?>:</label>
		<input class="quick_post_option" id="<?php echo $this->get_field_id('rb_style'); ?>" name="<?php echo $this->get_field_name('rb_style'); ?>" type="text" value="<?php echo $rb_style; ?>" title="<?php _e('Styling for the radio button (use valid CSS syntax)', 'quick-post-widget') ?>" />
		<br/>
		<label class="quick_post_option_label" for="<?php echo $this->get_field_id('new_cat_style'); ?>"><?php _e('New cat.', 'quick-post-widget') ?>:</label>
		<input class="quick_post_option" id="<?php echo $this->get_field_id('new_cat_style'); ?>" name="<?php echo $this->get_field_name('new_cat_style'); ?>" type="text" value="<?php echo $new_cat_style; ?>" title="<?php _e('Styling for the field [New category] (use valid CSS syntax)', 'quick-post-widget') ?>" />
		<br/>
		<label class="quick_post_option_label" for="<?php echo $this->get_field_id('button_style'); ?>"><?php _e('Buttons', 'quick-post-widget') ?>:</label>
		<input class="quick_post_option" id="<?php echo $this->get_field_id('button_style'); ?>" name="<?php echo $this->get_field_name('button_style'); ?>" type="text" value="<?php echo $button_style; ?>" title="<?php _e('Styling for the [Post] button (use valid CSS syntax)', 'quick-post-widget') ?>" />
		</div>
		<?php
	}

	function update($new_instance, $old_instance)
	{
		$instance = $old_instance;
		$instance['title']						= strip_tags($new_instance['title']);
		$instance['post_title_label']			= strip_tags($new_instance['post_title_label']);
		$instance['category_label']				= strip_tags($new_instance['category_label']);
		$instance['new_cat_label']				= strip_tags($new_instance['new_cat_label']);
		$instance['post_content_label']			= strip_tags($new_instance['post_content_label']);
		$instance['post_tags_label']			= strip_tags($new_instance['post_tags_label']);
		$instance['submit_label']				= strip_tags($new_instance['submit_label']);
		$instance['post_title_default']			= strip_tags($new_instance['post_title_default']);
		$instance['post_content_default']		= strip_tags($new_instance['post_content_default']);
		$instance['post_tag_list_default']		= strip_tags($new_instance['post_tag_list_default']);
		$instance['post_cat_parent_default']	= strip_tags($new_instance['post_cat_parent_default']);
		$instance['widget_style']				= strip_tags($new_instance['widget_style']);
		$instance['post_title_style']			= strip_tags($new_instance['post_title_style']);
		$instance['post_content_style']			= strip_tags($new_instance['post_content_style']);
		$instance['post_tags_style']			= strip_tags($new_instance['post_tags_style']);
		$instance['cat_checklist_style']		= strip_tags($new_instance['cat_checklist_style']);
		$instance['label_style']				= strip_tags($new_instance['label_style']);
		$instance['button_style']				= strip_tags($new_instance['button_style']);
		$instance['rb_style']					= strip_tags($new_instance['rb_style']);
		$instance['new_cat_style']				= strip_tags($new_instance['new_cat_style']);
		$instance['error_color']				= strip_tags($new_instance['error_color']);
		$instance['cat_list_type']				= strip_tags($new_instance['cat_list_type']);
		$instance['publish_status']				= strip_tags($new_instance['publish_status']);
		$instance['disable_new_cat']			= strip_tags($new_instance['disable_new_cat']);
		$instance['show_tags']					= strip_tags($new_instance['show_tags']);
		$instance['disable_editor']				= strip_tags($new_instance['disable_editor']);
		$instance['disable_plugins']			= strip_tags($new_instance['disable_plugins']);
		$instance['editor_label']				= strip_tags($new_instance['editor_label']);
		$instance['new_lines']					= strip_tags($new_instance['new_lines']);
		$instance['disable_media_upload']		= strip_tags($new_instance['disable_media_upload']);
		$instance['shared_upload_dirs']			= strip_tags($new_instance['shared_upload_dirs']);
		$instance['allow_guests']				= strip_tags($new_instance['allow_guests']);
		$instance['guest_account']				= strip_tags($new_instance['guest_account']);
		$instance['post_confirmation']				= strip_tags($new_instance['post_confirmation']);
		return $instance;
	}

	function widget($args, $instance)
	{
		global $plugin_url;
		global $qpw_locale;

		extract($args);
		
		$allow_guests				= ( $instance['allow_guests'] != '' ) ? esc_attr($instance['allow_guests']) : '';
		$guest_account				= ( $instance['guest_account'] != '' ) ? esc_attr($instance['guest_account']) : '1';
		$title						= ( $instance['title'] != '' ) ? apply_filters('title', esc_attr($instance['title'])) : '';
		$post_title_label			= ( $instance['post_title_label'] != '' ) ? esc_attr($instance['post_title_label']) : __('Title', 'quick-post-widget') . ':';
		$post_content_label			= ( $instance['post_content_label'] != '' ) ? esc_attr($instance['post_content_label']) : __('Content', 'quick-post-widget') . ':';
		$post_tags_label			= ( $instance['post_tags_label'] != '' ) ? esc_attr($instance['post_tags_label']) : __('Tags', 'quick-post-widget') . ':';
		$category_label				= ( $instance['category_label'] != '' ) ? esc_attr($instance['category_label']) : __('Category', 'quick-post-widget') . ':';
		$new_cat_label				= ( $instance['new_cat_label'] != '' ) ? esc_attr($instance['new_cat_label']) : __('New category', 'quick-post-widget') . ':';
		$submit_label				= ( $instance['submit_label'] != '' ) ? esc_attr($instance['submit_label']) : __('Post', 'quick-post-widget');
		$post_title_default			= ( $instance['post_title_default'] != '' ) ? esc_attr($instance['post_title_default']) : __('Post title', 'quick-post-widget');
		$post_content_default		= ( $instance['post_content_default'] != '' ) ? esc_attr($instance['post_content_default']) : __('Post content', 'quick-post-widget');
		$post_tag_list_default		= ( $instance['post_tag_list_default'] != '' ) ? esc_attr($instance['post_tag_list_default']) : __('Quickselect a tag', 'quick-post-widget');
		$post_cat_parent_default	= ( $instance['post_cat_parent_default'] != '' ) ? esc_attr($instance['post_cat_parent_default']) : __('Parent category', 'quick-post-widget');
		$widget_style				= ( $instance['widget_style'] != '' ) ? esc_attr($instance['widget_style']) : '';
		$post_title_style			= ( $instance['post_title_style'] != '' ) ? esc_attr($instance['post_title_style']) : '';
		$post_content_style			= ( $instance['post_content_style'] != '' ) ? esc_attr($instance['post_content_style']) : '';
		$post_tags_style			= ( $instance['post_tags_style'] != '' ) ? esc_attr($instance['post_tags_style']) : '';
		$cat_checklist_style		= ( $instance['cat_checklist_style'] != '' ) ? esc_attr($instance['cat_checklist_style']) : '';
		$label_style				= ( $instance['label_style'] != '' ) ? esc_attr($instance['label_style']) : '';
		$button_style				= ( $instance['button_style'] != '' ) ? esc_attr($instance['button_style']) : '';
		$rb_style					= ( $instance['rb_style'] != '' ) ? esc_attr($instance['rb_style']) : '';
		$new_cat_style				= ( $instance['new_cat_style'] != '' ) ? esc_attr($instance['new_cat_style']) : '';
		$error_color				= ( $instance['error_color'] != '' ) ? esc_attr($instance['error_color']) : '#FF0000';
		$cat_list_type				= ( $instance['cat_list_type'] != '' ) ? esc_attr($instance['cat_list_type']) : 'droplist';
		$publish_status				= ( $instance['publish_status'] != '' ) ? esc_attr($instance['publish_status']) : 'publish';
		$disable_new_cat			= ( $instance['disable_new_cat'] != '' ) ? esc_attr($instance['disable_new_cat']) : '';
		$show_tags					= ( $instance['show_tags'] != '' ) ? esc_attr($instance['show_tags']) : '';
		$disable_editor				= ( $instance['disable_editor'] != '' ) ? esc_attr($instance['disable_editor']) : '';
		$disable_plugins			= ( $instance['disable_plugins'] != '' ) ? esc_attr($instance['disable_plugins']) : '';
		$editor_label				= ( $instance['editor_label'] != '' ) ? esc_attr($instance['editor_label']) : __('Visual Editor', 'quick-post-widget');
		$new_lines					= ( $instance['new_lines'] != '' ) ? esc_attr($instance['new_lines']) : 'P';
		$disable_media_upload		= ( $instance['disable_media_upload'] != '' ) ? esc_attr($instance['disable_media_upload']) : '';
		$shared_upload_dirs			= ( $instance['shared_upload_dirs'] != '' ) ? esc_attr($instance['shared_upload_dirs']) : '';
		$post_confirmation			= ( $instance['post_confirmation'] != '' ) ? esc_attr($instance['post_confirmation']) : '';

		if ( (!is_user_logged_in()) && ($allow_guests == 'yes') ) {
			$guest_info = get_userdata($guest_account);
			$qpw_user_id = $guest_info->ID;
			$qpw_user_login = $guest_info->user_login;
			if ( $guest_info->user_level >= 5 ) {
				$qpw_user_can_manage_categories = 'yes';
				$qpw_user_can_publish_posts = 'yes';
			} elseif ( $guest_info->user_level >= 2 ) { 	
				$qpw_user_can_manage_categories = '';
				$qpw_user_can_publish_posts = 'yes';
			} elseif ( $guest_info->user_level == 1 ) { 	
				$qpw_user_can_manage_categories = '';
				$qpw_user_can_publish_posts = 'yes';
				$publish_status = 'pending';
				$disable_media_upload = 'yes';
			}
			if ( $guest_info->rich_editing == 'true' )
				$qpw_user_rich_editing = 'yes';
		} else {
			global $current_user;
			get_currentuserinfo();
			$qpw_user_id = $current_user->ID;
			$qpw_user_login = $current_user->user_login;
			if ( current_user_can('publish_posts') )
				$qpw_user_can_publish_posts = 'yes';
			if ( current_user_can('manage_categories') )
				$qpw_user_can_manage_categories = 'yes';
			if ( $current_user->rich_editing == 'true' )
				$qpw_user_rich_editing = 'yes';
		}

		if ( $qpw_user_can_publish_posts == 'yes' ) {

			if ( ($qpw_user_can_manage_categories == 'yes') && ($disable_new_cat != 'yes') )
				require_once(WP_PLUGIN_DIR . '/../../wp-admin/includes/taxonomy.php');

			require_once(WP_PLUGIN_DIR . '/../../wp-admin/includes/template.php');

			echo $before_widget;

			echo $before_title . $title . $after_title;

			?>
			<div id="quick_post_form" style="<?php echo $widget_style; ?>">
				<form method="post" name=quickpostwidget action="" >
					<p><label for="quick_post_title" class="quick_post_label" style="<?php echo $label_style; ?>"><?php echo $post_title_label; ?></label>
					<br />
					<input type="text" name="quick_post_title" id="quick_post_title" style="<?php echo $post_title_style; ?>" value="<?php if ($_POST['quick_post_title'] != '') echo $_POST['quick_post_title']; else echo '<' . $post_title_default . '>'; ?>"<?php if ( $post_title_default != '' ) { ?> onblur="if(this.value=='') this.value='<?php echo '<' . $post_title_default . '>'; ?>'; return false;" onfocus="if(this.value=='<?php echo '<' . $post_title_default . '>'; ?>') this.value=''; return false;"<?php } ?> /></p>
					<p><label for="quick_post_content" class="quick_post_label" style="<?php echo $label_style; ?>"><?php echo $post_content_label; ?></label>
					<br />
					<textarea name="quick_post_content" rows="3" id="quick_post_content" style="<?php echo $post_content_style; ?>" <?php if ( $post_content_default != '' ) { ?> onblur="if(this.value=='') this.value='<?php echo '<' . $post_content_default . '>'; ?>'; return false;" onfocus="if(this.value=='<?php echo '<' . $post_content_default . '>'; ?>') this.value=''; return false;"<?php } ?> ><?php if (stripslashes($_POST['quick_post_content']) != '') echo stripslashes($_POST['quick_post_content']); else echo '<' . $post_content_default . '>'; ?></textarea></p>
					<?php if ( ($disable_editor != 'yes') && ($qpw_user_rich_editing == 'yes') ) { ?>
						<?php
						if ($shared_upload_dirs == 'yes')
							$_SESSION['upath'] = str_replace('//', '/', upload_dir() . '/shared');
						else
							$_SESSION['upath'] = str_replace('//', '/', upload_dir() . '/' . str_replace(' ', '_', $qpw_user_login));
						?>
						<p>
						<input type='button' id="quick_post_load" style="<?php echo $button_style; ?>" value="<?php echo $editor_label; ?>" title="<?php echo $editor_label; ?>" />
						</p>
					<?php } ?>
					<?php if ($show_tags == 'yes') { ?>
						<label for="quick_post_tags" class="quick_post_label" style="<?php echo $label_style; ?>"><?php echo $post_tags_label; ?></label>
						<br />
						<input type="text" name="quick_post_tags" id="quick_post_tags" style="<?php echo $post_tags_style; ?>" value="<?php echo $_POST['quick_post_tags'] ?>" />
						<select id="quick_post_tag_list" onChange="if (this.options[this.selectedIndex].text != '<?php echo $post_tag_list_default; ?>') document.getElementById('quick_post_tags').value=ltrim(document.getElementById('quick_post_tags').value + ',' + this.options[this.selectedIndex].text,','); ">
						<?php
						echo "<option>".$post_tag_list_default."</option>\n";
						foreach (get_tags() as $tag)
						{
							echo "<option value=\"";
							echo $tag->term_id;
							echo "\">" . $tag->name . "</option>\n";
						} ?>
						</select>
					<?php } ?>
					<?php if ( ($qpw_user_can_manage_categories == 'yes') && ($disable_new_cat != 'yes') ) { ?>
						<p>
						<input type=radio name="quick_post_rb" class="quick_post_rb" value="existing" style="<?php echo $rb_style; ?>" <?php if ($_POST['quick_post_rb'] != 'new') echo 'checked' ?> onclick="disableIt('quick_post_new_cat',true); disableIt('quick_post_new_cat_parent',true); <?php if ($cat_list_type == 'droplist') echo 'disableIt(\'quick_post_cat\',false);'; else echo 'disableIt(\'quick_post_cat_checklist\', false);'; ?>" />
					<?php } ?>
					<label for="quick_post_cat" class="quick_post_label" style="<?php echo $label_style; ?>"><?php echo $category_label; ?></label> <br /> <?php if ($cat_list_type == 'droplist') wp_dropdown_categories('hide_empty=0&name=quick_post_cat&hierarchical=1');?>
					</p>

					<?php if ($cat_list_type == 'checklist') { ?>
					<div id="quick_post_cat_checklist" style="<?php echo $cat_checklist_style; ?>">
						<ul id="cats">
							<?php wp_category_checklist(0, 0, $_POST['post_category'], false, null, false);?>
						</ul>
					</div>
					<?php } ?>
					
					<?php if ( ($qpw_user_can_manage_categories == 'yes') && ($disable_new_cat != 'yes') ) { ?>
						<p>
						<input type=radio name="quick_post_rb" class="quick_post_rb" value="new" style="<?php echo $rb_style; ?>" <?php if ($_POST['quick_post_rb'] == 'new') echo 'checked' ?> onclick="disableIt('quick_post_new_cat',false); disableIt('quick_post_new_cat_parent',false); <?php if ($cat_list_type == 'droplist') echo 'disableIt(\'quick_post_cat\',true);'; else echo 'disableIt(\'quick_post_cat_checklist\', true);'; ?>" />
						<label for="quick_post_new_cat" class="quick_post_label" style="<?php echo $label_style; ?>"><?php echo $new_cat_label; ?></label> <br />
						<input type="text" name="quick_post_new_cat" id="quick_post_new_cat" style="<?php echo $new_cat_style; ?>" value="<?php echo $_POST['quick_post_new_cat'] ?>" <?php if ($_POST['quick_post_rb'] != 'new') echo 'disabled' ?> />

						<?php wp_dropdown_categories('hide_empty=0&name=quick_post_new_cat_parent&hierarchical=1&show_option_none=' . $post_cat_parent_default);?>

						</p>
					<?php } ?>
					<p><input type="submit" id="quick_post_submit" style="<?php echo $button_style; ?>" value="<?php echo $submit_label; ?>" title="<?php echo $submit_label; ?>" /></p>
					<input type="hidden" id="quick_post_tinymce_path" value="<?php echo get_bloginfo('wpurl') . '/wp-includes/js/tinymce'; ?>" />
					<input type="hidden" id="quick_post_plugin_path" value="<?php echo $plugin_url . 'mce/'; ?>" />
					<input type="hidden" id="quick_post_plugins" value="<?php if ($disable_plugins != 'yes') echo 'safari,paste,spellchecker,media,-preview,-advlink,-advimage,-emotions,-searchreplace,-inlinepopups'; else echo ''; ?>" />
					<input type="hidden" id="quick_post_buttons1" value="<?php if ($disable_plugins != 'yes') echo 'preview,|,spellchecker,|,bold,italic,strikethrough,underline,|,justifyleft,justifycenter,justifyright,justifyfull,|,forecolor,backcolor,|,fontselect,|,fontsizeselect'; else echo 'bold,italic,strikethrough,underline,|,justifyleft,justifycenter,justifyright,justifyfull,|,forecolor,backcolor,|,fontselect,|,fontsizeselect'; ?>" />
					<input type="hidden" id="quick_post_buttons2" value="<?php if ($disable_plugins != 'yes') echo 'pastetext,pasteword,selectall,|,outdent,indent,|,bullist,numlist,|,undo,redo,|,media,link,unlink,image,charmap,|,search,replace,|,emotions,|,removeformat,cleanup,code'; else echo 'outdent,indent,|,bullist,numlist,|,undo,redo,|,media,link,unlink,image,charmap,|,removeformat,cleanup,code'; ?>" />
					<input type="hidden" id="quick_post_newlines" value="<?php echo $new_lines; ?>" />
					<input type="hidden" id="quick_post_file_manager" value="<?php if ($disable_media_upload != 'yes') echo 'tinyBrowser'; else echo ''; ?>" />
					<input type="hidden" id="quick_post_language" value="<?php echo $qpw_locale; ?>" />
					<input type="hidden" id="quick_post_ok" value="<?php _e('OK', 'quick-post-widget') ?>" />
					<input type="hidden" id="quick_post_cancel" value="<?php _e('Cancel', 'quick-post-widget') ?>" />
				</form>
			</div>
			
			<?php if ($_POST['quick_post_rb'] == 'new') { ?>
				<script type="text/javascript">
					<?php if ($cat_list_type == 'droplist') echo 'disableIt(\'quick_post_cat\',true);'; else echo 'disableIt(\'quick_post_cat_checklist\', true);'; ?>
				</script>	
			<?php } else { ?>
				<script type="text/javascript">
					<?php if ($cat_list_type != 'droplist') echo 'disableIt(\'quick_post_cat_checklist\', false);'; ?>
				</script> 
				<?php if ( ($qpw_user_can_manage_categories == 'yes') && ($disable_new_cat != 'yes') ) { ?>
					<script type="text/javascript">
						disableIt('quick_post_new_cat_parent',true);
					</script>	
				<?php }
			} ?>

			<?php if ($_POST['quick_post_title'] == '<' . $post_title_default . '>') { ?>
				<script type="text/javascript">
					document.getElementById('quick_post_title').style.border="solid 1px <?php echo $error_color; ?>";
				</script>	
			<?php } ?>

			<?php if ($_POST['quick_post_content'] == '<' . $post_content_default . '>') { ?>
				<script type="text/javascript">
					document.getElementById('quick_post_content').style.border="solid 1px <?php echo $error_color; ?>";
				</script>	
			<?php } ?>

			<?php if (($_POST['quick_post_rb'] == 'new') && ($_POST['quick_post_new_cat'] == '')) { ?>
				<script type="text/javascript">
					document.getElementById('quick_post_new_cat').style.border="solid 1px <?php echo $error_color; ?>";
				</script>	
			<?php } ?>

			<?php if (($_POST['quick_post_rb'] == 'existing') && ($cat_list_type == 'checklist') && (count($_POST['post_category']) == 0)) { ?>
				<script type="text/javascript">
					document.getElementById('quick_post_cat_checklist').style.border="solid 1px <?php echo $error_color; ?>";
				</script>
			<?php }

			echo $after_widget;

			if (($_POST['quick_post_title'] != '<' . $post_title_default . '>') && ($_POST['quick_post_content'] != '<' . $post_content_default . '>')) {

				if ($_POST['quick_post_rb'] == 'new') {
					if ($_POST['quick_post_new_cat'] != '') {
						$cat_id = get_cat_ID($_POST['quick_post_new_cat']);
						if ($cat_id == 0) {
							if ($_POST['quick_post_new_cat_parent'] == -1 ) {
								$cat_id = wp_create_category($_POST['quick_post_new_cat']);
							} else {
								$cat_id = wp_create_category($_POST['quick_post_new_cat'], $_POST['quick_post_new_cat_parent']);
							}
						}
						$_cats = array($cat_id);
					}
				} else {
					if ($cat_list_type == 'droplist') {
						$cat_id = $_POST['quick_post_cat'];
						$_cats = array($cat_id);
					} else {
						$_cats = $_POST['post_category'];
					}
				}

			if (count($_cats) > 0) {
					$post_id = wp_insert_post( array(
						'post_author'		=> $qpw_user_id,
						'post_title'		=> $_POST['quick_post_title'],
						'post_content'		=> $_POST['quick_post_content'],
						'tags_input'		=> $_POST['quick_post_tags'],
						'post_category'		=> $_cats,
						'post_status'		=> $publish_status
					) );
				}

				if ($post_id > 0)
					if ($post_confirmation == '') {
						echo "<meta http-equiv='Refresh' Content='0'; url='".$_SERVER['PHP_SELF']."'>";
					} else { ?>
						<div id="quick_post_success" title="Quick Post Widget"> 
							<p><?php echo $post_confirmation; ?></p> 
						</div>
				<?php }
			}
		}
	}
}
add_action('widgets_init', create_function('', 'return register_widget("Quick_Post_Widget");'));
load_plugin_textdomain('quick-post-widget', 'wp-content/plugins/quick-post-widget/langs');
wp_enqueue_style('quick-post-style', $plugin_url . 'css/quick-post-widget.css');
wp_enqueue_style('jquery-ui-style', $plugin_url . 'css/jquery-ui.css');
wp_enqueue_script('tinymce', get_bloginfo('wpurl') . '/wp-includes/js/tinymce/tiny_mce.js');
wp_enqueue_script('tinybrowser', $plugin_url . 'mce/tinybrowser/tb_tinymce.js.php');
wp_enqueue_script('quick-post-script', $plugin_url . 'js/qpw.js', array('jquery','jquery-ui-dialog'));
wp_enqueue_script('qpw_locale_' . $qpw_locale, $plugin_url . 'mce/langs/' . $qpw_locale . '.js');
?>