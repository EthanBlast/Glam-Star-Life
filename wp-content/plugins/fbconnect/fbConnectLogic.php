<?php
/**
 * @author: Javier Reyes Gomez (http://www.sociable.es)
 * @date: 05/10/2008
 * @license: GPLv2
 */
if (!class_exists('WPfbConnect_Logic')):

/**
 * Basic logic for wp-fbConnect plugin.
 */
class WPfbConnect_Logic {
	function getmicrotime()
	{
		list($usec, $sec) = explode(" ",microtime()); 
		return round(((float)$usec + (float)$sec)); 
	} 
	
	function cutWords($tamano,$texto){

		$contador = 0;
		$arrayTexto = split(' ',$texto);
		$texto = '';

		while($tamano >= strlen($texto) + strlen($arrayTexto[$contador])){
		    $texto .= ' '.$arrayTexto[$contador];
		    $contador++;
		}
		return $texto;

	}
	function redirect($url=null){
		if ($url==null){
			$url = get_option('siteurl');
		}
		if(FBCONNECT_CANVAS=="web"){
			wp_redirect( $url );
		}else{
			echo '<fb:redirect url="'.$url.'" />';
		}
	}
	
	function getMobileClient() {
		//return "iphone";
		$user_agent = $_SERVER['HTTP_USER_AGENT'];
		$useragents = array(		
			"iphone",  			// Apple iPhone
			"ipod", 			// Apple iPod touch
			"aspen", 			// iPhone simulator
			"dream", 			// Pre 1.5 Android
			"android", 			// 1.5+ Android
			"cupcake", 			// 1.5+ Android
			"blackberry9500",	// Storm
			"blackberry9530",	// Storm
			"opera mini", 		// Experimental
			"webos",			// Experimental
			"incognito", 		// Other iPhone browser
			"webmate" 			// Other iPhone browser
		);
		foreach ( $useragents as $useragent ) {
			if ( eregi( $useragent, $user_agent )  ) {
				return $useragent;
			} 	
		}
		return "";
	}
	
	function get_friends_data() {
	  global $wpdb, $fbconnect, $wpmu_version;
      $fb_blogid = 1;
	  //////////////////////////////////////////////
      if($wpmu_version) {// If wordpress MU
            $fb_blogid = $wpdb->blogid;
	  }
      /////////////////////////////////////////////
	  $friends_table = WPfbConnect::friends_table_name();
	  $user = wp_get_current_user();
	  if ( isset($user) && $user!="" && $user->fbconnect_userid != '' && $user->fbconnect_userid != '0'){
			$fb_uid = $user->fbconnect_userid ;
			
			$results = array();

			$query = 'SELECT uid, significant_other_id ,online_presence ,verified ,username,first_name, last_name,pic_small,birthday,birthday_date,sex,has_added_app,family,current_location,wall_count,notes_count   FROM user WHERE strpos(lower(first_name),lower(\'ier\'))>=0 AND uid IN '.
			'(SELECT uid2 FROM friend WHERE uid1 = '.$fb_uid.') ORDER BY wall_count DESC LIMIT 50';
			$rows = fb_fql_query($query);
			print_r($rows);
			if ($rows!=null && !empty($rows)) {
			  foreach ($rows as $row) {
			      $results[] = $row;
                  //$wpdb->insert( $friends_table, compact( 'userid', 'friendid', 'wpuserid','wpfriendid','netid','blog_id' ) );
			    //}
			  }
			}
			//$users_table = WPfbConnect::users_table_name();
			//$users = $wpdb->get_results("SELECT * FROM $friends_table friends,$users_table users WHERE friends.wpuserid=".$user->ID." AND friends.wpfriendid=users.ID ORDER BY users.fbconnect_lastlogin DESC ");
			return $rows;
		 }else{
		 	return "";
		 }
	}



	function get_connected_friends() {
	  global $wpdb, $fbconnect, $wpmu_version;
      $fb_blogid = 1;
	  //////////////////////////////////////////////
      if($wpmu_version) {// If wordpress MU
            $fb_blogid = $wpdb->blogid;
	  }
      /////////////////////////////////////////////
	  $friends_table = WPfbConnect::friends_table_name();
	  $user = wp_get_current_user();
	  if ( isset($user) && $user!="" && $user->fbconnect_userid != '' && $user->fbconnect_userid != '0'){
			$fb_uid = $user->fbconnect_userid ;
			$wpdb->query("DELETE FROM $friends_table WHERE userid='$fb_uid' AND blog_id=$fb_blogid");
			
			$results = array();
			$query = 'SELECT uid, email_hashes, has_added_app FROM user WHERE has_added_app = 1 AND uid IN '.
			'(SELECT uid2 FROM friend WHERE uid1 = '.$fb_uid.')';
			$rows = fb_fql_query($query);

			// Do filtering in PHP because the FQL doesn't allow it (yet)
			if ($rows!=null && $rows !="ERROR" && !empty($rows)) {
			  foreach ($rows as $row) {
			    //if ((is_array($row['email_hashes']) && count($row['email_hashes']) > 0) || ($row['has_added_app'] == 1)) {
			      //unset($row['has_added_app']);
			      $results[] = $row;
				  $fbwpuser = WPfbConnect_Logic::get_userbyFBID($row['uid']);
				  if (isset($fbwpuser)){
				  		$userid = $fb_uid;
						$friendid = $row['uid'];
						$wpuserid = $user->ID;
						$wpfriendid = $fbwpuser->ID;
						$netid = "facebook";
                        $blog_id = $fb_blogid;				  		
                        $wpdb->insert( $friends_table, compact( 'userid', 'friendid', 'wpuserid','wpfriendid','netid','blog_id' ) );
				  }
			    //}
			  }
			}
			//$users_table = WPfbConnect::users_table_name();
			//$users = $wpdb->get_results("SELECT * FROM $friends_table friends,$users_table users WHERE friends.wpuserid=".$user->ID." AND friends.wpfriendid=users.ID ORDER BY users.fbconnect_lastlogin DESC ");
			return $rows;
		 }else{
		 	return "";
		 }
	}

	function get_friends($wpuserID,$start=0,$limit=10){
        global $wpdb, $fbconnect, $wpmu_version;
        $fb_blogid = 1;
        //////////////////////////////////////////////
        if($wpmu_version) {// If wordpress MU
            $fb_blogid = $wpdb->blogid;
        }

		$friends_table = WPfbConnect::friends_table_name();
		$lastlogin_table = WPfbConnect::lastlogin_table_name();
		$users = $wpdb->get_results($wpdb->prepare("SELECT wpfriendid ID FROM $friends_table friends,$lastlogin_table lastlogin WHERE friends.wpuserid=$wpuserID AND friends.blog_id=$fb_blogid and lastlogin.wpuserid=friends.wpfriendid and lastlogin.blog_id=friends.blog_id ORDER BY lastlogin.fbconnect_lastlogin DESC LIMIT ".$start.",".$limit));
		return $users;
	}
	
	/* Get status text */
	function get_status_text() {
		if (is_single() != 1){
			return get_option('blogname')." ".get_option('home');
		}else{
			global $id;
			global $post;
			$fb_tiny_url = WPfbConnect_Logic::get_short_url();
			return $post->post_title." ".$fb_tiny_url;
		}
	}

	function get_status_postid() {

		if (is_single() != 1){
			return "";
		}else{
			global $id;
			return $id;
		}
	}


	function get_short_url() {
		global $id; $purl = get_permalink();
		$cached_url = get_post_meta($id, 'fbconnect_short_url', true);
		if($cached_url && $cached_url != 'getnew'){
			 return $cached_url;
		}else {	
			//$u= "tinyurl";
			$u = get_option('fb_short_urls');
			if (!isset($u) || $u ==""){
				$u = "WordpressPermalink";
			}
			//$u= "twitter friendly";
			switch($u) {
			case 'twitter friendly': $url = twitter_link(); break;
			case 'bit.ly': $url = file_get_contents('http://bit.ly/api?url=' . $purl); break;
			case 'h3o.de': $url = file_get_contents('http://h3o.de/api/index.php?url=' . $purl); break;
			case 'hex.io': $url = str_replace('www.', '',
				file_get_contents('http://www.hex.io/api-create.php?url=' . $purl)); break;
			case 'idek.net': $url = file_get_contents('http://idek.net/c.php?idek-api=true&idek-ref=Tweet+This&idek-url=' .
				$purl); break;
			case 'is.gd': $url = file_get_contents('http://is.gd/api.php?longurl=' . $purl); break;
			case 'lin.cr': $url = file_get_contents('http://lin.cr/?mode=api&full=1&l=' . $purl); break;
			case 'metamark': $url = file_get_contents('http://metamark.net/api/rest/' . 'simple?long_url=' . $purl); break;
			case 'ri.ms': $url = file_get_contents('http://ri.ms/api-create.php?url=' . $purl); break;
			case 'snurl': $url = file_get_contents('http://snurl.com/site/snip?r=simple&link=' . $purl); break;
			case 'tinyurl': $url = file_get_contents('http://tinyurl.com/api-create.php?url=' . $purl); break;
			case 'urlb.at': $url = file_get_contents('http://urlb.at/api/rest/?url=' .	urlencode($purl)); break;
			case 'zi.ma': $url = file_get_contents('http://zi.ma/?module=ShortURL&file=Add&mode=API&url=' . $purl); break;
			case 'WordpressPermalink': $url = $purl; break;
			}
			if ($cached_url == 'getnew'){
				update_post_meta($id, 'fbconnect_short_url', $url, 'getnew');
			}else{
				 add_post_meta($id, 'fbconnect_short_url', $url, true);
			}
		}		 
		return $url;
	}

	/**
	 * Update plugin
	 *
	 * @return boolean if the plugin is okay
	 */
	function updateplugin() {
		global $fbconnect;

		if( get_option('fb_db_revision') != FBCONNECT_DB_REVISION ) {
			$store =& WPfbConnect_Logic::getStore();
			$store->update_tables();
		}
		if( (get_option('fb_db_revision') != FBCONNECT_DB_REVISION) || (get_option('fb_plugin_revision') != FBCONNECT_PLUGIN_REVISION) ) {
			update_option( 'fb_plugin_revision', FBCONNECT_PLUGIN_REVISION );
			update_option( 'fb_db_revision', FBCONNECT_DB_REVISION );
		}

	}


	/**
	 * Get the internal SQL Store.  If it is not already initialized, do so.
	 *
	 * @return WPfbConnect_Store internal SQL store
	 */
	function getStore() {
		global $fbconnect;

		if (!isset($fbconnect->store)) {
			set_include_path( dirname(__FILE__) . PATH_SEPARATOR . get_include_path() );
			require_once 'fbConnectStore.php';

			$fbconnect->store = new WPfbConnect_Store($fbconnect);
			if (null === $fbconnect->store) {
				$fbconnect->enabled = false;
			}
		}

		return $fbconnect->store;
	}




	/**
	 * Called on plugin activation.
	 *
	 * @see register_activation_hook
	 */
	function activate_plugin() {
		global $fbconnect;
		//fbconnect_init();

		$store =& WPfbConnect_Logic::getStore();
		$store->create_tables();
		add_option( 'fb_plugin_revision', FBCONNECT_PLUGIN_REVISION );
		add_option( 'fb_db_revision', FBCONNECT_DB_REVISION );
		if (!get_option('fb_short_stories_body'))
			update_option( 'fb_short_stories_body', '{*body_short*}' );
		if (!get_option('fb_short_stories_title'))
			update_option( 'fb_short_stories_title', '{*actor*} commented on {*blogname*}' );
	}



	/**
	 * Called on plugin deactivation.  Cleanup tables.
	 *
	 * @see register_deactivation_hook
	 */
	function deactivate_plugin() {
	}


	function register_update(){
		global $wp_version,$new_fb_user;
		$new_fb_user = false;
		$self = basename( $GLOBALS['pagenow'] );
		
		$fb_user = fb_get_loggedin_user();
		//echo "LOGED:".$fb_user;
		$user = wp_get_current_user();
		if ( !is_user_logged_in() && $fb_user!="") { //Profile Update
			$usersinfo = fb_user_getInfo($fb_user);

			$userdata = get_userdatabylogin( "FB_".$fb_user );	
			if ($userdata==""){
				$userdata = get_userdatabylogin( "fb".$fb_user );	
			}
			if((!$userdata || $userdata=="") && isset($usersinfo) && $usersinfo!=""){
				$wpid = WPfbConnect_Logic::update_wpuser("","FB_".$fb_user,$usersinfo["proxied_email"]);
				WPfbConnect_Logic::set_userid_fbconnect($wpid,$fb_user);
				$new_fb_user= true;
				
			}elseif(isset($usersinfo) && $usersinfo!=""){
				WPfbConnect_Logic::set_userid_fbconnect($userdata->ID,$fb_user);
				WPfbConnect_Logic::update_wpuser($user->ID);
				$wpid = $userdata->ID;
			}
		}elseif (is_user_logged_in()){
			WPfbConnect_Logic::update_wpuser($user->ID);
			$wpid = $user->ID;
		}

		//$userdata = get_userdatabylogin( "FB_".$fb_user );
		$userdata = WPfbConnect_Logic::get_userbyFBID($fb_user);
		WPfbConnect_Logic::set_lastlogin_fbconnect($userdata->ID);
		global $current_user;
		$current_user = null;
		
		WPfbConnect_Logic::fb_set_current_user($userdata);
		if ($new_fb_user){
			//WPfbConnect_Logic::add_wall_comment($current_user->user_nicename." has joined the community. [".current_time( "mysql" )."]","fbconnect_newuser");
			//WPfbConnect_Logic::add_wall_comment($current_user->user_nicename." has joined the community. [".current_time( "mysql" )."]","");
			/*$blogname = get_option('blogname');
			$blogdesc = get_option('blogdescription');
			$siteurl = get_option('siteurl');
	  		$template_data = array('post_title' => '<a href="'.$siteurl.'">'.$comment->post_title.'</a>',
             'body' => 'has registered on <a href="'.$siteurl.'">'.$blogname.'</a>',
             'body_short' => 'has registered on <a href="'.$siteurl.'">'.$blogname.'</a>',
             'post_permalink' => $siteurl,
			 'blogname' => '<a href="'.$siteurl.'">'.$blogname.'</a>',
			 'blogdesc' => $blogdesc,
			 'siteurl' => $siteurl);
			$_SESSION["template_data"]= $template_data;*/
		}
		global $userdata;
		if (isset($userdata) && $userdata!="")
			$userdata->fbconnect_userid = $fb_user;
		//wp_set_auth_cookie($userdata->ID, false);

		//Cache friends
		WPfbConnect_Logic::get_connected_friends();
	}

	function update_wpuser($wpuserid="",$user_login="",$proxied_email=""){
		require(ABSPATH . WPINC . '/registration.php');
		$user_data = array();
		$user_data['user_nicename'] = $_POST["name"];
		$user_data['display_name'] = $_POST["name"];
		$user_data['first_name'] = $_POST["name"];
		$user_data['user_url'] = $_POST["user_url"];
		$user_data['user_email'] = $_POST["email"];
		$user_data['nickname']= $_POST["nickname"];
		$user_data['description']= $_POST["about"];
		
		if (isset($wpuserid) && $wpuserid!=""){
			$user_data['ID'] = $wpuserid;
			$wpid =$wpuserid;
			wp_update_user($user_data);						
		}else{
			$user_data['user_login'] = $user_login;
			$user_data['user_pass'] = substr( md5( uniqid( microtime() ).$_SERVER["REMOTE_ADDR"] ), 0, 15);
			$wpid = wp_insert_user($user_data);
			// We create the first login record
            WPfbConnect_Logic::set_lastlogin_fbconnect($wpid);

		}
        WPfbConnect_Logic::set_userblog_fbconnect($wpid);


		//print_r($_REQUEST);
		//exit;
		update_usermeta( $wpid, "facebook_email", $proxied_email); 

	
		if (isset($_POST["birthdate_day"]) && $_POST["birthdate_day"]!="00" && isset($_POST["birthdate_month"]) && birthdate_month!="00" && isset($_POST["birthdate_year"]) &&birthdate_year!="0000" ){
			//print_r($_POST);
			$birthday = mktime(0, 0, 0, $_POST["birthdate_month"], $_POST["birthdate_day"], $_POST["birthdate_year"]);
			update_usermeta( $wpid, "birthday", date("M j, Y",$birthday) );
		}
		
		if (isset($_POST["location_city"]))
			update_usermeta( $wpid, "location_city", $_POST["location_city"] );
			
		if (isset($_POST["location_state"]))
			update_usermeta( $wpid, "location_state", $_POST["location_state"] );
			
		if (isset($_POST["location_country"]))
			update_usermeta( $wpid, "location_country", $_POST["location_country"] );
			
		if (isset($_POST["location_zip"]))
			update_usermeta( $wpid, "location_zip", $_POST["location_zip"] );	
			
		if (isset($_POST["sex"]))
			update_usermeta( $wpid, "sex", $_POST["sex"] );
	
		if (isset($_POST["company_name"]))
			update_usermeta( $wpid, "company_name", $_POST["company_name"] );
		
		if (isset($_POST["phone"]))
			update_usermeta( $wpid, "phone", $_POST["phone"] );

		if (isset($_POST["custom_vars"]) && $_POST["custom_vars"]!=""){
			$vars = explode("=",$_POST["custom_vars"]);
			update_usermeta( $wpid, $vars[0], $vars[1] );
		}
		//print_r($_POST);
		/*foreach($_POST as $keypost=>$valpost){
			$pos = strpos($keypost, "custom_field_");
			if ($pos === false) {
				//echo "NO";
			}else{
				update_usermeta( $wpid, $keypost, $valpost );
			}

		}*/

		return $wpid;
	}		
	
	/**
	 * Facebook connect Login 
	 */
	function wp_login_fbconnect() {
		
		global $wp_version,$new_fb_user;
		$self = basename( $GLOBALS['pagenow'] );
	
		$fb_user = fb_get_loggedin_user();
		WPfbConnect::log("[fbConnectLogic::wp_login_fbconnect] FBUserID:".$fb_user,FBCONNECT_LOG_DEBUG);	
		$user = wp_get_current_user();

		//if ( $fb_user && (!is_user_logged_in() || ($user->fbconnect_userid != $fb_user) || $sesion_anterior!=$fbparams["session_key"])) { //Intenta hacer login estando registrado en facebook
		if ( $fb_user && (!is_user_logged_in() || ($user->fbconnect_userid != $fb_user))) { //Intenta hacer login estando registrado en facebook
			//echo "<br/>INICIA NUEVA1";
			require_once(ABSPATH . WPINC . '/registration.php');
			//$_SESSION["facebook_session_id"] = 	$fbparams["session_key"];
			$usersinfo = fb_user_getInfo($fb_user);
			WPfbConnect::log("[fbConnectLogic::wp_login_fbconnect] UserInfo:".print_r($usersinfo,true),FBCONNECT_LOG_DEBUG);	
			if ($usersinfo=="ERROR"){
				return;	
			}

			$_SESSION["facebook_usersinfo"] = $usersinfo;
			
			$wpid = "";
			$fbwpuser = WPfbConnect_Logic::get_userbyFBID($fb_user);
			$wpid = "";
			$new_fb_user= false;
			
			if(is_user_logged_in() && !$fbwpuser && ($user->fbconnect_userid =="" || $user->fbconnect_userid =="0")){ // El usuario WP no está asociado al de FB
				WPfbConnect_Logic::set_userid_fbconnect($user->ID,$fb_user);
				$wpid = $user->ID;
			}elseif (!is_user_logged_in() && $fbwpuser && $fbwpuser->fbconnect_userid ==""){
				WPfbConnect_Logic::set_userid_fbconnect($fbwpuser->ID,$fb_user);
				$wpid = $fbwpuser->ID;	
			}elseif ((!is_user_logged_in() && !$fbwpuser) || (!$fbwpuser && is_user_logged_in() && $user->fbconnect_userid != $fb_user)){
				if(isset($usersinfo) && $usersinfo!=""){
					$username = trim($usersinfo['username']);
					if (isset($username) && $username!="" ){
						$usertmp = get_userdatabylogin( $username );	
						if (isset($usertmp) && $usertmp!=""){
							$username = "FB_".$fb_user;
						}
					}else{
							$username = "FB_".$fb_user;
					}

					$user_data = array();
					$user_data['user_login'] = $username;
					$user_data['user_pass'] = substr( md5( uniqid( microtime() ).$_SERVER["REMOTE_ADDR"] ), 0, 15);
					$user_data['user_nicename'] = $usersinfo["first_name"]." ".$usersinfo["last_name"];
					$user_data['display_name'] = $usersinfo["first_name"]." ".$usersinfo["last_name"];
					$user_data['user_url'] = $usersinfo["profile_url"];
					//$user_data['user_email'] = $usersinfo["proxied_email"];
					$user_data['user_email'] = "";
					if ($usersinfo["proxied_email"]!=""){
						$user_data['user_email'] = $usersinfo["proxied_email"];
					}
					
					if ($usersinfo["email"]!=""){
						$user_data['user_email'] = $usersinfo["email"];
					}else{//WP3 no permite el email en blanco
						define ( 'WP_IMPORTING', true);
					}
					
					$wpid = wp_insert_user($user_data);
					WPfbConnect::log("[fbConnectLogic::wp_login_fbconnect] User Insert ID:".$wpid,FBCONNECT_LOG_DEBUG);	
					update_usermeta( $wpid, "first_name", $usersinfo["first_name"] );
					update_usermeta( $wpid, "last_name", $usersinfo["last_name"] );
					update_usermeta( $wpid, "description", $usersinfo["about_me"] );
					update_usermeta( $wpid, "birthday", $usersinfo["birthday"] );
					update_usermeta( $wpid, "location_city", $usersinfo["current_location"]["city"] );
					update_usermeta( $wpid, "location_state", $usersinfo["current_location"]["state"] );
					update_usermeta( $wpid, "location_country", $usersinfo["current_location"]["country"] );
			
					update_usermeta( $wpid, "sex", $usersinfo["sex"] );
					WPfbConnect_Logic::set_userid_fbconnect($wpid,$fb_user);
					$new_fb_user= true;
				}
			}elseif(is_user_logged_in() && $fbwpuser && $user->ID != $fbwpuser->ID){ // El usuario FB está asociado a un usaurio WP distinto al logeado
				//echo "<br/>INICIA NUEVA4";
				//WPfbConnect_Logic::set_userid_fbconnect($user->ID,$fb_user);
				//WPfbConnect_Logic::set_userid_fbconnect($fbwpuser->ID,"0"); //Eliminamos la asociación anterior
				$wpid = $fbwpuser->ID;
				//return;
			}
			
			$userdata = WPfbConnect_Logic::get_userbyFBID($fb_user);
			//$userdata = get_userdata($wpid); 

			WPfbConnect_Logic::set_lastlogin_fbconnect($userdata->ID);
			global $current_user;

			$current_user = null;
			
			WPfbConnect_Logic::fb_set_current_user($userdata);
			//if ($new_fb_user){
				//WPfbConnect_Logic::add_wall_comment($current_user->user_nicename." has joined the community. [".current_time( "mysql" )."]","fbconnect_newuser");
				/*$blogname = get_option('blogname');
				$blogdesc = get_option('blogdescription');
				$siteurl = get_option('siteurl');
		  		$template_data = array('post_title' => '<a href="'.$siteurl.'">'.$comment->post_title.'</a>',
                 'body' => 'has registered on <a href="'.$siteurl.'">'.$blogname.'</a>',
                 'body_short' => 'has registered on <a href="'.$siteurl.'">'.$blogname.'</a>',
                 'post_permalink' => $siteurl,
				 'blogname' => '<a href="'.$siteurl.'">'.$blogname.'</a>',
				 'blogdesc' => $blogdesc,
				 'siteurl' => $siteurl);
				$_SESSION["template_data"]= $template_data;*/
			//}
			global $userdata;
			if (isset($userdata) && $userdata!="")
				$userdata->fbconnect_userid = $fb_user;

			//Cache friends
			WPfbConnect_Logic::get_connected_friends();
		}
	}

	function fb_set_auth_cookie($auth_cookie, $expire, $expiration, $user_id, $scheme){
		$fb_user = fb_get_loggedin_user();
		if (isset($fb_user) && $fb_user!=""){
			//fb_data_setCookie($fb_user,AUTH_COOKIE, $auth_cookie, $expire, PLUGINS_COOKIE_PATH);
			setcookie(AUTH_COOKIE, $auth_cookie, $expire, "/", COOKIE_DOMAIN, flase, true);
		}
	}
	
	function fb_set_logged_in_cookie($logged_in_cookie, $expire, $expiration, $user_id, $scheme){
		$fb_user = fb_get_loggedin_user();
		if (isset($fb_user) && $fb_user!="")
			//fb_data_setCookie($fb_user,LOGGED_IN_COOKIE, $logged_in_cookie, $expire, COOKIEPATH);
			setcookie(LOGGED_IN_COOKIE, $logged_in_cookie, $expire, "/", COOKIE_DOMAIN, false, true);
	}
	
	function fb_set_current_user($userdata, $remember = false) {

		$user = set_current_user($userdata->ID);
		//echo "<br/>	COOKIEPATH:".COOKIEPATH;
		//echo "<br/>	COOKIE_DOMAIN:".COOKIE_DOMAIN;
		//echo "<br/>	SITECOOKIEPATH:".SITECOOKIEPATH;
		if (function_exists('wp_set_auth_cookie')) {
			//echo "<br/>	Existe funcion wp_set_auth_cookie:";
			wp_set_auth_cookie($userdata->ID, $remember);
		} else {
			//echo "<br/>	NO Existe funcion wp_set_auth_cookie:";
			wp_setcookie($userdata->user_login, md5($userdata->user_pass), true, '', '', $remember);
		}

		//do_action('wp_login', $user->user_login);
	}


	function fb_logout(){
		//fb_expire_session();
	}
	
	function fbc_comments_template($current_path){
		global $fb_old_comments_path;
		$fb_old_comments_path = $current_path;
		return FBCONNECT_PLUGIN_PATH."/fbconnect_comments.php";
	}	
	
	function comment_fbconnect($comment_ID) {
		global $fbconnect;

		$comment = WPfbConnect_Logic::get_comment_byID($comment_ID);

		$fb_user = fb_get_loggedin_user();
			
			if (is_user_logged_in() && $fb_user){
				$url = get_post_meta($comment->comment_post_ID , 'fb_external_url', true);
				if ($url==""){
					$url = get_permalink($comment->comment_post_ID);
				}
				WPfbConnect_Logic::set_comment_fbconnect($comment_ID,$fb_user);
				$comment_body = strip_tags(apply_filters( 'comment_text', $comment->comment_content));
				fb_comments_add($comment->comment_post_ID, $comment_body, $fb_user, $comment->post_title, $url, false);
				
				if ($_REQUEST["sendToFacebook"] ){
					$blogname = get_option('blogname');
					$blogdesc = get_option('blogdescription');
					$siteurl = get_option('siteurl');
				
					
					$user = wp_get_current_user();	 
					
					$attachbody ="";
					if (isset($comment->post_excerpt) && $comment->post_excerpt!=""){
						$attachbody = strip_tags($comment->post_excerpt);
						$attachbody_short = substr(strip_tags($comment->post_excerpt),0,255);
					}else{
						$attachbody = strip_tags($comment->post_content);
						$attachbody_short = substr(strip_tags($comment->post_content),0,255);
					}
									  		
					$template_data = array('actorName'=>$user->display_name,
									 'post_title' => $comment->post_title,
			                         'body' => $attachbody,
			                         'body_short' => $attachbody_short,
			                         'post_permalink' => $url,
						 'blogname' => $blogname,
						 'blogdesc' => $blogdesc,
						 'siteurl' => $siteurl,
						 'postid'=>$comment->comment_post_ID);
						
						$fb_short_stories_title = get_option('fb_short_stories_title');
						$fb_short_stories_body = get_option('fb_short_stories_body');

						$fb_short_stories_title = WPfbConnect_Logic::replace_params_values($fb_short_stories_title,$template_data);	 
						$fb_short_stories_body = WPfbConnect_Logic::replace_params_values($fb_short_stories_body,$template_data);	 

						$imgurl = WPfbConnect_Logic::get_post_image($comment->comment_post_ID);
						

						//$caption="{*actor*} ".__('commented on', 'fbconnect')." ".$blogname;
						$caption = $fb_short_stories_title;
						$attachment = WPfbConnect_Logic::create_attachment($comment->post_title,$caption,$fb_short_stories_body,$url,$imgurl,$comment->comment_post_ID);
						$action_links = array(array('text' => __('Read more...', 'fbconnect'), 'href' => $url));
						
						//$body_short = substr(strip_tags(apply_filters( 'comment_text', $comment->comment_content)),0,255);
						//$body_short = strip_tags(apply_filters( 'comment_text', $comment->comment_content));
						//$body_short = $fb_short_stories_body;
						$template_data = array('actor'=>$user->display_name,
									 'post_title' => $comment->post_title,
			                         'body' => $comment_body,
			                         'body_short' => $comment_body,
			                         'post_permalink' => $url,
						 'blogname' => $blogname,
						 'blogdesc' => $blogdesc,
						 'siteurl' => $siteurl,
						 'postid'=>$comment->comment_post_ID,
						 'action_links'=>$action_links,
						 'attachment'=>$attachment);
					
					if (WPfbConnect_Logic::getMobileClient()!=""){
						

					    
						//$attachment = WPfbConnect_Logic::create_attachment($comment->post_title,$caption,$body_short,$url,get_option('fb_comments_logo'),$comment->comment_post_ID);
						
						fb_render_prompt_feed_url($action_links, null, $comment_body, null,$comment_body,$url,$url,$attachment,true);
						exit;
					}
					
					$_SESSION["template_data"]= $template_data;
					//return $template_data;
					//fb_feed_publishUserAction($template_data);
				}
			}

	}
	
	function get_post_image_thumb($post_id){
		if(function_exists('get_post_thumbnail_id')):
			$post_thumbnail_id = get_post_thumbnail_id( $post_id );
			$wp_thumb = wp_get_attachment_image_src( $post_thumbnail_id,"thumbnail");
			if (isset($wp_thumb) && $wp_thumb!=""){
				return $wp_thumb[0];
			}
		endif;
		$imgid = get_post_meta($post_id , 'fb_mainimg_id', true);
		$imgurl = "";
		if (isset($imgid) && $imgid!=0){
			$imgurl = wp_get_attachment_thumb_url($imgid);
		}
		if ($imgurl=="" && get_option('fb_comments_logo')!=""){
			$imgurl = get_option('fb_comments_logo');
		}elseif($imgurl==""){
			$imgurl = FBCONNECT_PLUGIN_URL."/images/sociable_logo.gif";
		}
		return $imgurl;
	}
	
	//sizes: thumbnail, medium, full
	function get_post_image($post_id,$size='medium'){
		$imgurl="";
		if(function_exists('get_post_thumbnail_id')):
			$post_thumbnail_id = get_post_thumbnail_id( $post_id );
			$wp_thumb = wp_get_attachment_image_src( $post_thumbnail_id ,$size);
		endif;
		$imgurl = get_post_meta($post_id , 'fb_mainimg_url', true);
		$thesis_thumb = get_post_meta($post_id ,"thesis_thumb", true);
		$thesis_post_image = get_post_meta($post_id ,"thesis_post_image", true);

		if (isset($wp_thumb) && $wp_thumb!=""){
			return $wp_thumb[0];
		}elseif ($imgurl=="" && $thesis_thumb!=""){
			$imgurl = $thesis_thumb;
		}elseif ($imgurl=="" && $thesis_post_image!=""){
			$imgurl = $thesis_post_image;
		}elseif ($imgurl=="" && get_option('fb_comments_logo')!=""){
			$imgurl = get_option('fb_comments_logo');
		}elseif($imgurl==""){
			$imgurl = FBCONNECT_PLUGIN_URL."/images/default_logo.gif";
		}
		
/*		if ($post_id!=""){
			$files = get_children("post_parent=$post_id&post_type=attachment&post_mime_type=image");
			if ($files!="" && count($files)>0){
				foreach($files as $num=>$file){
					$imgurl = $file->guid;
					break;
				}
			}
		}
		if ($imgurl=="" && get_option('fb_comments_logo')!=""){
			$imgurl = get_option('fb_comments_logo');
		}elseif($imgurl==""){
			$imgurl = FBCONNECT_PLUGIN_URL."/images/sociable_logo.gif";
		}
		*/
		
		return $imgurl;
		
	}
	
	function create_attachment($name,$caption,$description,$callback_url,$attach_url,$comments_xid="",$type='image',$preview_img="",$properties=""){
	  $attachment = new stdClass();
      $attachment->name = $name;
	  if ($comments_xid!="")
		  $attachment->comments_xid = $comments_xid;
      $attachment->caption = $caption;
      $attachment->description = $description;
      $attachment->href = $callback_url;
      if (!empty($attach_url)) {
        $media = new stdClass();
        $media->type = $type;
		if ($type=='image'){
	        $media->src = $attach_url;
	        $media->href = $callback_url;
		}elseif($type=='flash'){
			$media->swfsrc = $attach_url;
			$media->imgsrc = $preview_img;
		}elseif($type=='video'){
			$media->video_src = $attach_url;
			$media->preview_img = $preview_img;
			$media->video_link = $callback_url;
			$media->video_title = $name;
		}
        $attachment->media = array($media);
      }
	  if ($properties!=""){
	  	$attachment->properties= $properties;
	  }
	  return $attachment;
	}

	function replace_params_values($template,$params){
	    foreach ($params as $search => $replace) {
	        $template = str_replace('{*'.$search.'*}', $replace, stripslashes($template));
	    }
	    return $template;

	}
	/**
	 * Mark the provided comment as an Facebook Connect comment
	 *
	 */
	function set_comment_fbconnect($comment_ID,$fb_user = 0) {
		global $wpdb, $fbconnect;

		$comments_table = WPfbConnect::comments_table_name();
		$wpdb->query("UPDATE $comments_table SET fbconnect='".$fb_user."' WHERE comment_ID='$comment_ID' LIMIT 1");
	}


    function set_userblog_fbconnect($userID)
    {
        global $wpdb, $wpmu_version;
        if(isset($wpmu_version)) {
            $caps = get_usermeta( $userID, $wpdb->prefix . 'capabilities');
            if ( empty($caps) || defined('RESET_CAPS') ) {
                update_usermeta( $userID, $wpdb->prefix . 'capabilities', array('subscriber' => true) );
            }
        }

    }

	/**
	 * Insert the first  login
	 *
	 */
	function set_firstlogin_fbconnect($userID) {
		global $wpdb, $fbconnect, $wpmu_version;
		$lastlogin_table = WPfbConnect::lastlogin_table_name();
		$netId = WPfbConnect::netId();
		$fb_blogid = 1;
		//////////////////////////////////////////////
		if($wpmu_version) {// If wordpress MU					
			$fb_blogid = $wpdb->blogid;
		}
		$fbconnect_lastlogin = date("U");
		$wpuserid = $userID;


        $result = $wpdb->query("INSERT INTO $lastlogin_table (wpuserid, blog_id ,	netid ,	fbconnect_lastlogin) VALUES ($wpuserid,$fb_blogid,'$netId',$fbconnect_lastlogin)");
        //$wpdb->insert( $friends_table, compact( 'wpuserid','fb_blogid','netId','fbconnect_lastlogin'));

	}
	
	
	/**
	 * Update last user login date
	 *
	 */
	function set_lastlogin_fbconnect($userID) {
		global $wpdb, $fbconnect, $wpmu_version;
		$lastlogin_table = WPfbConnect::lastlogin_table_name();
		$netId = WPfbConnect::netId();
		$fb_blogid = 1;
		//////////////////////////////////////////////
		if(isset($wpmu_version)) {// If wordpress MU
			$fb_blogid = $wpdb->blogid;
		}
		$result = $wpdb->query("UPDATE $lastlogin_table SET fbconnect_lastlogin=".date("U")." WHERE wpuserid='$userID' AND blog_id=$fb_blogid AND netid='$netId' LIMIT 1");
        if($result == 0)
            WPfbConnect_Logic::set_firstlogin_fbconnect($userID);
	}
	
	/**
	 * Get last users
	 *
	 */
	function get_lastusers_fbconnect($num = 10,$start=0) {
		global $wpdb, $fbconnect, $wpmu_version;
		$users_table = WPfbConnect::users_table_name();
		$lastlogin_table = WPfbConnect::lastlogin_table_name();
		$netId = WPfbConnect::netId();
		$fb_blogid = 1;
		if(isset($wpmu_version)) {// If wordpress MU
			$fb_blogid = $wpdb->blogid;
		}

		//$users = $wpdb->get_results("SELECT * FROM $users_table where ID=(SELECT wpuserid FROM wp_fb_lastlogin WHERE blog_id=$fb_blogid AND netid='$netId' ORDER BY fbconnect_lastlogin DESC LIMIT ".$start.",".$num.")");
        $users = $wpdb->get_results("SELECT users.* FROM $users_table users,$lastlogin_table lastlogin WHERE lastlogin.wpuserid =users.ID AND lastlogin.blog_id=$fb_blogid AND lastlogin.netid='$netId' ORDER BY lastlogin.fbconnect_lastlogin DESC LIMIT ".$start.",".$num);
		return $users;
	}

	/**
	 * User count
	 *
	 */
	function get_count_users() {
		global $wpdb, $fbconnect,$wpmu_version;
		$fb_blogid = 1;
        //////////////////////////////////////////////
        if($wpmu_version) {// If wordpress MU
              $fb_blogid = $wpdb->blogid;
        }
		
		$lastlogin_table = WPfbConnect::lastlogin_table_name();
		
		//$users = $wpdb->get_results("SELECT count(ID) as userscount FROM $lastlogin_table lasttable WHERE lasttable.blog_id=$fb_blogid");
		$users = $wpdb->get_results("SELECT count(wpuserid) as userscount FROM $lastlogin_table lasttable WHERE lasttable.blog_id=$fb_blogid");
		if (count($users)>0){
			return $users[0]->userscount;
		}else{
			return null;
		}
	}
		
	/**
	 * Get user by fbid
	 *
	 */
	function get_userbyFBID($fbid) {
		global $wpdb, $fbconnect;
		$users_table = WPfbConnect::users_table_name();
		$users = $wpdb->get_results("SELECT * FROM $users_table WHERE fbconnect_userid = ".$fbid." OR user_login=\"FB_".$fbid."\" OR user_login=\"fb".$fbid."\"");
		if (count($users)>0){
			$userresp = "";
			foreach($users as $user){
				if ($userresp=="" || ($user->fbconnect_userid!="" && $user->fbconnect_userid!="0")){
					$userresp = $user;
				}
			}
			
			return $userresp;
		}else{
			return null;
		}
	}
	
	/**
	 * Update Facebook userID
	 *
	 */
	function set_userid_fbconnect($userID,$fbuserid) {
		global $wpdb, $fbconnect;
		$users_table = WPfbConnect::users_table_name();
		$wpdb->query("UPDATE $users_table SET fbconnect_userid=".$fbuserid." WHERE ID='$userID' LIMIT 1");
	}

	/**
	 * Get community comments
	 *
	 */
	function get_community_comments($limit=10,$start=0) {
		global $wpdb;
		return $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->comments wpcomments, $wpdb->posts posts WHERE wpcomments.comment_post_ID=posts.ID AND wpcomments.comment_approved = '1' ORDER BY comment_date DESC LIMIT ".$start.",".$limit));
	}
	
	/**
	 * Count community comments
	 *
	 */
	function count_community_comments() {
		global $wpdb;
		$comments = $wpdb->get_results($wpdb->prepare("SELECT count(*) as commentcount FROM $wpdb->comments wpcomments, $wpdb->posts posts WHERE wpcomments.comment_post_ID=posts.ID AND wpcomments.comment_approved = '1' ORDER BY comment_date DESC"));
		if (count($comments)>0){
			return $comments[0]->commentcount;
		}else{
			return null;
		}
	}
	
	/**
	 * Get post comments
	 *
	 */
	function get_post_comments($limit=10,$postID="",$start=0) {
		if ($postID==""){
			return WPfbConnect_Logic::get_community_comments($limit,$start);
		}
		global $wpdb;
		return $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->comments wpcomments, $wpdb->posts posts WHERE posts.ID=$postID AND wpcomments.comment_post_ID=posts.ID AND wpcomments.comment_approved = '1' ORDER BY comment_date DESC LIMIT ".$start.",".$limit));
	}
	
	/**
	 * Count post comments
	 *
	 */
	function count_post_comments($postID="") {
		if ($postID==""){
			return WPfbConnect_Logic::count_community_comments();
		}
		global $wpdb;
		$comments = $wpdb->get_results($wpdb->prepare("SELECT count(comment_ID) as commentcount FROM $wpdb->comments wpcomments WHERE wpcomments.comment_post_ID=$postID AND wpcomments.comment_approved = '1' ORDER BY comment_date DESC "));
		if (count($comments)>0){
			return $comments[0]->commentcount;
		}else{
			return null;
		}

	}

	/**
	 * Get friends community comments
	 *
	 */
	function get_community_friends_comments($userID,$limit=10,$start=0) {
		global $wpdb,$wpmu_version;
        $fb_blogid = 1;
        //////////////////////////////////////////////
        if($wpmu_version) {// If wordpress MU
              $fb_blogid = $wpdb->blogid;
        }

		return $wpdb->get_results($wpdb->prepare("SELECT * FROM ".WPfbConnect::friends_table_name()." wpfriends,$wpdb->comments wpcomments, $wpdb->posts posts WHERE wpfriends.wpuserid=".$userID." AND wpfriends.blog_id=".$fb_blogid." AND wpfriends.wpfriendid=wpcomments.user_id AND wpcomments.comment_post_ID=posts.ID AND wpcomments.comment_approved = '1' ORDER BY comment_date DESC LIMIT ".$start.",".$limit));
	}
	
	/**
	 * Count friends community comments
	 *
	 */
	function count_community_friends_comments($userID) {
		global $wpdb,$wpmu_version;
        $fb_blogid = 1;
        //////////////////////////////////////////////
        if($wpmu_version) {// If wordpress MU
              $fb_blogid = $wpdb->blogid;
        }
		$comments = $wpdb->get_results($wpdb->prepare("SELECT count(*) as commentcount FROM ".WPfbConnect::friends_table_name()." wpfriends,$wpdb->comments wpcomments, $wpdb->posts posts WHERE wpfriends.wpuserid=".$userID." AND wpfriends.blog_id=".$fb_blogid." AND wpfriends.wpfriendid=wpcomments.user_id AND wpcomments.comment_post_ID=posts.ID AND wpcomments.comment_approved = '1' ORDER BY comment_date DESC"));
		if (count($comments)>0){
			return $comments[0]->commentcount;
		}else{
			return null;
		}
	}
		
	/**
	 * Get friends post comments
	 *
	 */
	function get_post_friends_comments($userID,$limit=10,$postID="",$start=0) {
		if ($postID==""){
			return WPfbConnect_Logic::get_community_friends_comments($userID,$limit,$start);
		}
		global $wpdb,$wpmu_version;
        $fb_blogid = 1;
        //////////////////////////////////////////////
        if($wpmu_version) {// If wordpress MU
              $fb_blogid = $wpdb->blogid;
        }

		return $wpdb->get_results($wpdb->prepare("SELECT * FROM ".WPfbConnect::friends_table_name()." wpfriends,$wpdb->comments wpcomments, $wpdb->posts posts WHERE wpfriends.wpuserid=".$userID." AND wpfriends.blog_id=".$fb_blogid." AND wpfriends.wpfriendid=wpcomments.user_id AND posts.ID=$postID AND wpcomments.comment_post_ID=posts.ID AND wpcomments.comment_approved = '1' ORDER BY comment_date DESC LIMIT ".$start.",".$limit));
	}
	
	/**
	 * Count friends post comments
	 *
	 */
	function count_post_friends_comments($userID,$postID="") {
		if ($postID==""){
			return WPfbConnect_Logic::count_community_friends_comments($userID);
		}

		global $wpdb,$wpmu_version;
        $fb_blogid = 1;
        //////////////////////////////////////////////
        if($wpmu_version) {// If wordpress MU
              $fb_blogid = $wpdb->blogid;
        }

		$comments = $wpdb->get_results($wpdb->prepare("SELECT count(*) as commentcount FROM ".WPfbConnect::friends_table_name()." wpfriends,$wpdb->comments wpcomments, $wpdb->posts posts WHERE wpfriends.wpuserid=".$userID." AND wpfriends.blog_id=".$fb_blogid." AND wpfriends.wpfriendid=wpcomments.user_id AND posts.ID=$postID AND wpcomments.comment_post_ID=posts.ID AND wpcomments.comment_approved = '1' ORDER BY comment_date DESC "));
		if (count($comments)>0){
			return $comments[0]->commentcount;
		}else{
			return null;
		}

	}
		
	/**
	 * Get user comments
	 *
	 */
	function get_user_comments($user_id,$limit=25) {
		global $wpdb;
		return $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->comments wpcomments, $wpdb->posts posts WHERE wpcomments.comment_post_ID=posts.ID AND wpcomments.fbconnect = %s AND wpcomments.comment_approved = '1' ORDER BY comment_date DESC LIMIT ".$limit, $user_id));
	}
	
	/**
	 * Get user comments
	 *
	 */
	function get_user_comments_byID($user_id,$limit=25) {
		global $wpdb;
		return $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->comments wpcomments, $wpdb->posts posts WHERE wpcomments.comment_post_ID=posts.ID AND wpcomments.user_id = %s AND wpcomments.comment_approved = '1' ORDER BY comment_date DESC LIMIT ".$limit, $user_id));
	}
	
	/**
	 * Get a comment by ID
	 *
	 */
	function get_comment_byID($comment_id) {
		global $wpdb;
		$comments = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->comments wpcomments, $wpdb->posts posts WHERE wpcomments.comment_post_ID=posts.ID AND wpcomments.comment_ID = %s", $comment_id));
		if ($comments != ""){
			return $comments[0];			
		}
	}

	/**
	 * Get post by external url
	 *
	 */
	function get_postByExternalURL($url) {
		$longitudCadena=strlen($url);
		$posicion=strrpos($url, "/");
		if($posicion==$longitudCadena-1)
			$url=substr($url,0,$posicion);

		$arrayPath=split("/",$url);
		$idConcursante=$arrayPath[count($arrayPath)-1];		
		
		global $wpdb;
		//return $wpdb->get_var($wpdb->prepare("SELECT post_id FROM $wpdb->postmeta WHERE meta_key='fb_external_url' AND meta_value='%s'",$url));
		$resp = $wpdb->get_var($wpdb->prepare("SELECT post_id FROM $wpdb->postmeta WHERE meta_key='idConcursante' AND meta_value='%s'",$idConcursante));
		if($resp!=""){
			return $resp;
		}
		$resp = $wpdb->get_var($wpdb->prepare("SELECT post_id FROM $wpdb->postmeta WHERE meta_key='fb_external_url' AND meta_value='%s'",$url));
		return $resp;
	}
	
	/**
	 * This filter callback simply approves all Facebook Connect comments
	 *
	 * @param string $approved comment approval status
	 * @return string new comment approval status
	 */
	function comment_approval($approved) {
		$fb_user = fb_get_loggedin_user();
		if ($fb_user) {
			return 1;
		}else{
			return $approved;
		}
	}

	function html_namespace($html_lang){
		return "xmlns:fb=\"http://www.facebook.com/2008/fbml\" ".$html_lang;
	}

	function fb_get_avatar($avatar=null, $id_or_email = null, $size = null, $default=null){
		$fbuser = "";
		$username = "";
		
		if ( is_numeric($id_or_email) ) {
			$id = (int) $id_or_email;
			$user = get_userdata($id);
			$username = $user->display_name;
			if ( $user && isset($user->fbconnect_userid) && $user->fbconnect_userid!=""){
					$fbuser = $user->fbconnect_userid;
			}
		} elseif ( is_object($id_or_email) ) {
			if ( !empty($id_or_email->fbconnect) && $id_or_email->fbconnect!="0" ) {
				$id = (int) $id_or_email->user_id;
				$fbuser = $id_or_email->fbconnect;
			}else if ( !empty($id_or_email->user_id) ) {
				$id = (int) $id_or_email->user_id;
				$user = get_userdata($id);	
				$username = $user->display_name;
				if ( $user && isset($user->fbconnect_userid) && $user->fbconnect_userid!=""){
					$fbuser = $user->fbconnect_userid;
				}
			}
		}else{
			global $comment;
			if (isset($comment) && $comment!="" && !empty($comment->fbconnect) && $comment->fbconnect!="0"){
				$fbuser = $comment->fbconnect;
				$id=$comment->user_id;
			}
		}

		$profileurl = get_option('siteurl')."/?fbconnect_action=myhome&amp;userid=%USERID%";
		$fb_custom_user_profile = get_option('fb_custom_user_profile');
		if (isset($fb_custom_user_profile) && $fb_custom_user_profile!=""){
			$profileurl = get_option('siteurl').$fb_custom_user_profile;
		}
		$profileurl = str_replace('%USERID%',$id,$profileurl);
		
		$showlogo = get_option('fb_connect_avatar_logo');
		if ($showlogo=="on"){
			$showlogo = "true";
		}else{
			$showlogo = "false";
		}
		
		$linked = get_option('fb_connect_avatar_link');
		if ($linked=="on" && $fbuser){
			$fb_current_user = fb_get_loggedin_user();
			if(get_option('fb_connect_use_thick') && $fb_current_user!="" && FBCONNECT_CANVAS=="web"){
				$linked = "false";
				$prelink ='<a title="'.__("User profile","fbconnect").'" class="thickbox" href="http://touch.facebook.com/#/profile.php?id='.$fbuser.'&amp;height='.FBCONNECT_TICKHEIGHT.'&amp;width='.FBCONNECT_TICKWIDTH.'&amp;TB_iframe=true">';
				$postlink ="</a>";				
			}else{
				//$linked = "true";
				$linked = "false";
				$prelink ='<a target="_blank" href="http://www.facebook.com/profile.php?id='.$fbuser.'">';
				$postlink ="</a>";
			}
		}else{
			if(get_option('fb_connect_use_thick') && FBCONNECT_CANVAS=="web"){
				$linked = "false";
				$prelink ='<a title="'.__("User profile","fbconnect").'" class="thickbox" href="'.$profileurl.'&amp;height='.FBCONNECT_TICKHEIGHT.'&amp;width='.FBCONNECT_TICKWIDTH.'">';
				$postlink ="</a>";				
			}else{
				$linked = "false";
				$prelink ="<a onclick=\"location.href='".$profileurl."';\" href=\"".$profileurl."\">";
				$postlink ="</a>";
			}
		}
				
		if ($fbuser != "" && $fbuser != 0){
			if (!isset($size) || $size==""){
				return $prelink."<fb:profile-pic class=\"avatar photo avatar-".avatar."\" facebook-logo=\"".$showlogo."\" uid=\"".$fbuser."\" size=\"square\" linked=\"".$linked."\"></fb:profile-pic>".$postlink;
			}else{
				return $prelink."<fb:profile-pic class=\"avatar photo avatar-".avatar."\" facebook-logo=\"".$showlogo."\" uid=\"".$fbuser."\" size=\"square\" width=\"".$size."\" height=\"".$size."\" linked=\"".$linked."\"></fb:profile-pic>".$postlink;
			}
		}elseif($id!=""){
			return $prelink.$avatar.$postlink;
		}

		return $avatar;
	}


	function add_wall_comment($comment,$comment_type) {
		global $wpdb;
		$comment_post_ID = get_option('fb_wall_page');
		$wall_post=get_post($comment_post_ID);
		if (!$wall_post){
			return;
		}
		$user_ID = "";
		$comment_author       = "";
		$comment_content      = $comment;
		$comment_author_email = "";
	
		$user = wp_get_current_user();
		if ( $user->ID ) {
	 	  $user_ID = $user->ID;
		  $comment_author  = $wpdb->escape($user->display_name);		
		  $comment_author_email = $wpdb->escape($user->user_email);
		}else{
			return;
		}
		$commentdata = compact('comment_post_ID', 'comment_author', 'comment_author_email', 'comment_content', 'user_ID','comment_type');
		$comment_id = wp_new_comment( $commentdata );
	}
	
	function set_error($error) {
		$_SESSION['fb_error'] = $error;
		return;
	}
	
	function get_user(){
		$fb_user ="";
		$wpuser ="";
		if (isset($_REQUEST['userid']) && $_REQUEST['userid']!=""){
			$wpuser = $_REQUEST['userid'];
		}elseif (isset($_REQUEST['fbuserid']) && $_REQUEST['fbuserid']!=""){
			$fb_user = $_REQUEST['fbuserid'];
		}elseif(isset($_REQUEST['fb_sig_profile_user']) && $_REQUEST['fb_sig_profile_user']!=""){
			$fb_user = $_REQUEST['fb_sig_profile_user'];
		}else{
			$fb_user = fb_get_loggedin_user();
		}

		if ($fb_user!=""){
			$userprofile = WPfbConnect_Logic::get_userbyFBID($fb_user);
		}elseif($wpuser){
			$userprofile= get_userdata($wpuser);
		}
		return $userprofile;
	}
	

	function fbconnect_init_scripts(){
		if (FBCONNECT_CANVAS=="web") {

			//echo "<script src='".get_static_root()."/js/api_lib/v0.4/FeatureLoader.js.php/es_ES' type='text/javascript'></script>\n";
			//echo '<script src="http://static.ak.fbcdn.net/connect/en_US/core.js"></script>';
			$lang= "en_US";
			if (WPLANG!=""){
				$lang = WPLANG;	
			}
			$requestperms="";	
			if (get_option('fb_permsToRequestOnConnect')!=""){
				$requestperms = 'permsToRequestOnConnect : "'.get_option('fb_permsToRequestOnConnect').'",';
			}

			?>
			<div id="fb-root"></div>
			
			<script type='text/javascript'>
			var tb_pathToImage = "<?php echo get_option('siteurl');?>/wp-includes/js/thickbox/loadingAnimation.gif";
			var tb_closeImage = "<?php echo get_option('siteurl');?>/wp-includes/js/thickbox/tb-close.png";
			var fb_userid="";
	
	   		//FB.init("<?php echo get_api_key();?>", "<?php echo FBCONNECT_PLUGIN_URL;?>/xd_receiver.htm");
			//			    appId: "30698522313",
			
			window.fbAsyncInit = function() {
			  FB.init({
			  	<?php 
			  	if (get_appId()!=""){
					echo "appId: '".get_appId()."',";
				}else{
				  	echo "apiKey: '".get_api_key()."',";
				}?>
			    xfbml: true,
			    cookie: true,
			    status: true
			  });
			  FB.getLoginStatus(handleSessionResponse);

			};
			(function() {
			  var e = document.createElement('script'); e.async = true;
			  e.src = document.location.protocol + '//connect.facebook.net/<?php echo $lang;?>/all.js';
			 //e.src ='http://static.ak.fbcdn.net/connect/en_US/core.js';
			  document.getElementById('fb-root').appendChild(e);
			}());

			function handleSessionResponse(response){
				        if (!response.session) {
									//fb_show("fbloginbutton");
									FB.XFBML.parse();
									return;        
						}
						<?php fb_streamPublishDialog();     ?>  
			}
			
			//document.onload = "FB.XFBML.parse()";
<?php
/*
echo  'window.fbAsyncInit = function() {
 FB.init({
   appId: "30698522313", xfbml: true
 });
};';*/
//echo  'document.onload = "FB.XFBML.parse()";';
			$fb_user = fb_get_loggedin_user();
			$uri = "";
			if (isset($_SERVER["REQUEST_URI"])){
				$uri = $_SERVER["REQUEST_URI"];			
			}
				
	/*		if (!$fb_user){
				echo "	    FB.Facebook.get_sessionState().waitUntilReady(function(session){\n";
				echo "      var is_now_logged_into_facebook = session ? true : false;\n";
				echo "      alert(\">\"+ is_now_logged_into_facebook);\n" ;
				echo "		if (is_now_logged_into_facebook){\n";
				echo "  		window.location = \"".$uri."\";}\n";
			  	echo "});\n";
			}*/
	//	    echo "});\n";

		   	echo "</script>\n";
			//fb_showFeedDialog();
		}else{
			echo "<script>\n";
			include FBCONNECT_PLUGIN_PATH.'/pro/fbconnect_canvas.js';
			echo "	</script>";
		}
		
		//fb_streamPublishDialog();
	}

function fbc_remove_nofollow($anchor) {
  global $comment;
  // Only remove for facebook comments, since url is trusted
  // Adam Hupp FB Connect Plugin
  $newanchor = $anchor;
  if ($comment->user_id && $comment->fbconnect!="" && $comment->fbconnect!="0") {
   $newanchor = preg_replace('/ rel=[\"\'](.*)nofollow(.*?)[\"\']/', ' rel="$1 $2" ', $anchor);
  }
  $linked = get_option('fb_connect_avatar_link');
  if(get_option('fb_connect_use_thick') && FBCONNECT_CANVAS=="web"){
	  $newanchor = preg_replace('/ class=[\"\'](.*)[\"\']/', ' class="$1 thickbox" ', $newanchor);
  }elseif ($linked=="on"){
    $newanchor = preg_replace('/ class=[\"\'](.*)[\"\']/', ' target="_blank" class="$1" ', $newanchor);
  }
  return $newanchor;
}

//Replace user comments url with profile url
function get_comment_author_url($url){
	global $comment;
	if ($comment->user_id!="" && $comment->user_id!="0"){
		$addthickboxsize= "";
		$fb_current_user = fb_get_loggedin_user();
		$linked = get_option('fb_connect_avatar_link');
		
		if(get_option('fb_connect_use_thick') && FBCONNECT_CANVAS=="web" && $fb_current_user!="" && $linked=="on"){
			$addthickboxsize= "&height=".FBCONNECT_TICKHEIGHT."&width=".FBCONNECT_TICKWIDTH."&TB_iframe=true";
		}elseif(get_option('fb_connect_use_thick') && FBCONNECT_CANVAS=="web"){
			$addthickboxsize= "&height=".FBCONNECT_TICKHEIGHT."&width=".FBCONNECT_TICKWIDTH;			
		}
		
		if ($linked=="on"){
			if ($comment->fbconnect!="" && $comment->fbconnect!="0" && $fb_current_user!="" && get_option('fb_connect_use_thick')){
				return "http://touch.facebook.com/#/profile.php?id=".$fbuser.$addthickboxsize;
			}elseif ($comment->fbconnect!="" && $comment->fbconnect!="0"){
				return "http://www.facebook.com/profile.php?id=".$comment->fbconnect.$addthickboxsize;
			}else{
				return $url;
			}
		}else{
			return get_option('siteurl')."/?fbconnect_action=myhome&amp;userid=".$comment->user_id.$addthickboxsize;		
		}
	}
	if ($comment->user_id!="" && $comment->user_id!="0"){
		return get_option('siteurl')."/?fbconnect_action=myhome&amp;userid=".$comment->user_id.$addthickboxsize;
	}
	return $url;
}

} 
endif; // end if-class-exists test

?>
