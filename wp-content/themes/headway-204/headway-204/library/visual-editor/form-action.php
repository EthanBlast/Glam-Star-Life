<?php
if(!headway_can_visually_edit()) die('You have insufficient privileges to use the visual editor.');

check_ajax_referer('headway-visual-editor-nonce', 'headway-ve-nonce');

if($_POST['encoded']){
	//Work-around for mod_security quirks
	
	$serialized = base64_decode($_POST['encoded']);		
	parse_str($serialized, $_POST);
}

global $wpdb;

$navigation = $_POST['nav_order'];
$do_not_change = array();

if(isset($_POST['nav_order'])){
	foreach($navigation as $position => $items){
	
		if($position == 'main' || $position == 'inactive'){
			if($items != 'unserialized') {
				$items = explode('|', $items);
		
				if($position == 'inactive'){
					headway_update_option('excluded_pages', $items);
				} else {
					foreach($items as $item){
						if(!in_array($item, $do_not_change)){
							$position = array_search($item, $items);
				
							$nav_update[$item]  = "UPDATE $wpdb->posts SET post_parent='0', menu_order='$position' WHERE ID='$item'";						
									
							$wpdb->query($nav_update[$item]);
						}
					}
				}	
			}	
		} elseif($position == 'child') {
			foreach($items as $parent => $children){
				if($children != 'unserialized' && $children) {
			
					$children = explode('|', $children);		
					$parent = str_replace('page-', '', $parent);
			
					foreach($children as $child){
						$position = array_search($child, $children);
						$position = $position+1;
					
						array_push($do_not_change, $child);

						$nav_child_update[$child]  = "UPDATE $wpdb->posts SET post_parent='$parent', menu_order='$position' WHERE ID='$child'";
										
						$wpdb->query($nav_child_update[$child]);
					}
			
				}
		
			}
		}
	}
}


if(isset($_POST['color'])){
	foreach($_POST['color'] as $element => $properties){
		foreach($properties as $property => $value){
			headway_queue_element_style($element, 'color', $property, $value);
		}
	}
}


if(isset($_POST['fonts'])){	
	foreach($_POST['fonts'] as $element => $properties){
		foreach($properties as $property => $value){
			headway_queue_element_style($element, 'font', $property, $value);
		}
	}
}


if(isset($_POST['width'])){	
	foreach($_POST['width'] as $element => $properties){
		foreach($properties as $property => $value){
			$value = ($value == '0') ? 'zero' : $value;
			headway_queue_element_style($element, 'sizing', $property, $value);
		}
	}
}


if(isset($_POST['delete'])){
	foreach($_POST['delete'] as $leaf => $delete){
		if($delete){
			headway_delete_leaf(str_replace('leaf-', '', $leaf));
		}
	}
}


if(isset($_POST['layout-order']) && is_array($_POST['layout-order']) || $_POST['layout-order'] != 'unserialized'){
	if(is_array($_POST['layout-order'])){
		foreach($_POST['layout-order'] as $container => $leafs){
			$layout_order[$container] = explode('|', str_replace('&', '|', str_replace('leaf[]=', '', $leafs)));
		}
				
		foreach($layout_order as $container => $leafs){
			$leaf_position = 1;
						
			foreach($leafs as $leaf){
				if($leaf){
					headway_update_leaf($leaf, array('position' => $leaf_position, 'container' => $container));
					$leaf_position++;
				}
			}
		}
	} else {
		$layout_order = explode('|', str_replace('&', '|', str_replace('leaf[]=', '', $_POST['layout-order'])));
	
		$leaf_position = 1;
	
		foreach($layout_order as $leaf){
			headway_update_leaf($leaf, array('position' => $leaf_position));
			$leaf_position++;
		}
	}
}


if(isset($_POST['column-order']) && $_POST['column-order'] != 'unserialized'){	
	$new_columns_order = explode('&', str_replace('column-', '', preg_replace("/(-)(page)(\\[\\])(=)((?:[a-z][a-z0-9_]*))/is", '', $_POST['column-order'])));
		
	foreach($new_columns_order as $new_position => $current_column){
		$new_column = $new_position + 1;
		
		headway_update_page_option($_POST['current-page'], 'column-'.$new_column.'-width', $_POST['page-config']['column-'.$current_column.'-width']);
														
		foreach(headway_get_page_leafs($_POST['current-page']) as $leaf){
			if($leaf['container'] == '0' || $leaf['container'] === 'main') $leaf['container'] = 1;
			
			if($leaf['container'] == $current_column){
				headway_update_leaf($leaf['id'], array('container' => $new_column));
			}
		}
	}
}


function headway_options_base64_encode(&$value, $option){
	if(strpos($option, 'base64') !== false){
		$value = base64_encode($value);
	} else {
		$value = $value;
	}
}


if(isset($_POST['add'])){	
	$layout_order = array();
	
	if(is_array($_POST['layout-order'])){
		foreach($_POST['layout-order'] as $container => $leafs){
			$layout_order[$container] = explode('|', str_replace('&', '|', str_replace('leaf[]=', '', $leafs)));
		}
	} else {
		$layout_order['main'] = explode('|', str_replace('&', '|', str_replace('leaf[]=', '', $_POST['layout-order'])));
	}
	
	foreach($_POST['add'] as $leaf => $class){		
		$leaf_id = str_replace('leaf-', '', $leaf);
		
		$container = array();
		
		foreach($layout_order as $leaf_container => $leafs){
			if(in_array($leaf_id, $leafs)){
				$container[$leaf_id] = $leaf_container;
			}
		}
		
		$position[$leaf_id] = array_search($leaf_id, $layout_order[$container[$leaf_id]]);
				
		$options[$leaf_id] = $_POST['leaf-options'][$leaf_id];
		$config[$leaf_id] = $_POST['config'][$leaf_id];
		
		array_walk_recursive($options[$leaf_id], 'headway_options_base64_encode');
			
		if($options[$leaf_id]['text-content']) $options[$leaf_id]['text-content'] = base64_encode($options[$leaf_id]['text-content']);
		if($options[$leaf_id]['blurb']) $options[$leaf_id]['blurb'] = base64_encode($options[$leaf_id]['blurb']);
				
		if(!is_array(headway_get_leaf($leaf_id))){
			headway_add_leaf($_POST['current-page'], 
				array(
					'position' => $position[$leaf_id]+1,
					'type' => $class,
					'config' => array(
						'title' => base64_encode($_POST['title'][$leaf_id]),
						'show-title' => $config[$leaf_id]['show-title'],
						'title-link' => $config[$leaf_id]['leaf-title-link'],
						'width' => $_POST['dimensions'][$leaf]['width'],
						'height' => $_POST['dimensions'][$leaf]['height'],
						'fluid-height' => true,
						'align' => 'left',
						'custom-classes' => $config[$leaf_id]['custom-css-classes']
					),
					'options' => $options[$leaf_id],
					'container' => $container[$leaf_id],
					'id' => $leaf_id
				)
			);
		}

	}
}


if(is_array($_POST['title'])){		
	foreach($_POST['title'] as $leaf_id => $title){		
		$config[$leaf_id] = headway_get_leaf($leaf_id);
		$config[$leaf_id] = $config[$leaf_id]['config'];
	
		$config[$leaf_id]['title'] = base64_encode($title);
							
		headway_update_leaf($leaf_id, array('config' => $config[$leaf_id]));
	}
}


if(isset($_POST['dimensions'])){	
	foreach($_POST['dimensions'] as $leaf => $dimensions){	
		$leaf_id = str_replace('leaf-', '', $leaf);
		
		$config[$leaf_id] = headway_get_leaf($leaf_id);
		$config[$leaf_id] = $config[$leaf_id]['config'];
				
		$config[$leaf_id]['width'] = ($dimensions['width'] != 'undefined') ? $dimensions['width'] : $config[$leaf_id]['width'];
		$config[$leaf_id]['height'] = ($_POST['dimensions'][$leaf]['height-changed'] == 'true' && $dimensions['height'] != 'undefined') ? $dimensions['height'] : $config[$leaf_id]['height'];

		headway_update_leaf($leaf_id, array('config' => $config[$leaf_id]));
	}
}


if(isset($_POST['leaf-options'])){	
	foreach($_POST['leaf-options'] as $leaf => $options){
		array_walk_recursive($options, 'headway_options_base64_encode');
			
		if($options['text-content']) $options['text-content'] = base64_encode($options['text-content']);
		if($options['blurb']) $options['blurb'] = base64_encode($options['blurb']);
				
		headway_update_leaf($leaf, array('options' => $options));
	}
}


if(isset($_POST['leaf-switches'])){	
	foreach($_POST['leaf-switches'] as $leaf => $options){
		$leaf = str_replace('leaf-', '', $leaf);
	
		$config[$leaf] = headway_get_leaf($leaf);
		$config[$leaf] = $config[$leaf]['config'];
			
		$config[$leaf]['align'] = $options['align'];
		$config[$leaf]['clear'] = $options['clear'];
		$config[$leaf]['fluid-height'] = ($options['fluid-height'] == 'true') ? true : false;
		
		headway_update_leaf($leaf, array('config' => $config[$leaf]));
	}
}


if(isset($_POST['config'])){		
	foreach($_POST['config'] as $leaf => $config_post){
		$config[$leaf] = headway_get_leaf($leaf);
		$config[$leaf] = $config[$leaf]['config'];
		
		if($_POST['title'][$leaf]) $config[$leaf]['title'] = base64_encode($_POST['title'][$leaf]);
		
		if($_POST['dimensions']['leaf-'+$leaf]['width']) $config[$leaf]['width'] = $_POST['dimensions']['leaf-'+$leaf]['width'];
		if($_POST['dimensions']['leaf-'+$leaf]['height'] && $_POST['dimensions']['leaf-'+$leaf]['height-changed'] == 'true') $config[$leaf]['height'] = $_POST['dimensions']['leaf-'+$leaf]['height'];
		
		$config[$leaf]['show-title'] = $config_post['show-title'];
		$config[$leaf]['title-link'] = $config_post['leaf-title-link'];
		$config[$leaf]['custom-classes'] = $config_post['custom-css-classes'];

		headway_update_leaf($leaf, array('config' => $config[$leaf]));
	}
}


if(isset($_POST['headway-config'])){
	foreach($_POST['headway-config'] as $key => $value){
		$value = ($value === false && $key != 'header-image-url' && $key != 'body-background-image-url') ? 'DELETE' : $value;
		
		if($key == 'header-image' && $_POST['headway-config']['header-image'] == headway_get_option('header-image')){
			continue;
		} elseif($key == 'header-image-url' && $value && $value != headway_get_option('header-image')){
			$key = 'header-image';
		}
		
		
		if($key == 'body-background-image' && $_POST['headway-config']['body-background-image'] == headway_get_option('body-background-image')){
			continue;
		} elseif($key == 'body-background-image-url' && $value && $value != headway_get_option('body-background-image')){
			$key = 'body-background-image';
		}				
				
		headway_update_option($key, $value);
	}
}


if(isset($_POST['page-config'])){	
	$exceptions = array('hide_header', 'hide_breadcrumbs', 'hide_navigation', 'hide_footer');
	
	foreach($_POST['page-config'] as $key => $value){
		if($_POST['column-order'] != 'unserialized' && strpos($key, '-width') !== false && strpos($key, 'column') !== false) continue;
		
		if(!in_array($key, $exceptions)){
			headway_update_page_option($_POST['current-page'], $key, $value);
		} else {
			update_post_meta($_POST['current-page'], '_'.$key, $value);
		}
	}
}


if(isset($_POST['header-order']) && $_POST['header-order'] != 'unserialized'){
	$order = explode('|', str_replace('-container', '', str_replace('headerOrder[]=', '', str_replace('&headerOrder[]=', '|', $_POST['header-order']))));

	headway_update_option('header-order', $order);
}


if(is_array($_POST['nav-item'])){
	foreach($_POST['nav-item'] as $page => $options){
		
		$page = str_replace('page-', '', str_replace('page-item-', '', $page));
		
		foreach($options as $option => $value){
			if($option == 'name'){
				$page_name_query[$page] = "UPDATE $wpdb->posts SET post_title='".$value."' WHERE ID='$page'";
				
				$wpdb->query($page_name_query[$page]);
			}
			
			if($option == 'forward-url'){
				update_post_meta($page, '_navigation_url', $value);
			}
			
			if($option == 'category'){
				update_post_meta($page, '_headway_category_forward', $value);
			}
		}
	}

}


if(isset($_POST['link-pages'])){
	if($_POST['link-pages']['pages']){
		foreach($_POST['link-pages']['pages'] as $pages){
			if($_POST['is-system-page'] == 'true'){
				update_post_meta($pages, '_leaf_system_template', $_POST['current-real-page']);
				delete_post_meta($pages, '_leaf_template');
			} else {
				update_post_meta($pages, '_leaf_template', $_POST['current-real-page']);
				delete_post_meta($pages, '_leaf_system_template');
			}
		}
	}
	if($_POST['link-pages']['system-pages']){
		foreach($_POST['link-pages']['system-pages'] as $system_page){
			if($_POST['is-system-page'] == 'true'){
				headway_update_option('leaf-template-system-page-'.$system_page, $_POST['current-real-page']);
				headway_delete_option('leaf-template-page-'.$system_page);
			} else {
				headway_update_option('leaf-template-page-'.$system_page, $_POST['current-real-page']);
				headway_delete_option('leaf-template-system-page-'.$system_page);
			}
		}
	}
}
elseif(isset($_POST['leafs-link-page']) || isset($_POST['leafs-link-system-page'])){
	
	if($_POST['leafs-link-page'] != 'DELETE'){
		if($_POST['is-system-page'] == 'true'){
			headway_update_option('leaf-template-page-'.$_POST['current-real-page'], $_POST['leafs-link-page']);
			headway_delete_option('leaf-template-system-page-'.$_POST['current-real-page']);
		} else {
			update_post_meta($_POST['current-real-page'], '_leaf_template', $_POST['leafs-link-page']);
			delete_post_meta($_POST['current-real-page'], '_leaf_system_template');
		}
	} elseif($_POST['leafs-link-page'] == 'DELETE') {
		if($_POST['is-system-page'] == 'false'){
			if($_POST['leafs-link-system-page'] == 'DELETE') delete_post_meta($_POST['current-real-page'], '_leaf_system_template');
			delete_post_meta($_POST['current-real-page'], '_leaf_template');
		} else {
			headway_delete_option('leaf-template-page-'.$_POST['current-real-page']);
		}
	}
	
	if($_POST['leafs-link-system-page'] != 'DELETE'){
		if($_POST['is-system-page'] == 'true'){
			headway_update_option('leaf-template-system-page-'.$_POST['current-real-page'], $_POST['leafs-link-system-page']);
			headway_delete_option('leaf-template-page-'.$_POST['current-real-page']);
		} else {
			update_post_meta($_POST['current-real-page'], '_leaf_system_template', $_POST['leafs-link-system-page']);
			delete_post_meta($_POST['current-real-page'], '_leaf_template');
		}
	} elseif($_POST['leafs-link-system-page'] == 'DELETE') {
		if($_POST['is-system-page'] == 'false'){
			if($_POST['leafs-link-page'] == 'DELETE') delete_post_meta($_POST['current-real-page'], '_leaf_system_template');
		} else {
			headway_delete_option('leaf-template-system-page-'.$_POST['current-real-page']);
		}
	}
	
	
	if($_POST['leafs-link-page'] == 'DELETE' && $_POST['leafs-link-system-page'] == 'DELETE'){
		headway_delete_option('leaf-template-system-page-'.$_POST['current-real-page']);
		headway_delete_option('leaf-template-page-'.$_POST['current-real-page']);
				
		delete_post_meta($_POST['current-real-page'], '_leaf_template');
		delete_post_meta($_POST['current-real-page'], '_leaf_system_template');
	}

}

if(isset($_POST['set-default-leafs'])){
	$leafs = headway_get_page_leafs($_POST['current-real-page']);
	
	global $wpdb;
	$headway_leafs_table = $wpdb->prefix.'headway_leafs';
	
	$wpdb->query("DELETE FROM $headway_leafs_table WHERE page='leaf-template'");
	
	if(count($leafs) > 0){
		foreach($leafs as $leaf){
			$leaf = array_map('maybe_unserialize', $leaf);			
						
			$leaf_config = $leaf['config'];
			$leaf_options = $leaf['options'];
			
			if($leaf['type'] == 'sidebar' && !$leaf_options['duplicate-id']){
				$leaf_options['duplicate-id'] = $leaf['id'];
			}
						
			$position = $leaf['position'];
			
			$type = isset($leaf['type']) ? $leaf['type'] : $leaf['config']['type'];
			
			headway_add_leaf('leaf-template', 
				array(
					'position' => $position,
					'type' => $type,
					'config' => $leaf_config,
					'options' => $leaf_options
				)
			);
			
		}
	}
	
	headway_update_option('leaf-template-exists', 'true');
}

if(isset($_POST['reset-leafs'])){
	headway_build_default_leafs($_POST['current-real-page'], true);
	
	headway_clear_cache();
	
	headway_update_option('cleared-cache', 'true');
}

if(isset($_POST['headway-config']) || isset($_POST['page-config'])){
	headway_clear_cache();
	
	headway_update_option('css-last-updated', mktime()+1);
	
	headway_update_option('cleared-cache', 'true');
}

if(isset($_POST['wizard']['ran-wizard']) && !isset($_POST['wizard']['skip-layout'])){		
	switch($_POST['wizard']['layout']){
		case 'content-sidebar':
			headway_wizard_layout_builder($_POST['wizard']['use-columns-system'], 2, 1, 2);
		break;
		
		case 'sidebar-content':
			headway_wizard_layout_builder($_POST['wizard']['use-columns-system'], 2, 2, 1);
		break;
		
		case 'content-sidebar-sidebar':
			headway_wizard_layout_builder($_POST['wizard']['use-columns-system'], 3, 1, 2, 3);
		break;
		
		case 'sidebar-content-sidebar':
			headway_wizard_layout_builder($_POST['wizard']['use-columns-system'], 3, 2, 1, 3);
		break;
		
		case 'content':
			headway_wizard_layout_builder($_POST['wizard']['use-columns-system'], 1, 1);
		break;
	}
	
	headway_update_option('ran-wizard', true);
}


$truncate = (isset($_GET['headway-ve-truncate-elements']) && $_GET['headway-ve-truncate-elements'] === 'true') ? true : false;
headway_run_element_style_queue($truncate);

unset($_POST);