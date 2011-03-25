=== FPW Category Thumbnails ===
Contributors: frankpw
Donate link: 
Tags: category, thumbnail
Requires at least: 2.9.0
Tested up to: 3.0.4
Stable tag: 1.1.6

Assigns a thumbnail based on categoryid/thumbnail mapping to a post/page when
the post is created or updated.

== Description ==

**FPW Category Thumbnails** allows assigning thumbnails to post categories.
When configured it will check on create/update of the post/page if selected
category has thumbnail mapped to it and will add that thumbnail to the 
post/page.

== Installation ==

1. Upload `fpw-category-thumbnails` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Select Dashboard's Settings -> FPW Category Thumbnails and build category/thumbnail mapping
1. **UPDATE:** If you update from versions prior to 1.1.0 please write down your current assignments! Update will destroy previous assignments 

== Frequently Asked Questions ==

= I've entered ID of a picture from NextGen Gallery and thumbnail doesn't show. =

IDs from NextGen Gallery must be entered with ngg- prefix, so ID 230 should be entered as ngg-230.

== Screenshots ==

1. Settings Page

== Changelog ==

= 1.1.6 =
* Moved Description and Instructions blocks to contextual help

= 1.1.5 =
* Added table of available images

= 1.1.4 =
* Added plugin activation action to apply proper extension to uninstall(.txt/.php) file based on option setting in database

= 1.1.3 =
* Plugin code optimization
* Minor fixes

= 1.1.2 =
* Added: update information line to plugin's meta block which shows only when update is detected

= 1.1.1 =
* Added: immediate action to apply all mappings to existing posts/pages
* Added: immediate action to unconditionally remove thumbnails from existing posts/pages

= 1.1.0 =
* Changed: changed from thumbnails to category names mapping to thumbnails to category ids mapping
* Changed: category listing shows category names and ids reflecting hierarchy of categories 

= 1.0.4 =
* Added: change name of uninstall file based on cleanup flag

= 1.0.3 =
* Added: option to prevent overwriting if post/page has thumbnail allready
* Updated: translations

= 1.0.2 =
* Added: link to Settings into plugin's action links
* Added: database cleanup on uninstall
* Updated: translations

= 1.0.1 =
* Added: check if current theme supports post thumbnails
* Updated: translations

= 1.0 =
* Initial release.