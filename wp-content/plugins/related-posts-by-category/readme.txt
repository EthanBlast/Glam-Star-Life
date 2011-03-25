=== Related Posts by Category ===
Contributors: stalkerX
Tags: similar posts, related posts, similar, related, posts, category
Requires at least: 2.3
Tested up to: 3.1
Stable tag: trunk

WordPress plugin for related posts ordered by current category. It's small. It's fast. Really!


== Description ==
*Related Posts by Category* lists similar posts within any post. As a search string the plugin does not use the title of the article nor weighs the content. In fact the category, which was assigned to the post, serves as the source of accordance.


= Features =
* Categories exclude
* Hook support
* Very fast execution
* No user interface required
* Post thumbnail integration (WP 2.9 or older)
* Quick query in only one sql statement
* Quantity of results and further options are adjustable


= Mode of operation =
Just put `<?php do_action('related_posts_by_category', args) ?>` in your *single.php* template for display a list of similar posts.


= Example =
`
<ul>
  <?php do_action(
    'related_posts_by_category',
    array(
	    'orderby' => 'RAND',
	    'order' => 'DESC',
	    'limit' => 5,
	    'echo' => true,
	    'before' => '<li>',
	    'inside' => '&raquo; ',
	    'outside' => '',
	    'after' => '</li>',
	    'rel' => 'nofollow',
	    'type' => 'post',
	    'image' => 'thumbnail',
	    'message' => 'No matches'
	  )
	) ?>
</ul>
`
Please adjust the parameters accordingly.


= Documentation =
* [Documentation](http://playground.ebiene.de/356/related-posts-by-category-wp-plugin-fur-verwandte-beitrage-einer-kategorie/ "Related Posts by Category")
* [Twitter](http://twitter.com/wpSEO "wpSEO on Twitter")
* [Blog](http://playground.ebiene.de "Playground Blog")
* [Other plugins](http://wpcoder.de "Other plugins")
* [Author page](http://ebiene.de "Author page")


== Changelog ==
= 0.8 =
* Exclusion of desired categories

= 0.7 =
* Add support for the default thumb image

= 0.6 =
* Add hook support for the attaching action

= 0.5 =
* Post thumbnails (since WP 2.9), first available post image alternatively
* WordPress 2.9 ready

= 0.4 =
* Increase the security of the database query

= 0.3 =
* By chance generated display of results with *orderby* => *RAND*

= 0.2 =
* *post* or *page* as a parameter for type value

= 0.1 =
* *Related Posts by Category* goes online


== Installation ==
1. Download plugin
1. Unzip the archive
1. Upload the file *related_posts.php* into *../wp-content/plugins/*
1. Go to tab *Plugins*
1. Activate *Related Posts by Category*
1. Ready


== Frequently Asked Questions ==
= Documentation =
* [*Related Posts by Category* documentation in English](http://playground.ebiene.de/400/related-posts-by-category-the-wordpress-plugin-for-similar-posts/ "Related Posts by Category")
* [*Related Posts by Category* documentation in German](http://playground.ebiene.de/356/related-posts-by-category-wp-plugin-fur-verwandte-beitrage-einer-kategorie/ "Related Posts by Category")
* [Follow us on Twitter for updates](http://twitter.com/wpSEO "wpSEO on Twitter")