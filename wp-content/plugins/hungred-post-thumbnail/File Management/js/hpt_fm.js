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
var currentObj = null;
var fail = '';
var display_menu = function()
{
	jQuery('#upload-form').stop().animate({"top": "0px"}, 1000, function(){
	jQuery('#arrow').css({"background":"transparent url('images/up.png')  no-repeat 100% 100%"});
	});
}
var hide_menu = function()
{
	jQuery('#upload-form').stop().animate({"top":"-"+jQuery('#upload-form').height()+"px"}, 1000, function(){
	jQuery('#arrow').css({"background":"transparent url('images/down.png') no-repeat 100% 100%"});
	});
}
jQuery(document).ready(function() {
	attach_handler();
	jQuery('#arrow').toggle(display_menu, hide_menu);
});
/*
Name: attach_handler
Usage: use when the element first initialize
Parameter: 	NONE
Description: this method attach event handler to each element
*/
function attach_handler()
{
	jQuery('.box').click(function(){
		currentObj = this;
		jQuery('#screen').css({"opacity": 0,"width": jQuery(document).width(), "height": jQuery(document).height(), "display":"block"}).animate({opacity: 0.7},500, function(){jQuery('#navbox').css("display", "block");});
	});
}
/*
Name: cancel
Usage: use when want to cancel button is clicked
Parameter: 	NONE
Description: this method hide all boxes and grey effect
*/
function cancel()
{
	hideAllBox();
	appear();
}
/*
Name: appear
Usage: use when want to clear away the grey effect
Parameter: 	NONE
Description: this method hide the grey effect
*/
function appear()
{
	jQuery('#screen').animate({"opacity": 0},1000).css({"display":"none"});
}
/*
Name: renameBox
Usage: use when rename button is clicked
Parameter: 	NONE
Description: this method display the rename box pop out
*/
function renameBox()
{
	hideAllBox();
	oldname= getSelectedFileName();
	displayname = oldname.split('.');
	jQuery('#input_rename').attr('value', displayname[0]);
	jQuery('#renamebox').css("display", "block");
}
/*
Name: messagebox
Usage: use when remove button is clicked
Parameter: 	NONE
Description: this method display the remove box pop out
*/
function deleteBox()
{
	hideAllBox();
	jQuery('#deletebox').css("display", "block");
}
/*
Name: messagebox
Usage: use when certain action is completed
Parameter: 	NONE
Description: this method display the message box pop out
*/
function messagebox()
{
	hideAllBox();
	jQuery('#messagebox').css("display", "block");
}
/*
Name: okbox
Usage: use when certain action is completed
Parameter: 	NONE
Description: this method display the ok button pop out
*/
function okbox()
{
	hideAllBox();
	jQuery('#okbox').css("display", "block");
}
/*
Name: getSelectedFileName
Usage: get the name located in the soure
Parameter: 	NONE
Description: this initial rename and do validation checking
*/
function getSelectedFileName()
{
	oldname =  jQuery(currentObj).children('img')[0];
	oldname = jQuery(oldname).attr('title');
		return oldname;
}

/*
Name: getFolderLocation
Usage: get the location folder of the image file
Parameter: 	NONE
Description: it will return the folder location
*/
function getFolderLocation()
{
	oldname =  jQuery(currentObj).children('img')[0];
	src = jQuery(oldname).attr('src');
	name = jQuery(oldname).attr('title');
	src = src.replace(name, '');
	return src;
}
/*
Name: rename_confirm
Usage: use when the user clicked rename button
Parameter: 	NONE
Description: this initial rename and do validation checking
*/
function rename_confirm()
{
	var available = true;
	var newname = jQuery('#input_rename').attr("value");
	var rename = document.getElementById('input_rename');
	if(isAllowedName(rename,"Found Invalid Character! Rename Failed. Only symbol '-' or '_' allowed"))
	{
		jQuery('#body').contents().find('img').each(function(){
		oldname = jQuery(this).attr('title').split(".");

		if((oldname[0]).toLowerCase() == newname.toLowerCase())
		{
			available=false;
			return;
		}
		});
		if(available)
		{
			oldname = getSelectedFileName();
			ajaxCall('R', oldname,newname);
		}
		else
			alert("The name has been taken. Please choose another name");
	}
}
/*
Name: delete_confirm
Usage: use when the user clicked remove button
Parameter: 	NONE
Description: this initial delete
*/
function delete_confirm()
{
	filename = getSelectedFileName();
	ajaxCall('D', filename,'');
	
}
/*
Name: retry
Usage: use when the user clicked retry button
Parameter: 	NONE
Description: this check what failed previously and call the appropriate method again
*/
function retry()
{
	fail == 'D'?delete_confirm():rename_confirm();
}
/*
Name: ajaxCall
Usage: use to call the server scripts
Parameter: 	calller: determine who are the caller
			nameold: the old file name
			namenew: the new file name
Description: all the server with client operation is done in this small method
*/
function ajaxCall(caller, nameold, namenew)
{
	jQuery.post("hpt_operate.php", { op: caller, oldname: nameold, newname: namenew }, function(data){
		if(caller=='D')
		{
			if(data == "1")
			{
				
				jQuery("#okmsg").html("Delete Successful");
				jQuery(currentObj).remove();
				okbox();
				
			}
			else
			{
				fail = 'D';
				jQuery("#errormsg").html("Delete has fail, Please try again later or contact <a href='clay@hungred.com'>Clay</a>");
				messagebox();
			}
		}
		else if(caller=='R')
		{
			data = data.split("||");
			if(data[1] == "1")
			{
				jQuery("#okmsg").html("Rename Successful");
				var domain = getFolderLocation()+data[0];
				jQuery(currentObj).parent().append("<div class='box'><img title='"+data[0]+"' src='"+domain+"'/></div>");
				jQuery('#input_rename').attr('value','');
				jQuery(currentObj).attr('title', namenew);
				jQuery(currentObj).remove();
				attach_handler();
				okbox();
				
			}
			else
			{
				fail = 'R';
				jQuery("#errormsg").html(data);
				messagebox();
			}
		}
  });
}
/*
Name: hideAllBox
Usage: use to hide all the element
Parameter: 	NONE
Description: use to hide all the pop out box from appearing
*/
function hideAllBox()
{
	jQuery('#renamebox').css("display", "none");
	jQuery('#deletebox').css("display", "none");
	jQuery('#messagebox').css("display", "none");
	jQuery('#navbox').css("display", "none");
	jQuery('#okbox').css("display", "none");
}
/*
Name: confirm_cancel
Usage: when user click confirm cancel
Parameter: 	NONE
Description: this is call when user clicked cancel
*/
function confirm_cancel()
{
	hideAllBox();
	jQuery('#navbox').css("display", "block");
}

/*
Name: isAllowedName
Usage: use to validate space color and space border color text box
Parameter: 	elem: the DOM object of each element
			helperMsg: the pop out box message
Description: This is a simple method to check whether a given text box string contains 
			 all type of characters except symbols excluding '#'
*/
function isAllowedName(elem, helperMsg){
	var alphaExp = /^[-_0-9a-zA-Z]+$/;
	if(elem.value.toLowerCase().match(alphaExp)){
		return true;
	}else{
		alert(helperMsg);
		elem.focus();
		return false;
	}
}
