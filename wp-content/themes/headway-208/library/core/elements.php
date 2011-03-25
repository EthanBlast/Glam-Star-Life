<?php
/**
 * Elements and functions for the visual editor.
 *
 * @package Headway
 * @subpackage Visual Editor Elements
 * @author Clay Griffiths
 **/


/**
 * Returns all of the elements Headway uses in array format.
 * 
 * @return array
 **/
function headway_get_elements(){
	$elements_array = array(		
			'Site' => array(
				array(
					'body',
				   	'Body',   
				   	array('background'),
					false,
					false,
					'body',
					true
				), 

				array(
					'div#wrapper',
				   	'Wrapper',
				   	array('background', 'border')
				)
			),    

			'Header' => array(                                                     
				array(
					'div#header',
				    'Header',  
				    array('background', 'bottom-border', 'top-border'),
					false,
					false,
					'body.header-fluid div#header, body.header-fixed div#header'
				),
				
				array(
					'div#header-container',
					'Header Container (Fluid Only)',
					array('background', 'bottom-border', 'top-border'),
					false,
					false,
					'body.header-fluid div#header-container'
				),    

				array(
					'.header-link-text-inside',
					'Header Site Name',
					array('color' => 'primary', 'bottom-border'),
					array('title' => true),
					array('styling', 'capitalization', 'letter-spacing'),
					'a.header-link-text-inside'
				),
				
				array(
					'.header-link-text-inside:hover',
					'Header Site Name &mdash; Hover',
					array('color' => 'primary'),
					false,
					array('styling'),
					'a.header-link-text-inside:hover'
				),

				array(
					'#tagline',
					'Tagline',
					array('color' => 'tertiary'),
					true,
					array('styling', 'capitalization', 'letter-spacing')
				),
				
				array(
					'div#header a#header-rss-link',
					'Header Subscribe Hyperlink',
					array('color'),
					true,
					array('styling')
				),
				
				array(
					'div#header a#header-rss-link:hover',
					'Header Subscribe Hyperlink &mdash; Hover',
					array('color'),
					false,
					array('styling')
				)
			),
			
			'Navigation' => array(        
				array(
					'div#navigation',
					'Navigation',
					array('background' => 'secondary', 'bottom-border', 'top-border'),
					false,
					false,
					'body.header-fluid div#navigation, body.header-fixed div#navigation'
				),
				
				array(
					'div#navigation-container',
					'Navigation Container (Fluid Only)',
					array('background' => 'secondary', 'bottom-border', 'top-border'),
					false,
					false,
					'body.header-fluid div#navigation-container'
				),
				
				array(
					'ul.navigation li a',
					'Navigation Item',
					array('color', 'background' => 'secondary', 'right-border'),
					true,
					array('styling', 'capitalization', 'letter-spacing'),
					'ul.navigation li a, ul.navigation li ul'
				),
				
				array(
					'ul.navigation li a:hover',
					'Navigation Item &mdash; Hover',
					array('color', 'background' => 'secondary'),
					false,
					array('styling')
				),
				    
				array(
					'ul.navigation li.current_page_item a',
					'Navigation Item &mdash; Active',
					array('color', 'background' => 'secondary', 'right-border'),
					true,
					array('styling', 'capitalization', 'letter-spacing'),
					'ul.navigation > li.current-menu-item > a, ul.navigation > li.current_page_item > a, ul.navigation > li.current_page_parent > a, ul.navigation > li.current_page_ancestor > a, ul.navigation li.current_page_ancestor ul, ul.navigation li.current_page_parent ul, ul.navigation li.current_page_item ul, ul.navigation li.current_page_item a:hover, ul.navigation li.current-menu-item a:hover'
				)
			),
				
			'Breadcrumbs' => array(
				array(
					'div#breadcrumbs',
					'Breadcrumbs',
					array('color', 'background', 'bottom-border', 'top-border'),
					true,
					true,
					'body.header-fixed div#breadcrumbs, body.header-fluid div#breadcrumbs'
				),
				
				array(
					'div#breadcrumbs-container',
					'Breadcrumbs Container (Fluid Only)',
					array('background', 'bottom-border', 'top-border')
				),
				
				array(
					'div#breadcrumbs a',
					'Breadcrumb Hyperlinks',
					array('color' => 'primary'),
					false,
					false,
					'div#breadcrumbs a'
				)
			),
			
			'Leafs' => array(
				array(
					'div.leaf-top',
					'Leaf Titles',
					array('color' => 'tertiary', 'background', 'bottom-border'),
					array('title' => true),
					true,
					'.leaf-top, div.headway-leaf input.inline-title-edit'
				),
				
				array(
					'div.leaf-top a',
					'Leaf Titles &mdash; Hyperlinks',
					array('color' => 'tertiary'),
					false,
					array('styling'),
					'.leaf-top a'
				),
				
				array(
					'div.leaf-top a:hover',
					'Leaf Titles &mdash; Hyperlinks &mdash; Hover',
					array('color' => 'tertiary'),
					false,
					array('styling'),
					'.leaf-top a:hover'
				),
				
				array(
					'div.headway-leaf',
					'Leafs',
					array('background')
				),
				
				array(
					'div.leaf-content',
					'Leaf Content',
					array('color'),
					true
				)
			),
			
			'Leaf Columns and Containers' => array(
				array(
					'div.leafs-column, div.leafs-container',
					'All Columns and Containers',
					array('border' => 'leaf-container')
				),
				
				array(
					'div.leafs-column-1',
					'Leaf Column #1',
					array('background'),
					false,
					false,
					false,
					true
				),	
				
				array(
					'div.leafs-column-2',
					'Leaf Column #2',
					array('background'),
					false,
					false,
					false,
					true
				),
				
				array(
					'div.leafs-column-3',
					'Leaf Column #3',
					array('background'),
					false,
					false,
					false,
					true
				),
				
				array(
					'div.leafs-column-4',
					'Leaf Column #4',
					array('background'),
					false,
					false,
					false,
					true
				),
				
				array(
					'div#top-container',
					'Top Leafs Container',
					array('background'),
					false,
					false,
					false,
					true
				),
				
				array(
					'div#bottom-container',
					'Bottom Leafs Container',
					array('background'),
					false,
					false,
					false,
					true
				)
			),
			
			'Posts/Pages' => array(
				array(
					'div.post',
					'Posts',
					array('bottom-border'),
					false,
					false,
					'div.post, div.small-excerpts-row'
				),
				
				array(
					'.page-title',
					'Page Title',
					array('color' => 'primary'),
					array('title' => true),
					true
				),
				
				array(
					'h2.entry-title, h1.entry-title',
					'Post Title',
					array('color' => 'primary'),
					array('title' => true),
					true,
					'.entry-title, .entry-title a'
				),
				
				array(
					'.entry-title a:hover',
					'Post Title &mdash; Hover',
					array('color'),
					false,
					array('styling')
				),

				array(
					'div.entry-content',
					'Post Content',
					array('color'),
					true
				),
				
				array(
					'div.entry-content a',
					'Post Content &mdash; Hyperlink',
					array('color' => 'primary'),
					false,
					array('styling')
				),
				
				array(
					'div.entry-content a:hover',
					'Post Content &mdash; Hyperlink &mdash; Hover',
					array('color' => 'primary'),
					false,
					array('styling')
				),
				
				array(
					'div.entry-content h1',
					'Post Content &mdash; H1',
					array('color' => 'secondary'),
					true,
					true
				),
				
				array(
					'div.entry-content h2',
					'Post Content &mdash; H2',
					array('color' => 'secondary'),
					true,
					true
				),
				
				array(
					'div.entry-content h3',
					'Post Content &mdash; H3',
					array('color' => 'primary'),
					true,
					true
				),
				
				array(
					'div.entry-content h4',
					'Post Content &mdash; H4',
					array('color' => 'tertiary'),
					true,
					true
				),
				
				array(
					'div.entry-content blockquote',
					'Blockquotes',
					array('color', 'top-border', 'bottom-border'),
					true,
					array('styling', 'capitalization', 'letter-spacing')
				),
				
				array(
					'div.entry-meta',
					'Post Meta',
					array('color'),
					true,
					array('styling', 'capitalization', 'letter-spacing')
				),
				
				array(
					'div.entry-meta a',
					'Post Meta &mdash; Hyperlinks',
					array('color'),
					false,
					array('styling')
				),
				
				array(
					'div.entry-meta a:hover',
					'Post Meta &mdash; Hyperlinks &mdash; Hover',
					array('color'),
					false,
					array('styling')
				),
				
				array(
					'a.more-link',
					'Read More Links',
					array('color', 'background'),
					true,
					array('styling', 'capitalization', 'letter-spacing'),
					'div.post a.more-link, div.featured-entry-content a.more-link'
				),
				
				array(
					'a.more-link:hover',
					'Read More Links &mdash; Hover',
					array('color', 'background'),
					false,
					array('styling'),
					'div.post a.more-link:hover, div.featured-entry-content a.more-link:hover'
				),
				
				array(
					'.nav-below a',
					'Next/Previous Links',
					array('color', 'background'),
					true,
					array('styling', 'capitalization', 'letter-spacing'),
					'div.nav-below div.nav-previous a, div.nav-below div.nav-next a'
				),
				
				array(
					'.nav-below a:hover',
					'Next/Previous Links &mdash; Hover',
					array('color', 'background'),
					false,
					array('styling'),
					'div.nav-below div.nav-previous a:hover, div.nav-below div.nav-next a:hover'
				)
			),
			
			'Comments/Trackbacks' => array(
				array(
					'span.heading',
					'Comment Area Headings',
					array('color' => 'secondary'),
					true,
					true
				),
				
				array(
					'ol.commentlist',
					'Comment Area',
					array('background', 'border')
				),
				
				array(
					'ol.commentlist li',
					'Comments',
					array('background', 'bottom-border')
				),
				
				array(
					'ol.commentlist li.even',
					'Comments (Even)',
					array('background')
				),
				
				array(
					'span.comment-author',
					'Comment Author',
					array('color'),
					true,
					true
				),
				
				array(
					'span.comment-author a',
					'Comment Author &mdash; Hyperlink',
					array('color'),
					false,
					array('styling')
				),
				
				array(
					'span.comment-author a:hover',
					'Comment Author &mdash; Hyperlink &mdash; Hover',
					array('color'),
					false,
					array('styling')
				),
				
				array(
					'div.comment-date',
					'Comment Date',
					array('color'),
					true,
					true
				),
				
				array(
					'div.comment-body',
					'Comment Content',
					array('color'),
					true
				),
				
				array(
					'img.avatar',
					'Commenter Avatar',
					array('background', 'border')
				),
				
				array(
					'div#trackback-box',
					'Trackback Box',
					array('background', 'border')
				),
				
				array(
					'div#trackback-box span#trackback',
					'Trackback Heading',
					array('color'),
					true,
					true
				),
				
				array(
					'div#trackback-box span#trackback-url',
					'Trackback URL',
					array('color'),
					true,
					true
				)
			),
			
			
			'Leafs &mdash; Sidebar' => array(
				array(
					'div.sidebar',
					'Sidebar',
					array('background')
				),
				
				array(
					'div.sidebar li.widget',
					'Widget',
					array('background', 'color'),
					true
				),
				
				array(
					'div.sidebar span.widget-title',
					'Widget Title',
					array('color' => 'tertiary', 'background', 'bottom-border'),
					array('title' => true),
					true
				),
				
				array(
					'div.sidebar a',
					'Widget Content &mdash; Hyperlinks',
					array('color'),
					false,
					array('styling'),
					'div.sidebar a, ul.link-list a'
				),
				
				array(
					'div.sidebar a:hover',
					'Widget Content &mdash; Hyperlinks &mdash; Hover',
					array('color'),
					false,
					array('styling'),
					'div.sidebar a:hover, ul.link-list a:hover'
				)
			),
			
			'Leafs &mdash; Image Rotator' => array(
				array(
					'div.rotator-images img',
					'Image Rotator &mdash; Image',
					array('border')
				)
			),
			
			
			'Footer' => array(
				array(
					'div#footer',
					'Footer',
					array('color', 'background', 'top-border'),
					true,
					false,
					'body.footer-fixed div#footer, body.footer-fluid div#footer, body.footer-fluid div#footer-container'
				),
				
				array(
					'div#footer a',
					'Footer &mdash; Hyperlinks',
					array('color'),
					false,
					array('styling'),
					'div#footer a'
				),
				
				array(
					'div#footer a:hover',
					'Footer &mdash; Hyperlinks &mdash; Hover',
					array('color'),
					false,
					array('styling')
				)
			)
	);
	
	global $headway_custom_elements;
	if($headway_custom_elements) $elements_array['Custom'] = $headway_custom_elements;
	
	return $elements_array;
}


/**
 * Changes a CSS selector to an attribute-safe string.
 *
 * @param string $selector
 * 
 * @return string
 **/
function headway_selector_to_form_name($selector){
	return str_replace(',', '-comma-', str_replace('.', '-period-', str_replace('#', '-pound-', str_replace(' ', '-space-', str_replace(':', '-colon-', $selector)))));
}


/**
 * Changes the form name back to a CSS selector.
 *
 * @param string $name
 * 
 * @return string
 **/
function headway_form_name_to_selector($name){
	return str_replace('-comma-', ',', str_replace('-period-', '.', str_replace('-pound-', '#', str_replace('-space-', ' ', str_replace('-colon-', ':', $name)))));
}