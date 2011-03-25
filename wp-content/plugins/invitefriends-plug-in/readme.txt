=== Invite Friends ===
Author: Giovanni Caputo (email: giovannicaputo86@gmail.com) http://www.giovannicaputo.netsons.org
Donate link: http://rat86.netsons.org/blog/?page_id=2015
Tags: invite, buddypress, messenger, yahoo, friends, gMail, API, 
Requires at least: BuddyPress 1.0
Tested up to: BuddyPress 1.0
Stable tag: 0.8.1


Invite friends on buddypress social network from MSN, gmail, Yahoo, facebook and twitter. 

== Description ==

Invite friends on **buddypress** social network from MSN, gmail, Yahoo, facebook and twitter.
You can invite friends from BuddyPress Bar : MyAccount/Friends/Invite Friends.

It works under [wordpressMU](http://mu.wordpress.org/) + [BuddyPress](http://www.buddypress.org) .


####History

0.1 - First release for beta version of BuddyPress.  
0.3 - First release for RC1 version of BuddyPress.   
0.5 - RC1 release with import rom Facebook and Twitter.  
0.6 - RC1 Messenger API and new admin page.  
0.7 - First release for RC2.  
0.8 - Release for BuddyPress 1.0


 

####Localization

	In English but you can translate language file .po

####Please!
If you don't rate my plugin as 5/5 - please write why - and I will change plugin, add options and fix bugs. It's very unpleasant to see silient low rates.  
If you don't understand what plugin does - also don't rate it ;)

####Questions
If you have troubles with my plugin, need more details, or have suggestions - please visit [my blog](http://rat86.netsons.org/blog/?page_id=2015) or [buddypressDev Home Page](http://bp-dev.org/plugins/invite-friends/).

== Installation ==
If you use 0.6.1 version for RC1

    * Unzip files in /wp-content/mu-plugins/.

If you use 0.7.1 version for RC2

    * Unzip files in /wp-content/plugins/buddypress/.

0.8 Cersion for BP 1.0
	* * Unzip files in /wp-content/plugins/.
	Active plugin from plugin page.

####From admin page: 
	Setting/Invite Friends
	Set a mail address from will be send a mail to invite friends.[Default: mail admin].
	Set type methods for all services. You can use API or scraper. If you not change it than it can not avaiable.
	
####Warning:
	If use Yahoo API click on "GET AN APP ID" to get an application ID.(SET all fild and select "Browser Based Authentication")
	If use gMail API set ZEND URL. Ensure that all services are TESTED with "No errors found".	 
	
	For Facebook: 
		Create new Facebook application from [createapp](http://www.facebook.com/developers/createapp.php) as
		Canvas Callback URL
			http://rat86.netsons.org/bp/index.php?facebook=true
		Canvas URL
			http://apps.facebook.com/buddypress/
		FBML/iframe
			FBML
		Developer Mode
			Disattivato
		Application Type
			Sito Web
		Private Install
			No
		Descrizione dell'applicazione
			facebook import
		
		Copy  API key and secret to admin page, insert url of application(canvas URL), and set redirect URL as home page of blog.
		
  				

		
== Screenshots ==

Here are some screenshots of the plug-in at work:

1. Admin page
2. How to use
3. Base screenshot
4. Filter and select contacts



