<?php
function headway_clean_slug($data) {	
	if(!headway_get_option('seo-slugs')) return $data;
	
	//Save slug for later in case the ending slug equals nothing.
	$original_slug = $data['post_name'];
	
	$bad_words = array_map('headway_filter_array_piece', explode("\n", headway_get_option('seo-slug-bad-words')));	
		
    //If a user slug already exists, don't do anything.
	if(isset($_POST['post_name']) && $_POST['post_name'] == true) return $data;

	$title = strtolower(stripslashes($data['post_title']));

	$slug = preg_replace("/&.+?;/", '', $title); //Remove HTML entities
	
	if(headway_get_option('seo-slugs-numbers')){
		$slug = preg_replace("/[^a-zA-Z ]/", '', $slug); //Remove anything that isn't a letter space.
	} else {
		$slug = preg_replace("/[^a-zA-Z0-9 ]/", '', $slug); //Remove anything that isn't a letter, number, or space.
	}

	//Explode slug into array, then do an array_diff to remove bad words.
    $slug_array = array_filter(array_diff(explode(' ', $slug), $bad_words));

	//Join slug array back to a string.
	$data['post_name'] = implode('-', $slug_array);
	
	//If the slug is empty after being cleaned, revert to the original.
	if($data['post_name'] == '-' || !$data['post_name']) $data['post_name'] = $original_slug;
	
	return $data;
}

function headway_setup_page($pageID){		
	headway_build_default_leafs($pageID, false, $_POST['template_box']['leaf_template']);
	headway_clear_cache(array('leafs'));
	
	headway_update_option('css-last-updated', mktime()+1);
}

/* Actions */
add_action('publish_page', 'headway_setup_page');
add_action('delete_post', 'headway_delete_page_leafs');

/* Filters */
add_filter('wp_insert_post_data', 'headway_clean_slug', 0);