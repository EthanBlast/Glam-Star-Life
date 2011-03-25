<?php
//////Upgrade for 2.0/////
headway_delete_element_style('body', 'font', 'line-height');

headway_delete_element_style('div.sidebar a', 'font', 'font-size');
headway_delete_element_style('div.sidebar a', 'font', 'font-family');
headway_delete_element_style('div.sidebar a', 'font', 'line-height');

headway_delete_element_style('.entry-title a', 'font', 'line-height');

headway_delete_element_styles('div#footer-container');
headway_delete_element_styles('h3.entry-title');
headway_delete_element_styles('.entry-title a');



//Set Up Underlines (Default none, hover underline)
headway_update_element_style('.header-link-text-inside', 'font', 'text-decoration', 'none');
headway_update_element_style('.header-link-text-inside:hover', 'font', 'text-decoration', 'underline');

headway_update_element_style('ul.navigation li a', 'font', 'text-decoration', 'none');
headway_update_element_style('ul.navigation li a:hover', 'font', 'text-decoration', 'underline');

headway_update_element_style('a.more-link', 'font', 'text-decoration', 'none');
headway_update_element_style('a.more-link:hover', 'font', 'text-decoration', 'underline');

headway_update_element_style('.nav-below a', 'font', 'text-decoration', 'none');
headway_update_element_style('.nav-below a:hover', 'font', 'text-decoration', 'underline');


//More Underlines (Default underline, hover none)
headway_update_element_style('div.entry-content a', 'font', 'text-decoration', 'underline');
headway_update_element_style('div.entry-content a:hover', 'font', 'text-decoration', 'none');

headway_update_element_style('div.entry-meta a', 'font', 'text-decoration', 'underline');
headway_update_element_style('div.entry-meta a:hover', 'font', 'text-decoration', 'none');

headway_update_element_style('div.sidebar a', 'font', 'text-decoration', 'underline');
headway_update_element_style('div.sidebar a:hover', 'font', 'text-decoration', 'none');

headway_update_element_style('div#footer a', 'font', 'text-decoration', 'underline');
headway_update_element_style('div#footer a:hover', 'font', 'text-decoration', 'none');

headway_update_element_style('div#header a#header-rss-link', 'font', 'text-decoration', 'underline');
headway_update_element_style('div#header a#header-rss-link:hover', 'font', 'text-decoration', 'none');

headway_update_element_style('div.leaf-top a', 'font', 'text-decoration', 'underline');
headway_update_element_style('div.leaf-top a:hover', 'font', 'text-decoration', 'none');

headway_update_element_style('span.comment-author a', 'font', 'text-decoration', 'underline');
headway_update_element_style('span.comment-author a:hover', 'font', 'text-decoration', 'none');

//Fix Tagline Selector
global $wpdb;
$elements_table = $wpdb->prefix.'headway_elements';

$wpdb->query("UPDATE $elements_table SET element = '#tagline' WHERE element = 'h1#tagline';");


////////Tell the DB it has been done
update_option('headway-version', '2.0');