=== Advanced Most Recent Posts Mod ===
Contributors: yakuphan, trepmal
Tags: Advanced, recent, recent posts, most recent, category posts, thumbnail
Donate link: http://kaileylampert.com/donate/
Requires at least: 2.8
Tested up to: 3.0.1
Stable tag: 1.4

Display most recent posts from selected categories or current category or all categories with thumbnail images (optional).
Mods: Adds date/author options, post type choice, title link, and title/excerpt separator.

== Description ==
Advanced Most Recent Posts Widget displays your recent posts with thumbnail images (optional). It gets posts from selected categories or current category or all categories. When your visitors are at home, it gets posts from all posts or selected category. If you set 'Get posts from current category', when visitors see single post, widget lists posts in the same category of single post or when visitors click a category link, it gets posts from current category.

Mods: Adds date/author options, post type choice, title link, and title/excerpt separator.

Notice: This widget requires at least 2.8.

== Installation ==

= Installation =
1. Make sure you are running WordPress version 2.8 or better. It won't work with older versions.
2. Download the zip file and extract the contents.
3. Upload the 'advanced-most-recent-posts' folder (wp-content/plugins/).
4. Activate the plugin through the 'plugins' page in WP.
5. See 'Appearance'->'Widgets' to place it on your sidebar. Set the settings.

== Frequently Asked Questions ==

= How can I set it to get posts from current category? =
Select checkbox on widget's settings called 'Get posts from current category'.

= I want to display only the posts in two categories. =
You have to write their category's ids -seperated with a comma- to 'Categories' textbox.

= I don't use Widgets. How can use this widget? =

template tag: `yg_recentposts( $args )`
shortcode: `[amrp]` with args
Original author's [website](http://www.yakupgovler.com/?p=1033).

== Screenshots ==

1. Widget's screenshot in 'Appearance'->'Widgets'
2. (original version) Widget's screenshot in 'Appearance'->'Widgets'

== Options ==

Widget's options allow you to change your recent posts list displaying.

= Title: =
Your recent posts widget's title on your sidebar.

= Title Link: =
The page the title should link to.

= Hide Post Title: =
Check to hide post title in output. useful for thumbnail-only displays

= Separator: =
The character to use to separate the title from the excerpt.

= After Excerpt: =
What should appear after the excerpt

= After Excerpt Link: =
should the 'after excerpt' text link to the post? useful if 'after excerpt' read like "read more..."

= Show: =
The post type to be displayed.

= Number of posts to show: =
How many posts to display

= Excerpt length (letters) =
You know that

= Thumbnail Custom Field Name =
If you want to display the thumbnail of your posts via a custom field, write its name.

= Height - Width =
Images size.

= Get first image of post =
If you don't want to use custom field, plugin will get first image from your post content.

= Get first attached image of post =
Plugin gets first attached image of post.

= Default image =
If post has no image, plugin display this image. Ex: http://www.yakupgovler.com/default-image.png


Notice: If you use three options, plugin uses custom field image firstly. If the post has no custom field, it gets first image from content. At last it gets first attached image. I suggest not to use "Get first image of post" for performance. It queries much more.

= Show Author =
If checked, shows author next to title

= Show Post Timestamp =
If checked, shows post timestamp

= Time format =
The format to be used when displaying the timestamp 

= Put time =
A placement option for the post timestamp

= Categories =
Plugin gets posts in these categories. (Category IDs, separated by commas.)

= Get posts from current category: =
Posts will be get from current category (single post's category or current category).


== Changelog ==
= 1.4.1 =
* (trepmal) fixed double echo issue

= 1.4 =
* (trepmal) added support for shortcodes, show author option and post-type choice

= 1.3 =
* (trepmal) fixed timestamp bug, added timestamp placement option

= 1.2 =
* (trepmal) added support for setting a title link, choosing a title/content separator, and displaying post timestamp

= 1.1 =
* Fixed a bug. If you don't set image dimensions, it displays thumbnail wrong.

= Version 1.0 =
* Initial release version.
