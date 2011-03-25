<?php
function headway_copy_folder($src, $dst, $use_ftp = false) {
	//Credit: http://ca.php.net/manual/en/function.copy.php#91010
	$dir = opendir($src);
	
	if($use_ftp){
		global $wp_filesystem;
		
		$ftp_src = $wp_filesystem->find_folder($src);
		$ftp_dst = $wp_filesystem->find_folder($dst);
								
		$wp_filesystem->mkdir($ftp_src);
	} else {
		@mkdir($dst);
	}
	
	while(false !== ($file = readdir($dir))) {
		if (( $file != '.' ) && ( $file != '..' )) {
			if($use_ftp){
				if ( $wp_filesystem->is_dir($ftp_src . '/' . $file) ) {
					headway_copy_folder($src . '/' . $file,$dst . '/' . $file, true);
				}
				else {					
					if(!$wp_filesystem->copy($ftp_src . $file, $ftp_dst . $file, true)) return false;
				}
			} else {
				if ( is_dir($src . '/' . $file) ) {
					headway_copy_folder($src . '/' . $file,$dst . '/' . $file);
				}
				else {
					if(!copy($src . '/' . $file, $dst . '/' . $file)) return false;
				}
			}
		}
	}
	closedir($dir);
	
	return true;
}


function headway_download_latest_version($transfer_custom_files = false, $use_ftp = false) { 
	global $headway_force_queries;
	$headway_force_queries = true;
	
	//Fetch latest version from Headway servers
	$latest_version = headway_latest_version();
	$latest_version_english = headway_latest_version_nice();
	$latest_version_for_folder = str_replace('.', '', $latest_version[0]);
	
	if(headway_check_for_updates() == 'beta')
		$latest_version_for_folder .= 'b'.$latest_version[2];
		
	if(!$latest_version) return false;
		
	//Set Up Options
	$upgrade_path = ABSPATH . 'wp-content/upgrade';
	$theme_path = ABSPATH.'wp-content/themes';
	$folder_name = 'headway-'.$latest_version_for_folder;
	$target = $theme_path.'/'.$folder_name;
	$zip = $folder_name.'.zip';
	
	if(headway_check_for_updates() == 'beta'){
		$url = 'http://headwaythemes.com/upgrade/developer-files/'.$zip;		
	} else {
		$url = 'http://headwaythemes.com/upgrade/files/'.$zip;
	}	
		
	if(function_exists('file_put_contents')){
		//Check if cURL exists
		if(!function_exists('curl_init')){
			echo '<p>Your host must support the <strong>cURL PHP library</strong> to use the Headway automatic update functionality.  Please contact your web host for further assistance.</p>';
			return false;
		}
			
		//Check if WordPress upgrade directory is present	
		if(!is_dir($upgrade_path)){
			!@mkdir($upgrade_path);
			!@chmod($upgrade_path);
		}
				
		if($use_ftp){
			global $wp_filesystem;
			
			$ftp_options['hostname'] = $_POST['ftp-hostname'];
			$ftp_options['username'] = $_POST['ftp-username'];
			$ftp_options['password'] = $_POST['ftp-password'];
			$ftp_options['connection_type'] = 'ftpext';
						
			if (!WP_Filesystem($ftp_options)) {
				$error = true;
				if ( is_object($wp_filesystem) && $wp_filesystem->errors->get_error_code() )
					$error = $wp_filesystem->errors;
								
				echo '<p><strong>ERROR:</strong> We were unable to connect to your FTP server with the info you provided.  Please click <a href="javascript: history.go(-1)">here</a> and verify the validity of your FTP credentials.</p>';
				return false;
			}
			
			
			$ftp_upgrade_path = $wp_filesystem->find_folder($upgrade_path);			
			$ftp_themes_path = str_replace('/upgrade/', '/themes/', $wp_filesystem->find_folder($upgrade_path));
									
			if(!is_dir($upgrade_path)){
				$wp_filesystem->mkdir($ftp_upgrade_path.'/', 0777);
			} elseif(!is_writable($upgrade_path)) {	
				$wp_filesystem->chmod($ftp_upgrade_path.'/', 0777);			
			}
												
			$wp_filesystem->chmod($ftp_themes_path.'/', 0777);			
		}
		
		//Create temporary zip filename
	    $temp_zip = $upgrade_path.'/'.md5($zip).'.zip';
	
	    $connection = curl_init();
	
		$username = strtolower(headway_get_option('headway-username'));
		$password = headway_get_option('headway-password');
		
		//Setup cURL and .htaccess login
		curl_setopt($connection, CURLOPT_URL, $url);
		curl_setopt($connection, CURLOPT_USERPWD, $username.':'.$password);
		curl_setopt($connection, CURLOPT_HEADER, 0);
		curl_setopt($connection, CURLOPT_RETURNTRANSFER, 1);

		echo '<p>Downloading Headway '.headway_latest_version_nice().'.</p>';
				
		//Download zip
	    $downloaded_zip = curl_exec($connection);
			
		//If zip can be downloaded then extract
		if(curl_getinfo($connection, CURLINFO_HTTP_CODE) == 200){
			//Create tempoary zip and insert downloaded zip contents into it
			touch($temp_zip);
		    file_put_contents($temp_zip, $downloaded_zip);

			//Open zip
		    require_once(ABSPATH . 'wp-admin/includes/class-pclzip.php');
			$zip = new PclZip($temp_zip);
		
		    if($zip){ 
				echo '<p>Extracting...</p>';
			
			 	//Check if theme directory already exists.  If so, add some random numbers to the end.
				if(is_dir($target)){
					$rand = '-'.rand(0,9999);
					$target = $target.$rand;
				} else {
					$rand = false;
				}
			
				//Delete nasty Mac directories
				$zip->delete(PCLZIP_OPT_BY_NAME, '__MACOSX/');
				
				//If not using FTP, use the normal extract method in PCLZIP, otherwise, use WordPress' unzip function which uses FTP.
				if(!$use_ftp){
					$extract = $zip->extract(PCLZIP_OPT_PATH, $target);
					if($extract[0]['status'] == 'path_creation_fail'){
						echo '<p><strong>ERROR:</strong> Unable to extract theme to themes directory.</p>';
						return false;
					}
				} else {				
					$zip_path = $temp_zip;
					$theme_path = $ftp_themes_path.$folder_name.$rand.'/';
																					
					$wp_filesystem->mkdir($theme_path);				
					$unzip = unzip_file($zip_path, $theme_path);
															
					if(is_wp_error($unzip)){
						echo '<p><strong>ERROR:</strong> Unable to extract theme.</p>';
					}
				}
					
				//Remove nasty Mac directories			
				@rmdir($target.'/__MACOSX');
			
				//Delete temporary zip
		        @unlink($temp_zip); 
		
			
				if($transfer_custom_files){
					//If transferring custom files and not using FTP, use the direct filesystem system, otherwise, use WordPress' FTP filesystem.
					if(!$use_ftp){
						if(!@copy(TEMPLATEPATH.'/custom.css', $target.'/custom.css')) $custom_files_copy_errors[] = '<strong>ERROR:</strong> Unable to copy custom.css to the new theme directory.';
						if(!@headway_copy_folder(TEMPLATEPATH.'/custom', $target.'/custom')) $custom_files_copy_errors[] = '<strong>ERROR:</strong> Unable to copy custom folder to the new theme directory.';
					} else {
						if(!$wp_filesystem->copy($wp_filesystem->find_folder(TEMPLATEPATH).'custom.css', $theme_path.'custom.css', true)) $custom_files_copy_errors[] = '<strong>ERROR:</strong> Unable to copy custom.css to the new theme directory.';
						if(!headway_copy_folder(TEMPLATEPATH.'/custom', $target.'/custom', true)) $custom_files_copy_errors[] = '<strong>ERROR:</strong> Unable to copy custom folder to the new theme directory.';
					}
					
					echo '<p>Copying custom.css and custom directory to new theme directory.</p>';
					
					if(isset($custom_files_copy_errors) && is_array($custom_files_copy_errors)){
						foreach($custom_files_copy_errors as $error){
							echo '<p>'.$error.'</p>';
						}
					}
				}
		 
				echo '<h3>Success!</h3>';
				
				//Change permission
				if($use_ftp){
					//Change permissions of theme folder, upgrade folder, and cache folder (and contents) to the proper permissions.
					global $wp_filesystem; 
										
					$wp_filesystem->chmod($ftp_themes_path, 0755);
					$wp_filesystem->chmod($ftp_upgrade_path, 0755);
					
					$wp_filesystem->chmod($ftp_themes_path.$folder_name.$rand.'/media/cache/', 0777, true);
				} else {
					//Change permissions of cache folder and contents.
					@chmod($target.'/media/cache/', 0777);
					@chmod($target.'/media/cache/headway.css', 0777);
					@chmod($target.'/media/cache/leafs.css', 0777);
					@chmod($target.'/media/cache/scripts.js', 0777);
					@chmod($target.'/media/images/', 0777);
				}
				
				$theme_id = $folder_name.$rand;
				
				echo '<p>Click <a href="'.get_bloginfo('wpurl').'/wp-admin/admin.php?page=headway-tools&amp;activate-theme='.$theme_id.'">here</a> to activate the updated version of Headway.</p>';
				return true;
		    } else {
		        return false;
		    }  
		} else {
			echo '<p><strong>Error!</strong> You have entered an invalid username and password combination for your Headway login.  Please double check your login.  If upgrading to a beta version, insure that you own a developer\'s license or personal-to-developer\'s upgrade.  You can verify your Headway credentials by clicking <a href="'.get_bloginfo('wpurl').'/wp-admin/admin.php?page=headway#headway-registration">here</a>.</p>';
			return false;
		}
	} else {
		echo '<p>You must have <strong>PHP 5</strong> or greater to use the Headway automatic update functionality.</p>';
		return false;
	}
}


function headway_is_ftp_required(){
	$rand = md5(rand(0, 9999));
	
	if(@touch(ABSPATH.'wp-content/upgrade/'.$ftp_rand.'.test') && @touch(ABSPATH.'wp-content/themes/'.$ftp_rand.'.test')){
		@unlink(ABSPATH.'wp-content/upgrade/'.$ftp_rand.'.test');
		@unlink(ABSPATH.'wp-content/themes/'.$ftp_rand.'.test');
		
		return false;
	} else {
		//Delete files if one of them got through.
		@unlink(ABSPATH.'wp-content/upgrade/'.$ftp_rand.'.test');
		@unlink(ABSPATH.'wp-content/themes/'.$ftp_rand.'.test');
		
		return true;
	}
}