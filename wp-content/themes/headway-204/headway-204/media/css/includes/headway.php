/* Default Styles */

body, body input { font-family: <?php echo headway_get_element_property_value('font', 'div-period-entry-content', 'font-family', true); ?>; }
 
div#wrapper { background: #fff; border: 0 solid; }
 
div.header-link-text {
	margin: 20px 0 6px 15px;
	float: left; }
 
div.header-link-image { float: left; }
 
a.header-link-text-inside {
	padding: 0 0 5px 0;
	border-bottom: 0 solid;
	margin: 0 0 10px; }
 
div.header-link-image a { border-bottom: none; }
  
#tagline {
	width: 85%;
	margin: 0 0 20px 15px;
	float: none;
	display: block;
	clear: left; }

div#header { position: relative;}	

div.header-image #tagline { 
	margin: 0;
	position: absolute; 
	bottom: 30px; 
	left: 30px; }
 
<?php if(headway_get_skin_option('wrapper-vertical-margin') === 0){ ?>
body.header-fluid div#wrapper { border-top: none !important; }
<?php } ?>

ul.navigation li.page_parent.hover a,ul.navigation li.page_parent:hover a {
	padding: 10px 10px 10px;
	z-index: 10001;
	position: relative;
	border-bottom: none; }
   
.leaf-top {
	padding: 2px 4px;
	border-bottom: 0 solid;
	margin: 0 0 5px 0; }
 
div#footer {
	border-top: 0 solid;
	display: block;
	padding: 10px 0; }
  
div#footer * {
	padding: 0;
	margin: 0; }
 
div#footer .footer-left {
	margin-left: 10px;
	float: left; }
 
div#footer .footer-right {
	margin-right: 10px;
	float: right; }
 
div#footer a.no-underline { text-decoration: none; }
 
div#footer .copyright {
	clear: both;
	text-align: center;
	margin: 25px 0 0; }
 
div#footer a.no-underline:hover { text-decoration: underline; }
  
.featured-entry-content {
	clear: both;
	margin: 5px 0;
	float: left; } 
 
.featured-post { margin: 10px 0; }
  
.featured_prev { float: left; }
 
.featured_next { float: right; }
 
.featured_outside_prev,
.featured_outside_next {
	margin: 30px 0 0 0;
	position: relative; }
 
div.leaf-content div.rotator-images {
	display: inline-block;
	top: -5px;
	position: relative; }
  
.align-left { margin: 0 7px 0 0; }
.align-right { margin: 0 0 0 7px; }
 
.about-image {
	padding: 1px;
	border: 1px solid #ccc; }
 
.about-read-more {
	clear: both;
	float: left;
	margin: 3px 0 0; }
 
div.nav-previous {
	float: left;
	margin: 10px 0; }
 
	div.nav-previous a,
div.nav-next a {
		-moz-border-radius: 3px;
		-webkit-border-radius: 3px;
		padding: 7px 8px 6px;
		text-decoration: none;
		display: block; }
 
	div.nav-previous a:hover,
div.nav-next a:hover { text-decoration: underline; }
 
div.nav-next {
	float: right;
	margin: 10px 0; }
 
ul.sidebar {
	margin: 0;
	padding: 0; }
 
	ul.sidebar li { list-style: none; }

		ul.sidebar li ul, ul.link-list {
			margin: 0 0 10px 10px;
			padding: 0;
			list-style: none; }
			
			ul.link-list { margin-top: 5px; float: left; }
 
			ul.sidebar li ul li, ul.link-list li {
				margin: 0 0 7px;
				list-style: none; }
 
				ul.sidebar li ul li ul, ul.link-list li ul {
					padding: 0 0 0 25px;
					margin: 7px 0 7px; }
 
span.widget-title { padding: 2px 4px; border-bottom: 1px solid; }
 
li.widget_socialwidget { text-align: center; }
 
	li.widget_socialwidget span.widget-title { text-align: left; }
 
.entry-title { clear: both; }
 
	.entry-title a,.entry-title a:visited { text-decoration: none; }
   
.page-title { margin: 0 0 20px; }
 
div.post,div.page { display: block; }
 
.entry-meta {
	display: block;
	margin: 3px 0 0 0;
	clear: both; }
  
	.entry-meta a:hover { text-decoration: none; }
 
.meta-above-title .left,.meta-above-title .right { margin: 0 0 5px; }
 
.entry-content { clear: both; }
 
	.entry-content h2,.entry-content h3,.entry-content h4 { margin: 10px 0; }
 
img.border {
	padding: 1px;
	border: 1px solid #ddd; }
 
img.no-border {
	padding: 0;
	border: none; }
 
a.more-link {
	-moz-border-radius: 3px;
	-webkit-border-radius: 3px;
	padding: 3px 7px;
	text-decoration: none;
	margin: 10px 0 20px;
	float: left;
	clear: both; }
 
a.more-link:hover { text-decoration: underline; }
 
div.post, div.small-post {
	margin: 0 0 20px;
	padding: 0 0 20px;
	border-bottom: 1px solid; }
  
.post-image {
	border: 3px double #eaeaea;
	padding: 1px; }
 
.post-image-left {
	float: left;
	margin: 0 10px 10px 0; }
 
.post-image-right {
	float: right;
	margin: 0 0 10px 10px; }
   
div.feed-post {
	margin: 5px 0;
	padding: 10px 0; }
 
body.single div.post { border-bottom: none; }
 
div.small-post { font-size: 90%; }

h3.entry-title { font-size: 80%; }
 
input.text, textarea.text {
	border-top: 1px solid #aaa;
	border-right: 1px solid #e1e1e1;
	border-bottom: 1px solid #e1e1e1;
	border-left: 1px solid #aaa;
	background: #fff;
	font-size: 1.1em;
	padding: 3px;
	color: #4c4c4c; }
 
.text:focus {
	background: #f3f3f3;
	color: #111; }
 
input.text { width: 50%; }
 
textarea.text {
	width: 70%;
	line-height: 1.4em; }
 
input.submit {
	border-top: 1px solid #efefef;
	border-right: 1px solid #777;
	border-bottom: 1px solid #777;
	border-left: 1px solid #efefef;
	background: #eee;
	color: #444;
	font-size: 1.1em;
	padding: 3px 5px; }
  
ol.commentlist {
	margin: 10px 0;
	padding: 0;
	border-bottom: 0 solid; }
 
ol.commentlist { list-style: none; }
 
	ol.commentlist li {
		border: 0 solid;
		list-style: none;
		padding: 10px;
		margin: 0; }
 
		ol.commentlist li ul.children {
			border-top: 0 solid;
			margin: 10px -10px 0 10px; }
  
img.avatar {
	float: right;
	margin: 0 0 2px 5px;
	padding: 1px;
	border: 1px solid #eee; }
 
span.comment-author { font-size: 12px; }
  
span.heading {
	clear: both;
	display: block;
	margin-top: 15px; }
 
p.nocomments {
	border-top: 1px solid #ddd;
	font-size: 12px;
	margin: 10px 0 0;
	padding: 10px 0;
	clear: both; }
 
.comment-info-box {
	background: #f9f9f9;
	border: 1px solid #ddd;
	padding: 7px;
	width: 70%; }
 
.comment-body { line-height: 150%; }
 
div.comments-navigation {
	margin: 15px 0;
	float: left; }
 
div#trackback-box { float: left; }
 
	div#trackback-box span#trackback {
		margin: 0;
		font-size: 12px;
		float: left; }
 
	div#trackback-box span#trackback-url {
		margin: 5px 0 0;
		clear: left;
		font-size: 10px;
		float: left; }
 
ol.commentlist div#respond {
	margin: 10px -10px 0 15px;
	border: 1px solid #ddd;
	border-width: 1px 0;
	padding: 10px 0 0; }
 
div#respond label {
	font-size: 12px;
	color: #555; }
 
ul.subscribe { padding: 0 0 0 15px; }
 
	ul.subscribe li {
		list-style: none;
		padding: 2px 0 2px 22px; }
 
		ul.subscribe li.rss { background: url(<?php bloginfo('template_directory') ?>/media/images/rss.gif) no-repeat; }
 
		ul.subscribe li.email { background: url(<?php bloginfo('template_directory') ?>/media/images/email.gif) no-repeat; }
 
input#s {
	width: 96.5%;
	background: #f6f6f6;
	border: 1px solid #ccc;
	color: #666;
	font-size: 1em;
	padding: 4px 5px; }
 
	input#s:focus {
		background: #fff;
		border: 1px solid #888;
		color: #222; }
 
ul.twitter-updates,ul.sidebar li ul.twitter-updates {
	list-style: none;
	margin: 10px 0 0 10px;
	padding: 0; }
 
.headway-leaf ul.twitter-updates { margin-left: 0; }
 
ul.twitter-updates li,ul.sidebar li ul.twitter-updates li {
	clear: both;
	margin: 0 0 5px;
	padding: 0 0 5px;
	border-bottom: 1px solid #ddd;
	list-style: none; }
 
ul.twitter-updates li span {
	color: #888;
	margin: 0 0 0 6px; }
	
img.wp-smiley { border: none; }
 
.wp-caption {
	padding: 5px;
	border: 1px solid #eee;
	background: #fcfcfc;
	margin-top: 15px;
	margin-bottom: 15px; }
 
	.wp-caption img {
		border: 1px solid #ddd;
		margin: 0 auto;
		display: block;
		padding: 0; }
		
	.wp-caption img.wp-smiley { border: none; }
 
	.wp-caption p {
		text-align: center;
		color: #555;
		margin: 5px 0 0;
		font-style: italic; }
 
div.small-excerpts-row {
	display: block;
	float: left;
	width: 100%;
	border-bottom: 0 solid;
	margin: 0 0 30px;
	padding: 0 0 30px; }
 
div.small-excerpts-post { border-bottom: none; }
 
	div.small-excerpts-post h2 a { font-size: 80%; }
 
	div.small-excerpts-post .entry-content p { font-size: 90%; }
 
/* Prettify Subscribe to Comments checkbox - Thanks to http://headwayhq.com */
#commentform p.subscribe-to-comments input#subscribe {
	display: inline;
	vertical-align: text-top; }
 
#commentform p.subscribe-to-comments label { display: inline; }
 
/* End comments checkbox */
div#page-links { margin: 15px 0; }

div.entry-content ul, div.html div.leaf-content ul {
	margin: 20px 0;
	list-style: disc;
	padding: 0 0 0 35px; }
 
	div.entry-content ul li ul, div.html div.leaf-content ul li ul { margin: 5px 0; }
 
		div.entry-content ul li ul li, div.html div.leaf-content ul li ul li { list-style: circle; }
 
			div.entry-content ul li ul li ul li, div.html div.leaf-content ul li ul li ul li { list-style: square; }
 
div.entry-content ol, div.html div.leaf-content ol {
	margin: 20px 0;
	list-style: decimal;
	padding: 0 0 0 35px; }
 
	div.entry-content ol li ol, div.html div.leaf-content ol li ol { margin: 5px 0; }
 
		div.entry-content ol li ol li, div.html div.leaf-content ol li ol li { list-style: upper-alpha; }
 
			div.entry-content ol li ol li ol li, div.html div.leaf-content ol li ol li ol li { list-style: lower-roman; }

div.entry-content ul li, div.html div.leaf-content ul li {
	list-style: disc;
	margin: 0 0 5px; }
 
div.entry-content ol li, div.html div.leaf-content ol li {
	list-style: decimal;
	margin: 0 0 5px; }
	
div.entry-content em a, div.html div.leaf-content em a { font-style: italic; }
div.entry-content strong a, div.html div.entry-content strong a { font-weight: bold; }
 
blockquote {
	color: #666;
	padding: 5px 0 5px 26px;
	background: url(<?php bloginfo('template_directory') ?>/media/images/blockquote.gif) no-repeat 5px 20px;
	border-top: 0 dotted;
	border-bottom: 0 dotted;
	margin: 10px 0; }
 
em,i { font-style: italic; }
 
.notice {
	background: #FFFFE0;
	border: 1px solid #E6DB55;
	margin: 10px 0;
	padding: 10px; }
	
.warning { 
	background: #FBE3E4;
	border-color: #FBC2C4;
	color: #8A1F11;
}
 
.drop-cap {
	font-size: 310%;
	line-height: 120%;
	margin-bottom: -0.25em;
	color: #888;
	float: left;
	padding: 0 6px 0 0; }
 
code {
	background: #EAEAEA;
	font-family: Consolas,Monaco,Courier,monospace;
	font-size: 0.9em;
	margin: 0 1px;
	padding: 1px 3px; }
 
.code {
	display: block;
	background: #eee;
	border: 1px solid #ddd;
	color: #555;
	font-family: Consolas,Monaco,Courier,monospace;
	padding: 10px;
	overflow: auto;
	white-space: pre;
	font-size: 12.5px;
	line-height: 18px;
	margin: 5px 0; }
 
.required,.unapproved { color: #aa0000; } 
 
div#nav-below-single { width: 100%; }
 
div#greet_block,div#greet_block div { display: block; }

div#header a#header-rss-link { 
	background: url(<?php bloginfo('template_directory') ?>/media/images/rss.gif) no-repeat; 
	padding: 0 0 0 22px; 
	float: right; 
	margin: 15px 15px 7px 7px; 
	font-size: 13px; 
	height: 16px; 
	line-height: 16px; }
	
div#wrapper div.header-image a#header-rss-link, div#header-container div.header-image a#header-rss-link { position: absolute; margin: 0; top: 20px; right: 20px; }

form#header-search-bar { float: right;  }
div#navigation { position: relative; }

form#header-search-bar input { 
	color: rgba(0, 0, 0, .9);
	background-color: rgba(255, 255, 255, 0.6);
	position: absolute; 
	width: 20%; 
	margin: -12px 7px 0 7px; 
	padding: 4px 5px;
	top: 50%; 
	right: 0; 
	height: 14px; 
	line-height: 14px; 
	display: block; 
	border-top: 1px solid rgba(0, 0, 0, 0.35);
	border-left: 1px solid rgba(0, 0, 0, 0.35);
	border-right: 1px solid rgba(0, 0, 0, 0.1);
	border-bottom: 1px solid rgba(0, 0, 0, 0.1); }
	
form#header-search-bar input:focus { background-color: rgba(255, 255, 255, 0.65); }

div.navigation-right form#header-search-bar { float: left; }
div.navigation-right form#header-search-bar input { left: 0; }
