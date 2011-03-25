<?php
/*
Plugin Name: Advanced Most Recent Posts Mod
Plugin URI: http://trepmal.com/plugins/advanced-most-recent-posts-mod/
Description: Display most recent posts from selected categories or current category or all posts with thumbnail images (optional).
Version: 1.4
Author: Yakup GÖVLER/Kailey Lampert
Author URI: http://kaileylampert.com
*/

class yg_recent_posts extends WP_Widget {

	function yg_recent_posts() {
		//Load Language
		load_plugin_textdomain( 'adv-recent-posts', false, dirname(plugin_basename(__FILE__)) .  '/lang' );
		$widget_ops = array('description' => __('Shows most recent posts. You can customize it easily.', 'adv-recent-posts') );
		//Create widget
		$this->WP_Widget('advancedrecentposts', __('Advanced Recent Posts', 'adv-recent-posts'), $widget_ops);
	}

	function widget($args, $instance) {

		extract($args, EXTR_SKIP);
		echo $before_widget;
		$title = empty($instance['title']) ? __('', 'adv-recent-posts') : apply_filters('widget_title', $instance['title']);
		$link = empty($instance['link']) ? '' : $instance['link'];
		$parameters = array(
				'title' => $title,
				'link' => $instance['link'],
				'separator' => $instance['separator'],
				'show_type' => $instance['show_type'],
				'limit' => (int) $instance['show-num'],
				'excerpt' => (int) $instance['excerpt-length'],
				'actcat' => (bool) $instance['actcat'],
				'cats' => esc_attr($instance['cats']),
				'cusfield' => esc_attr($instance['cus-field']),
				'w' => (int) $instance['width'],
				'h' => (int) $instance['height'],
				'firstimage' => (bool) $instance['firstimage'],
				'atimage' =>(bool) $instance['atimage'],
				'defimage' => esc_url($instance['defimage']),
				'showauthor' => (bool) $instance['showauthor'],
				'showtime' => (bool) $instance['showtime'],
				'format' => esc_attr($instance['format']),
				'spot' => esc_attr($instance['spot1']) ? esc_attr($instance['spot1']) : (esc_attr($instance['spot2']) ? esc_attr($instance['spot2']) : esc_attr($instance['spot1'])),
			);

		if ( !empty( $title ) &&  !empty( $link ) ) {
				echo $before_title . '<a href="' . $link . '">' . $title . '</a>' . $after_title;
		}
		else if ( !empty( $title ) ) {
			 echo $before_title . $title . $after_title;
		}
        //print recent posts
		echo yg_recentposts($parameters);
		echo $after_widget;

  } //end of widget()
	
	//Update widget options
  function update($new_instance, $old_instance) {

		$instance = $old_instance;
		//get old variables
		$instance['title'] = esc_attr($new_instance['title']);
		$instance['link'] = esc_attr($new_instance['link']);
		$instance['separator'] = $new_instance['separator'];
		$instance['show_type'] = $new_instance['show_type'];
		$instance['show-num'] = (int) abs($new_instance['show-num']);
		if ($instance['show-num'] > 20) $instance['show-num'] = 20;
		$instance['excerpt-length'] = (int) abs($new_instance['excerpt-length']);
		$instance['cats'] = esc_attr($new_instance['cats']);
		$instance['actcat'] = $new_instance['actcat'] ? 1 : 0;
		$instance['cus-field'] = esc_attr($new_instance['cus-field']);
		$instance['width'] = esc_attr($new_instance['width']);
		$instance['height'] = esc_attr($new_instance['height']);
		$instance['firstimage'] = $new_instance['first-image'] ? 1 : 0;
		$instance['atimage'] = $new_instance['atimage'] ? 1 : 0;
		$instance['defimage'] = esc_url($new_instance['def-image']);
		$instance['showauthor'] = $new_instance['showauthor'] ? 1 : 0;
		$instance['showtime'] = $new_instance['showtime'] ? 1 : 0;
		$instance['format'] = esc_attr($new_instance['format']);
		$instance['spot1'] = esc_attr($new_instance['spot1']);
		$instance['spot2'] = esc_attr($new_instance['spot2']);
		$instance['spot3'] = esc_attr($new_instance['spot3']);
		$instance['spot'] = $instance['spot1'] ? $instance['spot1'] : ($instance['spot2'] ? $instance['spot2'] : $instance['spot3']);
		return $instance;
 
	} //end of update()
	



	//Widget options form
  function form($instance) {

		$instance = wp_parse_args( (array) $instance, 
									array( 'title' => __('Recent Posts','adv-recent-posts'),'link' => __(get_bloginfo('home').'/blog/','adv-recent-posts'), 'separator' => __(': ','adv-recent-posts'), 'show_type' => 'post', 'show-num' => 10, 'excerpt-length' => 0, 'actcat' => 0, 'cats' => '', 'cus-field' => '', 'width' => '', 'height' => '', 'firstimage' => 0, 'show-time' => 0, 'atimage' => 0,'defimage'=>'','format'=>'m/d/Y', 'spot1'=>'spot1', 'spot2'=>'', 'spot3'=>'' ) 
								);
		
		$title = esc_attr($instance['title']);
		$link = esc_attr($instance['link']);
		$separator = $instance['separator'];
		$show_type = $instance['show_type'];
		$show_num = (int) $instance['show-num'];
		$excerpt_length = (int) $instance['excerpt-length'];
		$cats = esc_attr($instance['cats']);
		$actcat = (bool) $instance['actcat'];
		$cus_field = esc_attr($instance['cus-field']);
		$width = esc_attr($instance['width']);
		$height = esc_attr($instance['height']);
		$firstimage = (bool) $instance['firstimage'];
		$atimage = (bool) $instance['atimage'];
		$defimage = esc_url($instance['defimage']);
		$showauthor = (bool) $instance['showauthor'];
		$showtime = (bool) $instance['showtime'];
		$format = esc_attr($instance['format']);
		$spot1 = esc_attr($instance['spot1']);
		$spot2 = esc_attr($instance['spot2']);
		$spot3 = esc_attr($instance['spot3']);

		$spot = $spot1 ? $spot1 : ($spot2 ? $spot2 : $spot3);
		
		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:');?> 
				<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
			</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('link'); ?>"><?php _e('Title Link:');?> 
				<input class="widefat" id="<?php echo $this->get_field_id('link'); ?>" name="<?php echo $this->get_field_name('link'); ?>" type="text" value="<?php echo $link; ?>" />
			</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('separator'); ?>"><?php _e('Separator:');?> 
				<input class="widefat" id="<?php echo $this->get_field_id('separator'); ?>" name="<?php echo $this->get_field_name('separator'); ?>" type="text" value="<?php echo $separator; ?>" />
			</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('show_type'); ?>"><?php _e('Show:');?> 
				<select class="widefat" id="<?php echo $this->get_field_id('show_type'); ?>" name="<?php echo $this->get_field_name('show_type'); ?>">
				<?php
					global $wp_post_types;
					foreach($wp_post_types as $k=>$pt) {
						if($pt->exclude_from_search) continue;
						echo '<option value="' . $k . '"' . selected($k,$show_type,true) . '>' . $pt->labels->name . '</option>';
					}
				?>
				</select>
			</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('show-num'); ?>"><?php _e('Number of posts to show:');?> 
				<input id="<?php echo $this->get_field_id('show-num'); ?>" name="<?php echo $this->get_field_name('show-num'); ?>" type="text" value="<?php echo $show_num; ?>" size ="3" /><br />
				<small><?php _e('(at most 20)','adv-recent-posts'); ?></small>
			</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('excerpt-length'); ?>"><?php _e('Excerpt length (letters):', 'adv-recent-posts');?> 
				<input id="<?php echo $this->get_field_id('excerpt-length'); ?>" name="<?php echo $this->get_field_name('excerpt-length'); ?>" type="text" value="<?php echo $excerpt_length; ?>" size ="3" /><br />
				<small>(<?php _e('0 - Don\'t show excerpt', 'adv-recent-posts');?>)</small>
			</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('cus-field'); ?>"><?php _e('Thumbnail Custom Field Name:', 'adv-recent-posts');?> 
				<input id="<?php echo $this->get_field_id('cus-field'); ?>" name="<?php echo $this->get_field_name('cus-field'); ?>" type="text" value="<?php echo $cus_field; ?>" size ="20" /> 
			</label><br />
		 	<label for="<?php echo $this->get_field_id('width'); ?>"><?php _e('Width:', 'adv-recent-posts');?> <input id="<?php echo $this->get_field_id('width'); ?>" name="<?php echo $this->get_field_name('width'); ?>" type="text" value="<?php echo $width; ?>" size ="3" /></label>px<br />
			<label for="<?php echo $this->get_field_id('height'); ?>"><?php _e('Height:', 'adv-recent-posts');?> <input id="<?php echo $this->get_field_id('height'); ?>" name="<?php echo $this->get_field_name('height'); ?>" type="text" value="<?php echo $height; ?>" size ="3" /></label>px
		</p>
		<p>
			<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('first-image'); ?>" name="<?php echo $this->get_field_name('first-image'); ?>"<?php checked( $firstimage ); ?> />
			<label for="<?php echo $this->get_field_id('first-image'); ?>"><?php _e('Get first image of post', 'adv-recent-posts');?></label>
		</p>
		<p>
			<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('atimage'); ?>" name="<?php echo $this->get_field_name('atimage'); ?>"<?php checked( $atimage ); ?> />
			<label for="<?php echo $this->get_field_id('atimage'); ?>"><?php _e('Get first attached image of post', 'adv-recent-posts');?></label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('def-image'); ?>"><?php _e('Default image:', 'adv-recent-posts');?> 
				<input class="widefat" id="<?php echo $this->get_field_id('def-image'); ?>" name="<?php echo $this->get_field_name('def-image'); ?>" type="text" value="<?php echo $defimage; ?>" /><br />
				<small>(<?php _e('if there is no thumbnail, use this', 'adv-recent-posts');?></small>
			</label>
		</p>	
		<p>
			<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('showauthor'); ?>" name="<?php echo $this->get_field_name('showauthor'); ?>"<?php checked( $showauthor ); ?> />
			<label for="<?php echo $this->get_field_id('showauthor'); ?>"><?php _e('Show Author', 'adv-recent-posts');?></label>
		</p>
		<p>
			<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('showtime'); ?>" name="<?php echo $this->get_field_name('showtime'); ?>"<?php checked( $showtime ); ?> />
			<label for="<?php echo $this->get_field_id('showtime'); ?>"><?php _e('Show Post Timestamp', 'adv-recent-posts');?></label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('format'); ?>"><?php _e('Time format:', 'adv-recent-posts');?> 
				<input class="widefat" id="<?php echo $this->get_field_id('format'); ?>" name="<?php echo $this->get_field_name('format'); ?>" type="text" value="<?php echo $format; ?>" /><br />
				<small>(<?php _e('<a href="http://www.php.net/manual/en/function.date.php">PHP style</a> - leave as default unless you know what you\'re doing.', 'adv-recent-posts');?>)</small>
			</label>
		</p>
		<p>
			<label>Put time</label><br />
			<input type="radio" class="checkbox" id="<?php echo $this->get_field_id('spot1'); ?>" name="<?php echo $this->get_field_name('spot1'); ?>" value="spot1" <?php checked( $spot, 'spot1' ); ?> />
			<label for="<?php echo $this->get_field_id('spot1'); ?>"><?php _e('Before Title', 'adv-recent-posts');?></label>

			<input type="radio" class="checkbox" id="<?php echo $this->get_field_id('spot2'); ?>" name="<?php echo $this->get_field_name('spot1'); ?>" value="spot2" <?php checked( $spot, 'spot2' ); ?> />
			<label for="<?php echo $this->get_field_id('spot2'); ?>"><?php _e('After Title', 'adv-recent-posts');?> </label>

			<input type="radio" class="checkbox" id="<?php echo $this->get_field_id('spot3'); ?>" name="<?php echo $this->get_field_name('spot1'); ?>" value="spot3" <?php checked( $spot, 'spot3' ); ?> />
			<label for="<?php echo $this->get_field_id('spot3'); ?>"><?php _e('After Separator', 'adv-recent-posts');?> </label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('cats'); ?>"><?php _e('Categories:', 'adv-recent-posts');?> 
				<input class="widefat" id="<?php echo $this->get_field_id('cats'); ?>" name="<?php echo $this->get_field_name('cats'); ?>" type="text" value="<?php echo $cats; ?>" /><br />
				<small>(<?php _e('Category IDs, separated by commas.', 'adv-recent-posts');?>)</small>
			</label>
		</p>
		<p>
			<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('actcat'); ?>" name="<?php echo $this->get_field_name('actcat'); ?>"<?php checked( $actcat ); ?> />
			<label for="<?php echo $this->get_field_id('actcat'); ?>"> <?php _e('Get posts from current category', 'adv-recent-posts');?></label>
		</p>
		<?php
	} //end of form
}

add_action( 'widgets_init', create_function('', 'return register_widget("yg_recent_posts");') );
//Register Widget

// Show recent posts function

add_shortcode( 'amrp' , 'yg_recentposts_sc');
function yg_recentposts_sc( $atts ) {

	$defaults = array('separator' => ': ','show_type' => 'post', 'limit' => 10, 'excerpt' => 0, 'actcat' => 0, 'cats'=>'', 'cusfield' =>'', 'w' => 48, 'h' => 48, 'firstimage' => 0, 'showauthor' => 0, 'showtime' => 0, 'atimage' => 0, 'defimage' => '', 'format' => 'm/d/Y', 'spot' => 'spot1');
	$args = shortcode_atts($defaults, $atts);
	return yg_recentposts( $args, false );
	
}
	

function yg_recentposts($args = '', $echo = true) {
	global $wpdb;
	$defaults = array('separator' => ': ','show_type' => 'post', 'limit' => 10, 'excerpt' => 0, 'actcat' => 0, 'cats'=>'', 'cusfield' =>'', 'w' => 48, 'h' => 48, 'firstimage' => 0, 'showauthor' => 0, 'showtime' => 0, 'atimage' => 0, 'defimage' => '', 'format' => 'm/d/Y', 'spot' => 'spot1');
	$args = wp_parse_args( $args, $defaults );
	extract($args);
	
	$separator = $separator;
	$show_type = $show_type;

	$limit = (int) abs($limit);
	$firstimage = (bool) $firstimage;
	$showauthor = (bool) $showauthor;
	$showtime = (bool) $showtime;

	$spot = esc_attr($spot);

	$atimage = (bool) $atimage;
	$defimage = esc_url($defimage);
	$format = esc_attr($format);
	$w = (int) $w;
	$h = (int) $h;
	
	$excerptlength = (int) abs($excerpt);
	$excerpt = '';
	$cats = str_replace(" ", "", esc_attr($cats));
	if (($limit < 1 ) || ($limit > 20)) $limit = 10;
	
	/*$postlist = wp_cache_get('yg_recent_posts'); //Not yet
	if ( false === $postlist ) {
	*/
		if (($actcat) && (is_category())) {
			$cats = get_query_var('cat');
		}
		if (($actcat) && (is_single())) {
			$cats = '';
			foreach (get_the_category() as $catt) {
				$cats .= $catt->cat_ID.' '; 
			}
			$cats = str_replace(" ", ",", trim($cats));
		}
		
		if (!intval($cats)) $cats='';
		$query = "cat=$cats&showposts=$limit&post_type=$show_type";
		$posts = get_posts($query); //get posts
		$postlist = '';
		$height = $h ? ' height = "' . $h .'"' : '';
		$width = $w ? ' width = "' . $w . '"' : '';	
	 
		foreach ($posts as $post) {
			if ($showtime) { $time = ' '. date($format,strtotime($post->post_date)); } 
			$post_title = stripslashes($post->post_title);
			if ($excerptlength) {
				$excerpt = $post->post_excerpt;
				if ( '' == $excerpt ) {
					$text = $post->post_content;
					$text = strip_shortcodes( $text );
					$text = str_replace(']]>', ']]&gt;', $text);
					$text = strip_tags($text);
					$excerpt_length = 100;
					$words = explode(' ', $text, $excerpt_length + 1);
					if (count($words) > $excerpt_length) {
						array_pop($words);
						$text = implode(' ', $words);
					}
					$excerpt = $text;
				}
				
				if(strlen($excerpt) > $excerptlength) {
					$excerpt = mb_substr($excerpt, 0, $excerptlength) . '...';
				}
				$excerpt = $separator . ($spot == 'spot3' ? '<span class="date">'.$time.'</span> ' : '') . $excerpt;
			}
			$image = '';
			$img = '';
			if ($cusfield) {
				$cusfield = esc_attr($cusfield);
				$img = get_post_meta($post->ID, $cusfield, true);
			}
	
			if (!$img && $firstimage) {
				$match_count = preg_match_all("/<img[^']*?src=\"([^']*?)\"[^']*?>/", $post->post_content, $match_array, PREG_PATTERN_ORDER);		
				$img = count($match_array['1']) > 0 ? $match_array[1][0] : false;
			}
			if (!$img && $atimage) {
				$p = array(
					'post_type' => 'attachment',
					'post_mime_type' => 'image',
					'numberposts' => 1,
					'order' => 'ASC',
					'orderby' => 'menu_order ID',
					'post_status' => null,
					'post_parent' => $post->ID
				 );
				$attachments = get_posts($p);
				if ($attachments) {
					$imgsrc = wp_get_attachment_image_src($attachments[0]->ID, 'thumbnail');
					$img = $imgsrc[0];
				}			 
			 }
				 
			if (!$img && $defimage)
				$img = $defimage;
				 
			if ($img)
				$image = '<img src="' . $img . '" title="' . $post_title . '"' . $width . $height . ' />';
	   
			// $postlist .= '<li>'.($spot == 'spot1' ? '<span class="date">'.$time.'</span> ' : '').'<a href="' . get_permalink($post->ID) . '" title="'. $post_title .'"'. $image .'</br>'. strtoupper($post_title).'</a>' . ($showauthor ? ' by '.get_the_author($post->post_author) : '') . '' . ($spot == 'spot2' ? ' <span class="date">'.$time.'</span>' : '') . $excerpt . "&nbsp;</p></li>";
			
			$postlist .= '<li><a href="' . get_permalink($post->ID) . '" style="newslink" title="'. $post_title .'">'. $image . '</a><a href="' . get_permalink($post->ID) . '" style="newslink" title="'. $post_title .'">'. strtoupper($post_title).'</a></li>';
			
			
	
		}// end foreach()
		
		/*
		wp_cache_set('yg_recent_posts', $postlist);
	}*/

	if ($echo)
		echo '<a href="http://www.glamstarlife.com/news" align="right" style="text-decoration: none; text-align: right;"><span align="right" style="padding: 7px;">GlamStarLife</span><span align="right" style="color: #00CCCC;">News</span></a><div class="header"><ul>' . $postlist . '</ul></div>';
	else
		return '<ul>' . $postlist . '</ul>';
}
?>