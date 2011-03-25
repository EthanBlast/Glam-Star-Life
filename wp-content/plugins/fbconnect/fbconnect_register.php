<?php
global $regmodal;
if (!isset($regmodal) || !$regmodal){
	if (!get_option('fb_connect_use_thick')){
		get_header();
		echo '<div class="narrowcolumn">';
	}
}
	//$userprofile = WPfbConnect_Logic::get_user();
		
	if (is_user_logged_in()){ //Si el usuario está conectado solo puede modificar su perfil
		$userprofile = wp_get_current_user();
		//print_r($userprofile);
		$name = $userprofile->display_name;
		$nickname = $userprofile->nickname;
		$user_url = $userprofile->user_url;
		$about = $userprofile->description;
		$email = $userprofile->user_email;
		$birthday = $userprofile->birthday;
		
		$location_city = $userprofile->location_city;
		$location_state  = $userprofile->location_state;
		$location_country  = $userprofile->location_country;
	 	$location_zip  = $userprofile->location_zip;
		$day = 0;
		$month = 0 ;
		$year = 0;

		if (isset($birthday) && $birthday!=""){
				$birthday = strtotime($birthday);
				$day = date("j",$birthday);
				$month = date("n",$birthday);
				$year = date("Y",$birthday);
		}
		$sex = $userprofile->sex;
		if (!isset($registered))
			$registered = true;
		$fb_user = $userprofile->fbconnect_userid;
		$company_name = $userprofile->company_name;
		$phone = $userprofile->phone;
	}
	if (!is_user_logged_in() || (isset($force_fbloaddata) && $force_fbloaddata)){
		$fb_user = fb_get_loggedin_user();
		$usersinfo = fb_user_getInfo($fb_user);
		if ($usersinfo!="ERROR"){
			//$name = $usersinfo["first_name"]." ".$usersinfo["last_name"];
			if ($name==""){
				$name = $usersinfo["name"];
			}
			if ($nickname==""){
				$nickname = $usersinfo["username"];
			}
			if ($user_url==""){
				if (isset($usersinfo["website"]) && $usersinfo["website"]!=""){
					$user_url_array = explode(" ",$usersinfo["website"]." ",1);
					$user_url = trim($user_url_array[0]);
				}else{
					$user_url = $usersinfo["profile_url"];
				}
			}
			if ($about==""){
				$about = $usersinfo["about_me"];
			}
			//$email = $usersinfo["proxied_email"];
			if (isset($usersinfo["current_location"])){		
				if ($location_city==""){
					$location_city = $usersinfo["current_location"]["city"];
				}
				if($location_state ==""){
					$location_state = $usersinfo["current_location"]["state"];
				}
				if ($location_country==""){
					$location_country = $usersinfo["current_location"]["country"];
				}
				if ($location_zip==""){
					$location_zip = $usersinfo["current_location"]["zip"];
				}
			}
			if ($company_name =="" && isset($usersinfo["work_history"]) && isset($usersinfo["work_history"][0])){
				$company_name = $usersinfo["work_history"][0]["company_name"];
			}
	
			if ((!isset($birthday) || $birthday=="") && isset($usersinfo["birthday"]) && $usersinfo["birthday"]!=""){		
					$birthday = 0;
					$day = 0;
					$month = 0 ;
					$year = 0;
					$birthday = strtotime($usersinfo["birthday"]);
					$day = date("j",$birthday);
					$month = date("n",$birthday);
					$year = date("Y",$birthday);
			}
			if ($sex ==""){
				$sex = $usersinfo["sex"];
			}
		}else{
			echo "No ha sido posible conectar con Facebook";
			exit;
		}
	}
	$fb_form_fields = get_option('fb_form_fields');
	$fb_form_fields_bool = array();
	global $fb_reg_formfields;
	if ($fb_reg_formfields){
		foreach($fb_reg_formfields as $field){
		 	$pos = strrpos($fb_form_fields, ";".$field.";");
			$fb_form_fields_bool[$field] = true;
			if (is_bool($pos) && !$pos) { 
				$fb_form_fields_bool[$field] = false;
			}
		}
	}
?>	
	
<div class="fbconnect_regform">

<?php if (!isset($show_userphoto) || $show_userphoto) : ?>		
	<table>
		<tr>
			<td>
				<div class="fbconnect_userpicmain">
					<fb:profile-pic uid="<?php echo $fb_user; ?>" size="square" facebook-logo="false" linked="false"></fb:profile-pic>
				</div>
			</td>
			<td>
				<div class="titlepassport"><?php _e('Community Passport', 'fbconnect') ?></div>
				<div><a id="mailperms" href="#" ><img src="<?php echo FBCONNECT_PLUGIN_URL; ?>/images/sobre.gif"/> <?php _e('Allow Facebook email access?', 'fbconnect') ?></a></div>
				<div><a id="statusperms" href="#" ><img src="<?php echo FBCONNECT_PLUGIN_URL; ?>/images/comment.gif"/> <?php _e('Allow Stream Publish?', 'fbconnect') ?></a></div>
			</td>
		</tr>		
</table>

				<script type='text/javascript'>
					FB.XFBML.parse();
				</script>
<?php endif; ?>	

	<div class="fbconnect_profiletexts">
<form action="<?php echo get_option('siteurl')."/index.php?fbconnect_action=register_update"; ?>" method="post">
	<table>
<?php if ($fb_form_fields_bool["name"] || $registered) : ?>		
		<tr>
			<td class="fb_formfila">
				<label class="labelform" for="name"><?php _e('Name:', 'fbconnect') ?> </label>
			</td>	
			<td class="fb_formfila"><input class="textform" type="text" name="name" id="name" value="<?php echo $name;?>"/>	</td>	
		</tr>
<?php endif; ?>
<?php if ($fb_form_fields_bool["nickname"] || $registered) : ?>		
		<tr>
			<td class="fb_formfila">
				<label class="labelform" for="nickname"><?php _e('Nickname:', 'fbconnect') ?> </label>
			</td>	
			<td class="fb_formfila"><input class="textform" type="text" name="nickname" id="nickname" value="<?php echo $nickname;?>"/>	</td>	
		</tr>
<?php endif; ?>
<?php if ($fb_form_fields_bool["email"] || $registered) : ?>		

		<tr>
			<td class="fb_formfila">
				<label class="labelform" for="email"><?php _e('Email:', 'fbconnect') ?> </label>
			</td >	
			<td class="fb_formfila"><input class="textform" type="text" name="email" id="email" value="<?php echo $email;?>"/>	</td>	
		</tr>
<?php endif; ?>
<?php if ($fb_form_fields_bool["sex"] || $registered) : ?>		
		<tr>
			<td class="fb_formfila">
				<label class="labelform" for="sex"><?php _e('Sex:', 'fbconnect') ?> </label>
			</td>	
			<td class="fb_formfila">
		<div>
				<select class="fb_dateselect" id="sex" name="sex">
				<option selected="" value="Sex"><?php _e('Sex', 'fbconnect') ?></option>
				<option value="male" <?php if($sex=="male") echo "selected" ?> ><?php _e('Male', 'fbconnect') ?></option>
				<option value="female" <?php if($sex== "female") echo "selected" ?> ><?php _e('Female', 'fbconnect') ?></option>
		</div>
		</td>
		</tr>		
<?php endif; ?>
<?php if ($fb_form_fields_bool["birthdate"] || $registered) : ?>		
		<tr>
			<td class="fb_formfila">
				<label class="labelform" for="birthdate"><?php _e('Birdthdate:', 'fbconnect') ?> </label>
			</td>	
			<td class="fb_formfila">
		<div>
				<select class="fb_dateselect" id="birthdate_day" name="birthdate_day">
				<option selected="" value="00"><?php _e('Day', 'fbconnect') ?></option>
				<?php
				 for($i=1;$i<=31;$i++){
					echo "<option value=\"$i\" ";
					if ($day==$i) echo "selected";
					echo ">$i</option>";
				}
				?>
				</select>
				<select style="width:80px;" class="fb_dateselect" id="birthdate_month" name="birthdate_month">
				<option value="00"><?php _e('Month', 'fbconnect') ?></option>
				<?php
				 global $wp_locale;
				 $months = $wp_locale->month;
				 for($i=1;$i<=12;$i++){
					echo "<option value=\"$i\" ";
					if ($month==$i) echo "selected";
					echo ">".$months[substr("0".$i,-2,2)]."</option>";
				}
				?>
				</select>
				<select class="fb_dateselect" id="birthdate_year" name="birthdate_year">
				<option selected="" value="0000"><?php _e('Year', 'fbconnect') ?></option>
				<?php
				 for($i=1910;$i<=2000;$i++){
					echo "<option value=\"$i\" ";
					if ($year==$i) echo "selected";
					echo ">".$i."</option>";
				}
				?>					
				</select>								
				
				</div>
		</td>	
		</tr>		
<?php endif; ?>
<?php if ($fb_form_fields_bool["user_url"] || $registered) : ?>		

		<tr>
			<td class="fb_formfila">
				<label class="labelform" for="user_url"><?php _e('Web:', 'fbconnect') ?> </label>
			</td>	
			<td class="fb_formfila"> <input class="textform" type="text" name="user_url" id="user_url" value="<?php echo $user_url;?>"/>	</td>	
		</tr>
<?php endif; ?>
<?php if ($fb_form_fields_bool["location"] && $registered) : ?>		
		
		<tr>
			<td class="fb_formfila">
				<label class="labelform" for="location_city"><?php _e('City:', 'fbconnect') ?> </label>
			</td>	
			<td class="fb_formfila"><input class="textform" type="text" name="location_city" id="location_city" value="<?php echo $location_city;?>"/>	</td>	
		</tr>
		<tr>
			<td class="fb_formfila">
				<label class="labelform" for="location_state"><?php _e('State:', 'fbconnect') ?> </label>
			</td>	
			<td class="fb_formfila"><input class="textform" type="text" name="location_state" id="location_state" value="<?php echo $location_state;?>"/>	</td>	
		</tr>
		<tr>
			<td class="fb_formfila">
				<label class="labelform" for="location_country"><?php _e('Country:', 'fbconnect') ?> </label>
			</td>	
			<td class="fb_formfila"><input class="textform" type="text" name="location_country" id="location_country" value="<?php echo $location_country;?>"/>	</td>	
		</tr>
		<tr>
			<td class="fb_formfila">
				<label class="labelform" for="location_zip"><?php _e('ZIP:', 'fbconnect') ?> </label>
			</td>	
			<td class="fb_formfila"><input class="textform" type="text" name="location_zip" id="location_zip" value="<?php echo $location_zip;?>"/>	</td>	
		</tr>
<?php endif; ?>
<?php if ($fb_form_fields_bool["company_name"] && $registered) : ?>		

		<tr>
			<td class="fb_formfila">
				<label class="labelform" for="company_name"><?php _e('Company:', 'fbconnect') ?> </label>
			</td>	
			<td class="fb_formfila"> <input class="textform" type="text" name="company_name" id="company_name" value="<?php echo $company_name;?>"/>	</td>	
		</tr>
<?php endif; ?>
<?php if ($fb_form_fields_bool["phone"] && $registered) : ?>		

		<tr>
			<td class="fb_formfila">
				<label class="labelform" for="phone"><?php _e('Phone:', 'fbconnect') ?> </label>
			</td>	
			<td class="fb_formfila"> <input class="textform" type="text" name="phone" id="phone" value="<?php echo $phone;?>"/>	</td>	
		</tr>
<?php endif; ?>
<?php if ($fb_form_fields_bool["about"] || $registered) : ?>		
		<tr>
			<td class="fb_formfila">
				<label class="labelform" for="about"><?php _e('About me:', 'fbconnect') ?></label>
			</td>	
			<td class="fb_formfila"> <textarea class="textareaform" type="text" name="about" id="about" cols=30 rows=2><?php echo $about;?></textarea>	</td>	
		</tr>
<?php endif; ?>
<?php 
	if (isset($custom_fields) && $custom_fields!=""){
		echo $custom_fields;
	}
?>
<?php if ($fb_form_fields_bool["terms"] && !$registered) : ?>		
		<tr>
			<td style="text-align:right;" class="fb_formfila">
				<input type="checkbox" name="terms" id="terms"/>
			</td>	
			<td class="fb_formfila">
				<a href="<?php echo "?p=".get_option('fb_terms_page'); ?>" target="_blank"><?php _e('I agree to the Terms of Use and Privacy Policy', 'fbconnect') ?></a>
			</td>	
		</tr>
<?php endif; ?>
		<?php if (isset($custom_vars) && $custom_vars!="") : ?>		
		<input type="hidden" name="custom_vars" id="custom_vars" value="<?php echo $custom_vars; ?>"/>
		<?php endif; ?>
		<tr>
			<td>
			&nbsp;	
			</td>	
			<td>
			<?php if (!isset($fb_show_cancel) || $fb_show_cancel) : ?>	
				<input type="button" name="cancel" id="cancel" onclick="tb_remove();" value="<?php _e('Cancel', 'fbconnect') ?>"> 
			<?php endif; ?>	
			<input type="submit" class="fbconnect_sendbutton" name="send" id="send" value="<?php _e('Send', 'fbconnect') ?>"></td>	
		</tr>

</table>		
</form>		
	</div>
</div>
<script type='text/javascript'>
	jQuery(document).ready(function($) {	
		$('#mailperms').click(function(){
		facebook_prompt_permission('email', function(accepted){
							if (accepted) {
								readUserData();
							}
						});
		});	
	});
	jQuery(document).ready(function($) {	
		$('#statusperms').click(function(){
		facebook_prompt_permission('publish_stream', function(accepted){
							if (accepted) {
								
							}
						});
		});	
	});
</script>
<?php
if (!get_option('fb_connect_use_thick')){
	if (!isset($_REQUEST["modal"]) || $_REQUEST["modal"]=="false"){
		echo '</div>';
		get_sidebar();
		get_footer();
	}
}
?>