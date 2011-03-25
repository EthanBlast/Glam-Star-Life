<?php
function twitter_leaf_inner($leaf){
	if(isset($leaf['new'])){
		$leaf['config']['show-title'] = 'on';
		$leaf['options']['tweet-limit'] = 5;
	}

	HeadwayLeafsHelper::create_tabs(array('options' => 'Options', 'miscellaneous' => 'Miscellaneous'), $leaf['id']);
	
	//////
	
	HeadwayLeafsHelper::open_tab('options', $leaf['id']) ;
	
		HeadwayLeafsHelper::create_text_input(array('name' => 'twitter-username', 'value' => $leaf['options']['twitter-username'], 'label' => 'Twitter Username'), $leaf['id']);
		HeadwayLeafsHelper::create_text_input(array('name' => 'tweet-limit', 'value' => $leaf['options']['tweet-limit'], 'label' => 'Tweet Limit'), $leaf['id']);
		HeadwayLeafsHelper::create_select(array('no-border' => true, 'name' => 'tweet-format', 'value' => $leaf['options']['tweet-format'], 'label' => 'Date/Time Format', 'options' => array(
					1 => 'January 1, 2009 - 12:00 AM',
					2 => 'MM/DD/YY - 12:00 AM',
					3 => 'DD/MM/YY - 12:00 AM',
					4 => '12:00 AM - Jan 1',
					5 => '12:00 AM - Jan 1, 2009',
					6 => 'January 1, 2009',
					7 => 'Jan 1, 2009'
				)), $leaf['id']);
				
	HeadwayLeafsHelper::close_tab();
	
	//////
	
	HeadwayLeafsHelper::open_tab('miscellaneous', $leaf['id']);
	
		HeadwayLeafsHelper::create_show_title_checkbox($leaf['id'], $leaf['config']['show-title']);
		HeadwayLeafsHelper::create_title_link_input($leaf['id'], $leaf['config']['leaf-title-link']);
		HeadwayLeafsHelper::create_classes_input($leaf['id'], $leaf['config']['custom-css-classes'], true);
		
	HeadwayLeafsHelper::close_tab();
	
}

function twitter_leaf_content($leaf){
	if(!function_exists('headway_get_twitter_updates')) require_once HEADWAYRESOURCES.'/twitter.php';
	
	if($leaf['options']['tweet-format'] == '1') $date_format = 'F j, Y - g:i A';
	if($leaf['options']['tweet-format'] == '2') $date_format = 'm/d/y - g:i A';
	if($leaf['options']['tweet-format'] == '3') $date_format = 'd/m/y - g:i A';
	if($leaf['options']['tweet-format'] == '4') $date_format = 'g:i A - M j';
	if($leaf['options']['tweet-format'] == '5') $date_format = 'g:i A - M j, Y';	
	if($leaf['options']['tweet-format'] == '6') $date_format = 'F j, Y';	
	if($leaf['options']['tweet-format'] == '7') $date_format = 'M j, Y';	
		
	echo '<ul class="twitter-updates">';
		headway_get_twitter_updates($leaf['options']['twitter-username'], $leaf['options']['tweet-limit'], $date_format);
	echo '</ul>';
}

$options = array(
		'id' => 'twitter',
		'name' => 'Twitter',
		'default_leaf' => true,
		'options_callback' => 'twitter_leaf_inner',
		'content_callback' => 'twitter_leaf_content',
		'icon' => get_bloginfo('template_directory').'/library/leafs/icons/twitter.gif'
	);

$twitter_leaf = new HeadwayLeaf($options);