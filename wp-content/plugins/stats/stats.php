<?php
/*
Plugin Name: WordPress.com Stats Plus Connect
Plugin URI: http://wordpress.org/extend/plugins/stats/
Description: Tracks views, post/page views, referrers, and clicks. Requires a WordPress.com account.
Author: Andy Skelton
Version: trunk
License: GPL v2 - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
Text Domain: stats
*/

define( 'STATS_VERSION', '5' );
define( 'STATS_FILE', __FILE__ );
define( 'STATS_XMLRPC_SERVER', 'http://wordpress.com/xmlrpc.php' );

require 'lib/options.php';
require 'lib/metrics.php';
require 'lib/connect.php';
require 'lib/shortlinks.php';

if ( is_admin() ) :
require 'lib/activation.php';
require 'lib/dashboard.php';
require 'lib/notices.php';
require 'lib/admin.php';
endif;

require 'load.php';
