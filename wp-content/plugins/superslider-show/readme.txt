=== SuperSlider-Show ===
Contributors: Daiv Mowbray
Plugin URI: http://wp-superslider.com/superslider/superslider-show/
Donate link: http://wp-superslider.com/support-me/donate/
Tags: slideshow , animation, animated, gallery, mootools 1.2, mootools, slider, superslider, slideshow2, photos
Requires at least: 2.6
Tested up to: 3
Stable tag: 2.7.7

Animated Gallery slideshow uses Mootools 1.2 javascript replaces wordpress gallery with a slideshow.

== Description ==

SuperSlider-Show is your Animated slideshow plugin with automatic thumbnail list inclusion. Now displays nextgen galleries as a slideshows.
This slideshow uses [Mootools](http://mootools.net/ "Your favorite javascript framework") 1.2 javascript to replace your gallery with a Slideshow. 
Highly configurable, theme based design, css based animations, automatic minithumbnail creation. 
Shortcode system on post and page screens to make each slideshow unique. 
Built upon [Slideshow2](http://www.electricprism.com/aeron/slideshow/ "Your favorite slideshow"). 
Degrades gracefully with plugin removed / disabled.
Compatible with WordPress system default gallery shows. 
New Features v2.2: improved theme controls, pull images from category or featured custom field. 


**Features**

* Endless image animation/transition possibilities.
    
    * Endless custom css based transition possibilities.
    * 20+ sample css transitions included.
    * 5 base transion styles.
    * 9 transition types per style.
    * All eliments transition controlled.
    
* now also displays your most recent posts in a slideshow.
* now also displays your nextgen gallery in a slideshow.
* get random images from category or site for slideshow.
* get images from category id number for slideshow..
* get images from custom field: featured for slideshow.
* get images from list of posts for slideshow.
* get all images from a folder for slideshow.
* 4 base visual themes built in.
* complete global control from options page.
* full short code over ride per show.
* pull images from any post/page to any post/page.    
* Control transition time, image display time.
* Animated controller
* Animated captions
* Link captions to post / page.
* Link each image to lightbox or attachment or parent post/page.
* Link whole show to any destination.
* Uses WordPress native media / images.
* Creates custom slideshow images and mini-thumbnails.

**Demos**

This plugin can be seen in use here:

* [Demo 1](http://wp-superslider.com/2008/slideshow-demo-1 "Demo 1")
* [Demo 2](http://wp-superslider.com/2008/slideshow-demo-2 "Demo 2")
* [Demo 3](http://wp-superslider.com/2008/slideshow-demo-3 "Demo 3")
* [Demo 4](http://wp-superslider.com/2008/slideshow-demo-4 "Demo 4")
* [Demo 5 multi-color flash](http://wp-superslider.com/2008/slideshow-demo-5-multi-color-flash "Demo 5 multi-color flash")
* [SlideShow fromfolder demo](http://wp-superslider.com/2009/slideshow-fromfolder-demo "SlideShow fromfolder demo")
* [Category photos slideshow Black](http://wp-superslider.com/2009/category-photos-slideshow "Category photos slideshow Black")


**credits:**

* mootools - [Mootools](http://mootools.net/ "Your favorite javascript framework")
* slideshow2 - [Slideshow2](http://www.electricprism.com/aeron/slideshow/ "Your favorite slideshow")
* squeezebox - Harald Kirschner [digitarald.de](http://www.digitarald.de "digitarald.de")
* slimbox - Christophe Beyls [digitarald.de](http://www.digitarald.de "digitarald.de")


**Sample Custom transitions**

 * if you look in the folder, plugins/superslider-show/plugin-data/superslider/ssShow/transitions/ you will find about 20 different demo css transitions.

**Support**

If you have any problems or suggestions regarding this plugin [please speak up](http://support.wp-superslider.com/forum/superslider-show "support forum")

**Other Plugins**
Download These SuperSlider Plugins here:

* [SuperSlider](http://wordpress.org/extend/plugins/superslider/ "SuperSlider")
* [Superslider-PostsinCat](http://wordpress.org/extend/plugins/superslider-postsincat/ "Superslider-PostsinCat")
* [SuperSlider-MooFlow](http://wordpress.org/extend/plugins/superslider-mooflow/ "SuperSlider-MooFlow")
* [SuperSlider-Login](http://wordpress.org/extend/plugins/superslider-login/ "SuperSlider-Login")

**NOTICE**

* The downloaded folder's name should be superslider-show
* Also available for [download from here](http://wp-superslider.com/downloadsuperslider/superslider-show-download "superslider-show plugin home page").
* Probably not compatible with plugins which use jquery. (not tested)


== Screenshots ==

1. ![SlideShow sample](screenshot-1.png "SlideShow sample")
2. ![SuperSlider-Show options screen](screenshot-2.png "SuperSlider-Show options screen")
3. ![SuperSlider-Show MetaBox on post screen](screenshot-3.png "SuperSlider-Show MetaBox screen")

== Installation ==

* Unpack contents to wp-content/plugins/ into a **superslider-show** directory
* Activate the plugin,
* Configure global settings for plugin under > settings > SuperSlider-Show
* Create post/page ,Add WordPress gallery shortcode, or slideshow shortcode.
* (optional) move SuperSlider-Show plugin sub folder plugin-data to your wp-content folder,
	under  > settings > SuperSlider-Show > option group, File Storage - Loading Options
	select "Load css from plugin-data folder, see side note. (Recommended)". This will
	prevent plugin uploads from over writing any css changes you may have made.

== Upgrade Notice ==

You may need to re-save your settings/ options when upgrading
Version 2.7.7 has some css changes with the captions

== USAGE ==

If you are not sure how this plugin works you may want to read the following.

* First ensure that you have uploaded all of the plugin files into wp-content/plugins/superslider-show folder.
* Go to your WordPress admin panel and stop in to the plugins control page. Activate the SuperSlider-Show plugin.
* Create a new post, use the WordPress built in media uploader, (upload some images).
* Click on insert gallery from the media uploader popover panel.
* you should now have the shortcode [gallery] in your post.
* Publish your new post

You should be able to view your new slide show in the new post.
You can adjust how the slide show looks and works by making adjustments in the plugin settings page. (SuperSlider-Show), or personalize the individual show with the shortcode helper.



== OPTIONS AND CONFIGURATIONS ==

Available under > settings > SuperSlider-Show

* theme css files to use
* shortcode tag to use (gallery or slideshow)
* post id to pull images from (if not actual post)
* transition type
* transition speed
* display time
* lightbox on images on or off
* to load or not Mootools.js
* css files storage loaction
* **many more Advanced options**

----------
Available in the shortcode tag:

* id ="any comma separated list of post ids" or "featured" or "category:5" or "random@5" or "nextgen-5"
* show_class="featured"
* css_theme="theme"
* first_slide="0"
* href="www.yourcooldoiman.com"
* show_type="kenburns/push/fold/default" (one of)
* height="400"
* width="200"
* transition="elastic:In:Out"
* thumbnails="true"
* image_size="thumbnail/slideshow/medium/large/full"
* delay="milliseconds"
* duration="milliseconds"
* center="true"
* resize="true"
* overlap="true"
* random="true"
* loop="true"
* linked="true"
* fast="true"
* captions="true"
* controller="true"
* paused ="true"
* exclude = "any comma separated list of images"
* **many more Advanced options**


== Themes ==

Create your own graphic and animation theme based on one of these provided.

**Available themes**

* default (Thumbs set to 150px x 150px)
* blue (Thumbs set to 50px x 50px)
* black (Thumbs set to 150px x 150px)
* custom (Thumbs set to 150px x 150px vertical right side )

== To Do ==

* create function to include slideshow in site theme files.	
* proper order option for the nextgen function.
* add option to use parent post excerpt as image caption. (DONE)

== Report Bugs Request / Options / Functions ==

* Please use the support system at http://support.wp-superslider.com
* Or post to the wordpress forums

== Frequently Asked Questions ==	

**Why isn't my slideshow working?**

>*You first need to check that your web site page isn't loading more than 1 copy of mootools javascript into the head of your file.
>*While reading the source code of your website files header look to see if another plugin is using jquery. This may cause a javascript conflict. Jquery and mootools are not always compatible.

**How do I change the style of the slideshow?**
  
>I recommend that you move the folder plugin-data to your wp-content folder if you already have a plugin-data folder there, just move the superslider folder. Remember to change the css location option in the settings page for this plugin. Or edit directly: **wp-content/plugins/superslider-show/plugin-data/superslider/ssShow/theme/theme.css** (where theme is the theme you have chosen) Alternatively, you can copy those rules into your WordPress themes, style file. Then remember to change the css location option in the settings page for this plugin.
  

**The stylesheet doesn't seem to be having any effect? **
 
>Check this url in your browser:
>http://yourblogaddress/wp-content/plugins/superslider-show/plugin-data/superslider/ssShow/theme/theme.css (where theme is the theme you have chosen)
>If you don't see a plaintext file with css style rules, there may be something wrong with your .htaccess file (mod_rewrite). If you don't know how to fix this, you can copy the style rules there into your themes style file.

**How do I use different graphics and symbols for collapsing and expanding? **

>You can upload your own images to
>http://yourblogaddress/wp-content/plugins/superslider-show/plugin-data/superslider/ssShow/theme/images/ (where theme is the theme you have chosen)


== CAVEAT ==

Currently this plugin relies on Javascript to create the slide show.
If a user's browser doesn't support javascript the gallery will not display.

== Changelog ==

* 2.7.7 (2010/06/28)
  
  * Fixed echo show, for return (bug found by user Loic)
  * Added option: post-excerpt as caption text
  * Wrapped post-excerpt as caption text in a paragraph
  * Edited css files, default, blue, custom, black
  
        * added classes: .slideshow-captions p , .slideshow-captions a
        * various edits to the cations class
        * custom.css now has a sample of auto height captions
  

* 2.7.6 (2010/06/26)
  
  * Added option: post category name as caption title (user request)
  
* 2.7.5 (2010/06/20)
  
  * Added shortcode option to pull recent posts (user request)
  * Added option to limit number of recent posts per category
  * improved the id type logic
  * Added text file for the recent posts option

* 2.7.4 (2010/06/02)

  * fixed link to settings page
  * added save options upon deactivation option

* 2.7.3 (2010/05/23 )

  * Changed the media options page layout

* 2.7.2 (2010/04/10 )
  
  * Fixed a bug with tool tips in admin
  * Fixed bug with show linkto global
  
* 2.7 (2010/04/07 )
  
  * Added nextgen gallery integration (use as slideshow for nextgen)
  * fixed an issue with the first slide option
  * upgraded the admin post screen meta box shortcode helper
  * Added the random attachment option in shortcode (random@)
  * Added set of css animations under plugin-data folder
  
* 2.6 (2010/02/26)
  
  * Fixed various bugs
  * upgraded functions for WP 3.0
  * Improved superslider-base integration
  
* 2.5.3 (2009/12/9)
  
  * Fixed another bug in the fromfolder option (image size)
  
* 2.5.2 (2009/11/26)
  
  * Fixed a bug in the fromfolder option

* 2.5.1 (2009/11/10)
  
  * Fixed issues with the lightbox system

* 2.5 (2009/10/26)
  
  * Added the featured post option (see howto-featured.txt)
  * Added the category post option (see howto-category.txt)
  * Added theme changer to shortcode (now set different theme per show, add your own.)
  * Added limit image option (limits total number of images pulled for show.)
  * Fixed bug, short code multiple id's error
  * Fixed html error , slide id repeated, now unique
  
* 2.0 (2009/07/15)

  * Added custom thumb extension option for the fromfolder option
  * Added option to change controller activation keys
  * Added option for properties list, ie: 'href', 'rel', 'rev', 'title'
  * Updated mootools to 1.2.3 

* 1.9 (2009/07/25)
    
  * Upgraded to slideshow2!r147

* 1.8 (2009/06/05)

  * Added the From Folder option - pulls all images from a defined folder.
  * Added caption, close button to base lightbox popover.
  * Fixed minithumb creation options, size and crop now work.
  * Added Custom Slideshow image creation and options for WP-system image upload.
  * Major organization changes to the options page.(added tabs)
  * Minor organization changes to the options page.
  * Squeezebox popover now stops and starts slideshow when opened/closed.
  * Slimbox pop over now requires the SuperSlider-slimbox plugin.
  * Slimbox popover, next and previous image links now work - sort of.
  * upgraded the slideshow2 script to Slideshow2r147

* 1.7 (2009/02/14)
	
  * Added Thumbnail creation 
  * Added Thumbnail use which size option

* 1.6 (2009/02/10) no public launch
	
  * Added popover image size option
  * Added slimbox popover module
  * Added squeezebox popover module
  * Fixed the script enqueue 
  * Removed the milkbox module
	
* 1.5 (2009/02/03)
	
  * Added insert at cursor for the shortcode metabox
	
* 1.4 (2009/01/15)
	
  * fixed shortcode array issue
	
* 1.3 (2009/01/15)
	
  * added thumbnail frame option
  * integrated with SuperSlider-Base
  * fixed 50+ php warnings

* 1.2 (2008/12/19)
	
  * added link each image to lightbox or attachment or parent
  * added Link whole show to any destination
  * added exclude images option
  * made script php4 compatible
  * improved the metabox layout (insert shortcode)
  * added shortcode option, color list for flash transition
		(comma separated list of hex colors)

* 1.1 (2008/12/11)
	
  * added meta box to post and page screens for easy shortcode entry
  * improved php coding
	
* 1.0 rc (2008/12/04)
	
  * fixed shortcode transition type
  * fixed no thumbs option

* 0.7.0_beta (2008/12/01)
	
  * fixed lightbox pop over (works with built in lightbox)
  * changed the theme structure to be easier to grow.
  * added more options to the image resize option

* 0.6.0_beta (2008/12/01)
	
  * added full short code support
  * added multiple shows per page/post
  * more code refinement

* 0.3.0_beta (2008/11/15)
	
  * reduced database calls
  * cleaned code

* 0.1.0_beta (2008/10/26)

    * first public launch

---------------------------------------------------------------------------