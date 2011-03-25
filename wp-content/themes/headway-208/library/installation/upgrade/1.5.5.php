<?php
//////Upgrade for 1.5.5/////

global $wpdb;
$elements_table = $wpdb->prefix.'headway_elements';

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