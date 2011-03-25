<?php
/**
 * API for adding skins and leafs to Headway.
 * 
 * @todo Rule the world ;-)
 *
 * @author Clay Griffiths
 * @package Headway
 * @subpackage Extendability
 * 
 * @since 1.6
 **/


/**
 * Headway skins API.
 *
 * @package Headway
 * @subpackage Skins
 **/
class HeadwaySkin {
	var $id;
	var $name;
	var $path;
	var $abspath;

	
	/**
	 * Constructor
	 * 
	 * @uses HeadwaySkin::register()
	 **/
	function HeadwaySkin($id, $name, $path, $options = false){
		$this->register($id, $name, $path, $options);
	}
	
	
	/**
	 * Registers the skin.  Sets up variables and registers a few actions.
	 * 
	 * @param string $id Unique ID of the skin.
	 * @param string $name Name of the skin.
	 * @param string $path Path to the skin.  Used mainly for stylesheet URL.
	 *
	 * @uses HeadwaySkin::is_active()
	 * @uses HeadwaySkin::exec()
	 * @uses HeadwaySkin::create_option()
	 **/
	function register($id, $name, $path, $options = false){
		$this->id = $id;
		$this->name = $name;
		$this->path = WP_PLUGIN_URL.'/'.str_replace(basename($path), '', plugin_basename($path));
		$this->abspath = ABSPATH.'wp-content/plugins/'.str_replace(basename($path), '', plugin_basename($path));
		$this->options = $options;
		
		$this->options['skin'] = $id;
				
		add_action('headway_skins_selector', array(&$this, 'create_option'));
		add_action('headway_skins_thumbnails', array(&$this, 'create_thumbnail'));

		if($this->is_active()) $this->exec();
	}
	
	
	/**
	 * Adds the action to print the stylesheet.
	 * 
	 * @uses HeadwaySkin::print_head()
	 **/
	function exec(){
		global $headway_skin_name;
		
		$headway_skin_name = $this->name;
		
		add_action('headway_skins_stylesheets', array(&$this, 'print_head'));
		
		if(isset($_GET['headway-skin-preview']) && $_GET['headway-skin-preview'] != 'none'){
			global $preview_skin_options;
			
			$preview_skin_options = $this->options;
		} elseif(headway_json_encode(headway_get_option('skin-options')) != headway_json_encode($this->options)){
			headway_update_option('skin-options', $this->options);
		}
	}
	
	
	/**
	 * Checks if the skin is activated.
	 *
	 * @return bool
	 **/
	function is_active(){	
		global $headway_active_skin;
								
		if(headway_get_option('active-skin') == $this->id && !isset($_GET['headway-skin-preview'])){
			$headway_active_skin = $this->id;
						
			return true;
		} elseif(isset($_GET['headway-skin-preview']) && $_GET['headway-skin-preview'] != 'none') {
			$headway_active_skin = $_GET['headway-skin-preview'];
									
			return true;
		} else {
			return false;
		}
	}
	
	
	/**
	 * Returns or echos the path to the skin.
	 * 
	 * @param bool $print Whether or not to echo the path.
	 *
	 * @return void|string
	 **/
	function get_url($print = false){
		if($print){
			echo $this->path;
		} else {
			return $this->path;
		}
	}
	
	
	/**
	 * Echos the HTML for the stylesheet link.
	 *
	 * @uses HeadwaySkin::get_url()
	 * 
	 * @see HeadwaySkin::exec()
	 **/
	function print_head(){
		$stylesheet_url = str_replace(':/', '://', str_replace('//', '/', $this->get_url().'/style.css'));
						
		echo '<link rel="stylesheet" type="text/css" href="'.$stylesheet_url.'" />'."\n";
	}
	
	
	/**
	 * Creates the option for the skin select box.
	 *
	 * @see HeadwaySkin::register()
	 **/
	function create_option(){
		$selected = (headway_get_option('active-skin') == $this->id) ? ' selected' : null;
		
		echo '<option value="'.$this->id.'"'.$selected.'>'.$this->name.'</option>';
	}
	
	/**
	 * Creates the thumbnail/preview for the skin/template panel.
	 *
	 * @see HeadwaySkin::register()
	 **/
	function create_thumbnail(){
		$thumbnail = (file_exists($this->abspath.'/screenshot.jpg')) ? $this->get_url().'/screenshot.jpg' : get_bloginfo('template_directory').'/library/visual-editor/images/default_thumbnail.png';
		$class = ($this->is_active()) ? ' class="selected"' : null;
		
		echo '<li id="'.$this->id.'"'.$class.'><a href="#"><img src="'.$thumbnail.'" />'.$this->name.'</a></li>';
	}
	
	
	/**
	 * Creates a text input element along with the table HTML and label.
	 *
	 * @param array $options Associative array.  Requires name and label.
	 **/
	function create_text_input($options){
		
		$no_border = (isset($options['no-border'])) ? ' class="no-border"' : null;
		
		$return = '<tr'.$no_border.'>';				
		
		$return .= '<th scope="row"><label for="skin_option_'.str_replace('-', '_', $options['name']).'">'.$options['label'].'</label></th>
						<td><input type="text" name="skin-options['.$options['name'].']" id="skin_option_'.str_replace('-', '_', $options['name']).'" value="'.stripslashes(htmlentities(headway_skin_option($options['name'], true))).'" />';
		
		if($options['description']) $return .= '<span class="description">'.$options['description'].'</span>';
		
		$return .= '</td>';

		$return .= '</tr>';
		
		global $headway_skin_options;
		$headway_skin_options = $headway_skin_options.$return;
	}
	
	
	/**
	 * Creates a textarea input element along with the table HTML and label.
	 *
	 * @param array $options Associative array.  Requires name and label.
	 **/
	function create_textarea($options){
		
		$no_border = (isset($options['no-border'])) ? ' class="no-border"' : null;
		
		$return = '<tr'.$no_border.'>';				
		
		$return .= '<th scope="row"><label for="skin_option_'.str_replace('-', '_', $options['name']).'">'.$options['label'].'</label></th>
						<td><textarea class="regular-text" name="skin-options['.$options['name'].']" id="skin_option_'.str_replace('-', '_', $options['name']).'" rows="6">'.stripslashes(htmlentities(headway_skin_option($options['name'], true))).'</textarea>';

		if(isset($options['description'])) $return .= '<span class="description">'.$options['description'].'</span>';

		$return .= '</td>';

		$return .= '</tr>';
		
		global $headway_skin_options;
		$headway_skin_options = $headway_skin_options.$return;
	}
	
	
	/**
	 * Creates a select element along with the table HTML and label.
	 *
	 * @param array $options Associative array.  Needs a name, left label and checkbox label.
	 **/
	function create_checkbox($options){
		
		$no_border = (isset($options['no-border'])) ? ' class="no-border"' : null;
		
		$return = '<tr'.$no_border.'>';				
		
		$return .= '<th scope="row"><label for="skin_option_'.str_replace('-', '_', $options['name']).'">'.$options['left-label'].'</label></th>	
				<td>
					<p class="radio-container">
						<input type="hidden" name="skin-options['.$options['name'].']" value="DELETE" />
						<input type="checkbox" class="radio" id="skin_option_'.str_replace('-', '_', $options['name']).'" name="skin-options['.$options['name'].']"'.headway_checkbox_value(headway_skin_option($options['name'], true)).'/><label for="skin_option_'.str_replace('-', '_', $options['name']).'">'.$options['checkbox-label'].'</label>
					</p>';
		
		if($options['description']) $return .= '<span class="description">'.$options['description'].'</span>';

		$return .= '</td>';

		$return .= '</tr>';
		
		global $headway_skin_options;
		$headway_skin_options = $headway_skin_options.$return;
	}
	
	
	/**
	 * Creates a select element along with the table HTML and label.
	 *
	 * @param array $options Associative array.  Needs a name, label and options (value and label).
	 **/
	function create_select($options){
		
		$no_border = (isset($options['no-border'])) ? ' class="no-border"' : null;
		
		$return = '<tr'.$no_border.'>				
				<th scope="row">
					<label for="skin_option_'.str_replace('-', '_', $options['name']).'">'.$options['label'].'</label>
				</th>
				
				<td>
					<select id="skin_option_'.str_replace('-', '_', $options['name']).'" name="skin-options['.$options['name'].']">';
			
					foreach($options['options'] as $value => $label){
						$return .= '<option value="'.$value.'"'.headway_option_value(headway_skin_option($options['name'], true), $value).'>'.$label.'</option>';
					}
	
			$return .= '</select>';
		
		if($options['description']) $return .= '<span class="description">'.$options['description'].'</span>';

		$return .= '</td>';
		
		$return .=	'</tr>';
		
		global $headway_skin_options;
		$headway_skin_options = $headway_skin_options.$return;
	}
	
	
	/**
	 * Allows HTML/PHP to be inserted at will in the skin options.
	 *
	 * @param $options Associative array.  Label and border.
	 * @param mixed $content
	 **/
	function create_custom_option($options = false, $content, $callback = false){
		
		$no_border = ($options['no-border']) ? ' class="no-border"' : null;
		
		$return = '<tr'.$no_border.'>';
		
		if($options['label']){
		
			$return .= '<th scope="row">
				<label>'.$options['label'].'</label>
			</th>
			<td>';			
				
		} else {
			$return .= '<td colspan="2">';
		}
			
		$return .= $content;

		$return .= '</td>';
		
		$return .=	'</tr>';
		
		global $headway_skin_options;
		$headway_skin_options = $headway_skin_options.$return;
		
		if($callback) add_action('headway_custom_option_actions', $callback);
		
	}
}

/**
 * Queries the database for the specific skin option.  Don't mix this up with headway_get_skin_option()
 *
 * @uses headway_get_option()
 * 
 * @param string $option Skin option to be queried.
 * 
 * @return mixed
 **/
function headway_skin_option($option, $force_query = false){
	return headway_get_option('skin-'.headway_get_option('active-skin').'-'.$option, true, $force_query);
}


/**
 * Checks the skin-options option for the desired option.  Basically, if there's a skin option for it, it uses that, otherwise it falls back to the normal.
 *
 * @uses headway_get_option()
 * 
 * @param string $option Option to be queried.
 * 
 * @return mixed
 **/
function headway_get_skin_option($option, $exists = false){	
	global $headway_skin_options_cache;
	global $headway_active_skin;
	
	$active_skin = (!isset($_GET['headway-skin-preview'])) ? $headway_active_skin : $_GET['headway-skin-preview'];
	
	if(!isset($headway_skin_options_cache)){
		if(headway_is_skin_active() && !isset($_GET['headway-skin-preview'])){
			$headway_skin_options_cache = headway_get_option('skin-options', true, true);
		} elseif(isset($_GET['headway-skin-preview'])){
			global $preview_skin_options;
		
			$headway_skin_options_cache = $preview_skin_options;
		}	
	}
	
	$skin_options = &$headway_skin_options_cache;
			
	if($exists){		
		if(isset($skin_options[$option]) && $skin_options['skin'] == $active_skin){
			return true;
		} else {
			return false;
		}
	}
	
	if(isset($skin_options[$option]) && $skin_options['skin'] == $active_skin){
		if($skin_options[$option] === 'off')
			return false;
		else
			return $skin_options[$option];
	} else {
		return headway_get_option($option);
	}
}


/**
 * Returns disabled if the option is present in the skin options array.  To be used in conjunction with headway_disabled_input_name().
 * 
 * @see headway_disabled_input_name()
 * 
 * @param string $option
 * @param bool $print
 *
 * @return void|string
 **/
function headway_disabled_input($option, $print = false){			
	global $headway_active_skin;
			
	if(headway_get_skin_option($option, true)){
		if($print)
			echo ' disabled';
		else
			return ' disabled';
	}
}


/**
 * Changes the input name so nothing is saved.  To be used in conjunction with headway_disabled_input().
 *
 * @param string $options
 * @param bool $print
 * 
 * @return void|string
 **/
function headway_disabled_input_name($option, $print = false){
	global $headway_active_skin;
			
	if(headway_get_skin_option($option, true)){	
		if($print)
			echo '-inactive';
		else
			return '-inactive';
	}
}


/**
 * Clears Active Skin option if skin doesn't exist.
 **/
function headway_reset_active_skin(){	
	if(!headway_active_skin() && headway_get_option('active-skin') != '' && !isset($_GET['headway-skin-preview'])){
		headway_update_option('active-skin', '');
		headway_update_option('skin-options', '');
	}
}
add_action('wp', 'headway_reset_active_skin');


function headway_is_skin_active(){
	global $headway_active_skin;
	
	if(headway_get_option('active-skin') && $headway_active_skin)
		return true;
	else
		return false;
}


function headway_active_skin(){
	global $headway_active_skin;

	if(!isset($_GET['headway-skin-preview'])){
		return $headway_active_skin;
	} else {
		return ($_GET['headway-skin-preview'] != 'none') ? $_GET['headway-skin-preview'] : false;
	}
}


/**
 * Headway leafs API.
 *
 * @package Headway
 * @subpackage Leafs
 **/
class HeadwayLeaf {	
	var $id;
	var $name;
	var $options_callback;
	var $content_callback;
	var $show_hooks;
	var $icon;
	
	/**
	 * Constructor
	 * 
	 * @uses HeadwayLeaf::register()
	 **/
	function HeadwayLeaf($options){
		$this->register($options);
	}
	
	
	/**
	 * Registers the leaf (never would've thought with this kind of method name).  Adds actions and some other magic.
	 * 
	 * @param string $id Unique ID of the leaf.
	 * @param string $name Name/type of leaf.
	 * @param string $options_callback Function to be ran on the "inner" part of the leaf.  This is what shows up inside the leaf options window.
	 * @param int $options_width Defines how wide to make the leaf options panel.  Defualt: 350
 	 * @param string $content_callback Functions that displays the contents of the leaf.
	 * @param bool $show_hooks Determines whether or not to run the before/after leaf Headway hooks.
	 * @param string $icon Path to the leaf icon.  If no icon is present, the default text/html icon is shown instead.
	 * @param string $js_callback Function to be ran inside the Headway JS for the leaf.
	 *
	 * @uses HeadwayLeaf::button()
	 **/
	function register($options){		
		$defaults = array(
				'show_hooks' => true,
				'icon' => false,
				'live_saving' => true,
				'options_js_callback' => false,
				'js_callback' => false,
				'options_width' => false,
				'default_leaf' => false
			);
		
		$options = array_merge((array)$defaults, (array)$options);
		
		extract($options);
		
		$this->id = $id;
		$this->name = $name;
		$this->default_leaf = $default_leaf;
		$this->options_callback = $options_callback;
		$this->options_width = $options_width;
		$this->content_callback = $content_callback;
		$this->show_hooks = $show_hooks;
		$this->icon = $icon;
		$this->js_callback = $js_callback;
		$this->options_js_callback = $options_js_callback;
		$this->live_saving = $live_saving;
		
		if($this->js_callback){
			global $headway_custom_leaf_js;
						
			$headway_custom_leaf_js[$this->id] = true;
			
			add_filter('headway_custom_leaf_js_'.$this->id, array(&$this, 'js_callback'));
		}
		
		if($this->options_js_callback){
			add_action('headway_custom_leaf_options_js_'.$this->id, array(&$this, 'options_js_callback'));
		}
				
		add_action('headway_custom_leaf_'.$this->id.'_options', array(&$this, 'options'), 10, 2);
		add_action('headway_custom_leaf_'.$this->id.'_content', array(&$this, 'content'));
						
		$this->button();
	}
	
	
	/**
	 * Method that runs the content callback function.  Eventually passed through an action 
	 * 
	 * @param array $leaf
	 * 
	 * @see HeadwayLeaf::register()
	 **/
	function content($leaf){
		if($this->show_hooks){
			do_action('headway_before_leaf_content');
			do_action('headway_before_leaf_content_'.$leaf['id']);
		}
		
		if(function_exists($this->content_callback)){
			call_user_func($this->content_callback, $leaf);
 		}
		
		if($this->show_hooks){
			do_action('headway_after_leaf_content');
			do_action('headway_after_leaf_content_'.$leaf['id']);
		}
	}
	
	
	/**
	 * Method that runs the inner/options callback function.  Eventually passed through an action 
	 * 
	 * @param array $leaf
	 * 
	 * @see HeadwayLeaf::register()
	 **/
	function options($leaf_id, $get_width = false){
		if(!$get_width){
			$id = str_replace('leaf-', '', $leaf_id);
		
			$leaf = headway_get_leaf($id);
		
			if(!$leaf){
				$leaf['new'] = true;
			}
		
			$leaf['id'] = $id;
		
			echo '<h4 class="floaty-box-header">'.$this->name.'</h4>
			<div class="tabs">';
		
			call_user_func($this->options_callback, $leaf);
			
			if($this->live_saving && !isset($leaf['new'])){
				echo '<p><input type="submit" class="visual-editor-button headway-visual-editor-input headway-save-leaf-button" value="Save and View Leaf Settings" name="save-leaf-settings-'.$leaf['id'].'" id="save-leaf-settings-'.$leaf['id'].'" /></p>';
			} elseif($leaf['new'] && $this->live_saving){
				echo '<p class="info-box">You must save and reload the visual editor prior to using the save and view leaf settings feature on this leaf.</p>';
			}
		
			echo '</div>';
		} else {
			$width = $this->options_width;
			
			$width = ($width >= headway_get_skin_option('wrapper-width')) ? (int)headway_get_skin_option('wrapper-width')-((int)headway_get_skin_option('leaf-container-horizontal-padding')*2)-((int)headway_get_skin_option('leaf-margins')*2)-((int)headway_get_skin_option('leaf-padding')*2) : $width;
			
			echo str_replace('px', '', $width);
		}
	}


	/**
	 * Adds the leaf to the $custom_leafs global that displays all of the leaf buttons.
	 * 
	 * @global array $custom_leafs
	 **/
	function button(){
		if($this->default_leaf){
			global $default_leafs;
			
			$default_leafs[$this->id] = array('name' => $this->name, 'icon' => $this->icon);
		} else {
			global $custom_leafs;
			
			$custom_leafs[$this->id] = array('name' => $this->name, 'icon' => $this->icon);
		}
	}
	
	
	/**
	 * Function that adds JS to Headway JS file for the leaf.
	 **/
	function js_callback($leaf){				
		if(function_exists($this->js_callback)){
			return call_user_func($this->js_callback, $leaf);
 		}
	}
	
	
	/**
	 * Adds JS to options panel for leaf.
	 **/
	function options_js_callback($leaf_id){							
		if(function_exists($this->options_js_callback)){	
			$id = str_replace('leaf-', '', $leaf_id);
		
			$leaf = headway_get_leaf($id);
		
			if(!$leaf){
				$leaf['new'] = true;
			}
		
			$leaf['id'] = $id;
										
			call_user_func($this->options_js_callback, $leaf);
		 }
	}
}


/**
 * Leafs API helper class for creating HTML elements in the leaf options.
 *
 * @package Headway
 * @subpackage Leafs
 **/
class HeadwayLeafsHelper {
	
	
	/**
	 * Creates the <ul> holding the tab buttons.
	 * 
	 * @param array $tabs
	 * @param mixed $leaf_id
	 **/
	function create_tabs($tabs, $leaf_id){
		echo '<ul class="clearfix tabs">';
	
		foreach($tabs as $id => $name){
			echo '<li><a href="#'.$id.'-tab-'.$leaf_id.'">'.$name.'</a></li>';
		}
	    
		echo '</ul>';
	}
	
	
	/**
	 * Opens and names the div containing a tab.
	 * 
	 * @param string $tab ID of the tab.
	 * @param mixed $leaf_id
	 **/
	function open_tab($tab, $leaf_id){
		echo '<div id="'.$tab.'-tab-'.$leaf_id.'">
				<table>';
	}
	
	
	/**
	 * Simply closes the tab.
	 **/
	function close_tab(){
		echo '</table></div>';
	}
	
	
	/**
	 * Creates a text input element along with the table HTML and label.
	 * 
	 * @param array $options Associative array.  Needs a name, label, and value.
	 * @param mixed $leaf_id
	 **/
	function create_text_input($options, $leaf_id, $type = 'leaf-options'){
		
		$no_border = ($options['no-border']) ? ' class="no-border"' : null;
		
		echo '<tr'.$no_border.'>';				
		
		echo '<th scope="row"><label for="'.$leaf_id.'_'.str_replace('-', '_', $options['name']).'">'.$options['label'].'</label></th>
						<td><input type="text" class="headway-visual-editor-input" name="'.$type.'['.$leaf_id.']['.$options['name'].']" id="'.$leaf_id.'_'.str_replace('-', '_', $options['name']).'" value="'.stripslashes(htmlentities($options['value'])).'" /></td>';

		echo '</tr>';
		
	}
	
	
	/**
	 * Creates a textarea element along with the table HTML and label.
	 * 
	 * @param array $options Associative array.  Needs a name, label, and value.
	 * @param mixed $leaf_id
	 **/
	function create_textarea($options, $leaf_id, $type = 'leaf-options'){
		
		$no_border = ($options['no-border']) ? ' no-border' : null;
		
		echo '<tr class="no-border">
					<th scope="row" colspan="2"><label for="'.$leaf_id.'_'.str_replace('-', '_', $options['name']).'" style="text-align: left;">'.$options['label'].'</label></th>
				</tr>
				<tr class="textarea'.$no_border.'">
					<td colspan="2"><textarea class="text-content headway-visual-editor-input" name="'.$type.'['.$leaf_id.']['.$options['name'].']" id="'.$leaf_id.'_'.str_replace('-', '_', $options['name']).'">'.stripslashes(htmlentities($options['value'])).'</textarea></td>
			  </tr>';
	
	}
	
	
	/**
	 * Creates a checkbox input element along with the table HTML and label.
	 * 
	 * @param array $options Associative array.  Needs a name, left label (optional), checkbox label, and value.
	 * @param mixed $leaf_id
	 **/
	function create_checkbox($options, $leaf_id, $type = 'leaf-options'){
		
		$no_border = ($options['no-border']) ? ' class="no-border"' : null;
		
		echo '<tr'.$no_border.'>';				
		
		echo '<th scope="row"><label for="'.$leaf_id.'_'.str_replace('-', '_', $options['name']).'">'.$options['left-label'].'</label></th>	
				<td>
					<p class="radio-container">
						<input type="checkbox" class="radio headway-visual-editor-input" id="'.$leaf_id.'_'.str_replace('-', '_', $options['name']).'" name="'.$type.'['.$leaf_id.']['.$options['name'].']"'.headway_checkbox_value($options['value']).'/><label for="'.$leaf_id.'_'.str_replace('-', '_', $options['name']).'">'.$options['checkbox-label'].'</label>
					</p>
				</td>';

		echo '</tr>';
		
	}
	
	
	/**
	 * Creates a select element along with the table HTML and label.
	 * 
	 * @param array $options Associative array.  Needs a name, label, options (value and label), and selected value.
	 * @param mixed $leaf_id
	 **/
	function create_select($options, $leaf_id, $type = 'leaf-options'){
		
		$no_border = ($options['no-border']) ? ' class="no-border"' : null;
		
		echo '<tr'.$no_border.'>				
				<th scope="row">
					<label for="'.$leaf_id.'_'.str_replace('-', '_', $options['name']).'">'.$options['label'].'</label>
				</th>
				
				<td>
					<select id="'.$leaf_id.'_'.str_replace('-', '_', $options['name']).'" name="'.$type.'['.$leaf_id.']['.$options['name'].']" class="headway-visual-editor-input">';
			
					foreach($options['options'] as $value => $label){
						echo '<option value="'.$value.'"'.headway_option_value($options['value'], $value).'>'.$label.'</option>';
					}
	
			echo '</select>
				</td>
		
			  </tr>';
		
	}
	
	
	/**
	 * Uses the create_checkbox() method to simplify adding the show title checkbox.
	 *
	 * @uses HeadwayLeafsHelper::create_checkbox()
	 * 
	 * @param mixed $leaf_id
	 * @param mixed $value
	 * @param bool $no_border
	 * 
	 **/
	function create_show_title_checkbox($leaf_id, $value, $no_border = false){		
		$options = array(
				'name' => 'show-title',
				'left-label' => 'Leaf Title',
				'checkbox-label' => 'Show Title',
				'value' => $value,
				'no-border' => $no_border
			);
		
		HeadwayLeafsHelper::create_checkbox($options, $leaf_id, 'config');
	}
	
	
	/**
	 * Uses the create_text_input() method to simplify adding the CSS classes input.
	 *
	 * @uses HeadwayLeafsHelper::create_text_input()
	 * 
	 * @param mixed $leaf_id
	 * @param mixed $value
	 * @param bool $no_border
	 * 
	 **/
	function create_classes_input($leaf_id, $value, $no_border = false){
		$options = array(
				'name' => 'custom-css-classes',
				'label' => 'Custom CSS Class(es)',
				'value' => $value,
				'no-border' => $no_border
			);
		
		HeadwayLeafsHelper::create_text_input($options, $leaf_id, 'config');
	}
	
	
	/**
	 * Uses the create_text_input() method to simplify adding the leaf title link input.
	 *
	 * @uses HeadwayLeafsHelper::create_text_input()
	 * 
	 * @param mixed $leaf_id
	 * @param mixed $value
	 * @param bool $no_border
	 * 
	 **/
	function create_title_link_input($leaf_id, $value, $no_border = false){
		$options = array(
				'name' => 'leaf-title-link',
				'label' => 'Leaf Title Link',
				'value' => $value,
				'no-border' => $no_border
			);
		
		HeadwayLeafsHelper::create_text_input($options, $leaf_id, 'config');
	}
	
}


/**
 * Registers custom font families to be used in the visual editor.
 * 
 * param string $name Name of font to be displayed in visual editor.
 * param string $family Font family to be used.
 *
 * @return void
 **/
function headway_register_custom_font($name, $family){
	headway_register_font($name, $family, 'Custom');
}


function headway_add_custom_font($name, $family){
	_deprecated_function(__FUNCTION__, '0.0', 'headway_register_custom_font()');

	headway_register_custom_font($name, $family);
}


/**
 * Registers custom elements to the visual editor to be styled visually.
 * 
 * @param array $options Options for the element.  Needs a CSS selector ($selector), name ($name), array of options ($options), boolean for font options ($fonts), boolean for advanced font options ($fonts_advanced), and optional specific CSS selector for overrides ($specific_selector).  
 *
 * @return void
 **/
function headway_register_custom_element($options){
	global $headway_custom_elements;
	
	extract($options);
	
	$element_options = array(
		$selector,
		$name,
		$color_options,
		$fonts,
		$fonts_advanced,
		$specific_selector
	);
	
	if(!$headway_custom_elements) $headway_custom_elements = array();
	
	array_push($headway_custom_elements, $element_options);
}


function headway_add_custom_element($options){
	_deprecated_function(__FUNCTION__, '0.0', 'headway_register_custom_element()');

	headway_register_custom_element($options);
}