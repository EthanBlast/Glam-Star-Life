<?php
/**
 * @author: Javier Reyes Gomez (http://www.sociable.es)
 * @date: 05/10/2008
 * @license: GPLv2
 */

function fb_get_loggedin_user() {
	$fbclient = & facebook_client();
	if ($fbclient)
		return $fbclient->get_loggedin_user();
	return null;
}

function fb_user_getInfo($fb_user) {
	$fbclient = & facebook_client();
	if ($fbclient){
		$fbapi_client = & $fbclient->api_client;
		$userinfo = $fbapi_client->users_getInfo($fb_user, "username,website,about_me,email,proxied_email,profile_url,name,first_name,last_name,birthday,current_location,sex,pic,pic_small,pic_big,pic_square");
		//print_r($userinfo);
		if (isset($userinfo["users_getInfo_response"]) && isset($userinfo["users_getInfo_response"]["user"]))
			return $userinfo["users_getInfo_response"]["user"];
		else
			return $userinfo;
	}
	return null;
}

function fb_feed_getRegisteredTemplateBundles() {
	$fbclient = & facebook_client();
	if ($fbclient){
		$fbapi_client = & $fbclient->api_client;
		$templates = $fbapi_client->feed_getRegisteredTemplateBundles();
	
		if (isset($templates) && isset($templates["feed_getRegisteredTemplateBundles_response"]) && isset($templates["feed_getRegisteredTemplateBundles_response"]["template_bundle"])){
		 $templates = $templates["feed_getRegisteredTemplateBundles_response"]["template_bundle"];
		 if (!isset($templates[0])){
		 	$newtemplates = array();
			$newtemplates[] = $templates;
			$templates = $newtemplates;
		 }
		 foreach ($templates as $key=>$template){ //PHP5 structure
		 	if (isset($template["one_line_story_templates"]) && isset($template["one_line_story_templates"]["one_line_story_template"])){
		 		$templates[$key]["one_line_story_templates"][]=$template["one_line_story_templates"]["one_line_story_template"];
		 	}
			if(isset($template["short_story_templates"]) && isset($template["short_story_templates"]["short_story_template"])){
		 		$templates[$key]["short_story_templates"][]=$template["short_story_templates"]["short_story_template"];
		 	}
		 }
		 return $templates;
		}else{
		 return array();
		}
	}
	return null;
}

function fb_feed_registerTemplateBundle($one_line_stories,$short_stories,$full_stories){
	$fbclient = & facebook_client();
	if ($fbclient){
		$fbapi_client = & $fbclient->api_client;
		$response = $fbapi_client->feed_registerTemplateBundle($one_line_stories,$short_stories,$full_stories);
		if (isset($response["feed_registerTemplateBundle_response"]))
			return $response["feed_registerTemplateBundle_response"];
		else
			return $response;
	}
	return null;
}

function fb_feed_deactivateTemplateBundleByID($templateID){
	$fbclient = & facebook_client();
	if ($fbclient){		
		$fbapi_client = & $fbclient->api_client;
		$fbapi_client->feed_deactivateTemplateBundleByID($templateID);
	}
	return null;	
}

function fb_feed_getRegisteredTemplateBundleByID($templateID){
	$fbclient = & facebook_client();
	if ($fbclient){		
		$fbapi_client = & $fbclient->api_client;
		return $fbapi_client->feed_getRegisteredTemplateBundleByID($templateID);
	}
	return null;
}

function fb_fql_query($query){
	$fbclient = & facebook_client();
	if ($fbclient){		
		$fbapi_client = & $fbclient->api_client;
		$response = $fbapi_client->fql_query($query);
		if (isset($response["fql_query_response"]["user"]) && isset($response["fql_query_response"]["user"][0])){
			return $response["fql_query_response"]["user"];
		}elseif(isset($response["fql_query_response"]["user"])){
				$newresp = array();
				$newresp[] = $response["fql_query_response"]["user"];
				return $newresp;
		}
		return $response;
	}
	return null;
}
function fb_expire_session(){
	$fbclient = & facebook_client();
    if ($fbclient && $fbclient->get_loggedin_user()!="") {
		$fbclient->expire_session();
	}
		//Si se produce un error 102 de sesion invalida no limpia la sesion con exprire_sesion
	if (!$fbclient->in_fb_canvas() && isset($_COOKIE[$fbclient->api_key . '_user'])) {
        $cookies = array('user', 'session_key', 'expires', 'ss');
        foreach ($cookies as $name) {
          setcookie($fbclient->api_key . '_' . $name, "-", time()-3600);
          unset($_COOKIE[$fbclient->api_key . '_' . $name]);
        }
        setcookie($fbclient->api_key, "-", time()-3600);
        unset($_COOKIE[$fbclient->api_key]);
      }

      // now, clear the rest of the stored state
      $fbclient->user = 0;
      $fbclient->api_client->session_key = 0;
}

function fb_feed_publishUserAction($template_data){
	$fbclient = & facebook_client();
	if ($fbclient){		
		$fbapi_client = & $fbclient->api_client;
		$feed_bundle_id = get_option('fb_templates_id');
		$fbapi_client->feed_publishUserAction( $feed_bundle_id, 
                                           $template_data , 
                                           null, 
                                          null,2);
	}
}	

function fb_showFeedDialog(){
		$template_data = $_SESSION["template_data"];
		if (isset($template_data) && $template_data!=""){
				echo "<script type='text/javascript'>\n";
				//echo "jQuery(window).ready(function() {\n";  NO FUNCIONA COMO EL ONLOAD
				echo "window.onload = function() {\n";
					echo "FB.ensureInit(function(){\n";
					echo "	  FB.Connect.showFeedDialog(".get_option('fb_templates_id').", ".fb_json_encode($template_data).", null, null, FB.FeedStorySize.full , FB.RequireConnect.promptConnect);";
					echo "});\n";
				echo "   };\n";
				//echo "   });\n";
				echo "	</script>";
				$_SESSION["template_data"] = "";
		}

} 	

//REYES
function fb_json_encode($data){
	require_once 'facebook-client4/classes/JSON.php';
	$json = new Services_JSON();
	return $json->encode($data);
}

function fb_users_setStatus($status,$uid = null,$clear = false,$status_includes_verb = true){
	$fbclient = & facebook_client();
	if ($fbclient){		
		$fbapi_client = & $fbclient->api_client;
		return $fbapi_client->users_setStatus($status,$uid,$clear,$status_includes_verb);
	}
}	

function fb_stream_get($viewer_id = null,$source_ids = null,$start_time = 0,$end_time = 0,$limit = 30,$filter_key = '') {												  
	$fbclient = & facebook_client();
	if ($fbclient){		
		$fbapi_client = & $fbclient->api_client;
		return $fbapi_client->stream_get($viewer_id,$source_ids ,$start_time ,$end_time ,$limit ,$filter_key );
	}
}

function fb_stream_publish(
    $message, $attachment = null, $action_links = null, $target_id = null,
    $uid = null) {
	$fbclient = & facebook_client();
	if ($fbclient){		
		$fbapi_client = & $fbclient->api_client;
		return $fbapi_client->stream_publish($message, $attachment, $action_links , $target_id , $uid );
	}
}
    	
function fb_friends_areFriends($uids1, $uids2) {
	$fbclient = & facebook_client();
	if ($fbclient){		
		$fbapi_client = & $fbclient->api_client;
		return $fbapi_client->friends_areFriends($uids1, $uids2);
	}
}

function fb_getParams(){
	$fbclient = & facebook_client();
	if ($fbclient){
		return $fbclient->fb_params;
	}
}

function fb_comments_add($xid, $text, $uid=0, $title='', $url='', $publish_to_stream=false) {  
	$fbclient = & facebook_client();
	if ($fbclient){		
		$fbapi_client = & $fbclient->api_client;
		return $fbapi_client->comments_add($xid, $text, $uid, $title, $url, $publish_to_stream);
	}
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

	$fbclient = & facebook_client();
	if ($fbclient){
		return $fbclient->render_prompt_feed_url($action_links,$target_id,$message,$user_message_prompt,$caption,$callback,$cancel,$attachment,$preview);
	}
	return null;
                                         	
}

function fb_get_fbconnect_tos_url() {
		$fbclient = & facebook_client();
		if ($fbclient){
			return $fbclient->get_fbconnect_tos_url();
		}

	return null;
	
}

?>