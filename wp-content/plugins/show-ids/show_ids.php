<?php
/*
Plugin Name: Show IDs
Version: 1.0
Plugin URI: http://jacobanderic.com
Description: Simply adds a column in the Admin edit view to show post and page IDs.
Author: Jacob Guite-St-Pierre
Author URI: http://jacobanderic.com
*/
function add_id_column($column) {
	$column['id_column'] = '<abbr style="cursor:help;" title="ID">ID</abbr>';
	return $column;
}
function add_id_column_content($column,$ID) {
	if( $column == 'id_column' ) {
		echo '<strong style="cursor:help;">'.$ID.'</strong>';
	}
}
add_filter('manage_posts_columns', 'add_id_column', 5, 2);
add_action('manage_posts_custom_column', 'add_id_column_content', 5, 2);
add_filter('manage_pages_columns', 'add_id_column', 5, 2);
add_action('manage_pages_custom_column', 'add_id_column_content', 5, 2);
?>