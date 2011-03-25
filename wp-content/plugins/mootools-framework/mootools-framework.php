<?php
/*
Plugin Name: Mootools Framework
Plugin URI: http://nilswindisch.de
Description: Adds the Mootools JS Library (Version 1.2, all functions, YUI compressed).
Author: Nils K. Windisch
Version: 1.2
Author URI: http://nilswindisch.de
*/

add_action("wp_head","mootoolsFramework");

function mootoolsFramework() {
	echo "\n\n<script type='text/javascript' src='".get_bloginfo('wpurl')."/wp-content/plugins/mootools-framework/mootools.js?v=1.2'></script>\n\n";
}

?>