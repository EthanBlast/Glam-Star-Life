<?php
	/**
	 * Contains all sites that Sociable supports, array items have 4 keys:
	 * required favicon - the favicon for the site, a 16x16px PNG, to be found in the images subdirectory
	 * required url - submit URL of the site, containing at least PERMALINK
	 * description - description, used in several spots, but most notably as alt and title text for the link
	 */
	 $this->sites = array(
		 'Add to favorites' => array(
			 'favicon' => 'addtofavorites.png',
			 'url' => '#',
			 'spriteCoordinates' => array(181,73)
		 ),

		'BarraPunto' => array(
			'favicon' => 'barrapunto.png',
			'url' => 'http://barrapunto.com/submit.pl?subj=TITLE&amp;story=PERMALINK',
			'spriteCoordinates' => array(1,1),
		),

		'Bebo' => array(
			'favicon' => 'bebo.png',
			'url' => 'http://www.bebo.com/c/share?Url=PERMALINK'
		),

		'Bitacoras.com' => array(
			'favicon' => 'bitacoras.png',
			'url' => 'http://bitacoras.com/anotaciones/PERMALINK',
			'spriteCoordinates' => array(19,1),
		),

		'BlinkList' => array(
			'favicon' => 'blinklist.png',
			'url' => 'http://www.blinklist.com/index.php?Action=Blink/addblink.php&amp;Url=PERMALINK&amp;Title=TITLE',
			'spriteCoordinates' => array(37,1)
		),

		'blogmarks' => array(
			'favicon' => 'blogmarks.png',
			'url' => 'http://blogmarks.net/my/new.php?mini=1&amp;simple=1&amp;url=PERMALINK&amp;title=TITLE',
			'spriteCoordinates' => array(73,1),
		),

		'Blogosphere' => array(
			'favicon' => 'blogospherenews.png',
			'url' => 'http://www.blogospherenews.com/submit.php?url=PERMALINK&amp;title=TITLE',
			'spriteCoordinates' => array(91,1),
		),

		'blogtercimlap' => array(
			'favicon' => 'blogter.png',
			'url' => 'http://cimlap.blogter.hu/index.php?action=suggest_link&amp;title=TITLE&amp;url=PERMALINK',
			'spriteCoordinates' => array(109,1),
		),

		'Faves' => array(
			'favicon' => 'bluedot.png',
			'url' => 'http://faves.com/Authoring.aspx?u=PERMALINK&amp;title=TITLE',
			'spriteCoordinates' => array(127,1),
		),

		'connotea' => array(
			'favicon' => 'connotea.png',
			'url' => 'http://www.connotea.org/addpopup?continue=confirm&amp;uri=PERMALINK&amp;title=TITLE&amp;description=EXCERPT',
			'spriteCoordinates' => array(163,1),
		),

		'Current' => array(
			'favicon' => 'current.png',
			'url' => 'http://current.com/clipper.htm?url=PERMALINK&amp;title=TITLE',
			'spriteCoordinates' => array(181,1),
		),

		'del.icio.us' => array(
			'favicon' => 'delicious.png',
			'url' => 'http://delicious.com/post?url=PERMALINK&amp;title=TITLE&amp;notes=EXCERPT',
			'spriteCoordinates' => array(199,1),
		),

		'Design Float' => array(
			'favicon' => 'designfloat.png',
			'url' => 'http://www.designfloat.com/submit.php?url=PERMALINK&amp;title=TITLE',
			'spriteCoordinates' => array(217,1),
		),

		'Digg' => array(
			'favicon' => 'digg.png',
			'url' => 'http://digg.com/submit?phase=2&amp;url=PERMALINK&amp;title=TITLE&amp;bodytext=EXCERPT',
			'description' => 'Digg',
			'spriteCoordinates' => array(235,1),
		),

		'Diigo' => array(
			'favicon' => 'diigo.png',
			'url' => 'http://www.diigo.com/post?url=PERMALINK&amp;title=TITLE',
			'spriteCoordinates' => array(253,1),
		),

		'DotNetKicks' => array(
			'favicon' => 'dotnetkicks.png',
			'url' => 'http://www.dotnetkicks.com/kick/?url=PERMALINK&amp;title=TITLE',
			'spriteCoordinates' => array(271,1),
		),

		'DZone' => array(
			'favicon' => 'dzone.png',
			'url' => 'http://www.dzone.com/links/add.html?url=PERMALINK&amp;title=TITLE',
			'spriteCoordinates' => array(289,1),
		),

		'eKudos' => array(
			'favicon' => 'ekudos.png',
			'url' => 'http://www.ekudos.nl/artikel/nieuw?url=PERMALINK&amp;title=TITLE&amp;desc=EXCERPT',
			'spriteCoordinates' => array(307,1),
		),

		'email' => array(
			'favicon' => 'email_link.png',
			'url' => 'mailto:?subject=TITLE&amp;body=PERMALINK',
			'spriteCoordinates' => array(325,1)
		),

		'Facebook' => array(
			'favicon' => 'facebook.png',
			'url' => 'http://www.facebook.com/share.php?u=PERMALINK&amp;t=TITLE',
			'spriteCoordinates' => array(343,1),
		),

		'Fark' => array(
			'favicon' => 'fark.png',
			'url' => 'http://cgi.fark.com/cgi/fark/farkit.pl?h=TITLE&amp;u=PERMALINK',
			'spriteCoordinates' => array(1,19),
		),

		'Fleck' => array(
			'favicon' => 'fleck.png',
			'url' => 'http://beta3.fleck.com/bookmarklet.php?url=PERMALINK&amp;title=TITLE',
			'spriteCoordinates' => array(19,19),
		),

		'FriendFeed' => array(
			'favicon' => 'friendfeed.png',
			'url' => 'http://www.friendfeed.com/share?title=TITLE&amp;link=PERMALINK',
			'spriteCoordinates' => array(37,19),
		),

		'FSDaily' => array(
			'favicon' => 'fsdaily.png',
			'url' => 'http://www.fsdaily.com/submit?url=PERMALINK&amp;title=TITLE',
			'spriteCoordinates' => array(55,19),
		),

		'Global Grind' => array (
			'favicon' => 'globalgrind.png',
			'url' => 'http://globalgrind.com/submission/submit.aspx?url=PERMALINK&amp;type=Article&amp;title=TITLE',
			'spriteCoordinates' => array(73,19),
		),

		'Google' => array (
			'favicon' => 'googlebookmark.png',
			'url' => 'http://www.google.com/bookmarks/mark?op=edit&amp;bkmk=PERMALINK&amp;title=TITLE&amp;annotation=EXCERPT',
			'description' => 'Google Bookmarks',
			'spriteCoordinates' => array(91,19),
		),

		'Google Buzz' => array (
			'favicon' => 'googlebuzz.png',
			'url' => 'http://www.google.com/reader/link?url=PERMALINK&amp;title=TITLE&amp;srcURL=PERMALINK&amp;srcTitle=BLOGNAME',
			'description' => 'Google Buzz'
		),

		'Gwar' => array(
			'favicon' => 'gwar.png',
			'url' => 'http://www.gwar.pl/DodajGwar.html?u=PERMALINK',
			'spriteCoordinates' => array(109,19)
		),

		'HackerNews' => array(
			'favicon' => 'hackernews.png',
			'url' => 'http://news.ycombinator.com/submitlink?u=PERMALINK&amp;t=TITLE',
			'spriteCoordinates' => array(127,19),
		),

		'Haohao' => array(
			'favicon' => 'haohao.png',
			'url' => 'http://www.haohaoreport.com/submit.php?url=PERMALINK&amp;title=TITLE',
			'spriteCoordinates' => array(145,19),
		),

		'HealthRanker' => array(
			'favicon' => 'healthranker.png',
			'url' => 'http://healthranker.com/submit.php?url=PERMALINK&amp;title=TITLE',
			'spriteCoordinates' => array(163,19),
		),

		'HelloTxt' => array(
			'favicon' => 'hellotxt.png',
			'url' => 'http://hellotxt.com/?status=TITLE+PERMALINK',
			'spriteCoordinates' => array(181,19),
		),

		'Hemidemi' => array(
			'favicon' => 'hemidemi.png',
			'url' => 'http://www.hemidemi.com/user_bookmark/new?title=TITLE&amp;url=PERMALINK',
			'spriteCoordinates' => array(199,19),
		),

		'Hyves' => array(
			'favicon' => 'hyves.png',
			'url' => 'http://www.hyves.nl/profilemanage/add/tips/?name=TITLE&amp;text=EXCERPT+PERMALINK&amp;rating=5',
			'spriteCoordinates' => array(217,19),
		),

		'Identi.ca' => array(
			'favicon' => 'identica.png',
			'url' => 'http://identi.ca/notice/new?status_textarea=PERMALINK',
			'spriteCoordinates' => array(235,19)
		),

		'IndianPad' => array(
			'favicon' => 'indianpad.png',
			'url' => 'http://www.indianpad.com/submit.php?url=PERMALINK',
			'spriteCoordinates' => array(253,19),
		),

		'Internetmedia' => array(
			'favicon' => 'im.png',
			'url' => 'http://internetmedia.hu/submit.php?url=PERMALINK',
			'spriteCoordinates' => array(271,19),
		),

		'Kirtsy' => array(
			'favicon' => 'kirtsy.png',
			'url' => 'http://www.kirtsy.com/submit.php?url=PERMALINK&amp;title=TITLE',
			'spriteCoordinates' => array(289,19),
		),

		'laaik.it' => array(
			'favicon' => 'laaikit.png',
			'url' => 'http://laaik.it/NewStoryCompact.aspx?uri=PERMALINK&amp;headline=TITLE&amp;cat=5e082fcc-8a3b-47e2-acec-fdf64ff19d12',
			'spriteCoordinates' => array(307,19),
		),

		'LaTafanera' => array(
			'favicon' => 'latafanera.png',
			'url' => 'http://latafanera.cat/submit.php?url=PERMALINK',
			'spriteCoordinates' => array(289,73),
		),

		'LinkArena' => array(
			'favicon' => 'linkarena.png',
			'url' => 'http://linkarena.com/bookmarks/addlink/?url=PERMALINK&amp;title=TITLE',
			'spriteCoordinates' => array(325,19),
		),

		'LinkaGoGo' => array(
			'favicon' => 'linkagogo.png',
			'url' => 'http://www.linkagogo.com/go/AddNoPopup?url=PERMALINK&amp;title=TITLE',
			'spriteCoordinates' => array(343,19),
		),

		'LinkedIn' => array(
			'favicon' => 'linkedin.png',
			'url' => 'http://www.linkedin.com/shareArticle?mini=true&amp;url=PERMALINK&amp;title=TITLE&amp;source=BLOGNAME&amp;summary=EXCERPT',
			'spriteCoordinates' => array(1,37),
		),

		'Linkter' => array(
			'favicon' => 'linkter.png',
			'url' => 'http://www.linkter.hu/index.php?action=suggest_link&amp;url=PERMALINK&amp;title=TITLE',
			'spriteCoordinates' => array(19,37),
		),

		'Live' => array(
			'favicon' => 'live.png',
			'url' => 'https://favorites.live.com/quickadd.aspx?marklet=1&amp;url=PERMALINK&amp;title=TITLE',
			'spriteCoordinates' => array(37,37),
		),

		'Meneame' => array(
			'favicon' => 'meneame.png',
			'url' => 'http://meneame.net/submit.php?url=PERMALINK',
			'spriteCoordinates' => array(55,37)
		),

		'MisterWong' => array(
			'favicon' => 'misterwong.png',
			'url' => 'http://www.mister-wong.com/addurl/?bm_url=PERMALINK&amp;bm_description=TITLE&amp;plugin=soc',
			'spriteCoordinates' => array(73,37),
		),

		'MisterWong.DE' => array(
			'favicon' => 'misterwong.png',
			'url' => 'http://www.mister-wong.de/addurl/?bm_url=PERMALINK&amp;bm_description=TITLE&amp;plugin=soc',
			'spriteCoordinates' => array(73,37),
		),

		'Mixx' => array(
			'favicon' => 'mixx.png',
			'url' => 'http://www.mixx.com/submit?page_url=PERMALINK&amp;title=TITLE',
			'spriteCoordinates' => array(91,37),
		),

		'MOB' => array(
			'favicon' => 'mob.png',
			'url' => 'http://www.mob.com/share.php?u=PERMALINK&t=TITLE',
			'description' => 'MOB',
			 'spriteCoordinates' => array(217,73),
		),

		'muti' => array(
			'favicon' => 'muti.png',
			'url' => 'http://www.muti.co.za/submit?url=PERMALINK&amp;title=TITLE',
			'spriteCoordinates' => array(109,37),
		),

		'MyShare' => array(
			'favicon' => 'myshare.png',
			'url' => 'http://myshare.url.com.tw/index.php?func=newurl&amp;url=PERMALINK&amp;desc=TITLE',
			'spriteCoordinates' => array(127,37),
		),

		'MySpace' => array(
			'favicon' => 'myspace.png',
			'awesm_channel' => 'myspace',
			'url' => 'http://www.myspace.com/Modules/PostTo/Pages/?u=PERMALINK&amp;t=TITLE',
			'spriteCoordinates' => array(145,37)
		),

		'MSNReporter' => array(
			'favicon' => 'msnreporter.png',
			'url' => 'http://reporter.nl.msn.com/?fn=contribute&amp;Title=TITLE&amp;URL=PERMALINK&amp;cat_id=6&amp;tag_id=31&amp;Remark=EXCERPT',
			'description' => 'MSN Reporter',
			'spriteCoordinates' => array(163,37),
		),

		'N4G' => array(
			'favicon' => 'n4g.png',
			'url' => 'http://www.n4g.com/tips.aspx?url=PERMALINK&amp;title=TITLE',
			'spriteCoordinates' => array(181,37),
		),

		'Netvibes' => array(
			'favicon' => 'netvibes.png',
			'url' =>    'http://www.netvibes.com/share?title=TITLE&amp;url=PERMALINK',
			'spriteCoordinates' => array(199,37),
		),

		'NewsVine' => array(
			'favicon' => 'newsvine.png',
			'url' => 'http://www.newsvine.com/_tools/seed&amp;save?u=PERMALINK&amp;h=TITLE',
			'spriteCoordinates' => array(217,37),
		),

		'Netvouz' => array(
			'favicon' => 'netvouz.png',
			'url' => 'http://www.netvouz.com/action/submitBookmark?url=PERMALINK&amp;title=TITLE&amp;popup=no',
			'spriteCoordinates' => array(235,37),
		),

		'NuJIJ' => array(
			'favicon' => 'nujij.png',
			'url' => 'http://nujij.nl/jij.lynkx?t=TITLE&amp;u=PERMALINK&amp;b=EXCERPT',
			'spriteCoordinates' => array(253,37),
		),

		'Orkut' => array(
			'favicon' => 'orkut.png',
			'url' => 'http://promote.orkut.com/preview?nt=orkut.com&amp;du=PERMALINK&amp;tt=TITLE'
		),

		'Ping.fm' => array(
			'favicon' => 'ping.png',
			'awesm_channel' => 'pingfm',
			'url' => 'http://ping.fm/ref/?link=PERMALINK&amp;title=TITLE&amp;body=EXCERPT',
			'spriteCoordinates' => array(271,37),
		),

		'Posterous' => array(
			'favicon' => 'posterous.png',
			'url' => 'http://posterous.com/share?linkto=PERMALINK&amp;title=TITLE&amp;selection=EXCERPT',
			'spriteCoordinates' => array(289,37),
		),

		'PDF' => array(
			'favicon' => 'pdf.png',
			'url' => 'http://www.printfriendly.com/print/new?url=PERMALINK',
			'spriteCoordinates' => array(325,37),
		),

		'Plurk' => array(
			'favicon' => 'plurk.png',
			'url' => 'http://www.plurk.com/m?content=PERMALINK (TITLE)&qualifier=shares',
			'description' => 'Plurk'
		),

		'Print' => array(
			'favicon' => 'printfriendly.png',
			'url' => 'http://www.printfriendly.com/print/new?url=PERMALINK',
			'spriteCoordinates' => array(343,37),
		),

		'Propeller' => array(
			'favicon' => 'propeller.png',
			'url' => 'http://www.propeller.com/submit/?url=PERMALINK',
			'spriteCoordinates' => array(1,55),
		),

		'Ratimarks' => array(
			'favicon' => 'ratimarks.png',
			'url' => 'http://ratimarks.org/bookmarks.php/?action=add&address=PERMALINK&amp;title=TITLE',
			'spriteCoordinates' => array(19,55),
		),

		'Rec6' => array(
			'favicon' => 'rec6.png',
			'url' => 'http://rec6.via6.com/link.php?url=PERMALINK&amp;=TITLE',
			'spriteCoordinates' => array(37,55),
		),

		'Reddit' => array(
			'favicon' => 'reddit.png',
			'url' => 'http://reddit.com/submit?url=PERMALINK&amp;title=TITLE',
			'spriteCoordinates' => array(55,55),
		),

		'RSS' => array(
			'favicon' => 'rss.png',
			'url' => 'FEEDLINK',
			'spriteCoordinates' => array(73,55),
		),

		'Scoopeo' => array(
			'favicon' => 'scoopeo.png',
			'url' => 'http://www.scoopeo.com/scoop/new?newurl=PERMALINK&amp;title=TITLE',
			'spriteCoordinates' => array(91,55),
		),

		'Segnalo' => array(
			'favicon' => 'segnalo.png',
			'url' => 'http://segnalo.alice.it/post.html.php?url=PERMALINK&amp;title=TITLE',
			'spriteCoordinates' => array(109,55),
		),

		'SheToldMe' => array(
			'favicon' => 'shetoldme.png',
			'url' => 'http://shetoldme.com/publish?url=PERMALINK&title=TITLE',
			'spriteCoordinates' => array(307,73),
		),

		'Simpy' => array(
			'favicon' => 'simpy.png',
			'url' => 'http://www.simpy.com/simpy/LinkAdd.do?href=PERMALINK&amp;title=TITLE',
			'spriteCoordinates' => array(127,55),
		),

		'Slashdot' => array(
			'favicon' => 'slashdot.png',
			'url' => 'http://slashdot.org/bookmark.pl?title=TITLE&amp;url=PERMALINK',
			'spriteCoordinates' => array(145,55),
		),

		'Socialogs' => array(
			'favicon' => 'socialogs.png',
			'url' => 'http://socialogs.com/add_story.php?story_url=PERMALINK&amp;story_title=TITLE',
			'spriteCoordinates' => array(163,55),
		),

		'SphereIt' => array(
			'favicon' => 'sphere.png',
			'url' => 'http://www.sphere.com/search?q=sphereit:PERMALINK&amp;title=TITLE',
			'spriteCoordinates' => array(181,55),
		),

		'Sphinn' => array(
			'favicon' => 'sphinn.png',
			'url' => 'http://sphinn.com/index.php?c=post&amp;m=submit&amp;link=PERMALINK',
			'spriteCoordinates' => array(199,55),
		),

		'StumbleUpon' => array(
			'favicon' => 'stumbleupon.png',
			'url' => 'http://www.stumbleupon.com/submit?url=PERMALINK&amp;title=TITLE',
			'spriteCoordinates' => array(217,55)
		),

		'Techmeme' => array(
			'favicon' => 'techmeme.png',
			'awesm_channel' => 'twitter-techmeme',
			'url' => 'http://twitter.com/home/?status=tip%20@Techmeme%20PERMALINK%20TITLE',
			'description' => 'Suggest to Techmeme via Twitter',
			'spriteCoordinates' => array(253,55)
		),

		'Technorati' => array(
			'favicon' => 'technorati.png',
			'url' => 'http://technorati.com/faves?add=PERMALINK',
			'spriteCoordinates' => array(271,55),
		),

		'ThisNext' => array(
			'favicon' => 'thisnext.png',
			'url' => 'http://www.thisnext.com/pick/new/submit/sociable/?url=PERMALINK&amp;name=TITLE',
			'spriteCoordinates' => array(289,55),
		),

		'Tipd' => array(
			'favicon' => 'tipd.png',
			'url' => 'http://tipd.com/submit.php?url=PERMALINK',
			'spriteCoordinates' => array(307,55),
		),

		'Tumblr' => array(
			'favicon' => 'tumblr.png',
			'url' => 'http://www.tumblr.com/share?v=3&amp;u=PERMALINK&amp;t=TITLE&amp;s=EXCERPT',
			'spriteCoordinates' => array(325,55)
		),

		'Twitter' => array(
			'favicon' => 'twitter.png',
			'awesm_channel' => 'twitter',
			'url' => 'http://twitter.com/home?status=TITLE%20-%20SHORT_LINK',
			'spriteCoordinates' => array(343,55)
		),

		'Upnews' => array(
			'favicon' => 'upnews.png',
			'url' => 'http://www.upnews.it/submit?url=PERMALINK&amp;title=TITLE',
			'spriteCoordinates' => array(1,73),
		),

		'viadeo FR' => array(
			'favicon' => 'viadeo.png',
			'url' => 'http://www.viadeo.com/shareit/share/?url=PERMALINK&title=TITLE&urllanguage=fr',
			'spriteCoordinates' => array(325,73),
		),

		'Webnews.de' => array(
			'favicon' => 'webnews.png',
			'url' => 'http://www.webnews.de/einstellen?url=PERMALINK&amp;title=TITLE',
			'spriteCoordinates' => array(19,73)
		),

		'Webride' => array(
			'favicon' => 'webride.png',
			'url' => 'http://webride.org/discuss/split.php?uri=PERMALINK&amp;title=TITLE',
			'spriteCoordinates' => array(37,73),
		),

		'Wikio' => array(
			'favicon' => 'wikio.png',
			'url' => 'http://www.wikio.com/vote?url=PERMALINK',
			'spriteCoordinates' => array(55,73),
		),

		'Wikio FR' => array(
			'favicon' => 'wikio.png',
			'url' => 'http://www.wikio.fr/vote?url=PERMALINK',
			'spriteCoordinates' => array(55,73),
		),

		'Wikio IT' => array(
			'favicon' => 'wikio.png',
			'url' => 'http://www.wikio.it/vote?url=PERMALINK',
			'spriteCoordinates' => array(55,73),
		),

		'Wykop' => array(
			'favicon' => 'wykop.png',
			'url' => 'http://www.wykop.pl/dodaj?url=PERMALINK',
			'spriteCoordinates' => array(91,73)
		),

		'Xerpi' => array(
			'favicon' => 'xerpi.png',
			'url' => 'http://www.xerpi.com/block/add_link_from_extension?url=PERMALINK&amp;title=TITLE',
			'spriteCoordinates' => array(109,73),
		),

		'YahooBuzz' => array(
			'favicon' => 'yahoobuzz.png',
			'url' => 'http://buzz.yahoo.com/submit/?submitUrl=PERMALINK&amp;submitHeadline=TITLE&amp;submitSummary=EXCERPT&amp;submitCategory=science&amp;submitAssetType=text',
			'description' => 'Yahoo! Buzz',
			'spriteCoordinates' => array(127,73),
		),

		'Yigg' => array(
			'favicon' => 'yiggit.png',
			'url' => 'http://yigg.de/neu?exturl=PERMALINK&amp;exttitle=TITLE',
			'spriteCoordinates' => array(163,73),
		 ),
	);
?>
