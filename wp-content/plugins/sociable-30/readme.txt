=== Sociable for WordPress 3.0 ===
Contributors: tompokress
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=H3YD2QYUJH8TY
Tags: sociable,sexy bookmarks,sexy,social,bookmark,social bookmarks,social bookmark,bookmarks,bookmarking,social bookmarking,sharing,sociable,share,sharethis,Add to favorites,BarraPunto,Bitacoras.com,BlinkList,blogmarks,Blogosphere,blogtercimlap,Faves,connotea,Current,del.icio.us,Design Float,Digg,Diigo,DotNetKicks,DZone,eKudos,email,Facebook,Fark,Fleck,FriendFeed,FSDaily,Global Grind,Google,Google Buzz,Gwar,HackerNews,Haohao,HealthRanker,HelloTxt,Hemidemi,Hyves,Identi.ca,IndianPad,Internetmedia,Kirtsy,laaik.it,LaTafanera,LinkArena,LinkaGoGo,LinkedIn,Linkter,Live,Meneame,MisterWong,MisterWong.DE,Mixx,MOB,muti,MyShare,MySpace,MSNReporter,N4G,Netvibes,NewsVine,Netvouz,NuJIJ,Orkut,Ping.fm,Posterous,PDF,Plurk,Print,Propeller,Ratimarks,Rec6,Reddit,RSS,Scoopeo,Segnalo,SheToldMe,Simpy,Slashdot,Socialogs,SphereIt,Sphinn,StumbleUpon,Techmeme,Technorati,ThisNext,Tipd,Tumblr,Twitter,Upnews,viadeo FR,Webnews.de,Webride,Wikio,Wikio FR,Wikio IT,Wykop,Xerpi,YahooBuzz,Yahoo! Bookmarks,Yigg, XHTML, facebook, facebook like, like button, facebook button, facebook like button, bit.ly, bitly, bebo
Requires at least: 2.9
Tested up to: 3.0
Stable tag: 5.13

== Description ==

The famous Sociable plugin now updated and compatible with WordPress 3.0.  Add social bookmarks to posts, pages and RSS feeds. Choose from more than 100 different social bookmarking sites like Digg, Facebook, and del.icio.us, or add your own sites!

= IN DEVELOPMENT =
* I'd like to hear from you!  Please don't be shy about writing with your suggestions and enhancements.
* Icon animations
* Click statistics
* Additional URL shorteners

= FEATURES =
* Social bookmarking for WordPress 3.0 and multisite
* 99 social bookmarking services included
* Add your own services
* Icons are automatically added to posts, pages or RSS feeds
* Template tag and shortcode are provided for precise control
* Fully XHTML compliant

= GET PRO =
* Get the [Pro version](http://wpplugins.com/plugin/155/sociable-pro) for even more functionality!
* Bit.ly URL shortening for twitter
* Facebook 'like' button
* Custom, themeable CSS tooltips
* 10 new icon sets in different sizes (icons are included only for popular services; see the wpplugins.com description for a list)
* Easily add your own custom icon sets


== Installation ==

1. Deactivate any old Sociable versions
2. Unzip the sociable plugin zip file
3. Upload the sociable plugin files to your sociable folder /wp-contents/plugins/sociable-30
4. Activate the sociable plugin from the WordPress 'plugins' screen
5. Use the sociable settings page to activate your bookmarks (only a few are active by default)

== Advanced Users ==

A [sociable] shortcode is available so you can place the Sociable bookmarks anywhere you like in a post or page.  Just place the shortcode anywhere in your post or page (if the shortcode is present the default icon output will be disabled).

If you include the 'tagline' attribute you can set the tag line as well.  For example:

`This is my post. [sociable tagline='Share it:']`

Just be sure to put the [sociable] tag on its own line - it's not allowed inside a paragraph.

Another option is to turn off the automatic bookmarks display in the Sociable options screen and add calls directly to your theme.  With this approach you can also specify which sites to display:
`
// Show all active sites
<?php global $sociable; echo $sociable->get_links(); ?>

// Show only these two sites if they are active
// Careful: the site names are case-sensitive!  They must
// exactly match the name in the services.php file.
<?php global $sociable; echo $sociable->get_links(Array('Facebook', 'Twitter')); ?>
`

== Frequently Asked Questions ==

= How can I use my own icons? =
Make sure you're using [Sociable Pro](http://wpplugins.com/plugin/155/sociable-pro).  Custom icons aren't supported in the free version.

Pro includes 10 icon sets.  You can find more at the links below or at many other sites:
[http://www.online-blogger.net/2010/02/02/100-social-media-icon-sets/](http://www.online-blogger.net/2010/02/02/100-social-media-icon-sets/)
[http://coderplus.com/blog/2009/11/social-bookmarking-icon-packs/](http://coderplus.com/blog/2009/11/social-bookmarking-icon-packs/)
[http://www.komodomedia.com/download/](http://www.komodomedia.com/download/)

Just put your icons in the directory `/images/custom/size` (where 'size' is the icon size).  For example put your 16x16 icons in `images/custom/size/16`

Your icons will then be available right from the settings screen.

If you have multiple sets of icons and you want to switch between them you can also edit the file 'pro.php' to specify the icon set names and directories.

= How can I add a new site? =
The easiest way is to send it to me for inclusion in the next version of the plugin.  But you can also do it yourself by editing the sites.php file and adding the icon to the images directory.

= How can I change the CSS? =
You can add a 'sociable_custom.css' to the plugin directory and it will be included (along with the original) when the plugin is loaded.  Override the settings you need changed.

= How has Sociable been updated? =
The plugin has been rewritten to fix bugs in past versions and make it WordPress 3.0 and multisite compatible.  It now includes multiple icon sets, shortcodes, theme tags, CSS tooltips and more.

= Which sites are supported? =
The complete list is: sociable,sexy bookmarks,sexy,social,bookmark,social bookmarks,social bookmark,bookmarks,bookmarking,social bookmarking,sharing,sociable,share,sharethis,Add to favorites,BarraPunto,Bitacoras.com,BlinkList,blogmarks,Blogosphere,blogtercimlap,Faves,connotea,Current,del.icio.us,Design Float,Digg,Diigo,DotNetKicks,DZone,eKudos,email,Facebook,Fark,Fleck,FriendFeed,FSDaily,Global Grind,Google,Google Buzz,Gwar,HackerNews,Haohao,HealthRanker,HelloTxt,Hemidemi,Hyves,Identi.ca,IndianPad,Internetmedia,Kirtsy,laaik.it,LaTafanera,LinkArena,LinkaGoGo,LinkedIn,Linkter,Live,Meneame,MisterWong,MisterWong.DE,Mixx,MOB,muti,MyShare,MySpace,MSNReporter,N4G,Netvibes,NewsVine,Netvouz,NuJIJ,Orkut,Ping.fm,Posterous,PDF,Plurk,Print,Propeller,Ratimarks,Rec6,Reddit,RSS,Scoopeo,Segnalo,SheToldMe,Simpy,Slashdot,Socialogs,SphereIt,Sphinn,StumbleUpon,Techmeme,Technorati,ThisNext,Tipd,Tumblr,Twitter,Upnews,viadeo FR,Webnews.de,Webride,Wikio,Wikio FR,Wikio IT,Wykop,Xerpi,YahooBuzz,Yahoo! Bookmarks,Yigg


== Changelog ==
= 5.13 =
* Fixed: corrected W3C validation errors in facebook like plugin.  Note: this required removing allow_transparency attribute; contact me if this is an issue on your site

= 5.12 =
* Fixed: bug in Google Analytics plugin (http://wordpress.org/extend/plugins/google-analytics-for-wordpress/changelog/) breaks Sociable links with single quotes
* Added: message for Chrome users for add to favorites link

= 5.11 =
* Updated plugin to use the newer printfriendly service
* Added better debugging information

= 5.10 =
* Fixed 404 errors from automatic inclusion of 'sociable_custom.css' stylesheet.  For custom CSS with this version: (1) name your file 'custom.css' (without the 'sociable') and (2) set the 'Custom CSS' checkbox in the Sociable settings screen.
* Warning message during activation fixed

= 5.09 =
* Fixed: incorrect spacing for the Facebook 'standard 'icon
* Added option to position tagline above or left of the icons
* Moved the tagline position to just above the icons, rather than above the facebook 'like' button
* Tagline CSS now sets font weight, so <strong> tags can be removed from the tagline
* Added 'text-align: left' to div.sociable CSS to override WP default text justification for posts (which affects tagline)
* Removed these CSS classes, which are not being used: span.sociable-tagline, span.sociable-tagline span, span.sociable-tagline:hover span
* Fixed: error in addtofavorites.js when using IE

= 5.08 =
* Added !important modifier to CSS for images to prevent overrides by some themes
* Added option to choose Facebook like button/text colors
* Added option to show Facebook faces.  Note that currently this inserts too much space before the icons; in a future release I'll use the javascript libraries which allow a 'drop down' effect instead.

= 5.07 =
* Added code to block PRO version from wordpress repository updates (which will downgrade to regular version)

