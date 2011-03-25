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
require_once WP_CONFIG_DIR;
require_once HPT_PLUGIN_DIR. '/hpt_function.php';
/*
Upload handler
*/
if ( is_user_logged_in()) 
{
	$error = false;
	$thisPost = $_POST['hpt_id'];
	//check for appropriate size with php.ini
	$POST_MAX_SIZE = ini_get('post_max_size');
	$mul = substr($POST_MAX_SIZE, -1);
	$mul = ($mul == 'M' ? 1048576 : ($mul == 'K' ? 1024 : ($mul == 'G' ? 1073741824 : 1)));
	if ($_SERVER['CONTENT_LENGTH'] > $mul*(int)$POST_MAX_SIZE && $POST_MAX_SIZE) $error = true;
	if($thisPost != "" && $thisPost != 0)
	{
		if(!$error)
		{
			if (is_uploaded_file($_FILES['hpt_files']['tmp_name'])) //check whether the file has been uploaded
			{
			/***
				now verify if the file exists, just verify
				if the 1rst array is not empty. else you
				can do what you want, that form allows to
				use a multipart form, for exemple for a
				topic on a forum, and then to post an
				hpt_files with all our other values
			***/ 
			if(isset($_FILES['hpt_files']) && !empty($_FILES['hpt_files']['name'])) {

				/***
					verify whether a file already exist
				***/ 
				if (!file_exists(HPT_UPLOAD_DIR ."/images/live/". $_FILES['hpt_files']['name'])) {
					/***
						now verify the mime, i did not find
						something more easy than verify the
						'image/' ty^pe. if wrong tell it!
					***/
					if(!eregi('image/', $_FILES['hpt_files']['type'])) {

					  echo 'The uploaded file is not an image. Please upload a valid file!';

					} else { 
						$file = $_FILES['hpt_files'];
						//allowed extension
						$allowedExtensions = array("jpg", "png", "gif", "jpeg");
						if($file['error'] == UPLOAD_ERR_OK) {
						  if(isAllowedExtension(strtolower($file['name']))) {
							// Uploading starts here
								$uploaddir = '/images/draft/';
								$post = get_post($thisPost); 
								//remove all unnecessary symbols for SEO purposes
								$title = preg_replace("/[^a-zA-Z0-9\s ]/", "",  $post->post_title); 
								//add the title of the post to the image for SEO purposes
								$title = str_replace(" ", "-",$title);
								$newName = $title."-".basename($_FILES['hpt_files']['name']);
								$uploadfile = HPT_UPLOAD_DIR."/".$uploaddir . $newName;
								$oripath = str_replace("draft", "original/draft", $uploadfile);
								//resize image began!
								$options = getOptions();
								if($options['hpt_keep'] == "Y")
								{
									copy($_FILES['hpt_files']['tmp_name'], $oripath);
								}
								
								smart_resize_image($_FILES['hpt_files']['tmp_name'], $options['hpt_width'], $options['hpt_height']);
								if (move_uploaded_file($_FILES['hpt_files']['tmp_name'], $uploadfile)) {
									$url = HPT_UPLOAD_URL.'/images/draft/'.$newName;

									if(file_exists($uploadfile) != false)
									{
										$result = sqlquery($url,$newName, $uploadfile);
										if($result)
											echo "<img src='".$url."'/>";
									}
									else
									{
										if(file_exists($oripath) != false)
											unlink($oripath);
										echo "Failed moving file";
									}
								
								} else {
									if(file_exists($oripath) != false)
											unlink($oripath);
									echo "error";
								}
							} else {
							echo "Invalid file type";
						  }
						} else die("Cannot upload");
					}
				}
				else
				{
					echo 'The uploaded file already exist. Please upload another file!';
				}
			}
			
			}
			
		}
		else
		echo "file too big";
	}
	else
	{
		echo "This is a new post, please save draft to get a post id before uploading an image";
	}
}
/*
Name: sqlquery
Usage: use to query Wordpress SQL database for thumbnail
Parameter: 	$url: printed url
			$name: file name
			$loc: path location
Description: search for previous file, remove it and update the table with the new ones
*/
function sqlquery($url,$name,$loc)
{
	global $wpdb;
	global $thisPost;
	$table = $wpdb->prefix."hungred_post_thumbnail_draft";
	$query = "SELECT `hpt_loc` FROM `".$table."` WHERE `hpt_post` = '".$thisPost."'";
	$row = $wpdb->get_row($query,ARRAY_A);
	// if(file_exists($row['hpt_loc']) != false)
		// unlink($row['hpt_loc']);
	if(file_exists($row['hpt_loc']) != false){
		echo 'file exist';
		return false;
	}
	// $oripath = str_replace("draft", "original/draft", $row['hpt_loc']);
	// if(file_exists($oripath) != false)
		// unlink($oripath);
	$query = " 	INSERT INTO $table(hpt_post, hpt_name, hpt_url, hpt_loc) VALUES('".$wpdb->escape($thisPost)."', '".$wpdb->escape($name)."', '".$wpdb->escape($url)."', '".$wpdb->escape($loc)."')
				ON DUPLICATE KEY UPDATE `hpt_name`='".$wpdb->escape($name)."', `hpt_url`='".$wpdb->escape($url)."', `hpt_loc`='".$wpdb->escape($loc)."'
				";
	return $wpdb->query($query);
}

/*
Name: getOptions
Usage: use to query Wordpress SQL database for admin options
Parameter: 	NONE
Description: get all data from admin options
*/
function getOptions()
{
	global $wpdb;
	$table = $wpdb->prefix."hungred_post_thumbnail_options";
	$query = "SELECT * FROM `".$table."` WHERE 1 AND `hpt_id` = '1' limit 1";
	$row = $wpdb->get_row($query,ARRAY_A);
	return $row;
}
?>
