=== Quick Post Widget ===
Contributors: inapan
Donate link: http://www.famvanakkeren.nl/downloads/quick-post-widget/
Tags: post, quick, sidebar, frontpage, widget, youtube, tinymce, WYSIWYG, visual, editor, easy, fast, upload
Requires at least: 2.8
Tested up to: 3.0.1
Stable tag: 1.7.1

This plugin provides a widget to post directly from the frontpanel of your site without going into the backend.


== Description ==

This plugin provides a widget to post directly from the frontpanel of your site without going into the backend.  
The widget also offers visual editing in a popup window while posting from the frontpanel, with a bunch of features (media uploading, spellchecking, preview,....)!  
You can easily add images or video-clips to your posts without using the backend of your site.  
Originally developed to enable my 10-year old son to post youtube clips simply by copying and pasting the title and code from youtube and choose a category without having to use the backend of his blog.  
Of course the widget can be used for just about anything you want to post quickly. You could use the Quick Post Widget to write short messages, like twitter, but of course you can also write longer posts.  
The widget is highly configurable with the options panel.


Features:

* Post directly from the frontpage of your site.
* The widget offers visual editing directly from the frontpanel of your site, without going into the backend, with a bunch of features (media uploading, spellchecking, preview,....)!
* If you do not want visual editing you can disable this feature. The widget also follows the WordPress configuration option to disable visual editing per user.
* If you don't want media uploading you can also disable this. You can choose to use a shared directory or private directories per user. Directories are automatically created.
* Input: post-title, post-content, category and optionally tags.
* By default the post is immediately published but you can set the default publish status in the options panel.
* By default the widget only displays when logged-in and allowed to post. There is an option to let guests create posts without being logged-in.
* When allowed to create a new category, radio buttons are displayed to choose between an existing category and a new category as well as an input box for the name of the new category. It is possible to select the parent category for a new category.  
  When however you don't want to allow the creation of new categories, you can explicitly disable this feature.
* Categories can be displayed in either a droplist or a checklist to enable selecting more than just one category.
* Tags can be typed in manually but you can also select from a list of existing tags. If you don't want to use tags you can disable the feature in the options panel.
* When a required item remains empty the item-border displays default red but you can choose other colors (in case you have a red site).
* If for some reason posting is unsuccessful, for instance when a required field is empty, the widget doesn't lose its values.
* Every widget element can be styled with the stylesheet.
* Almost every widget element can also be styled with the options panel so you won't lose any customizations by upgrading.
* Every options panel element can be styled with the stylesheet.
* To keep the widget small you can disable the creation of new categories and tags and display the category selection in the droplist.



== Installation ==

1. Download the plugin.
2. Upload the entire `quick-post-widget` folder to the `/wp-content/plugins/` directory of your blog.
3. Go to the Plugins section of the WordPress admin and activate the plugin.
4. Go to the Widget tab of the Presentation section and configure the widget.


== Frequently Asked Questions ==

= Feature requests = 
If you have any feature requests feel free to mail (or use the forum). Suggestions are welcome, but I will try to keep the widget as general as possible.

= How do I configure guest access? =
To enable guest access without being logged-in, enable the option [Allow guests (not logged-in)]. You will also have to create and/or select a dedicated guest account. You could for instance create an account 'Anonymous' or 'Guest'. Every post a non-logged-in guest creates will be created under this special guest account.  
In the user tab you can enable visual editing for the guest account, unless it is globally disabled in the widget's admin panel, and select a role. The selected role determines the capabilities of the guest account:  

Subscriber: cannot post. There's no point in using this role for the guest account.  

Contributor: can post but posts will have to be reviewed (pending for review). This overrides the default publish status in the admin panel (only for the guest account).  

Author: can also post but posts will have the default publish status selected in the admin panel. The role also allows media uploading from the visual editor (unless it is globally disabled in the widget's admin panel).  

Editor: same as Author but also allows creating new categories (unless it is globally disabled in the widget's admin panel). 

Admin: same as Editor.

Remember the role only determines the capabilities of the guest account when not logged-in. It is better not to use the guest account to actually log in.  
Use these options with care and only in controlled environments. Your blog will be open to everyone!

= The widget temporarily hides Flash content (YouTube). Why? = 
By default flash content bleeds through the modal dialog because of its wmode behaviour. Of course you can manually change the wmode parameter of Flash content but just hiding it temporarily is easier.

= How does uploading work? = 
In the media and image popups in the visual editor there is a small icon at the end of the Image/File URL fields. Clicking it opens an additional file manager popup with uploading capabilities. You can choose to use a shared directory or private directories per user.

= How can I upload for instance pdf- and doc-files? = 
To upload non-media/image files use the [Insert/edit link] button in the popup editor. In the popup which opens there is a small icon at the end of the Link URL field. Clicking it opens the file manager popup from where you can upload. The result will be a link to your uploaded file.

= My upload ends unsuccessfully with error 500 =
If you are using Apache with mod_security you can avoid those errors by putting the following lines in the .htaccess file in your WordPress root:  
SecFilterEngine Off  
SecFilterScanPOST Off

= Translations =
Translations for the popup visual editor are complete. The translations for the rest of the widget and the backend are, apart from Dutch, still incomplete.  
Please help me translate using the po- and pot-files in the langs subdirectory of the widget.

= Use P- or BR-tag for newlines? =
Using the P-tag for newlines is strongly recommended. Using the P-tag results in paragraphs when using [Enter]. Although using the BR-tag saves space it is not recommended.  
If you use the P-tag and don't want a new paragraph when hitting [Enter], thus saving space, use [Shift][Enter] instead.

= Bugs = 
If you experience bugs please mail or use the forum (don't just say its broken).

== Screenshots ==

1. The actual widget functioning on a site, configured full
2. The Quick Post Widget visual editor
3. The actual widget functioning on a site, configured small
4. Part of the options panel in the widget

== Changelog ==

= 1.7.1 =
* Two bug fixes (guest users could not post using the category checklist in WP3, empty iframes using the visual editor).
* Optionally a confirmation message can be displayed after a successful post.

= 1.7 =
* Provided an option to let guests create posts without being logged-in. Use with care! Please read the FAQ section to configure.

= 1.6.1 =
* Added Italian and Polish translations for popup visual editor and the file manager.
* The languages for the rest of the widget and the backend are, apart from Dutch, still incomplete. Please help me translate using the po- and pot-files in the langs subdirectory of the widget.

= 1.6 =
* Internationalized the popup visual editor and the file manager for English, Dutch, French, German, Portuguese and Spanish. The language follows the WordPress language setting.
* The languages for the rest of the widget and the backend are, apart from Dutch, still incomplete. Please help me translate using the po- and pot-files in the langs subdirectory of the widget.

= 1.5 =
* Enabled media uploading in the popup visual editor (please read the FAQ section).  
* Fixed one or two small bugs.

= 1.4 =
* Enabled resizing, auto focus, paste plugin and default WordPress options of the popup WYSIWYG editor.  
* Provided an option to choose between using the P-tag or the BR-tag for newlines.

= 1.3 =
* The widget now offers visual editing directly from the frontpanel of your site, without going into the backend, with a bunch of features (spellchecking, preview,....).   If you do not want visual editing you can disable this feature. If you encounter difficulties you can disable the editor plugins.

= 1.2 =
* Categories can also be displayed in a checklist to enable selecting more than just one category.
* A new category can be given a parent category.
* Tags can be typed or selected from a list. Tags can be disabled in the options panel.
* It is possible to set the default publish status in the options panel.
* The creation of new categories can be disabled in the options panel.
* Slightly changed stylesheet.
* Cleaned up some code.

= 1.1.1 =
* Fixed a bug which occurs when wordpress is installed in a subdirectory.

= 1.1 =
* When allowed to create a new category, radio buttons are displayed to choose between an existing category and a new category as well as an input box to type in the name of the new category.
* Built in visual form validation. When a required item remains empty the item-border displayes default red, but you can choose other colors (in case you have a red site).
* Corrected and slightly changed stylesheet.

= 1.0 =
* First release

== Upgrade Notice ==

= 1.7.1 =
* Two bug fixes (guest users could not post using the category checklist in WP3, empty iframes using the visual editor)
* Optionally a confirmation message can be displayed after a successful post.

= 1.7 =
* Provided an option to let guests create posts without being logged-in. Use with care! Please read the FAQ section to configure.

= 1.6.1 =
* Added Italian and Polish translations for popup visual editor and the file manager.
* The languages for the rest of the widget and the backend are, apart from Dutch, still incomplete. Please help me translate.

= 1.6 =
* Internationalized the popup visual editor and the file manager for English, Dutch, French, German, Portuguese and Spanish. The language follows the WordPress language setting.
* The languages for the rest of the widget and the backend are, apart from Dutch, still incomplete. Please help me translate.

= 1.5 =
* Enabled media uploading in the popup visual editor (please read the FAQ section).
* Fixed one or two small bugs.

= 1.4 =
* Enabled resizing, auto focus, the paste plugin and default WordPress options of the popup WYSIWYG editor. Provided an option to choose between using the P-tag or the BR-tag for newlines.

= 1.3 =
* The widget now offers visual editing directly from the frontpanel of your site, without going into the backend, with a bunch of features (spellchecking, preview,....).

= 1.2 =
Categories can also be displayed in a checklist to enable selecting more than just one category.
A new category can be given a parent category.
Optionally tags can be defined by typing or selecting from a list.
The default publish status can be defined.
Creation of new categories can be disabled.

= 1.1.1 =
Fixed a bug which occurs when wordpress is installed in a subdirectory.

= 1.1 =
Adds a new feature to create a new category while posting.
Built in visual form validation.
Corrected and slightly changed stylesheet.

= 1.0 =
This is the first release of this plugin, no upgrade necessary.