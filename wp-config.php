<?php
/** Enable W3 Total Cache **/
define('WP_CACHE', true); // Added by W3 Total Cache

/** WordPress's config file **/
/** http://wordpress.org/   **/

// ** MySQL settings ** //
define('DB_NAME', 'db103018_glamstarlife');     // The name of the database
define('DB_USER', 'db103018');     // Your MySQL username
define('DB_PASSWORD', 'denise512'); // ...and password
define('DB_HOST', 'internal-db.s103018.gridserver.com');     // ...and the server MySQL is running on

// Change the prefix if you want to have multiple blogs in a single database.

$table_prefix  = 'wp_p1k430_';   // example: 'wp_' or 'b2' or 'mylogin_'

// Turning off Post Revisions. Comment this line out if you would like them to be on.

define('WP_POST_REVISIONS', false );

// Change this to localize WordPress.  A corresponding MO file for the
// chosen language must be installed to wp-includes/languages.
// For example, install de.mo to wp-includes/languages and set WPLANG to 'de'
// to enable German language support.
define ('WPLANG', '');

/* Stop editing */

$server = DB_HOST;
$loginsql = DB_USER;
$passsql = DB_PASSWORD;
$base = DB_NAME;

define('ABSPATH', dirname(__FILE__).'/');

// Get everything else
require_once(ABSPATH.'wp-settings.php');
?>
