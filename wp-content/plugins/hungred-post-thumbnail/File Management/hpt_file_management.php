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
require_once "../hpt_constants.php";
require_once WP_CONFIG_DIR;
require_once HPT_PLUGIN_DIR. '/hpt_function.php';
nocache_headers();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html dir="ltr" xmlns="http://www.w3.org/1999/xhtml" lang="en-US">
<head>
<title>Hungred Post thumbnail - file management</title>

<link rel="stylesheet" href="css/ini.css"/>
<link href="css/default.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../../../../wp-includes/js/jquery/jquery.js"></script>
<script type="text/javascript" src="js/hpt_fm.js"></script>

<script type="text/javascript" src="../../../../wp-includes/js/swfupload/swfupload.js"></script> 
<script type="text/javascript" src="js/swfupload.queue.js"></script>
<script type="text/javascript" src="js/fileprogress.js"></script>
<script type="text/javascript" src="js/handlers.js"></script>
</head>
<body>
<?php

global $current_user;
get_currentuserinfo();
$level = $current_user->user_level;
if ( is_user_logged_in() && $level == "10") 
{
$hpt_all_files = hpt_getAllFile(HPT_UPLOAD_DIR."/images/random/");
global $wpdb;
$table = $wpdb->prefix."hungred_post_thumbnail_options";
$query = "SELECT `hpt_height`, `hpt_width`, `hpt_keep` FROM `".$table."` WHERE 1 AND `hpt_id` = '1' limit 1";
$row = $wpdb->get_row($query,ARRAY_A);
$width = $row['hpt_width'];
$height = $row['hpt_height'];
$keep = $row['hpt_keep'];
?>
<script type="text/javascript">
var swfuploadL10n = {
	queue_limit_exceeded: "You have attempted to queue too many files.",
	file_exceeds_size_limit: "This file is too big. Your php.ini upload_max_filesize is 64M.",
	zero_byte_file: "This file is empty. Please try another.",
	invalid_filetype: "This file type is not allowed. Please try another.",
	default_error: "An error occurred in the upload. Please try again later.",
	missing_upload_url: "There was a configuration error. Please contact the server administrator.",
	upload_limit_exceeded: "You may only upload 1 file.",
	http_error: "HTTP error.",
	upload_failed: "Upload failed.",
	io_error: "IO error.",
	security_error: "Security error.",
	file_cancelled: "File cancelled.",
	upload_stopped: "Upload stopped.",
	dismiss: "Dismiss",
	crunching: "Crunching&hellip;",
	deleted: "Deleted"
};
try{convertEntities(swfuploadL10n);}catch(e){};
var swfu;
jQuery(document).ready(function() {
var settings = {
	upload_url : "<?php echo HPT_PLUGIN_URL;?>"+"/File Management/hpt_file_upload.php", 
	flash_url : "<?php echo get_bloginfo('url');?>"+"/wp-includes/js/swfupload/swfupload.swf", 
	post_params:{
	"width":"<?php echo $width;?>",
	"height":"<?php echo $height;?>",
	"keep":"<?php echo $keep;?>",
	"logged":"<?php echo is_user_logged_in();?>",
	"level":"<?php echo $level;?>"
	},
	file_types : "*.jpg;*.gif;*.png", 
	file_types_description : "Image Files Only",
	file_queue_limit : 0,
	custom_settings : {
				progressTarget : "fsUploadProgress",
				cancelButtonId : "cancel"
	},
	debug: false,
	button_cursor : SWFUpload.CURSOR.HAND, 

	button_image_url: "<?php echo get_bloginfo('url');?>"+"/wp-includes/images/upload.png",
	button_width: "132",
	button_height: "24",
	button_placeholder_id: "spanButtonPlaceHolder",
	button_text_style: '.button { text-align: center; font-weight: bold; font-family:"Lucida Grande",Verdana,Arial,"Bitstream Vera Sans",sans-serif; }',
	button_text: '<span class="button">Select Files</span>',
	button_text_top_padding: 2,
	
	file_size_limit : "67108864b",
				file_queued_handler : fileQueued,
				file_queue_error_handler : fileQueueError,
				file_dialog_complete_handler : fileDialogComplete,
				upload_start_handler : uploadStart,
				upload_progress_handler : uploadProgress,
				upload_error_handler : uploadError,
				upload_success_handler : uploadSuccess,
				upload_complete_handler : uploadComplete,
				queue_complete_handler : queueComplete	// Queue plugin event
};
swfu = new SWFUpload(settings);


});

</script>
<div id="body">
<div id='frame'>
<div id="upload-form">
<form enctype="multipart/form-data" method="post" action="hpt_file_upload.php" > 
		<div class="fieldset flash" id="fsUploadProgress">
			<span class="legend">Upload Queue</span>
			</div>
		<div>
				<span id="spanButtonPlaceHolder"></span>
				<input id="cancel"  type="button" value="Cancel All Uploads" onclick="swfu.cancelQueue();" disabled="disabled"  />
		</div>
		<div id="arrow"></div>
</form>
</div>
	<div id='container'>
<?php
	if($hpt_all_files != NULL )
	{
		foreach($hpt_all_files as $file)
		{
			$file_name = basename($file);
			$url = HPT_UPLOAD_URL."/images/random/".$file_name;
			echo "<div class='box'><img title='".$file_name."' src='".$url."'/></div>";
		}
	}
	else
	{
		echo "There are currently no files. Please upload them before proceding to this page.";
	}
?>
		
	</div>
</div>



</div>
<div id="screen"></div>

<div class="navbox" id="navbox">
	<input type="button" class="hpt_rename" onclick="renameBox()"/>
	<input type="button" class="hpt_remove" onclick="deleteBox()"/>
	<input type="button" class="hpt_cancel" onclick="cancel()"/>
</div>
<div class="navbox" id="renamebox">
	<input type="input" id="input_rename" />
	<input type="button" class="hpt_rename" onclick="rename_confirm()"/>
	<input type="button" class="hpt_cancel" onclick="confirm_cancel()"/>
</div>
<div class="navbox" id="deletebox">
	<p>Are you sure?</p>
	<input type="button" class="hpt_remove" onclick="delete_confirm()"/>
	<input type="button" class="hpt_cancel" onclick="confirm_cancel()"/>
</div>
<div class="navbox" id="messagebox">
	<p id="errormsg"></p>
	<input type="button" class="hpt_retry" onclick="retry()"/>
	<input type="button" class="hpt_cancel" onclick="confirm_cancel()"/>
</div>
<div class="navbox" id="okbox">
	<p id="okmsg"></p>
	<input type="button" class="hpt_ok" onclick="hideAllBox();appear();"/>
</div>

<?php
}
else
{
	echo "You do not have access to view this page. Please contact your administrator.";
}
?>
</body>
</html>