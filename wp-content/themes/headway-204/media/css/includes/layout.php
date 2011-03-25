div#whitewrap { display: inline-block; width: 100%; }

div#wrapper {
	<?php if(headway_get_skin_option('wrapper-margin', true) && headway_get_skin_option('wrapper-vertical-margin') == 30){ ?>
	margin: <?php echo headway_get_skin_option('wrapper-margin')?>;
	<?php } else { ?>
	margin: <?php echo headway_get_skin_option('wrapper-vertical-margin')?>px auto;
	<?php }	?>
	
	width: <?php echo str_replace('px', '', headway_get_skin_option('wrapper-width'))?>px;
	clear: both; }
 
div.container { 
	padding: <?php echo str_replace('px', '', headway_get_skin_option('leaf-container-vertical-padding'))?>px <?php echo str_replace('px', '', headway_get_skin_option('leaf-container-horizontal-padding'))?>px;
	width: <?php echo str_replace('px', '', headway_get_skin_option('wrapper-width'))-str_replace('px', '', headway_get_skin_option('leaf-container-horizontal-padding'))*2 ?>px; }
	
div#container { margin-bottom: 5px; }

.header-outside div#wrapper {
	border-width: 0 1px 1px 1px;
	margin: 0 auto; }
 
#header-container {
	width: 100%;
	border-bottom: 0 solid;
	border-top: 0 solid;
	float: left; }
 
#header {
	margin: 0 auto;
	width: <?php echo str_replace('px', '', headway_get_skin_option('wrapper-width'))?>px;
	clear: both;
	float: left; }
 
.header-fixed #header { border-bottom: 0 solid; border-top: 0 solid; }
 
.header-fluid #header { float: none; }
 
div.header-link-top { margin: 10px 0 5px 10px; }
  
div.header-link-image {
	margin:<?php echo (headway_get_skin_option('header-image-margin') || headway_get_skin_option('header-image-margin') == '0') ? headway_get_skin_option('header-image-margin'): '15px';
	?>; }
 
a.header-link-image-inside {
	float: left;
	margin: 0; }
 
	a.header-link-image-inside img { float: left; }
 
div#navigation-container {
	border-bottom: 0 solid;
	border-top: 0 solid;
	clear: both;
	width: 100%; }
 
div#navigation {
	float: left;
	width: <?php echo str_replace('px', '', headway_get_skin_option('wrapper-width'))?>px;
	display: block;
	clear: both; }
 
.header-fixed div#navigation { border-bottom: 0 solid; border-top: 0 solid; }
 
.header-fluid div#navigation {
	float: none;
	margin: 0 auto; }
 
ul.navigation {
	margin: 0;
	padding: 0;
	list-style: none;
	float: left;
	position: relative; }
 
ul.search-active { margin-right: 23%; }

ul.navigation-right { float: right; margin-right: 0; }
 
.header-outside ul.navigation {
	border-left: 0 solid;
	margin: 0 0 0 -1px;
	position: relative; }
 
ul.navigation li {
	float: left;
	list-style: none;
	margin: 0;
	position: relative; }
 
	ul.navigation li a {
		padding: 10px;
		text-decoration: none;
		border-right: 0 solid;
		margin: 0;
		display: block; }
 
	ul.navigation li a:hover { text-decoration: underline; }
 
	ul.navigation li.current_page_item a, ul.navigation li.current_page_parent a, ul.navigation li.current_page_parent a:hover { text-decoration: none; }
 
	ul.navigation li ul {
		display: none;
		position: absolute;
		padding: 0 0 1px;
		z-index: 10002;
		margin: 0;
		left: 0;
		width: 120px; }
 
	ul.navigation li.current_page_parent ul { background: #eee; }
 
	ul.navigation li ul, ul.navigation li.page_parent ul li,ul.navigation li.page_parent.hover ul li, ul.navigation li.page_parent:hover ul li { width: <?php echo str_replace('px', '', headway_get_skin_option('sub-nav-width')) ?>px; }
 
	ul.navigation li.hover ul,
ul.navigation li:hover ul { display: block; }
 
	ul.navigation li.hover ul li ul,
ul.navigation li:hover ul li ul { display: none; }
 
	ul.navigation li ul li.hover ul,
ul.navigation li ul li:hover ul { display: block; }
 
	ul.navigation li ul li.hover ul li ul,
ul.navigation li ul li:hover ul li ul { display: none; }
 
	ul.navigation li ul li ul li.hover ul,
ul.navigation li ul li ul li:hover ul { display: block; }
 
	ul.navigation li ul li ul li.hover ul li ul,
ul.navigation li ul li ul li:hover ul li ul { display: none; }
 
	ul.navigation li ul li ul li ul li.hover ul,
ul.navigation li ul li ul li ul li:hover ul { display: block; }
 
	ul.navigation li ul li ul li ul li.hover ul li ul,
ul.navigation li ul li ul li ul li:hover ul li ul { display: none; }
 
	ul.navigation li ul li ul li ul li ul li.hover ul,
ul.navigation li ul li ul li ul li ul li:hover ul { display: block; }
 
ul.navigation .hide { display: none !important; }
 
ul.navigation .show { display: block !important; }
 
ul.navigation li ul li {
	margin: 0;
	list-style: none;
	float: none;
	position: relative; }
 
	ul.navigation li ul li a {
		padding: 10px;
		border: none;
		width: auto; }
    
ul.navigation li ul li ul {
	display: block;
	position: absolute;
	float: none;
	margin-left: <?php echo str_replace('px', '', headway_get_skin_option('sub-nav-width'))+1?>px;
	clear: none;
	top: -1px; }
 
div#breadcrumbs-container {
	border-bottom: 0 solid;
	border-top: 0 solid;
	clear: both; }
 
div#breadcrumbs {
	float: left;
	width: <?php echo str_replace('px', '', headway_get_skin_option('wrapper-width'))?>px; }
 
.header-fixed div#breadcrumbs { border-bottom: 0 solid; border-top: 0 solid; }
 
.header-fluid div#breadcrumbs {
	float: none;
	margin: 0 auto; }
 
div#breadcrumbs p {
	padding: 0;
	margin: 0 10px;
	display: block;
	width: <?php echo str_replace('px', '', headway_get_skin_option('wrapper-width'))-20?>px;
	overflow: hidden; }
  
div.headway-leaf {
	float: left;
	width: 250px;
	margin: <?php echo str_replace('px', '', headway_get_skin_option('leaf-margins'))?>px;
	overflow: hidden;
	min-height: 125px; }
	
div.headway-leaf { padding: <?php echo str_replace('px', '', headway_get_skin_option('leaf-padding'))?>px; }

.leafs-column .headway-leaf { padding: 0; }
.leafs-column .headway-leaf .headway-leaf-inside { padding: <?php echo str_replace('px', '', headway_get_skin_option('leaf-padding'))?>px; }
	
div.resize-container div.resize { border-width: <?php echo str_replace('px', '', headway_get_skin_option('leaf-margins'))-1 ?>px; }
	
div.leafs-column div.hw-ui-sortable-helper { max-width: <?php echo str_replace('px', '', headway_get_skin_option('wrapper-width'))-20?>px !important; }	

div#columns-container { width: 100%; }
	
div#container div.hw-ui-sortable-placeholder, div#top-container div.hw-ui-sortable-placeholder, div#bottom-container div.hw-ui-sortable-placeholder {
	margin: 1px !important;
	border-width: 1px !important;
	padding: <?php echo str_replace('px', '', headway_get_skin_option('leaf-margins'))-2+str_replace('px', '', headway_get_skin_option('leaf-padding')) ?>px !important; }

div#wrapper div.leafs-column div.hw-ui-sortable-placeholder { width: 100% !important; padding: 0 !important; }
div#wrapper div.resize-column div.hw-ui-sortable-placeholder { margin: 0 0 1px -1px !important; padding-bottom: <?php echo (str_replace('px', '', headway_get_skin_option('leaf-margins'))*2)-4; ?>px !important; }

<?php
$leaf_containers_border_style_query = (headway_get_skin_option('leaf-columns-border-style') == 'no border') ? 'none' : headway_get_skin_option('leaf-columns-border-style');
$leaf_containers_border_color_query = headway_get_skin_option('leaf-columns-border-color');

$leaf_containers_border_style = ($leaf_containers_border_style_query) ? $leaf_containers_border_style_query : 'solid';
$leaf_containers_border_color = ($leaf_containers_border_color_query) ? $leaf_containers_border_color_query : 'dddddd';

$leaf_containers_border_width = ($leaf_containers_border_style == 'none') ? 0 : 1;
$leaf_containers_border_width = ($leaf_containers_border_style == 'double') ? 3 : 1;

$leaf_containers_padding = ($leaf_containers_border_style_query == 'double') ? 13 : 15;
$leaf_columns_padding = ($leaf_containers_border_style == 'none') ? 10 : 9;
$leaf_columns_padding = ($leaf_containers_border_style == 'double') ? 7 : 9;
?>
div#wrapper div.clear { clear: both; display: block; height: 0; }

div#wrapper div.leafs-column { 
	border-right-style: <?php echo $leaf_containers_border_style ?>;
	border-right-width: <?php echo $leaf_containers_border_width ?>px; 
	border-right-color: #<?php echo $leaf_containers_border_color ?>;
	min-height: 200px; 
	display: block; 
	margin: 0;
	padding: 10px <?php echo $leaf_columns_padding ?>px 10px 10px;
	height: auto;
	float: left; }
	
body.headway-visual-editor-open div#wrapper div.leafs-column { margin: 0; padding: 10px <?php echo $leaf_columns_padding ?>px 10px 10px; }

div#wrapper div.last-leafs-column, body.headway-visual-editor-open div#wrapper div.last-leafs-column { border-right: 0; padding-right: 10px; }

	div#wrapper div.leafs-column .headway-leaf { width: 100% !important; margin: 0 0 <?php echo str_replace('px', '', headway_get_skin_option('leaf-margins'))*2; ?>px; }
	div#wrapper div.leafs-column .hw-ui-sortable-placeholder { margin-bottom: <?php echo (str_replace('px', '', headway_get_skin_option('leaf-margins'))*2)-2; ?>px; }
	div#wrapper div.leafs-column .hw-ui-sortable-helper { width: inherit !important; }
	

div#wrapper div#top-container { 
	border-right: 0; 
	margin: 0;
	border-bottom-style: <?php echo $leaf_containers_border_style ?>;
	border-bottom-color: #<?php echo $leaf_containers_border_color ?>;
	border-bottom-width: <?php echo $leaf_containers_border_width ?>px; 
	padding-bottom: <?php echo $leaf_containers_padding ?>px;
	min-height: 50px; }
	
div#wrapper div#bottom-container { 
	border-right: 0; 
	padding-right: 5px; 
	margin: 0; 
	border-top-style: <?php echo $leaf_containers_border_style ?>;
	border-top-color: #<?php echo $leaf_containers_border_color ?>;
	border-top-width: <?php echo $leaf_containers_border_width ?>px; 
	padding-top: <?php echo $leaf_containers_padding ?>px;
	min-height: 50px; }
 
div.headway-leaf-right { float: right; }

div.headway-leaf-clear-left { clear: left; }
div.headway-leaf-clear-right { clear: right; }
div.headway-leaf-clear-both { clear: both; }
 
div.leaf-content div.featured-post-container,
div.featured-leaf-container {
	float: left;
	display: block; }
	
div.leaf-content div.featured-post-container { padding-bottom: 15px; }
 
div.featured-entry-content {
	float: left;
	display: block;
	width: 100%;
	margin: -5px 0 5px; }
 
div.leaf-content .entry-meta {
	display: block;
	clear: both; }
 
.fluid-height { height: auto !important; overflow: visible; }
 
div#footer-container {
	width: 100%;
	border-top: 0 solid; }
 
div#footer {
	margin: 0 auto;
	width: <?php echo str_replace('px', '', headway_get_skin_option('wrapper-width'))?>px;
	clear: both;
	min-height: 17px; }
 
.footer-fluid #footer {
	float: none;
	border-top: none; }
 
.align-left,.alignleft {
	float: left;
	margin: 0 7px 0 0; }
 
.align-right,.alignright {
	float: right;
	margin: 0 0 0 7px; }
 
.aligncenter {
	display: block;
	margin-left: auto;
	margin-right: auto;
	clear: both; }
 
.widget-title {
	margin: 0 0 10px;
	display: block; }
 
li.widget { margin: 0 0 25px; }
 
label { display: block; }
 
input,textarea,label { clear: both; }

div.entry-content input, div.entry-content textarea, div.entry-content label { clear: none; }
 
input,textarea { margin: 0 0 10px; }
 
div.post { display: block; }
 
.entry-meta .left { float: left; }

<?php
//Catch exception to do wordwrapping
if(headway_get_option('post-below-title-left') == 'Written on %date% by %author% in %categories%' && headway_get_option('post-below-title-right') == '%comments% - %respond%'){
	echo 'div.meta-below-title div.left { width: 65%; }';
	echo 'div.meta-below-title div.right { width: 30%; text-align: right; }';
}
?>
 
.entry-meta .right { float: right; }
 
.meta-below-content .left,
.meta-below-content .right,
.meta-above-title .left,
.meta-above-title .right { margin: 0; }
 
div.nav-below { 
	margin: 10px 0;
	display: block; }
 
div.gallery div.leaf-content div { display: block; }
 
div.content-slider div.leaf-content div { display: block; }
 
div.feed div.leaf-content div { display: block; }
 
div.content-slider-controller { margin: -20px 0 0 0; }
 
div.sidebar ul.horizontal-sidebar li.widget {
	float: left;
	margin: 0 15px 0 15px;
	width: 20%; }
 
.content .post, .content .page { width: 100%; }
div.small-excerpts-row div.small-excerpts-post { width: 48.5%; float: left; margin: 0; padding: 0; }
div.small-excerpts-row div.small-excerpts-post-left { padding-right: 3%; }

<?php if(headway_get_skin_option('wrapper-border-radius') > 0){ ?>
div#wrapper {
	-webkit-border-radius: <?php echo headway_get_skin_option('wrapper-border-radius') ?>px;
	-moz-border-radius: <?php echo headway_get_skin_option('wrapper-border-radius') ?>px;
	border-radius: <?php echo headway_get_skin_option('wrapper-border-radius') ?>px;
}

body.header-fluid div#top-container {
	-webkit-border-top-left-radius: <?php echo headway_get_skin_option('wrapper-border-radius') ?>px;
	-webkit-border-top-right-radius: <?php echo headway_get_skin_option('wrapper-border-radius') ?>px;
	-moz-border-radius-topleft: <?php echo headway_get_skin_option('wrapper-border-radius') ?>px;
	-moz-border-radius-topright: <?php echo headway_get_skin_option('wrapper-border-radius') ?>px;
	border-top-left-radius: <?php echo headway_get_skin_option('wrapper-border-radius') ?>px;
	border-top-right-radius: <?php echo headway_get_skin_option('wrapper-border-radius') ?>px;
}

<?php if(headway_get_element_styles(array('element' => 'div#wrapper', 'property' => 'border-width')) <= 5){ ?>
div.header-rearrange-item-1 {
	-webkit-border-top-left-radius: <?php echo headway_get_skin_option('wrapper-border-radius') ?>px;
	-webkit-border-top-right-radius: <?php echo headway_get_skin_option('wrapper-border-radius') ?>px;
	-moz-border-radius-topleft: <?php echo headway_get_skin_option('wrapper-border-radius') ?>px;
	-moz-border-radius-topright: <?php echo headway_get_skin_option('wrapper-border-radius') ?>px;
	border-top-left-radius: <?php echo headway_get_skin_option('wrapper-border-radius') ?>px;
	border-top-right-radius: <?php echo headway_get_skin_option('wrapper-border-radius') ?>px;
}

div.header-rearrange-item-1 ul.navigation li:first-child a {
	-webkit-border-top-left-radius: <?php echo headway_get_skin_option('wrapper-border-radius') ?>px;
	-moz-border-radius-topleft: <?php echo headway_get_skin_option('wrapper-border-radius') ?>px;
	border-top-left-radius: <?php echo headway_get_skin_option('wrapper-border-radius') ?>px;
}

div.header-rearrange-item-1 ul.navigation-right li:last-child a {
	-moz-border-radius: 0;
	-webkit-border-radius: 0;
	border-radius: 0;
	
	-webkit-border-top-right-radius: <?php echo headway_get_skin_option('wrapper-border-radius') ?>px;
	-moz-border-radius-topright: <?php echo headway_get_skin_option('wrapper-border-radius') ?>px;
	border-top-right-radius: <?php echo headway_get_skin_option('wrapper-border-radius') ?>px;
}
<?php } ?>

div#footer {
	-webkit-border-bottom-left-radius: <?php echo headway_get_skin_option('wrapper-border-radius') ?>px;
	-webkit-border-bottom-right-radius: <?php echo headway_get_skin_option('wrapper-border-radius') ?>px;
	-moz-border-radius-bottomleft: <?php echo headway_get_skin_option('wrapper-border-radius') ?>px;
	-moz-border-radius-bottomright: <?php echo headway_get_skin_option('wrapper-border-radius') ?>px;
	border-bottom-left-radius: <?php echo headway_get_skin_option('wrapper-border-radius') ?>px;
	border-bottom-right-radius: <?php echo headway_get_skin_option('wrapper-border-radius') ?>px;
}
<?php } ?>
<?php if(headway_get_skin_option('leaf-border-radius') > 0){ ?>
div.headway-leaf {
	-webkit-border-radius: <?php echo headway_get_skin_option('leaf-border-radius') ?>px;
	-moz-border-radius: <?php echo headway_get_skin_option('leaf-border-radius') ?>px;
	border-radius: <?php echo headway_get_skin_option('leaf-border-radius') ?>px;
}
<?php } ?>