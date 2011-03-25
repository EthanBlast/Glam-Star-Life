=== Yet Another Featured Posts Plugin (YAFPP) ===
Author: JonRaasch (Jon Raasch)
Author URI: http://jonraasch.com/
Contributors: JonRaasch, pdclark
Plugin URI: http://jonraasch.com/blog/yet-another-featured-posts-plugin
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=4URDTZYUNPV3J&lc=US&item_name=Jon%20Raasch&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donate_LG%2egif%3aNonHosted
Tags: featured posts, featured, highlight, starred, star, highlight posts, feature, featured post list
Requires at least: 2.8.4
Tested up to: 2.9.2
Stable tag: 1.4

Yet Another Featured Posts Plugin provides an easy AJAX interface to feature posts, with thumbnails & other display options for featured posts.

== Description ==

Yet Another Featured Posts Plugin (YAFPP) provides an easy interface to feature posts in your WordPress blog. Simply check the 'featured stars' associated with each post in WordPress' post listing screen to feature or unfeature a post.  This info is posted immediately to your WordPress settings using AJAX.

This interface for featuring/unfeaturing posts is a big step up from other featured posts plugins, which make you enter a string of IDs.

Additionally, YAFPP provides a number of output options for WP developers - you can echo out or return an HTML formatted string of featured posts, return an array of featured post data or manipulate WordPress' The_Loop.

Using these display options you can easily display a thumbnail with each featured post, or otherwise modify the list of featured posts according to your setup.

Not a developer?  Beginners can also display thumbnails with their featured posts by installing [YAPB](http://wordpress.org/extend/plugins/yet-another-photoblog/ "Yet Another Photoblog").  YAFPP interfaces nicely with YAPB, and allows you to display YAPB thumbnails along with your normal featured posts output.

Please read the [complete documentation for YAFPP](http://dev.jonraasch.com/yafpp/docs "Read the complete documentation")

== Installation ==

= Basic installation of YAFPP is simple: =

1. Upload the folder `yet-another-featured-posts-plugin` into the `/wp-content/plugins/` directory

2. Activate the plugin through the 'Plugins' menu in WordPress

3. Select featured posts by clicking the 'featured stars' within the post listing in 'Posts > Edit'

4. Place `<?php if ( function_exists('get_featured_posts') ) get_featured_posts(); ?>` wherever you want a list of featured posts in your templates

= Output options =

For more advanced users there are a number of output options.  Simply pass an option array: `<?php get_featured_posts(array( 'method' => 'return' ); ?>`.  In this example `<?php get_featured_posts(); ?>` would return the formatted string of data instead of echoing it.

Output options include:

* `echo`     : (Default) Echoes an HTML formatted string of featured posts
* `return`   : Returns an HTML formatted string of featured posts
* `arr`      : Returns a PHP array of data related to the featured posts
* `the_loop` : Alters the query for WordPress' [The_Loop](http://codex.wordpress.org/The_Loop "WordPress' The_Loop")

See the FAQs for more info on output options.

Read the [complete documentation](http://dev.jonraasch.com/yafpp/docs "Read the complete documentation")

== Frequently Asked Questions ==

= How do I feature pages? =

First, in the YAFPP settings page, check the checkbox 'Allow pages to be featured'.  Now any pages you feature will show in the list of featured posts.

= How do I separate featured posts & pages? =

When calling `get_featured_posts()` you can pass in the option `post_type` to either `post` or `page` to specify entries of that type.  So to get a list of featured posts only:

`<?php get_featured_posts( array('post_type' => 'post') ); ?>`

Or pull a list of only featured pages:

`<?php get_featured_posts( array('post_type' => 'page') ); ?>`

= What data is returned when using the `arr` output method? =

YAFPP returns an array in this format:

`<?php array(
    'id'      => '', // id of the post
    'title'   => '', // title of the post
    'excerpt' => '', // excerpt of the post
    'url'     => '', // the post permalink
    'image'   => '', // an array of data for a YAPB image, if it exists
    'author' =>  '', // the post's author
); ?>`

= How do I use the `the_loop` output method? =

When using `the_loop` output method, YAFPP alters the next instance of The_Loop to contain only the featured posts.  To call these featured posts, call The_Loop:

`<?php
get_featured_posts(array('method' => 'the_loop'));

 while (have_posts()) : the_post();
//whatever you want in here
endwhile;
?>`

It should be noted that the original query is still preserved when using this method.  Thus if you want to call the original query for a given page, just call The_Loop a second time:

`<?php
get_featured_posts(array('method' => 'the_loop'));

 while (have_posts()) : the_post();
//whatever you want to do with the featured posts
endwhile;

 while (have_posts()) : the_post();
//whatever you want to do with the original loop
endwhile;
?>`

For more answers to your YAFPP questions, please read the [complete documentation](http://dev.jonraasch.com/yafpp/docs "Read the complete documentation")

== Screenshots ==

1. Settings page for YAFPP - unfeature already featured posts, edit display and permission settings
2. Featuring posts from the post listing page - just click each posts 'featured star'

== Changelog ==

= 1.4 =
* Builds functionality to allow featured pages as well as posts
* Optimizes database calls within featured post loop
* Improves sterilization of content via native Wordpress methods
* Thanks to [Paul Clark](http://pdclark.com/) for these contributions

= 1.3 =
* Fixes image path bug (thanks to Greg Boggs for his help here)
* Output method 'the_loop' now honors "max_posts" setting (thanks to Nicol˜ Martini for this contribution)

= 1.2 =
* URL and descriptions bug fixed
* Cleaning up bugs in YAPB support and better photo output

= 1.1 =
* Fixing errors in documentation

= 1.0 =
* First major release of YAFPP

== License ==

Copyright 2009-2010 Jon Raasch - Released under the FreeBSD License - [License details](http://dev.jonraasch.com/yafpp/docs#licensing)