<?php
/**
 * Twitter Helper Functions
 *
 * @package Headway
 * @subpackage Twitter
 * @author Clay Griffiths
 **/


/**
 * Retrieves the latest Twitter updates.  Must have PHP 5.2 or higher.
 *
 * @param string $twitter_username Twitter username to fetch.
 * @param int $limit Amount of tweets to retrieve.
 * @param string $date_format The date format for the timestamps.
 * 
 * @return void
 **/
function headway_get_twitter_updates($twitter_username, $limit = 10, $date_format = "F j, Y \&\m\d\a\s\h\; g:i a"){
	if(version_compare(PHP_VERSION, '5.2.0') === 1){
		$timeline = "http://twitter.com/statuses/user_timeline/$twitter_username.rss";
		$timeline_rss = wp_remote_fopen($timeline);

		if($timeline_rss){
		
			$tweets_object = @simplexml_load_string($timeline_rss);
							
			if($tweets_object){
				if($tweets_object->channel->item){
					foreach($tweets_object->channel->item as $tweet){
						if($i++ >= $limit) break;
						echo '<li>'.substr($tweet->description, strlen($twitter_username)+2);
						echo '<span>'.date($date_format, strtotime($tweet->pubDate)).'</span></li>';
					}
				} else {
					echo 'Eek! There was an error fetching the Twitter feed.';
				}
			} else {
				echo 'Error!  RSS file is invalid.';
			}
			
		} else {
			echo 'Error.  Twitter stream not found. Make sure you entered a valid username.';
		}
	} else {
		echo 'Error.  You must be running at least PHP 5.2 to use the Headway Twitter functionality.';
	}
}