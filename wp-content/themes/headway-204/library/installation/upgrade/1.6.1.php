<?php
//////Upgrade for 1.6.1/////

global $wpdb;
$headway_options_table = $wpdb->prefix.'headway_options';

$wpdb->query("ALTER TABLE `$headway_options_table` CHANGE `value` `value` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL");

// Create uploads folder and other Headway uploads folders if they don't exist.
headway_create_uploads_folders();