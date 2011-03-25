<?php
//////Upgrade for 1.6/////

global $wpdb;

$headway_leafs_table = $wpdb->prefix.'headway_leafs';
$elements_table = $wpdb->prefix.'headway_elements';

$leafs = $wpdb->get_results("SELECT * FROM $headway_leafs_table", ARRAY_A);	

foreach($leafs as $leaf){
	$config = maybe_unserialize($leaf['config']);

	$config['title'] = base64_encode($config['title']);

	headway_update_leaf($leaf['id'], false, $config);
}
	
$wpdb->query("UPDATE $elements_table SET element = 'div-period-headway-leaf' WHERE element = 'div-period-box';");

headway_update_element_style(headway_selector_to_form_name('div.entry-content blockquote'), 'color', 'border-top', '999999', false);
headway_update_element_style(headway_selector_to_form_name('div.entry-content blockquote'), 'color', 'border-bottom', '999999', false);
headway_update_element_style(headway_selector_to_form_name('div.entry-content blockquote'), 'color', 'color', '666666', false);

headway_update_element_style(headway_selector_to_form_name('div.entry-content blockquote'), 'sizing', 'border-top-width', '1', false);
headway_update_element_style(headway_selector_to_form_name('div.entry-content blockquote'), 'sizing', 'border-bottom-width', '1', false);

headway_update_element_style(headway_selector_to_form_name('div.entry-content blockquote'), 'font', 'font-family', 'verdana, sans-serif', false);
headway_update_element_style(headway_selector_to_form_name('div.entry-content blockquote'), 'font', 'font-size', '12', false);
headway_update_element_style(headway_selector_to_form_name('div.entry-content blockquote'), 'font', 'line-height', '20', false);


headway_update_element_style(headway_selector_to_form_name('div#trackback-box span#trackback'), 'color', 'color', '444444', false);		
headway_update_element_style(headway_selector_to_form_name('div#trackback-box span#trackback'), 'font', 'font-family', 'verdana, sans-serif', false);
headway_update_element_style(headway_selector_to_form_name('div#trackback-box span#trackback'), 'font', 'font-size', '16', false);
headway_update_element_style(headway_selector_to_form_name('div#trackback-box span#trackback'), 'font', 'line-height', '16', false);

headway_update_element_style(headway_selector_to_form_name('div#trackback-box span#trackback-url'), 'color', 'color', '777777', false);		
headway_update_element_style(headway_selector_to_form_name('div#trackback-box span#trackback-url'), 'font', 'font-family', 'verdana, sans-serif', false);
headway_update_element_style(headway_selector_to_form_name('div#trackback-box span#trackback-url'), 'font', 'font-size', '10', false);
headway_update_element_style(headway_selector_to_form_name('div#trackback-box span#trackback-url'), 'font', 'line-height', '12', false);

headway_update_option('enable-developer-mode', headway_get_option('disable-visual-editor'));
headway_delete_option('disable-visual-editor');

headway_update_option('sub-nav-width', '250');

headway_update_option('print-css', 'on');

headway_update_option('seo-slugs', 'on');
headway_update_option('seo-slug-bad-words', base64_decode('YQ0KYW4NCmFsc28NCmFuZA0KYW5vdGhlcg0KYXJlDQpmZWF0dXJlZA0KaW4NCmlzDQppdA0KbmV3DQpvdXINCnBhZ2UNCnRoZQ0KdGhpcw0KdG8NCnRvcA0KdXMNCndlDQp3aGF0DQp3aXRoDQp5b3U='));

if(headway_get_option('disable-header-resizing')){
	headway_delete_option('enable-header-resizing');
} else {
	headway_update_option('enable-header-resizing', 'true');
}

// Create the first sizing property.  Without this, the visual editor won't load due to JSON screw up. 
if(!headway_get_element_styles(false, 'sizing')){
	global $wpdb;
	$headway_elements_table = $wpdb->prefix.'headway_elements';
	$wpdb->query("INSERT INTO `$headway_elements_table` (`element`, `property_type`, `property`, `value`) VALUES('wrapper', 'sizing', 'border-all-width', '3');");  
}

// Create navigation position option.
if(!headway_get_option('navigation-position')){
	headway_update_option('navigation-position', 'left');
}

if(!headway_get_option('post-thumbnail-width')){
	headway_update_option('post-thumbnail-width', '200');
}

if(!headway_get_option('post-thumbnail-height')){
	headway_update_option('post-thumbnail-height', '200');
}

if(!headway_get_option('read-more-text')){
	headway_update_option('read-more-text', 'Continue Reading &raquo;');
}