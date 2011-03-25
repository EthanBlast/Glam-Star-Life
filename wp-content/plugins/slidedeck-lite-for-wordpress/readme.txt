=== Content Slider by SlideDeck ===
Contributors: dtelepathy, kynatro, dtrenkner
Donate link: http://www.slidedeck.com/download
Tags: Slider, dynamic, slide show, slideshow, widget, Search Engine Optimized, seo, jquery, plugin, pictures, slide, skinnable, skin, posts, video, photo, media, image gallery, iPad, iphone, vertical slides, touch support, theme
Requires at least: 2.7.0
Tested up to: 3.0.1
Stable tag: trunk

Create SlideDecks on your WordPress blogging platform. Manage SlideDeck content and insert them into templates and posts.

== Description ==

The SlideDeck WordPress slider plugin allows you to easily create a content slider widget or slideshow on your WordPress blog without having to write any code. Just create a new slider with the SlideDeck control panel tool and insert the widget into your post via the WYSIWYG editor with the TinyMCE plugin SlideDeck picker. 

You can also create a dynamic slider by using the Smart Slider function. Just choose your blog post criteria (recent, popular, featured), select a theme, set your options and viola, you have a dynamically updated slider in seconds! Users can now visually experience your blog posts.

**NEW!:** Vertical slides (PRO), RSS Feed Smart SlideDecks (PRO), Skin Support, Compatible with WordPress 3.0, Now uses custom post types!

**Requirements:** PHP5+, WordPress 2.7.x+

**Important Links:**

* [Demo](http://www.slidedeck.com/wordpress)
* [Community Examples](http://www.slidedeck.com/examples)
* [More Details](http://www.slidedeck.com/wordpress)
* [Full Feature List](http://www.slidedeck.com/features)
* [Documentation](http://www.slidedeck.com/usage-documentation)
* [Support](http://getsatisfaction.com/slidedeck)

**Features:**

* No coding required!
* Smart SlideDecks. Build dynamic SlideDeck slideshows from blog content
* Add any media (image, video, mp3...etc) to a slide with the WordPress editor
* Add, remove or reorder slides with a slick drag and drop interface
* Search Engine Optimized (SEO) - all content of each SlideDeck (copy, alt tags...etc) are completely indexable by search engines
* Update SlideDeck content at anytime without even editing your posts or template code
* Specify any slide as the start slide as well as the animation speed
* Specify unique spine title text
* Use all the tools in the WordPress Kitchen Sink editor to make your SlideDeck look perfect
* Customize the code and add any content directly into the slide with the WordPress HTML editor
* Preview your SlideDeck in a modal box or on your post as you create it
* Set custom dimensions for each SlideDeck
* Copy and paste a code snippet to place your SlideDeck anywhere on your WordPress blog or site
 * Touchscreen support for iPad, iPhone and other devices (PRO).
 * Ability to create vertical slides (PRO).
 * Smart SlideDecks from RSS feeds (PRO).
 * Ability to apply free skin/themes.
 
**Use Cases:**

* Dynamic Content
 * Feature Slider for WordPress Blog Posts  
 * Visualize Any RSS Feed 
 * Automate News Articles and Updates
* Tours & Demos
 * Product Tour 
 * Features Demo 
 * Process Guide 
 * Step-by-Step Instruction
* Media Galleries
 * Photo Gallery 
 * Video Gallery 
 * Artwork Gallery 
 * Music Gallery (Artist, Album Song) 
 * Movie/Television Guide/Gallery
* Multi-Dimensional Web Content (User Input or Vertical + Horizontal Slides)
 * "Choose Your Own Content" Based on User Input 
 * Lead Generation Based on User Input 
 * Decision Tree Process 
 * User Based Tutorials (Skip steps based on user level)
 * Surveys


== Installation ==

1. Upload the `slidedeck` folder and all its contents to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Create a new SlideDeck from the new menu in the control panel sidebar
1. Insert a SlideDeck in your post or page by clicking on the `Embed a SlideDeck` button in the rich text editor or the button in the sidebar in the post/page view. 

You can also place a SlideDeck in your template or theme via the PHP command slidedeck(); Just pass the ID of the SlideDeck you want to render and an associative array of the styles you want to apply to the SlideDeck itself. For example:

	`<?php slidedeck(12,array('width'=>'100%','height'=>'300px')); ?>`
	
Where 12 is the SlideDeck's ID. You can also see this code snippet in the sidebar of the SlideDeck editing interface.

== Frequently Asked Questions ==

The best place for getting your questions answered is via our [Get Satisfaction support thread](http://www.getsatisfaction.com/slidedeck). 

= I can't add a slide, it just shows me a wierd looking page =

Make sure that you are running up-to-date plugins on your website and that they are all compatible with the version of WordPress you are running. We find that the most common cause of any problem with getting SlideDeck working on a website has to do with a plugin that isn't written for the version of WordPress you are running and it causes a conflict or a JavaScript failure (which prevents our JavaScript from loading).

= I just purchased the Pro version, but I don't see a feature that the SlideDeck JavaScript library has =

We try and move the features we develop for the JavaScript library over to the WordPress plugin as soon as possible, but it takes some time to integrate and create an interface to use the feature. Keep an eye on your Inbox for updates; we'll let you know when the plugin is updated with the feature. 

= I just put a SlideDeck on my site and I'm getting tons of "Warning: cannot yet handle MBCS in html_entity_decode()!" errors, whats going on? =

This error appears if you are running PHP 4 on your server. This is a bug in PHP 4 itself that is causing this error to occur. Please contact your web hosting company and ask them how to upgrade your web server to PHP 5; this is usally a quick switch flip in your web host's control panel. 

= My WYSIWYG editors are not loading =

Make sure you are running up-to-date plugins. Some older versions of common plugins that add buttons to the WYSIWYG editor (such as Vipers video plugin) may cause the WYSIWYG editor to error out when it is initializing, preventing much of the JavaScript on the page from working.

= My SlideDeck isn't loading =

Make sure that your theme is running both the `wp_head();` command and the `wp_footer();` command, otherwise SlideDeck will not work properly. If you are manually loading jQuery in your template or theme after the `<?php wp_head(); ?>` command, you will overwrite the SlideDeck plugin extension. Make sure that `<?php wp_head(); ?>` is the last thing loading in your `<head>` tag. If you need to load JavaScript for your WordPress theme, make sure you are using the `wp_enqueue_script();` command in your theme's `functions.php` file. See http://codex.wordpress.org/Function_Reference/wp_enqueue_script for more information on how to implement this.

= I can't get SlideDeck working with Buddypress =

We've made some serious improvements to the way we are implementing interface elements, doing previews, etc. in version 1.2 that may resolve some of these issues. Please try the latest version of SlideDeck and let us know how it is working for you.

= Pieces of the SlideDeck look wierd on my website =

Sometimes you might see extra spaces between links or "closed" slides, this is usually due to a conflict with the WordPress theme you are running on your website and the SlideDeck CSS. We are constantly working to improve the stability of the CSS of SlideDeck, but sometimes there are some themes that do some CSS definitions we cannot accommodate for. We recommend looking into getting [Firebug](http://www.getfirebug.com) for [Firefox](http://www.firefox.com) and investigate the elements that look strange and try and correct your theme's conflicting CSS.

= I want to put video in my SlideDeck, how do I do that? =

We updated the WYSIWYG editor implementation method in version 1.2 to be more compatible with other plugins and to process shortcodes. Please be advised that it is up to the plugin author to choose to display their plugin buttons in WYSIWYG editors used outside of the Posts and Pages section of WordPress. So, you still may not see your shortcode buttons, but we do still process any shortcodes entered manually. Any sort of media though can still be embedded in a SlideDeck. Just click on the HTML edit tab for the slide and copy-and-paste the embed code for a video into a SlideDeck's content area.

= I've placed a video in my SlideDeck, but it shows through the SlideDeck, even on closed slides =

Flash doesn't play nicely all the time with fancy interactions like SlideDeck and the like, luckily there's an easy fix. Just add the the `wmode="opaque"` parameter to your embed code. For example, with a YouTube video embed code, this will change the code from this:

`<object width="480" height="385"><param name="movie" value="http://www.youtube.com/v/au3-hk-pXsM&hl=en_US&fs=1&"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/au3-hk-pXsM&hl=en_US&fs=1&" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="480" height="385"></embed></object>`

to this:

`<object width="480" height="385"><param name="movie" value="http://www.youtube.com/v/au3-hk-pXsM&hl=en_US&fs=1&"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><param name="wmode" value="opaque"></param><embed src="http://www.youtube.com/v/au3-hk-pXsM&hl=en_US&fs=1&" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="480" height="385" wmode="opaque"></embed></object>`

Take note of the addition of the `<param name="wmode" value="opaque"></param>` tag inside the `<object>` tag and the `wmode="opaque"` attribute on the `<embed>` tag.

= I want to customize the way my SlideDeck looks, how do I do that? = 

We provide a few skins with SlideDeck, all of which you can edit. You will find all the skins located in the "skins" folder in the SlideDeck WordPress Plugin folder. Edit the CSS files in the skins folders to make changes. Just be sure and backup your changes before updating your plugin, otherwise you'll loose them!

== Screenshots ==

1. The SlideDeck editing view. Create a SlideDeck, add slides, re-order slides and change SlideDeck settings with the sidebar modules.
2. Preview your SlideDeck before you insert it into a post or into your theme.
3. SlideDeck integrates seamlessly in your post and page editing views. Just click on the `Embed a SlideDeck` button in the WYSIWYG editor and choose a SlideDeck you have created from the list in the dialog box. You can also specify dimensions for how the SlideDeck appears in your post.
4. Add your SlideDeck directly to your template and make it a fixed feature of your design!
5. Insert SlideDecks directly into your posts or pages.
6. NEW! Smart SlideDecks - create a SlideDeck out of your pre-existing posts! Choose the most recent posts on your blog, featured posts, or popular posts (requires at least WordPress 2.9+) and even filter by category.
7. A preview of the Smart SlideDeck. Three different navigation layouts available - Post Titles, Post Dates, and Dots.

== Changelog ==

= 1.3.3 =
* BETA Feature: Widget deployment option for users running WordPress 2.8+

= 1.3.2 =
* Modified the way that we were processing dates to be more globally compatibile with different time zones.
* Bug fix for WordPress 2.7 to make accommodations for the lack of the esc_html() function.
* Bug fix to properly handle getting the first image from a post's gallery. We were not accommodating for a keyed array return and it was preventing access to the first element in the returned array.
* Bug fix for WordPress 2.9.2 that was preventing the "Upload/Set" button for slide backgrounds from opening the media upload dialog.
* Bug fix for adding media to a new, un-saved SlideDeck. Implemented new method for creating SlideDecks that will create a new SlideDeck entry in the database with the "auto-draft" post_status value to have a legitimate post entry to associate media attachments to - method modeled after the way WordPress handles regular post creation.

= 1.3.2beta1 =
* Linked images in Smart SlideDecks to their article's permalink
* Made IE stylesheet conditionals for skins more specific to prevent IE8 specific styles from accidentally overriding IE7 specific styles
* Improved RSS Smart SlideDeck XML parsing for access to the RSS feed's content area
* Changed RSS XML loading to use WordPress' built in wp_remote_fopen() function for better compatibility with servers that do not have allow_url_fopen set to "On"
* Added additional exclusions for RSS image filtering to further filter out unwanted imagery from getting picked up as a post's summary image
* Made an exclusion for BuddyPress to get around the missing easing in certain versions of the ScrollTo library that comes with BuddyPress
* Improved SlideDeck slide content processing to prevent plugins that append content to posts from doing so on SlideDeck slide content
* Bug fix for TinyMCE editors that was causing shortcodes to come back as rendered markup instead of the shortcode
* Bug fix for the way that content was being loaded for SlideDecks and SlideDeck slides that caused a conflict in comment open/close status
* Bug fix for GLOB_BRACE issue; GLOB_BRACE was unnecessary for the command so it was removed

= 1.3.1 =
* Bug fix for TinyMCE editors to properly process HTML tags and prevent paragraphs from being accidentally removed
* Fixed a bug that was causing comments to appear in posts that had comments turned off and had a Smart SlideDeck embeded

= 1.3.0 =
* Major change to the way SlideDeck stores data in the database, we are no longer using any custom tables, now we use custom post types to store SlideDecks!
* Now compatible with WordPress 3.0!
* Improved image scrubbing for Smart SlideDecks
* Improved in-line documentation
* Hooked up slide backgrounds to the media library (PRO feature only)

= 1.2.2 =
* Pathing fixes for "preview" and "add another slide" buttons
* Added button overlay to prevent AJAX button utilization before JavaScript has had a chance to map events

= 1.2.1 =
* Fixed image parsing for dynamic SlideDecks to be more reliable
* Added "validate images" option for dynamic SlideDecks to help eliminate possible advertisement images
* Fixed stripslashes issue
* Updated plugin URL and directory referencing plugins to help improve reliability for deployments outside of top level domain
* Fixed issue with new skin loading method that wasn't accommodating for multiple SlideDecks in a single post
* Added new BETA feature to add backgrounds to slides for regular SlideDecks (PRO feature only)!

= 1.2.0 =
* NEW! Vertical Slides (PRO feature only)
* NEW! RSS feed Smart SlideDecks (PRO feature only)
* NEW! Skin support for regular SlideDecks
* Upgraded JavaScript core plugin to 1.1.6 to improve cross-browser compatibility
* New and improved preview method that is less hacky and more cross-browser and cross-platform compatible
* New and improved skin loading methods for better cross-browser compatibility and greater plugin stability
* New and improved WYSIWYG implementations for better compatibility
* Added shortcode processing support for SlideDecks
* Improved WordPress 3.0 compatibility. NOTE: Unfortunately the new WordPress 3.0 core will not show the "Insert into post" button in the Upload/Insert media dialog. See the [Get Satisfaction support thread](http://www.getsatisfaction.com/slidedeck/topics/media_insert_buttons_and_wordpress_3_0) for more details. 
* Tons of little internal code optimizations, improvements and bug fixes

= 1.1.4 =
* Bug fix for IE display compatibility

= 1.1.1 =
* Bug fixes to dark and light skin JavaScript when displaying more than one Dynamic SlideDeck on the page.
* Bug fixes for pathing to fix TinyMCE issues some users were experiencing.
* Added SlideDeck Lite JavaScript core options to static SlideDeck interface: Auto Play, Hide Slide Title Bars, Loop slides

= 1.1.0 =
* Added Smart SlideDecks - create SlideDecks automatically based off of content from your posts! Select recent, featured and popular posts (requires at least WordPress 2.9) to display in your Smart SlideDeck. Place your Smart SlideDeck in your theme as an automatically updating feature.
* Added post sidebar options to feature a post in Smart SlideDecks and customize a post's Smart SlideDeck title
* Added core changes for skin and template handling in preparation for the skin library coming soon!
* Updated SlideDeck Lite library to new GPL licensed 1.1.5
* IE Interface fixes and preview improvements
* Implemented wp_nonce security measures where appropriate
* Made function prefixing more consistent
* Implemented numerous SQL protection implementations
* Modification to database table structure for better option storage - remember to BACKUP your database before installing or upgrading any plugin!

= 1.0.35 =
* Modified preview to use UTF-8 character set.
* Specified removal of background image from preview.
* Added UTF-8 character set decoding to template processing for proper UTF-8 output.
* Specified text-align:left in preview to override odd text-align:center default.
* Changed JavaScript and Stylesheet inclusion methods to be more reliable.

= 1.0.31 =
* Bug fix for HTML/WYSIWYG editor syncing.

= 1.0.3 =
* Fixed minor bug that prevented HTML editing view from syncing with WYSIWYG editors.

= 1.0.2 =
* Updated database schema to use UTF-8 collation and character sets for title and content fields on SlideDecks and SlideDeck slides for better international language support.

= 1.0.1 =
* Update of JavaScript library to version 1.1.3.
* Changed method of creating SlideDeck instances to use direct Class instancing instead of extended jQuery method.

= 1.0 =
* Addition of media uploads to SlideDeck slides and per SlideDeck gallery associations.
* Addition of SlideDeck preview interaction.
* Addition of theme PHP snippet sidebar module.
* Addition of embed button in rich editor view sidebar for better visibility.
* Lots of bug fixes for data storage, character escaping and encoding.
* Lots of bug fixes for proper use of plugin in WordPress 2.7.x environments - removed jQuery `.live()` references and adapted jQuery UI skinning for older version of jQuery UI that comes with WordPress 2.7.x. 

= 0.5 =
* Initial beta release with basic SlideDeck creation, management, and placement.

== Upgrade Notice ==

= 1.3.2 =
Bug fix patch. Made some BuddyPress exceptions, improved RSS feed reading.

= 1.3.2beta1 =
Private beta release primarily for bug fixes

= 1.3.1 =
Update to the TinyMCE visual/html editor bug that caused paragraphs to be removed from the content area

= 1.3.0 =
Major database storage change - using custom post types! WordPress 3.0 compatibility!

= 1.2.2 =
Hotfix again! Sorry guys, last fix introduced some pathing problems. I've cleared these up in this release, so please update.

= 1.2.1 =
Hotfix! Couple of bug fixes from the latest 1.2.0 release. Update now!

= 1.2.0 =
Major update! NEW features: RSS Smart SlideDecks (PRO), Vertical Slides (PRO), Skin Support! Lots of compatibility bug fixes!

= 1.1.3 =
Bug fix for IE display.

= 1.1.1 =
Bug fixes and addition of new SlideDeck Lite features to static SlideDecks.

= 1.1.0 =
Smart SlideDecks and skin system core added! SlideDeck Lite JavaScript updated to new GPL licensed 1.1.5 version. 

= 1.0.35 =
Preview changes, UTF-8 decoding on output, and changed JavaScript and Stylesheet inclusion methods to be more reliable. 

= 1.0.31 =
Bug fix for WYSIWYG editors when saving.

= 1.0.3 =
Bug fix for slide editors. Fixed problem where updating/saving a SlideDeck when in the HTML editing mode erased the HTML content.

= 1.0.2 =
Updated the way we store titles and content for better international character support.

= 1.0.1 =
Updated plugin to use SlideDeck 1.1.3 which adds a more reliable implementation method.

= 1.0 =
Gold release, please upgrade your beta plugin now.

= 0.5 =
Initial beta test release.
