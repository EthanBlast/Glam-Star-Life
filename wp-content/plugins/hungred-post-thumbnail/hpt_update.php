<?php
/*  Copyright 2009  Clay Lua  (email : clay@hungred.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
require_once "hpt_constants.php";
require_once WP_CONFIG_DIR;
require_once HPT_PLUGIN_DIR. '/hpt_function.php';
global $current_user;
get_currentuserinfo();
$level = $current_user->user_level;
if ( is_user_logged_in() && $level == "10") 
{
	global $wpdb;
	$value = $_POST['current'];
	$name = $_POST['id'];
	$post = $_POST['post'];
	$table = $wpdb->prefix."hungred_post_thumbnail_draft";
	$query = " 	INSERT INTO `".$table."`(`hpt_post`, `".(make_safe($name))."`)
				VALUES('".(make_safe($post))."','".(make_safe($value))."') ON DUPLICATE KEY UPDATE `".$name."`='".(make_safe($value))."'
				";
	$wpdb->query($query);
}
?>