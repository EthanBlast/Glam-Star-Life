function show_feed(template_id,data,callback,user_message_prompt,user_message_txt){
		user_message = {value: user_message_txt};
		FB.Connect.showFeedDialog(template_id, data,null, null, null , FB.RequireConnect.promptConnect,callback);
}
function login_facebook(){
		window.location.reload(true);
	
	}
	
function login_facebook2(){
	jQuery(document).ready(function($) {
		var fbstatusform = $('#fbstatusform');
		$('#fbconnect_feed').load(fbstatusform[0].action+'?checklogin=true');
	});
}

function login_facebook3(urlajax){
	jQuery(document).ready(function($) {
		$('.fbconnect_login_div').load(urlajax+'?checklogin=true&refreshpage=fbconnect_refresh');
	});
}

function login_facebookForm(){
	jQuery(document).ready(function($) {
		$('#fbconnect_reload2').show(); 
		var fbstatusform = $('#fbstatusform');	
		$('#fbresponse').load(fbstatusform[0].action+'?checklogin=true&login_mode=themeform');
	});
}

function login_facebookNoRegForm(){
	jQuery(document).ready(function($) {
		$('#fbconnect_reload2').show(); 
		$('#fbloginbutton').hide(); 
		var fbstatusform = $('#fbstatusform');	
		$('#fbresponse').load(fbstatusform[0].action+'?checklogin=true&login_mode=themeform&hide_regform=true');
	});
}
	
function verify(url, text){
		if (text=='')
			text='Are you sure you want to delete this comment?';
		if (confirm(text)){
			document.location = url;
		}
		return void(0);
	}
// setup everything when document is ready
var fb_statusperms = false;	

function facebook_prompt_permission(permission, callbackFunc) {
    //check is user already granted for this permission or not
    FB.Facebook.apiClient.users_hasAppPermission(permission,
     function(result) {
        // prompt offline permission
        if (result == 0) {
            // render the permission dialog
            FB.Connect.showPermissionDialog(permission, callbackFunc,true);
        } else {
            // permission already granted.
			fb_statusperms = true;
            callbackFunc(true);
        }
    });
}

function callback_perms(){
	
	window.location.reload()
}

function facebook_prompt_stream_permission(callback_perms){
    //check is user already granted for this permission or not
	FB.Facebook.apiClient.users_hasAppPermission('read_stream',
     function(result) {
        // prompt offline permission
        if (result == 0) {
            // render the permission dialog
            FB.Connect.showPermissionDialog('publish_stream,read_stream',callback_perms,true );
        } 
    });	
} 
function facebook_prompt_mail_permission(){
    //check is user already granted for this permission or not
	FB.Facebook.apiClient.users_hasAppPermission('email',
     function(result) {
        // prompt offline permission
        if (result == 0) {
            // render the permission dialog
            FB.Connect.showPermissionDialog('email',callback_perms );
        } 
    });	
}

function fb_showTab(tabName){
	document.getElementById("fbFirstA").className = '';
	document.getElementById("fbSecondA").className = '';
	document.getElementById("fbThirdA").className = '';
	
	document.getElementById("fbFirst").style.visibility = 'hidden';
	document.getElementById("fbSecond").style.visibility = 'hidden';
	document.getElementById("fbThird").style.visibility = 'hidden';
	document.getElementById("fbFirst").style.display = 'none';
	document.getElementById("fbSecond").style.display = 'none';
	document.getElementById("fbThird").style.display = 'none';
	document.getElementById(tabName).style.visibility = 'visible';
	document.getElementById(tabName).style.display = 'block';
	document.getElementById(tabName+'A').className = 'selected';
	return false;
}

function fb_showTabComments(tabName){
	document.getElementById("fbFirstCommentsA").className = '';
	document.getElementById("fbSecondCommentsA").className = '';
	
	document.getElementById("fbFirstComments").style.visibility = 'hidden';
	document.getElementById("fbSecondComments").style.visibility = 'hidden';
	document.getElementById("fbFirstComments").style.display = 'none';
	document.getElementById("fbSecondComments").style.display = 'none';
	document.getElementById(tabName).style.visibility = 'visible';
	document.getElementById(tabName).style.display = 'block';
	document.getElementById(tabName+'A').className = 'selected';
	return false;
}

function fb_show(idname){
	document.getElementById(idname).style.visibility = 'visible';
	document.getElementById(idname).style.display = 'block';
}
function fb_hide(idname){
	document.getElementById(idname).style.visibility = 'hidden';
	document.getElementById(idname).style.display = 'none';
}	
function fb_showComments(tabName){
	document.getElementById("fbAllFriendsComments").style.visibility = 'hidden';
	document.getElementById("fbAllComments").style.visibility = 'hidden';
	document.getElementById("fbAllFriendsComments").style.display = 'none';
	document.getElementById("fbAllComments").style.display = 'none';
	document.getElementById("fbAllFriendsCommentsA").className = '';
	document.getElementById("fbAllCommentsA").className = '';
	document.getElementById(tabName).style.visibility = 'visible';
	document.getElementById(tabName).style.display = 'block';
	document.getElementById(tabName+'A').className = 'selected';
	return false;
}
function pinnedChange(){
	if (document.getElementById('fbconnect_widget_div').className == "") {
		document.getElementById('fbconnect_widget_div').className = "pinned";
	}else{
		document.getElementById('fbconnect_widget_div').className = "";
	}
}

function showCommentsLogin(){
	var comment_form = document.getElementById('commentform');
	if (!comment_form) {
		return;
	}

	commentslogin = document.getElementById('fbconnect_commentslogin');
	var firstChild = comment_form.firstChild;
    comment_form.insertBefore(commentslogin, firstChild);
	//comment_form.appendChild(commentslogin);
}

function readUserData(){
	var api = FB.Facebook.apiClient;  
	
	api.fql_query("SELECT name,pic,email,username,website,about_me,proxied_email FROM user WHERE uid="+fb_userid, function(result, ex) {  	   
	if (result) {
		for (i = 0; i < result.length; i++) {
		
			if (document.getElementById("email").value == "") {
				if (result[i].email != "") {
					document.getElementById("email").value = result[i].email;
				}
				else 
					if (result[i].proxied_email != "") {
						document.getElementById("email").value = result[i].proxied_email;
					}
			}
		}
	}

   });			

}

function readcomments(xid,divid){
	var api = FB.Facebook.apiClient;  

	//uid=FB.Facebook.apiClient.get_session().uid;
	//api.fql_query("select xid,object_id,post_id,fromid,time,text,id,username,reply_xid from comment where post_id IN (select post_id from stream where source_id="+uidstream+" LIMIT 10)", function(result, ex) {  	   
	api.fql_query("select xid,object_id,post_id,fromid,time,text,id,username,reply_xid from comment where xid="+xid+" LIMIT 10", function(result, ex) {  	   
	//api.fql_query("SELECT name,pic FROM user WHERE uid=719970963", function(result, ex) {  	   
		var comments="<div><ul>";
		for (i=0;i<result.length;i++)  
		{	alert(result);			
			//resultado2=resultado2 + result[i].first_name + " " + result[i].last_name + "|" + result[i].uid + "\n";
			//alert(result[i].birthday + " " + result[i].birthday_date);
			comments = comments + "<li>"+result[i].fromid + "<br/>" + result[i].text + "</li>\n";
		}	
		comments= comments +"</ul></div>";	
		alert(comments);
		document.getElementById(divid).innerHTML = comments;	
		FB.XFBML.Host.parseDomTree();

   });			

}
