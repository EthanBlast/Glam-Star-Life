<?php
class HeadwayMetaBox
{
		
	var $id;
	var $name;
	var $options;
	var $defaults;
	var $info = NULL;
	var $type = 'both';


	function HeadwayMetaBox($args){
		extract($args);
		
		$this->id = $id;
		$this->name = $name;
	    $this->options = $options;
		$this->defaults = $defaults;
		if(isset($info)) $this->info = $info;
		if(isset($type)) $this->type = $type;
		$this->context = isset($context) ? $context : 'advanced';
		$this->priority = isset($priority) ? $priority : 'low';
		$this->hidden_on_publish = isset($hidden_on_publish) ? $hidden_on_publish : false;
		
		
		add_action('admin_init', array(&$this, 'BuildBox'));
		add_action('save_post', array(&$this, 'SaveData'), 10, 2);	
	}
	
	
	
	function BuildBox($post){				
		if($this->hidden_on_publish && isset($_GET['post'])){
			$post = get_post($_GET['post']);
			
			if($post && in_array($post->post_status, array('publish', 'future', 'private'))) return false;
		}
			
		if($this->id === 'seo' && $this->type !== 'page'){
			foreach((array)get_post_types(array('public' => true)) as $post_type){
				if(post_type_supports($post_type, 'headway-seo') || $post_type == 'post'){
					add_meta_box($this->id, $this->name, array(&$this, 'BoxContent'), $post_type, $this->context, $this->priority);
				}
			}
		} else {
			add_meta_box($this->id, $this->name, array(&$this, 'BoxContent'), $this->type, $this->context, $this->priority);
		}
		
		if($this->type == 'both'):
			add_meta_box($this->id, $this->name, array(&$this, 'BoxContent'), 'post', $this->context, $this->priority);
			add_meta_box($this->id, $this->name, array(&$this, 'BoxContent'), 'page', $this->context, $this->priority);
		endif;	
	}
	
	
	
	
	function BoxContent($post){				
		echo '<input type="hidden" name="'.$this->id.'_nonce" id="'.$this->id.'_nonce" value="' . wp_create_nonce( base64_encode(md5($this->id)) ) . '" />';


		foreach($this->options as $option):
		
			if(get_post_meta($post->ID, '_'.$option['id'], true) || get_post_meta($post->ID, '_'.$option['id'], true) == '0'):
				$value = get_post_meta($post->ID, '_'.$option['id'], true);
			else:
				$value = (isset($this->defaults[$option['id']])) ? $this->defaults[$option['id']] : false;
			endif;
		
			
			
			
			if($value == '1') $data[$option['id']] = ' checked="checked" ';
			elseif($value != '1') $data[$option['id']] = $value;
			else $data[$option['id']] = false;
		endforeach;


		if($this->info) echo '<p class="notice">'.$this->info.'</p>';
		
		
		if($this->id == 'seo' && get_option('page_on_front') != $post->ID && !class_exists('All_in_One_SEO_Pack')){
			$date = get_the_time('M j, Y') ? get_the_time('M j, Y') : mktime('M j, Y');
?>
	<h4 id="seo-preview-title">Search Engine Result Preview</h4>
	<div id="seo-preview">
		<h4 title="Click To Edit"><?php echo get_bloginfo('name'); ?></h4>
		<p id="seo-preview-description" title="Click To Edit"><?php if($this->type == 'post'){ echo $date. ' ... '; } ?><span id="text"></span></p>
		<p id="seo-preview-bottom"><span id="seo-preview-url"><?php echo str_replace('http://', '', get_bloginfo('url')) ?></span> - <span>Cached</span> - <span>Similar</span></p>
	</div>
	<small id="seo-preview-disclaimer">Remember, this is only a predicted search engine result preview.  There is no guarantee that it will look exactly this way.  However, it will look similar.</small>
<?php
		}
		
		
		foreach($this->options as $key => $value):
		
			$input_options['id'] = $value['id'];
			$input_options['name'] = $value['name'];
			$input_options['type'] = $value['type'];
			$input_options['defaults'] = (isset($value['defaults'])) ? $value['defaults'] : false;
			$input_options['options'] = (isset($value['options'])) ? $value['options'] : false;
			$input_options['description'] = (isset($value['description'])) ? $value['description'] : false;

			
			$id = $input_options['id'];
						
				if(!isset($options)) $options = '';
				
			
				if($input_options['type'] == 'text'){

					$id = $this->id.'_'.$input_options['id'];
					
					$options .= '<tr class="label"><th valign="top" scope="row"><label for="'.$id.'">'.$input_options['name'].'</label></th></tr>
								 <tr><td><input type="text" style="width: 95%;" value="'.$data[$input_options['id']].'" size="50" id="'.$id.'" name="'.$this->id.'['.$input_options['id'].']"/></td></tr>';
								
					if($id == 'seo_title'){
						$title_post = str_replace('%tagline%', get_bloginfo('tagline'), str_replace('%blogname%', get_bloginfo('name'), headway_get_option('title-single')));
						$title_page = str_replace('%tagline%', get_bloginfo('tagline'), str_replace('%blogname%', get_bloginfo('name'), headway_get_option('title-page')));
												
						if($this->type == 'post'){
							$options .= '<input type="hidden" name="" id="post-title-setup" value="'.$title_post.'" />';
						} elseif($this->type == 'page'){
							$options .= '<input type="hidden" name="" id="page-title-setup" value="'.$title_page.'" />';							
						}
						
						$options .= '<tr><td style="padding-top: 0;"><p class="character-count-container" style="color:#777;margin-top: 0;">
							<input type="text" disabled value="" style="width: 35px;text-align:right;" size="3" id="'.$id.'-character-count">&nbsp;<span style="font-style: italic;">characters.</span>  Most search engines will only recognize up to <strong style="color: #444;">60</strong> characters for the title.
						</p></td></tr>';
					}
								
					if($input_options['description']){ $options .= '<tr class="description"><td><p>'.$input_options['description'].'</p></td></tr>'; }

				} elseif($input_options['type'] == 'textarea') {
					
					$id = $this->id.'_'.$input_options['id'];
					
					$options .= '<tr class="label"><th valign="top" scope="row"><label for="'.$id.'">'.$input_options['name'].'</label></th></tr>
								<tr><td><textarea style="width: 95%;" rows="6" cols="50" id="'.$id.'" name="'.$this->id.'['.$input_options['id'].']">'.$data[$input_options['id']].'</textarea></td></tr>';
								
					if($id == 'seo_description'){
						$options .= '<tr><td style="padding-top: 0;"><p class="character-count-container" style="color:#777;margin-top: 0;">
							<input type="text" disabled value="" style="width: 35px;text-align:right;" size="3" id="'.$id.'-character-count">&nbsp;<span style="font-style: italic;">characters.</span>  Most search engines will only recognize up to <strong style="color: #444;">150</strong> characters for the description.
						</p></td></tr>';
					}
								
					if($input_options['description']) $options .= '<tr class="description"><td><p>'.$input_options['description'].'</p></td></tr>';
				
				
				} elseif($input_options['type'] == 'checkbox') {
					$value = (isset($normal_data[$id]) && $normal_data[$id] == 1) ? 1 : 0;
					
					$options .= '<input type="hidden" name="'.$this->id.'['.$input_options['id'].'_unchecked]" value="0" /> ';
					$options .= '<tr><td colspan="2"><label class="selectit" for="'.$this->id.'_'.$input_options['id'].'"> <input type="checkbox" id="'.$this->id.'_'.$input_options['id'].'" value="1" name="'.$this->id.'['.$input_options['id'].']" class="check" '.$data[$id].'/> '.$input_options['name'].'</label></td></tr>';
					
					if($input_options['description']) $options .= '<tr class="description"><td><p>'.$input_options['description'].'</p></td></tr>';
				
					
				} elseif($input_options['type'] == 'show-navigation') {
					
					if(in_array($post->ID, array_values((array)headway_get_option('excluded_pages')))){
						$data[$id] = 'hide';
					} else {
						$data[$id] = 'show';
					}

					$options .= '<tr><td colspan="2">
					
										<input type="radio" id="'.$this->id.'_'.$input_options['id'].'_show" value="show" name="'.$this->id.'['.$input_options['id'].']" class="check" '.headway_radio_value($data[$id], 'show').'/> 
											<label class="selectit" for="'.$this->id.'_'.$input_options['id'].'_show"> 
												Show In Navigation
											</label>
										
										<br />
										
										<input type="radio" id="'.$this->id.'_'.$input_options['id'].'_hide" value="hide" name="'.$this->id.'['.$input_options['id'].']" class="check" '.headway_radio_value($data[$id], 'hide').'/> 
											<label class="selectit" for="'.$this->id.'_'.$input_options['id'].'_hide"> 
												Hide From Navigation
											</label>
											
								</td></tr>';
								
					if($input_options['description']) $options .= '<tr class="description"><td><p>'.$input_options['description'].'</p></td></tr>';
					
								
				
				} elseif($input_options['type'] == 'radio') {


					$options .= '<tr><td colspan="2">';

					$count = 1;
					
					$possible_options = array_values($input_options['options']);
					
					foreach($input_options['options'] as $label => $value) {
						if($count == 1 && !(in_array($data[$id], $possible_options))) $checked[$count] = 'checked="checked" ';
						
						$options .= '<input type="radio" id="'.$this->id.'_'.$input_options['id'].'_'.$value.'" value="'.$value.'" name="'.$this->id.'['.$input_options['id'].']" class="check" '.headway_radio_value($data[$id], $value).$checked[$count].'/> 
							<label class="selectit" for="'.$this->id.'_'.$input_options['id'].'_'.$value.'"> 
								'.$label.'
							</label>

						<br />';
						
						$count++;
					}
								

					$options .= '			</td></tr>';
					
					if($input_options['description']) $options .= '<tr class="description"><td><p>'.$input_options['description'].'</p></td></tr>';
				

				
				
				} elseif($input_options['type'] == 'page-select') {
				
					
					$options .= '<tr class="label"><th valign="top" scope="row"><label for="'.$this->id.'_'.$input_options['id'].'">'.$input_options['name'].'</label></th></tr>
								 <tr><td>'.wp_dropdown_pages(array('selected' => $data[$id], 'name' => $this->id.'['.$input_options['id'].']', 'show_option_none' => '   ', 'sort_column'=> 'menu_order, post_title', 'echo' => false)).'</td></tr>';
								
					if($input_options['description']) $options .= '<tr class="description"><td><p>'.$input_options['description'].'</p></td></tr>';
					
				
				
				} elseif($input_options['type'] == 'template-select') {
					$templates = headway_get_option('leaf-templates');
					
					$options .= '<tr class="label"><th valign="top" scope="row"><label for="'.$this->id.'_'.$input_options['id'].'">'.$input_options['name'].'</label></th></tr>
								 <tr><td>';
						
						
					if(is_array($templates) && count($templates) > 0){
						$options .= '<select id="'.$input_options['id'].'" name="'.$this->id.'['.$input_options['id'].']">';
						
						if(!headway_get_option('default-leafs-template'))
							$options .= '<option value="">System Default</option>';
						
								
						foreach($templates as $template => $template_options){
							$default = headway_get_option('default-leafs-template');
						
							$selected = ($default['id'] == $template_options['id']) ? ' selected="selected"' : false;
							$default_text = ($default['id'] == $template_options['id']) ? ' (Default)' : false;
						
							$options .= '<option value="'.$template_options['id'].'---'.$template_options['name'].'"'.$selected.'>'.preg_replace('/%u0*([0-9a-fA-F]{1,5})/', '&#x\1;', $template_options['name']).$default_text.'</option>';
						}
						
						$options .= '</select>';
					} else {
						$options .= '<p>There are no leaf templates.  Add some using the visual editor!';
					}
										
					$options .= '</td></tr>';		
									
								
					if($input_options['description'] && is_array($templates) && count($templates) > 0) $options .= '<tr class="description"><td><p>'.$input_options['description'].'</p></td></tr>';
					
				
				
				} elseif($input_options['type'] == 'system-page-select') {
				
					$current_system_page_link[$data[$id]] = ' selected';
					
					$options .= '<tr class="label"><th valign="top" scope="row"><label for="'.$this->id.'_'.$input_options['id'].'">'.$input_options['name'].'</label></th></tr>
								 <tr><td>
									<select name="'.$this->id.'['.$input_options['id'].']" id="'.$this->id.'_'.$input_options['id'].'"">
										<option value="DELETE"></option>
										<option value="index"'.$current_system_page_link['index'].'>Blog Index</option>
										<option value="single"'.$current_system_page_link['single'].'>Single Post</option>
										<option value="category"'.$current_system_page_link['category'].'>Category Archive</option>
										<option value="archives"'.$current_system_page_link['archives'].'>Archives</option>
										<option value="tag"'.$current_system_page_link['tag'].'>Tag Archive</option>
										<option value="author"'.$current_system_page_link['author'].'>Author Archive</option>
										<option value="search"'.$current_system_page_link['search'].'>Search</option>
										<option value="four04"'.$current_system_page_link['four04'].'>404 Page</option>
									</select>
								</td></tr>';
								
					if($input_options['description']) $options .= '<tr class="description"><td><p>'.$input_options['description'].'</p></td></tr>';
				
				 
				
				} elseif($input_options['type'] == 'nav-menu' && function_exists('wp_get_nav_menus')) {
					$options .= '<tr class="label"><th valign="top" scope="row"><label for="'.$this->id.'_'.$input_options['id'].'">'.$input_options['name'].'</label></th></tr>
								 <tr><td>
									<select name="'.$this->id.'['.$input_options['id'].']" id="'.$this->id.'_'.$input_options['id'].'"">
										<option value="">Default Menu</option>';
									
									
						$menus = wp_get_nav_menus();
						foreach ( $menus as $menu ) {							
							if ( wp_get_nav_menu_items($menu->term_id) ) {
								$nav_menu_selected = ($data[$id] == $menu->slug) ? ' selected' : null;

								$options .= '<option value="'.$menu->slug.'"'.$nav_menu_selected.'>'.$menu->name.'</option>';
							}
						}			
									
					$options .=	'</select></td></tr>';
								
					if($input_options['description']) $options .= '<tr class="description"><td><p>'.$input_options['description'].'</p></td></tr>';
				}
			
			
						

		endforeach;
		

		  echo '<table cellspacing="2" cellpadding="5" style="width: 100%;" class="form-table">
				'.$options.'
		  		</table>';
		
		
	}
	
	
	
	function SaveData($post_ID, $post){
		if($post->post_type == 'revision' || $post->post_type != $this->type) return;
		
		$post_ID = $post->ID;
		
		$encrypt = base64_encode(md5($this->id));
		if ( !wp_verify_nonce( isset($_POST[$this->id.'_nonce']), $encrypt )) {
		  return;
		}
				
		if ( 'page' == $_POST['post_type'] ) {
		  if ( !current_user_can( 'edit_page', $post_ID ))
		    return $post_ID;
		} else {
		  if ( !current_user_can( 'edit_post', $post_ID ))
		    return $post_ID;
		}

		if(!is_array($_POST[$this->id])) return false;
		
		foreach($_POST[$this->id] as $key => $value){		
			if($value == '' || $value == '0'){
			
				if(strpos($key, '_unchecked')){
					$key = str_replace('_unchecked', '', $key);
					if(!$_POST[$this->id][$key]) update_post_meta($post_ID, '_'.$key, '0');

				}else{
					delete_post_meta($post_ID, '_'.$key);
				}


			} elseif($value != get_post_meta($post_ID, '_'.$key, true)) {
			
				update_post_meta($post_ID, '_'.$key, $value); 
			
				if($key == 'show_navigation'){
						
					if($value == 'show'){
					
						$excluded_pages = (is_array(headway_get_option('excluded_pages'))) ? headway_get_option('excluded_pages') : array();
											
						if($excluded_pages){
							foreach($excluded_pages as $key => $page){
								if($page == $post_ID) unset($excluded_pages[$key]);
							}
						}

						global $wpdb;
						$headway_options_table = $wpdb->prefix.'headway_options';
											
						$excluded_pages = serialize($excluded_pages);
											
						$wpdb->query("UPDATE $headway_options_table SET `value`='$excluded_pages' WHERE `option`='excluded_pages'");
											
					} else {
					
						$excluded_pages = (is_array(headway_get_option('excluded_pages'))) ? headway_get_option('excluded_pages') : array();
												 		
											
						if(!in_array($post_ID, $excluded_pages)) array_push($excluded_pages, $post_ID);
					
			
						global $wpdb;
						$headway_options_table = $wpdb->prefix.'headway_options';
					
						$excluded_pages = serialize($excluded_pages);
											
						$wpdb->query("UPDATE $headway_options_table SET `value`='$excluded_pages' WHERE `option`='excluded_pages'");
					
					}

									
				}

			}
			elseif(!get_post_meta($post_ID, '_'.$key, true) && $value != NULL){
				add_post_meta($post_ID, '_'.$key, $value); 
			}
		}
	}
}