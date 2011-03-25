<?php

	// Extra information

	$extra['author'] = "Sumolari";
	$extra['author_url']= "http://sumolari.com";
	$extra['name'] = __('Last tweet', 'wp_carousel');
	$extra['url'] = "http://sumolari.com/wp-carousel";
	$extra['desc'] = __('Show the lastest tweet from a Twitter user', 'wp_carousel');
	$extra['version'] = '1.0';
	
	// Functions to get item's information
	
	$extra['image_url_function'] = 'swpc_tweet_image_url';
	$extra['link_url_function'] = 'swpc_tweet_link_url';
	$extra['title_function'] = 'swpc_tweet_title';
	$extra['desc_function'] = 'swpc_tweet_desc';

?>