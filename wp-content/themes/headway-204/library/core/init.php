<?php
//Do not allow previewing of theme to avoid errors and accidentally upgrades, downgrades, etc.
if(isset($_GET['template']) && isset($_GET['preview']) && isset($_GET['stylesheet']))
	die('<h1>Please activate Headway instead of previewing.');
	

//Define simple constants
define('SITE', strtolower(str_replace(' ', '-', preg_replace("/[^A-Za-z0-9 ]/", '', get_bloginfo('name')))));
define('THEME_FRAMEWORK', 'headway');
define('HEADWAYVERSION', '2.0.4');


//Define directories
define('HEADWAYROOT', TEMPLATEPATH);
define('HEADWAYLIBRARY', HEADWAYROOT.'/library');
define('HEADWAYADMIN', HEADWAYLIBRARY.'/admin');
define('HEADWAYCORE', HEADWAYLIBRARY.'/core');
define('HEADWAYLANGUAGES', HEADWAYLIBRARY.'/languages');
define('HEADWAYRESOURCES', HEADWAYLIBRARY.'/resources');
define('HEADWAYLEAFS', HEADWAYLIBRARY.'/leafs');
define('HEADWAYWIDGETS', HEADWAYLIBRARY.'/widgets');
define('HEADWAYEDITOR', HEADWAYLIBRARY.'/visual-editor');
define('HEADWAYMEDIA', HEADWAYROOT.'/media');
define('HEADWAYFOLDER', basename(get_bloginfo('template_url')));
define('HEADWAYURL', get_bloginfo('template_url'));


//Define directories for multi-site
if(is_main_site()){
	define('HEADWAYCUSTOM', HEADWAYROOT.'/custom');
	define('HEADWAYCACHE', HEADWAYMEDIA.'/cache');
	define('HEADWAYCACHEDIR', 'cache');
} else {
	define('HEADWAYCUSTOM', HEADWAYROOT.'/custom/sites/'.SITE);
	define('HEADWAYCACHE', HEADWAYMEDIA.'/cache/sites/'.SITE);
	define('HEADWAYCACHEDIR', 'cache/sites/'.SITE);
}	


//Load locale
load_theme_textdomain('headway', HEADWAYLANGUAGES);


//Load all required files
require_once HEADWAYCORE.'/data-handling.php';
require_once HEADWAYCORE.'/hooks.php';
require_once HEADWAYCORE.'/api.php';
require_once HEADWAYCORE.'/functions.php';
require_once HEADWAYCORE.'/leafs.php';
require_once HEADWAYCORE.'/generator.php';
require_once HEADWAYCORE.'/triggers.php';
require_once HEADWAYCORE.'/feed.php';
require_once HEADWAYCORE.'/elements.php';
require_once HEADWAYCORE.'/data.php';
require_once HEADWAYCORE.'/fonts.php';

require_once HEADWAYLIBRARY.'/installation/installation.php';

if(!is_admin()){
	require_once HEADWAYEDITOR.'/visual-editor.php';
} else {
	require_once HEADWAYCORE.'/updater.php';
	require_once HEADWAYADMIN.'/admin.php';
}

require_once HEADWAYCORE.'/navigation.php';
require_once HEADWAYCORE.'/posts.php';
require_once HEADWAYCORE.'/layout.php';
require_once HEADWAYCORE.'/head.php';
require_once HEADWAYCORE.'/css-classes.php';
require_once HEADWAYCORE.'/comments.php';

require_once HEADWAYWIDGETS.'/functions.php';


//Load custom functions files if they exist
if(file_exists(HEADWAYROOT.'/custom/network_custom.php') && is_multisite()) include_once HEADWAYROOT.'/custom/network_custom.php';

if(file_exists(HEADWAYCUSTOM.'/custom_functions.php')) include_once HEADWAYCUSTOM.'/custom_functions.php';