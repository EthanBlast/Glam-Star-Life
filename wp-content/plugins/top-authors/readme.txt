=== Plugin Name ===
Contributors: sebvandijk
Donate link: 
Tags: Authors, list, widget, gravatar, posts
Requires at least: 2.8
Tested up to: 3.01
Stable tag: 4.3

A highly customizable widget that sums the top authors on your blog.

== Description ==

This plugins allows you to easily sum the most contributing authors on your site.<br />
It is highlty customizable, it's posible to to customize your own HTML output. <br />
Customize the tag before and after the list (for example a custom class). <br />
Add a gravatar to your list<br />
set the size of your gravatars<br />
Option to exclude administrator users<br />

== Installation ==

1. Upload `top-authors` folder to the `/wp-content/plugins/` directory 
1. OR search for Top-authors in WP-admin > Plugins > Add New
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to Appearance and drag the top-authors to a sidebar
4. Setup the widget! and have fun.

== Frequently Asked Questions ==

= Please send me a question by mail.

I'll give you the answer

== Screenshots ==

1. Example 1: without gravatar
2. Example 2: use only gravatar thumbnails
3. Example 3: Gravatar and text
4. Widget settings. in this case the settings of Example 2, note the exclude administrator checkbox


== Changelog ==

= 0.1 =
* Initial release

= 0.2 =
* Check if input is nummeric and between 1 and 99 

= 0.3 =
* Cleaner and more effective PHP code
* Added templating / self html support
* Replaced space in author name by dash so the link is more WP friendly

= 0.3.1 =
* readme.txt updated

= 0.4 =
* Small bugfix in html template.
* added gravatar support
* added custom before and after the list tags 

= 0.4.1 =
* Replaced deprecated fuction (http://codex.wordpress.org/Function_Reference/get_usernumposts) with count_many_users_posts
* Did some underwater code improvements.
* Added feedback link in widget, to get u guys involved :)

= 0.4.2 =
* bugfix sorting thanx Yusuf Savci for reporting!

= 0.5 =
* New feature requested by vectorism (thank you): Exclude administrator users from the list.
	Exclude function get information from wp_capabilities or blog_capabilities. If it's not working on your blog, please contact me.
= 0.5.1 =
* New feature to exclude authors with 0 posts (Thanks paul for request)

== Upgrade Notice ==

= 0.4 =
* This update contains new features as: Gravatar support and control over the begin and end tag.

= 0.4.1 =
* Important update: widget was using deprecated function that maybe will be removed by Wordpress.

= 0.5 =
* This update will add the option to exclude administrator users
