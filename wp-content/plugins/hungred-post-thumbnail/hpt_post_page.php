<?php
/*  Copyright 2009  Clay Lua  (email : clay@hungred.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
require_once "hpt_constants.php";
require_once HPT_PLUGIN_DIR. '/hpt_function.php';
global $post;
global $wpdb;
$hpt_post = $post->ID;
$hpt_flag = false;
//retrieve the loading image for display
$hpt_table = $wpdb->prefix."hungred_post_thumbnail_options";
$hpt_query = "SELECT `hpt_loading_url` FROM `".$hpt_table."` WHERE 1  limit 1";
$hpt_row = $wpdb->get_row($hpt_query,ARRAY_A);
$hpt_load_img = "<img src='".$hpt_row['hpt_loading_url']."'/>";
//retrieve the live image for display
$hpt_table = $wpdb->prefix."hungred_post_thumbnail";
$hpt_query = "SELECT * FROM `".$hpt_table."` WHERE `hpt_post` = '".$hpt_post."'  limit 1";
$hpt_row = $wpdb->get_row($hpt_query,ARRAY_A);
$hpt_live_img = "<img src='".$hpt_row['hpt_url']."'/>";
//check for displaying different title
if($hpt_row['hpt_url'] != "")
	$hpt_flag = true;
//retrieve the draft image for display
$hpt_table = $wpdb->prefix."hungred_post_thumbnail_draft";
$hpt_query = "SELECT * FROM `".$hpt_table."` WHERE `hpt_post` = '".$hpt_post."' limit 1";
$hpt_row = $wpdb->get_row($hpt_query,ARRAY_A);
$hpt_draft_img = "<img src='".$hpt_row['hpt_url']."'/>";




?>

<div id="hpt_main">
		<input name="hpt_files" id="hpt_files" size="27" type="file" />
		<input id="hpt_upload" class="button" type="button" name="action" value="Upload" disabled onclick="hpt_redirect()"/>
		<h2><?php  
		if($hpt_flag)
		_e("Uploaded thumbnail");
		else
		_e("No thumbnail uploaded for this post");
		?></h2>
		<div class='postbox' >
		<iframe id='hpt_iframe' name='hpt_iframe'>
		</iframe>
		<div id="hpt_loading"><?php echo $hpt_load_img;?></div>
		<div id="hpt_image_container">
			<div id="hpt_image"><h4><?php _e("Draft Image");?></h4><?php  echo $hpt_draft_img; ?></div>
			<div id="hpt_image_live"><h4><?php _e("Live Image");?></h4><?php echo $hpt_live_img; ?></div>
			<?php
			global $current_user;
			get_currentuserinfo();
			$level = $current_user->user_level;
			if ( is_user_logged_in() && $level == "10") 
			{
			?>
			<div id="hpt_image_options">
				<h4><?php _e("Post options");?></h4>
				<div id='hpt_options_content'>
					<span><label>Width</label><input type='text' class='hpt_element' id='hpt_width' name='hpt_width' value="<?php echo reverse_make_safe($hpt_row['hpt_width']);?>"/></span>
					<span><label>Height</label><input type='text' class='hpt_element' id='hpt_height' name='hpt_height' value="<?php echo reverse_make_safe($hpt_row['hpt_height']);?>"/></span>
					<span><label>Video</label><textarea class='hpt_element' id='hpt_video' name='hpt_video' ><?php echo reverse_make_safe($hpt_row['hpt_video']);?></textarea></span>
					<span><label>Mode</label>
					<SELECT name="hpt_mode" class='hpt_element'>
					<?php 
					if($hpt_row['hpt_mode'] == "S"){ ?>
					<option selected value="S">Single</option>
					<option value="R">Random</option>
					<option value="T">Smart</option>
					<option value="V">Advance</option>
					<option value="U">Upload</option>
					<option value="O">Video</option>
					<option value="F">First Image</option>
					<option value="D">Disabled</option>
					<?php }else if($hpt_row['hpt_mode'] == "R"){?>
					<option value="S">Single</option>
					<option selected value="R">Random</option>
					<option value="T">Smart</option>
					<option value="V">Advance</option>
					<option value="U">Upload</option>
					<option value="O">Video</option>
					<option value="F">First Image</option>
					<option value="D">Disabled</option>
					<?php }else if($hpt_row['hpt_mode'] == "T"){?>
					<option value="S">Single</option>
					<option value="R">Random</option>
					<option selected value="T">Smart</option>
					<option value="V">Advance</option>
					<option value="U">Upload</option>
					<option value="O">Video</option>
					<option value="F">First Image</option>
					<option value="D">Disabled</option>
					<?php }else if($hpt_row['hpt_mode'] == "V"){?>
					<option value="S">Single</option>
					<option value="R">Random</option>
					<option value="T">Smart</option>
					<option selected value="V">Advance</option>
					<option value="U">Upload</option>
					<option value="O">Video</option>
					<option value="F">First Image</option>
					<option value="D">Disabled</option>
					<?php }else if($hpt_row['hpt_mode'] == "D"){?>
					<option value="S">Single</option>
					<option value="R">Random</option>
					<option value="T">Smart</option>
					<option value="V">Advance</option>
					<option value="U">Upload</option>
					<option value="O">Video</option>
					<option value="F">First Image</option>
					<option selected value="D">Disabled</option>
					<?php }else if($hpt_row['hpt_mode'] == "F"){?>
					<option value="S">Single</option>
					<option value="R">Random</option>
					<option value="T">Smart</option>
					<option value="V">Advance</option>
					<option value="U">Upload</option>
					<option value="O">Video</option>
					<option selected value="F">First Image</option>
					<option value="D">Disabled</option>
					<?php }else if($hpt_row['hpt_mode'] == "O"){?>
					<option value="S">Single</option>
					<option value="R">Random</option>
					<option value="T">Smart</option>
					<option value="V">Advance</option>
					<option value="U">Upload</option>
					<option selected value="O">Video</option>
					<option value="F">First Image</option>
					<option value="D">Disabled</option>
					<?php }else{?>
					<option value="S">Single</option>
					<option value="R">Random</option>
					<option value="T">Smart</option>
					<option value="V">Advance</option>
					<option selected value="U">Upload</option>
					<option value="O">Video</option>
					<option value="F">First Image</option>
					<option value="D">Disabled</option>
					<?php }?>
					</SELECT></span>
					
					<span><label>Active Option</label>
					<?php
					if($hpt_row['hpt_enable'] == "t"){
						echo "<input type='checkbox' name='hpt_enable' id='hpt_enable' checked />";
					}else{
						echo "<input type='checkbox' name='hpt_enable' id='hpt_enable' />";
					}
					
					?>
					<input type="hidden" id='hpt_id' name="hpt_id" value="<?php echo $hpt_post;?>" />
					<input type="hidden" id='hpt_url' name="hpt_url" value="<?php echo HPT_PLUGIN_URL;?>" />
					</span>
					<span><label>Front Page Only?</label>
					<?php
					if($hpt_row['hpt_front'] == "t"){
						echo "<input type='checkbox' name='hpt_front' id='hpt_front' checked />";
					}else{
						echo "<input type='checkbox' name='hpt_front' id='hpt_front' />";
					}
					
					?>
					</span>
				</div>
			</div>
			<?php
			}?>
		</div>
		
		</div>
</div>
