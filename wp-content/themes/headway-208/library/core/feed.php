<?php
function headway_filter_feed($query) {
	if($query->is_feed){
		$posts = new WP_Query('meta_key=_headway_hide_from_feed&meta_value=1');

		$exclude = array();

		while($posts->have_posts()){
			$posts->the_post();

			array_push($exclude, get_the_id());
		}
		
		if(count($exclude) > 0)
			$query->set('post__not_in', $exclude);

		if(is_array(headway_get_option('feed-exclude-cats')))
			$query->set('category__not_in', headway_get_option('feed-exclude-cats'));
	}
	
	return $query;
}
add_filter('pre_get_posts','headway_filter_feed');