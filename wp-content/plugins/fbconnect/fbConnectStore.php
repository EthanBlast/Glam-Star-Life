<?php
/**
 * @author: Javier Reyes Gomez (http://www.sociable.es)
 * @date: 05/10/2008
 * @license: GPLv2
 */

if (!class_exists('WPfbConnect_Store')):
class WPfbConnect_Store {
	var $comments_table_name;
	var $usermeta_table_name;
	var $users_table_name;
	var $friends_table_name;
	var $last_login_table_name;

	function WPfbConnect_Store()
	{
		global $wpdb;          
        		

		$this->comments_table_name =  $wpdb->comments;
		$this->usermeta_table_name =  $wpdb->usermeta;
		$this->users_table_name =  $wpdb->users;
        
		$aux=$wpdb->users;
        $pos = strpos($aux, "users");
		$this->friends_table_name =  substr($aux, 0, $pos) . 'fb_friends';
		$this->last_login_table_name = substr($aux, 0, $pos) . 'fb_lastlogin';

	
	}

	function isError($value)
	{
		return $value === false;
	}

	/**
	 * Create Facebook Connect columns and tables
	 */
	function create_tables(){
		global $wp_version, $wpdb, $fbconnect;

        if ( !function_exists('wp_get_current_user') )
            require_once(ABSPATH . 'wp-includes/pluggable.php');
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		/*
         *
        if ($wp_version >= '2.3') {
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		} else {
			require_once(ABSPATH . 'wp-admin/admin-db.php');
			require_once(ABSPATH . 'wp-admin/upgrade-functions.php');
		}
         * */
         

		// add column to comments table
		$result = maybe_add_column($this->comments_table_name, 'fbconnect',
				"ALTER TABLE $this->comments_table_name ADD `fbconnect` varchar(50) NOT NULL DEFAULT '0'");

		// add column to users table
		$result = maybe_add_column($this->users_table_name, 'fbconnect_lastlogin',
				"ALTER TABLE $this->users_table_name ADD `fbconnect_lastlogin` int(14) NOT NULL DEFAULT '0'");

		// add column to users table
		$result = maybe_add_column($this->users_table_name, 'fbconnect_userid',
				"ALTER TABLE $this->users_table_name ADD `fbconnect_userid` varchar(250) NOT NULL DEFAULT '0'");
		
		$sql = "CREATE TABLE IF NOT EXISTS " . $this->friends_table_name . " (
			userid varchar(250) NOT NULL default '',
  			friendid varchar(250) NOT NULL default '',
  			wpuserid bigint(20) NOT NULL default '0',
  			wpfriendid bigint(20) NOT NULL default '0',
  			netid varchar(250) NOT NULL default 'facebook',
            blog_id bigint(20) NOT NULL,
  			PRIMARY KEY  (wpuserid,wpfriendid,netid,blog_id)
			);";
		
		dbDelta($sql);
		
		
		$sql = "CREATE TABLE IF NOT EXISTS " . $this->last_login_table_name . " (
			wpuserid bigint(20) unsigned NOT NULL,
			blog_id bigint(20) NOT NULL,
			netid varchar(250) NOT NULL default 'facebook',  
  			fbconnect_lastlogin int(14) NOT NULL DEFAULT '0',
  			PRIMARY KEY  (wpuserid,blog_id,netid),
			KEY `blog_id` (`blog_id`,`netid`)
			);";		
		
		dbDelta($sql);		
		
	}
	
	/**
	 * Update Database
	 */
	function update_tables(){
		global $wp_version, $wpdb, $fbconnect;

        if ( !function_exists('wp_get_current_user') )
            require_once(ABSPATH . 'wp-includes/pluggable.php');
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

		if( get_option('fb_db_revision') < 15 ) {
			$this->create_tables();
			
			$sql = "INSERT INTO $this->last_login_table_name (wpuserid, blog_id, netid, fbconnect_lastlogin) SELECT ID,'1','facebook', fbconnect_lastlogin  FROM $this->users_table_name";
			$resp = $wpdb->query($sql);	
			
			$result = maybe_add_column($this->friends_table_name, 'fbconnect',
				"ALTER TABLE " . $this->friends_table_name . " ADD `blog_id` BIGINT( 20 ) NOT NULL DEFAULT '1' AFTER `netid`");
            
            $sql="ALTER TABLE " . $this->friends_table_name . " DROP PRIMARY KEY , ADD PRIMARY KEY ( `wpuserid` , `wpfriendid` , `netid` , `blog_id` ) ";

            $resp = $wpdb->query($sql);		            
		}
		
	}
	
	function migration_adamplugin(){
		global $wp_version, $wpdb, $fbconnect;
		$response ="Migrating users ids...<br/>";
		$sql = "UPDATE $wpdb->users usertable SET fbconnect_userid=(SELECT meta_value FROM $wpdb->usermeta usermeta WHERE meta_key=\"fbuid\" AND usermeta.user_id =usertable.ID) WHERE EXISTS (select  * from $wpdb->usermeta usermeta WHERE usermeta.meta_key=\"fbuid\" AND usermeta.user_id =usertable.ID)";

		$resp = $wpdb->query($sql);
		$response .="Users migrated: ".$resp."<br/>";
		
		$response .="Migrating last login dates...<br/>";
		$lastlogin = date("U");
		$sql = "UPDATE $wpdb->users usertable SET fbconnect_lastlogin=$lastlogin WHERE fbconnect_lastlogin=0";
		$resp = $wpdb->query($sql);
		$response .="Dates migrated: ".$resp."<br/>";

		$response .="Migrating comments userid...<br/>";
		$sql = "UPDATE $wpdb->comments comments SET fbconnect=(SELECT meta_value FROM $wpdb->usermeta usermeta WHERE meta_key=\"fbuid\" AND usermeta.user_id =comments.user_id AND comments.fbconnect=\"\") WHERE EXISTS (select  * from $wpdb->usermeta usermeta WHERE usermeta.meta_key=\"fbuid\" AND usermeta.user_id =comments.user_id AND comments.fbconnect=\"\")";
		$resp = $wpdb->query($sql);
		$response .="Comments migrated: ".$resp."<br/>";

		$response .="Migrating last login table...<br/>";
		$sql = "INSERT INTO $this->last_login_table_name (wpuserid, blog_id, netid, fbconnect_lastlogin) SELECT ID,'1','facebook', fbconnect_lastlogin  FROM $this->users_table_name";
		$resp = $wpdb->query($sql);
		$response .="Dates migrated: ".$resp."<br/>";
		
		$response .="Migrating application info...<br/>";
		$app_key = get_option('fbc_app_key_option');
		$app_secret = get_option('fbc_app_secret_option');
		$app_key_new = get_option('fb_api_key');
		$app_secret_new = get_option('fb_api_secret');
		if ($app_key_new=="" && $app_secret_new==""){
			update_option('fb_api_key', $app_key);
			update_option('fb_api_secret', $app_secret);
			$response .="Migrated fb_api_key and fb_api_secret...<br/>";
		}
		
		return $response;
	}
}
endif;
?>