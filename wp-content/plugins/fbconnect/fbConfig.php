<?php
/**
 * @author: Javier Reyes Gomez (http://www.sociable.es)
 * @date: 05/10/2008
 * @license: GPLv2
 */

if (version_compare("5", phpversion(),"<")){
	if  (!class_exists('Facebook')):
		if(isset($_REQUEST["fb_sig_in_canvas"]) || isset($_REQUEST["fb_sig_in_profile_tab"])){
			WPfbConnect::log("[Load Facebook ORIG server API for PHP5]:",FBCONNECT_LOG_DEBUG);	
			include_once 'facebook-client/facebookOrig.php';			
		}else{
			WPfbConnect::log("[Load New Facebook server API for PHP5]:",FBCONNECT_LOG_DEBUG);	
			include_once 'facebook-client/facebook.php';
		}
		include_once 'facebook-client/facebook_mobile.php';
	endif;
	include_once 'fbConfig_php5.php';
}else{	
	if  (!class_exists('Facebook')):
		if(isset($_REQUEST["fb_sig_in_canvas"]) || isset($_REQUEST["fb_sig_in_profile_tab"])){
			WPfbConnect::log("[Load Facebook ORIG server API for PHP4]:",FBCONNECT_LOG_DEBUG);		
			include_once 'facebook-client4/facebookOrig.php';
		}else{
			WPfbConnect::log("[Load New Facebook server API for PHP4]:",FBCONNECT_LOG_DEBUG);	
			include_once 'facebook-client4/facebook.php';
		}

	endif;	
	include_once 'fbConfig_php4.php';
}

if  (!class_exists('WPFacebookRestClient')):
class WPFacebookRestClient extends FacebookRestClient{

function call_method($method, $params = array()) {
	$initime = date("U");
	$response = parent::call_method($method, $params);
	$fintime = date("U");
	$total = $fintime - $initime;
	WPfbConnect::log("[WPFacebookRestClient::call_method] Response time: ".$total,FBCONNECT_LOG_DEBUG);
	return $response;
}

}
endif;

function is_config_setup() {
  return (get_api_key() && get_api_secret() &&
          get_api_key() != 'YOUR_API_KEY' &&
          get_api_secret() != 'YOUR_API_SECRET');
}

// Whether the site is "connected" or not
function is_fbconnect_enabled() {
  if (!is_config_setup()) {
    return false;
  }

  // Change this if you want to turn off Facebook connect
  return true;
}
function get_api_key() {
		return get_option('fb_api_key');
}
function get_api_secret() {
		return get_option('fb_api_secret');
}

function get_appId(){
		return get_option('fb_appId');
}

function get_base_fb_url() {
  return "connect.facebook.com";
}

function get_ssl_root() {
  return 'https://www.'.get_base_fb_url();
}


function get_static_root() {
  return 'http://static.ak.'.get_base_fb_url();
}


function get_feed_bundle_id() {
  return get_option('fb_templates_id');
}

/*
 * Get the facebook client object for easy access.
 */
function facebook_client() {
	
  static $facebook = null;
  $api_key = get_appId();
  if ($api_key==""){
	  $api_key = get_api_key();
  }
  $api_secret = get_api_secret();
  if ($facebook === null && $api_key!="" && $api_secret!="") {
	$facebook = new Facebook($api_key, $api_secret, false, get_base_fb_url());
	//facebook_construct($facebook,$api_key, $api_secret);
	//$wprest = new WPFacebookRestClient($api_key, $api_secret, null);
	//$facebook->api_client = $wprest;
  }
  return $facebook;
}

function facebook_construct(&$facebook,$api_key, $api_secret) {
	if (version_compare("5", phpversion(),"<")){
		$wprest = new WPFacebookRestClient($api_key, $api_secret, null);
	}else{
		$wprest = new WPFacebookRestClient($api_key, $api_secret,$facebook, null);
	}
	$facebook->api_client = $wprest;

    $facebook->validate_fb_params();

    // Set the default user id for methods that allow the caller to
    // pass an explicit uid instead of using a session key.

    if (isset($facebook->fb_params['friends'])) {
      $facebook->api_client->friends_list =
        array_filter(explode(',', $facebook->fb_params['friends']));
    }
    if (isset($facebook->fb_params['added'])) {
      $facebook->api_client->added = $facebook->fb_params['added'];
    }
    if (isset($facebook->fb_params['canvas_user'])) {
      $facebook->api_client->canvas_user = $facebook->fb_params['canvas_user'];
    }
  }
  
/*
 * Get the facebook mobile client object for easy access.
 */
function facebook_mobile_client() {
  static $facebook_mobile = null;
  $api_key = get_api_key();
  $api_secret = get_api_secret();
  if ($facebook === null && $api_key!="" && $api_secret!="") {
	$facebook = new FacebookMobile($api_key, $api_secret);
  }
  return $facebook;
}

function fb_streamPublishDialog(){
		$template_data = $_SESSION["template_data"];
		if (isset($template_data) && $template_data!="" && FBCONNECT_CANVAS=="appcanvas"){
				echo "<script>\n";
				echo "Facebook.streamPublish(".fb_json_encode(strip_tags($template_data["body_short"])).",".fb_json_encode($template_data["attachment"]).",".fb_json_encode($template_data["action_links"]).",null,null,true);";
				echo "	</script>";
				$_SESSION["template_data"] = "";
		}elseif (isset($template_data) && $template_data!=""){
				//echo "<script type='text/javascript'>\n";
				?>
				 FB.ui(
				   {
				     method: 'stream.publish',
				     message: <?php echo fb_json_encode(strip_tags($template_data["body_short"]));?>,
				     attachment: <?php echo fb_json_encode($template_data["attachment"]);?>,
				     action_links: <?php echo fb_json_encode($template_data["action_links"]);?>
				   },
				   function(response) {
				     if (response && response.post_id) {
				       //alert('Comment was published.');
				     } else {
				       //alert('Comment was not published.');
				     }
				   }
				 );
<?php
				/*echo "window.onload = function() {\n";
					echo "FB.ensureInit(function(){\n";
					echo "FB.Connect.streamPublish(".fb_json_encode(strip_tags($template_data["body_short"])).",".fb_json_encode($template_data["attachment"]).",".fb_json_encode($template_data["action_links"]).",null,null,true);";
					echo "});\n";
				echo "   };\n";*/
				//echo "	</script>";
				$_SESSION["template_data"] = "";
		}
}