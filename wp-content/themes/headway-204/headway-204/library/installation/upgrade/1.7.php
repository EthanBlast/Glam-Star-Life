<?php
//////Upgrade for 1.7/////

////////Alter leafs tables
global $wpdb;
$headway_leafs_table = $wpdb->prefix.'headway_leafs';

$wpdb->query("ALTER TABLE `$headway_leafs_table` DROP `system_page`");
$wpdb->query("ALTER TABLE `$headway_leafs_table` ADD `type` VARCHAR(50) NOT NULL AFTER `page`;");
$wpdb->query("ALTER TABLE `$headway_leafs_table` ADD `container` VARCHAR(50) NOT NULL AFTER `position`;");


////////Fix leaf types and leaf settings
$leafs = headway_get_all_leafs();

foreach($leafs as $leaf){
	$options = maybe_unserialize($leaf['options']);
	$config = maybe_unserialize($leaf['config']);

	$type = $config['type'];
	
	unset($config['type']);
	
	if($type == 'content'){
		$options['excerpts'] = $options['disable-excerpts'] ? 'disable' : 'default';
		
		unset($options['disable-excerpts']);
	}

	headway_update_leaf($leaf['id'], array('type' => $type, 'config' => $config, 'options' => $options));
}


////////Import styles
headway_import_style(array('file' => HEADWAYLIBRARY.'/installation/styles/Magazine.hwstyle', 'no_delete' => true, 'add_upload_path' => false));
headway_import_style(array('file' => HEADWAYLIBRARY.'/installation/styles/Feeling_Blue.hwstyle', 'no_delete' => true, 'add_upload_path' => false));
headway_import_style(array('file' => HEADWAYLIBRARY.'/installation/styles/Cream.hwstyle', 'no_delete' => true, 'add_upload_path' => false));
headway_import_style(array('file' => HEADWAYLIBRARY.'/installation/styles/Sky.hwstyle', 'no_delete' => true, 'add_upload_path' => false));


////////New Options
headway_update_option('leaf-margins', 5);
headway_update_option('leaf-padding', 10);
headway_update_option('leaf-container-horizontal-padding', 0);
headway_update_option('leaf-container-vertical-padding', 5);

headway_update_option('wrapper-vertical-margin', 30);
headway_update_option('wrapper-border-radius', 0);

headway_update_option('leaf-border-radius', 0);


////////Fix Line Heights and Element Selectors
$elements_table = $wpdb->prefix.'headway_elements';

$wpdb->query("UPDATE $elements_table SET element = 'div.comment-body' WHERE element = 'comment-body';");
$wpdb->query("UPDATE $elements_table SET element = 'div.comment-date' WHERE element = 'comment-date';");
$wpdb->query("UPDATE $elements_table SET element = 'ol.commentlist li.comment' WHERE element = 'olcommentlist_li-period-comment';");
$wpdb->query("UPDATE $elements_table SET element = 'div#trackback-box' WHERE element = 'trackback-box';");

$wpdb->query("DELETE FROM $elements_table WHERE element = 'entry-title';");
$wpdb->query("DELETE FROM $elements_table WHERE element = 'h2entry-title';");
$wpdb->query("DELETE FROM $elements_table WHERE element = 'trackback-box_spantrackback';");
$wpdb->query("DELETE FROM $elements_table WHERE element = 'trackback-box_spantrackback-url';");

$elements = headway_get_elements_cache('multi');

foreach($elements as $element => $property_type){	
	foreach($property_type as $property_type => $properties){		
		foreach($properties as $property => $value){
			headway_delete_element_style($element, $property_type, $property);
			headway_update_element_style(headway_form_name_to_selector($element), $property_type, $property, $value);
			
			if($property == 'font-size') $font_size = $value;
			if($property == 'line-height') $line_height = $value;
		}
	}
		
	if($line_height == 0 || $font_size == 0) continue;
	
	$line_height_percentage = round((int)$line_height/(int)$font_size, 1)*100;
		
	headway_update_element_style(headway_form_name_to_selector($element), 'font', 'line-height', $line_height_percentage);
}

////////Fix Old Border Properties
$wpdb->query("UPDATE $elements_table SET property = 'border-width' WHERE property = 'border-all-width';");
$wpdb->query("UPDATE $elements_table SET property = 'border' WHERE property = 'border-all';");

$wpdb->query("UPDATE $elements_table SET property = 'top-border' WHERE property = 'border-top';");
$wpdb->query("UPDATE $elements_table SET property = 'right-border' WHERE property = 'border-right';");
$wpdb->query("UPDATE $elements_table SET property = 'bottom-border' WHERE property = 'border-bottom';");
$wpdb->query("UPDATE $elements_table SET property = 'left-border' WHERE property = 'border-left';");

$wpdb->query("UPDATE $elements_table SET property = 'top-border-width' WHERE property = 'border-top-width';");
$wpdb->query("UPDATE $elements_table SET property = 'right-border-width' WHERE property = 'border-right-width';");
$wpdb->query("UPDATE $elements_table SET property = 'bottom-border-width' WHERE property = 'border-bottom-width';");
$wpdb->query("UPDATE $elements_table SET property = 'left-border-width' WHERE property = 'border-left-width';");





////////Fix Screwed Up Border Widths

//Get Cache Again
headway_get_elements_cache('multi');

//Header
if(!headway_get_element_property_value('sizing', 'div#header', 'border-bottom-width'))
	headway_update_element_style('div#header', 'sizing', 'border-bottom-width', 1);

if(!headway_get_element_property_value('sizing','div#header-container', 'border-bottom-width'))
	headway_update_element_style('div#header-container', 'sizing', 'border-bottom-width', 1);
	
if(!headway_get_element_property_value('sizing', '.header-link-text-inside', 'border-bottom-width'))
	headway_update_element_style('.header-link-text-inside', 'sizing', 'border-bottom-width', 1);

//Navigation
if(!headway_get_element_property_value('sizing', 'div#navigation', 'border-bottom-width'))
	headway_update_element_style('div#navigation', 'sizing', 'border-bottom-width', 1);

if(!headway_get_element_property_value('sizing', 'div#navigation-container', 'border-bottom-width'))
	headway_update_element_style('div#navigation-container', 'sizing', 'border-bottom-width', 1);

if(!headway_get_element_property_value('sizing', 'ul.navigation li a', 'border-right-width'))
	headway_update_element_style('ul.navigation li a', 'sizing', 'border-right-width', 1);

if(!headway_get_element_property_value('sizing', 'ul.navigation li.current_page_item a', 'border-right-width'))
	headway_update_element_style('ul.navigation li.current_page_item a', 'sizing', 'border-right-width', 1);
	
//Breadcrumbs
if(!headway_get_element_property_value('sizing', 'div#breadcrumbs', 'border-bottom-width'))
	headway_update_element_style('div#breadcrumbs', 'sizing', 'border-bottom-width', 1);

if(!headway_get_element_property_value('sizing', 'div#breadcrumbs-container', 'border-bottom-width'))
	headway_update_element_style('div#breadcrumbs-container', 'sizing', 'border-bottom-width', 1);	
	
//Footer
if(!headway_get_element_property_value('sizing', 'div#footer', 'border-top-width'))
	headway_update_element_style('div#footer', 'sizing', 'border-top-width', 1);

if(!headway_get_element_property_value('sizing', 'div#footer-container', 'border-top-width'))
	headway_update_element_style('div#footer-container', 'sizing', 'border-top-width', 1);

//Leaf Top
if(!headway_get_element_property_value('sizing', 'div.leaf-top', 'border-bottom-width'))
	headway_update_element_style('div.leaf-top', 'sizing', 'border-bottom-width', 1);
	

headway_delete_element_style('body', 'font', 'line-height');

headway_delete_element_style('div.sidebar a', 'font', 'font-size');
headway_delete_element_style('div.sidebar a', 'font', 'font-family');
headway_delete_element_style('div.sidebar a', 'font', 'line-height');

headway_delete_element_style('.entry-title a', 'font', 'line-height');

headway_delete_element_styles('div#footer-container');


//Tell wizard it's "been ran"
headway_update_option('ran-wizard', true);


////////Tell the DB it has been done
update_option('headway-version', '1.7');