<?php
require_once 'meta-box-class.php';
global $post;

$Feed_Box['id'] = 'feed';
$Feed_Box['name'] = 'Feed';
$Feed_Box['options'] = array(
	array(
		'id' => 'headway_hide_from_feed',
		'name' => 'Hide From Feed',
		'type' => 'checkbox',
		'description' => 'By default, all posts will go to the feed for the blog.  If you wish, check this box to keep the post from going to the feed.'
	)
);
$Feed_Box['defaults'] = array();
$Feed_Box['type'] = 'post';
$Feed_Box['context'] = 'side';
$Feed_Box = new HeadwayMetaBox($Feed_Box);


$Image_Box['id'] = 'post-image';
$Image_Box['name'] = 'Post Thumbnail';
$Image_Box['options'] = array(
	array(
		'id' => 'image-alignment',
		'name' => 'Post Thumbnail Alignment',
		'type' => 'radio',
		'options' => array(
			'Right' => 'post-image-right',
			'Left' => 'post-image-left'
		),
		'description' => 'Set the placement of the post image/thumbnail for this post.'
	),
	array(
		'id' => 'disable-resizing',
		'name' => 'Disable Single Post Thumbnail Resizing',
		'type' => 'checkbox',
		'description' => 'Disable the resizing of the post thumbnail when viewing the post on the single post system page.'
	),
);
$Image_Box['defaults'] = array('image-alignment' => 'right', 'disable-resizing' => true);
$Image_Box['type'] = 'post';
$Image_Box['context'] = 'side';
$Image_Box = new HeadwayMetaBox($Image_Box);


$Template_Box['id'] = 'template_box';
$Template_Box['name'] = 'Leaf Template';
$Template_Box['options'] = array(
	array(
		'id' => 'leaf_template',
		'name' => 'Template',
		'type' => 'template-select',
		'description' => 'Select which leaf template you would like to load on this page.'
	)
);
$Template_Box['defaults'] = array();
$Template_Box['type'] = 'page';
$Template_Box['context'] = 'side';
$Template_Box['hidden_on_publish'] = true;
$Template_Box = new HeadwayMetaBox($Template_Box);


$Linking_Box['id'] = 'linking_box';
$Linking_Box['name'] = 'Page Linking';
$Linking_Box['options'] = array(
	array(
		'id' => 'leaf_template',
		'name' => 'Originating Page',
		'type' => 'page-select',
		'description' => 'Select a page you would like to copy (link) the leafs from.  Note: This will NOT copy the actual page content.'
	),
	array(
		'id' => 'leaf_system_template',
		'name' => 'Originating System Page',
		'type' => 'system-page-select',
		'description' => 'Select a system page you would like to copy (link) the leafs from.'
	)
);
$Linking_Box['defaults'] = array();
$Linking_Box['type'] = 'page';
$Linking_Box['context'] = 'side';
$Linking_Box = new HeadwayMetaBox($Linking_Box);


if(headway_nav_menu_check()){
	
	$Navigation_Box['id'] = 'navigation';
	$Navigation_Box['name'] = 'Navigation';
	$Navigation_Box['options'] = array(
		array(
			'id' => 'nav-menu',
			'name' => 'Navigation Menu',
			'type' => 'nav-menu',
			'description' => 'Choose which navigation menu you would like to display on this page.'
		)
	);
	$Navigation_Box['defaults'] = array();
	$Navigation_Box['type'] = 'page';
	$Navigation_Box['context'] = 'side';
	$Navigation_Box = new HeadwayMetaBox($Navigation_Box);
	
} else {
	
	$Navigation_Box['id'] = 'navigation';
	$Navigation_Box['name'] = 'Navigation';
	$Navigation_Box['options'] = array(
		array(
			'id' => 'show_navigation',
			'name' => 'Show In Navigation Bar',
			'type' => 'show-navigation',
			'description' => 'Show this page in the navigation bar.  You can also hide navigation items using the visual editor.'
		)
	);
	$Navigation_Box['defaults'] = array();
	$Navigation_Box['type'] = 'page';
	$Navigation_Box['context'] = 'side';
	$Navigation_Box = new HeadwayMetaBox($Navigation_Box);
	
}


$SEO_Box_Page['options'] = array();
$SEO_Box_Page['id'] = 'seo';
$SEO_Box_Page['name'] = 'Search Engine Optimization (SEO)';

if(isset($_GET['post']) && get_option('page_on_front') == $_GET['post'] && get_option('show_on_front') == 'page'){
	$SEO_Box_Page['info'] = '<strong>Configure the SEO settings for this page in the Headway Search Engine Optimization settings tab in <a href="'.get_bloginfo('wpurl').'/wp-admin/admin.php?page=headway#headway-seo" target="_blank">Headway &raquo; Configuration</a>.</strong>';
	
} else {
	if(defined('WPSEO_VERSION')){
		$SEO_Box_Page['info'] = '<strong>Headway has detected that you are using Yoast\'s WordPress SEO plugin.  In order to reduce conflicts, Headway\'s SEO functionality has been disabled.</strong>';
		
		$SEO_Box_Page['options'] = array(
			array(
				'id' => 'navigation_url',
				'name' => 'Redirect URL',
				'type' => 'text',
				'description' => 'Enter a destination URL that you would like this page to automatically redirect to.  <strong>This is ideal for masking affiliate links.</strong>'
			)
		);
	} elseif(class_exists('All_in_One_SEO_Pack')){
		$SEO_Box_Page['info'] = '<strong>Headway has detected that you are using the All In One SEO pack plugin.  In order to reduce conflicts, Headway\'s SEO functionality has been disabled.</strong>';
		
		$SEO_Box_Page['options'] = array(
			array(
				'id' => 'navigation_url',
				'name' => 'Redirect URL',
				'type' => 'text',
				'description' => 'Enter a destination URL that you would like this page to automatically redirect to.  <strong>This is ideal for masking affiliate links.</strong>'
			)
		);
	} else {
		$SEO_Box_Page['info'] = '<strong>Confused on what this is or how it works?  Head on over to the <a href="http://headwaythemes.com/documentation/creating-managing-content/headway-options-for-wordpress-posts/">In-Post Options » Search Engine Optimization</a> documentation.</strong>';
		
		$SEO_Box_Page['options'] = array(
			array(
				'id' => 'title',
				'name' => 'Title',
				'type' => 'text',
				'description' => 'Custom <code>&lt;title&gt;</code> tag'
			),
			array(
				'id' => 'description',
				'name' => 'Description',
				'type' => 'textarea',
				'description' => 'Custom <code>&lt;meta&gt;</code> description'
			),
			array(
				'id' => 'keywords',
				'name' => 'Keywords',
				'type' => 'text',
				'description' => 'Custom <code>&lt;meta&gt;</code> keywords'
			),
			array(
				'id' => 'noindex',
				'name' => '<code>noindex</code> this page.',
				'type' => 'checkbox'
			),
			array(
				'id' => 'nofollow_links',
				'name' => '<code>nofollow</code> links in this page.',
				'type' => 'checkbox'
			),
			array(
				'id' => 'nofollow_page',
				'name' => '<code>nofollow</code> navigation link to this page.',
				'type' => 'checkbox'
			),
			array(
				'id' => 'navigation_url',
				'name' => 'Redirect URL',
				'type' => 'text',
				'description' => 'Enter a destination URL that you would like this page to automatically redirect to.  <strong>This is ideal for masking affiliate links.</strong>'
			)
		);
	}
}

$SEO_Box_Page['defaults'] = array();
$SEO_Box_Page['type'] = 'page';
$SEO_Box_Page['priority'] = 'high';
$SEO_Box_Page = new HeadwayMetaBox($SEO_Box_Page);


if(!class_exists('All_in_One_SEO_Pack') && !defined('WPSEO_VERSION')){
	$SEO_Box['id'] = 'seo';
	$SEO_Box['name'] = 'Search Engine Optimization (SEO)';
	$SEO_Box['options'] = array(
		array(
			'id' => 'title',
			'name' => 'Title',
			'type' => 'text',
			'description' => 'Custom <code>&lt;title&gt;</code> tag'
		),
		array(
			'id' => 'description',
			'name' => 'Description',
			'type' => 'textarea',
			'description' => 'Custom <code>&lt;meta&gt;</code> description'
		),
		array(
			'id' => 'keywords',
			'name' => 'Keywords',
			'type' => 'text',
			'description' => 'Custom <code>&lt;meta&gt;</code> keywords.  Keyword stuffing will not get you results, so try to refrain from using more than 10 keywords.'
		),
		array(
			'id' => 'noindex',
			'name' => '<code>noindex</code> this page.',
			'type' => 'checkbox'
		),
		array(
			'id' => 'nofollow_links',
			'name' => '<code>nofollow</code> links in this page.',
			'type' => 'checkbox'
		)
	);
	$SEO_Box['defaults'] = array();
	$SEO_Box['info'] = '<strong>Confused on what this is or how it works?  Head on over to the <a href="http://headwaythemes.com/documentation/creating-managing-content/headway-options-for-wordpress-posts/">In-Post Options » Search Engine Optimization</a> documentation.</strong>';
	$SEO_Box['type'] = 'post';
	$SEO_Box['priority'] = 'high';
	$SEO_Box = new HeadwayMetaBox($SEO_Box);
}


$Dynamic_Content_Box['id'] = 'display';
$Dynamic_Content_Box['name'] = 'Display';
$Dynamic_Content_Box['options'] = array(
	array(
		'id' => 'dynamic-content',
		'name' => 'Dynamic Content',
		'type' => 'textarea',
		'description' => 'If you have a text leaf on the single posts template with dynamic content enabled, you can enter content here, such as targeted ads, and have the content of the text box vary for every post.'
	)
);
$Dynamic_Content_Box['defaults'] = array('');
$Dynamic_Content_Box['type'] = 'post';
$Dynamic_Content_Box = new HeadwayMetaBox($Dynamic_Content_Box);


$Page_Title_Box['id'] = 'page-title';
$Page_Title_Box['name'] = 'Page Title';
$Page_Title_Box['options'] = array(
	array(
		'id' => 'custom-title',
		'name' => 'Alternate Page Title',
		'type' => 'text',
		'description' => 'Using the alternate page title, you can override the title that\'s displayed in the content section [leaf] of the page.  Doing this, you can have a shorter page title in the navigation bar and <code>&lt;title&gt;</code>, but have a longer and more descriptive title in the actual page content.'
	),
	array(
		'id' => 'hide_title',
		'name' => 'Hide Page Title',
		'type' => 'checkbox',
		'description' => 'Use this to hide the page title in the content section [leaf] of the page.'
	)
);
$Page_Title_Box['defaults'] = array();
$Page_Title_Box['type'] = 'page';
$Page_Title_Box = new HeadwayMetaBox($Page_Title_Box);


$Header_Box_Page['id'] = 'header-elements';
$Header_Box_Page['name'] = 'Header Elements';
$Header_Box_Page['options'] = array(
	array(
		'id' => 'hide_header',
		'name' => 'Hide Header',
		'type' => 'checkbox',
		'description' => 'Hide the header for this page.'
	),
	array(
		'id' => 'hide_navigation',
		'name' => 'Hide Navigation',
		'type' => 'checkbox',
		'description' => 'Hide the navigation bar on this page.'
	),
	array(
		'id' => 'hide_breadcrumbs',
		'name' => 'Hide Breadcrumbs',
		'type' => 'checkbox',
		'description' => 'Hide the breadcrumbs on this page.'
	)
);
$Header_Box_Page['defaults'] = array(
		'hide_header' => '0',
		'hide_navigation' => '0',
		'hide_breadcrumbs' => '0'
);
$Header_Box_Page['type'] = 'page';
$Header_Box_Page = new HeadwayMetaBox($Header_Box_Page);



$Footer_Box_Page['id'] = 'footer-elements';
$Footer_Box_Page['name'] = 'Footer Elements';
$Footer_Box_Page['options'] = array(
	array(
		'id' => 'hide_footer',
		'name' => 'Hide Footer',
		'type' => 'checkbox',
		'description' => 'Hide the footer for this page.'
	)
);
$Footer_Box_Page['defaults'] = array(
		'hide_footer' => '0',
);
$Footer_Box_Page['type'] = 'page';
$Footer_Box_Page = new HeadwayMetaBox($Footer_Box_Page);



$Misc_Box['id'] = 'misc';
$Misc_Box['name'] = 'Miscellaneous';
$Misc_Box['options'] = array(
	array(
		'id' => 'hide_breadcrumbs',
		'name' => 'Hide Breadcrumbs',
		'type' => 'checkbox',
		'description' => 'Hide the breadcrumbs on this post.'
	)
);
$Misc_Box['defaults'] = array(
	'hide_breadcrumbs' => '0'
);
$Misc_Box['type'] = 'post';
$Misc_Box = new HeadwayMetaBox($Misc_Box);