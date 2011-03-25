=== Top Contributors ===
Contributors: blueinstyle
Tags: widgets, plugins, top commenters, commenters, gravatars, top authors, author list, authors
Requires at least: 2.8
Tested up to: 3.0.1
Stable tag: 1.3.1

Display your top commenters or authors in a widget.

== Description ==

Display your top commenters or authors in a widget, or you can display anywhere on your blog by pasting this code into your theme: ` <?php if(function_exists('jme_top_contributors')) { jme_top_contributors(); } ?> `

* List your top commenters or authors with the option to display their Gravatar, and several other options.
* Choose from 2 formats of the widget, with complete control of styles via css.
* Exclude users from the list by email address.
* The list uses a cache system for improved performance. List updates only when a post or comment is added, or options updated.

= Extra Feature =
* Add a special Icon next to each of your Top Commenter's name in their comments to give them a little special recognition for being a regular contributor.


Questions, comments and support can be found on my blog at http://justmyecho.com/2010/07/top-contributors-plugin-wordpress/

== Installation ==

1. Download and extract top-contributors.zip file.
2. Upload the folder containing the Plugin files to your WordPress Plugins folder (usually ../wp-content/plugins/ folder).
3. Activate the Plugin via the 'Plugins' menu in WordPress.
4. Go to "Settings > Top Contributors" page to set your options and add the widget.

If you're upgrading from a previous version, make sure to back up any customizations to "top-contributors/css/tooltip.css" file to prevent loss of custom formatting.

== Frequently Asked Questions ==

= The list says "No Commenters" now, but was working before =

If you're using the 3rd Time limit option "Only This week, month, year". The comment count 'resets' at the start of each interval. So the start of each week/month/year the list will reset to 0. If you want a continuous list of the last 7 days, use "The Last 7 days" instead.

== Screenshots ==

1. The 2 formats to display top commenters

== Changelog ==
= 1.3.1 =
* Fixed a bug with widget caching
= 1.3 =
* Added option to show top Authors instead of commenters, plus few other new options.
* Fixed language localization. Language files should go in /languages
= 1.2 =
* Added Integration for the "Add Local Avatar" plugin.
* Added support for localization of text. File is included for users who would like to translate text for the plugin.
* Added comment threshold for top commenter Icons.
= 1.1 =
* Added Time Limit options for comments.
* Fixed some formatting/style issues in widget.
= 1.0 =
* Initial release

== Upgrade Notice ==

= 1.3.1 =
Fixed a bug with widget caching
= 1.3 =
Added option to list Authors plus several other options.
= 1.2 =
Added new options and integration with Add Local Avatars plugin.
= 1.1 =
Fixes some format/style issues with widget. Add new Time limit options.
