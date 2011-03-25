=== Author Information Widget ===
Contributors: dhoppe
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=1220480
Tags: widget, post, posts, page, pages, author, co-author, co-authors, coauthor, coauthors, sidebar, meta
Requires at least: 2.9.2
Tested up to: 3.1
Stable tag: trunk

This Widget shows the "about me" text, gravatar and social network/contact links of your post author(s). Author Information Widget has been granted the "Famous Software" Award.

== Description ==

= LATEST NEWS! =
* Author Information Widget has been granted the "Famous Software" Award. [To the post &raquo;](http://download.famouswhy.com/author_information_widget/)

= Description =
This Widget shows the "about me" text, gravatar and social network/contact links of one or more author(s) of your blog. You can add this widget to sidebars on author relevant sections, i.e. pages, posts or author archives. The Plug-in shows the name of the current author (current is the author who has written the current page/post or is chosen by "posts by"-URL), his or her "about me"-description (WP Admin &raquo; User &raquo; Your profile) and if you check it the plug-in will show the authors gravatar, website link, mail address, jabber profile, AOL IM, Yahoo IM and a link to the author archive in the blog.


= Requirements =
* **PHP5!** (please notice: 5 is not 4.9, not everyone seems to know that -.-)
* WordPress 2.9.2 or higher


= Handling =
The handling is very easy. After activating the plug-in you will find a new widget in your admin panel. Add it to a sidebar and check all options you need. That's it.


= It doesn't work! =
If you are wondering why there is no new widget in your admin panel after activating the plug-in i guess you aren't using PHP5. **This widget requires PHP5!**


= Multiple post authors =
This plug-in plays well with the great plug-in [Co-Authors Plus](http://wordpress.org/extend/plugins/co-authors-plus/) by Mohammad Jangda which supports multiple authors for your blog post and pages. If you have a favorite plug-in which is not supported let me know. Maybe i will write an interface for a small fee.


= Customizing the appearance =
**This plug-in does not contain any style information!** If you want to customize the design you have to do the following:

* copy the *author-info-widget.css* file (from the plug-in folder) to your theme directory
* copy the *author-info-widget.php* file (from the plug-in folder) to your theme directory
* Now you can start customize the style of your widget until it fits your needs. Both files are well documented and easy to understand. The *author-info-widget.php* builds the architecture. The *author-info-widget.css* adds the style information. 

**Of course i can help you customizing your widget appearance** for a small fee. ;) Feel free to send me a mail or leave a comment in my blog.


= For developers =
If you want to use a customized template file outside the theme directory you can use the *author_info_widget_template* filter. Just write a path to a file in the filter to bypass the template. Here is an example that shows how you can write a plugin which changes the template path to a file in the same directory.

<code>
Function bypass_template($template_file){
  /* the $template_file is the file which is currently set as template so
     you can also use the filter to read the current template file. 
  */
  return DirName(__FILE__) . '/my-template.php';
}
Add_Filter('author_info_widget_template', 'bypass_template');
</code>

Analogical you can change the style sheet with the *author_info_widget_style_sheet* filter. Here is an example:
<code>
Function bypass_style_sheet($css_file){
  /* the $css_file is the file (URL) which is currently set as style sheet so
     you can also use the filter to read the current css file. 
  */
  // Url to your CSS File
  return get_bloginfo('wpurl') . '/my-style.css';
}
Add_Filter('author_info_widget_style_sheet', 'bypass_style_sheet');
</code>

= In the Press =
* Author Information Widget has been granted the "Famous Software" Award. [To the post &raquo;](http://download.famouswhy.com/author_information_widget/)


= Language =
* This plug-in is available in English.
* Dieses Plugin ist in Deutsch verfügbar. ([Dennis Hoppe](http://dennishoppe.de/))
* Denna plugin finns på svenska. ([Jonas Flodén](http://koalasoft.se/))
* Αυτό το πρόσθετο είναι διαθέσιμο στα Ελληνικά. (Στέφανος Μεϊμάρογλου)
* Deze plugin is beschikbaar in het Nederlands. ([WordPress Webshop](http://wpwebshop.com))

If you have translated this plug-in in your language feel free to send me the language file (.po file) via E-Mail with your name and this translated sentence: "This plug-in is available in %YOUR_LANGUAGE_NAME%." So i can add it to the plug-in.

You can find the *Translation.pot* file in the *language/* folder in the plug-in directory.

* Copy it.
* Rename it (to your language code).
* Translate everything.
* Send it via E-Mail to mail@DennisHoppe.de.
* Thats it. Thank you! =)


= Questions =
If you have any questions feel free to leave a comment in my blog. But please think about this: I will not add features, write customizations or write tutorials for free. Please think about a donation. I'm a human and to write code is hard work.
 

== Screenshots ==

1. The Author Information Widget next to a post with two authors in WordPress 3 (TwentyTen). 


== Installation ==

Installation as usual.

1. Unzip and Upload all files to a sub directory in "/wp-content/plugins/".
1. Activate the plugin through the 'Plugins' menu in WordPress.
1. Go to your widget admin page and choose a place for your new Author-Info-Widget.
1. You like what you see?

== Changelog ==

= 1.2.10 =
* Added Dutch translation by [WordPress Webshop](http://wpwebshop.com).

= 1.2.9 =
* Added 'plugin_locale' filter support

= 1.2.8 =
* Added Greek translation by Stefanos Meimaroglou.

= 1.2.7 =
* Fix: Error message in back end if there are no authors available.

= 1.2.6 =
* Read template directory with get_query_template()
* Read style sheet directory with get_stylesheet_directory()
* Template Engine should now work with child themes


= 1.2.5 =
* Added Swdish translation by [Jonas Flodén](http://koalasoft.se/)
* renamed template.php to author-info-widget.php (same name as it should have in the theme folder)

= 1.2.4 =
* encoded style sheet url
* handle filter requests more secure
* avoid directory listings

= 1.2.3
* Small template modifications

= 1.2.2 =
* Added "author_info_widget_style_sheet" filter

= 1.2.1 =
* Fixed: Widget read from current author
* Fixed german translation

= 1.2 =
* Added template architecture.
* Added filters to change all outputs.
* Reordered options in the widget settings.

= 1.1 =
* Now the plugin supports the famous [Co-Authors-Plus Plugin](http://wordpress.org/extend/plugins/co-authors-plus/).
* Corrected some German translations.

= 1.0.7 =
* Added dutch translation.

= 1.0.6 =
* You can set the avatar adjustment to "none" and "center"
* Widget can be disabled on pages
* You can show the authors e-mail-address
* You can hide the link to the authors posts
* Removed CSS code
* New you can add your own style sheet.

= 1.0.5 =
* Now you can choose the adjustment of the avatar.

= 1.0.4 =
* Code Optimization: Replaced the db query to read authors with a core function.

= 1.0.3 =
* New Feature: You can select any author from the blog which profile should be shown. So you can use this widget in any widget area on your blog.

= 1.0.2 =
* New Feature: You can hide the widget from visitors which are not logged in.

= 1.0.1 =
* Now all the line breaks and paragraphs in the users profile are visible in widget text.

= 1.0 =
* Everything works fine.