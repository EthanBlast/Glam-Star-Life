=== Superslider-PostsinCat ===
Contributors: Daiv Mowbray
Plugin URI: http://wp-superslider.com/superslider/superslider-postsincat
Donate link: http://wp-superslider.com/support-me/donate/
Tags:widget, posts, category, animation, animated, gallery, scroller, mootools 1.2, mootools, slider, superslider
Requires at least: 2.7
Tested up to: 3
Stable tag: 1.6

This widget dynamically creates a list of posts from the active category. Displaying the first image and title. 

== Description ==

This widget dynamically creates a list of posts from the active category. Displaying the first image and title. It will display the first image in your post as a thumbnail,it looks first for an attached image, then an embedded image then if it finds the image, it grabs the thumbnail version. Oh, and by the way, it's an animated vertical scroller, way cool.
If the post has no image the widget uses a default image. This works best with the plugin Widget logic. Just set the widget to is_single() || is_category() and all your single viewed posts will have a scrolling thumbnail list of other posts in this category.

**credits:**

* mootools - [Mootools](http://mootools.net/ "Your favorite javascript framework")
* slideBox - [slideBox](http://www.mootools.nl/slideBox/ "Riaan Los")
* Widget logic you will want this - [Widget logic](http://wordpress.org/extend/plugins/widget-logic/ "Widget logic ")

**Support**

If you have any problems or suggestions regarding this plugin [please speak up](http://support.wp-superslider.com/forum "support forum")

**Other Plugins**
Download These Plugins here:

* [SuperSlider](http://wordpress.org/extend/plugins/superslider/ "SuperSlider")
* [SuperSlider-Show](http://wordpress.org/extend/plugins/superslider-show/ "SuperSlider-Show")

**NOTICE**

* The downloaded folder's name should be superslider-postsincat
* Also available for [download from here](http://wp-superslider.com/downloadsuperslider/superslider-postsincat-download "superslider-postsincat plugin home page").
* Probably not compatible with plugins which use jquery. (not tested)


**Features**

* pull images from posts in this category 
* animated scroller
* Control max number of images.
* Images link to post
* control size of scroll area
* Widget title is dynamic, writes category Name
* works also for non attached images in your library
* presents default image if none in post.
* Uses WordPress native media / images
* Control scroll speed
* Control animation type

**Demos**

This plugin can be seen in use here:

* [Demo 1](http://wp-superslider.com/wp-plugin-demos/superslider-postsincat/postsincat-elk "Demo")


== Screenshots ==

1. ![SuperSlider-postsincat sample](screenshot-1.png "SuperSlider-postsincat sample")
2. ![SuperSlider-postsincat widget screen](screenshot-2.png "SuperSlider-Show widget screen")

== Installation ==

* Unpack contents to wp-content/plugins/ into a **superslider-postsincat** directory
* Activate the plugin,
* Add and configure widget -SS Posts in Cat-
* Add: is_single() || is_category() or just one of those: is_single() to the Widget logic field.
* (optional) select to disable css, then add the css file contents to your theme style.css.

== Upgrade Notice ==

You may need to re-save your settings/ options when upgrading.
1.6 has some changes to the css files.

== USAGE ==

If you are not sure how this plugin works you may want to read the following.

* First ensure that you have uploaded all of the plugin files into wp-content/plugins/superslider-postsincat folder.
* Go to your WordPress admin panel and stop in to the widgets control page. Activate the SS Posts in Cat widget.
* Configure the widget.
* Go view a single post on the front side, you should see the superslider-postsincat widget in your side bar.
* Optional: move the file postincat.css to your theme folder and the images inside of superslider-postincat/css/images to yourtheme/images, select "Load css from your theme folder" in the widget options panel. This will preserve any modifications you make to the css or image files, if and when there is a plugin update.
* Recommended : The css is set up for thumbnails of 150 x 150 pixels, to display 3 images at a time with the default css set the widget height to 552. If you have your thumbnail sizes set differently in the WordPress admin, then you will need to adjust the css and the widget height accordingly.


== OPTIONS AND CONFIGURATIONS ==

Available under > settings > SuperSlider-Show

* theme css file to load or not (you may want to add the classes to your theme css and reduce file requests to the server).
* widget title.
* Add Category name to the widget title (this will change dynamically according to the active category).
* number of images to pull from category.
* height of display area.
* Load css from your theme folder.

== To Do ==

* Add option to link images to a lightbox 
* Add option to remove the title(done)
* resolve issues with posts in multiple categories
				

== Report Bugs Request / Options / Functions ==

* Please use the support system at http://support.wp-superslider.com
* Or post to the wordpress forums

== Frequently Asked Questions ==	

**Why isn't my superslider-postsincat widget not working?**

>*Did you remember to also add the widget-logic plugin?.
>*While reading the source code of your website files header look to see if another plugin is using jquery. This may cause a javascript conflict. Jquery and mootools are not always compatible.

**How do I change the style of the widget?**
  
>edit the css file which comes with the plugin.

**The stylesheet doesn't seem to be having any effect? **
 
>Check this url in your browser:
>http://yourblogaddress/wp-content/plugins/superslider-postsincat/plugin-data/superslider/ssPostinCat/default/default.css
>If you don't see a plaintext file with css style rules, there may be something wrong with your .htaccess file (mod_rewrite). If you don't know how to fix this, you can copy the style rules there into your themes style file and deactivate the css in your widget options.

**How do I use different graphics and symbols for the scrolling buttons? **

>You can upload your own images to
>http://yourblogaddress/wp-content/plugins/superslider-postsincat/plugin-data/superslider/ssPostinCat/theme/


== CAVEAT ==

Currently this plugin relies on Javascript to create the scroller.
If a user's browser doesn't support javascript the scroller will display normally as a list of images.

== Changelog ==

* 1.6 (2010/04/07)

    * changed function name image_by_scan to ss_image_by_scan to avoid conflicts
    * adjustments to the css files
    
* 1.5 (2010/03/13)

    * fixed a bug with array extraction
    
* 1.4 (2010/03/10)

    * updated for WP 2.9.
    * added as submenu for superslider

* 1.3.2 (2009/12/30)

    * fixed a php function structure issue.
    * added WP 2.9 post_thumbnail usage.
    * Added control scroll speed
    * Added control animation type

* 1.3.1 (2009/09/21)

    * fixed css to run on sprites

* 1.3 (2009/07/15)

    * added themes option
    * added image size option
    * fixed minor bugs
    * Updated mootools to 1.2.3 
    
* 1.0 (2009/04/11)

    * first public launch

---------------------------------------------------------------------------