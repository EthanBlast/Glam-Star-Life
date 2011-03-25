<?php
function headway_add_leaf($page, $args){
	global $wpdb;
	$headway_leafs_table = $wpdb->prefix.'headway_leafs';
	
	if(is_array($args)){
		extract($args);
	} else {
		return false;
	}
	
	if(is_array($config)) $config = serialize($config);
	if(is_array($options)) $options = serialize($options);
			
	if(!isset($container) || !$container) $container = 'main';	
			
	$insert_data = array( 'page' => $page, 'type' => $type, 'position' => $position, 'config' => $config, 'options' => $options, 'container' => $container );	
		
	if(isset($id)) $insert_data['id'] = $id;
			
	$wpdb->insert($headway_leafs_table, $insert_data);
						
	return $wpdb->insert_id;
}


function headway_update_leaf($leaf, $args){	
	global $wpdb;
	$headway_leafs_table = $wpdb->prefix.'headway_leafs';
	
	if(is_array($args)){
		extract($args);
	} else {
		return false;
	}
	
	if(is_array($config)) $config = serialize($config);
	if(is_array($options)) $options = serialize($options);
		
	if(isset($config)) $data['config'] = $config;
	if(isset($options)) $data['options'] = $options;
	if(isset($position)) $data['position'] = $position;
	if(isset($container)) $data['container'] = $container;
	if(isset($type)) $data['type'] = $type;	
	
	return $wpdb->update($headway_leafs_table, $data, array('id' => $leaf));
}


function headway_delete_leaf($leaf){
	global $wpdb;
	$headway_leafs_table = $wpdb->prefix.'headway_leafs';
	
	return $wpdb->query("DELETE FROM $headway_leafs_table WHERE id='$leaf'");
}


function headway_get_leaf($leaf){ 
	global $wpdb;
	$headway_leafs_table = $wpdb->prefix.'headway_leafs';
	
	$leaf = str_replace('leaf-', '', $leaf);
	$result = $wpdb->get_row("SELECT * FROM $headway_leafs_table WHERE id='$leaf'", ARRAY_A);
					
	return (is_array($result)) ? array_map('maybe_unserialize', $result) : (($result === NULL) ? false : $result);
}


function headway_get_page_leafs($page, $container = false){
	global $wpdb;
	$headway_leafs_table = $wpdb->prefix.'headway_leafs';
			
	if($container){
				
		if(wp_cache_get('leafs_page_'.$page.'_container_'.$container, 'headway')){
			return wp_cache_get('leafs_page_'.$page.'_container_'.$container, 'headway');
		} else {			
			$leafs = headway_get_page_leafs($page);
			
			if($container == 'main'){
				foreach($leafs as $key => $leaf){				
					if($leaf['container'] && $leaf['container'] != $container) unset($leafs[$key]);
				}
			} else {
				foreach($leafs as $key => $leaf){				
					if($leaf['container'] != $container) unset($leafs[$key]);
				}
			}

			wp_cache_set('leafs_page_'.$page.'_container_'.$container, $leafs, 'headway');

			return $leafs;
		}
		
	} else {
				
		if(wp_cache_get('leafs_page_'.$page, 'headway')){
			return wp_cache_get('leafs_page_'.$page, 'headway');
		} else {
			$leafs = $wpdb->get_results("SELECT * FROM $headway_leafs_table WHERE page='$page' ORDER BY position ASC", ARRAY_A);

			wp_cache_set('leafs_page_'.$page, $leafs, 'headway');

			return $leafs;
		}
		
	}
}


function headway_top_leafs_container(){
	$top_container = headway_get_page_option(false, 'show-top-leafs-container');
	
	if(!$top_container) return false;
	
	echo '<div id="top-container" class="leafs-container container clearfix">';
	do_action('headway_leaf_container_open', 'top');
	headway_build_leafs('top');
	do_action('headway_leaf_container_close', 'top');
	echo '</div><!-- #top-container -->';
}


function headway_bottom_leafs_container(){
	$bottom_container = headway_get_page_option(false, 'show-bottom-leafs-container');
	
	if(!$bottom_container) return false;
	
	echo '<div id="bottom-container" class="leafs-container container clearfix">';
	do_action('headway_leaf_container_open', 'bottom');
	headway_build_leafs('bottom');
	do_action('headway_leaf_container_close', 'bottom');
	echo '</div><!-- #bottom-container -->';
}


add_action('headway_above_columns_container', 'headway_top_leafs_container');
add_action('headway_below_columns_container', 'headway_bottom_leafs_container');


function headway_build_layout(){	
	$columns = (int)headway_get_page_option(false, 'leaf-columns');
	$top_container = headway_get_page_option(false, 'show-top-leafs-container');
	$bottom_container = headway_get_page_option(false, 'show-bottom-leafs-container');
	
	if($columns && $columns !== 1){
		
		do_action('headway_above_columns_container');
		
		echo '<div id="columns-container">'."\n";
		
		for($i = 1; $i <= $columns; $i++){
			$last_column = ($i == $columns) ? ' last-leafs-column' : false;
			
			echo '<div id="column-'.$i.'-page-'.headway_current_page().'" class="container leafs-column-'.$i.' leafs-column'.$last_column.' clearfix">';
			
			do_action('headway_leaf_column_open', $i);
			
			headway_build_leafs($i);
			
			if($i === 1){
				headway_build_leafs('main');
				
				if(!$top_container) headway_build_leafs('top');
				if(!$bottom_container) headway_build_leafs('bottom');
				
				for($odd_balls_i = $columns+1; $odd_balls_i <= 4; $odd_balls_i++){
					headway_build_leafs($odd_balls_i);
				}
			}
			
			do_action('headway_leaf_column_close', $i);
			
			echo '</div><!-- #column-'.$i.'-page-'.headway_current_page().' -->';
		}
		
		echo "\n".'</div><!-- #columns-container -->';
		
		do_action('headway_below_columns_container');
		
	} else {
		
		echo '<div id="container" class="container clearfix">';
		do_action('headway_leaf_container_open', 'top');
		headway_build_leafs();
		do_action('headway_leaf_container_close', 'bottom');
		echo '</div><!-- #container -->';
		
	}
	
}


function headway_build_leafs($container = false){
	global $wp_query;

	if(isset($wp_query->query_vars['idx-action'])){
		$leafs = headway_get_page_leafs('single', $container);
	} else {
		$leafs = headway_get_page_leafs(headway_current_page(), $container);
	}
	
	if(count($leafs) > 0){												    	
		foreach($leafs as $leaf){ 													// Start foreach loop for every leaf/box.
			$leaf = array_map('maybe_unserialize', $leaf);			
						
			$leaf_config = $leaf['config'];
			$leaf_options = $leaf['options'];
			
			
				$box_classes[$leaf['id']] = array(); //Create empty array. Won't work unless this is here.
				array_push($box_classes[$leaf['id']], $leaf['type']); // Push the leaf type to the classes array.

				if($leaf['config']['custom-classes']){
					$custom_classes[$leaf['id']] = explode(' ', $leaf['config']['custom-classes']);
					
					foreach($custom_classes[$leaf['id']] as $custom_class){
						array_push($box_classes[$leaf['id']], $custom_class);
					}
				}  
				if(!$leaf['config']['show-title']) array_push($box_classes[$leaf['id']], 'box-no-title');
				if(isset($leaf['config']['align']) && $leaf['config']['align'] == 'right') array_push($box_classes[$leaf['id']], 'headway-leaf-right');
				
				if(isset($leaf['config']['clear']) && $leaf['config']['clear'] == 'both') array_push($box_classes[$leaf['id']], 'headway-leaf-clear-both');
				if(isset($leaf['config']['clear']) && ($leaf['config']['clear'] == 'left' || $leaf['config']['clear'] == 'right')) array_push($box_classes[$leaf['id']], 'headway-leaf-clear-'.$leaf['config']['align']);
				
				if($leaf['config']['fluid-height']) array_push($box_classes[$leaf['id']], 'fluid-height');
												
				$box_classes[$leaf['id']] = implode(' ', $box_classes[$leaf['id']]); //Implodes array separating each class with a space so when echoed it doesn't print "Array"
			
									
			echo "\n\n".'<div class="'.$box_classes[$leaf['id']].' headway-leaf" id="leaf-'.$leaf['id'].'">'."\n";
			
				echo '<div class="headway-leaf-inside">';
			
					if($leaf['type'] == 'sidebar') do_action('headway_before_sidebar');
			
					do_action('headway_before_leaf', $leaf);
					do_action('headway_before_leaf_'.$leaf['id']);
					if($leaf_config['show-title']):
						$leaf_title = ($leaf_config['title-link']) ? '<a href="'.$leaf_config['title-link'].'" title="">'.stripslashes(base64_decode($leaf_config['title'])).'</a>' : stripslashes(base64_decode($leaf_config['title']));
						echo "\n".'<div class="leaf-top">'.$leaf_title.'</div>';
					endif;
										
					echo "\n".'<div class="leaf-content">'."\n";
				
					$action = 'headway_custom_leaf_'.$leaf['type'].'_content';
				
					if(isset($_GET['safe-mode']) && headway_can_visually_edit()){
						echo 'You are currently in safe mode.  All leaf content will be disregarded until you leave safe mode.';
					} elseif(!has_action($action)){
						echo 'The requested leaf type does not exist.  Please re-activate the plugin if you wish to use this leaf again.';
					} else {
						do_action($action, $leaf);
					}

					echo '</div><!-- .leaf-content -->'."\n";
				
					do_action('headway_after_leaf', $leaf);
					do_action('headway_after_leaf_'.$leaf['id']);
					
					if($leaf['type'] == 'sidebar') do_action('headway_after_sidebar');
				
					rewind_posts();
					wp_reset_query();
				
				echo '</div><!-- .headway-leaf-inside -->'."\n";
					
			echo '</div><!-- #leaf-'.$leaf['id']." -->\n\n";
		}
	}
}


function headway_build_leaf_content($leaf_id, $content_only = false){	
	$leaf = headway_get_leaf($leaf_id);	

	if(!$leaf){
		echo 'Leaf does not exist.';
	
		return false;
	}

	$action = 'headway_custom_leaf_'.$leaf['type'].'_content';
	
	if($content_only){
		do_action($action, $leaf);
		
		return;
	}
}


function headway_get_all_leafs($type = false){
	global $wpdb;
	$headway_leafs_table = $wpdb->prefix.'headway_leafs';
	
	$where = $type ? " WHERE type = '$type'" : false;
		
	$result = $wpdb->get_results("SELECT * FROM $headway_leafs_table$where ORDER BY id ASC", ARRAY_A);
	return $result;
}


function headway_get_last_leaf_id(){
	global $wpdb;
	$headway_leafs_table = $wpdb->prefix.'headway_leafs';
	
	$result = $wpdb->get_row("SELECT * FROM $headway_leafs_table ORDER BY id DESC LIMIT 1", ARRAY_A);
	return $result['id'];
}


function headway_delete_page_leafs($page){
	global $wpdb;
	$headway_leafs_table = $wpdb->prefix.'headway_leafs';
	
	$wpdb->query("DELETE FROM $headway_leafs_table WHERE page='$page'");
}


function headway_delete_all_leafs(){
	global $wpdb;
	$headway_leafs_table = $wpdb->prefix.'headway_leafs';
	
	$wpdb->query("TRUNCATE TABLE $headway_leafs_table");
}


function headway_build_default_leafs($pageID, $force = false, $use_template = false){
	global $wp_query;
	
	$leafs = headway_get_page_leafs($pageID);
	
	if((!isset($wp_query->query_vars['idx-action']) && count($leafs) < 1) || $force){
		
		if($force){
			global $wpdb;
			$headway_leafs_table = $wpdb->prefix.'headway_leafs';

			$wpdb->query("DELETE FROM $headway_leafs_table WHERE page='$pageID'");
		}
		
		if($use_template){
			//Turn value from option element into something usable
			$template = explode('---', $use_template);
			$use_template = array('id' => $template[0], 'name' => $template[1]);
		} else {
			$use_template = headway_get_option('default-leafs-template');
		}
				
		if($use_template && headway_get_option('leaf-template-'.$use_template['name'].'-'.$use_template['id'])){
			$page = $pageID;

			$template = headway_json_decode(headway_get_option('leaf-template-'.$use_template['name'].'-'.$use_template['id']));

			headway_update_page_option($page, 'leaf-columns', $template['columns']);

			if(count($template['leafs']) > 0){
				headway_delete_page_leafs($page);
				
				$containers = array();	

				foreach($template['leafs'] as $leaf){
					$leaf = array_map('maybe_unserialize', $leaf);	
					
					$type = isset($leaf['type']) ? $leaf['type'] : $leaf['config']['type'];

					headway_add_leaf($page, array('container' => $leaf['container'], 'position' => $leaf['position'], 'config' => $leaf['config'], 'options' => $leaf['options'], 'type' => $type));
					
					if(!is_numeric($leaf['container']))
						$containers[] = $leaf['container'];
				}
				
				$containers = array_unique($containers);
				
				if(isset($template['columns']) && (int)$template['columns'] !== 1){
					foreach($template['column-widths'] as $width => $value){
						headway_update_page_option($page, 'column-'.$width.'-width', $value);
					}
					
					if(in_array('top', $containers)){
						headway_update_page_option($page, 'show-top-leafs-container', 'on');
					} else {
						headway_delete_page_option($page, 'show-top-leafs-container');
					}

					if(in_array('bottom', $containers)){
						headway_update_page_option($page, 'show-bottom-leafs-container', 'on');
					} else {
						headway_delete_page_option($page, 'show-bottom-leafs-container');
					}
				}
			}
		} else {
			headway_add_leaf($pageID, 
				array(
					'position' => 1,
					'type' => 'content',
					'config' => array(
						'title' => 'Content',
						'show-title' => false,
						'title-link' => false,
						'width' => (int)headway_get_skin_option('wrapper-width')-((int)headway_get_skin_option('leaf-container-horizontal-padding')*2)-((int)headway_get_skin_option('leaf-margins')*2)-((int)headway_get_skin_option('leaf-padding')*2),
						'height' => 125,
						'fluid-height' => true,
						'align' => 'left',
						'custom-classes' => false
					),
					'options' => array(
						'mode' => 'page',
						'other-page' => false,
						'categories-mode' => 'include',
						'post-limit' => get_option('posts_per_page'),
						'featured-posts' => 1,
						'paginate' => true
					)
				)
			);
		}
		
		headway_generate_cache(array('leafs'));
	}
}


function headway_load_leafs(){
	$path = HEADWAYLEAFS;
	$dir_handle = @opendir($path) or die("Unable to open $path");

	while(false !== ($file = readdir($dir_handle))) {
		$file = rawurlencode($file);
		$leafs_dir[] = $file;
	}
	
	$remove_these = array('index.php', '.', '..', '.svn', '.git', 'includes', 'icons', '.DS_Store');
	$leafs = array_diff($leafs_dir, $remove_these);

	closedir($dir_handle);
		
	foreach($leafs as $leaf){
		if(!strpos($leaf, '.php')) continue;
		
		require_once HEADWAYLEAFS.'/'.$leaf;
	}
}
add_action('init', 'headway_load_leafs');


function headway_is_page_linked($page = false){
	if(!$page){
		$page = headway_current_page(true);
	}
	
	if(headway_get_option('leaf-template-page-'.$page)){
		$linking = true;
	}
	
	if(headway_get_option('leaf-template-system-page-'.$page)){
		$linking = true;
	}
	
	if(get_post_meta($page, '_leaf_template', true) && get_post_meta($page, '_leaf_template', true) != 'DELETE'){
		$linking = true;
	}
	
	if(get_post_meta($page, '_leaf_system_template', true) && get_post_meta($page, '_leaf_system_template', true) != 'DELETE'){
		$linking = true;
	}
	
	if(isset($linking)){
		return true;
	} else {
		return false;
	}
}


function headway_check_if_new_leaf($leaf){
	$leaf = (strpos($leaf, 'leaf-') !== false) ? $leaf : 'leaf-'.$leaf;
	
	$add_array = ($_POST['add']) ? $_POST['add'] : array();
	
	if($add_array[$leaf]){
		return true;
	} else {
		return false;
	}
}


function headway_wizard_layout_builder($use_columns = false, $number_of_cols = false, $content_col = false, $sidebar_col = false, $alt_sidebar_col = false){
	headway_delete_all_leafs();
	
	$system_pages = array(
						'index', 
						'single', 
						'category',
						'archives', 
						'tag', 
						'author',
						'search', 
						'four04');
						
	foreach($system_pages as $system_page){
		headway_wizard_layout_builder_action($system_page, $use_columns, $number_of_cols, $content_col, $sidebar_col, $alt_sidebar_col);
	}
	
	$all_pages = new WP_Query('post_type=page');
	while ($all_pages->have_posts()) : $all_pages->the_post();
		headway_wizard_layout_builder_action($all_pages->post->ID, $use_columns, $number_of_cols, $content_col, $sidebar_col, $alt_sidebar_col);
	endwhile;
	
}


function headway_wizard_layout_builder_action($page, $use_columns = false, $number_of_cols = false, $content_col = false, $sidebar_col = false, $alt_sidebar_col = false){
	global $primary_sidebar_duplicate_id;
	global $secondary_sidebar_duplicate_id;
	
	$wrapper_width = headway_get_skin_option('wrapper-width');
	$container = $wrapper_width - 10;	
	$leafs_padding_margin = (headway_get_skin_option('leaf-padding') + headway_get_skin_option('leaf-margins')) * 2;
		
	if($number_of_cols === 1){		
		$content_width = $container - $leafs_padding_margin;
	} elseif($number_of_cols == 2){
		$content_width = round($container*.7, 0) - $leafs_padding_margin;
		$sidebar_width = round($container*.3, 0) - $leafs_padding_margin;
	} elseif($number_of_cols == 3){
		$content_width = round($container*.56, 0) - $leafs_padding_margin;
		$sidebar_width = round($container*.22, 0) - $leafs_padding_margin;
		$alt_sidebar_width = round($container*.22, 0) - $leafs_padding_margin;
	}
	
	if(!isset($primary_sidebar_duplicate_id)) $primary_sidebar_duplicate_id = false;
	if(!isset($secondary_sidebar_duplicate_id)) $secondary_sidebar_duplicate_id = false;
	
	$content_leaf = array(
						'position' => $content_col,
						'type' => 'content',
						'config' => array(
							'title' => 'Content',
							'show-title' => false,
							'title-link' => false,
							'width' => $content_width,
							'height' => 125,
							'fluid-height' => true,
							'align' => 'left',
							'custom-classes' => false
						),
						'options' => array(
							'mode' => 'page',
							'other-page' => false,
							'categories-mode' => 'include',
							'post-limit' => get_option('posts_per_page'),
							'featured-posts' => 1,
							'paginate' => true
						)
					);
					
	$sidebar_leaf = array(
						'position' => $sidebar_col,
						'type' => 'sidebar',
						'config' => array(
							'title' => 'Primary Sidebar',
							'show-title' => false,
							'title-link' => false,
							'width' => $sidebar_width,
							'height' => 125,
							'fluid-height' => true,
							'align'=> 'left',
							'custom-classes' => false
						),
						'options' => array(
							'duplicate-id' => $primary_sidebar_duplicate_id,
							'sidebar-name' => 'Primary Sidebar'
						)
					);
					
	$alt_sidebar_leaf = $sidebar_leaf;
	$alt_sidebar_leaf['position'] = $alt_sidebar_col;
	$alt_sidebar_leaf['options']['sidebar-name'] = 'Secondary Sidebar';
	$alt_sidebar_leaf['options']['duplicate-id'] = $secondary_sidebar_duplicate_id;
	$alt_sidebar_leaf['config']['width'] = $alt_sidebar_width;
	
	if($number_of_cols > 1 && $use_columns){
		headway_update_page_option($page, 'leaf-columns', $number_of_cols);

		if($number_of_cols == 2){
			$content_width = round($container*.73) - 20;			
			$sidebar_width = round($container*.27) - 20;
			
			
			headway_update_page_option($page, 'column-'.$content_col.'-width', $content_width);
			headway_update_page_option($page, 'column-'.$sidebar_col.'-width', $sidebar_width);
		} elseif($number_of_cols == 3){
			$content_width = round($container*.56) - 20;			
			$sidebar_width = round($container*.22) - 20;
			
			headway_update_page_option($page, 'column-'.$content_col.'-width', $content_width);
			headway_update_page_option($page, 'column-'.$sidebar_col.'-width', $sidebar_width);
			headway_update_page_option($page, 'column-'.$alt_sidebar_col.'-width', $sidebar_width);
		}
		
		$content_leaf['container'] = $content_col;
		$sidebar_leaf['container'] = $sidebar_col;
		$alt_sidebar_leaf['container'] = $alt_sidebar_col;
	} else {
		headway_update_page_option($page, 'leaf-columns', 1);
	}
	
	if($number_of_cols === 1){
		headway_add_leaf($page, $content_leaf);
	} elseif($number_of_cols == 2){
		headway_add_leaf($page, $content_leaf);
		
		if(!$primary_sidebar_duplicate_id){
			$primary_sidebar_duplicate_id = headway_add_leaf($page, $sidebar_leaf);
		} else {
			headway_add_leaf($page, $sidebar_leaf);
		}
	} elseif($number_of_cols == 3){
		headway_add_leaf($page, $content_leaf);
		
		if(!$primary_sidebar_duplicate_id && !$secondary_sidebar_duplicate_id){
			$primary_sidebar_duplicate_id = headway_add_leaf($page, $sidebar_leaf);
			$secondary_sidebar_duplicate_id = headway_add_leaf($page, $alt_sidebar_leaf);
		} else {
			headway_add_leaf($page, $sidebar_leaf);
			headway_add_leaf($page, $alt_sidebar_leaf);
		}
	}
	
}