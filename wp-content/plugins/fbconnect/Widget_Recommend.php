<?php
 class Widget_Recommend extends WP_Widget {
 	function Widget_Recommend() 
 	{
		$widget_ops = array('description' => __('Faceboook Recommendations Widget', 'sjworkspaces'));
		$this->WP_Widget('Recommend', __('Facebook Recommendations'), $widget_ops);
 		
 	}

 	function widget($args, $instance) {
		$title = esc_attr($instance['title']);		
		$width = esc_attr($instance['width']);	
		$height = esc_attr($instance['height']);	
		$bordercolor = esc_attr($instance['bordercolor']);	
		$site = esc_attr($instance['site']);	
		$showHeader = intval($instance['showHeader']);
		$recommendations= intval($instance['recommendations']);
		$colorscheme = esc_attr($instance['colorscheme']);	
	
		extract($args);
		$header="true";
		if (!$showHeader){
			$header="false";
		}
		$recommTxt="false";
	
		if ($recommendations){
			$recommTxt="true";
		}
		echo $before_widget.$before_title.$title.$after_title;
		echo '<fb:recommendations bordercolor="'.$bordercolor.'" colorscheme="'.$colorscheme.'" recommendations="'.$recommTxt.'" height="'.$height.'" width="'.$width.'" header="'.$header.'" site="'.$site.'"></fb:recommendations>';
		
		echo $after_widget;
 		
 	}
 	
	// When Widget Control Form Is Posted
	function update($new_instance, $old_instance) {
		if (!isset($new_instance['submit'])) {
			return false;
		}
		
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);		
		$instance['width'] = esc_attr($new_instance['width']);	
		$instance['height'] = esc_attr($new_instance['height']);	
		$instance['bordercolor'] = esc_attr($new_instance['bordercolor']);	
		$instance['site'] = esc_attr($new_instance['site']);	
		$instance['colorscheme'] = esc_attr($new_instance['colorscheme']);	
		$instance['showHeader'] = intval($new_instance['showHeader']);
		$instance['recommendations'] = intval($new_instance['recommendations']);
		
		return $instance;
	}
 	
 	// DIsplay Widget Control Form
	function form($instance) {
		global $wpdb;
		$instance = wp_parse_args((array) $instance, array('title' => __('Facebook Activity', 'fbconnect'), 'postid' => 12, 'showAll' => 1));
		$title = esc_attr($instance['title']);		
		$width = esc_attr($instance['width']);	
		$height = esc_attr($instance['height']);	
		$bordercolor = esc_attr($instance['bordercolor']);	
		$site = esc_attr($instance['site']);	
		$colorscheme = esc_attr($instance['colorscheme']);	
		$showHeader = intval($instance['showHeader']);
		$recommendations = intval($instance['recommendations']);

		if ($colorscheme==""){
			$colorscheme="White";
		}
		if ($bordercolor==""){
			$bordercolor="#94A3C4";
		}
		if ($site==""){
			$site=str_replace("http://","",get_option('siteurl'));
		}
		if (isset($showHeader) && $showHeader=="1")
			$checked_showHeader = "checked";
			
		if (isset($recommendations) && $recommendations=="1")
			$checked_recommendations = "checked";
		?>

		<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'fbconnect'); ?>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
		</label>
	 	<label for="<?php echo $this->get_field_id('site'); ?>"><?php _e('Site:', 'fbconnect'); ?>
				<input class="widefat" id="<?php echo $this->get_field_id('site'); ?>" name="<?php echo $this->get_field_name('site'); ?>" type="text" value="<?php echo $site; ?>" />
		</label>	 
		<label for="<?php echo $this->get_field_id('width'); ?>"><?php _e('Width:', 'fbconnect'); ?>
				<input class="widefat" id="<?php echo $this->get_field_id('width'); ?>" name="<?php echo $this->get_field_name('width'); ?>" type="text" value="<?php echo $width; ?>" />
		</label>
		<label for="<?php echo $this->get_field_id('height'); ?>"><?php _e('Height:', 'fbconnect'); ?>
				<input class="widefat" id="<?php echo $this->get_field_id('height'); ?>" name="<?php echo $this->get_field_name('height'); ?>" type="text" value="<?php echo $height; ?>" />
		</label>
		<label for="<?php echo $this->get_field_id('bordercolor'); ?>"><?php _e('Border color:', 'fbconnect'); ?>
				<input class="widefat" id="<?php echo $this->get_field_id('bordercolor'); ?>" name="<?php echo $this->get_field_name('bordercolor'); ?>" type="text" value="<?php echo $bordercolor; ?>" />
		</label>
		<label for="<?php echo $this->get_field_id('colorscheme'); ?>"><?php _e('colorscheme:', 'fbconnect'); ?>
				<input class="widefat" id="<?php echo $this->get_field_id('colorscheme'); ?>" name="<?php echo $this->get_field_name('colorscheme'); ?>" type="text" value="<?php echo $colorscheme; ?>" />
		</label>
		<label for="<?php echo $this->get_field_id('showHeader'); ?>"><?php _e('Show Header:', 'fbconnect'); ?>
			<input id="<?php echo $this->get_field_id('showHeader'); ?>" name="<?php echo $this->get_field_name('showHeader'); ?>" type="checkbox" value="1" <?php echo $checked_showHeader;?> />
		</label>
		<br/>
		<label for="<?php echo $this->get_field_id('recommendations'); ?>"><?php _e('Show Recommendations:', 'fbconnect'); ?>
			<input id="<?php echo $this->get_field_id('recommendations'); ?>" name="<?php echo $this->get_field_name('recommendations'); ?>" type="checkbox" value="1" <?php echo $checked_recommendations;?> />
		</label>
		<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
	<?php
	}	
	
 }

add_action('widgets_init', 'widget_Recommend_init');
function widget_Recommend_init() {
	register_widget('Widget_Recommend');
}
 
?>