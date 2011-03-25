<?php
function headway_get_hooks(){
	$headway_hooks = array(
		'Main Hooks' => array(
			array('before-everything', 'Before Everything', 'Will be placed right after the <code>&lt;body&gt;</code> tag is opened.'),
			array('after-everything', 'After Everything', 'Will be placed right before the <code>&lt;body&gt;</code> tag is closed.'),
			array('page-start', 'Page Start', 'Placed after the header, navigation, and breadcrumbs, but before all the leafs and footer.')
		),
		
		'Wrapper' => array(
			array('wrapper-open', 'Wrapper Open', 'Placed right after the wrapper <code>&lt;div&gt;</code> is opened.'),
			array('wrapper-close', 'Wrapper Close', 'Placed right after the wrapper <code>&lt;div&gt;</code> is closed.'),
			array('whitewrap-open', 'Whitewrap Open', 'Placed right after the whitewrap <code>&lt;div&gt;</code> is opened.'),
			array('whitewrap-close', 'Whitewrap Close', 'Placed right after the whitewrap <code>&lt;div&gt;</code> is closed.'),
		),

		'Header' => array(
			array('before-header-link', 'Before Header Link', 'Before the header link.  This is either the name of the site or the logo you place there.'),
			array('after-header-link', 'After Header Link', 'After the header link.  This is either the name of the site or the logo you place there.'),
			array('after-tagline', 'After Tagline', 'After the tagline.  This is generally the slogan that will show up in your header.')			
		),

		'Navigation' => array(
			array('before-navigation', 'Before Navigation', 'Will be displayed before the navigation in the header.'),
			array('after-navigation', 'After Navigation', 'Will be displayed after the navigation in the header.'),
			array('navigation-open', 'Navigation Open', 'Inject code right after the navigation container is opened.'),
			array('navigation-close', 'Navigation Close', 'Inject code right after the navigation container is closed.'),
			array('navigation-inside-open', 'Navigation Inside Open', 'Inject code right after the navigation <code>&lt;ul&gt;</code> is opened.', true),
			array('navigation-inside-close', 'Navigation Inside Close', 'Inject code right after the navigation <code>&lt;ul&gt;</code> is closed.', true),
		),

		'Breadcrumbs' => array(
			array('before-breadcrumbs', 'Before Breadcrumbs', 'Will be displayed before the breadcrumbs in the header.'),
			array('after-breadcrumbs', 'After Breadcrumbs', 'Will be displayed after the breadcrumbs in the header.'),
			array('breadcrumbs-open', 'Breadcrumbs Open', 'Inject code right after the breadcrumbs container is opened.'),
			array('breadcrumbs-close', 'Breadcrumbs Close', 'Inject code right after the breadcrumbs container is closed.')		
		),
		
		'Leafs' => array(
			array('before-leaf', 'Before Leaf', 'This will be placed before each leaf.'),
			array('before-leaf-content', 'Before Leaf Content', 'Placed at the top of each leaf inside the leaf-content <code>&lt;div&gt;</code>.  This will generally be under the leaf\'s title.'),
			array('after-leaf-content', 'After Leaf Content', 'Will be placed at the bottom of each leaf inside the leaf-content <code>&lt;div&gt;</code>.'),
			array('after-leaf', 'After Leaf', 'This will be placed after each leaf.')			
		), 
		
		'Leaf Containers/Columns' => array(
			array('leaf-container-open', 'Leaf Container Open', 'Placed right after a leaf container (not column) is opened.'),
			array('leaf-container-close', 'Leaf Container Close', 'Placed before after a leaf container (not column) is closed.'),
			array('leaf-column-open', 'Leaf Column Open', 'Placed right after a leaf column is opened.'),
			array('leaf-column-close', 'Leaf Column Close', 'Placed right before a leaf column is closed.')
		),
		
		'Featured/Single Posts' => array(
			array('before-post', 'Before Featured/Single Post', 'Goes before each featured/single post.'),
			array('before-post-title', 'Before Featured/Single Post Title', 'Goes before the post title, and before the post meta (categories, etc.) if you choose to place any up there.'),
			array('after-post-title', 'After Featured/Single Post Title', 'Goes after the meta below the title and the title.'),
			array('before-post-content', 'Before Featured/Single Post Content', 'Goes before the main text body of the post.'),
			array('after-post-content', 'After Featured/Single Post Content', 'Goes after the main text body of the post.'),
			array('after-post', 'After Featured/Single Post', 'Goes after each featured/single post.'),
		),
		
		'Excerpts' => array(
			array('before-excerpt', 'Before Excerpt', 'Goes before each excerpt.'),
			array('before-excerpt-title', 'Before Excerpt Title', 'Goes before the excerpt title, and before the post meta (categories, etc.) if you choose to place any up there.'),
			array('after-excerpt-title', 'After Excerpt Title', 'Goes after the meta below the title and the title.'),
			array('before-excerpt-content', 'Before Excerpt Content', 'Goes before the main text body of the excerpt.'),
			array('after-excerpt-content', 'After Excerpt Content', 'Goes after the main text body of the excerpt.'),
			array('after-excerpt', 'After Excerpt', 'Goes after each excerpt.'),
		),
		
		'Pages' => array(
			array('before-page', 'Before Page', 'Goes before the page being displayed (in content leaf).'),
			array('before-page-title', 'Before Page Title', 'Goes before the page title.'),
			array('after-page-title', 'After Page Title', 'Goes after the page title.'),
			array('before-page-content', 'Before Page Content', 'Goes before the main text body of the page.'),
			array('after-page-content', 'After Page Content', 'Goes after the main text body of the page.'),
			array('after-page', 'After Page', 'Goes after the page being displayed (in content leaf).')
		),
		
		'Comments' => array(
			array('before-comments', 'Before Comments', 'Goes before the comments and comment reply form.'),
			array('before-comment-form', 'Before Comment Form', 'Goes before the comment reply form, but after comments.'),
			array('after-comments', 'After Comments', 'Goes after the comments and comment reply form.'),
		),
		
		'Sidebars' => array(
			array('before-sidebar', 'Before Sidebar', 'Will be placed before all sidebar leafs.'),
			array('after-sidebar', 'After Sidebar', 'Will be placed after all sidebar leafs.'),
		),
		
		'Footer' => array(
			array('footer-open', 'Footer Open', 'Will be placed right after the footer is opened.', false, 1),
			array('before-copyright', 'Before Copyright', 'Will be placed right before the copyright is displayed.'),
			array('footer-close', 'Footer Close', 'Will be placed right before the footer is closed.', false, 12)
		)
	);
	
	return $headway_hooks;
}

function headway_setup_easy_hooks(){
	foreach(headway_get_hooks() as $group => $hooks){
		foreach($hooks as $hook){
			//If the easy hook doesn't have any content, don't bother wasting memory creating functions
			if(!headway_get_option('easy-hooks-'.$hook[0])) continue;
			
			//Set up priority
			$hook[4] = isset($hook[4]) ? $hook[4] : 10;
			
			//If the hook is a filter, use return instead of echo and add_filter instead of add_action.
			if(isset($hook[3]) && $hook[3] === true){
				$function_hooks[$hook[0]] = create_function(false, "return do_shortcode(headway_parse_php(stripslashes(headway_get_option('easy-hooks-$hook[0]'))));");
				add_filter('headway_'.str_replace('-', '_', $hook[0]), $function_hooks[$hook[0]], $hook[4]);
			} else {
				$function_hooks[$hook[0]] = create_function(false, "echo do_shortcode(headway_parse_php(stripslashes(headway_get_option('easy-hooks-$hook[0]'))));");
				add_action('headway_'.str_replace('-', '_', $hook[0]), $function_hooks[$hook[0]], $hook[4]);
			}
		}
	}
}
add_action('init', 'headway_setup_easy_hooks');