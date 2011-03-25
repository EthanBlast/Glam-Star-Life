<?php
/**
Plugin Name: Top Contributors
Version: 1.3.1
Plugin URI: http://justmyecho.com/2010/07/top-contributors-plugin-wordpress/
Description: Display your top commenters/authors in a widget. Make sure to backup any customizations to your "top-contributors/css/tooltip.css" before upgrading.
Author: Robin Dalton
Author URI: http://justmyecho.com
Changes:
	1.3.1 - Fixed a bug with widget caching
	1.3 - Option to show top Authors instead of commenters, plus few other new options. Fixed language localization.
	1.2 - Added integration for 'Add Local Avatar' plugin. Reformatted text to support plugin localization, + other fixes and additions.
	1.1 - Added Time limit options, fixed some formatting/style issues.
	1.0 - Initial release.
**/

define('JMETC_PLUGINPATH', WP_CONTENT_URL . '/plugins/'. plugin_basename(dirname(__FILE__)) . '/');
load_plugin_textdomain( 'jmetc', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

if(get_option('jmetc')) {
	$jmetc_options = get_option('jmetc');
}

function top_contributors_load_widget() {
	register_widget( 'Top_Contributors_Widget' );
}

class Top_Contributors_Widget extends WP_Widget {

	function Top_Contributors_Widget() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'jmetc', 'description' => __('Display Top Contributors.', 'jmetc') );

		/* Widget control settings. */
		$control_ops = array( 'width' => 250, 'height' => 350, 'id_base' => 'jmetc-widget' );

		/* Create the widget. */
		$this->WP_Widget( 'jmetc-widget', __('Top Contributors', 'jmetc'), $widget_ops, $control_ops );
	}

	function widget( $args, $instance ) {
		extract( $args );

		echo $before_widget;
		if($instance['title'] != '') {
			echo $before_title . $instance['title'] . $after_title;
		}
		jme_top_contributors();
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		/* Strip tags for title and name to remove HTML (important for text inputs). */
		foreach($new_instance as $key => $val) {
			$instance[$key] = strip_tags( $new_instance[$key] );
		}
		return $instance;
	}

	function form( $instance ) {
		
		/* Set up some default widget settings. */
		$defaults = array( 	'title' => __( '', 'jmetc' ) );
							
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'jmetc'); ?></label>
			<input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:225px;" />
		</p>
		<p><?php _e('Widget options can be found under Settings > Top Contributors.', 'jmetc'); ?></p>

	<?php
	}
}

function jme_tc_activate() {
	global $wpdb;
	$tcOptions = array(	'limit' => 10,
						'show_count' => 1,
						'show_avatar' => 1,
						'show_icon' => 0,
						'avatar_size' => 40,
						'exclude_author' => '',
						'format' => 1,
						'cache' => '',
						'toplist' => array(),
						'icon' => 'star.png',
						'time_limit_type' => 1,
						'time_limit_int' => 1,
						'time_limit_interval' => 3,
						'time_limit_this' => 2,
						'comment_limit' => 0,
						'list_type' => 0,
						'rel_links' => 0,
						'count_by' => 0
					);
	if (!get_option('jmetc')) {
		add_option('jmetc', $tcOptions);
	}
	@$wpdb->query("ALTER TABLE $wpdb->comments ADD INDEX `comment_author_email` ( `comment_author_email` )");
}
	
function jme_tc_deactivate() {
	global $wpdb;
	//delete_option('jmetc');
	@$wpdb->query("ALTER TABLE $wpdb->comments DROP INDEX `comment_author_email`");
}

function jmetc_refresh_comment_cache() {
	global $wpdb, $jmetc_options;
	
	if($jmetc_options['list_type'] == 0) {
	
		switch($jmetc_options['rel_links']) {
			case 1 : $rel = 'rel="nofollow" '; break;
			case 2 : $rel = 'rel="dofollow" '; break;
			default : $rel = '';
		}
			
		$cache = '<div class="top-contributors">'."\n";	
	
		$author_sql = jme_get_author_exclude(2,$jmetc_options['exclude_author']);
		$timeInterval = jme_get_time_interval(2,$jmetc_options);
		$groupby = ($jmetc_options['count_by'] == 0) ? 'comment_author_email' : 'comment_author';
		
		$query = "	SELECT 	COUNT(comment_ID) AS `comment_count`,
						comment_author,
						comment_author_email,
						comment_author_url
					FROM $wpdb->comments
					WHERE comment_approved = 1
					AND comment_type = ''
					$author_sql
					$timeInterval
					GROUP BY $groupby
					ORDER BY comment_count DESC
					LIMIT $jmetc_options[limit]
				";
				
		$gettc = $wpdb->get_results( $query );

		if($gettc) {
			
			// If format is List Style
			if($jmetc_options['format'] == 1) {
				
				foreach($gettc as $tc) {
				
					$cache .= '<div class="list">';
				
					if($tc->comment_author_url != '') {
						$username = '<a '.$rel.'href="' . $tc->comment_author_url . '">' . $tc->comment_author . '</a>';
					} else {
						$username = $tc->comment_author;
					}
				
					if($jmetc_options['show_avatar'] == 1) {
						$cache .= get_avatar($tc->comment_author_email, $jmetc_options['avatar_size']);
					}
				
					$cache .= '<div class="tc-user">' . $username . '</div>';
						if($jmetc_options['show_count'] == 1) {
							$cache .= '<div class="tc-count">';
							$cache .= ($tc->comment_count == 1) ? sprintf(__("%s comment", 'jmetc'), $tc->comment_count) :
								sprintf(__("%s comments", 'jmetc'), number_format($tc->comment_count));
							$cache .= '</div>';
						}
					$cache .= '<div style="clear:both;"></div></div>'."\n";
				}
			}
			
			// If format is Gallery Style
			if($jmetc_options['format'] == 2) {
				
				foreach($gettc as $tc) {

					$gavatar = get_avatar($tc->comment_author_email, $jmetc_options['avatar_size']);
					$cache .= '<div class="gallery">'."\n";
					
					$theinfo = 'title="<div class=\'tc-user\'>' . $tc->comment_author . '</div>';
						if($jmetc_options['show_count'] == 1) {
							$theinfo .= '<div class=\'tc-count\'>';
							$theinfo .= ($tc->comment_count == 1) ? sprintf(__("%s comment", 'jmetc'), $tc->comment_count) :
								sprintf(__("%s comments", 'jmetc'), number_format($tc->comment_count));
							$theinfo .= '</div>';
						}
					$theinfo .= '" ';
								
					$cache .= str_replace('<img','<img '.$theinfo,$gavatar);
					
					$cache .= "</div>\n";
				}
				$cache .= '<div style="clear:both;"></div>';
			}
				
			// create array of top users to add Star Icon next to in comments.
			if (($jmetc_options['show_icon'] == 1) && ($jmetc_options['comment_limit'] == 0)) {
				foreach($gettc as $tc) {
					$jmetc_options['toplist'][] = $tc->comment_author;
				}
			}
		} else {
			$cache .= __('No commenters found.', 'jmetc');
		}
		$cache .= "</div>\n";	

		// create array of top users to add Star Icon, if not generating commenter list above:
		if($jmetc_options['show_icon'] == 1 && $jmetc_options['comment_limit'] > 0) {
		
			$author_sql = jme_get_author_exclude(2,$jmetc_options['exclude_author']);
			$timeInterval = jme_get_time_interval(2,$jmetc_options);
			$groupby = ($jmetc_options['count_by'] == 0) ? 'comment_author_email' : 'comment_author';
			$jmetc_options['toplist'] = array();
		
			$query = "	SELECT 	COUNT(comment_ID) AS `comment_count`,
							comment_author
						FROM $wpdb->comments
						WHERE comment_approved = 1
						AND comment_type = ''
						$author_sql
						$timeInterval
						GROUP BY $groupby
						HAVING `comment_count` >= '$jmetc_options[comment_limit]'
					";
				
			$gettc = $wpdb->get_results( $query );
		
			if($gettc) {			
				foreach($gettc as $tc) {				
					$jmetc_options['toplist'][] = $tc->comment_author;
				}
			}
		}

		$jmetc_options['cache'] = $cache;

		update_option('jmetc', $jmetc_options);
	}
}

// If listing Top Post Authors //
function jmetc_refresh_author_cache() {
	global $wpdb, $jmetc_options;
	
	if($jmetc_options['list_type'] == 1) {
		
		switch($jmetc_options['rel_links']) {
			case 1 : $rel = 'rel="nofollow" '; break;
			case 2 : $rel = 'rel="dofollow" '; break;
			default : $rel = '';
		}
			
		$cache = '<div class="top-contributors">'."\n";	
		
		$author_sql = jme_get_author_exclude(1,$jmetc_options['exclude_author']);
		$timeInterval = jme_get_time_interval(1,$jmetc_options);
		
		$query = "	SELECT 	COUNT(a.ID) AS `post_count`,
						b.display_name,
						b.user_email,
						b.user_url
					FROM $wpdb->posts a
					LEFT JOIN $wpdb->users b
					ON a.post_author = b.ID
					WHERE a.post_status = 'publish'
					AND a.post_type = 'post'
					$author_sql
					$timeInterval
					GROUP BY b.ID
					ORDER BY post_count DESC
					LIMIT $jmetc_options[limit]
				";
				
		$gettc = $wpdb->get_results( $query );

		if($gettc) {
		
			if($jmetc_options['format'] == 1) {
				
				foreach($gettc as $tc) {
					$cache .= '<div class="list">';
				
					if($tc->user_url != '') {
						$username = '<a '.$rel.'href="' . $tc->user_url . '">' . $tc->display_name . '</a>';
					} else {
						$username = $tc->display_name;
					}
					
					if($jmetc_options['show_avatar'] == 1) {
						$cache .= get_avatar($tc->user_email, $jmetc_options['avatar_size']);
					}
				
					$cache .= '<div class="tc-user">' . $username . '</div>';
						if($jmetc_options['show_count'] == 1) {
							$cache .= '<div class="tc-count">';
							$cache .= ($tc->post_count == 1) ? sprintf(__("%s post", 'jmetc'), $tc->post_count) :
								sprintf(__("%s posts", 'jmetc'), number_format($tc->post_count));
							$cache .= '</div>';
						}
					$cache .= '<div style="clear:both;"></div></div>'."\n";
				}
			}
		
			if($jmetc_options['format'] == 2) {
				
				foreach($gettc as $tc) {
					$gavatar = get_avatar($tc->user_email, $jmetc_options['avatar_size']);
					$cache .= '<div class="gallery">'."\n";
					
					$theinfo = 'title="<div class=\'tc-user\'>' . $tc->display_name . '</div>';
					if($jmetc_options['show_count'] == 1) {
						$theinfo .= '<div class=\'tc-count\'>';
						$theinfo .= ($tc->post_count == 1) ? sprintf(__("%s post", 'jmetc'), $tc->post_count) :
							sprintf(__("%s posts", 'jmetc'), number_format($tc->post_count));
						$theinfo .= '</div>';
					}
					$theinfo .= '" ';
								
					$cache .= str_replace('<img','<img '.$theinfo,$gavatar);

					$cache .= "</div>\n";
				}
				$cache .= '<div style="clear:both;"></div>';
			}
		} else {
			$cache .= __('No authors found.', 'jmetc');
		}
		
		$cache .= "</div>\n";
	
		$jmetc_options['cache'] = $cache;

		update_option('jmetc', $jmetc_options);
	}
}

function jme_top_contributors() {
	global $jmetc_options;

	if($jmetc_options['cache'] == '') {
		if($jmetc_options['list_type'] == 0) {
			jmetc_refresh_comment_cache();
		} else if ($jmetc_options['list_type'] == 1) {
			jmetc_refresh_author_cache();
		}
	}
	echo $jmetc_options['cache'];
}

function jme_add_options_page() {
	add_options_page( __('Top Contributors', 'jmetc'), __('Top Contributors', 'jmetc'), 'edit_themes', basename(__FILE__), 'jme_the_options_page');
}
	
function jme_the_options_page() {
	global $jmetc_options;

	if($_POST['save_settings']) {
		$jmetc_options['cache'] = '';
		$jmetc_options['limit'] = $_POST['limit'];
		$jmetc_options['show_count'] = ($_POST['show_count'] == 1) ? 1 : 0;
		$jmetc_options['exclude_author'] = $_POST['exclude_author'];
		$jmetc_options['show_avatar'] = ($_POST['show_avatar'] == 1) ? 1 : 0;
		$jmetc_options['avatar_size'] = $_POST['avatar_size'];
		$jmetc_options['format'] = $_POST['format'];
		$jmetc_options['show_icon'] = ($_POST['show_icon'] == 1) ? 1 : 0;
		//$jmetc_options['avatar_rating'] = $_POST['avatar_rating']; // no longer used
		$jmetc_options['icon'] = $_POST['icon'];
		$jmetc_options['time_limit_type'] = $_POST['time_limit_type'];
		$jmetc_options['time_limit_int'] = $_POST['time_limit_int'];
		$jmetc_options['time_limit_interval'] = $_POST['time_limit_interval'];
		$jmetc_options['time_limit_this'] = $_POST['time_limit_this'];
		//$jmetc_options['use_local_avatars'] = ($_POST['use_local_avatars'] == 1) ? 1 : 0; // no longer used
		$jmetc_options['comment_limit'] = $_POST['comment_limit'];
		
		$jmetc_options['list_type'] = ($_POST['list_type'] == 1) ? 1 : 0;
		$jmetc_options['count_by'] = ($_POST['count_by'] == 1) ? 1 : 0;
		$jmetc_options['rel_links'] = ($_POST['rel_links']) ? $_POST['rel_links'] : 0;
		
		update_option('jmetc', $jmetc_options);
		if($jmetc_options['list_type'] == 0) {
			jmetc_refresh_comment_cache();
		} else if ($jmetc_options['list_type'] == 1) {
			jmetc_refresh_author_cache();
		}
		echo '<div id="message" class="updated fade"><p>Your options have been saved.</p></div>';
	}
	
	//$jmetc_options = get_option('jmetc');
	?>
<style type="text/css">
.wrap table.tbloptions td {padding:5px 0;}
.wrap table.tbloptions td.tblbreak {padding:20px 0 5px 0;}
h4 {margin:0;}
</style>
<div class="wrap">
<div id="poststuff">
	<form method="post" name="jme_options">
	
	<h2><?php _e('Top Contributors', 'jmetc'); ?></h2>
		<p><?php _e('Use the <i>Top Contributors Widget</i> to add the widget to sidebar, or paste this code into your template where you want the widget to display:', 'jmetc'); ?> <br />
	<code>&lt;?php if(function_exists('jme_top_contributors')) { jme_top_contributors(); } ?&gt;</code></p>
	<div class="postbox">
		<h3 class="hndle"><span><?php _e("Top Contributors Options",'jmetc'); ?></span></h3>
		<div class="inside">	

		<table class="tbloptions">	
			<tr>
				<td width="200"><?php _e("Show Top",'jmetc'); ?>:</td>
					<td><label><input type="radio" name="list_type" value="0"<?php if($jmetc_options['list_type'] == 0) echo ' checked="checked"'; ?>> <?php _e("Commenters", 'jmetc'); ?></label> &nbsp; &nbsp; 
					<label><input type="radio" name="list_type" value="1"<?php if($jmetc_options['list_type'] == 1) echo ' checked="checked"'; ?>> <?php _e("Authors",'jmetc'); ?></label></td>
	
				</td>
			</tr>
			<tr>
				<td><?php _e('Number of Contributors', 'jmetc'); ?>:</td>
				<td><label for="limit"><input style="width:50px;" type="text" id="limit" name="limit" value="<?php echo htmlentities($jmetc_options['limit']); ?>" /></label></td>
			</tr>
			
			<tr>
				<td><label for="show_count"><?php _e('Display Count', 'jmetc'); ?>:</td>
				<td><input type="checkbox" id="show_count" name="show_count" value="1"<?php if($jmetc_options['show_count'] == 1) echo ' checked="checked"'; ?> /></label></td>
			</tr>
			
			<tr><td colspan="2" class="tblbreak"><h4>Avatar Options:</h4></td></tr>
			
			<tr>
				<td><label for="show_avatar"><?php _e('Display User Avatar', 'jmetc'); ?>:</td>
				<td><input type="checkbox" id="show_avatar" name="show_avatar" value="1"<?php if($jmetc_options['show_avatar'] == 1) echo ' checked="checked"'; ?> /></label></td>
			</tr>
			
			<tr>
				<td><label for="avatar_size"><?php _e('Avatar Size', 'jmetc'); ?>:</td>
				<td><input style="width:50px;" type="text" id="avatar_size" name="avatar_size" value="<?php echo htmlentities($jmetc_options['avatar_size']); ?>" /></label> (pixels)</td>
			</tr>
			<!-- Gravatar Rating disabled - Uses setting in Settings > Discussion 
			<tr>
				<td><label for="avatar_rating"><?php _e('Avatar Rating', 'jmetc'); ?>:</label></td>
				<td><label><input type="radio" name="avatar_rating" value="g"<?php if($jmetc_options['avatar_rating'] == 'g') echo ' checked="checked"'; ?>> G</label> &nbsp;
					<label><input type="radio" name="avatar_rating" value="pg"<?php if($jmetc_options['avatar_rating'] == 'pg') echo ' checked="checked"'; ?>> PG</label> &nbsp;
					<label><input type="radio" name="avatar_rating" value="r"<?php if($jmetc_options['avatar_rating'] == 'r') echo ' checked="checked"'; ?>> R</label> &nbsp;
					<label><input type="radio" name="avatar_rating" value="x"<?php if($jmetc_options['avatar_rating'] == 'x') echo ' checked="checked"'; ?>> X</label> &nbsp;</td>
			</tr>
			// end gravatar rating option //-->
			<tr>
				<td colspan="2">*<?php _e('If using the plugin "Add Local Avatar", this plugin will automatically use local avatars in the list.', 'jmetc'); ?></td>
			</tr>
			
			<tr><td colspan="2" class="tblbreak"><h4><?php _e("Time Options", 'jmetc'); ?>:</h4></td></tr>
			
			<tr>
				<td valign="top"><?php _e("Show comments/posts from", 'jmetc'); ?>:</td>
				<td>
					<label for="time_limit_type1"><input type="radio" id="time_limit_type1" name="time_limit_type" value="1"<?php if($jmetc_options['time_limit_type'] == 1) echo ' checked="checked"'; ?>> <?php _e('All Time', 'jmetc'); ?></label>
					<br />
					<label for="time_limit_type2"><input type="radio" id="time_limit_type2" name="time_limit_type" value="2"<?php if($jmetc_options['time_limit_type'] == 2) echo ' checked="checked"'; ?>> <?php _e('The Last', 'jmetc'); ?> </label><input type="text" style="width:40px;" id="time_limit_int" name="time_limit_int" value="<?php echo $jmetc_options['time_limit_int']; ?>" /> <select id="time_limit_interval" name="time_limit_interval">
						<option value="1"<?php if($jmetc_options['time_limit_interval'] == 1) echo ' selected="selected"'; ?>><?php _e('day(s)', 'jmetc'); ?> </option>
						<option value="2"<?php if($jmetc_options['time_limit_interval'] == 2) echo ' selected="selected"'; ?>><?php _e('week(s)', 'jmetc'); ?> </option>
						<option value="3"<?php if($jmetc_options['time_limit_interval'] == 3) echo ' selected="selected"'; ?>><?php _e('month(s)', 'jmetc'); ?> </option>
						<option value="4"<?php if($jmetc_options['time_limit_interval'] == 4) echo ' selected="selected"'; ?>><?php _e('year(s)', 'jmetc'); ?> </option>
						</select>
						<br />
						<label for="time_limit_type3"><input type="radio" id="time_limit_type3" name="time_limit_type" value="3"<?php if($jmetc_options['time_limit_type'] == 3) echo ' checked="checked"'; ?>> <?php _e('Only This', 'jmetc'); ?> </label><select id="time_limit_this" name="time_limit_this">
						<option value="1"<?php if($jmetc_options['time_limit_this'] == 1) echo ' selected="selected"'; ?>><?php _e('week', 'jmetc'); ?> </option>
						<option value="2"<?php if($jmetc_options['time_limit_this'] == 2) echo ' selected="selected"'; ?>><?php _e('month', 'jmetc'); ?> </option>
						<option value="3"<?php if($jmetc_options['time_limit_this'] == 3) echo ' selected="selected"'; ?>><?php _e('year', 'jmetc'); ?> </option>
						</select>
				</td>			
			</tr>
		
			<tr><td colspan="2" class="tblbreak"></td></tr>
		
			<tr>
				<td valign="top"><h4><?php _e('Widget Format', 'jmetc'); ?>:</h4></td>
				<td>
				<div><div style="float:left;margin:0 20px 0 0;">
					<label for="format1"><input type="radio" id="format1" name="format" value="1"<?php if($jmetc_options['format'] == 1) echo ' checked="checked"'; ?> /> <?php _e('List Style', 'jmetc'); ?><br /><img src="<?php echo JMETC_PLUGINPATH; ?>images/list.png" /></label>
					</div>
					<div style="float:left;">
					<label for="format2"><input type="radio" id="format2" name="format" value="2"<?php if($jmetc_options['format'] == 2) echo ' checked="checked"'; ?> /> <?php _e('Gallery Style with tooltips', 'jmetc'); ?><br /><img src="<?php echo JMETC_PLUGINPATH; ?>images/gallery.png" /></label>
					</div>
					<div style="clear:both;"></div>
				</div>
				</td>
			</tr>		
		</table>
		
		</div>
	</div>

	<div class="postbox">
		<h3 class="hndle"><span><?php _e("Advanced Options",'jmetc'); ?></span></h3>
		<div class="inside">	

		<table class="tbloptions">	
			
			<tr>
				<td valign="top"><?php _e('Exclude Users by their Email Address (separate by comma)', 'jmetc'); ?>:</td>
				<td><textarea style="width:400px;height:50px;" id="exclude_author" name="exclude_author"><?php echo htmlspecialchars(stripslashes($jmetc_options['exclude_author'])); ?></textarea></td>
			</tr>
			<tr>
				<td width="200"><?php _e("Count Comments By", 'jmetc'); ?>:</td>
				<td><label><input type="radio" name="count_by" value="0"<?php if($jmetc_options['count_by'] == 0) echo ' checked="checked"'; ?>> <?php _e("User Email Address", 'jmetc'); ?></label> &nbsp; &nbsp; 
					<label><input type="radio" name="count_by" value="1"<?php if($jmetc_options['count_by'] == 1) echo ' checked="checked"'; ?>> <?php _e("Username",'jmetc'); ?></label>
					<div style="font-size:.8em;padding:3px 0 0 10px;"><?php _e('Use the Username option if users are not required to enter email address when commenting.','jmetc'); ?></div></td>
			</tr>
			<tr>
				<td><?php _e("Link Attribute Options", 'jmetc'); ?>:</td>
				<td><label><input type="radio" name="rel_links" value="0"<?php if($jmetc_options['rel_links'] == 0) echo ' checked="checked"'; ?>> <?php _e("None", 'jmetc'); ?></label> &nbsp; &nbsp; 
					<label><input type="radio" name="rel_links" value="1"<?php if($jmetc_options['rel_links'] == 1) echo ' checked="checked"'; ?>> rel='nofollow'</label> &nbsp; &nbsp; 
					<label><input type="radio" name="rel_links" value="2"<?php if($jmetc_options['rel_links'] == 2) echo ' checked="checked"'; ?>> rel='dofollow'</label>
				</td>
			</tr>
		</table>
		</div>
	</div>
		
	<div class="postbox">
		<h3 class="hndle"><span><?php _e("Top Contributor Icon Options",'jmetc'); ?></span></h3>
		<div class="inside">	

		<table class="tbloptions">	
			<tr>		

				<td width="200" valign="top"><?php _e('Top Contributor Icon', 'jmetc'); ?>:</td>	
				<td><label for="show_icon"><input type="checkbox" id="show_icon" name="show_icon" value="1"<?php if($jmetc_options['show_icon'] == 1) echo ' checked="checked"'; ?> />
					<?php _e('Show "Top Contributor Icon" next to Username in comments.', 'jmetc'); ?></label><br />
					<?php _e('This option gives your loyal blog followers and contributors some recognition by adding a little icon next to their name in all of their comments.', 'jmetc'); ?><br />
					<?php _e('By default this is a Star, however it can be changed to any Icon you want by uploading the new image to the plugin image directory <code>../plugins/top-contributors/images</code>.', 'jmetc'); ?>
				</td>
			</tr>
			<tr>
				<td><?php _e('Icon Image', 'jmetc'); ?>:</td>
				<td><label for="icon"><input style="width:150px;" type="text" id="icon" name="icon" value="<?php echo htmlentities($jmetc_options['icon']); ?>" /></label> <img src="<?php echo JMETC_PLUGINPATH; ?>images/<?php echo $jmetc_options['icon']; ?>" alt="" title="Top Contributor" /></td>
			</tr>
			
			<tr>
				<td valign="top"><?php _e('Comment Threshold', 'jmetc'); ?>:</td>
				<td><input style="width:50px;" type="text" id="comment_limit" name="comment_limit" value="<?php echo (is_numeric($jmetc_options['comment_limit'])) ? $jmetc_options['comment_limit'] : 0; ?>" />
					<br /><?php _e('Use Comment Threshold value to display Icon next to commenters that have X amount of comments or more. Setting to 0 will use default setting of top 10 commenters', 'jmetc'); ?>
				</td>
			</tr>
		</table>
		</div>
	</div>
		<p class="submit"><input type="submit" name="save_settings" value="<?php _e('Save Options', 'jmetc'); ?>" /></p>
	</form>
</div>
</div>
<?php
}

function jme_get_author_exclude($type,$the_authors) {
	$author_sql = '';
	if(trim($the_authors) != '') {
		$authorlist = array();
		$authors = explode(",",$the_authors);
		for($i=0;$i<count($authors);$i++) {
			if(trim($authors[$i]) != '') {
				$authorlist[] = strtolower(trim($authors[$i]));
			}
		}
		$al = implode("','",$authorlist);		
		if($al != '') {
			if($type == 1) {
				$author_sql = "AND LOWER(b.user_email) NOT IN('" . $al . "')";
			} else if($type == 2) {
				$author_sql = "AND LOWER(comment_author_email) NOT IN('" . $al . "')";
			}
		}
	}
	return $author_sql;	
}

function jme_get_time_interval($type,$options) {
	$timeInterval = '';
	$currenttime = time();
	if($options['time_limit_type'] == 2) {

		$basetime = 60 * 60 * 24; // 1 day of time
		
		if ($options['time_limit_interval'] == 1) {
			$time = $basetime; // last day
		} else if ($options['time_limit_interval'] == 2) {
			$time = $basetime * 7; // last week
		} else if ($options['time_limit_interval'] == 3) {
			$time = $basetime * 30; // last month
		} else if ($options['time_limit_interval'] == 4) {
			$time = $basetime * 365; // last year
		}
		
		$int = (is_numeric(trim($options['time_limit_int']))) ? trim($options['time_limit_int']) : 1;
		$time = $time * $int; // multiply by number of intervals
		
		$subTime = $currenttime - $time;
		$dateLimit = gmdate('Y-m-d H:i:s', $subTime);
		if($type == 1) {
			$timeInterval = "AND a.post_date_gmt > '" . $dateLimit . "'";
		} else if($type == 2) {
			$timeInterval = "AND comment_date_gmt > '" . $dateLimit . "'";
		}
	}
	if($options['time_limit_type'] == 3) {
		
		// define current day, month, year
		$currentDay = gmdate('j'); 
		$currentMonth = gmdate('n');
		$currentYear = gmdate('Y');
		
		// set current to the time
		$theDay = $currentDay;
		$theMonth = $currentMonth;
		$theYear = $currentYear;
			
		/* if this week, get the start of week */
		if($options['time_limit_this'] == 1) {
			// set array of how many days into the week, Sunday = 0
			$weekArray = array(	'Sunday' => 0, 'Monday' => 1, 'Tuesday' => 2, 'Wednesday' => 3, 'Thursday' => 4, 'Friday' => 5, 'Saturday' => 6 );
	
			$currentDayOfWeek = gmdate('l'); // get day of week
			
			// get starting day of week. Used this method to support PHP4, only PHP5 can get current day directly.
			$theDay = $theDay - $weekArray[$currentDayOfWeek];
		}
		/* if this month, set day = 1 */
		if($options['time_limit_this'] == 2) {
			$theDay = 1;
		}
		/* if this year, set day, month = 1 */
		if($options['time_limit_this'] == 3) {
			$theDay = 1;
			$theMonth = 1;
		}
			
		$subTime = mktime(0, 0, 0, $theMonth, $theDay, $theYear);
		$dateLimit = gmdate('Y-m-d H:i:s', $subTime);
		if($type == 1) {
			$timeInterval = "AND a.post_date_gmt > '" . $dateLimit . "'";
		} else if($type == 2) {
			$timeInterval = "AND comment_date_gmt > '" . $dateLimit . "'";
		}
	}
	return $timeInterval;	
}

function jme_top_contributors_header() {
	global $jmetc_options;
	//$jmetc_options = get_option('jmetc');
	if($jmetc_options['format'] == 2) {
		wp_enqueue_script( 'jqdim', JMETC_PLUGINPATH.'js/jquery.dimensions.js', array('jquery'), '' );
		wp_enqueue_script( 'jqtt', JMETC_PLUGINPATH.'js/jquery.tooltip.js', array('jquery'), '' );
	}
}
function jme_top_contributors_tooltip() {
	global $jmetc_options;
	//$jmetc_options = get_option('jmetc');
	if($jmetc_options['format'] == 2) {
		echo "<script type=\"text/javascript\">jQuery(document).ready(function($) { $('.top-contributors img').tooltip({delay:0,showURL:false,}); });</script>\n";
	}
	echo "<link rel=\"stylesheet\" href=\"" . JMETC_PLUGINPATH . "css/tooltip.css\" type=\"text/css\" />\n";
}

function jme_tc_icon($user) {
	global $jmetc_options;

	$string = $user;
	if($jmetc_options['show_icon'] == 1) {
		if(in_array(strip_tags($user), $jmetc_options['toplist'])) {
			$string = $user . ' <img class="tc-icon" src="' . JMETC_PLUGINPATH . 'images/' . $jmetc_options['icon'] . '" alt="" title="' . __('Top Contributor', 'jmetc') . '" />';
		}
	}
	return $string;
}

function jmetc_settings_link($links, $file) {
	static $this_plugin;
 
	if( !$this_plugin ) $this_plugin = plugin_basename(__FILE__);
 
	if( $file == $this_plugin ){
		$settings_link = '<a href="options-general.php?page='.dirname(plugin_basename(__FILE__)).'.php">' . __('Settings') . '</a>';
		$links = array_merge( array($settings_link), $links); // before other links
	}
	return $links;
}


add_action('admin_menu', 'jme_add_options_page');
add_action('widgets_init', 'top_contributors_load_widget');
add_action('init', 'jme_top_contributors_header');
add_action('wp_head', 'jme_top_contributors_tooltip');

add_filter('plugin_action_links', 'jmetc_settings_link', 10, 2);
add_filter('get_comment_author_link','jme_tc_icon');

add_action('delete_comment','jmetc_refresh_comment_cache');
add_action('wp_set_comment_status','jmetc_refresh_comment_cache');
add_action('comment_post','jmetc_refresh_comment_cache');

add_action('edit_post', 'jmetc_refresh_author_cache');
add_action('delete_post', 'jmetc_refresh_author_cache');
add_action('publish_post', 'jmetc_refresh_author_cache');



register_activation_hook( __FILE__, jme_tc_activate);
register_deactivation_hook( __FILE__, jme_tc_deactivate);
?>