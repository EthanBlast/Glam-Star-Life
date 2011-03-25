<?php
//Form action for all Headway configuration panels.  Not in function/hook so it can load before everything else.
if(isset($_POST['headway-submit']) && $_POST['headway-submit'] == true){
	if(!wp_verify_nonce($_POST['headway-admin-nonce'], 'headway-admin-nonce')) die('Security nonce does not match.');
	
	//Do not save these inputs to the DB
	$do_not_save = array('select-hook', 'headway-admin-nonce');

	do_action('headway_custom_option_actions');

	foreach($_POST as $key => $value){
		if($key == 'js_libraries_unchecked'){
			$libraries = (array)headway_get_option('js-libraries');

			foreach($value as $key => $value){
				$remove = array($key);

				$libraries = array_diff($libraries, $remove);
			}

			headway_update_option('js-libraries', $libraries);

			continue;
		}

		if($key == 'js_libraries'){
			$libraries = (array)headway_get_option('js-libraries');

			foreach($value as $key => $value){
				if(!in_array($key, $libraries)) array_push($libraries, $key);
			}

			headway_update_option('js-libraries', $libraries);

			continue;
		}

		if($key == 'multisite'){			
			foreach($value as $key => $value){
				if(!$value) 
					delete_site_option($key);
				else
					update_site_option($key, $value);
			}

			continue;
		}

		if($key == 'skin-options'){
			foreach($value as $key => $value){
				$key = 'skin-'.headway_get_option('active-skin').'-'.$key;

				if(!$value) headway_delete_option($key);

				if(strpos($key, '_unchecked')):
					$key = str_replace('_unchecked', '', $key);
					if($_POST[$key] == NULL) headway_delete_option($key);
				endif;

				if($value) headway_update_option($key, $value);
			}

			continue;
		}

		if(!$value) headway_delete_option($key);

		if(strpos($key, '_unchecked')){
			$key = str_replace('_unchecked', '', $key);
			if(!isset($_POST[$key])) headway_delete_option($key);
		}

		if($value && !in_array($key, $do_not_save)) headway_update_option($key, $value);

		if($key == 'feed-exclude-cats'){
			$feed_exclude_cats = true;
		}
	}
	
	if(!isset($feed_exclude_cats)) headway_delete_option('feed-exclude-cats');

	global $headway_admin_success;
	$headway_admin_success = true;
} else if(isset($_POST['reset-headway']) && $_POST['reset-headway'] == true){
	if(!wp_verify_nonce($_POST['headway-admin-nonce'], 'headway-admin-nonce')) die('Security nonce does not match.');
	
	global $wpdb;
		
	$headway_elements_table = $wpdb->prefix.'headway_elements';
	$headway_leafs_table = $wpdb->prefix.'headway_leafs';
	$headway_options_table = $wpdb->prefix.'headway_options';
	
	$wpdb->query("DROP TABLE IF EXISTS $headway_elements_table;");
	$wpdb->query("DROP TABLE IF EXISTS $headway_leafs_table;");
	$wpdb->query("DROP TABLE IF EXISTS $headway_options_table;");
	
	delete_option('headway-version');
	
	headway_clear_cache();
	
	global $headway_admin_success;
	$headway_admin_success = true;
}