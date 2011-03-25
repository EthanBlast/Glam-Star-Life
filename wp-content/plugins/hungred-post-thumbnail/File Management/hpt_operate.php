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
require_once HPT_PLUGIN_DIR. '/hpt_function.php';

$op = make_safe($_POST['op']);
$oldname = make_safe($_POST['oldname']);
$newname = make_safe(preg_replace("/[^a-zA-Z0-9\s-_ ]/", "",  $_POST['newname']));

$path = HPT_UPLOAD_DIR."/images/random/";
$extension = explode(".", $oldname);
$extension = $extension[1];
if($op == "D")
{
	$realpath = $path.$oldname;
	$oripath = str_replace("random", "original/random", $realpath);
	if(file_exists($oripath) != false)
		unlink($oripath);
	echo unlink($realpath);
}
else if($op == "R")
{
	echo $newname.".".$extension."||";
	
	$realpath = $path;
	$oripath = str_replace("random", "original/random", $realpath);
	
	
		
	if(file_exists($path.$newname.".".$extension) == false)
	{
		if(file_exists($oripath.$oldname.".".$extension) != false)
		rename($oripath. $oldname, $oripath.$newname.".".$extension);
		
		echo rename($path. $oldname, $path.$newname.".".$extension);
	}
	else
		echo "File Exist, Rename Fail. Please use a different file name";
}

?>