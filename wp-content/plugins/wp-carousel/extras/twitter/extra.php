<?php

	// Returns the image URL

	function swpc_tweet_image_url($id)
	{
		$format = 'json';
		
		$tweet=json_decode(file_get_contents("http://api.twitter.com/1/statuses/user_timeline/{$id}.{$format}"));
				
		return $tweet[0]->user->profile_image_url;
	}
	
	// Returns the link to the item
	
	function swpc_tweet_link_url($id)
	{
		$format = 'json';
		
		$tweet=json_decode(file_get_contents("http://api.twitter.com/1/statuses/user_timeline/{$id}.{$format}"));
				
		return 'http://twitter.com/'.$id.'/status/'.$tweet[0]->id;
	}
	
	// Returns the title
	
	function swpc_tweet_title($id)
	{
		$format = 'json';
		
		$tweet=json_decode(file_get_contents("http://api.twitter.com/1/statuses/user_timeline/{$id}.{$format}"));
		
		return $tweet[0]->user->screen_name;
	}
	
	// Returns the desc
	
	function swpc_tweet_desc($id)
	{
		$format = 'json';
		
		$tweet=json_decode(file_get_contents("http://api.twitter.com/1/statuses/user_timeline/{$id}.{$format}"));
				
		return $tweet[0]->text;
	}
	
?>