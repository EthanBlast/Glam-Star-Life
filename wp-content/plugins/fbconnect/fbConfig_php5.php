<?php
/**
 * @author: Javier Reyes Gomez (http://www.sociable.es)
 * @date: 05/10/2008
 * @license: GPLv2
 */
function fb_get_session(){
	WPfbConnect::log("[fbConfig_php5::fb_get_session]",FBCONNECT_LOG_DEBUG);	
	try{
		$fbclient = & facebook_client();
		if ($fbclient){
			$fbapi_client = & $fbclient->api_client;
			return $fbapi_client->session_key;
		}
	}catch (FacebookRestClientException $e) {
		WPfbConnect::log("[fbConfig_php5::fb_get_session] [Line: ".$e->getLine()."] [Code: ".$e->getCode()."] [MSG:".$e->getMessage()."]",FBCONNECT_LOG_ERR);
		WPfbConnect::log("[fbConfig_php5::fb_get_session] ".print_r($e,true),FBCONNECT_LOG_DEBUG);
		return "ERROR";
	}
	return null;
}

function fb_fbml_refreshRefUrl($url){
	WPfbConnect::log("[fbConfig_php5::fb_fbml_refreshRefUrl] url: ".$url,FBCONNECT_LOG_DEBUG);	
	try{
		$fbclient = & facebook_client();
		if ($fbclient){
			$fbapi_client = & $fbclient->api_client;
			return $fbapi_client->fbml_refreshRefUrl($url);
		}
	}catch (FacebookRestClientException $e) {
		WPfbConnect::log("[fbConfig_php5::fb_users_hasAppPermission] [Line: ".$e->getLine()."] [Code: ".$e->getCode()."] [MSG:".$e->getMessage()."]",FBCONNECT_LOG_ERR);
		WPfbConnect::log("[fbConfig_php5::fb_users_hasAppPermission] url: ".$url,FBCONNECT_LOG_ERR);
		WPfbConnect::log("[fbConfig_php5::fb_users_hasAppPermission] ".print_r($e,true),FBCONNECT_LOG_DEBUG);
		return "ERROR";
	}
	return null;
}
function fb_profile_setFBML($markup, $uid=null, $profile='', $profile_action='', $mobile_profile='', $profile_main='') {
	WPfbConnect::log("[fbConfig_php5::fb_profile_setFBML] UID: ".$uid,FBCONNECT_LOG_DEBUG);	
	try{
		$fbclient = & facebook_client();
		if ($fbclient){
			$fbapi_client = & $fbclient->api_client;
			return $fbapi_client->profile_setFBML($markup, $uid, $profile, $profile_action, $mobile_profile, $profile_main);
		}
	}catch (FacebookRestClientException $e) {
		WPfbConnect::log("[fbConfig_php5::fb_users_hasAppPermission] [Line: ".$e->getLine()."] [Code: ".$e->getCode()."] [MSG:".$e->getMessage()."]",FBCONNECT_LOG_ERR);
		WPfbConnect::log("[fbConfig_php5::fb_users_hasAppPermission] uid: ".$uid,FBCONNECT_LOG_ERR);
		WPfbConnect::log("[fbConfig_php5::fb_users_hasAppPermission] ".print_r($e,true),FBCONNECT_LOG_DEBUG);
		return "ERROR";
	}
	return null;
							
}
						   
function fb_get_userPrmisions($fb_user){
	$perms = fb_fql_query("SELECT uid,status_update,photo_upload,sms,publish_stream,offline_access,email,create_event,rsvp_event,read_stream,share_item,create_note,bookmarked FROM permissions  WHERE uid ='".$fb_user."'");
	if ($perms!="" && $perms!="ERROR" && count($perms>0)){
		return $perms[0];
	}else{
		return $perms;
	}
}

function fb_get_objectinfo($pageurl){
	$resp = fb_fql_query("select url, id,type,site from object_url where url='$pageurl'");
	return $resp;
}
function fb_get_friends($fb_user){
	$friends = fb_fql_query("SELECT uid2 FROM friend  WHERE uid1 ='".$fb_user."'");
	return $friends;
}

function fb_users_hasAppPermission($ext_perm, $uid=null) {
	WPfbConnect::log("[fbConfig_php5::fb_users_hasAppPermission] PERMS: ".$ext_perm,FBCONNECT_LOG_DEBUG);	
	try{
		$fbclient = & facebook_client();
		if ($fbclient){
			$fbapi_client = & $fbclient->api_client;
			return $fbapi_client->users_hasAppPermission($ext_perm, $uid);
		}
	}catch (FacebookRestClientException $e) {
		WPfbConnect::log("[fbConfig_php5::fb_users_hasAppPermission] [Line: ".$e->getLine()."] [Code: ".$e->getCode()."] [MSG:".$e->getMessage()."]",FBCONNECT_LOG_ERR);
		WPfbConnect::log("[fbConfig_php5::fb_users_hasAppPermission] PERMS: ".$ext_perm,FBCONNECT_LOG_ERR);
		WPfbConnect::log("[fbConfig_php5::fb_users_hasAppPermission] ".print_r($e,true),FBCONNECT_LOG_DEBUG);
		return "ERROR";
	}
	return null;	
}

function fb_admin_setRestrictionInfo($restriction_info = null){
	WPfbConnect::log("[fbConfig_php5::fb_admin_setRestrictionInfo] ",FBCONNECT_LOG_DEBUG);	
	try{
		$fbclient = & facebook_client();
		if ($fbclient){
			$fbapi_client = & $fbclient->api_client;
			return $fbapi_client->admin_setRestrictionInfo($restriction_info);
		}
	}catch (FacebookRestClientException $e) {
		echo "ERROR";
		WPfbConnect::log("[fbConfig_php5::fb_admin_setRestrictionInfo] [Line: ".$e->getLine()."] [Code: ".$e->getCode()."] [MSG:".$e->getMessage()."]",FBCONNECT_LOG_ERR);
		return "ERROR";
	}
	return null;
}

function fb_admin_getRestrictionInfo(){
	WPfbConnect::log("[fbConfig_php5::fb_admin_getRestrictionInfo] ",FBCONNECT_LOG_DEBUG);	
	try{
		$fbclient = & facebook_client();
		if ($fbclient){
			$fbapi_client = & $fbclient->api_client;
			return $fbapi_client->admin_getRestrictionInfo();
		}
	}catch (FacebookRestClientException $e) {
		echo "ERROR";
		WPfbConnect::log("[fbConfig_php5::fb_admin_getRestrictionInfo] [Line: ".$e->getLine()."] [Code: ".$e->getCode()."] [MSG:".$e->getMessage()."]",FBCONNECT_LOG_ERR);
		return "ERROR";
	}
	return null;	
}
	
function fb_get_action_links_url(){
	$action_links = array(array('text' => 'Read more...', 'href' => 'http://www.sociable.es'));
}

function fb_manualInit(){
	if (isset($_REQUEST['fb_sig_user']) && $_REQUEST['fb_sig_user']!="" && isset($_REQUEST['fb_sig_session_key']) && $_REQUEST['fb_sig_session_key']!=""){
		fb_set_user($_REQUEST['fb_sig_user'], $_REQUEST['fb_sig_session_key']);
	}
}

function fb_notifications_send($to_ids, $notification, $type) {
	WPfbConnect::log("[fbConfig_php5::fb_notifications_send] TOIDS: ".$to_ids,FBCONNECT_LOG_DEBUG);	
	try{
			$fbclient = & facebook_mobile_client();
			if ($fbclient){
				$fbapi_client = & $fbclient->api_client;
				return $fbapi_client->notifications_send($to_ids, $notification, $type);
			}
	}catch (FacebookRestClientException $e) {
		WPfbConnect::log("[fbConfig_php5::fb_notifications_send] [Line: ".$e->getLine()."] [Code: ".$e->getCode()."] [MSG:".$e->getMessage()."]",FBCONNECT_LOG_ERR);
		WPfbConnect::log("[fbConfig_php5::fb_notifications_send] TOIDS: ".$to_ids,FBCONNECT_LOG_ERR);
		WPfbConnect::log("[fbConfig_php5::fb_notifications_send] ".print_r($e,true),FBCONNECT_LOG_DEBUG);
		return "ERROR";
	}
	return null;
}

function fb_notifications_sendEmail($recipients,$subject,$text,$fbml){
	WPfbConnect::log("[fbConfig_php5::fb_notifications_sendEmail] Recipients: ".$recipients,FBCONNECT_LOG_DEBUG);
	try{
			$fbclient = & facebook_mobile_client();
			if ($fbclient){
				$fbapi_client = & $fbclient->api_client;
				return $fbapi_client->notifications_sendEmail($recipients,$subject,$text,$fbml);
			}
	}catch (FacebookRestClientException $e) {
		WPfbConnect::log("[fbConfig_php5::fb_notifications_sendEmail] [Line: ".$e->getLine()."] [Code: ".$e->getCode()."] [MSG:".$e->getMessage()."]",FBCONNECT_LOG_ERR);
		WPfbConnect::log("[fbConfig_php5::fb_notifications_sendEmail] Recipients: ".$recipients,FBCONNECT_LOG_ERR);
		WPfbConnect::log("[fbConfig_php5::fb_notifications_sendEmail] ".print_r($e,true),FBCONNECT_LOG_DEBUG);
		return "ERROR";
	}
	return null;
}

										   	
function fb_render_prompt_feed_url($action_links=NULL,
                                         $target_id=NULL,
                                         $message='',
                                         $user_message_prompt='',
                                         $caption=NULL,
                                         $callback ='',
                                         $cancel='',
                                         $attachment=NULL,
                                         $preview=true){

	try{
		$fbclient = & facebook_mobile_client();
		if ($fbclient){
			return $fbclient->render_prompt_feed_url($action_links,$target_id,$message,$user_message_prompt,$caption,$callback,$cancel,$attachment,$preview);
		}
	}catch (FacebookRestClientException $e) {
		return "ERROR";
		//echo "Facebook connect error:".$e->getCode();
		//print_r($e);
	}
	return null;
                                         	
}

function fb_get_fbconnect_tos_url() {
	WPfbConnect::log("[fbConfig_php5::fb_get_fbconnect_tos_url] ",FBCONNECT_LOG_DEBUG);
	try{
		$fbclient = & facebook_mobile_client();
		if ($fbclient){
			return $fbclient->get_fbconnect_tos_url();
		}
	}catch (FacebookRestClientException $e) {
		WPfbConnect::log("[fbConfig_php5::fb_get_fbconnect_tos_url] [Line: ".$e->getLine()."] [Code: ".$e->getCode()."] [MSG:".$e->getMessage()."]",FBCONNECT_LOG_ERR);
		WPfbConnect::log("[fbConfig_php5::fb_get_fbconnect_tos_url] ".print_r($e,true),FBCONNECT_LOG_DEBUG);
		return "ERROR";
	}
	return null;
	
}


function fb_pages_isFan($page_id, $uid = null) {
	WPfbConnect::log("[fbConfig_php5::fb_pages_isFan] PageId: ".$page_id." UID:".$uid,FBCONNECT_LOG_DEBUG);
	try{
		$fbclient = & facebook_client();
		if ($fbclient){
			$fbapi_client = & $fbclient->api_client;
			return $fbapi_client->pages_isFan($page_id, $uid);
		}
	}catch (FacebookRestClientException $e) {
		WPfbConnect::log("[fbConfig_php5::fb_pages_isFan] [Line: ".$e->getLine()."] [Code: ".$e->getCode()."] [MSG:".$e->getMessage()."]",FBCONNECT_LOG_ERR);
		WPfbConnect::log("[fbConfig_php5::fb_pages_isFan] PageId: ".$page_id." UID:".$uid,FBCONNECT_LOG_ERR);
		WPfbConnect::log("[fbConfig_php5::fb_pages_isFan] ".print_r($e,true),FBCONNECT_LOG_DEBUG);
		return "ERROR";
	}
	return null;
	
}
function fb_set_user($user, $session_key, $expires=null, $session_secret=null) {
	WPfbConnect::log("[fbConfig_php5::fb_set_user] User: ".$user,FBCONNECT_LOG_DEBUG);
	try{
		$fbclient = & facebook_client();
		if ($fbclient){
			return $fbclient->set_user($user, $session_key, $expires, $session_secret);
		}
	}catch (FacebookRestClientException $e) {
		WPfbConnect::log("[fbConfig_php5::fb_set_user] [Line: ".$e->getLine()."] [Code: ".$e->getCode()."] [MSG:".$e->getMessage()."]",FBCONNECT_LOG_ERR);
		WPfbConnect::log("[fbConfig_php5::fb_set_user] User: ".$user,FBCONNECT_LOG_ERR);
		WPfbConnect::log("[fbConfig_php5::fb_set_user] ".print_r($e,true),FBCONNECT_LOG_DEBUG);
		return "ERROR";
	}
	return null;
	
}
function fb_data_setCookie($uid, $name, $value, $expires, $path) {
	WPfbConnect::log("[fbConfig_php5::fb_data_setCookie] UID: ".$uid." NAME:".$name,FBCONNECT_LOG_DEBUG);
	try{
		$fbclient = & facebook_client();
		if ($fbclient){
			$fbapi_client = & $fbclient->api_client;
			return $fbapi_client->data_setCookie($uid, $name, $value, $expires, $path);
		}
	}catch (FacebookRestClientException $e) {
		WPfbConnect::log("[fbConfig_php5::fb_data_setCookie] [Line: ".$e->getLine()."] [Code: ".$e->getCode()."] [MSG:".$e->getMessage()."]",FBCONNECT_LOG_ERR);
		WPfbConnect::log("[fbConfig_php5::fb_data_setCookie] UID: ".$uid." NAME:".$name,FBCONNECT_LOG_ERR);
		WPfbConnect::log("[fbConfig_php5::fb_data_setCookie] ".print_r($e,true),FBCONNECT_LOG_DEBUG);
		return "ERROR";
	}
	return null;

}
 	
function fb_data_getCookies($uid, $name) {
	WPfbConnect::log("[fbConfig_php5::fb_data_getCookies] UID: ".$uid." NAME:".$name,FBCONNECT_LOG_DEBUG);
	try{
		$fbclient = & facebook_client();
		if ($fbclient){
			$fbapi_client = & $fbclient->api_client;
			return $fbapi_client->data_getCookies($uid, $name);
		}
	}catch (FacebookRestClientException $e) {
		WPfbConnect::log("[fbConfig_php5::fb_data_getCookies] [Line: ".$e->getLine()."] [Code: ".$e->getCode()."] [MSG:".$e->getMessage()."]",FBCONNECT_LOG_ERR);
		WPfbConnect::log("[fbConfig_php5::fb_data_getCookies] UID: ".$uid." NAME:".$name,FBCONNECT_LOG_ERR);
		WPfbConnect::log("[fbConfig_php5::fb_data_getCookies] ".print_r($e,true),FBCONNECT_LOG_DEBUG);
		return "ERROR";
	}
	return null;

}
function fb_require_login($perms=""){
	WPfbConnect::log("[fbConfig_php5::fb_require_login] ",FBCONNECT_LOG_DEBUG);
	try{
		$fbclient = & facebook_client();
		if ($fbclient)
			return $fbclient->require_login($perms);
	}catch (FacebookRestClientException $e) {
		WPfbConnect::log("[fbConfig_php5::fb_require_login] [Line: ".$e->getLine()."] [Code: ".$e->getCode()."] [MSG:".$e->getMessage()."]",FBCONNECT_LOG_ERR);
		WPfbConnect::log("[fbConfig_php5::fb_require_login] ".print_r($e,true),FBCONNECT_LOG_DEBUG);
		return "ERROR";
	}
	return null;
}

function fb_getParams(){
	WPfbConnect::log("[fbConfig_php5::fb_getParams] ",FBCONNECT_LOG_DEBUG);
	try{
		$fbclient = & facebook_client();
		if ($fbclient){
			return $fbclient->fb_params;
		}
	}catch (FacebookRestClientException $e) {
		WPfbConnect::log("[fbConfig_php5::fb_getParams] [Line: ".$e->getLine()."] [Code: ".$e->getCode()."] [MSG:".$e->getMessage()."]",FBCONNECT_LOG_ERR);
		WPfbConnect::log("[fbConfig_php5::fb_getParams] ".print_r($e,true),FBCONNECT_LOG_DEBUG);
		return "ERROR";
	}
	return null;
}

function fb_get_loggedin_user() {
	WPfbConnect::log("[fbConfig_php5::fb_get_loggedin_user] ",FBCONNECT_LOG_DEBUG);
	try{
		$fbclient = & facebook_client();
		if ($fbclient)
			return $fbclient->get_loggedin_user();
	}catch (FacebookRestClientException $e) {
		WPfbConnect::log("[fbConfig_php5::fb_get_loggedin_user] [Line: ".$e->getLine()."] [Code: ".$e->getCode()."] [MSG:".$e->getMessage()."]",FBCONNECT_LOG_ERR);
		WPfbConnect::log("[fbConfig_php5::fb_get_loggedin_user] ".print_r($e,true),FBCONNECT_LOG_DEBUG);
		return "ERROR";
	}
	return null;
}

function fb_user_getInfo($fb_user) {
	WPfbConnect::log("[fbConfig_php5::fb_user_getInfo] UID: ".$fb_user,FBCONNECT_LOG_DEBUG);
	try{
		$fbclient = & facebook_client();
		if ($fbclient){
			$fbapi_client = & $fbclient->api_client;
			$userinfo = $fbapi_client->users_getInfo($fb_user, "username,website,about_me,email,proxied_email,profile_url,name,first_name,last_name,birthday,birthday_date,current_location,locale,sex,pic,pic_with_logo,pic_small,pic_small_with_logo,pic_big_with_logo,pic_big,pic_square,pic_square_with_logo,affiliations,email_hashes,hometown_location,hs_info,education_history,interests,meeting_for,meeting_sex,movies,music,political,profile_update_time,proxied_email,quotes,relationship_status,religion,significant_other_id,timezone,tv,work_history,wall_count");
			if (isset($userinfo[0])){
				return $userinfo[0];
			}else{
				return $userinfo;
			}
		}
	}catch (FacebookRestClientException $e) {
		WPfbConnect::log("[fbConfig_php5::fb_user_getInfo] [Line: ".$e->getLine()."] [Code: ".$e->getCode()."] [MSG:".$e->getMessage()."]",FBCONNECT_LOG_ERR);
		WPfbConnect::log("[fbConfig_php5::fb_user_getInfo] UID: ".$fb_user,FBCONNECT_LOG_ERR);
		WPfbConnect::log("[fbConfig_php5::fb_user_getInfo] ".print_r($e,true),FBCONNECT_LOG_DEBUG);
		return "ERROR";
	}
	return null;
}

function fb_user_getInfoSmall($fb_user) {
	WPfbConnect::log("[fbConfig_php5::fb_user_getInfo] UID: ".$fb_user,FBCONNECT_LOG_DEBUG);
	try{
		$fbclient = & facebook_client();
		if ($fbclient){
			$fbapi_client = & $fbclient->api_client;
			$userinfo = $fbapi_client->users_getInfo($fb_user, "username,website,about_me,email,proxied_email,profile_url,name,first_name,last_name,birthday,birthday_date,current_location,locale,sex,pic,pic_with_logo,pic_small,pic_small_with_logo,pic_big_with_logo,pic_big,pic_square,pic_square_with_logo,affiliations,email_hashes,hometown_location,hs_info,education_history,interests,meeting_for,meeting_sex,movies,music,political,profile_update_time,proxied_email,quotes,relationship_status,religion,significant_other_id,timezone,tv,work_history,wall_count");
			if (isset($userinfo[0])){
				return $userinfo[0];
			}else{
				return $userinfo;
			}
		}
	}catch (FacebookRestClientException $e) {
		WPfbConnect::log("[fbConfig_php5::fb_user_getInfo] [Line: ".$e->getLine()."] [Code: ".$e->getCode()."] [MSG:".$e->getMessage()."]",FBCONNECT_LOG_ERR);
		WPfbConnect::log("[fbConfig_php5::fb_user_getInfo] UID: ".$fb_user,FBCONNECT_LOG_ERR);
		WPfbConnect::log("[fbConfig_php5::fb_user_getInfo] ".print_r($e,true),FBCONNECT_LOG_DEBUG);
		return "ERROR";
	}
	return null;
}

function fb_feed_getRegisteredTemplateBundles() {
	try{
		$fbclient = & facebook_client();
		if ($fbclient){
			$fbapi_client = & $fbclient->api_client;
			return $fbapi_client->feed_getRegisteredTemplateBundles();
		}
	}catch (FacebookRestClientException $e) {
		return "ERROR";
		//echo "Facebook connect error:".$e->getCode();
		//print_r($e);
	}
	return null;
}

function fb_feed_registerTemplateBundle($one_line_stories,$short_stories,$full_stories){
	try{
		$fbclient = & facebook_client();
		if ($fbclient){
			$fbapi_client = & $fbclient->api_client;
			return $fbapi_client->feed_registerTemplateBundle($one_line_stories,$short_stories,$full_stories);
		}
	}catch (FacebookRestClientException $e) {
		return "ERROR";
		//echo "Facebook connect error:".$e->getCode();
		//print_r($e);
	}
	return null;
}

function fb_feed_deactivateTemplateBundleByID($templateID){
	 try{
		$fbclient = & facebook_client();
		if ($fbclient){		
			$fbapi_client = & $fbclient->api_client;
			$fbapi_client->feed_deactivateTemplateBundleByID($templateID);
		}
	}catch (FacebookRestClientException $e) {
		return "ERROR";
		//echo "Facebook connect error:".$e->getCode();
		//print_r($e);
	}
	return null;	
}

function fb_feed_getRegisteredTemplateBundleByID($templateID){
	 try{
		$fbclient = & facebook_client();
		if ($fbclient){		
			$fbapi_client = & $fbclient->api_client;
			return $fbapi_client->feed_getRegisteredTemplateBundleByID($templateID);
		}
	}catch (FacebookRestClientException $e) {
		return "ERROR";
		//echo "Facebook connect error:".$e->getCode();
		//print_r($e);
	}
	return null;
}

function fb_stream_posts($uids,$fromdate=null){
	if (isset($fromdate) && $fromdate!=""){
		$datefilter="AND updated_time > ".$fromdate;
	}
	return fb_fql_query("SELECT post_id, actor_id, target_id, message,attachment,likes,updated_time,permalink FROM stream WHERE source_id IN (".$uids.") ".$datefilter);
}
function fb_stream_comments($uid){
	return fb_fql_query("select xid,object_id,post_id,fromid,time,text,id,username,reply_xid from comment where post_id IN (select post_id from stream where source_id=$uid LIMIT 10)");
}

function fb_stream_comments_xid($xid){
	return fb_fql_query("select xid,object_id,post_id,fromid,time,text,id,username,reply_xid from comment where xid=$xid LIMIT 10");
}


function fb_get_user_pages($uid,$order="name"){
	return fb_fql_query("select page_id,name,type,pic_small,pic_big,pic_square,pic,pic_large,page_url,fan_count  from page where page_id IN (select page_id from page_fan where uid=$uid) order by ".$order);
}

function fb_get_user_pages_ids($uid){
	return fb_fql_query("select page_id from page_fan where uid=$uid");
}

function fb_get_page_info($pageid){
	return fb_fql_query("select page_id,username,general_info,name,bio,company_overview ,website,type,pic_small,pic_big,pic_square,pic,pic_large,page_url,location,fan_count  from page where page_id IN(".$pageid.")");
}

function fb_pages_getInfo($page_ids, $fields=null, $uid=null, $type=null){
/*	if ($fields==null){
		$fields = array("page_id","name","type","pic_small","pic_big","pic_square","pic","pic_large","page_url","fan_count");
	}*/
	WPfbConnect::log("[fbConfig_php5::fb_pages_getInfo] PageIDs: ".print_r($page_ids,true),FBCONNECT_LOG_DEBUG);
  	try{
		$fbclient = & facebook_client();
		if ($fbclient){		
			$fbapi_client = & $fbclient->api_client;
			return $fbapi_client->pages_getInfo($page_ids, $fields, $uid, $type);
		}
	}catch (FacebookRestClientException $e) {
		//print_r($e);
		WPfbConnect::log("[fbConfig_php5::fb_pages_getInfo] ".$e->getCode(),FBCONNECT_LOG_ERR);
		WPfbConnect::log("[fbConfig_php5::fb_pages_getInfo] ".print_r($e,true),FBCONNECT_LOG_DEBUG);

		return "ERROR";
		
		//echo "Facebook connect error:".$e->getCode();
	}
	return null;
}
	
function fb_fql_query($query){
	WPfbConnect::log("[fbConfig_php5::fb_fql_query] Query: ".$query,FBCONNECT_LOG_DEBUG);
  	try{
		$fbclient = & facebook_client();
		if ($fbclient){		
			$fbapi_client = & $fbclient->api_client;
			return $fbapi_client->fql_query($query);
		}
	}catch (FacebookRestClientException $e) {
		//print_r($e);
		WPfbConnect::log("[fbConfig_php5::fb_fql_query] ".$e->getCode(),FBCONNECT_LOG_ERR);
		WPfbConnect::log("[fbConfig_php5::fb_fql_query] Query: ".$query,FBCONNECT_LOG_ERR);
		WPfbConnect::log("[fbConfig_php5::fb_fql_query] ".print_r($e,true),FBCONNECT_LOG_DEBUG);

		return "ERROR";
		
		//echo "Facebook connect error:".$e->getCode();
	}
	return null;
}
function fb_expire_session(){
	$fbclient = & facebook_client();
	try {
	    $fbclient->expire_session();
	}catch (Exception $e) {
		print_r($e);
	}

      $base_domain_cookie = 'base_domain_' . $fbclient->api_key;
      if (isset($_COOKIE[$base_domain_cookie])) {
        $base_domain = $_COOKIE[$base_domain_cookie];
      }else{
      	$base_domain="";
      }

	//Si se produce un error 102 de sesion invalida no limpia la sesion con exprire_sesion
	//if (!$fbclient->in_fb_canvas() && isset($_COOKIE[$fbclient->api_key . '_user'])) {
        $cookies = array('user', 'session_key', 'expires', 'ss');
        foreach ($cookies as $name) {
          setcookie($fbclient->api_key . '_' . $name, "-", time()-3600);
		  setcookie($fbclient->api_key . '_' . $name, "-", time()-3600, '', $base_domain);
          unset($_COOKIE[$fbclient->api_key . '_' . $name]);
        }
        setcookie($fbclient->api_key, "-", time()-3600);
		setcookie($fbclient->api_key, "-", time()-3600, '', $base_domain);
        unset($_COOKIE[$fbclient->api_key]);
     // }

      $fbclient->user = 0;
      $fbclient->api_client->session_key = 0;
      
}

function fb_feed_publishUserAction($template_data){
	WPfbConnect::log("[fbConfig_php5::fb_feed_publishUserAction] ",FBCONNECT_LOG_DEBUG);
	try {
		$fbclient = & facebook_client();
		if ($fbclient){		
			$fbapi_client = & $fbclient->api_client;
			$feed_bundle_id = get_option('fb_templates_id');
			$fbapi_client->feed_publishUserAction( $feed_bundle_id, 
	                                           fb_json_encode($template_data) , 
	                                           null, 
	                                          null,2);
		}
	}catch (Exception $e) {
		WPfbConnect::log("[fbConfig_php5::fb_feed_publishUserAction] [Line: ".$e->getLine()."] [Code: ".$e->getCode()."] [MSG:".$e->getMessage()."]",FBCONNECT_LOG_ERR);
		WPfbConnect::log("[fbConfig_php5::fb_feed_publishUserAction] ".print_r($e,true),FBCONNECT_LOG_DEBUG);
		return "ERROR";
	}
}

function fb_events_get($uid=null, $eids=null, $start_time=null, $end_time=null, $rsvp_status=null){
	WPfbConnect::log("[fbConfig_php5::fb_events_get] UID: ".$uid." EIDS:".$eids,FBCONNECT_LOG_DEBUG);
	try {
		$fbclient = & facebook_client();
		if ($fbclient){		
			$fbapi_client = & $fbclient->api_client;
			return $fbapi_client->events_get( $uid, $eids, $start_time, $end_time, $rsvp_status);
		}
	}catch (Exception $e) {
		WPfbConnect::log("[fbConfig_php5::fb_events_get] [Line: ".$e->getLine()."] [Code: ".$e->getCode()."] [MSG:".$e->getMessage()."]",FBCONNECT_LOG_ERR);
		WPfbConnect::log("[fbConfig_php5::fb_events_get] UID: ".$uid." EIDS:".$eids,FBCONNECT_LOG_ERR);
		WPfbConnect::log("[fbConfig_php5::fb_events_get] ".print_r($e,true),FBCONNECT_LOG_DEBUG);
		return "ERROR";
	}
}

function fb_photos_getAlbums($uid=null, $aids=null) {
	WPfbConnect::log("[fbConfig_php5::fb_photos_getAlbums] UID: ".$uid." AIDS:".$aids,FBCONNECT_LOG_DEBUG);
	try {
		$fbclient = & facebook_client();
		if ($fbclient){		
			$fbapi_client = & $fbclient->api_client;
			return $fbapi_client->photos_getAlbums($uid, $aids);
		}
	}catch (Exception $e) {
		WPfbConnect::log("[fbConfig_php5::fb_photos_getAlbums] [Line: ".$e->getLine()."] [Code: ".$e->getCode()."] [MSG:".$e->getMessage()."]",FBCONNECT_LOG_ERR);
		WPfbConnect::log("[fbConfig_php5::fb_photos_getAlbums] UID: ".$uid." AIDS:".$aids,FBCONNECT_LOG_ERR);
		WPfbConnect::log("[fbConfig_php5::fb_photos_getAlbums] ".print_r($e,true),FBCONNECT_LOG_DEBUG);
		return "ERROR";
	}

}

function fb_photos_get($subj_id=null, $aid=null, $pids=null){
	WPfbConnect::log("[fbConfig_php5::fb_photos_get] SUBJID: ".$subj_id." AID:".$aid." PIDS:".$pids,FBCONNECT_LOG_DEBUG);
	try {
		$fbclient = & facebook_client();
		if ($fbclient){		
			$fbapi_client = & $fbclient->api_client;
			return $fbapi_client->photos_get($subj_id, $aid, $pids);
		}
	}catch (Exception $e) {
		WPfbConnect::log("[fbConfig_php5::fb_photos_get] [Line: ".$e->getLine()."] [Code: ".$e->getCode()."] [MSG:".$e->getMessage()."]",FBCONNECT_LOG_ERR);
		WPfbConnect::log("[fbConfig_php5::fb_photos_get] SUBJID: ".$subj_id." AID:".$aid." PIDS:".$pids,FBCONNECT_LOG_ERR);
		WPfbConnect::log("[fbConfig_php5::fb_photos_get] ".print_r($e,true),FBCONNECT_LOG_DEBUG);
		return "ERROR";
	}
}		

function fb_users_getStandardInfo($uids=null, $fields=null) {
	WPfbConnect::log("[fbConfig_php5::fb_users_getStandardInfo] UID: ".$uids,FBCONNECT_LOG_DEBUG);
	try {
		$fbclient = & facebook_client();
		if ($fbclient){		
			$fbapi_client = & $fbclient->api_client;
			return $fbapi_client->users_getStandardInfo($uids, $fields);
		}
	}catch (Exception $e) {
		WPfbConnect::log("[fbConfig_php5::fb_users_getStandardInfo] [Line: ".$e->getLine()."] [Code: ".$e->getCode()."] [MSG:".$e->getMessage()."]",FBCONNECT_LOG_ERR);
		WPfbConnect::log("[fbConfig_php5::fb_users_getStandardInfo] UID: ".$uids,FBCONNECT_LOG_ERR);
		WPfbConnect::log("[fbConfig_php5::fb_users_getStandardInfo] ".print_r($e,true),FBCONNECT_LOG_DEBUG);
		return "ERROR";
	}

}

function fb_showFeedDialog(){
		$template_data = $_SESSION["template_data"];
		if (isset($template_data) && $template_data!=""){
				echo "<script type='text/javascript'>\n";
				echo "window.onload = function() {\n";
					echo "FB.ensureInit(function(){\n";
					echo "	  FB.Connect.showFeedDialog(".get_option('fb_templates_id').", ".fb_json_encode($template_data).", null, null, null , FB.RequireConnect.promptConnect);";
					echo "});\n";
				echo "   };\n";
				echo "	</script>";
				$_SESSION["template_data"] = "";
		}
}

function fb_hash($email) {
      $normalizedAddress = trim(strtolower($email));
      //crc32 outputs signed int
      $crc = crc32($normalizedAddress);
      //output in unsigned int format
      $unsignedCrc = sprintf('%u', $crc);
      $md5 = md5($normalizedAddress);
      return "{$unsignedCrc}_{$md5}";
}
	
function fb_connect_registerUsers($accounts=null){
	WPfbConnect::log("[fbConfig_php5::fb_connect_registerUsers] ACCOUNTS: ".$accounts,FBCONNECT_LOG_DEBUG);
	try {
		$fbclient = & facebook_client();
		if ($fbclient){		
			$fbapi_client = & $fbclient->api_client;
			return $fbapi_client->connect_registerUsers(fb_json_encode($accounts));
		}
	}catch (Exception $e) {
		WPfbConnect::log("[fbConfig_php5::fb_connect_registerUsers] [Line: ".$e->getLine()."] [Code: ".$e->getCode()."] [MSG:".$e->getMessage()."]",FBCONNECT_LOG_ERR);
		WPfbConnect::log("[fbConfig_php5::fb_connect_registerUsers] ACCOUNTS: ".$accounts,FBCONNECT_LOG_ERR);
		WPfbConnect::log("[fbConfig_php5::fb_connect_registerUsers] ".print_r($e,true),FBCONNECT_LOG_DEBUG);
		return "ERROR";
	}	
}	


function fb_users_setStatus($status,$uid = null,$clear = false,$status_includes_verb = true){
	WPfbConnect::log("[fbConfig_php5::fb_users_setStatus] UID: ".$uid,FBCONNECT_LOG_DEBUG);
	try {
		$fbclient = & facebook_client();
		if ($fbclient){		
			$fbapi_client = & $fbclient->api_client;
			return $fbapi_client->users_setStatus($status,$uid,$clear,$status_includes_verb);
		}
	}catch (Exception $e) {
		WPfbConnect::log("[fbConfig_php5::fb_users_setStatus] [Line: ".$e->getLine()."] [Code: ".$e->getCode()."] [MSG:".$e->getMessage()."]",FBCONNECT_LOG_ERR);
		WPfbConnect::log("[fbConfig_php5::fb_users_setStatus] UID: ".$uid,FBCONNECT_LOG_ERR);
		WPfbConnect::log("[fbConfig_php5::fb_users_setStatus] ".print_r($e,true),FBCONNECT_LOG_DEBUG);
		return "ERROR";
	}	
}	

function fb_stream_get($viewer_id = null,$source_ids = null,$start_time = 0,$end_time = 0,$limit = 30,$filter_key = '') {												  
	WPfbConnect::log("[fbConfig_php5::fb_stream_get] Viewer ID: ".$viewer_id." SourcerIDS: ".$source_ids,FBCONNECT_LOG_DEBUG);
	try {
		$fbclient = & facebook_client();
		if ($fbclient){		
			$fbapi_client = & $fbclient->api_client;
			return $fbapi_client->stream_get($viewer_id,$source_ids ,$start_time ,$end_time ,$limit ,$filter_key );
		}
	}catch (Exception $e) {
		WPfbConnect::log("[fbConfig_php5::fb_stream_get] [Line: ".$e->getLine()."] [Code: ".$e->getCode()."] [MSG:".$e->getMessage()."]",FBCONNECT_LOG_ERR);
		WPfbConnect::log("[fbConfig_php5::fb_stream_get] Viewer ID: ".$viewer_id." SourcerIDS: ".$source_ids,FBCONNECT_LOG_ERR);
		WPfbConnect::log("[fbConfig_php5::fb_stream_get] ".print_r($e,true),FBCONNECT_LOG_DEBUG);
		return "ERROR";
		//print_r($e);
		//echo "ERROR";
	// nothing, probably an expired session
	}
}

function fb_stream_publish(
    $message, $attachment = null, $action_links = null, $target_id = null,
    $uid = null) {
	WPfbConnect::log("[fbConfig_php5::fb_stream_publish] TARGET: ".$target_id." UID:".$uid,FBCONNECT_LOG_DEBUG);
	try {
		$fbclient = & facebook_client();
		if ($fbclient){		
			$fbapi_client = & $fbclient->api_client;
			return $fbapi_client->stream_publish($message, $attachment, $action_links , $target_id , $uid );
		}
	}catch (Exception $e) {
		WPfbConnect::log("[fbConfig_php5::fb_stream_publish] [Line: ".$e->getLine()."] [Code: ".$e->getCode()."] [MSG:".$e->getMessage()."]",FBCONNECT_LOG_ERR);
		WPfbConnect::log("[fbConfig_php5::fb_stream_publish] TARGET: ".$target_id." UID:".$uid,FBCONNECT_LOG_ERR);
		WPfbConnect::log("[fbConfig_php5::fb_stream_publish] ".print_r($e,true),FBCONNECT_LOG_DEBUG);
		return "ERROR";
	}
}

function fb_stream_addLike($post_id, $uid = null){
	WPfbConnect::log("[fbConfig_php5::fb_stream_addLike] POSTID: ".$post_id." UID:".$uid,FBCONNECT_LOG_DEBUG);
	try {
		$fbclient = & facebook_client();
		if ($fbclient){		
			$fbapi_client = & $fbclient->api_client;
			return $fbapi_client->stream_addLike($post_id, $uid = null);
		}
	}catch (Exception $e) {
		WPfbConnect::log("[fbConfig_php5::fb_stream_addLike] [Line: ".$e->getLine()."] [Code: ".$e->getCode()."] [MSG:".$e->getMessage()."]",FBCONNECT_LOG_ERR);
		WPfbConnect::log("[fbConfig_php5::fb_stream_addLike] POSTID: ".$post_id." UID:".$uid,FBCONNECT_LOG_ERR);
		WPfbConnect::log("[fbConfig_php5::fb_stream_addLike] ".print_r($e,true),FBCONNECT_LOG_DEBUG);
		return "ERROR";
	}

}

function fb_comments_add($xid, $text, $uid=0, $title='', $url='', $publish_to_stream=false) {  
	WPfbConnect::log("[fbConfig_php5::fb_comments_add] XID: ".$xid." Title:".$title,FBCONNECT_LOG_DEBUG);
	try {
		$fbclient = & facebook_client();
		if ($fbclient){		
			$fbapi_client = & $fbclient->api_client;
			return $fbapi_client->comments_add($xid, $text, $uid, $title, $url, $publish_to_stream);
		}
	}catch (Exception $e) {
		WPfbConnect::log("[fbConfig_php5::fb_comments_add] [Line: ".$e->getLine()."] [Code: ".$e->getCode()."] [MSG:".$e->getMessage()."]",FBCONNECT_LOG_ERR);
		WPfbConnect::log("[fbConfig_php5::fb_comments_add] XID: ".$xid." Title:".$title,FBCONNECT_LOG_ERR);
		WPfbConnect::log("[fbConfig_php5::fb_comments_add] ".print_r($e,true),FBCONNECT_LOG_DEBUG);
		return "ERROR";
	}
}

function fb_comments_get($xid){
	WPfbConnect::log("[fbConfig_php5::fb_comments_get] XID: ".$xid,FBCONNECT_LOG_DEBUG);
	try {
		$fbclient = & facebook_client();
		if ($fbclient){		
			$fbapi_client = & $fbclient->api_client;
			return $fbapi_client->comments_get($xid);
		}
	}catch (Exception $e) {
		WPfbConnect::log("[fbConfig_php5::fb_comments_get] [Line: ".$e->getLine()."] [Code: ".$e->getCode()."] [MSG:".$e->getMessage()."]",FBCONNECT_LOG_ERR);
		WPfbConnect::log("[fbConfig_php5::fb_comments_get] XID: ".$xid,FBCONNECT_LOG_ERR);
		WPfbConnect::log("[fbConfig_php5::fb_comments_get] ".print_r($e,true),FBCONNECT_LOG_DEBUG);
		return "ERROR";
	}
}
    	
function fb_friends_areFriends($uids1, $uids2) {
	WPfbConnect::log("[fbConfig_php5::fb_friends_areFriends] UID1: ".$uids1." UID2: ".$uids2,FBCONNECT_LOG_DEBUG);
	try {
		$fbclient = & facebook_client();
		if ($fbclient){		
			$fbapi_client = & $fbclient->api_client;
			return $fbapi_client->friends_areFriends($uids1, $uids2);
		}
	}catch (Exception $e) {
		WPfbConnect::log("[fbConfig_php5::fb_friends_areFriends] [Line: ".$e->getLine()."] [Code: ".$e->getCode()."] [MSG:".$e->getMessage()."]",FBCONNECT_LOG_ERR);
		WPfbConnect::log("[fbConfig_php5::fb_friends_areFriends] UID1: ".$uids1." UID2: ".$uids2,FBCONNECT_LOG_ERR);
		WPfbConnect::log("[fbConfig_php5::fb_friends_areFriends] ".print_r($e,true),FBCONNECT_LOG_DEBUG);
		return "ERROR";
	}	
}

function fb_json_encode($data){
	return json_encode($data);
}

function fb_dashboard_addGlobalNews($news, $image = null){
	try {
		$fbclient = & facebook_client();
		if ($fbclient){		
			$fbapi_client = & $fbclient->api_client;
			return $fbapi_client->dashboard_addGlobalNews($news, $image);
		}
	}catch (Exception $e) {
		print_r($e);
		WPfbConnect::log("[fbConfig_php5::fb_dashboard_addGlobalNews] [Line: ".$e->getLine()."] [Code: ".$e->getCode()."] [MSG:".$e->getMessage()."]",FBCONNECT_LOG_ERR);
		WPfbConnect::log("[fbConfig_php5::fb_dashboard_addGlobalNews] ".print_r($e,true),FBCONNECT_LOG_DEBUG);
		return "ERROR";
	}		
}
function fb_dashboard_setCount($uid, $count){
	try {
		$fbclient = & facebook_client();
		if ($fbclient){		
			$fbapi_client = & $fbclient->api_client;
			return $fbapi_client->dashboard_setCount($uid, $count);
		}
	}catch (Exception $e) {
		print_r($e);
		WPfbConnect::log("[fbConfig_php5::fb_dashboard_setCount] [Line: ".$e->getLine()."] [Code: ".$e->getCode()."] [MSG:".$e->getMessage()."]",FBCONNECT_LOG_ERR);
		WPfbConnect::log("[fbConfig_php5::fb_dashboard_setCount] ".print_r($e,true),FBCONNECT_LOG_DEBUG);
		return "ERROR";
	}
}

function fb_dashboard_publishActivity($activity, $image=null){
	try {
		$fbclient = & facebook_client();
		if ($fbclient){		
			$fbapi_client = & $fbclient->api_client;
			return $fbapi_client->dashboard_publishActivity($activity, $image);
		}
	}catch (Exception $e) {
		print_r($e);
		WPfbConnect::log("[fbConfig_php5::fb_dashboard_publishActivity] [Line: ".$e->getLine()."] [Code: ".$e->getCode()."] [MSG:".$e->getMessage()."]",FBCONNECT_LOG_ERR);
		WPfbConnect::log("[fbConfig_php5::fb_dashboard_publishActivity] ".print_r($e,true),FBCONNECT_LOG_DEBUG);
		return "ERROR";
	}	
}
?>