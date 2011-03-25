=== Author Avatars List ===
Contributors: bforchhammer, pbearne
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=CP96HL6QE6WWN
Tags: Avatar, Author, BuddyPress, Comment, Editor, Image, Multisite, Photo, Picture, Profile, Shortcode, Random, Sidebar, Thumbnail, User, Widget, Wpmu
Requires at least: 2.8
Tested up to: 3.0.0
Stable tag: 1.0

Display lists of user avatars using widgets or shortcodes.

== Description ==

This plugin makes it easy to *display lists of user avatars* on your (multiuser) blog. It also allows to *insert single avatars* for blog users or any email address into a post or page. (Great for displaying an image of someone you're talking about.)

Avatar lists can be inserted into your sidebar by adding a widget or into posts/pages by using a [shortcode](http://authoravatars.wordpress.com/documentation/authoravatars-shortcode/). The plugin comes with a tinymce editor plugin which makes inserting shortcodes very easy.

Both shortcode and widget can be configured to...

*   Show a custom title (widget only)
*   Only show specific user groups and/or hide certain users
*   Limit the number of users shown
*   Change the sort order of users or show in random order
*   Adjust the size of user avatars
*   Optionally show a user's name or biography
*   Show users from the current blog, all blogs or a selection of blogs (on WPMU/Multisite)
*   Group users by their blog (when showing from multiple blogs), and show the blog name above each grouping (experimental feature).

The plugin makes use of built in wordpress (core) functions to retrieve user information and get avatars.

Single user avatars can be inserted using the [show_avatar shortcode](http://authoravatars.wordpress.com/documentation/show_avatar-shortcode/) and configured to...

*   Adjust the size of the user avatar.
*   Align the avatar left, centered or right.

Please report bugs and provide feedback in the [wordpress support forum](http://wordpress.org/tags/author-avatars?forum_id=10#postform). (I'm following all posts with the "author-avatars" tag.)

== Installation ==

1. Upload the `author-avatars` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Enable and configure the widget as usual on the Design / Widgets page.

[Look at this page](http://codex.wordpress.org/User:Bforchhammer/Author_Avatars_ShortCode_Documentation) to find out how to use the [authoravatars] shortcode.

You can find information for developers [on this page](http://authoravatars.wordpress.com/documentation/developers-guide/).

== Screenshots ==

1. Very simple set up of the widget on an empty blog.
2. The Widget configuration panel.
3. Examples of what the <code>[authoravatars]</code> shortcode can do.
4. Shortcode helper available from the WYSIWYG editor on the edit post page.
5. List of users with name and biography

== Changelog ==

= 1.0 =
*   Fixed a number of styling issues
*   Fixed bug with capabilities (Wordpress 3 multisite)
*   Removed deprecated functions

= 0.9 =
*   Fixed compatibility with WordPress 3.0 (and its new multisite feature)
*   Fixed BuddyPress integration
*   Added feature to show avatars of commentators
*   Added feature to sort by firstname or lastname

= 0.8 =
*   Added feature to show a user's biography next to the avatar
*   Added feature to limit shown users by a minimum number of posts
*   Added feature to show a user's number of posts
*   Added Italian translation (by Gianni Diurno)

= 0.7.4 =
*   Fixed javascript issues with widget settings page and shortcode wizard in WordPress 2.8
*   Fixed support for translations
*   Added German translation
*   Added feature to sort by recent user activity (requires Buddypress)

= 0.7.3 =
*   Added filters to allow modification of userlist templates
*   Added "BuddyPress? Member Page" to the list of pages which a user can be linked to
*   Changed get_avatar() call so that it works with buddypress

= 0.7.2 =
*   Fixed a spelling mistake which prevented the plugin from loading

= 0.7.1 =
*   Improved inline function documentation
*   Fixed bug which caused a faulty name attribute for checkbox lists with only one choice. Now the "show name" option is working as exptected again.
*   Removed by-reference variable which causes PHP 4 parse errors

= 0.7 =
*   Removed invalid characters from uninstall.php (fixes uninstall behaviour).
*   New feature to link users to their website or blog (wpmu).
*   Added new feature to allow specification of a sort direction for sorted user lists. 
*   Changed string-based sorting to case-insensitive (strcmp -> strcasecmp).
*   Added feature to sort users by date of registration.
*   Optimised UserList filtering.
*   Fixed numeric sorting issues (user_id and post count)
*   Added "order by number of posts" feature
*   Removed user role from avatar title.

= 0.6.2 =
*   Fixed bug which caused the plugin to crash in PHP 4.
*   Added uninstall.php to remove plugin related data when the plugin is deleted.

= 0.6.1 =
*   Fixed bug which caused other tinymce plugins to stop working. 
*   Improved way of detecting a wpmu install.

= 0.6 =
*   Implementation of tinymce plugin.
*   Removed personalised jquery ui script and added just the packed ui.resizable.
*   Changed script and stylesheet handling (using register&enqueue functions with proper dependencies).
*   Refactored the "resizable avatar preview" script code into separate file.
*   FormHelper: Added option to generate textareas.
*   FormHelper: Added option to show expanded choice fields "inline".
*   Added improved function for cleaning up a value to be used as html id attribute.
*   AuthorAvatarsForm: added methods to ease generation of tabs and two-column panes.
*   AuthorAvatarsForm: added new renderField methods for shortcode type, email and alignment (used in show_avatar wizard).
*   Various Documentation updates and cleanups.
*   Refactored widget form field generation into new AuthorAvatarsForm.class.php to ease devevlopment of shortcode wizard.
*   Refactored form field generation code into new FormHelper.class.php.
*   Fixed size and position of blog selection box on sitewide admin page. Changed the name of is_wpmu() function to safer name AA_is_wpmu().
*   Removed "Group by blogs" checkbox for users without the blog selection filter.

= 0.5.1 =
*   Fixed method chaining error that caused a critical syntax error on PHP 4 

= 0.5 =
*    Added "show_avatar" shortcode
*    Small MultiWidget fix by [Dan Cole](http://blog.firetree.net/2008/11/30/wordpress-multi-widget/#comment-24976)
*    Refactored [show_avatar] shortcode into new file ShowAvatarShortcode.class.php to keep it all nice and tidy.
*    Added basic blog filtering feature.
*    Added classes for settings and sitewide admin
*    Added sitewide setting for the blog filter
*    Updated update mechanism in AuthorAvatars.class.php
*    Added "Group by blog" feature

= 0.4 =
*    Added new [shortcode](http://authoravatars.wordpress.com/documentation/authoravatars-shortcode/) feature.
*    Fixed small bug in update procedure (version 0.1 to 0.2)

= 0.3 =
*    Fixed error that broke some javascript on "edit post" pages in wordpress 2.7

= 0.2 =
*    Widget: added avatar preview image to the control panel
*    Widget: added option to link the user/avatar to their respective "author page"
*    Widget: hiddenusers also allows user ids now (e.g. 1 for "admin")
*    Refactored the plugin to use [Alex Tingle's "MultiWidget" class](http://blog.firetree.net/2008/11/30/wordpress-multi-widget/)

== Frequently asked questions ==

= Shortcode, huh? =

A shortcode is a tag like <code>[authoravatars]</code> which you can insert into a page or post to display a list of users on that post/page. You can read more about shortcodes in general in the wordpress codex, for example [here](http://codex.wordpress.org/Using_the_gallery_shortcode) or [here](http://codex.wordpress.org/Shortcode_API).

= How do I use the author avatar shortcode? =

As of version 0.6 the plugin comes with a tinymce plugin which makes it very easy to insert shortcode(s).

If you'd like to do it manually it's still simple: just add <code>[authoravatars]</code> into your post and hit save! There's a large number of [parameters](http://authoravatars.wordpress.com/documentation/authoravatars-shortcode/) available.

The plugin comes with two shortcodes: <code>[authoravatars]</code> for lists of avatars and <code>[show_avatar]</code> for single avatars.

= I can't get my widget to show users from multiple blogs! =

Make sure you have enabled the "blog filter" in Site Admin / Author Avatars for the blog on which you are trying to use this feature on. By default this is only enabled for the root blog (blog id = 1).

And you are running [Wordpress MU](http://mu.wordpress.org/) (or respectively WordPress 3 in multi-site mode), right?

= Can I upload custom pictures for users? =

No, the Author Avatars List plugin only provides ways of <strong>displaying</strong> user avatars.

The plugin uses the Wordpress Core Template function <code>get_avatar()</code> to retrieve the actual avatar images. In order to display custom images you need to look for plugins which use/override WordPress' avatar features and provide respective upload features...

Have a look at the [User Photo](http://wordpress.org/extend/plugins/user-photo/) Plugin (turn on option "Override Avatar with User Photo") or the [Add Local Avatar](http://wordpress.org/extend/plugins/add-local-avatar/) Plugin.

= I get a "404 Page not found" error when I click on the avatar of a user! =

This can happens when you've choosen to link users to their "author page" and the user has not written any posts on a blog. There are two things that you should do in this situation:

1. To prevent the 404 page from showing up install the [Show authors without posts](http://wordpress.org/extend/plugins/show-authors-without-posts/) Plugin. This forces WordPress to always show the user page if the user exists.

2. If not already there add a custom user/author template to your theme. Otherwise if a user has no posts their user page is going to be quite empty by default...
You can find a [tutorial](http://codex.wordpress.org/Author_Templates) on Author Templates as well as a [Sample Template File](http://codex.wordpress.org/Author_Templates#Sample_Template_File) in the WordPress Codex.

= Can I use html in user biographies?

Wordpress Core unforunately strips all html from the user biography field when entered. Install the plugin [Weasel's HTML Bios](http://wordpress.org/extend/plugins/weasels-html-bios/) if you want to use html...

= How can I change the styling of the avatar lists? =

The styling of the widget is controlled by the styles defined in [css/widgets.css](http://plugins.trac.wordpress.org/browser/author-avatars/trunk/css/widget.css), avatars on posts/pages (using the shortcode) are styled by code in [css/shortcode.css](http://plugins.trac.wordpress.org/browser/author-avatars/trunk/css/shortcode.css).

You can override the styles in that file by copying a style block to your theme's `style.css` and adjusting respectively. For example add the following to remove the padding from avatars displayed in a widget:

`html .multiwidget_author_avatars .author-list .user {
  padding: 0;
}`
