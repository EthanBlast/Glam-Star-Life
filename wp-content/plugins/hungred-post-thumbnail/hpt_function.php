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
set_time_limit(0);
/*
Name: isAllowedExtension
Usage:  use by hpt_upload.php to validate upload file
Parameter: 	fileName: the file to be check
Description: use to validate whether a given filename has the appropriate extension
*/
function isAllowedExtension($fileName) {
  global $allowedExtensions;

  return in_array(end(explode(".", $fileName)), $allowedExtensions);
}

function hpt_extract_file_name($string)
{
	$basename = basename($string);
	$name = preg_replace('/[^a-zA-Z0-9\s]|hpt|jpg|png|jpeg|gif/i', ' ', $basename);
	$string = preg_replace('/\s\s+/', ' ', $name);
	return $string;
}

/*
Name: make_safe
Usage:  use to secure user input
Parameter: 	$variable: the variable required to modify
Description: return a safe string for manipulation
*/
function make_safe($variable) {
	$variable = htmlspecialchars(htmlentities(trim($variable)));
	return $variable;
}

/*
Name: reverse_make_safe
Usage:  use to secure user input
Parameter: 	$variable: the variable required to modify
Description: return a safe string for manipulation
*/
function reverse_make_safe($variable) {
	$variable = htmlspecialchars_decode(html_entity_decode(trim($variable)));
	return $variable;
}
/*
Name: resize_n_image
Usage: use to resize all images if user selected resize all option as 'YES'
Parameter: 	$path: the path to the given folder
			$w: the new width
			$h: the new height
Description: given a path and new width and height the method resize all the images contain
			 in the path given.
*/
function resize_n_image($path, $w, $h)
{
	$image_file_path = $path; 
	$d = dir($image_file_path) or $error .="Wrong path: $image_file_path";
	while (false !== ($entry = $d->read())) {
		if($entry != "hpt-options-loading.gif")
		{
			if(in_array(end(explode(".", strtolower($entry))), array("png", "jpg", "jpeg", "gif")))
			{
				if(strpos($image_file_path, "draft") != false)
				$tmp = str_replace("draft", "original/draft", $image_file_path);
				else if(strpos($image_file_path, "live") != false)
				$tmp = str_replace("live", "original/live", $image_file_path);
				else if(strpos($image_file_path, "random") != false)
				$tmp = str_replace("random", "original/random", $image_file_path);
				
				if(file_exists($tmp. $entry))
				smart_resize_image($tmp. $entry, $w, $h,false, $path. $entry,false);
				else if(file_exists($path. $entry))
				smart_resize_image($path. $entry, $w, $h);
			}
		}
	}
	$d->close();
}
/*
Name: hpt_getAllFile
Usage: use by random options to get the all images in the folder
Parameter: 	$path: the path to the given folder
Description: get the image specify in $path
*/
function hpt_getAllFile($path)
{
	if(function_exists ('glob')){
		$files = glob($path.'/*');
	}else
		$files = directoryToArray($path, true);
	return $files;
}

function directoryToArray($directory, $recursive) {
	$array_items = array();
	if ($handle = opendir($directory)) {
		while (false !== ($file = readdir($handle))) {
			if ($file != "." && $file != "..") {
				if (is_dir($directory. "/" . $file)) {
					if($recursive) {
						$array_items = array_merge($array_items, directoryToArray($directory. "/" . $file, $recursive));
					}
					$file = $directory . "/" . $file;
					$array_items[] = preg_replace("/\/\//si", "/", $file);
				} else {
					$file = $directory . "/" . $file;
					$array_items[] = preg_replace("/\/\//si", "/", $file);
				}
			}
		}
		closedir($handle);
	}
	return $array_items;
}
/*
Name: hpt_removeSymbols
Usage: use to remove all other character other than the allowed one
Parameter: 	$string: the format string
Description: this method take in a string and return a string at its parameter
*/
function hpt_removeSymbols(&$string)
{
	$valid_chars_regex = '.A-Za-z0-9_-\s ';
	$string = preg_replace('/[^'.$valid_chars_regex.']|\.+$/i', "", $string);
}
/*
Name: hpt_getAllNormalImage
Usage: use by random options to get the all images in the folder
Parameter: 	$path: the path to the given folder
Description: get the image specify in $path
*/
function hpt_getAllNormalImage($path)
{
	if(function_exists('glob')){
		$files = glob($path.'/hpt_*.*');
	}else
		$files = directoryToArray($path, true);
	return $files;
}
/*
Name: remove_numbers
Usage: use in smart detection to remove unwanted number in the image name
Parameter: 	$value: the string to operate with
Description: remove numbers in a string
*/
function remove_numbers($value) {
	$vowels = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "0");
	$value = str_replace($vowels, '', $value);
	return $value;
}
/*
Name: lower
Usage: use by array_walk to change the array to lower case
Parameter: 	$string: string to convert
Description: change a string to lower case
*/
function lower(&$string){
    $string = strtolower($string);
 }
/*
Name: smart_resize_image
Usage:  use by hpt_upload.php and hpt_admin_page.php to resize a given image
Parameter: 	http://mediumexposure.com/techblog/smart-image-resizing-while-preserving-transparency-php-and-gd-library
Description: resize the image with high quality
*/
function smart_resize_image( $file, $width = 0, $height = 0, $proportional = false, $output = 'file', $delete_original = true, $use_linux_commands = false )
  {
    if ( $height <= 0 && $width <= 0 ) {
      return false;
    }
 
    $info = getimagesize($file);
    $image = '';
 
    $final_width = 0;
    $final_height = 0;
    list($width_old, $height_old) = $info;
 
    if ($proportional) {
      if ($width == 0) $factor = $height/$height_old;
      elseif ($height == 0) $factor = $width/$width_old;
      else $factor = min ( $width / $width_old, $height / $height_old);   
 
      $final_width = round ($width_old * $factor);
      $final_height = round ($height_old * $factor);
 
    }
    else {
      $final_width = ( $width <= 0 ) ? $width_old : $width;
      $final_height = ( $height <= 0 ) ? $height_old : $height;
    }
 
    switch ( $info[2] ) {
      case IMAGETYPE_GIF:
        $image = imagecreatefromgif($file);
      break;
      case IMAGETYPE_JPEG:
        $image = imagecreatefromjpeg($file);
      break;
      case IMAGETYPE_PNG:
        $image = imagecreatefrompng($file);
      break;
      default:
        return false;
    }
 
    $image_resized = imagecreatetruecolor( $final_width, $final_height );
 
    if ( ($info[2] == IMAGETYPE_GIF) || ($info[2] == IMAGETYPE_PNG) ) {
      $trnprt_indx = imagecolortransparent($image);
 
      // If we have a specific transparent color
      if ($trnprt_indx >= 0) {
 
        // Get the original image's transparent color's RGB values
        $trnprt_color    = imagecolorsforindex($image, $trnprt_indx);
 
        // Allocate the same color in the new image resource
        $trnprt_indx    = imagecolorallocate($image_resized, $trnprt_color['red'], $trnprt_color['green'], $trnprt_color['blue']);
 
        // Completely fill the background of the new image with allocated color.
        imagefill($image_resized, 0, 0, $trnprt_indx);
 
        // Set the background color for new image to transparent
        imagecolortransparent($image_resized, $trnprt_indx);
 
 
      } 
      // Always make a transparent background color for PNGs that don't have one allocated already
      elseif ($info[2] == IMAGETYPE_PNG) {
 
        // Turn off transparency blending (temporarily)
        imagealphablending($image_resized, false);
 
        // Create a new transparent color for image
        $color = imagecolorallocatealpha($image_resized, 0, 0, 0, 127);
 
        // Completely fill the background of the new image with allocated color.
        imagefill($image_resized, 0, 0, $color);
 
        // Restore transparency blending
        imagesavealpha($image_resized, true);
      }
    }
 
    imagecopyresampled($image_resized, $image, 0, 0, 0, 0, $final_width, $final_height, $width_old, $height_old);
 
    if ( $delete_original ) {
      if ( $use_linux_commands )
        exec('rm '.$file);
      else
        @unlink($file);
    }
	if(file_exists($output))
	@unlink($output);
    switch ( strtolower($output) ) {
      case 'browser':
        $mime = image_type_to_mime_type($info[2]);
        header("Content-type: $mime");
        $output = NULL;
      break;
      case 'file':
        $output = $file;
      break;
      case 'return':
        return $image_resized;
      break;
      default:
      break;
    }
 
    switch ( $info[2] ) {
      case IMAGETYPE_GIF:
        imagegif($image_resized, $output);
      break;
      case IMAGETYPE_JPEG:
        imagejpeg($image_resized, $output);
      break;
      case IMAGETYPE_PNG:
        imagepng($image_resized, $output);
      break;
      default:
        return false;
    }
 
    return true;
  }

?>