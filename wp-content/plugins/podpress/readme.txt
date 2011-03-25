=== podPress ===
Tags: post, podcast, podcasting, audio, video, admin, feed, widget, stats, statistics, iTunes, mp3, m4a, ogg, ogv, xspf
Contributors: seek3r, macx, iscifi
Donate link: http://www.mightyseek.com/podpress_donate.php
Requires at least: 2.1
Tested up to: 3.0.1
Stable Tag: 8.8.6.3

A plugin for Podcasters using WordPress.

== Description ==
podPress adds a lot of features designed to make WordPress the ideal platform for hosting a podcast.

podPress 8.8.8 is a further maintenance release with a lot of new features (see the [Changelog](http://wordpress.org/extend/plugins/podpress/changelog/)) which is also compatible with WP 2.9.x and WP 3.0.x. (Most patches for v8.8.5+ were made by [Tim Berger (ntm)](http://profiles.wordpress.org/ntm/))
Thank you to all who have reported bugs and have made improvement proposals. Special thanks to [Ray (raymer)](http://wordpress.org/support/profile/68146) who did extensive tests with the develpment versions of 8.8.5+ and helped a lot! 

If somebody else is also interested in maintaining podPress while the main developers are out, please send an email to admin [at] laptoptips.ca.

Features:

* Full featured and automatic feed generation (RSS2, iTunes and ATOM and XSPF playlist)
* Podcast Download stats, with graphs.
* Makes adding a Podcast to a Post very simple
* View MP3 files ID3 tags when your Posting
* Control over where the player will display within your post and what it will look like.
* Support for various formats, including Video Podcasting
* Supports unlimited number of media files.
* Automatic Media player for MP3, RM, OGG, OGV, MP4, MOV, QT, FLV, ASF, WMV, AVI, and more, with inline and Popup Window support.
* Preview image for videos
* Support for seperate Category podcasts
* Support for Premium Content (Pay Only)


Available in these languages:

* English
* German

If you discover a problem with this plugin, please report it in the [WP.org forum](http://wordpress.org/tags/podpress?forum_id=10) and add the tag "podpress" to your post resp. thread.

For the more information visit the website
http://www.mightyseek.com/podpress

== Changelog ==
= v8.8.8 =
* fully customizable Podcast Feeds with file type filter
* The Feed Buttons widget was modified accordingly to these new capabilities and has now the possibility to define custom button URLs and alternative Feed URLs (e.g for Feedburner URLs). (The presentation of these new options is made with jQuery UI elements.) NOTICE: Please, check the Feed/iTunes and the widget settings of podPress after the upgrade to this version.
* a new feed button for the Premium Feed
* multiple Feed Buttons and XSPF player widgets in combination with WP 2.8+ | NOTICE: after installing v8.8.8 due to the massive modifications on both podPress widgets you will need to add the widgets again to your sidebars. The widgets will try to take over the old settings as far as possible.
* the customization of the XSPF player is different now but hopefully more comfortable for multi site blogs. Please, read the instructions in the new podpress_xspf_config-sample.php file an in the widgets settings.
* CategoryCasting Feeds have a file type filter
* possibility to add the CategoryCasting Feed to the sidebar via the Feed Buttons widget
* option to deactivate the statistic dashboard widget of podPress
* the color maps for the player customization is now converted to numbers instead of static English descriptions
* all image file which are 80x15 buttons for Feeds have a new name scheme: feed_button-{name of the feed}.png
* bug fix: for a JS problem with the new input field ids of the category form in WP 3.x
* bug fix: minor fixes for the CategoryCasting feeds
* bug fix: non-ASCII characters in the ID3-tags will be displayed properly
* bug fix: improved support for media files with names with non-ASCII characters and white spaces
* bug fix: the default video player preview image setting from player settings page works as the preview image proposal for video player previews of new posts
* bug fix: whether podPress shows the player elements while only the short version of a post is visible or not does not longer depend on a certain CSS class of the `<!--more-->` element (Thanks to [dayanayfreddy](http://wordpress.org/support/profile/dayanayfreddy) for the [bug report and the patch](http://wordpress.org/support/topic/plugin-podpress-bugfix-post-short-version-versiones-cortas-de-entradas))

(8.8.7 was only available as beta version resp. as preview for some features of 8.8.8)

= v8.8.6.3 =
* bug fix: for the "Play in Popup" feature which did not work for non-mp3 files or while the podango player was the mp3 player (in 8.8.6 - 8.8.6.2)

= v8.8.6.2 =
* possibility to define an URL of an custom XSPF playlist in the settings of the widget
* possibility to choose between an unsigned and a signed version of the Cortado player (Site Admin > podPress > player settings)
* bug fix: for the problem(s) with described in the WP.org forum thread "Podpress won't work on single posts" (http://wordpress.org/support/topic/podpress-wont-work-on-single-posts)

= v8.8.6.1 =
* bug fix: contains a fix for a problem with the URL sanitation while the "Absolute path of the media files directory" feature is in use (Thanks to mocinoz who has [reported this problem](http://wordpress.org/support/topic/podpress-886-breaks-file-selection))

= v8.8.6 =
* the process the 1PixelOut player will integrated into the posts is now as it is described in the [documentation of the player](http://wpaudioplayer.com/standalone). One of the advantages that the player software inserts now an object element which is individually assembled for the browser that the visitor/listener uses. This is an further effort to get a cross-browser compatibility of the mp3 player object. Furthermore it will bring back the functionality that a player stops playing if you start another one in the same browser window.
* podPress includes the Cortado Player 0.6.0 for Ogg Vorbis audio (.ogg) and Ogg Theora video (.ogv) files. Ogg files are going to be embedded with the HTML5 audio and video element. The Cortado player is the fallback option.
* refreshed statistic settings section on the general settings page of podPress (expanded explanations for all the options, all explanation are now visible all the time if statistic feature is enabled)
* bug fix: The Quick Counts statistic table (stat logging: Counts Only) shows now by default the Downloads per Media File and if desired the same data sorted by the date (resp. ID) of the posts in which the media files were last published.
* PODPRESS_DEBUG_LOG = FALSE is a default setting again. Now, It is also possible to check the status of this constant at the general settings page of podPress.
* bug fix: further CSS modifications for the (video) player preview
* bug fix: podPress loads the CategoryCasting settings in WP 3.0.x
* bug fix: the update check of podPress 8.8.5.x has disturbed the dashboard in WP 2.7
* a link in the popup player window which opens the single post view of the post with the media file
* a new option on the general settings page of podPress to Show/Hide the "Hide Player"/"Play Now" link
* a possibility to change the playlist URL of the XSPF player: define the constant PODPRESS_CUSTOM_XSPF_URL with your custom URL. At the beginning of the file podpress.php is a prepared line and a short how-to.
* localization added: German

= v8.8.5.3 =
* adjustments in the podpress.js file for a better cross-browser compatibility of the mp3 player object

= v8.8.5.2 =
* 4 new option for the 1PixelOut player: "Initial Volume Level", "Buffering Time", "Use a cross-domain policy file" and "Use custom values for titles and artists instead of the ID3 data".  "Use a cross-domain policy file" can help to display the ID3 information in the player if the mp3 files are not located on the same domain as your blog (and the player shows "Track #1"). With "Use custom values for titles and artists instead of the ID3 data", you can order the 1PixelOut player to use custom values of artist und title instead of the values from the ID3 tags.
* adjustments of the structure of the mp3 player object element
* on an upgrade from 8.8.4 or older version to 8.8.5.2 the color settings for the player will not be overwritten anymore. Despite this new behaviour you should definitely have a look to the player settings. There are new options available.
* improved file type filter for the Endhanced Podcast and Torrent feed
* misc_torrent_button.png has the design of the other "_button.png" - files
* minor CSS adjustments on the admin pages

= v8.8.5.1 =
* the option "Disable Video Preview" on the player settings page of podPress works again: When checked the preview player for video files will be invisible.

= v8.8.5 =
* the podPress_downloadFile function is respecting time limits
* the htmlentities-decoding for the skin files of the XSPF player works with the blogs charset, now

= v8.8.5 RC 3.1 =
* the log procedure PODPRESS_DEBUG_LOG writes log entries with timestamp

= v8.8.5 RC 3 =
* removed the automatic update of the XSPF skin file on reactivation because it worked not with "Network Activate" in a multi site blog. But it is possible that such a functionality will be in a future version again. (in podpress.php). Please, save the player width manually on the widgets page of your blog after an upgrade.
* additional error message in case that the user has not the permission to use chmod()  (for the XSPF skin file)

= v8.8.5 RC 2.3 =
* correct import of the global $wp_version (in podpress.php)
* modified the way podPress adds the custom role and capabilities

= v8.8.5 RC 2.2 =
* this is a rollback to RC 2 (the changes of RC 2.1 caused a lot of problems)

= v8.8.5 RC 2.1 =
* podPress uses now the init action hook to register, enque and and print scripts and styles to the frontend header

= v8.8.5 RC 2 =
* with the new constant PODPRESS_DEBUG_LOG (= TRUE) it is possible to log the duration and ID3 tag retrieval procedures (for debugging purposes)

= v8.8.5 RC 1.1 =
* minor changes of the method the plugin retrieves the ID3 tags
* parsing of the UserAgent data without the PHP function eregi
* the generated skin files for the XSPF player will no longer contain htmlentities-encoded strings

= v8.8.5 beta 14.1 =
* fixed the get_bloginfo('siteurl') / get_option('siteurl') confusion

= v8.8.5 beta 13 =
* fixed a problem with Listenwrapper

= v8.8.5 beta 12 =
* fixed a lot of problems related to the new botlist feature

= v8.8.5 beta 11 =
* "Play in Popup" works again with IE browsers
* with podPress embedded YouTube videos are now visible in IE6
* possibility to set the dimensions of the YouTube videos available (again)
* further HTML and CSS adjustments to separate code and style

= v8.8.5 beta 10 =
* "work in progress"-icon during the ID3 tag retrieval
* minor changes on several JS scripts
* fix for a problem on the CategoryCasting pages
* The text phrases in podpress_admin.js are ready for localization via .po and .mo files

= v8.8.5 beta 9 =
* further HTML and CSS adjustments
* "work in progress"-icon during the file size and duration detection
* "Theme Compatibility Check" message temporarily deactivate because it does not work reliably (podpress_admin_general_class.php)

= v8.8.5 beta 8 =
* adjustments on the podPress player settings page: new color map for the Podango player, new color options for the updated 1PixelOut player

= v8.8.5 beta 7 =
* a lot of deprecated function calls revised 
* further CSS adjustments

= v8.8.5 beta 6 =
* enhanced the compatibility for the WP 3.0 multi blogs mode
* XSPF player widget ready for the usage in a WP installation with multiple blogs
* some CSS adjustments

= v8.8.5 beta 5 =
* a fix for a problem ([WP.org forum post 379290](http://wordpress.org/support/topic/379290#post-1499746)) with single and double quotation marks in the media file titles while podpress is used with older WP versions (Thanks to [John Halton](http://wordpress.org/support/profile/140408))
* some style clean up on the statistic pages
* some old and unnecessary flash players removed

= v8.8.5 beta 4 =
* podPress uses the DB_COLLATE or DB_CHARSET to create its 2 statistic tables
* CategoryCasting page WP 3.0 ready
* podpress_tmp/-folder procedures are WP 3.0 ready

= v8.8.5 beta 3 =
* new ATOM feeds: podPress adds now an feed with the name "torrent" which contains only posts (or pages) with .torrent files and a feed with the name "enhancedpodcast" which contains only posts (or pages) with .m4a/.m4v files
* more options for the podPress - Feed Buttons widget: more buttons (new buttons added: feed-enhpodcast.png, feed-torrent.png, button_comments_rss_blog.png, button_atom_blog.png, button_comments_atom_blog.png)
* more options for the podPress - Feed Buttons widget: feed buttons mode or text mode (In the text mode there is in front of each text a feed icon, but only for WP 2.2+. For older WP versions it is possible to add an icon via the Filter Hook: podpress_legacy_support_feed_icon)
* more phrases ready for internationalization (http://codex.wordpress.org/I18n_for_WordPress_Developers)
* frappr.com maps advice on the General Settings page of podPress removed
* contains a fix for a problem ([WP.org forum post 379290](http://wordpress.org/support/topic/379290#post-1499746)) with single and double quotation marks in the media file titles (Thanks to [John Halton](http://wordpress.org/support/profile/140408))
* the appearance (incl. paging) of the tables on the statistic pages is updated
* new feature: a bot filter for the download statistic - users can select (and deselected) IP addresses and user agent names which are probably search engine bots. The statistics will be computed without the download numbers of these bots. (for FULL / FULL+ stat logging) 
* The display of the statistics of the different collecting method Count Only, FULL and FULL+ are now separated. If the Count Only logging is active then the statistic page will have now besides the Quick Counts a comparable graph. This table and the graph and the Statistic Summary on the Dashboard are based on the numbers of the db table wp_podpress_statcounts. In Full or Full+ logging mode the number come from the wp_podpress_stats. That is important to know because the counter which counts the downloads on the wp_podpress_statcounts table counts when one of the 3 logging modes is active but the other counter which collects the data in the wp_podpress_stats table counts only during Full and Full+ mode. In other words: the numbers of the 2 tables can differ if someone uses the Counts Only mode for a while and than one of the others. Furthermore it is not possible to display the numbers of the Counts Only mode without the download numbers of the bots. 
* a new "Downloads Per Media File" and a new "Downloads Per Post" overview (for FULL / FULL+ stat logging)
* The <object> element of the players has the class "podpress_player_object".
* each podPress page and the Admin Menu has an icon (podpress_icon_r2_v2_32.png and podpress_icon_r2_v2_16.png added)
* a new button and icon for YouTube videos embedded with podPress (embed_youtube_button.png / embed_youtube_icon.png)
* a new button and icon for WMA files embedded with podPress (audio_wma_button.png / audio_wma_icon.png) (Now, podPress handles WMA file internally as audio_wma and not as video_wma.)
* [Ticket #1089](http://plugins.trac.wordpress.org/ticket/1089) - props: a new customizable XSPF player
* [Ticket #1085](http://plugins.trac.wordpress.org/ticket/1085) - enhancement for loading time of the podpress pages
* [Ticket #1083](http://plugins.trac.wordpress.org/ticket/1083) - a reworked player preview 
* [Ticket #1080](http://plugins.trac.wordpress.org/ticket/1080) - fix for podPress_downloadlinks container
* [Ticket #1079](http://plugins.trac.wordpress.org/ticket/1079) - fix for the "Before <!- More -> tag:"-functionality
* [Ticket #1074](http://plugins.trac.wordpress.org/ticket/1074) - better handling of invalid input data in the feed generating functions
* [Ticket #1073](http://plugins.trac.wordpress.org/ticket/1073) - additional descriptions for the feed settings pages of podPress
* [Ticket #1068](http://plugins.trac.wordpress.org/ticket/1068) - patch for an error-free stats counter (Thanks to [avatarworf](http://wordpress.org/support/profile/17483))
* [Ticket #1066](http://plugins.trac.wordpress.org/ticket/1066) - Podango message at the general settings page of podPress
* [Ticket #1064](http://plugins.trac.wordpress.org/ticket/1064) - compared to 8.8.5 beta 2 the last patch of this ticket makes that the enclosures which are added with podPress listed always before the enclosures from the custom fields with the name "enclosure". that is important because pdocatchers like iTunes tend to recognize only the first enclosure of a RSS item. (Is a fix for [this problem](http://wordpress.org/support/topic/372625). Many thanks to [gamebynight](http://wordpress.org/support/profile/3657145) for the very good bug report!)
* [Ticket #1061](http://plugins.trac.wordpress.org/ticket/1061) - update to 1PixelOut player a.k.a. Audio Player 2.0

= v8.8.5 beta 2 =
* [Ticket #1064](http://plugins.trac.wordpress.org/ticket/1064) - multiple fixes on the feed feed/iTunes settings page and feed functions
* [Ticket #1062](http://plugins.trac.wordpress.org/ticket/1062) - Fix for the Video Player Preview and the Listen Wrapper (contains the [Ticket #1060](http://plugins.trac.wordpress.org/ticket/1060) - little enhancement for the variables tempFileSystemPath and tempFileURLPath)

= v8.8.5 beta =
* [Ticket #1059](http://plugins.trac.wordpress.org/ticket/1059) - fixed color picker (on the player settings page)
* [Ticket #1058](http://plugins.trac.wordpress.org/ticket/1058) - feed stats overview as the dashboard widgets in WP versions >= 2.7
* [Ticket #1057](http://plugins.trac.wordpress.org/ticket/1057) - fix for the php warning while saving the "Quick Edit"-changes in WP versions >= 2.7

= v8.8.4 =
* [Ticket #1056](http://plugins.trac.wordpress.org/ticket/1056) - fix for a problem which appeared in 8.8.2/.3 while the option "Local path to media files directory (optional)" (on podpress General settings page) was in use

= v8.8.3 =
* [Ticket #1055](http://plugins.trac.wordpress.org/ticket/1055) - fix for wrong itunes tags in the rss feed

= v8.8.2 =
* [Ticket #1037](http://plugins.trac.wordpress.org/ticket/1037) - multiple enhancements and fixes

A changelog of earlier versions can be found at
[http://www.mightyseek.com/podpress/changelog/](http://www.mightyseek.com/podpress/changelog/)


== Installation ==

If you have ever installed a plugin, then this will be pretty easy.

1. Extract the files. Copy the "podpress" directory into `/wp-content/plugins/`
1. Activate the plugin through the "Plugins" menu in WordPress
1. Configure the Feed/iTunes Settings and add eventually one of the podPress widgets to one of the sidebars (OR If you are using a WP version that is older than WP 2.5 and you want to add a link to itunes on your website then set the FeedID in the podPress options page, and then add this code in your template `<?php podPress_iTunesLink(); ?>`)

Details about all the optional_files are in optional_files/details.txt

= Requirements =
podPress requires at least WP 2.1 but it is very recommended to use at least WP 2.3. Many features like the widgets, the custom feeds or the CategoryCasting feature will work only with WP 2.3 or later WP versions. Multiple Widgets are available in combination with WP 2.8.x or newer.

= Included Software =
podPress v8.8.8 includes:

* 1PixelOut Audio Player v2.0 - http://www.1pixelout.net/code/audio-player-wordpress-plugin/
* Podango player - http://sites.google.com/site/podangohibernate/
* XSPF Jukebox v5.9.5 - http://lacymorrow.com/projects/jukebox/
* Cortado Player 0.6 (cortado-ovt-stripped-0.6.0.jar and cortado-signed-0.6.0.jar) - http://www.theora.org/cortado/
* a flvplayer.swf
* getID3 v1.7.9 - http://www.getid3.org
* jQuery 1.4.2 + jQuery UI 1.8.5 Dialog + Accordion (jQuery is integrated with the object name podpress_jquery142 for parallel usage with the jQuery versions of the different WP versions.)


== Upgrade Notice ==
Upgrading to 8.8.8:
If you have used the widgets of podPress then it will be necessary to reactivate these widgets manually after an upgrade. The reason for these cirumstances are the massive modifications on both podPress widgets. The widgets will try to take over the old settings as far as possible.
podPress adds feeds to your blog. If you are not using the default permalink structure then you should save the permalink settings after the podPress update again. That will make the new podPress feeds available with your usual permalinks.
podPress v8.8.5+ requires at least WP 2.1 but it is recommended to use at least WP 2.3. To be able to use the full feature set (e.g. for multiple widget support) of podPress, you should use at least WP 2.8.x.


== Upgrade ==
Upgrading to 8.8.8:
If you have used the widgets of podPress then it will be necessary to reactivate these widgets manually after an upgrade. The reason for these cirumstances are the massive modifications on both podPress widgets. The widgets will try to take over the old settings as far as possible.
podPress adds feeds to your blog. If you are not using the default permalink structure then you should save the permalink settings after the podPress update again. That will make the new podPress feeds available with your usual permalinks.

podPress v8.8.5+ requires at least WP 2.1 but it is recommended to use at least WP 2.3. To be able to use the full feature set (e.g. for multiple widget support) of podPress, you should use at least WP 2.8.x.

Upgrading to 4.0:
The plugins/podpress.php file is no longer needed and MUST be deleted. The podpress.php file now lives in `plugins/podpress/`

About wp-rss2.php:
Only users that have not upgraded to a version of WordPress above 2.0.0 need the custom wp-rss2.php file. If you have upgraded past 2.0.0 then use the normal wp-rss2.php that came with WordPress.

About wp-commentsrss2.php: 
No one should be using the custom version of this file anymore. It is not supported and may cause problems.


== Configuration ==

Go to the new podPress menu and start configuring your Feed settings, player appearance, statistics and more general settings.

= Feeds of podPress =
podPress adds not only iTunes-tags and <enclosure>-tags to your usual RSS feed. It adds e.g. a feed called "podcast" (http://example.com/?feed=podcast), a feed called "playlist.xspf" (http://example.com/?feed=playlist.xspf) and a feed called "premium" (http://example.com/?feed=premium).
The podcast feed contains all the posts which have at least one media file added with podPress despite the categories the post is in. It is not necessary to put all the post with podcast episodes in a category called podcast. (There is one constellation you should avaoid: If you name a sub category podcast and you are using e.g. the Permalink structure "Date and name" then the URL http://example.com/category/parentcategoryname/podcast/ leads to the feed called podcast and not to the category view.)

The playlist.xspf feed is a feed in a special XML format, the [XML Shareable Playlist Format](http://en.wikipedia.org/wiki/XML_Shareable_Playlist_Format "Wikipedia: XSPF (en)") (XSPF). It is a playlist of all the .mp3 files which are added to posts with podPress.

The premium feed contains the premium content added with podPress.

Since v8.8.5 podPress produces by default a torrent feed again and has a new feed called "enhancedpodcast". The "torrent" feed contains only posts which have .torrent files attached with podPress. The 'enhancedpodcast' feed contains only posts which have .m4a or .m4v files attached with podPress. If a post has different types of media files attached then only the right media files will show up in these feeds. In other if you have a post with an .m4a podcast and you have a torrent for this file you can add these file to your post.
(If you are not using the default permalink structure then you should save the permalink settings after the update to v8.8.5 or higher.)
Since v8.8.8 you can customize all aspects of these additional feeds. For instance it is possible to change the file type filter setting and it is possible to activate/deactivate these feeds.

Furthermore you can customize the feed settings of category feeds. This feature is called CategoryCasting and you can find it when you edit a category.

= Filter Hooks of podPress =
Since v8.8.5 you can filter some parts of the output of podPress

* podpress_entry_enclosuretags: a filter for each `<link rel="enclosure">` tag which podPress adds to ATOM feed entries
* podpress_xspf_trackinformation: a filter for each `<track>` tag which podPress adds to the XSPF playlist
* podpress_item_enclosure_and_itunesduration: a filter for each `<enclosure>` tag which podPress adds to the RSS feed items
* nonpodpress_atom_enclosure: podPress adds the media files which are added with podPress at first to the news feeds. `<enclosure>` tags of media files which are added a different way (e.g. automatically via a link the post content) will arranged after the podPress `<enclosure>` tags by podPress. This filter is for filtering each of these non-podPress `<link rel="enclosure">` tags in ATOM feeds.
* nonpodpress_rss_enclosure: This filter is for filtering each of these non-podPress `<enclosure>` tags in RSS feeds.
* podpress_downloadlinks: a filter for the complete podPress row beneath the player (the `<div class="podPress_downloadlinks">` container) <br /> If you want to use this filter hook then make sure that the filtered result still contains the span element with an id like this: `<span id="podPressPlayerSpace_X_PlayLink" style="display:none">| Play Now</span>`. This span element is in the filter input. Do not replace all of the content.
* podpress_legacy_support_feed_icon (only in combination with WP < v2.2): it is a possibility to add a feed icon in front of each line of the Feed Buttons widget in text mode

= How-To use a different skin for the XSPF player: =
Since v8.8.5 podPress uses the [XSPF player created by Lacy Morrow](http://blog.lacymorrow.com/projects/xspf-jukebox/) which has the possibility to use customized player skins. podPress includes a default skin. But it is also possible to use skin files. These skin files are XML files and situated in sub folders of podPress (e.g. /podpress_options/xspf_options/custom/skin_1_{width}x{height}.xml). For more information about using such files with podPress have a look into the podpress_xspf_config-sample.php file (since v8.8.8).
The "[XSPF Jukebox skin.xml Specification](http://lacymorrow.com/projects/jukebox/skindoc.html)" documents how-to make such a file. Or you can eventually use one of the [existing skins](http://blog.lacymorrow.com/projects/xspf-jukebox/). podPress uses a derivate of the SlimOriginal skin by default.

The name of the skin file and the custom variables file are skin_1_{width}x{height}.xml and variables_1.txt. The "_1" in the file names (and in the names of the constants) stands for the blog ID. In the single blog mode the blog ID is 1. If you have an multiple blog installation (WP 3.+ or WPMU) the main blog ID is also 1. The skin file names need to contain also the width and height which has been set in the file. If you are using custom skin files or custom variables files then you need to create the files with the right blog ID and one for each pair of player dimensions manually. For instance if you want to use the player in one sidebar with the width 210 pixels and on an other page with the width 600 pixels then you need two files e.g. skin_1_210x210.xml and skin_1_600x210.xml. For more information see podpress_xspf_config-sample.php.

= How-To change the XSPF playlist URL =
If you have a custom XSPF playlist e.g. a .xspf file and you would like that the player uses this custom playlist then you can insert the new URL in the widgets settings (or define the constant PODPRESS_CUSTOM_XSPF_URL_x with the URL of this custom playlist (At the beginning of the file podpress_xspf_config-sample.php is a prepared line of code.)). The URL has to be an URL to a playlist which is on the same domain/server as your blog! But it is allowed that the tracks in the playlist can be situated on a different server.


== Frequently Asked Questions ==

Please, use the WP.org [Plugins and Hacks forum](http://wordpress.org/tags/podpress?forum_id=10) to ask your questions or for bug reports. When you post in this forum please add at least the tag "podpress" to your post. That makes it very much easier to get a notice of your post (There is a [RSS feed of forum posts which are tagged with "podpress"](http://wordpress.org/support/rss/tags/podpress).).
(The domain name of the official [podPress FAQ](http://podcasterswiki.com/index.php?title=PodPress_FAQ "Official podPress FAQ") page is currently (03/2010) unavailable resp. parked. You can still find some [traces of this Wiki at Archive.org](http://web.archive.org/web/20080708140114/podcasterswiki.com/index.php?title=PodPress).)

= How can I add podcast episodes with podPress? =

You can get probably a good impression how it works from [this WP.org forum thread](http://wordpress.org/support/topic/356947) although the original question is a little bit different. This [other WP.org forum thread](http://wordpress.org/support/topic/425141) might also be helpful.

= How can I submit podcast episodes to iTunes? =

podPress creates a RSS feed with additional information for iTunes. You can use the "Feed/iTunes settings"-page of podPress to determine what the content of these [iTunes RSS tags](http://www.apple.com/uk/itunes/podcasts/specs.html#rss) should be. It is possible to subscribe to this feed directly with the iTunes client program.
But you can add your podcast to the iTunes Store. For more information go to the [iTunes Podcast Resources / Making a Podcast / Submitting Your Podcast to the iTunes Store](http://www.apple.com/itunes/podcasts/specs.html#submitting).

= What is this iTunes:FeedID? =

If you submit your podcast to the iTunes Store then your podcast will get an ID. Insert this ID on the "Feed/iTunes settings"-page of podPress e.g. if you want to use the Feed Buttons widget of podPress. podPress creates the link to your podcast in the iTunes Store with this ID.

= How can I change the my podcast feed-URL in the iTunes Store? =

Apple describes [how it works](http://www.apple.com/itunes/podcasts/specs.html#changing) basically in there [iTunes Podcasting Resources](http://www.apple.com/itunes/podcasts/specs.html) and how you can do this with podPress is dicussed in this WP.org [forum thread](http://wordpress.org/support/topic/249345).

= How can I set a post specific license and license URL? =

Define two custom field per post. One with the name <strong>podcast_episode_license_name</strong> and the name of the license as the value and one custom field with then name <strong>podcast_episode_license_url</strong> and the URL to the full license text. It is necessary to define at least the URL. If the name is not defined then the name will be the URL.

= Blank screen after activating podPress =

Some PHP5 users end up with a blank screen after activating the podPress plugin. For reasons yet fully understood some PHP5 installations consume double the memory compared to a PHP4 install when dealing with WordPress. Some notes I have seen blame it on a bug with caching objects in session data, but I have not debugged it to that level yet.
The solution is to increase the memory_limit in your php.ini from 8MB to at last 12MB 

= How do I upgrade PodPress? =

In general this just requires that you replace the existing files with the new ones. Sometimes it is a good idea to delete all the files in wp-content/plugins/podpress/ and re-upload them fresh.


== Screenshots ==

1. Write a page and at the end of your Post add your mp3 filename or full URL.
2. Players automatically added to your blog
3. Edit config settings and preview what your podcast will look like in various podcasting directories including iTunes.
4. Stats graph by podcast
5. Stats graph by date