<?php
/*
Plugin Name: Datafeedr Random Ads V2
Plugin URI: http://www.datafeedr.com/random-ads-plugin-v2.php
Description: The Datafeedr Random Ads plugin is a free plugin which allows you to simply and easily show random ads anywhere in your template files or using widgets. Go to Tools > Datafeedr Random Ads to begin using this plugin.
Version: 2.0
Author: Datafeedr.com
Author URI: http://www.datafeedr.com/
*/

// Add link to options page in Settings menu
add_action('admin_menu', 'dfrads_menu');
function dfrads_menu() {
  add_management_page('Datafeedr Random Ad Options', 'Datafeedr Random Ads', 8, 'datafeedr-ads', 'dfrads_options');
}

// Add "Settings" link on plugin page
add_action( 'plugin_action_links_'.basename( dirname( __FILE__ ) ).'/'.basename( __FILE__ ), 'plugin_settings', 10, 4 );
function plugin_settings ($links=array())
{
	$settings_link = '<a href="tools.php?page=datafeedr-ads">'.__('Settings', 'dfrads').'</a>';
	array_unshift($links, $settings_link);
	return $links;
}

// Parse $_GET values
function dfrads_return_get ($field) {
	if ( isset($_GET[$field]) && trim($_GET[$field]) != '' )
		return trim($_GET[$field]);
	return false;
}

// Parse $_POST values
function dfrads_return_post ($field) {
	if ( isset($_POST[$field]) && trim($_POST[$field]) != '' )
		return trim($_POST[$field]);
	return false;
}

// Determine what to do
function dfrads_options() {

	if (isset($_POST['submit-add-group'])) {
	
		check_admin_referer('dfrads_add_group');
		
		$ads = '';	
		foreach ($_POST as $k => $v)
			if (preg_match("/\bad_/", $k) && trim($v) != '')
				$ads .= trim($v).'[DFRADS]';
		
		if ($ads != '')
			$ads = substr($ads, 0, -8);
		
		$dfrads = get_option ('dfrads');
		$new_group_id = dfrads_new_group_id ($dfrads);
		$dfrads[$new_group_id]['name']				=	(trim($_POST['group_name']) != '') ? stripslashes(trim($_POST['group_name'])) : $new_group_id;
		$dfrads[$new_group_id]['before_ad']			=	stripslashes(trim($_POST['before_ad']));
		$dfrads[$new_group_id]['after_ad']			=	stripslashes(trim($_POST['after_ad']));
		$dfrads[$new_group_id]['ads']				=	stripslashes($ads);
		update_option('dfrads', $dfrads);
	} 
	
	
	if (isset($_POST['submit-edit-group'])) {	
		
		check_admin_referer('dfrads_edit_group');
		
		$ads = '';
		
		foreach ($_POST as $k => $v)
			if (preg_match("/\bad_/", $k) && trim($v) != '')
				$ads .= trim($v).'[DFRADS]';
		
		if ($ads != '')
			$ads = substr($ads, 0, -8);
		
		$dfrads = get_option ('dfrads');
		$group_id = $_POST['group_id'];
		$dfrads[$group_id]['name']				=	(trim($_POST['group_name']) != '') ? stripslashes(trim($_POST['group_name'])) : $group_id;
		$dfrads[$group_id]['before_ad']			=	stripslashes(trim($_POST['before_ad']));
		$dfrads[$group_id]['after_ad']			=	stripslashes(trim($_POST['after_ad']));
		$dfrads[$group_id]['ads']				=	stripslashes($ads);
		update_option('dfrads', $dfrads);
	}

	if (dfrads_return_get('action') == 'edit' && dfrads_return_get('group_id')) {
		dfrads_edit_group(dfrads_return_get('group_id'));
	} elseif (dfrads_return_get('action') == 'add') {
		dfrads_add_group();
	} elseif (dfrads_return_get('action') == 'duplicate' && dfrads_return_get('group_id')) {
		dfrads_duplicate_group(dfrads_return_get('group_id'));
	} elseif (dfrads_return_get('action') == 'delete' && dfrads_return_get('group_id')) {
		dfrads_delete_group(dfrads_return_get('group_id'));
	} else {
		dfrads_show_groups();
	}
}

// Add CSS, JS and initial HTML
function dfrads_header($page='') { ?>
	
	<style type="text/css">
	.dfrads_ads
	{
		margin-bottom: 20px;
		background-color: #fff;
		padding: 10px;
		border: 1px #CBCBCB solid;
	}
	
	.dfrads_ad_title { display: block; }
	.dfrads_ad_preview { }
	
	.dfrads_textarea
	{
		float: left;
		margin-right: 10px;
		width: 500px;
		height: 220px;
	}
	
	.dfrads_longtext { width: 100%; }
	
	.clear
	{
		clear: both;
		display: block;
		overflow: hidden;
		visibility: hidden;
		width: 0;
		height: 0;
	}
	
	</style>
	<script type="text/javascript">
		// http://www.dustindiaz.com/add-and-remove-html-elements-dynamically-with-javascript/
		function addEvent() {
		  var ni = document.getElementById('myDiv');
		  var numi = document.getElementById('theValue');
		  var num = (document.getElementById("theValue").value -1)+ 2;
		  numi.value = num;
		  var divIdName = "my"+num+"Div";
		  var newdiv = document.createElement('div');
		  newdiv.setAttribute("id",divIdName);
		  newdiv.innerHTML = "<div class=\"dfrads_ads\">Add new ad here (<a href=\"javascript:;\" onclick=\"removeElement(\'"+divIdName+"\')\">Remove this ad box</a>)<br /><textarea name='ad_" + num + "' class='dfrads_textarea'><\/textarea><div class='clear'> <\/div></div>";
		  ni.appendChild(newdiv);
		}
		function removeElement(divNum) {
		  var d = document.getElementById('myDiv');
		  var olddiv = document.getElementById(divNum);
		  d.removeChild(olddiv);
		}
	</script>
	
	<div class="wrap" id="dfrads">
		<h2>Datafeedr Random Ads V2</h2>
		<ul class="subsubsub">
			<li><a href="tools.php?page=datafeedr-ads"<?php if($page == '') : ?> class="current"<?php endif; ?>>All Ad Groups</a> | </li>
			<li><a href="tools.php?page=datafeedr-ads&amp;action=add"<?php if($page == 'add') : ?> class="current"<?php endif; ?>>Add New Group</a></li>
		</ul>
		<div class="clear"> </div>
		
<?php }

// Close <div>
function dfrads_footer() {
	echo '</div>';
}

// Show All Groups
function dfrads_show_groups() {
	dfrads_header();
	?>
	<table class="widefat" cellspacing="0">
		<thead>
			<tr>
				<th scope="col">Ad ID</th>
				<th scope="col">Ad Name</th>
				<th scope="col">Template Code</th>
				<th scope="col" style="text-align: center;">Actions</th>
			</tr>
		</thead>
	
		<tfoot>
			<tr>
				<th scope="col">Ad ID</th>
				<th scope="col">Ad Name</th>
				<th scope="col">Template Code</th>
				<th scope="col" style="text-align: center;">Actions</th>
			</tr>
		</tfoot>
		<tbody>
	
		<?php
		$dfrads = get_option('dfrads');
		$current_ads = '';
		$i=0;
		if (!empty($dfrads)) {
			foreach ($dfrads as $k => $v) {
				$i++;
				if ($i % 2 == 0)
					$class="alternate";
				else
					$class="";
				$name = ($v['name'] == '') ? $k : $v['name'];
				?>
				<tr class="<?php echo $class; ?>" valign="top">
					<td><?php echo $k; ?></td>
					<td><a href="tools.php?page=datafeedr-ads&amp;action=edit&amp;group_id=<?php echo $k; ?>"><b><?php echo $name; ?></b></a></td>
					<td style="white-space: nowrap;"><code>&lt;?php if (function_exists('dfrads')) { echo dfrads('<?php echo $k; ?>'); } ?&gt;</code></td>
					<td align="center">
						<a href="tools.php?page=datafeedr-ads&amp;action=edit&amp;group_id=<?php echo $k; ?>">edit</a> | 
						<a href="<?php echo wp_nonce_url("tools.php?page=datafeedr-ads&amp;action=duplicate&amp;group_id=".$k, 'dfrads_duplicate_group'); ?>"'><?php _e('duplicate', 'dfrads'); ?></a> | 
						<a href="<?php echo wp_nonce_url("tools.php?page=datafeedr-ads&amp;action=delete&amp;group_id=".$k, 'dfrads_delete_group'); ?>"' onclick="return confirm('<?php _e('You are about to delete this ad group.', 'datafeedr'); ?> \n\n <?php _e("Click \\'Cancel\\' to stop, \\'OK\\' to delete.", 'dfrads')?>')" class="delete" ><?php _e('delete', 'dfrads'); ?></a> 
					</td>	
				</tr>
				<?php
			}
		} else {
			echo '<tr><td colspan="4">There are no ad groups. <a href="tools.php?page=datafeedr-ads&amp;action=add">Create a new group</a>.</td></tr>';
		}
		?>
		</tbody>
	</table>
	<?php dfrads_footer(); 
}

// Get new, unique group ID
function dfrads_new_group_id ($dfrads) {
	
	$new_group_id = mt_rand(1111111,9999999);
	
	if (empty($dfrads))
		$dfrads = array();

	if (array_key_exists($new_group_id, $dfrads))
		return dfrads_new_group_id ($dfrads);
	else
		return $new_group_id;
}

// Duplicate group
function dfrads_duplicate_group ($group_id=false) {
	
	check_admin_referer('dfrads_duplicate_group');

	if (!$group_id)
		return dfrads_show_groups();
	
	$dfrads = get_option ('dfrads');
	
	$new_group_id = dfrads_new_group_id ($dfrads);	
	$dfrads[$new_group_id] = $dfrads[$group_id];
	$dfrads[$new_group_id]['name'] = $dfrads[$new_group_id]['name'] . ' copy';

	update_option('dfrads', $dfrads);
	dfrads_show_groups();
}

// Delete group
function dfrads_delete_group ($group_id=false) {
	
	check_admin_referer('dfrads_delete_group');

	if (!$group_id)
		return dfrads_show_groups();
	
	$dfrads = get_option ('dfrads');
	unset($dfrads[$group_id]);
	update_option('dfrads', $dfrads);
	dfrads_show_groups();
}

// Show edit form for group
function dfrads_edit_group ($group_id=false) {

	if (!$group_id)
		return dfrads_show_groups();
	
	$dfrads = get_option('dfrads');
	$group = $dfrads[$group_id];
	$ads = explode("[DFRADS]", $group['ads']);
	$i=0;
	$ad_textareas = '';
	
	foreach ($ads as $ad) {
		$i++;
		$ad_textareas .= "
		<div class=\"dfrads_ads\" id=\"my{$i}Div\">
			<span class=\"dfrads_ad_title\">Ad #{$i} (<a href=\"javascript:;\" onclick=\"removeElement('my{$i}Div')\">Remove this ad</a>)</span>
			<textarea name='ad_{$i}' class=\"dfrads_textarea\">{$ad}</textarea></span><span class=\"dfrads_ad_preview\">{$ad}</span>
			<div class=\"clear\"> </div>
		</div>
		";
	}
	dfrads_header('edit');
	?>
	<form action="tools.php?page=datafeedr-ads" method="post">
		<?php wp_nonce_field('dfrads_edit_group'); ?>
		<input name="group_id" type="hidden" value="<?php echo $group_id; ?>">
		<input type="hidden" value="<?php echo ($i++); ?>" id="theValue" />
		<h3>Optional Fields</h3>
		<p>The following fields are optional. You can insert text and/or HTML code before or after the entire ad group and each individual ad.</p>
		<table class="form-table">					
			<tr>
				<th>Ad Group Name:</th>
				<td><input name="group_name" type="text" value="<?php echo $group['name']; ?>" /> (No HTML. This field will not appear on your site.)</td>
			</tr>		
			<tr>
				<th>Before Ad:</th>
				<td><input name="before_ad" type="text" value="<?php echo $group['before_ad']; ?>" class="dfrads_longtext" /></td>
			</tr>
			<tr>
				<th>After Ad:</th>
				<td><input name="after_ad" type="text" value="<?php echo $group['after_ad']; ?>" class="dfrads_longtext" /></td>
			</tr>
		</table>
		<h3>Ad Boxes</h3>
		<p>Enter one ad into each box. To add additional boxes, click the "Add Box" link at the bottom of this page.</p>
		<div id="myDiv"> <?php echo $ad_textareas; ?> </div>
		<div>
			<a href="javascript:;" onclick="addEvent();" class="button-secondary">Add Box</a> 
			<input name="submit-edit-group" type="submit" value="Save Changes" class="button-secondary">
			<div class="clear"> </div>
		</div>
	</form>
	</div>
	<?php dfrads_footer(); 
}

// Show "Add Group" form
function dfrads_add_group() {
	dfrads_header('add');
	?>
	
	<h3>Optional Fields</h3>
	<p>The following fields are optional. You can insert text and/or HTML code before or after the entire ad group and each individual ad.</p>

	<form action="tools.php?page=datafeedr-ads" method="post">
		<?php wp_nonce_field('dfrads_add_group'); ?>
		<input type="hidden" value="1" id="theValue" />
		<table class="form-table">				
			<tr>
				<th>Ad Group Name</th>
				<td><input name="group_name" type="text" value="" /> (No HTML. This field will not appear on your site.)</td>
			</tr>		
			<tr>
				<th>Before Ad:</th>
				<td><input name="before_ad" type="text" value="" class="dfrads_longtext" /></td>
			</tr>
			<tr>
				<th>After Ad:</th>
				<td><input name="after_ad" type="text" value="" class="dfrads_longtext" /></td>
			</tr>
		</table>
		<h3>Ad Boxes</h3>
		<p>Enter one ad into each box. To add additional boxes, click the "Add Box" button at the bottom of this page.</p>
		<div id="myDiv">		
			<div class="dfrads_ads" id="my1Div">
				<span class="dfrads_ad_title">Ad #1 (<a href="javascript:;" onclick="removeElement('my1Div')">Remove this ad</a>)</span>
				<textarea name='ad_1' class='dfrads_textarea'></textarea>
				<div class="clear"> </div>
			</div>
		</div>
		<div>
			<a href="javascript:;" onclick="addEvent();" class="button-secondary">Add Box</a> 
			<input name="submit-add-group" type="submit" value="Save Ad Group" class="button-secondary">
			<div class="clear"> </div>
		</div>
	</form>
	<?php dfrads_footer(); 
}

// Show error message if user is Admin
function dfrads_display_admin_error($msg='') {
	if (current_user_can('level_10'))
		return '<div style="color:red;padding:10px;border:red 1px solid;background:#FFEFF1;"><b>Datafeedr Random Ads Message:</b><br />'. $msg.'</div>';	
	return '';
}

// Display the ads from a template function
function dfrads($group_id=false) {

	if (!$group_id)
		return dfrads_display_admin_error ('A <i>group ID</i> is required.');
		
	$dfrads = get_option ('dfrads');
	
	if (!is_array($dfrads[$group_id]))
		return dfrads_display_admin_error ('The ad group "<i>'.$group_id.'</i>" does not exist.');

	$ads = explode('[DFRADS]', $dfrads[$group_id]['ads']);
	$num_ads = count($ads);
	$ad_id = mt_rand(1, $num_ads);
	$ad = $ads[($ad_id-1)];
	
	return $dfrads[$group_id]['before_ad'] . $ad . $dfrads[$group_id]['after_ad'];
}

/**
 * Add function to widgets_init that'll load our widget.
 * @since 0.1
 */
add_action( 'widgets_init', 'example_load_widgets' );

/**
 * Register our widget.
 * 'DfrAds_Widget' is the widget class used below.
 *
 * @since 0.1
 */
function example_load_widgets() {
	register_widget( 'DfrAds_Widget' );
}

/**
 * Example Widget class.
 * This class handles everything that needs to be handled with the widget:
 * the settings, form, display, and update.  Nice!
 *
 * @since 0.1
 */
class DfrAds_Widget extends WP_Widget {
	
	/**
	 * Widget setup.
	 */
	function DfrAds_Widget() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'dfrads', 'description' => __('Display your rotating ads in the sidebar.', 'dfrads') );

		/* Widget control settings. */
		$control_ops = array( 'id_base' => 'dfrads-widget' );

		/* Create the widget. */
		$this->WP_Widget( 'dfrads-widget', __('Datafeedr Random Ads', 'dfrads'), $widget_ops, $control_ops );
	}

	/**
	 * How to display the widget on the screen.
	 */
	function widget( $args, $instance ) {
		extract( $args );

		/* Our variables from the widget settings. */
		$title 		= apply_filters('widget_title', $instance['title'] );
		$group_id 	= $instance['group_id'];

		/* Before widget (defined by themes). */
		echo $before_widget;

		/* Display the widget title if one was input (before and after defined by themes). */
		if ( $title )
			echo $before_title . $title . $after_title;

		/* Display name from widget settings if one was input. */
		echo dfrads($group_id);

		/* After widget (defined by themes). */
		echo $after_widget;
	}

	/**
	 * Update the widget settings.
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		/* Strip tags for title and name to remove HTML (important for text inputs). */
		$instance['title'] = strip_tags( $new_instance['title'] );

		/* No need to strip tags for sex and show_sex. */
		$instance['group_id'] = $new_instance['group_id'];

		return $instance;
	}

	/**
	 * Displays the widget settings controls on the widget panel.
	 * Make use of the get_field_id() and get_field_name() function
	 * when creating your form elements. This handles the confusing stuff.
	 */
	function form( $instance ) {

		/* Set up some default widget settings. */
		$defaults = array( 'title' => __('Our Sponsors', 'dfrads'),  );
		$instance = wp_parse_args( (array) $instance, $defaults ); 
		$dfrads = get_option ('dfrads');
		?>
		

		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'dfrads'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" class="widefat" type="text" />
		</p>
		
		<!-- group_id: Select Box -->
		<p>
			<label for="<?php echo $this->get_field_id( 'group_id' ); ?>"><?php _e('Select Ad Group:', 'example'); ?></label> 
			<select id="<?php echo $this->get_field_id( 'group_id' ); ?>" name="<?php echo $this->get_field_name( 'group_id' ); ?>" class="widefat">
				<?php
				if (count($dfrads)>0) {
					foreach ($dfrads as $k => $v) { 
						$name = ($v['name'] == '') ? $k : $v['name']; 
						?>
						<option <?php if ( $k == $instance['group_id'] ) echo 'selected="selected"'; ?> value="<?php echo $k; ?>"><?php echo $name; ?></option>
						<?php 
					} 
				} else { ?>
					<option>You have not created any ad groups.</option>
				<?php 
				}
				?>
			</select>
		</p>

	<?php
	}
}
?>