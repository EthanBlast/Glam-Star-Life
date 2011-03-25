<?php
global $headway_admin_success;

if(isset($headway_admin_success) && $headway_admin_success === true) {
?>
<div class="success"><span>Headway Reset!</span> <p>Your Headway settings have been successfully reset.</p></div>
<meta http-equiv="refresh" content="0;URL=<?php echo get_bloginfo('wpurl').'/wp-admin/admin.php?page=headway-tools'; ?>" />
<?php
} elseif($_FILES['import'] && !isset($_POST['update-headway'])){		
	if(!wp_verify_nonce($_POST['headway-admin-nonce'], 'headway-admin-nonce')) die('Security nonce does not match.');
	
	$tmp_file = $_FILES['import']['tmp_name'];

	$handle = fopen($tmp_file, 'rb');
	$file_contents = fread($handle, filesize($tmp_file));
	$contents = headway_json_decode($file_contents);

	$what = (isset($contents['what'])) ? true : false;
	
	if($what){
		foreach($contents['options'] as $option => $value){
			headway_update_option($option, stripslashes($value));
		}
	
		switch($what){
			case 'configuration':
				echo '<div class="success"><span>Import Successful!</span> <p>Your Headway Configuration has been successfully imported!</p></div>';
			break;
	
			case 'seo-settings':
				echo '<div class="success"><span>Import Successful!</span> <p>Your Headway SEO Settings have been successfully imported!</p></div>';
			break;
	
			case 'visual-editor-settings':
				echo '<div class="success"><span>Import Successful!</span> <p>Your Headway Visual Editor Settings have been successfully imported!</p></div>';
			break;
	
			case 'all-settings':
				echo '<div class="success"><span>Import Successful!</span> <p>Your full Headway import file has been successfully imported!</p></div>';
			break;
		}
		
		fclose($handle);
	} elseif(isset($contents['styles'])){
		headway_import_style(array('file_contents' => $file_contents));
	
		echo '<div class="success"><span>Import Successful!</span> <p>Your Headway style has been imported!</p></div>';
		
		fclose($handle);
	} elseif(isset($contents['leafs'])){
		headway_import_leaf_template(array('file_contents' => $file_contents));
	
		echo '<div class="success"><span>Import Successful!</span> <p>Your Headway leaf template has been imported!</p></div>';
		
		fclose($handle);
	} else {
		echo '<p class="notice"><strong>Error!</strong> The file you uploaded does not appear to be a valid Headway import file.</p>';
	}

}
?>

<form method="post" enctype="multipart/form-data">
	<input type="hidden" value="<?php echo wp_create_nonce('headway-admin-nonce'); ?>" name="headway-admin-nonce" />
	
	<div id="headway-tab-container">
	<div id="tabs">
		<ul>
			<?php if(is_main_site()){ ?>
			<li><a href="#headway-update-tab">Update</a></li>
			<?php } ?>
			<li><a href="#headway-import-tab">Import</a></li>				
			<li><a href="#headway-export-tab">Export/Backup</a></li>				
			<li><a href="#headway-safe-mode-tab">Safe Mode</a></li>				
			<li><a href="#headway-reset-tab">Reset</a></li>		
			<?php if(is_main_site()){ ?>		
			<li><a href="#headway-system-info-tab">System Info</a></li>	
			<?php } ?>			
		</ul>
		
		<?php if(is_main_site()){ ?>
		<!-- Start Update Panel -->
		<div id="headway-update-tab" class="tab">

			<?php 
			//If Update Button Pressed
			if(isset($_POST['update-headway'])){ 
				$transfer_custom_files = isset($_POST['transfer-custom-files']) ? true : false;
				$use_ftp = isset($_POST['ftp-username']) ? true : false;
				
				if(isset($_POST['headway-username']) && isset($_POST['headway-password'])){
					headway_update_option('headway-username', $_POST['headway-username']);
					headway_update_option('headway-password', $_POST['headway-password']);
				}
				
				echo '<h2>Upgrading...</h2>';
				
				headway_download_latest_version($transfer_custom_files, $use_ftp);
			//If button not pressed, and updates are available
			} elseif(headway_check_for_updates()){
				$credentials_exist = (headway_get_option('headway-username') && headway_get_option('headway-password')) ? true : false;
				
				$latest_version = headway_latest_version();

				echo '<h2>Update Available!</h2>';
				echo '<p>Headway '.headway_latest_version_nice().' is available, you are running '.headway_current_version().'.<p>';
				
				if(headway_check_for_updates() == 'beta'){
					echo '<p><strong>Important!</strong> Before upgrading, be sure to read the change notes in the <a href="http://support.headwaythemes.com" target="_blank">forums</a>.</p>';
				} else {
					echo '<p><strong>Important!</strong> Before upgrading, be sure to read the change notes in the <a href="http://support.headwaythemes.com" target="_blank">forums</a>.</p>';				
				} 
			?>
				
				<p class="border-top" style="margin: 20px 0 0;padding-top: 20px;font-weight:bold;font-style: italic;">Click the button below to start the update.</p>
				
				<table class="form-table" style="margin: 10px 0;">
					<tr valign="top">
						<td colspan="2"> 
							<fieldset>
								<legend class="hidden">Transfer Custom Files</legend>
								<label for="transfer-custom-files">
									<input type="checkbox" value="1" id="transfer-custom-files" name="transfer-custom-files" checked="checked" />									
									Transfer Custom Files
								</label>
							</fieldset>
							<span class="description">Keep this checked if you wish to transfer existing custom.css and custom folder (includes custom functions and images.) from this installation of Headway to the next.</span></td>

						</td>
					</tr>
					
					<?php
					//If Headway credentials are not in the database, display the form
					if(!$credentials_exist){
					?>
					<tr>
						<th scope="row"><label for="headway-username">Headway Username</th>
						<td><input type="text" class="regular-text" value="" id="headway-username" name="headway-username" />
						</td>
					</tr>

					<tr>
						<th scope="row"><label for="headway-password">Headway Password</th>
						<td><input type="password" class="regular-text" value="" id="headway-password" name="headway-password" />
						</td>
					</tr>
					<?php
					}
					 
					//Test if FTP is necessary
					if(headway_is_ftp_required()){
						$ftp_credentials = get_option('ftp_credentials', array( 'hostname' => '', 'username' => ''));

						$ftp_credentials['hostname'] = defined('FTP_HOST') ? FTP_HOST : $ftp_credentials['hostname'];
						$ftp_credentials['username'] = defined('FTP_USER') ? FTP_USER : $ftp_credentials['username'];
						$ftp_credentials['password'] = defined('FTP_PASS') ? FTP_PASS : '';
					?>
					<tr>
						<th scope="row"><label for="ftp-hostname">FTP Hostname</th>
						<td><input type="text" class="regular-text" value="<?php echo $ftp_credentials['hostname'] ?>" id="ftp-hostname" name="ftp-hostname" />
						</td>
					</tr>

					<tr>
						<th scope="row"><label for="ftp-username">FTP Username</th>
						<td><input type="text" class="regular-text" value="<?php echo $ftp_credentials['username'] ?>" id="ftp-username" name="ftp-username" />
						</td>
					</tr>
					
					<tr>
						<th scope="row"><label for="ftp-password">FTP Password</th>
						<td><input type="password" class="regular-text" value="<?php echo $ftp_credentials['password'] ?>" id="ftp-password" name="ftp-password" />
						</td>
					</tr>
					<?php 
					}
					?>
					
				</table>
				
				<input type="submit" value="Update Headway" name="update-headway" id="update-headway" class="button-secondary" />

			<?php 
			//If no updates are available, display the success message.
			} else { 
			?>
				<h2>Your installation of Headway is already up-to-date!</h2>
				
				<p>Woot!  We appreciate your efforts in keeping your Headway installation up-to-date.  Be sure to check back here occasionally to get the latest and greatest features, enhancements, and of course, bug fixes.</p>
			<?php } ?>

		</div>
		<!-- End Update Panel -->
		<?php } ?>
		
		<!-- Start Import Panel -->
		<div id="headway-import-tab" class="tab">
			<h2>Import</h2>
	
				<p>Have a export/backup file to upload?  Browse to it using the uploader below, click Import Files, and we'll handle the rest.</p>
				
				<input type="file" id="import" name="import" />
								
				<div class="border-top" style="margin-top:10px;"><input type="submit" value="Import Files" name="import-button" id="import-button" class="button-secondary" tabindex="305" /></div>
		
		</div>
		<!-- End Import Panel -->
		
		<!-- Start Export Panel -->
		<div id="headway-export-tab" class="tab">
			<h2>Export</h2>
		
				<h3>Configuration</h3>
				<p>This includes all of the Headway Configuration options (General, Posts and Comments, etc), but will exclude the search engine optimization settings and Headway registration.</p>
				
				<p>
					<input type="submit" value="Export Configuration" name="export-configuration-button" id="export-configuration-button" class="export-button button-secondary" tabindex="305" />
				</p>
				
				
				<h3 class="border-top">SEO Settings</h3>
				<p>SEO settings that are in the Headway Configuration panel.  This does <strong>not</strong> include specific post/page SEO settings.</p>
				<p>
					<input type="submit" value="Export SEO Settings" name="export-seo-settings-button" id="export-seo-settings-button" class="export-button button-secondary" tabindex="305" />
				</p>
								
				<h3 class="border-top"><em>Visual Editor:</em> Header, Footer, Navigation, and Site Dimensions</h3>
				<p>Options in the Header, Footer, Navigation (does not include navigation order), and Site Dimensions panels in the visual editor.</p>
				<p>
					<input type="submit" value="Export Visual Editor Settings" name="export-visual-editor-settings-button" id="export-visual-editor-settings-button" class="export-button button-secondary" tabindex="305" />
				</p>
				
				<h3 class="border-top"><em>Visual Editor:</em> Style</h3>
				<p>Choose a style using the select box below and click <em>Export Style</em> to export the style you select.</p>
				<select id="export-style-selector" style="margin-bottom: 10px;">
					<?php
					$styles = headway_get_option('styles');

					if(is_array($styles)){

						foreach($styles as $style => $options){					
							$style = str_replace($options['style-name'].'-', '', $style);

							echo '<option value="style-'.$style.'">'.$options['style-name'].'</option>';
						}

					}
					?>
				</select>
				
				<p>
					<input type="submit" value="Export Style" name="export-style-button" id="export-style-button" class="button-secondary" tabindex="305" />
				</p>
				
				
				<h3 class="border-top"><em>Visual Editor:</em> Leaf Template</h3>
				<p>Choose a template using the select box below and click <em>Export Leaf Template</em> to export the template you select.</p>
				<select id="export-template-selector" style="margin-bottom: 10px;">
					<?php
					$templates = headway_get_option('leaf-templates');

					if(is_array($templates)){

						foreach($templates as $template => $options){					
							$template = str_replace($options['name'].'-', '', $template);

							echo '<option value="template-'.$template.'">'.$options['name'].'</option>';
						}

					}
					?>
				</select>
				
				<p>
					<input type="submit" value="Export Leaf Template" name="export-leaf-template-button" id="export-leaf-template-button" class="button-secondary" tabindex="305" />
				</p>
				
				
				<h3 class="border-top">Full Headway Export/Backup</h3>
				<p>Export the configuration; SEO settings; Header, Footer, Navigation, and Site Dimensions panels; and current styles (and saved styles) applied in the visual editor, and leaf templates.  <strong>This will not include the leafs, layouts for any pages, and widgets.</strong>  However, if you save your pages into leaf templates prior to exporting, you can load the leaf templates to the new pages after importing.</p>
				
				<p>
					<input type="submit" value="Export All Settings" name="export-all-settings-button" id="export-all-settings-button" class="export-button button-secondary" tabindex="305" />
				</p>		

		</div>
		<!-- End Export Panel -->
				
		<!-- Start Safe Mode Panel -->
		<div id="headway-safe-mode-tab" class="tab">
			<h2>Safe Mode</h2>

			<table class="form-table">
				<tr class="no-border">
					<th scope="row">Visual Editor Safe Mode</th>
					
					<td>
						<span class="description">If you accidentally messed up the layout by inserting code into the layout and are unable to delete the leaf, you can enter the visual editor using safe mode.</span>
						
						<span class="description" style="margin: 5px 0 10px;"><strong>WARNING:</strong> Your layout may appear completely different in safe mode.  Try making the desired changes, save, then re-enter the visual editor to enter the visual editor in normal mode.</span>
						
						<input type="submit" onclick="window.open('<?php echo get_bloginfo('url') ?>/?visual-editor=true&amp;safe-mode=true'); return false;" value="Enter Visual Editor &mdash; Safe Mode" name="enter-safe-mode" id="enter-safe-mode" class="button-secondary" tabindex="305" />
						
					</td>
				</tr>
			</table>
		</div>
		<!-- End Safe Mode Panel -->
		
		<!-- Start Reset Panel -->
		<div id="headway-reset-tab" class="tab">
			<h2>Headway Reset</h2>

			<table class="form-table">				
				<tr valign="top" class="no-border">
					<th scope="row">Reset Headway</th>
					<td> 
					<input type="submit" value="Completely Reset Headway" name="reset-headway" id="reset-headway" class="button-secondary" tabindex="303" />
					<span class="description">Resetting Headway will remove all Headway customization, settings and data.  Or in other words, go back to the "Factory Default".</span></td>
				</tr>
			</table>
		</div>
		<!-- End Reset Panel -->
		
		<?php if(is_main_site()){ ?>
		<!-- Start System Info Panel -->
		<div id="headway-system-info-tab" class="tab">
			<h2>System Info</h2>

			<h3>Headway Version</h3>
			<p><?php echo headway_current_version(); ?></p>
				
			<h3 class="border-top">WordPress Version</h3>
			<p><?php global $wp_version; echo $wp_version; ?></p>
							
			<h3 class="border-top">PHP Version</h3>
			<p><?php echo PHP_VERSION; ?></p>
			
			<?php if(function_exists('mysql_get_server_info')){ ?>
			<h3 class="border-top">MySQL Version</h3>
			<p><?php echo mysql_get_server_info(); ?></p>
			<?php } ?>
			
			<h3 class="border-top">Web Server Info</h3>
			<p><?php echo $_SERVER['SERVER_SOFTWARE']; ?></p>
			
			<h3 class="border-top">Browser Info</h3>
			<p><?php echo $_SERVER['HTTP_USER_AGENT']; ?></p>

			<h3 class="border-top">PHP cURL Support</h3>
			<p><?php echo (function_exists('curl_init')) ? 'Yes' : 'No'; ?></p>
			
			<h3 class="border-top">PHP GD (Image Library) Support</h3>
			<p><?php echo (function_exists('gd_info')) ? 'Yes' : 'No'; ?></p>
			
			<h3 class="border-top" style="margin-bottom: 10px;"><strong>System Info: Plain Text Format</strong></h3>
			
<textarea rows="5">
Headway Version: <?php echo headway_current_version()."\n"; ?>
WordPress Version: <?php global $wp_version; echo $wp_version."\n"; ?>
PHP Version: <?php echo PHP_VERSION."\n"; ?>
MySQL Version: <?php echo mysql_get_server_info()."\n"; ?>
Web Server Info: <?php echo $_SERVER['SERVER_SOFTWARE']."\n"; ?>
Browser Info: <?php echo $_SERVER['HTTP_USER_AGENT']."\n"; ?>
PHP cURL Support: <?php echo (function_exists('curl_init')) ? 'Yes'."\n" : 'No'."\n"; ?>
PHP GD Support: <?php echo (function_exists('gd_info')) ? 'Yes' : 'No'; ?>
</textarea>

<span class="description">Copy and paste this information into support/forums if requested.</span>

		</div>
		<!-- End System Info Panel -->
		<?php } ?>
		
			
	</div>
	<!-- End Tabs -->

	</div>
</form>

<script type="text/javascript">
	headway_blog_url = "<?php echo get_bloginfo('url') ?>";
</script>