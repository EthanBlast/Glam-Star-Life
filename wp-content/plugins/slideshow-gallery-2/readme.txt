=== Slideshow Gallery ===
Contributors: Cameron Preston, Antonie Potgieter
Donate link: http://cameronpreston.com/projects/plugins
Tags: wordpress plugins, wordpress slideshow gallery, slides, slideshow, image gallery, images, gallery, featured content, content gallery, javascript, javascript slideshow, slideshow gallery
Requires at least: 2.8
Tested up to: 2.9.2
Stable tag: 1.1.4

'Slideshow Gallery' 2 is a image viewing solution that integrates with the WordPress image upload and gallery application. Thumbs and Lightbox.

== Description ==

Version 2 of 'Slideshow Gallery' this a photo viewing browsing that integrates seemlessly with your normal WordPress image upload and gallery.  They key attributes here is a pretty Ajax and Jquery motion. Scrolling thumbnails, captions, and lightbox.

Flexible, configurable and easy to use. Embed-able and hardcode-able and improved. To embed into a post/page, simply insert <code>[slideshow]</code> into its content with optional <code>post_id</code>, <code>exclude</code>, and <code>caption</code>  parameters. To hardcode into any PHP file of your WordPress theme, simply use <code><?php if (class_exists('Gallery')) { $Gallery = new Gallery(); $Gallery -> slideshow($output = true, $post_id = null); } ?></code> and specify the required <code>$post_id</code> parameter accordingly.

You will not be able to use "Slideshow Gallery" if you wish to use "Slideshow Gallery 2"

== Installation ==

Installing the WordPress Slideshow Gallery 2 plugin is very easy. Simply follow the steps below.

1. Extract the package to obtain the `slideshow-gallery-2` folder
1. Upload the `slideshow-gallery-2` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Configure the settings according to your needs through the 'Slideshow' > 'Configuration' menu
1. Add and manage your slides in the 'Slideshow' section (Or just use the built in wordpress gallery)
1. Put `[slideshow post_id="X" exclude="" caption="on/off"]` to embed a slideshow with the images of a post into your posts/pages or use `[slideshow custom=1]` to embed a slideshow with your custom added slides or `<?php if (class_exists('Gallery')) { $Gallery = new Gallery(); $Gallery -> slideshow($output = true, $post_id = null); }; ?>` into your WordPress theme

== Frequently Asked Questions ==

= Can I display/embed multiple instances of the slideshow gallery? =

Yes, you can, but only one slideshow per page.

= What if I only want captions on some of my pages

Set your default captions to off; for any slideshow you put on your page use `[slideshow caption="on"]`

= What if my configuration isn't showing up? =

You're most likely not running PHP5. Talk to your host to upgrade or switch your hosting provider. PHP5 is eleventy years old.

= How do I find the numbers to exclude? =

Not as easy as it used to be! Go into the Media Library. Choose an image you want to exclude and click on it and notice your address bar: "/wp-admin/media.php?action=edit&attachment_id=353". Therefore, [slideshow exclude="353"]

== Screenshots ==

1. Slideshow gallery with thumbnails at the bottom.
2. Slideshow gallery with thumbnails turned OFF.
3. Slideshow gallery with thumbnails at the top.
4. Different styles/colors.

== Upgrade Notice ==


== Changelog ==
= 1.1.4 =
* Fixed the thumbnails to display at startup for Chrome and Safari
* Fixed bug in the js file for lightbox

= 1.1.3 =
* Created it so captions was an option that you can turn on or off from [ slideshow ]

= 1.1.2 =
* Upgrade Manage Slides to work with the plugin nomenclature.

= 1.1.1 =
* Made it so if you pull a slideshow from a different post it still allows comments.
* Updated FAQs

= 1.1 = 
* Made it so the slideshow worked :)

= 1.0 =
* Initial release of the WordPress Slideshow Gallery 2 plugin
* Based on the popular and amazing slideshow: http://wordpress.org/extend/plugins/slideshow-gallery/

== Upgrade Notice ==

= 1.1.4 =
If you want your plugin thumbnails to work with Safari and Chrome I'd upgrade :)