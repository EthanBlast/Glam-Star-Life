<?php
/*
Plugin Name: Headway Leaf: Navigation Leaf
Plugin URI: http://headwaythemes.com
Description: Add a simple navigation menu/bar to your site.
Author: Clay Griffiths
Version: 1.0
Author URI: http://headwaythemes.com
*/

register_activation_hook(__FILE__, 'activate_navigation_leaf');

function activate_navigation_leaf(){
	if(!function_exists('headway_add_element_styles')) return;
	
	if(!headway_get_option('navigation-leaf-installed')){
		
		headway_add_element_styles(array(
			'div.leaf-content ul.horizontal-navigation' =>
				array(
					'background' => 'ffffff',
					'background-transparent' => 'on',
					'bottom-border' => 'ffffff',
					'bottom-border-width' => 'zero'
				),
				
			'div.leaf-content ul.horizontal-navigation li a' =>
				array(
					'color' => '888888',
					'background' => 'ffffff',
					'background-transparent' => 'on',
					'right-border' => 'ffffff',
					'right-border-width' => 'zero',
					'font-family' => 'georgia, serif',
					'font-weight' => 'normal',
					'font-size' => '14',
					'line-height' => '18',
					'text-transform' => 'none',
					'letter-spacing' => '0px',
					'font-variant' => 'normal'
				),
			
			'div.leaf-content ul.horizontal-navigation li.current_page_item a' =>
				array(
					'color' => '555555',
					'background' => 'ffffff',
					'background-transparent' => 'on',
					'right-border' => 'ffffff',
					'right-border-width' => 'zero',
					'font-family' => 'georgia, serif',
					'font-weight' => 'normal',
					'font-size' => '14',
					'line-height' => '18',
					'text-transform' => 'none',
					'letter-spacing' => '0px',
					'font-variant' => 'normal'
				)
		));
		
		headway_update_option('navigation-leaf-installed', 'true');
		
	}
}



function navigation_leaf_options($leaf){
	if($leaf['new']){
		$leaf['config']['show-title'] = false;
		
		$leaf['options']['show-sub-pages'] = true;
	}


	HeadwayLeafsHelper::create_tabs(array('pages' => 'Pages', 'display-options' => 'Display Options', 'miscellaneous' => 'Miscellaneous'), $leaf['id']);
	
	//////
	
	HeadwayLeafsHelper::open_tab('pages', $leaf['id']);		
		
		if(!headway_nav_menu_check()){
		
			$excluded_pages = $leaf['options']['excluded-pages'];
			$included_pages = $leaf['options']['included-pages'];

			$page_select_query = get_pages();
		
			foreach($page_select_query as $page){ 
				if(is_array($excluded_pages)){
					if(in_array($page->ID, $excluded_pages)) $excluded_pages_selected[$page->ID] = ' selected';
				}
			
				if(is_array($included_pages)){
					if(in_array($page->ID, $included_pages)) $included_pages_selected[$page->ID] = ' selected';
				}

				$excluded_pages_options .= '<option value="'.$page->ID.'"'.$excluded_pages_selected[$page->ID].'>'.$page->post_title.'</option>';
				$included_pages_options .= '<option value="'.$page->ID.'"'.$included_pages_selected[$page->ID].'>'.$page->post_title.'</option>';

			}
			
?>
			<tr>
				<th scope="row"><label for="<?php echo $leaf['id'] ?>_included_pages">Included Pages</label></th>
				<td>
					<select class="headway-visual-editor-input" name="leaf-options[<?php echo $leaf['id'] ?>][included-pages][]" id="<?php echo $leaf['id'] ?>_included_pages" multiple size="5">
						<?php echo $included_pages_options; ?>
					</select>
				</td>
			</tr>
		
			<tr>
				<th scope="row"><label for="<?php echo $leaf['id'] ?>_excluded_pages">Excluded Pages</label></th>
				<td>
					<select class="headway-visual-editor-input" name="leaf-options[<?php echo $leaf['id'] ?>][excluded-pages][]" id="<?php echo $leaf['id'] ?>_excluded_pages" multiple size="5">
						<?php echo $excluded_pages_options; ?>
					</select>
				</td>
			</tr>
<?php		
		} else {
			
			$wp_new_nav_menus = wp_get_nav_menus();
			foreach($wp_new_nav_menus as $menu) {							
				if(wp_get_nav_menu_items($menu->term_id)){					
					$nav_menus_options[$menu->slug] = $menu->name;
				}
			}
			
?>
				<tr>
					<td colspan="2">
						<p class="info-box">The navigation leaf has detected you are using WordPress' new navigation menu functionality.  Please select which menu you would like this leaf to display.</p>
					</td>
				</tr>
<?php
			
			HeadwayLeafsHelper::create_select(array('name' => 'new-nav-menu', 'value' => $leaf['options']['new-nav-menu'], 'label' => 'Navigation Menu', 'options' => $nav_menus_options), $leaf['id']);
			
		}

		HeadwayLeafsHelper::create_select(array('name' => 'sortby', 'value' => $leaf['options']['sortby'], 'label' => 'Sort Items By', 'options' => array('menu_order' => 'Page Order', 'page_title' => 'Page Title', 'ID' => 'Page ID')), $leaf['id']);

		HeadwayLeafsHelper::create_checkbox(array('no-border' => true, 'name' => 'show-sub-pages', 'value' => $leaf['options']['show-sub-pages'], 'left-label' => 'Sub Pages', 'checkbox-label' => 'Show Sub Pages'), $leaf['id']);	
				
	HeadwayLeafsHelper::close_tab();
	
	////////////
	
	HeadwayLeafsHelper::open_tab('display-options', $leaf['id']);		
		
		HeadwayLeafsHelper::create_checkbox(array('no-border' => true, 'name' => 'horizontal-navigation', 'value' => $leaf['options']['horizontal-navigation'], 'left-label' => 'Orientation', 'checkbox-label' => 'Flip Navigation Horitonally'), $leaf['id']);
		
	HeadwayLeafsHelper::close_tab();		
	
	////////////
	
	HeadwayLeafsHelper::open_tab('miscellaneous', $leaf['id']);
	
		HeadwayLeafsHelper::create_show_title_checkbox($leaf['id'], $leaf['config']['show-title']);
		HeadwayLeafsHelper::create_title_link_input($leaf['id'], $leaf['config']['leaf-title-link']);
		HeadwayLeafsHelper::create_classes_input($leaf['id'], $leaf['config']['custom-css-classes'], true);
		
	HeadwayLeafsHelper::close_tab();
}



function navigation_leaf_content($leaf){
	$css_classes = $leaf['options']['horizontal-navigation'] ? 'navigation-leaf navigation horizontal-navigation' : 'navigation-leaf link-list';
	
	$exclude = $leaf['options']['excluded-pages'];
	$include = $leaf['options']['included-pages'];
	$sortby = $leaf['options']['sortby'] ? $leaf['options']['sortby'] : 'menu_order';
		
	$options['echo'] = false;
	$options['title_li'] = '';
	$options['sort_column'] = $sortby;	
		
	if(count($include) > 0) $options['include'] = implode(',', array_values($include));
	if(count($exclude) > 0) $options['exclude'] = implode(',', array_values($exclude));
		
	if(!$leaf['options']['show-sub-pages']) $options['depth'] = 1; 
	
	//// Set Up 3.0 (or 3.1) WordPress Nav Options
	if($leaf['options']['new-nav-menu']) $new_options['menu'] = $leaf['options']['new-nav-menu'];
	
	$new_options['sort_column'] = $options['sort_column']; 
	$new_options['container'] = false;
	$new_options['menu_class'] = $css_classes;	
	////
	
	if(function_exists('wp_nav_menu') && headway_nav_menu_check())
		wp_nav_menu($new_options);
	else
		echo "<ul class=\"$css_classes\">\n".wp_list_pages($options)."\n</ul>";
			
}



function register_navigation_leaf(){	
	$options = array(
			'id' => 'navigation-leaf',
			'name' => 'Navigation Leaf',
			'options_callback' => 'navigation_leaf_options',
			'icon' => WP_PLUGIN_URL.'/'.str_replace(basename(__FILE__), '', plugin_basename(__FILE__)).'icon.png',
			'content_callback' => 'navigation_leaf_content'
		);
	
	if(class_exists('HeadwayLeaf')){
		$navigation_leaf = new HeadwayLeaf($options);
		
		add_action('wp', 'navigation_leaf_css');
		
		headway_add_custom_element(array('selector' => 'div.leaf-content ul.horizontal-navigation', 'name' => 'Horizontal Navigation &mdash; Container', 'color_options' => array('background', 'bottom-border')));
		headway_add_custom_element(array('selector' => 'div.leaf-content ul.horizontal-navigation li a', 'name' => 'Horizontal Navigation &mdash; Item', 'color_options' => array('color', 'background', 'right-border'), 'fonts' => true, 'fonts_advanced' => true, 'specific_selector' => 'div.leaf-content ul.horizontal-navigation li a, div.leaf-content ul.horizontal-navigation li ul, div.leaf-content ul.horizontal-navigation li.current_page_item ul li a'));
		
		headway_add_custom_element(array('selector' => 'div.leaf-content ul.horizontal-navigation li.current_page_item a', 'name' => 'Horizontal Navigation &mdash; Item (Selected)', 'color_options' => array('color', 'background', 'right-border'), 'fonts' => true, 'fonts_advanced' => true, 'specific_selector' => 'div.leaf-content ul.horizontal-navigation li.current_page_item a, div.leaf-content ul.horizontal-navigation li.current-menu-item a, div.leaf-content ul.horizontal-navigation li.current_page_item ul, div.leaf-content ul.horizontal-navigation li.current_page_parent a, div.leaf-content ul.horizontal-navigation li.current_page_parent ul, div.leaf-content ul.horizontal-navigation li.current_page_ancestor a, div.leaf-content ul.horizontal-navigation li.current_page_ancestor ul'));
	}
}
add_action('init', 'register_navigation_leaf');



function navigation_leaf_css(){
	wp_enqueue_style('navigation-leaf', WP_PLUGIN_URL.'/'.str_replace(basename(__FILE__), '', plugin_basename(__FILE__)).'navigation.css');
}

