<?php
switch($_GET['headway-process']){
	
		
	case 'options-js':
		do_action('headway_custom_leaf_options_js_'.$_GET['leaf'], $_GET['id']);
	break;
	
	
	
	case 'leaf-options':
		do_action('headway_custom_leaf_'.$_GET['leaf'].'_options', $_GET['id'], false);
	break;
	
	
	
	case 'get-leaf-options-width':
		do_action('headway_custom_leaf_'.$_GET['leaf'].'_options', $_GET['id'], true);
	break;
	
	
	
	case 'nav-item-options':
		headway_navigation_item_options($_GET['nav-item'], urldecode($_GET['nav-item-name']));
	break;
	
	
	
	case 'proxy':
		$url = rawurldecode($_GET['url']);

		if(function_exists('curl_init')){
			$ch = curl_init();

			$username = strtolower(headway_get_option('headway-username'));
			$password = headway_get_option('headway-password');

			curl_setopt($ch, CURLOPT_URL, $url);
			if($_GET['use_auth']) curl_setopt($ch, CURLOPT_USERPWD, $username.':'.$password);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

			$curl_exec = curl_exec($ch);		

			if(curl_getinfo($ch, CURLINFO_HTTP_CODE) == 200){
				$content = $curl_exec;
			} else {
				$content = '<p>Please enter a valid username and password in the <a href="'.get_bloginfo('wpurl').'/wp-admin/admin.php?page=headway#headway-registration">Headway configuration page</a>.</p>';
			}

			curl_close ($ch);
		} else {
			$content = '<p>Your web server does not support cURL (libcurl).  Please contact your web host.</p>';
		}

		echo $content;
	break;
	
	
	
	case 'import-style':
		echo $_GET['callback'] . '(' . headway_import_style(array('file' => $_GET['path'])) . ')';
	break;
	
	
	
	case 'load-style':
		$style = headway_get_option('style-'.urldecode($_GET['style-name']));
		
		echo $_GET['callback'] . '(' . $style . ')';
	break;
	
	
	
	case 'build-color-scheme':	
		$style = headway_json_decode(file_get_contents(HEADWAYLIBRARY.'/installation/styles/Magazine.hwstyle'));
		
		$primary = headway_hex_to_rgb($_GET['primary-color']);
		$secondary = headway_hex_to_rgb($_GET['secondary-color']);
		
		$primary_color_lightness = headway_rgb_to_lightness($primary);
		$secondary_color_lightness = headway_rgb_to_lightness($secondary);
				
		if($secondary_color_lightness >= 50){
			
			$colors['nav-item-color'] = array($secondary[0]-110, $secondary[1]-110, $secondary[2]-110);
			$colors['nav-item-selected-color'] = array($secondary[0]-190, $secondary[1]-190, $secondary[2]-190);
			
			$colors['nav-border'] = array($secondary[0]-25, $secondary[1]-25, $secondary[2]-25);
			
		} else {
			
			$colors['nav-item-color'] = array($secondary[0]+85, $secondary[1]+85, $secondary[2]+85);
			$colors['nav-item-selected-color'] = array($secondary[0]+230, $secondary[1]+230, $secondary[2]+230);
			
			$colors['nav-border'] = array($secondary[0]+25, $secondary[1]+25, $secondary[2]+25);
			
		}
		
		foreach($colors as $color => $value){
			$colors[$color] = headway_rgb_to_hex($value);
		}
			
		$data['style'] = $style['styles'];
		$data['colors'] = $colors;	
			
		echo $_GET['callback'] . '(' . headway_json_encode($data) . ')';
	break;
	
	
	
	case 'export-style':
		header("Cache-Control: public");
		header("Content-Description: File Transfer");
		header("Content-Disposition: attachment; filename=".str_replace(' ', '_', $_GET['style-name']).".hwstyle");
		header("content-type: text/plain");
		header("Content-Transfer-Encoding: binary");

		echo headway_get_option('style-'.urldecode($_GET['style-id']));
	break;
	
	
	
	case 'save-style':
		//Generate random ID for style
		$random = rand(15, 9999);
	
		$style['styles'] = array();
		
		foreach($_POST['color'] as $element => $properties){
			foreach($properties as $property => $value){
				array_push($style['styles'], array('element' => $element, 'property_type' => 'color', 'property' => $property, 'value' => $value));
			}
		}
		
		foreach($_POST['fonts'] as $element => $properties){
			foreach($properties as $property => $value){
				array_push($style['styles'], array('element' => $element, 'property_type' => 'font', 'property' => $property, 'value' => $value));
			}
		}

		foreach($_POST['width'] as $element => $properties){
			foreach($properties as $property => $value){
				array_push($style['styles'], array('element' => $element, 'property_type' => 'sizing', 'property' => $property, 'value' => $value));
			}
		}
		
		$style['style-id'] = $random;
		$style['style-name'] = urldecode($_GET['style-name']);
		$style['color-primary'] = urldecode($_GET['color-primary']);
		$style['color-secondary'] = urldecode($_GET['color-secondary']);
		$style['color-tertiary'] = urldecode($_GET['color-tertiary']);
		
		$style_options['style-id'] = $style['style-id'];
		$style_options['style-name'] = $style['style-name'];
		$style_options['color-primary'] = $style['color-primary'];
		$style_options['color-secondary'] = $style['color-secondary'];
		$style_options['color-tertiary'] = $style['color-tertiary'];

		$styles = headway_get_option('styles');
				
		if(!$styles){
			headway_update_option('styles', array($style_options['style-name'].'-'.$random => $style_options));
		} else {
			$styles[$style['style-name'].'-'.$random] = $style_options;
			
			headway_update_option('styles', $styles);
		}
		
		headway_update_option('style-'.$style['style-name'].'-'.$random, headway_json_encode($style));
		
		echo $random;
	break;
	
	
	
	case 'delete-style':
		$styles = headway_get_option('styles');
		$style_id = str_replace('style-', '', $_GET['style-id']);
		
		unset($styles[$_GET['style-name'].'-'.$style_id]);
		
		headway_update_option('styles', $styles);
		
		headway_delete_option('style-'.$_GET['style-name'].'-'.$style_id);
	break;
	
	
	
	case 'rename-style':
		//Fetch Styles
		$styles = headway_get_option('styles'); 
		$style_id = str_replace('style-', '', $_GET['style-id']);
		
		//Replace style name in style options array to the new name
		$styles[$_GET['style-name'].'-'.$style_id]['style-name'] = $_GET['style-new-name'];
		
		//Replace the style in the styles array with the new renamed style.
		$styles[$_GET['style-new-name'].'-'.$style_id] = $styles[$_GET['style-name'].'-'.$style_id];
		
		//Delete old style
		unset($styles[$_GET['style-name'].'-'.$style_id]);
		
		//Save styles to DB
		headway_update_option('styles', $styles);
		
		//Fetch actual style content and decode it
		$style_content = headway_json_decode(headway_get_option('style-'.$_GET['style-name'].'-'.$style_id), true);
		
		//Delete old style content
		headway_delete_option('style-'.$_GET['style-name'].'-'.$style_id);
		
		//If the style is a non-legacy style, replace the name with the new name.
		if(isset($style_content['style-name'])){
			$style_content['style-name'] = $_GET['style-new-name'];
		}
		
		//Save new style content to DB
		headway_update_option('style-'.$_GET['style-new-name'].'-'.$style_id, headway_json_encode($style_content));
	break;
	
	
	
	case 'visual-editor-run-up':
		//Headway settings for JavaScript
		$headway_settings['name'] = get_bloginfo('name');		
		$headway_settings['tagline'] = get_bloginfo('description');		
		
		$headway_settings['url'] = get_bloginfo('url');
		$headway_settings['wpurl'] = get_bloginfo('wpurl');
		$headway_settings['upload-path'] = headway_upload_path();
		$headway_settings['upload-url'] = headway_upload_url();
		$headway_settings['headway-css-url'] = get_bloginfo('url').'/?headway-css=&'.headway_get_option('css-last-updated').'&visual-editor-open=true';
		
		$headway_settings['wrapper-width'] = headway_get_skin_option('wrapper-width');
		$headway_settings['leaf-container-vertical-padding'] = headway_get_skin_option('leaf-container-vertical-padding');
		$headway_settings['leaf-container-horizontal-padding'] = headway_get_skin_option('leaf-container-horizontal-padding');
		$headway_settings['leaf-padding'] = headway_get_skin_option('leaf-padding');
		$headway_settings['leaf-margins'] = headway_get_skin_option('leaf-margins');
		
		$headway_settings['last-leaf-id'] = headway_get_last_leaf_id();
	
		$headway_settings['link'] = headway_is_page_linked() ? true : false;
		$headway_settings['use-visual-editor'] = (headway_get_option('disable-visual-editor') || headway_get_option('enable-developer-mode') || headway_is_skin_active()) ? false : true;
		$headway_settings['use-inspector'] = (headway_get_option('disable-inspector') && $headway_settings['use-visual-editor'] === true) ? false : true;
		
		$headway_settings['legacy-nav'] = headway_nav_menu_check() ? false : true;
		////////////
		
		//Leaf Sizing
		$leafs = headway_get_page_leafs($_GET['page-id']);
		
		foreach($leafs as $leaf){
			$leaf_configs[$leaf['id']] = maybe_unserialize($leaf['config']);
			
			$leaf_configs[$leaf['id']]['width'] = ($leaf_configs[$leaf['id']]['width'] >= headway_get_skin_option('wrapper-width')) ? (int)headway_get_skin_option('wrapper-width')-((int)headway_get_skin_option('leaf-container-horizontal-padding')*2)-((int)headway_get_skin_option('leaf-margins')*2)-((int)headway_get_skin_option('leaf-padding')*2) : $leaf_configs[$leaf['id']]['width'];
		}
		////////////
		
		$json['headway-settings'] = $headway_settings;
		$json['sizing'] = $leaf_configs;
		
		echo $_GET['callback'] . '(' . headway_json_encode($json) . ')';
	break;
	
	
	
	case 'leaf-sizes':
		$leafs = headway_get_page_leafs($_GET['page-id']);
		
		foreach($leafs as $leaf){
			$leaf_configs[$leaf['id']] = maybe_unserialize($leaf['config']);
						
			$leaf_configs[$leaf['id']]['width'] = ($leaf_configs[$leaf['id']]['width'] >= headway_get_skin_option('wrapper-width')) ? (int)headway_get_skin_option('wrapper-width')-((int)headway_get_skin_option('leaf-container-horizontal-padding')*2)-((int)headway_get_skin_option('leaf-margins')*2)-((int)headway_get_skin_option('leaf-padding')*2) : $leaf_configs[$leaf['id']]['width'];
		}

		echo $_GET['callback'] . '(' . headway_json_encode($leaf_configs) . ')';
	break;
	
	
	
	case 'edit-leaf':
		if($_POST['encoded']){
			//Work-around for mod_security quirks

			$serialized = base64_decode($_POST['encoded']);		
			parse_str($serialized, $_POST);
		}
		
		foreach($_POST['leaf-options'] as $leaf => $options){
			array_walk_recursive($options, 'headway_options_base64_encode');

			if($options['text-content']) $options['text-content'] = base64_encode($options['text-content']);
			if($options['blurb']) $options['blurb'] = base64_encode($options['blurb']);

			headway_update_leaf($leaf, array('options' => $options));
		}
		
		foreach($_POST['config'] as $leaf => $config_post){
			$config[$leaf] = headway_get_leaf($leaf);
			$config[$leaf] = $config[$leaf]['config'];

			$config[$leaf]['show-title'] = $config_post['show-title'];
			$config[$leaf]['title-link'] = $config_post['leaf-title-link'];
			$config[$leaf]['custom-classes'] = $config_post['custom-css-classes'];

			headway_update_leaf($leaf, array('config' => $config[$leaf]));
		}
	break;
	
	
	
	case 'get-leaf-content':
		headway_build_leaf_content($_GET['leaf-id'], true);
	break;
	
	
	
	case 'export-leaf-template':
		header("Cache-Control: public");
		header("Content-Description: File Transfer");
		header("Content-Disposition: attachment; filename=".str_replace(' ', '_', $_GET['template-name']).".hwtpl");
		header("content-type: text/plain");
		header("Content-Transfer-Encoding: binary");

		echo headway_get_option('leaf-template-'.urldecode($_GET['template-id']));
	break;
	
	
	
	case 'import-leaf-template':
		echo $_GET['callback'] . '(' . headway_import_leaf_template(array('file' => $_GET['path'])) . ')';
	break;
	
	
	
	case 'save-leaf-template':
		//Generate random ID for template
		$random = rand(15, 9999);
			
		$template['id'] = $random;
		$template['name'] = urldecode($_GET['template-name']);

		$templates = headway_get_option('leaf-templates');
				
		if(!$templates){
			headway_update_option('leaf-templates', array($template['name'].'-'.$template['id'] => $template));
		} else {
			$templates[$template['name'].'-'.$template['id']] = $template;
			
			headway_update_option('leaf-templates', $templates);
		}
		
		$template['leafs'] = headway_get_page_leafs($_GET['page']);
		$template['columns'] = $_GET['columns'];
				
		$template['column-widths'] = array(); 		
				
		foreach($template['leafs'] as $key => $leaf){
			$template['leafs'][$key] = array_map('maybe_unserialize', $template['leafs'][$key]);	
					
			$leaf = array_map('maybe_unserialize', $leaf);	
			
			if($leaf['type'] != 'sidebar') continue;
									
			if(!$leaf['options']['duplicate-id'])
				$template['leafs'][$key]['options']['duplicate-id'] = $leaf['id'];
		}
				
		if($_GET['column-1-width'] != 'unserialized') $template['column-widths'][1] = $_GET['column-1-width'];
		if($_GET['column-2-width'] != 'unserialized') $template['column-widths'][2] = $_GET['column-2-width'];
		if($_GET['column-3-width'] != 'unserialized') $template['column-widths'][3] = $_GET['column-3-width'];
		if($_GET['column-4-width'] != 'unserialized') $template['column-widths'][4] = $_GET['column-4-width'];
				
		headway_update_option('leaf-template-'.$template['name'].'-'.$template['id'], headway_json_encode($template));
		
		echo $random;
	break;
	
	
	
	case 'rename-template':
		//Fetch Templates
		$templates = headway_get_option('leaf-templates'); 
		$template_id = str_replace('template-', '', $_GET['id']);
				
		//Replace template name in template options array to the new name
		$templates[$_GET['template-name'].'-'.$template_id]['name'] = $_GET['new-name'];
		
		//Replace the template in the templates array with the new renamed template.
		$templates[$_GET['new-name'].'-'.$template_id] = $templates[$_GET['template-name'].'-'.$template_id];
		
		//Delete old template
		unset($templates[$_GET['template-name'].'-'.$template_id]);
		
		//Save templates to DB
		headway_update_option('leaf-templates', $templates);
		
		//Fetch actual template content and decode it
		$template_content = headway_json_decode(headway_get_option('leaf-template-'.$_GET['template-name'].'-'.$template_id), true);
		
		//Delete old template content
		headway_delete_option('template-'.$_GET['template-name'].'-'.$template_id);
		
		//Save new template content to DB
		headway_update_option('template-'.$_GET['new-name'].'-'.$template_id, headway_json_encode($template_content));
	break;
	
	
	
	case 'delete-template':
		$templates = headway_get_option('leaf-templates');
		
		$template_id = str_replace('template-', '', $_GET['template-id']);
		$template_name = $_GET['template-name'];
		
		unset($templates[$template_name.'-'.$template_id]);
		
		headway_update_option('leaf-templates', $templates);
		headway_delete_option('template-'.$template_name.'-'.$template_id);
		
		$default_template = headway_get_option('default-leafs-template');
		
		if($template_id == $default_template['id']) headway_delete_option('default-leafs-template');
	break;
	
	
	
	case 'load-template':
		$page = $_GET['page'];
	
		$template = headway_json_decode(headway_get_option('leaf-template-'.urldecode($_GET['template-name'])));
				
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
		}
				
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
	break;
	
	
	
	case 'set-default-leafs-template':
		return headway_update_option('default-leafs-template', array('name' => urldecode($_GET['template-name']), 'id' => str_replace('template-', '', urldecode($_GET['id']))));
	break;
	
	
	
	case 'remove-default-leafs-template':
		return headway_delete_option('default-leafs-template');
	break;
	
	
	
	case 'skip-wizard':
		headway_update_option('ran-wizard', true);
	break;
	
	
	
	case 'analyze-image':
		$colors = headway_image_color_palette(headway_upload_path(true).'/header-uploads/'.$_GET['image'], 10, 5, true);
		
		echo $_GET['callback'] . '(' . headway_json_encode($colors) . ')';
	break;
	
	
	
	case 'export-settings':
		switch($_GET['what']){
			case 'configuration':
				$filename = 'Configuration';
				$what = 'configuration';
				
				$options = array(
					'feed-url',
					'print-css',
					'favicon',
					'affiliate-link',
					'header-scripts',
					'footer-scripts',
					'active-skin',
					'featured-posts',
					'small-excerpts',
					'disable-excerpts',
					'post-thumbnail-width',
					'post-thumbnail-height',
					'hide-post-thumbnail-on-single',
					'post-above-title-left',
					'post-above-title-right',
					'post-below-title-left',
					'post-below-title-right',
					'post-below-content-left',
					'post-below-content-right',
					'post-date-format',
					'post-comment-format-0',
					'post-comment-format-1',
					'post-comment-format',
					'post-respond-format',
					'show-avatars',
					'page-comments',
					'default-avatar',
					'avatar-size',
					'read-more-text',
					'feed-exclude-cats',
					'enable-developer-mode',
					'gzip',
					'disable-caching',
					'js_libraries'
				);
				
				$content['what'] = $what;
				
				foreach($options as $option){
					$content['options'][$option] = headway_get_option($option);
				}
				
				$content = headway_json_encode($content);
			break;
			
			case 'seo-settings':
				$filename = 'SEO_Settings';
				$what = 'seo-settings';
				
				$options = array(
					'title-home',
					'title-posts-page',
					'title-page',
					'title-single',
					'title-404',
					'title-category',
					'title-tag',
					'title-archives',
					'title-search',
					'title-author-archives',
					'home-description',
					'home-keywords',
					'categories-meta',
					'tags-meta',
					'canonical',
					'nofollow-comment-author',
					'nofollow-home',
					'noindex-category-archives',
					'noindex-archives',
					'noindex-tag-archives',
					'noindex-author-archives',
					'seo-slugs',
					'seo-slugs-numbers',
					'seo-slug-bad-words'
				);
				
				$content['what'] = $what;
				
				foreach($options as $option){
					$content['options'][$option] = headway_get_option($option);
				}
				
				$content = headway_json_encode($content);
			break;
			
			case 'visual-editor-settings':
				$filename = 'Visual_Editor_Settings';
				$what = 'visual-editor-settings';
			
				$options = array(
					'leaf-columns-border-style',
					'leaf-columns-border-color',
					'enable-header-resizing',
					'header-image-margin',
					'show-tagline',
					'show-navigation',
					'show-header-search-bar',
					'show-header-rss-link',
					'show-breadcrumbs',
					'header-style',
					'header-order',
					'navigation-position',
					'show-navigation-subpages',
					'sub-nav-width',
					'hide-home-link',
					'home-link-text',
					'footer-style',
					'show-admin-link',
					'show-edit-link',
					'show-go-to-top-link',
					'show-copyright',
					'hide-headway-attribution',
					'custom-copyright',
					'wrapper-width',
					'wrapper-vertical-margin',
					'wrapper-border-radius',
					'leaf-margins',
					'leaf-padding',
					'leaf-container-horizontal-padding',
					'leaf-container-vertical-padding'
				);
			
				$content['what'] = $what;
			
				foreach($options as $option){
					$content['options'][$option] = headway_get_option($option);
				}
			
				$content = headway_json_encode($content);
			break;
			
			case 'all-settings':
				global $wpdb;
				$headway_options_table = $wpdb->prefix.'headway_options';
			
				$filename = get_bloginfo('name').' - '.date_i18n('M j, Y - g:i A');
				$what = 'all-settings';
				
				$content['what'] = $what;
			
				$options = $wpdb->get_results("SELECT * FROM $headway_options_table", ARRAY_A);
			
				$badlist = array(
					'css-last-updated',
					'upgrade-155',
					'permissions-',
					'upgrade-',
					'watched-intro-16',
					'page_'
				);
			
				foreach($options as $option){
					
					$continue = false;
					
					foreach($badlist as $word){
						if(strpos($option['option'], $word) !== false) $continue = true;
					}
					
					if($continue == true) continue;
					
					$content['options'][$option['option']] = $option['value'];
				}
			
				$content = headway_json_encode($content);
			break;
		}
		
		header('Cache-Control: public');
		header('Content-Description: File Transfer');
		header('content-type: text/plain');
		header('Content-Transfer-Encoding: binary');
		header('Content-Disposition: attachment; filename="'.$filename.'.hwcfg"');
		
		echo $content;
	break;

	case 've-upload':
		if(!isset($_GET['qqfile']) || !isset($_GET['what'])) return false;
		
		require_once HEADWAYLIBRARY.'/visual-editor/misc/upload-class.php';
	
		//Will check and create folders if they don't exist
		headway_create_uploads_folders();
		
		$upload_path = wp_upload_dir();
		$sizeLimit = 24 * 1024 * 1024;
		$basepath = str_replace('//','/', $upload_path['basedir'].'/headway');
		
		switch($_GET['what']){
			case 'header':
				$allowedExtensions = array('jpg', 'jpeg', 'png', 'gif');
				$uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
				$result = $uploader->handleUpload($basepath.'/header-uploads/');
			break;
			
			case 'background':
				$allowedExtensions = array('jpg', 'jpeg', 'png', 'gif');
				$uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
				$result = $uploader->handleUpload($basepath.'/background-uploads/');
			break;
			
			case 'style':
				$allowedExtensions = array('hwstyle', 'txt');
				$uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
				$result = $uploader->handleUpload($upload_path['basedir'].'/');
			break;
			
			case 'leaf-template':
				$allowedExtensions = array('hwtpl');
				$uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
				$result = $uploader->handleUpload($upload_path['basedir'].'/');
			break;
		}
		
		echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
	break;
}