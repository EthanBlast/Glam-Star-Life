<?php
/**
 * Sets up the Headway tables and fills data.
 * 
 * @package Headway
 * @subpackage Installation and Upgrading
 * @author Clay Griffiths
 **/


/**
 * Creates the tables and starts the elements table off with some data.
 * 
 * @global object $wpdb
 **/
function headway_install_build_tables(){
	global $wpdb;
		
	$headway_elements_table = $wpdb->prefix.'headway_elements';
	$headway_leafs_table = $wpdb->prefix.'headway_leafs';
	$headway_options_table = $wpdb->prefix.'headway_options';
	
	$charset_collate = '';
	
	if ( ! empty($wpdb->charset) )
		$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
	if ( ! empty($wpdb->collate) )
		$charset_collate .= " COLLATE $wpdb->collate";

	$create_elements_table = "CREATE TABLE $headway_elements_table (
		  `id` int(11) NOT NULL auto_increment,
		  `element` varchar(100) NOT NULL,
		  `property_type` varchar(10) NOT NULL,
		  `property` varchar(100) NOT NULL,
		  `value` varchar(100) NOT NULL,
		  PRIMARY KEY  (`id`)
		) $charset_collate;";
	
	$create_leafs_table = "CREATE TABLE $headway_leafs_table (
		  `id` int(11) NOT NULL auto_increment,
		  `page` varchar(255) NOT NULL,
		  `type` varchar(50) NOT NULL,
		  `position` int(11) NOT NULL,
		  `container` varchar(50) NOT NULL,
		  `config` text NOT NULL,
		  `options` text NOT NULL,
		  PRIMARY KEY  (`id`)
		) $charset_collate;";
	
	$create_options_table = "CREATE TABLE $headway_options_table (
		  `id` int(11) NOT NULL auto_increment,
		  `option` varchar(255) NOT NULL,
		  `value` text NOT NULL,
		  PRIMARY KEY  (`id`)
		) $charset_collate;";
		
							
	$wpdb->query($create_elements_table);		
	$wpdb->query($create_leafs_table);		
	$wpdb->query($create_options_table);
			
}


/**
 * Creates the content and sidebar leaf for the specified page.
 *
 * @param mixed $page
 * @param mixed $page_404_id The ID of the Whoops 404 Page!
 **/
function headway_install_create_default_leafs($page, $page_404_id = false){
	if($page == 'index'){
		
		headway_add_leaf('index', 
			array(
				'position' => 1,
				'type' => 'content',
				'config' => array(
					'title' => 'Content',
					'show-title' => false,
					'title-link' => false,
					'width' => 640,
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
		
		headway_add_leaf('index', 
			array(
				'position' => 2,
				'type' => 'sidebar',
				'config' => array(
					'title' => 'Primary Sidebar',
					'show-title' => false,
					'title-link' => false,
					'width' => 250,
					'height' => 125,
					'fluid-height' => true,
					'align'=> 'left',
					'custom-classes' => false
				),
				'options' => array(
					'duplicate-id' => false,
					'sidebar-name' => 'Primary Sidebar'
				)
			)
		);

	} elseif($page == 'four04'){
		
		headway_add_leaf('four04', 
			array(
				'position' => 1,
				'type' => 'content',
				'config' => array(
					'title' => 'Content',
					'show-title' => false,
					'title-link' => false,
					'width' => 640,
					'height' => 125,
					'fluid-height' => true,
					'align' => 'left',
					'custom-classes' => false
				),
				'options' => array(
					'mode' => 'page',
					'other-page' => $page_404_id,
					'categories-mode' => 'include',
					'post-limit' => get_option('posts_per_page'),
					'featured-posts' => 1,
					'paginate' => true
				)
			)
		);
		
		headway_add_leaf('four04', 
			array(
				'position' => 2,
				'type' => 'sidebar',
				'config' => array(
					'title' => 'Primary Sidebar',
					'show-title' => false,
					'title-link' => false,
					'width' => 250,
					'height' => 125,
					'fluid-height' => true,
					'align'=> 'left',
					'custom-classes' => false
				),
				'options' => array(
					'duplicate-id' => 2
				)
			)
		);
		
	} elseif(is_numeric($page)){
		
		headway_add_leaf($page, 
			array(
				'position' => 1,
				'type' => 'content',
				'config' => array(
					'title' => 'Content',
					'show-title' => false,
					'title-link' => false,
					'width' => 920,
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
		
	} else {
		
		headway_add_leaf($page, 
			array(
				'position' => 1,
				'type' => 'content',
				'config' => array(
					'title' => 'Content',
					'show-title' => false,
					'title-link' => false,
					'width' => 640,
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
		
		headway_add_leaf($page, 
			array(
				'position' => 2,
				'type' => 'sidebar',
				'config' => array(
					'title' => 'Primary Sidebar',
					'show-title' => false,
					'title-link' => false,
					'width' => 250,
					'height' => 125,
					'fluid-height' => true,
					'align'=> 'left',
					'custom-classes' => false
				),
				'options' => array(
					'duplicate-id' => 2
				)
			)
		);

	}
	

}


function headway_install_set_options(){
	headway_update_option('gzip', 1);
	headway_update_option('header-order', array('header', 'navigation', 'breadcrumbs'));
	headway_update_option('show-navigation', 'on');
	headway_update_option('show-breadcrumbs', 'on');
	headway_update_option('header-image-margin', '0px');
	headway_update_option('header-style', 'fixed');
	headway_update_option('show-tagline', 'on');
	headway_update_option('show-header-rss-link', 'on');
	headway_update_option('show-header-search-bar', 'on');		
	headway_update_option('wrapper-width', 960);
	headway_update_option('wrapper-vertical-margin', 30);
	headway_update_option('home-link-text', 'Home');
	headway_update_option('footer-style', 'fixed');

	headway_update_option('post-date-format', 'wp');
	headway_update_option('post-comment-format-0', '%num% Comments');
	headway_update_option('post-comment-format-1', '%num% Comment');
	headway_update_option('post-comment-format', '%num% Comments');
	headway_update_option('post-respond-format', 'Leave a comment!');
	
	headway_add_option('post-above-title-left', '');
	headway_add_option('post-above-title-right', '');
	headway_update_option('post-below-title-left', 'Written on %date% by %author% in %categories%');
	headway_update_option('post-below-title-right', '');
	headway_update_option('post-below-content-left', '%comments% - %respond%');
	headway_update_option('post-below-content-right', '%edit%');
	
	headway_update_option('featured-posts', 1);
	headway_update_option('show-avatars', 'on');
	headway_update_option('avatar-size', 48);
		
	headway_update_option('show-admin-link', 'on');
	headway_update_option('show-edit-link', 'on');
	headway_update_option('show-go-to-top-link', 'on');
	headway_update_option('show-copyright', 'on');
	
	headway_update_option('show-navigation-subpages', 'on');
	
	headway_update_option('title-home', '%tagline% | %blogname%');
	headway_update_option('title-page', '%page% | %blogname%');
	headway_update_option('title-posts-page', 'Blog | %blogname%');
	headway_update_option('title-single', '%postname% | %blogname%');
	headway_update_option('title-404', 'Whoops! 404 Error | %blogname%');
	headway_update_option('title-category', '%category% | %blogname%');
	headway_update_option('title-tag', '%tag% | %blogname%');
	headway_update_option('title-archives', '%archive% | %blogname%');
	headway_update_option('title-search', 'Search For: %search% | %blogname%');
	headway_update_option('title-author-archives', '%author_name% | %blogname%');

	headway_update_option('categories-meta', 0);
	headway_update_option('tags-meta', 0);
	headway_update_option('canonical', 1);
	headway_update_option('nofollow-comment-author', 1);
	headway_update_option('nofollow-home', 1);
	headway_update_option('noindex-category-archives', 0);
	headway_update_option('noindex-archives', 0);
	headway_update_option('noindex-tag-archives', 0);
	headway_update_option('noindex-author-archives', 0);
	
	headway_update_option('enable-developer-mode', false);
	
	headway_update_option('sub-nav-width', 250);
	
	headway_update_option('print-css', 'on');
	
	headway_update_option('seo-slugs', 'on');
	headway_update_option('seo-slug-bad-words', base64_decode('YQ0KYW4NCmFsc28NCmFuZA0KYW5vdGhlcg0KYXJlDQpmZWF0dXJlZA0KaW4NCmlzDQppdA0KbmV3DQpvdXINCnBhZ2UNCnRoZQ0KdGhpcw0KdG8NCnRvcA0KdXMNCndlDQp3aGF0DQp3aXRoDQp5b3U='));
	headway_update_option('seo-slugs-numbers', 'true');
	
	headway_update_option('navigation-position', 'left');
	headway_update_option('post-thumbnail-width', '200');
	headway_update_option('post-thumbnail-height', '200');
	headway_update_option('read-more-text', 'Continue Reading &raquo;');
	
	headway_update_option('leaf-margins', 5);
	headway_update_option('leaf-padding', 10);
	headway_update_option('leaf-container-horizontal-padding', 5);
	headway_update_option('leaf-container-vertical-padding', 5);

	headway_update_option('wrapper-vertical-margin', 30);
	headway_update_option('wrapper-border-radius', 0);

	headway_update_option('leaf-border-radius', 0);
}


function headway_upgrade(){	
	if(headway_versionify(get_option('headway-version')) >= headway_versionify(HEADWAYVERSION)) return false;
	
	if(headway_get_option('upgrade-15', true, true) && !headway_get_option('upgrade-155'))
		require_once HEADWAYLIBRARY.'/installation/upgrade/1.5.5.php';
		
	if(headway_get_option('upgrade-155', true, true) && !headway_get_option('upgrade-16'))
		require_once HEADWAYLIBRARY.'/installation/upgrade/1.6.php';
		
	if(headway_get_option('upgrade-16', true, true) && !headway_get_option('upgrade-161'))
		require_once HEADWAYLIBRARY.'/installation/upgrade/1.6.1.php';
		
	if(headway_get_option('upgrade-161', true, true) && !headway_get_option('upgrade-165'))
		require_once HEADWAYLIBRARY.'/installation/upgrade/1.6.5.php';
					
	if(headway_versionify(get_option('headway-version')) < headway_versionify('1.7'))
		require_once HEADWAYLIBRARY.'/installation/upgrade/1.7.php';
		
	if(headway_versionify(get_option('headway-version')) < headway_versionify('2.0'))
		require_once HEADWAYLIBRARY.'/installation/upgrade/2.0.php';
		
	if(headway_versionify(get_option('headway-version')) < headway_versionify('2.0.1'))
		require_once HEADWAYLIBRARY.'/installation/upgrade/2.0.1.php';
		
	if(headway_versionify(get_option('headway-version')) < headway_versionify('2.0.3'))
		require_once HEADWAYLIBRARY.'/installation/upgrade/2.0.3.php';
		
	if(headway_versionify(get_option('headway-version')) < headway_versionify('2.0.4'))
		require_once HEADWAYLIBRARY.'/installation/upgrade/2.0.4.php';
	
	if(headway_versionify(get_option('headway-version')) < headway_versionify('2.0.5'))
		require_once HEADWAYLIBRARY.'/installation/upgrade/2.0.5.php';
		
	if(headway_versionify(get_option('headway-version')) < headway_versionify('2.0.6'))
		require_once HEADWAYLIBRARY.'/installation/upgrade/2.0.6.php';
		
	if(headway_versionify(get_option('headway-version')) < headway_versionify('2.0.7'))
		require_once HEADWAYLIBRARY.'/installation/upgrade/2.0.7.php';
	
	if(headway_versionify(get_option('headway-version')) < headway_versionify('2.0.8'))
		require_once HEADWAYLIBRARY.'/installation/upgrade/2.0.8.php';
	
	update_option('headway-installed', true);
	headway_update_option('headway_installed', true);
	
	headway_clear_cache();
}


/**
 * Runs all the functions in the core-installation.php file and starts adding options to the database.
 * 
 * @global object $wpdb
 **/
function headway_install(){
	if(!headway_get_option('headway_installed')){	
		if(is_multisite() && !is_main_site()){
			if(!is_dir(TEMPLATEPATH.'/custom/sites')) 
				@mkdir(TEMPLATEPATH.'/custom/sites');
			
			@mkdir(HEADWAYCUSTOM);
			@touch(HEADWAYCUSTOM.'/index.php');
			@touch(HEADWAYCUSTOM.'/custom.css');
			@touch(HEADWAYCUSTOM.'/custom_functions.php');
			@mkdir(HEADWAYCUSTOM.'/images');
			
			@mkdir(HEADWAYCACHE);
		}	
		
		headway_install_build_tables();
	
		headway_install_set_options();

		// Update existing pages
		$existing_pages = new WP_Query('post_type=page');
		while ($existing_pages->have_posts()) : $existing_pages->the_post();
			headway_install_create_default_leafs($existing_pages->post->ID);
		endwhile;
		
		// Set Up 404s
		if(!get_option('headway-installed')){
			$page_404 = array();
			$page_404['post_title'] = 'Whoops! 404 Error!';
			$page_404['post_content'] = 'It appears as if you entered in an invalid URL.  Please fix the URL you entered or try using the search functionality on our website.';
			$page_404['post_status'] = 'publish';
			$page_404['post_author'] = 1;
			$page_404['post_type'] = 'page';

			$page_404_id = wp_insert_post($page_404);	
			
			add_post_meta($page_404_id, '_404_page', true);	
			add_post_meta($page_404_id, '_noindex', true);	
		
			headway_update_option('excluded_pages', array($page_404_id));
		} else {
			$page_404_query = new WP_Query('post_type=page&meta_key=_404_page');	

			while($page_404_query->have_posts()){
				$page_404_query->the_post();

				$page_404_id = get_the_id();
			}

			headway_update_option('excluded_pages', array($page_404_id));
		}
		
		// Create leafs	
		headway_install_create_default_leafs('index');
		headway_install_create_default_leafs('four04', $page_404_id);
		headway_install_create_default_leafs('archives');
		headway_install_create_default_leafs('author');
		headway_install_create_default_leafs('search');
		headway_install_create_default_leafs('category');
		headway_install_create_default_leafs('single');
		headway_install_create_default_leafs('tag');
		
		headway_import_style(array('file' => HEADWAYLIBRARY.'/installation/styles/Magazine.hwstyle', 'no_delete' => true, 'switch_to_style' => true, 'add_upload_path' => false));
		headway_import_style(array('file' => HEADWAYLIBRARY.'/installation/styles/Feeling_Blue.hwstyle', 'no_delete' => true, 'add_upload_path' => false));
		headway_import_style(array('file' => HEADWAYLIBRARY.'/installation/styles/Cream.hwstyle', 'no_delete' => true, 'add_upload_path' => false));
		headway_import_style(array('file' => HEADWAYLIBRARY.'/installation/styles/Sky.hwstyle', 'no_delete' => true, 'add_upload_path' => false));
		
		// Create a time reference for stylesheets to be loaded off of.
		headway_update_option('css-last-updated', mktime());
	
		// Tell the DB that installation is complete.			
		update_option('headway-version', HEADWAYVERSION);
		update_option('headway-installed', true);
		headway_update_option('headway_installed', true);
		
		headway_clear_cache();
	}

	headway_upgrade();
	
	return true;
}

// Hook the install to WordPress.
add_action('init', 'headway_install', 5);