<?php
/*
Plugin Name: jCarousel for WordPress
Version: 1.0
Description: Overrides the builtin Wordpress gallery and replaces it with a javascript carousel.
Author: Merrill M. Mayer
Author URI:http://www.koolkatwebdesigns.com/
Plugin URI: http://www.koolkatwebdesigns.com/
*/


/*
 TODO:
 - show captions, descriptions, should be optional
 - expanding paragraphs -- possible?
 - tweaks from comments
 - recode the entire thing according to "magazine like slideshow"
*/

load_plugin_textdomain('jcarousel-for-wordpress', NULL, dirname(plugin_basename(__FILE__)));

//add_filter('post_gallery', 'jcarousel_for_wordpress', 10, 2);
add_shortcode('jcarousel_gallery', 'jcarousel_for_wordpress');
add_action('wp_head', 'jcarousel_for_wordpress_header');



/*****************************
* Enqueue jQuery & Scripts
*/
function jcarousel_for_wordpress_enqueue_scripts() {
	if ( function_exists('plugin_url') )
		$plugin_url = plugin_url();
	else
		$plugin_url = get_option('siteurl') . '/wp-content/plugins/' . plugin_basename(dirname(__FILE__));

	// jquery
	wp_deregister_script('jquery');
	wp_register_script('jquery', ($plugin_url  . '/jquery-1.3.2.min.js'), false, '1.3.2');
	wp_enqueue_script('jquery');
	
}
if (!is_admin()) {
	add_action('init', 'jcarousel_for_wordpress_enqueue_scripts');
}




function jcarousel_for_wordpress_header() {
	if ( function_exists('plugin_url') )
		$plugin_url = plugin_url();
	else
		$plugin_url = get_option('siteurl') . '/wp-content/plugins/' . plugin_basename(dirname(__FILE__));

	echo '<link href="' . $plugin_url . '/jcarousel-for-wordpress.css" rel="stylesheet" type="text/css" />' . "\n";
	echo '<script type="text/javascript" src="' . $plugin_url . '/jcarousel.js"></script>' . "\n";

}



function remove_brs($string) {
	$new_string=urlencode ($string);
	$new_string=ereg_replace("%0D", "{br}", $new_string);
	$new_string=ereg_replace("%0A", "{br}", $new_string);
	$new_string=urldecode  ($new_string);
	return $new_string;
}


function jcarousel_for_wordpress($output, $attr) {

	/**
	* Grab attachments
	*/
	global $post;
	
	if ( isset( $attr['orderby'] ) ) {
		$attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
		if ( !$attr['orderby'] )
			unset( $attr['orderby'] );
	}
	
	extract(shortcode_atts(array(
		'order'      => 'ASC',
		'orderby'    => 'menu_order ID',
		'id'         => $post->ID,
		'itemtag'    => 'dl',
		'icontag'    => 'dt',
		'captiontag' => 'dd',
		'columns'    => 3,
		'size'       => 'thumbnail',
	), $attr));
	
	$id = intval($id);
	$attachments = get_children( array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
	
	if ( empty($attachments) )
		return '';
		
	if ( is_feed() ) {
		$output = "\n";
		foreach ( $attachments as $id => $attachment )
			$output .= wp_get_attachment_link($id, $size, true) . "\n";
		return $output;
	}
	


	/**
	* Start output
	*/
	$output = "\t
	<!-- Begin jCarousel for WordPress -->
	<div class='jcarousel-image-expand'>";
	/* hide titles, fixme */
	foreach ( $attachments as $id => $attachment ) {
	    $n++;
	    if ($n == 1) {
		  $output .= "<div id='expandeditem" .$n."'>";
		}
		else {
		  $output .= "<div id='expandeditem" .$n."' class='jcarousel-hide'>";
		}
		$image = wp_get_attachment_image_src($id, "medium");
		if (get_option('jcwp_show_titles') == "true") {		
           $output .= "<h2>".$attachment->post_title."</h2>";
		}   
		$output .= "<p><a href=\"#\"> <img src='" . $image[0] . "' alt='".$attachment->post_title."' title='".$attachment->post_title."'/></a>\n";
		$output .= "<span class='jcarousel-description'>".$attachment->post_content."</span></p>\n";
		
		$output .= "</div>";
	}
	$output .= "</div>";
	
	
	
    

	/**
	* Add ULs
	*/
	$output .= "<ul id='mycarousel_".$post->ID."' class='jcarousel-list'>";
	
	$thumb_size = 'thumbnail';

	
	
	foreach ( $attachments as $id => $attachment ) {
		$link = wp_get_attachment_link($id, $size, true);	
		$image = wp_get_attachment_image_src($id, $size, false); 	//need for height and width
		$style = 'style="width:'.($image[1]+20).'px; height:'.($image[1]+5).'px;"';
        $i++;
		$output .= "<li ".$style."><a ".$style." id=\"item".$i. "\" href=\"#\" class=\"jcarousel-item item".$n."_".$post->ID."\">" . wp_get_attachment_image( $id, $thumb_size ) . "</a></li>\n";
		$n++;
		
	}
	$output .= "</ul>\n";


	/**
	* Initialize
	*/
	$output .= "<script type='text/javascript'>\n
	jQuery(document).ready(function() {";
	
	    if (get_option('jcwp_animation_speed') == 'slow' || get_option('jcwp_animation_speed') == 'fast') {
		   $speed = '"'.stripslashes(get_option('jcwp_animation_speed')).'"';
		}
		else {
		  $speed = stripslashes(get_option('jcwp_animation_speed'));
		}
		$output .= "jQuery('#mycarousel_".$post->ID."').jcarousel({
			scroll: ".stripslashes(get_option('jcwp_scroll_amount'))."
			,itemFirstInCallback: mycarousel_".$post->ID."_itemFirstInCallback
			,animation: ".$speed."
			
		});
		jQuery('.jcarousel-item a').click(function(){
	 var idx = jQuery(this).attr('id').match(/\d/g);
         idx = idx.join('');
		  jQuery('.jcarousel-image-expand div').hide();
		  jQuery('.jcarousel-image-expand #expandeditem' +idx).fadeIn('slow');
		  return false;
    });

	});
	function mycarousel_".$post->ID."_itemFirstInCallback(carousel, item, idx, state) {
		  jQuery('.jcarousel-image-expand div').hide();
		  jQuery('.jcarousel-image-expand #expandeditem' +idx).fadeIn('slow');
		 
		};
	</script>
	";









	
	$output .= "
		<br style='clear: both;' />
	
	<!-- End jCarousel for WordPress -->\n
	";


	return $output;

}






/*****************************
* Options Page
*/

// Options
$jcwp_plugin_name = __("jCarousel for WordPress", 'jcarousel-for-wordpress');
$jcwp_plugin_filename = basename(__FILE__); //"jcarousel-for-wordpress.php";


add_option("jcwp_animation_speed", "fast", "", "yes");
add_option("jcwp_show_titles", "true", "", "yes");
add_option("jcwp_scroll_amount", "2", "", "yes");






function jcwp_admin_init() {
	if ( function_exists('register_setting') ) {
		register_setting('jcwp_settings', 'option-1', '');
	}
}
function add_jcwp_option_page() {
	global $wpdb;
	global $jcwp_plugin_name;

	add_options_page($jcwp_plugin_name . ' ' . __('Options', 'jcarousel-for-wordpress'), $jcwp_plugin_name, 8, basename(__FILE__), 'jcwp_options_page');
	
}
add_action('admin_init', 'jcwp_admin_init');
add_action('admin_menu', 'add_jcwp_option_page');



// Options function
function jcwp_options_page() {

	if (isset($_POST['info_update'])) {
			
		// Update options
		$jcwp_animation_speed = $_POST["jcwp_animation_speed"];
		update_option("jcwp_animation_speed", $jcwp_animation_speed);

		$jcwp_show_titles = $_POST["jcwp_show_titles"];
		update_option("jcwp_show_titles", $jcwp_show_titles);
		
		$jcwp_scroll_amount = $_POST["jcwp_scroll_amount"];
		update_option("jcwp_scroll_amount", $jcwp_scroll_amount);


		// Give an updated message
		echo "<div class='updated fade'><p><strong>" . __('Options updated', 'jcarousel-for-wordpress') . "</strong></p></div>";
		
	}

	// Show options page
	?>

		<div class="wrap">
		
			<div class="options">
		
				<form method="post" action="options-general.php?page=<?php global $jcwp_plugin_filename; echo $jcwp_plugin_filename; ?>">
			
				<h2><?php global $jcwp_plugin_name; printf(__('%s Settings', 'jcarousel-for-wordpress'), $jcwp_plugin_name); ?></h2>
	
					<h3><?php _e("Gallery Animation Speed", 'jcarousel-for-wordpress'); ?></h3>
					<input type="text" size="50" name="jcwp_animation_speed" id="jcwp_animation_speed" value="<?php echo stripslashes(get_option('jcwp_animation_speed')) ?>" />
					<br />
					
					<p class="setting-description"><?php _e("The speed of the animation. Options: <code>'fast'</code>, <code>'slow'</code>, or a number. 0 is instant, 10000 is very slow.", 'jcarousel-for-wordpress') ?></p>



					<h3><?php _e("Show Titles", 'jcarousel-for-wordpress'); ?></h3>
					<label>
					<?php
					echo "<input type='radio' ";
					echo "name='jcwp_show_titles' ";
					echo "id='jcwp_show_titles_0' ";
					echo "value='true' ";
					echo "true" == get_option('jcwp_show_titles') ? ' checked="checked"' : "";
					echo " />";
					?>
					<?php _e("Yes, show image titles.", 'jcarousel-for-wordpress'); ?>
					</label>
					<br />
					<label>
					<?php
					echo "<input type='radio' ";
					echo "name='jcwp_show_titles' ";
					echo "id='jcwp_show_titles_1' ";
					echo "value='false' ";
					echo "false" == get_option('jcwp_show_titles') ? ' checked="checked"' : "";
					echo " />";
					?>
					<?php _e("No, hide image titles. ", 'jcarousel-for-wordpress'); ?>
					</label>
					<br />
					
					<p class="setting-description"><?php _e("Should the title of each image be shown?", 'jcarousel-for-wordpress') ?></p>
					
                     <h3><?php _e("Scroll Amount", 'jcarousel-for-wordpress'); ?></h3>
					<input type="text" size="50" name="jcwp_scroll_amount" id="jcwp_scroll_amount" value="<?php echo stripslashes(get_option('jcwp_scroll_amount')) ?>" />
					<br />
					
					<p class="setting-description"><?php _e("How many items should the carousel scroll", 'jcarousel-for-wordpress') ?></p>
		
					<p class="submit">
						<?php if ( function_exists('settings_fields') ) settings_fields('jcwp_settings'); ?>
						<input type='submit' name='info_update' value='<?php _e('Save Changes', 'jcarousel-for-wordpress'); ?>' />
					</p>
				
				</form>
				
				
			</div><?php //.options ?>
			
		</div>

<?php
}












?>
