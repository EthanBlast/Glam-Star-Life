<?php
class SearchWidget extends WP_Widget {
    function SearchWidget() {
         $widget_ops = array('classname' => 'widget_headway_search', 'description' => __( 'Simple search form.  Use this Widget instead of the other search widget.', 'headway') );
		 $this->WP_Widget('search', __('Headway Search', 'headway'), $widget_ops);
    }

    function widget($args, $instance) {		
        extract( $args );
		$search_input_text = empty( $instance['title'] ) ? __('Type Here To Search, Then Press Enter', 'headway') : $instance['title'];
        ?>
              <?php echo $before_widget ?>
				<form id="searchform" method="get" action="<?php bloginfo('home') ?>">
					<div>
						<input id="s" class="text-input" name="s" type="text" value="<?php echo (get_search_query() == NULL) ? $search_input_text : get_search_query(); ?>" onblur="if(this.value == '') {this.value = '<?php echo $search_input_text ?>';}" onclick="if(this.value == '<?php echo $search_input_text ?>') {this.value = '';}" accesskey="S" />
					</div>
				</form>
			<?php echo $after_widget ?>
        <?php
    }
    function update($new_instance, $old_instance) {				
        return $new_instance;
    }

    function form($instance) {		
	    $title = isset($instance['title']) ? esc_attr($instance['title']) : false; 
?>
			<p><label for="<?php echo $this->get_field_id('title'); ?>">Search Text: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
			
      
	
			


        <?php 
    }

}
add_action('widgets_init', create_function('', 'return register_widget("SearchWidget");'));
////////////////////
////////////////////
class SubscribeWidget extends WP_Widget {
    function SubscribeWidget() {
       	 $widget_ops = array('classname' => 'widget_headway_subscribe', 'description' => __( 'Displays subscribe via RSS and e-mail links.', 'headway') );
		 $this->WP_Widget('subscribe', __('Subscribe', 'headway'), $widget_ops);
    }

    function widget($args, $instance) {		
        extract( $args );
		$email_url = $instance['email-url'];
        ?>
              <?php echo $before_widget ?>
				<?php echo $before_title ?>Subscribe<?php echo $after_title ?>

				<ul class="subscribe">
					<li class="rss"><a href="<?php echo headway_rss() ?>"><?php _e('Subscribe via RSS', 'headway'); ?></a></li>
					<?php if($email_url){ ?>
					<li class="email"><a href="<?php echo $email_url ?>" target="_blank"><?php _e('Subscribe via E-Mail', 'headway'); ?></a></li>
					<?php } ?>
				</ul>

			<?php echo $after_widget ?>
        <?php
    }

    function update($new_instance, $old_instance) {				
        return $new_instance;
    }

    function form($instance) {		
	    $email_url = esc_attr($instance['email-url']); 
?>
			<p><label for="<?php echo $this->get_field_id('email-url'); ?>">Subscribe by E-Mail URL: <input class="widefat" id="<?php echo $this->get_field_id('email-url'); ?>" name="<?php echo $this->get_field_name('email-url'); ?>" type="text" value="<?php echo $email_url; ?>" /></label></p>


        <?php 
    }


}
add_action('widgets_init', create_function('', 'return register_widget("SubscribeWidget");'));
///////////////////
///////////////////
class TwitterWidget extends WP_Widget {
    function TwitterWidget() {
        $widget_ops = array('classname' => 'widget_headway_twitter', 'description' => __( 'Displays any number of your Twitter updates', 'headway') );
		$this->WP_Widget('twitter', __('Twitter', 'headway'), $widget_ops);
    }

    function widget($args, $instance) {		
		if(!function_exists('headway_get_twitter_updates')) include HEADWAYRESOURCES.'/twitter.php';
	
        extract( $args );
		$title = apply_filters('widget_title', empty( $instance['title'] ) ? 'Twitter' : $instance['title']);
        ?>
              <?php echo $before_widget; ?>
                  <?php echo $before_title
                      . $instance['title']
                      . $after_title; ?>

						<?php
						if($instance['format'] == '1') $instance['format'] = 'F j, Y - g:i A';
						if($instance['format'] == '2') $instance['format'] = 'm/d/y - g:i A';
						if($instance['format'] == '3') $instance['format'] = 'd/m/y - g:i A';
						if($instance['format'] == '4') $instance['format'] = 'g:i A - M j';
						if($instance['format'] == '5') $instance['format'] = 'g:i A - M j, Y';	
						?>

						<ul class="twitter-updates">
						<?php headway_get_twitter_updates($instance['username'], $instance['limit'], $instance['format']) ?>
						</ul>


              <?php echo $after_widget; ?>
        <?php
    }
    function update($new_instance, $old_instance) {				
        return $new_instance;
    }

    function form($instance) {				
        $title = esc_attr($instance['title']);
		$username = esc_attr($instance['username']);
		$limit = esc_attr($instance['limit']);
		        ?>
            <p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
	
			<p><label for="<?php echo $this->get_field_id('username'); ?>">Twitter Username: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('username'); ?>" type="text" value="<?php echo $username; ?>" /></label></p>
			<p><label for="<?php echo $this->get_field_id('limit'); ?>">Tweet Limit: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('limit'); ?>" type="text" value="<?php echo $limit; ?>" /></label></p>
			
			<p><label for="<?php echo $this->get_field_id('format'); ?>">Date/Time Format: 
			<select name="<?php echo $this->get_field_name('format'); ?>" id="<?php echo $this->get_field_id('format'); ?>">
				<option value="1"<?php selected( $instance['format'], '1' ); ?>>January 1, 2009 - 12:00 AM</option>
				<option value="2"<?php selected( $instance['format'], '2' ); ?>>MM/DD/YY - 12:00 AM</option>
				<option value="3"<?php selected( $instance['format'], '3' ); ?>>DD/MM/YY - 12:00 AM</option>
				<option value="4"<?php selected( $instance['format'], '4' ); ?>>12:00 AM - Jan 1</option>
				<option value="5"<?php selected( $instance['format'], '5' ); ?>>12:00 AM - Jan 1, 2009</option>
			</select>
			</label></p>
			


        <?php 
    }

}
add_action('widgets_init', create_function('', 'return register_widget("TwitterWidget");'));
///////////////////
///////////////////
class SocialWidget extends WP_Widget {
    function SocialWidget() {
        parent::WP_Widget(false, $name = 'Social Widget');	
    }

    function widget($args, $instance) {		
        extract( $args );
        ?>
              <?php echo $before_widget; ?>
                  <?php if($instance['title']) echo $before_title
                      . $instance['title']
                      . $after_title; ?>

						<?php if($instance['feed']): ?><a href="<?php echo $instance['feed'] ?>" title="Subscribe via RSS!" target="_blank"><img src="<?php bloginfo('template_directory') ?>/media/images/social/feed.png" alt="Subscribe via RSS!" /></a><?php endif; ?>

						<?php if($instance['twitter']): ?><a href="<?php echo $instance['twitter'] ?>" title="Follow Me On Twitter!" target="_blank"><img src="<?php bloginfo('template_directory') ?>/media/images/social/twitter.png" alt="Follow Me On Twitter!" /></a><?php endif; ?>

						<?php if($instance['facebook']): ?><a href="<?php echo $instance['facebook'] ?>" title="Be my friend on Facebook!" target="_blank"><img src="<?php bloginfo('template_directory') ?>/media/images/social/facebook.png" alt="Follow Me On Twitter!" /></a><?php endif; ?>

						<?php if($instance['linkedin']): ?><a href="<?php echo $instance['linkedin'] ?>" title="Find me on LinkedIn!" target="_blank"><img src="<?php bloginfo('template_directory') ?>/media/images/social/linkedin.png" alt="Follow Me On Twitter!" /></a><?php endif; ?>

						<?php if($instance['youtube']): ?><a href="<?php echo $instance['youtube'] ?>" title="Subscribe to my channel on YouTube!" target="_blank"><img src="<?php bloginfo('template_directory') ?>/media/images/social/youtube.png" alt="Follow Me On Twitter!" /></a><?php endif; ?>

						<?php if($instance['vimeo']): ?><a href="<?php echo $instance['vimeo'] ?>" title="Subscribe to my channel on Vimeo!" target="_blank"><img src="<?php bloginfo('template_directory') ?>/media/images/social/vimeo.png" alt="Follow Me On Twitter!" /></a><?php endif; ?>

						<?php if($instance['stumbleupon']): ?><a href="<?php echo $instance['stumbleupon'] ?>" target="_blank"><img src="<?php bloginfo('template_directory') ?>/media/images/social/stumbleupon.png" alt="" /></a><?php endif; ?>

						<?php if($instance['friendfeed']): ?><a href="<?php echo $instance['friendfeed'] ?>" title="Subscribe to my FriendFeed!" target="_blank"><img src="<?php bloginfo('template_directory') ?>/media/images/social/friendfeed.png" alt="Follow Me On Twitter!" /></a><?php endif; ?>


              <?php echo $after_widget; ?>
        <?php
    }
    function update($new_instance, $old_instance) {				
        return $new_instance;
    }

    function form($instance) {				
        $title = esc_attr($instance['title']);

		$feed = esc_attr($instance['feed']);
		$twitter = esc_attr($instance['twitter']);
		$facebook = esc_attr($instance['facebook']);
		$linkedin = esc_attr($instance['linkedin']);
		$youtube = esc_attr($instance['youtube']);
		$vimeo = esc_attr($instance['vimeo']);
		$stumbleupon = esc_attr($instance['stumbleupon']);
		$friendfeed = esc_attr($instance['friendfeed']);
		?>
            <p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
	
			<p><label for="<?php echo $this->get_field_id('feed'); ?>">Feed: <input class="widefat" id="<?php echo $this->get_field_id('feed'); ?>" name="<?php echo $this->get_field_name('feed'); ?>" type="text" value="<?php echo $feed; ?>" /></label></p>
			<p><label for="<?php echo $this->get_field_id('twitter'); ?>">Twitter: <input class="widefat" id="<?php echo $this->get_field_id('twitter'); ?>" name="<?php echo $this->get_field_name('twitter'); ?>" type="text" value="<?php echo $twitter; ?>" /></label></p>
			<p><label for="<?php echo $this->get_field_id('facebook'); ?>">Facebook: <input class="widefat" id="<?php echo $this->get_field_id('facebook'); ?>" name="<?php echo $this->get_field_name('facebook'); ?>" type="text" value="<?php echo $facebook; ?>" /></label></p>
			<p><label for="<?php echo $this->get_field_id('linkedin'); ?>">LinkedIn: <input class="widefat" id="<?php echo $this->get_field_id('linkedin'); ?>" name="<?php echo $this->get_field_name('linkedin'); ?>" type="text" value="<?php echo $linkedin; ?>" /></label></p>
			<p><label for="<?php echo $this->get_field_id('youtube'); ?>">YouTube: <input class="widefat" id="<?php echo $this->get_field_id('youtube'); ?>" name="<?php echo $this->get_field_name('youtube'); ?>" type="text" value="<?php echo $youtube; ?>" /></label></p>
			<p><label for="<?php echo $this->get_field_id('vimeo'); ?>">Vimeo: <input class="widefat" id="<?php echo $this->get_field_id('vimeo'); ?>" name="<?php echo $this->get_field_name('vimeo'); ?>" type="text" value="<?php echo $vimeo; ?>" /></label></p>
			<p><label for="<?php echo $this->get_field_id('stumbleupon'); ?>">StumbleUpon: <input class="widefat" id="<?php echo $this->get_field_id('stumbleupon'); ?>" name="<?php echo $this->get_field_name('stumbleupon'); ?>" type="text" value="<?php echo $stumbleupon; ?>" /></label></p>
			<p><label for="<?php echo $this->get_field_id('friendfeed'); ?>">FriendFeed: <input class="widefat" id="<?php echo $this->get_field_id('friendfeed'); ?>" name="<?php echo $this->get_field_name('friendfeed'); ?>" type="text" value="<?php echo $friendfeed; ?>" /></label></p>


        <?php 
    }

}
add_action('widgets_init', create_function('', 'return register_widget("SocialWidget");'));




function headway_widgets_init() {
	if(!function_exists('register_sidebars')){
		return false;
	} else {
		$leafs = headway_get_all_leafs('sidebar');

		if(count($leafs) > 0){												    	
			foreach($leafs as $leaf){ 													// Start foreach loop for every leaf/box.
				$leaf = array_map('maybe_unserialize', $leaf);

				$leaf_config = $leaf['config'];
				$leaf_options = $leaf['options'];

				if(isset($leaf_options['duplicate-id']) && $leaf_options['duplicate-id'] == true) continue;
					
				$widget_title[$leaf['id']] = (isset($leaf_options['sidebar-name'])) ? $leaf_options['sidebar-name'].' &mdash; '.'ID: '.$leaf['id'] : base64_decode($leaf_config['title']).' &mdash; ID: '.$leaf['id'];

				$sidebar = array(
					'name'			 =>   $widget_title[$leaf['id']],
					'id' 			 =>   'sidebar-'.$leaf['id'],
					'before_widget'  =>   '<li id="%1$s" class="widget %2$s">',
					'after_widget'   =>   '</li>'."\n",
					'before_title'   =>   '<span class="widget-title">',
					'after_title'    =>   "</span>\n",
				);
				
				register_sidebar($sidebar);
			}
		}
	}
}
add_action('init', 'headway_widgets_init');