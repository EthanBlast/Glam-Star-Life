<?php 

/*

Plugin Name: Image resize wizz

Plugin URI: www.wpwizz.com

Description: This plugin helps you to limit maximum pictures width from post content. 

Version: 1.1

Author: wpwizz

Author URI: http://www.wpwizz.com

Programed: Marius Moiceanu (marius81@gmail.com)

*/
error_reporting(0);
add_action('admin_menu', 'show_config_page2');



 function show_config_page2() {

			global $wpdb;

			if ( function_exists('add_options_page') ) {

				add_options_page('Picture Wizz Configuration', 'Picture Wizz', 9, basename(__FILE__), 'show_page2');

			}

			}



function show_page2() { 

?>

<div class="wrap">
<div id="icon-options-general" class="icon32"><br /></div>
<h2>Picture Wizz</h2>


<?php 

include "../wp-content/plugins/image-wizz/options.php";

}







function the_content_resized($content) {



$size = get_option('pic_size');

if ( is_home()) {
$size = get_option('pic_size_home');
}

if ( is_page()) {
$size = get_option('pic_size_page');
}

if ( is_category() || is_archive() || is_date() || is_tag() || is_search()) {
$size = get_option('pic_size_category');
}

if (empty($size)){

$size=500;

}


$split = explode("<img",$content);

$count = count($split);

for ($i=1;$i<$count;$i++)

{

$piece = explode(">",$split[$i]);

$imgtag="<img".$piece[0].">";

$output = preg_match('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $imgtag, $link);
$class_output = preg_match('/<img.+class=[\'"]([^\'"]+)[\'"].*>/i', $imgtag, $class);

$original = imagecreatefromstring(file_get_contents($link[1]));

$osy = imagesy($original);

$osx = imagesx($original);

 

if ($osx>$size) {

$proc = $size/($osx/100);

$osy = $osy*($proc/100);

$osx = $size;

$tag = "<img src='".$link[1]."' class='".$class[1]."' width='".$osx."' height='".$osy."'/>";

$content = str_replace($imgtag,$tag,$content);				

    } 

}

return $content;

}

add_filter('the_content','the_content_resized');




?>