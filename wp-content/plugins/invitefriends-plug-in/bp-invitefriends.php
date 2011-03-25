<?php 
/* 
 Plugin Name: Invite Friends
 Plugin URI: 
 Description: Invite friends on buddypress social network from MSN, gmail, facebokk and twitter. It can easily be added to a page using the code [invitefriends] or from  BuddyPress Bar : MyAccount/Friends/Invite Friends
 Version: 0.8
 Author: Giovanni Caputo
 Author URI: http://www.giovannicaputo.netsons.org
Site Wide Only: true
 */ 

 /* Copyright 2008-2009 GIOVANNI CAPUTO (email: giovannicaputo86@gmail.com)

   This program is free software; you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation; either version 2 of the License, or
   (at your option) any later version.

   This program is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with this program; if not, write to the Free Software
   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
 */

/* HOW TO USE:
     It can easily be added to a page using the code [invitefriends] or from   BuddyPress Bar : MyAccount/Friends/Invite Friends
*/

//require_once( 'bp-core.php' );

define ( 'BP_INVITE_FRIENDS', '1.0' );
define ('INVITE_FRIENDS_FOLDER', '/bp-invitefriends/');

include_once( 'bp-invitefriends/bp-invitefriends-admin.php5' );


if ( file_exists( WP_PLUGIN_DIR . INVITE_FRIENDS_FOLDER .'languages/' . get_locale() . '.mo' ) )
	load_textdomain( 'bp-invitefriends', WP_PLUGIN_DIR . INVITE_FRIENDS_FOLDER. 'languages/' . get_locale() . '.mo' );

	//load_plugin_textdomain('invfri', WP_PLUGIN_URL.'/bp-invitefriends/languages'); 
	
function bp_invitefriends_nav() {
	global $bp;
	$profile_link = $bp->loggedin_user->domain . $bp->friends->slug . '/';
	bp_core_add_subnav_item(  $bp->friends->slug , __('InviteFriends'), __('Invite Friends'), $profile_link, 'wp_invitefriends' );
}

add_action( 'wp', 'bp_invitefriends_nav', 2 );


	
function wp_invitefriends() {
	add_action( 'bp_template_title', 'wp_invitefriends_title' );
	add_action( 'bp_template_content_header', 'wp_invitefriends_header' );
	add_action( 'bp_template_content', 'wp_invitefriends_content' );
	bp_catch_uri('plugin-template');
}

function wp_invitefriends_title() {
	_e('Invite contact');
}

function wp_invitefriends_header() {
	_e('Invite your contact');
}

function wp_invitefriends_content() {
	invitefriends_handler(null, null);
}


function invitefriends_add_js() {
	
	wp_enqueue_script( 'filtrocontatti', WP_PLUGIN_URL . '/bp-invitefriends/js/filtrocontatti.js', false, '' );
}
add_action( 'template_redirect', 'invitefriends_add_js' );

function invitefriends_add_structure_css() {
	wp_enqueue_style( 'bp-invitefriends-structure', WP_PLUGIN_URL . '/bp-invitefriends/css/page.css' );	
}
add_action( 'bp_styles', 'invitefriends_add_structure_css' );

/* Autoconfigure Admin Option*/
function inviteCheckInstall(){
if (!get_option("wp_InviteFriends")){
	  
	   $new= array (
				"mail"=>str_replace(" ", "", get_settings('admin_email')),
				"yahooAPPID"=>str_replace(" ", "", ""),
				"yahooSECRET"=>str_replace(" ", "", ""),
				"YahooMod"=>str_replace(" ", "", "API"),
				"GMailMod"=>str_replace(" ", "", "API"),
				"ZendUrl"=>str_replace(" ", "", WP_PLUGIN_URL."/bp-invitefriends/lib/Gmail/library"),	
				"HotmailMod"=>str_replace(" ", "", "cURL"),
				"aolMod"=>str_replace(" ", "", "API"),	
				"uploadFile"=>str_replace(" ", "",WP_PLUGIN_URL),
				"facebookApiKey"=>str_replace(" ", "", $_POST['facebookApiKey']),
				"facebookSECRET"=>str_replace(" ", "", $_POST['facebookSECRET']),
				"facebookAppName"=>str_replace(" ", "", $_POST['facebookAppName']),
				"facebookAppURL"=>str_replace(" ", "", $_POST['facebookAppURL']),
				"facebookRedURL"=>str_replace(" ", "", $_POST['facebookRedURL']),
				"msnAPPID"=>str_replace(" ", "", $_POST['msnAPPID']),
				"msnSECRET"=>str_replace(" ", "", $_POST['msnSECRET']),
				"Hotmail"=>"on",
				"Facebook"=>"on",
				"Yahoo"=>"on",
				"Gmail"=>"on",
				"AOL"=>"on",
				"CSV"=>"on",
				"Twitter"=>"on"
		     );
			add_option("wp_InviteFriends",$new);
	
	}else {}

}
function invitefriends_handler($atts, $content=null) {   

  inviteCheckInstall();
  ?>
  <div id="invtFrinds">
  <?php 
  gestioneInvio();
  if (is_user_logged_in()){
     if (!isset ($_GET['appid']) && !isset ($_GET['msn'])){ // non YAHOO
       if (isset($_POST['op'])){
		switch ($_POST['op']){
			case "webMail":
				webMailHandler($_POST['webmailType']);
				break;
			case "selected":
				/*SELEZIONATE MAIL*/
				$mail=$_POST['mail'];
				//print_r($mail);
				addFriends($mail);  
				break;
		     case "manual":
			   $mail=str_replace(" ","", $_POST['email_addresses']);
			   $mail=explode(",", $mail );
			   addFriends($mail); 
			 break;
			 case "twitterSel":
				global $current_user;
				$iduser=$current_user->ID;	
				$user_info = get_userdata($iduser);
				$nome=$user_info-> first_name;	$cognome=$user_info-> last_name;
				$nick=$user_info-> nickname;
				include_once( 'bp-invitefriends/lib/twitter.php' );
				$contatti=$_POST['mail'];
				$twitter = new Twitter($_POST['us'], $_POST['pwd']);
				foreach ($contatti as $c){
				  $msg=__("User ".$nome." ".$cognome." with nick ". $nick. " suggest to subscrive on ".get_bloginfo('url'));
				 $twitter->newMessage("xml", $c, $msg);
				 _e("Message sent to ".$c."<br>");
			    }
			 break;
		}
	   echo "</br>";
	   echo "<p></br><a href='".get_permalink()."'>";
	   _e("Invites other friends");
	   echo "</a></p>";   
	}else init_form(); //form iniziale
   }else{  //
		if (isset ($_GET['appid'])){
			include ("bp-invitefriends/mYahooApi.class.php");
			$Yahoo=new connectToYahooApi();
			$Yahoo->CreateLink();
			$Yahoo->seeYahooContact();
		}
		if (isset ($_GET['msn'])){ //msnAPI
			include ("bp-invitefriends/lib/msnAPI/msnAPIgestore.php");
			include ("bp-invitefriends/lib/msnAPI/windowslivelogin.php");
			$cont=0;
			if (!isset($_GET['gestore'])){
			
			   if (isset($_GET['temp'])){
			      $nomefile= urldecode($_GET['temp']);
				  
			      $handle = fopen($nomefile, "r");
				  $contents = fread($handle, filesize($nomefile));
				  $xml = simplexml_load_string($contents);
				  
				  foreach ($xml->children() as $child){
					if ( $child->getName() =="Contacts"){
					   foreach ($child->children() as $contatto){
					      
						   
						   $listMail[$cont++] = Array($contatto->WindowsLiveID);				   
			
				
					   }
					
					}
					
				  }
				  selectfriends($listMail);
					
					unlink($nomefile);
					fclose($handle);
			   }else{
			       
				$gestore= new msnAPIgestore();
				$wll= $gestore->init();
				echo $gestore->getLink($wll);  //rest click here
				}
			}else{
				// Specify true to log messages to Web server logs.
				$DEBUG = false;
				// Comma-delimited list of offers to be used.
				$OFFERS = "Contacts.View";
				// Application key file: store in an area that cannot be accessed from the Web.
				$KEYFILE = WP_PLUGIN_URL.'/bp-invitefriends/lib/msnAPI/DelAuth-Sample1.xml';
				// Name of cookie to use to cache the consent token. 
				$COOKIE = 'delauthtoken';
				$COOKIETTL = time() + (10 * 365 * 24 * 60 * 60);

				// URL of Delegated Authentication sample index page.
				//$INDEX = 'index3.php';
				$INDEX = site_url().'/members/admin/friends/InviteFriends/index.php?msn=true';
				
				
				// Default handler for Delegated Authentication.
				$HANDLER = 'delauth-handler.php';
				// Initialize the WindowsLiveLogin module.
				$wll = WindowsLiveLogin::initFromXml($KEYFILE);
				$wll->setDebug($DEBUG);
				// Extract the 'action' parameter, if any, from the request.
				$action = @$_REQUEST['action'];
				if ($action == 'delauth') {
					$consent = $wll->processConsent($_REQUEST);
				// If a consent token is found, store it in the cookie that is   configured in the settings.php file and then redirect to  the main page.
					if ($consent) {
						setcookie($COOKIE, $consent->getToken(), $COOKIETTL);
					}else {
						setcookie($COOKIE);
					}
				}
				header("Location: $INDEX");
			}
		}
   }
  }else   _e("<p>You need to be logged TO INVITE FRIENDS</p>");  // NON LOGGATO
  ?></div><?php
 }
 
 
 function webMailHandler($scelta){
 $salvata=get_option("wp_InviteFriends");
      $usr=$_POST['Email'];
	   $pwd=$_POST['Passwd'];
	   switch ($scelta) {
	   case "hotmail":
	      if ($salvata['HotmailMod']=='cURL'){
			include_once( 'bp-invitefriends/lib/msnscraper/msnp9.class.php' );
			$msn = new msn;
			if ($msn->connect($usr, $pwd))	{
				$msn->rx_data();
				selectfriends($msn->getMailVector());
			}else{
				// wrong username and password?
				echo '<p>Error Connecting to the MSN Network</p>';
			}

		} else{
			   _e("Messenger API don't work");
			}
		    //print_r($returned_emails);			
           break;
	   case "gmail":
            if ($salvata['GMailMod']=='cURL'){

			} else{
				$oldPath = set_include_path(get_include_path() . PATH_SEPARATOR . $salvata['ZendUrl']);
				require_once 'bp-invitefriends/lib/Gmail/library/Zend/Loader.php';
				Zend_Loader::loadClass('Zend_Gdata_ClientLogin');
				Zend_Loader::loadClass('Zend_Gdata_Gapps');
				Zend_Loader::loadClass('Zend_Gdata_Query');

				$client = Zend_Gdata_ClientLogin::getHttpClient($usr,$pwd, "cp");
				$gdata = new Zend_Gdata($client);
				$query = new Zend_Gdata_Query('http://www.google.com/m8/feeds/contacts/default/full');
				$query->setMaxResults(10000);
				$feed = $gdata->getFeed($query);
				$xml = new SimpleXMLElement($feed->getXML());
				$entries = $xml->children('http://www.w3.org/2005/Atom');
				$cont=0;
				foreach ($entries->entry as $entry ) {
					$defaults = $entry->children('http://schemas.google.com/g/2005');
						$a = $defaults->email->attributes();
					   $listMail[$cont++] = Array($a->address);				   
				}
			
				selectfriends($listMail);
			}
			
           break;
	   case "yahoo":
            if ($salvata['YahooMod']=='cURL'){
			   include_once( 'bp-invitefriends/lib/importYahoo.class.php' );
			   $yahoo = new yahooGrabber($usr, $pwd);
			   $list = $yahoo->grabYahoo();
			   $cont=0;
			   foreach ($list as $c ) $listMail[$cont++]=Array($c);
			  selectfriends($listMail);
			} 
			break;
	   case "aol":
            if ($salvata['aolMod']=='cURL'){
			   include_once( 'bp-invitefriends/lib/importAol.class.php' );
			   $aol = new grabAol($usr, $pwd);
			   $list = $aol->getContactList();
			   $cont=0;
			   foreach ($list as $c ) $listMail[$cont++]=Array($c);
			  selectfriends($listMail);
			} 
           break;
		case "CSV":
			define("UPLOAD_DIR", "./".$salvata['uploadFile']);
			if(isset($_FILES['CSVfile'])){
				$file = $_FILES['CSVfile'];
				if($file['error'] == UPLOAD_ERR_OK and is_uploaded_file($file['tmp_name'])){
					move_uploaded_file($file['tmp_name'], UPLOAD_DIR.$file['name']);
					$handle = fopen(UPLOAD_DIR.$file['name'], "r");
					$listaM=array(); $listaMail=array();
					while ($data = fgetcsv($handle,200, ";")){
					   foreach ($data as $dato) {
					      if (isemail($dato) && !(in_array($dato, $listaM))){
						     array_push($listaM, $dato);
						  }
					   }
					}
					$cont=0;		foreach ($listaM as $c ) $listMail[$cont++]=Array($c);
					unlink (UPLOAD_DIR.$file['name']);
			        selectfriends($listMail);			
				}
			}else _e("No file");
			break;
		case "twitter":
			include_once( 'bp-invitefriends/lib/twitter.php' );
			$twitter = new Twitter($usr, $pwd);
			$xmlscr = $twitter->getFriends("xml", $usr);
			$xml = new SimpleXMLElement($xmlscr);
			$listaMail=array();$cont=0;
			foreach ($xml->children() as $us) {
				$listMail[$cont++]=Array($us->screen_name);
			}
			$otherInfo=array('usr'  => $usr, 'pwd'=> $pwd);
			selectfriends($listMail, $otherInfo);
		
		break;
		case "facebook":
			require_once 'bp-invitefriends/lib/facebook-platform/php/facebook.php';
			$urlpag=$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
			if (strpos($urlpag, '?')!== FALSE ) 	$urlpag = substr($urlpag, 0, strpos($urlpag, '?')); 
			$urlpag="http://www.".$urlpag;
            
			$appapikey = $salvata['facebookApiKey'];
			$appsecret = $salvata['facebookSECRET'];
			$app_name = $salvata['facebookAppName']; 
			$app_url = $salvata['facebookAppURL']; // Assumes application is at http://apps.facebook.com/app-url/ 

			$invite_href = $salvata['facebookRedURL'];
			
		
			
			$facebook = new Facebook($appapikey, $appsecret);
			$facebook->require_frame(); 
			$user_id = $facebook->require_login();
				
	   }
 }
 

                

function init_form(){
?>
    <?php $salvata=get_option("wp_InviteFriends");	
	?>
	  <div>
		<?php 	
		if (is_site_admin()){ 
			echo "<p><a href='". site_url()."/wp-admin/options-general.php?page=bp-invitefriends-admin.php5"."'>";
			_e("Go to setting page of inviteFriends plugin");
			echo "</a></p>";
		}
		?>
	  </div>
    <div><p><?php _e("We won't store your password or contact anyone without your permission.");?></p></div>
       <form action="<?php get_permalink(); ?>" method="post" accept-charset="UTF-8" name="inviteFriendsForm" onSubmit="return checkUsPwd()"  enctype="multipart/form-data">
            <ul id="tipologie">
			  <?php if ( $salvata['Gmail']=="on"){ ?>
				 <li id="gmail">
                    <input onclick="inputSelection(this,'<?php echo $salvata['GMailMod'];?>')" name="webmailType" value="gmail" id="gmail-webmailType-emailParam-getContactsForm" class="gmail" type="radio">
                    <label for="gmail-webmailType-emailParam-getContactsForm">
                        <img src="<?php echo WP_PLUGIN_URL.'/bp-invitefriends/'?>images/logo_gmail_50x23.gif" alt="Google Mail" width="50" height="23">
                    </label>
                </li>
				<?php } 
				if ( $salvata['Hotmail']=="on"){ 
				?>
                <li id="Hotmail">
                    <input onclick="inputSelection(this,'<?php echo $salvata['HotmailMod'];?>')" name="webmailType" value="hotmail"  id="hotmail-webmailType-emailParam-getContactsForm" class="hotmail" type="radio">
                    <label for="hotmail-webmailType-emailParam-getContactsForm">
                        <img src="<?php echo WP_PLUGIN_URL.'/bp-invitefriends/'?>images/logo_hotmail_109x14.gif" alt="Windows Live Mail" width="109" height="14">
                    </label>
                </li>
               <?php } 
				if ( $salvata['Yahoo']=="on"){ 
				?>
                <li id="yahoo">
                    <input onclick="inputSelection(this,'<?php echo $salvata['YahooMod'];?>')"  name="webmailType" value="yahoo" id="yahoo-webmailType-emailParam-getContactsForm" class="yahoo" type="radio">
                    <label for="yahoo-webmailType-emailParam-getContactsForm">
                        <img src="<?php echo WP_PLUGIN_URL.'/bp-invitefriends/'?>images/logo_yahoo_80x23.gif" alt="Yahoo!" width="80" height="23">
                    </label>
                </li>
				<?php } 
				if ( $salvata['AOL']=="on"){ 
				?>
                <li id="aol">
                    <input  onclick="inputSelection(this,'<?php echo $salvata['aolMod'];?>')" name="webmailType" value="aol" id="aol-webmailType-emailParam-getContactsForm" class="aol" type="radio">
                    <label for="aol-webmailType-emailParam-getContactsForm">
                        <img src="<?php echo WP_PLUGIN_URL.'/bp-invitefriends/'?>images/logo_aol_56x23.gif" alt="AOL" width="56" height="23">
                    </label>
                </li>
				<?php } 
				if ( $salvata['Twitter']=="on"){ 
				?>
				 <li id="twitter">
                    <input  onclick="inputSelection(this,'cURL')" name="webmailType" value="twitter" id="twitter-webmailType-emailParam-getContactsForm" class="twitter" type="radio">
                    <label for="twitter-webmailType-emailParam-getContactsForm">
                        <img src="<?php echo WP_PLUGIN_URL.'/bp-invitefriends/'?>images/twitter-80x23.jpg" alt="twitter" width="80" height="23">
                    </label>
                </li>
				<?php } 
				if ( $salvata['Facebook']=="on"){ 
				?>
				 <li id="facebook">
                    <input  onclick="inputSelection(this,'facebook')" name="webmailType" value="facebook" id="facebook-webmailType-emailParam-getContactsForm" class="facebook" type="radio">
                    <label for="facebook-webmailType-emailParam-getContactsForm">
                        <img src="<?php echo WP_PLUGIN_URL.'/bp-invitefriends/'?>images/home_facebook56x21.jpg" alt="facebook" width="80" height="23">
                    </label>
                </li>
				<?php } 
				if ( $salvata['CSV']=="on"){ 
				?>
				<li id="uploadCSV">
                    <input  onclick="inputSelection(this,'CSV')" name="webmailType" value="CSV" id="CSV" class="CSV" type="radio">
                    <label for="CSV">
                        <?php _e("Upload CSV File"); ?>
                    </label>
                </li>  
				<?php } ?>
            </ul>
			<div id="usr_pwd" style="display:none">
            <ul id="dati">
                <li>
                    <label id="userName">
                        Mail:
                    </label>
                    <input name="Email" value="" class="inpt" type="text">
                </li>
                <li>
                    <label>
                        Password:
                    </label>
                    <input name="Passwd" value="" class="inpt" type="password">
                </li>
            </ul>
			   <input type="hidden" name="op" value="webMail">
            <p>
                <input id="btn" class="inputsubmit" name="btnUpContact" value="<?php _e("Upload Contacts");?>" type="submit">
            </p>
			</div>
				<div id="yahooAPI"  style="display:none">
				<div id="">
				<?php 
					include ("bp-invitefriends/mYahooApi.class.php");
					$Yahoo=new connectToYahooApi();
					$Yahoo->CreateLink();
				?>
				</div>
				<div id=""> </div>
				</div>
				
				<div id="msnAPI" style="display:none">
				<?php 
					$urlpag=$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
					if (strpos($urlpag, '?')!== FALSE ) 	$urlpag = substr($urlpag, 0, strpos($urlpag, '?')); 
				    $urlpag="http://".$urlpag."?msn=true";
				     echo "<a href=\"". $urlpag. "\">";
					 _e("Login to Windows Live Messenger");
					 echo "</a>";
					 
				?>
				</div>
				
				<div id="facebookDiv" style="display:none">
					
					<a href="<?php echo site_url()."/index.php?facebook=true"; ?>"><?php _e("Go to Facebook");?></a>
				</div>
				
				<div id="uploadFile"  style="display:none">
					<input type="hidden" name="action" value="upload" />
					<label><?php _e("Load your CSV file:"); ?></label><br>
					<input id="sourceCSV" type="file" name="CSVfile" />
					<input id="btn" class="inputsubmit" name="btnUpContact" value="<?php _e("Upload Contacts");?>" type="submit">
					<br />
				</div>
       </form>
	   <div class="manual">
	   <form action="<?php get_permalink(); ?>" method="post" >
	       <label><?php _e("Invite:"); ?></label>
		   <div><small style="color: gray;"><?php _e("Enter your e-mail separated by commas");?></small>
		   </div>
		   <input type="hidden" name="op" value="manual">
		   <div class="module_options clearfix"><textarea name="email_addresses" class="textarea" rows="4" id="email_addresses"> </textarea>
		      <input class="inputsubmit" id="btn" name="manual" value="<?php _e("Add");?>" type="submit">
			</div>
		</form>
		</div>
<?php
}



function gestioneInvio(){
	//include_once( 'bp-friends/bp-friends-classes.php' );
	$salvata=get_option("wp_InviteFriends");
	$mailSender=$salvati['mail'];
	global $current_user;
	$iduser=$current_user->ID;	
}


function selectfriends($listMail, $otherInfo = null){
	global $current_user;
	$iduser=$current_user->ID;	
	$urlpag=$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
	if (strpos($urlpag, '?')!== FALSE ) 	$urlpag = substr($urlpag, 0, strpos($urlpag, '?')); 
?>
		<div class="column right">
		   <div class="filter_form">
		    <label><?php _e("Filter");?>:</label>
		     <input type="text" id="myfilter" onkeyup="filter()">
		  </div>
		  <form action="<?php echo  "http://".$urlpag."/";?>" method="post" accept-charset="UTF-8" onSubmit="return someSelected()">      
		    <?php wp_nonce_field( 'friends_add_friend' ); ?>
	        <div id="userlist" class="userlist" style="height:250px;">
		     <span id="friends_list">
			 <?php
			 if (!empty($listMail)){
			   foreach ($listMail as $cnt) {
			    $type=typeuser($iduser, $cnt[0]);
			    ?>
                 <span u_mail="<?php echo $cnt[0];?>">
				      <input id="<?php echo $cnt[0];?>"class="inputcheckbox"  name="mail[]" value="<?php echo $cnt[0];?>" type="checkbox" 
					  <?php if ($type=='friends') echo "DISABLED";?>
					  >
					  <label for="<?php echo $cnt[0];?>" id="<?php echo $type?>"><?php echo $cnt[0]; ?> </label><br>
			    </span>
				<?php
               }
			 }else{_e("Contact list is empty");}
			 ?>
		    </span>
		  </div>
		  <input id="btncheck" type="button" name="CheckAll" value="Check All" onClick="checkAll(true)">
          <input id="btncheck"  type="button" name="UnCheckAll" value="Uncheck All"onClick="checkAll(false)">
		  <?php if (isset($otherInfo)){
		  		   ?><input type="hidden" name="op" value="twitterSel">
				   <input type="hidden" name="us" value="<?php echo $otherInfo['usr'];?>">
				   <input type="hidden" name="pwd" value="<?php echo $otherInfo['pwd'];?>">
				   <input type="hidden" name="twitterSel" value="twitterSel">
				   <?php
				 }else {
				    ?><input type="hidden" name="op" value="selected"><?php
				 }
          ?>
		  <input class="inputsubmit" id="email_button" name="confermaMail" value="<?php _e("Add");?>" type="submit">
		 </form>
		<div class="separator"></div><br>
     </div>
<?php 
}

function addFriends($destinatari){
    global $current_user;global $wpdb;
	$iduser=$current_user->ID;
	foreach ($destinatari as $mai) {
	  if (isemail($mai)){
		$type=typeuser($iduser, $mai);
		//echo "<br>".$mai." is ".$type."<br>";
		switch($type){
			case 'registred':
				_e("Request of Friends: ").$mai."<br>";
				$sql = $wpdb->prepare( "SELECT id ". "FROM $wpdb->users " ."where user_email='".$mai."'");
				$result=$wpdb->get_results($sql);
				//echo "<br> id of ".$mai." is ".$result[0]->id. "<br>";
				friends_add_friend( $iduser, $result[0]->id);
			break;
			case 'notregistred':
				_e("Request registration: ").$mai."<br>";
				send_mail($mai);
			break;
		}	
	  }else echo $mai.__(" is not a mail!");
	}
}


function isemail($email) {
    return preg_match('|^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]{2,})+$|i', $email);
}



function send_mail($mail){
	global $current_user;
	$iduser=$current_user->ID;	
	$user_info = get_userdata($iduser);
	$nome=$user_info-> first_name;
	$cognome=$user_info-> last_name;
	$nick=$user_info-> nickname;
	$salvati=get_option("wp_InviteFriends");
	$sendermail=$salvati['mail'];
	
	$header = "From: <".$sendermail.">\n";
	$header .= "CC:".__("Sender")."<"." ".">\n";
	$header .= "X-Mailer:".__("Invite")."\n";
	// generiamo la stringa che funge da separatore
	$boundary = "==String_Boundary_x" .md5(time()). "x";
	// costruiamo le intestazioni che specificano
	// un messaggio costituito da più parti alternative
	$header .= "MIME-Version: 1.0\nContent-Type: multipart/alternative;\n boundary=\"$boundary\";\n\n";

	// questa parte del messaggio viene visualizzata
	// solo se il programma non sa interpretare
	// i MIME poiché è posta prima della stringa boundary
	$messaggio = __("Your mail client does not support MIME Type\n\n");
	// inizia la prima parte del messaggio in testo puro
	$messaggio .= "--$boundary\nContent-Type: text/plain; charset=\"iso-8859-1\"\nContent-Transfer-Encoding: 7bit\n\n";
	 $messaggio .= __("User ").$nome." ".$cognome." ( ". $nick. __(") suggest you to sign up. Go to :").get_bloginfo('url')."\n\n".__("\n\n");// inizia la seconda parte del messaggio in formato html
		  $messaggio .= "--$boundary\nContent-Type: text/html; charset=\"iso-8859-1\"\nContent-Transfer-Encoding: 7bit\n\n";
        	$messaggio .= "<html><body><p>User ".$nome." ".$cognome." (". $nick. ") suggest you to sign up :</p><p>Go on  <a href=\"".get_bloginfo('url')."\">".get_bloginfo('url')."</a></p>". bp_core_get_avatar($iduser)."</body></html>\n";
			$messaggio .= "--$boundary--\n";
		$subject = __("Invite to ").get_bloginfo('name'). __(" net.");
	if( mail($mail, $subject, $messaggio, $header) ) _e("e-mail sent successfully!")."<br>";
	else _e("error on sent e-mail")."<br>";
}



/*return if $mail is a mail of a friend of user, or if is only registred or if not registred 
*/
function typeuser($iduser, $mail){
	global $wpdb;
	//include_once( 'bp-xprofile/bp-xprofile-classes.php' );
	$friendship = new BP_Friends_Friendship( $iduser, false, true );
	//echo "hai ".$friendship->total_friend_count( $iduser )."amici";
	$amici=$friendship->get_friend_user_ids($iduser, false, false);
	//echo "['profile']['table_name_data'] ".$bp['profile']['table_name_data'] ;
	//print_r($wpdb);	
	if ($amici){
	  foreach($amici as $amico){
	      $user_info = get_userdata($amico);
		  if (($user_info->user_email)==$mail) {return 'friends';};
	  }
	}

	$sql = $wpdb->prepare( "SELECT id, user_email, display_name ". "FROM $wpdb->users " ."where user_email='".$mail."'");
	if ( !$values = $wpdb->get_results($sql) )
		return 'notregistred';
	else return 'registred';
	
}

/*----------------------------------------------------------------------------------------------------    AMMINISTRAZIONE  ---------------------------------------------------------------------------------*/
	add_action('admin_menu', 'add_invitefriendsPage');
	add_action('admin_head', 'admin_invitefriends_header');

/* -----------------------------------------------------------------------------------------------------------AMMINISTRAZIONE-----*/


add_shortcode('invitefriends', 'invitefriends_handler');
/*CSS Styling*/
function inviteFriends_css() {  	
   ?>
	   <link rel="stylesheet" type="text/css" media="screen" href="<?php echo WP_PLUGIN_URL; ?>/bp-invitefriends/css/page.css" />
	<?php   
} 


function enqueue_test() {
  //wp_register_script( 'myjquery', get_bloginfo('wpurl') . '/wp-content/plugins/invitefriends/js/jquery.js', false, '' );
  // wp_enqueue_script('myjquery');
   wp_enqueue_script('filtrocontatti', WP_PLUGIN_URL . '/bp-invitefriends/js/filtrocontatti.js', false, ''); 
}




add_action ('template_redirect', 'checkfacebook');
add_action('wp_head', 'inviteFriends_css');
add_action ('init', 'enqueue_test');



function checkfacebook(){
       if (isset($_GET['facebook'])){
	   
	
	   require_once 'bp-invitefriends/lib/facebook-platform/php/facebook.php';
	    $salvata=get_option("wp_InviteFriends");
		$facebook = new Facebook($appapikey, $appsecret);
		
			$appapikey = $salvata['facebookApiKey'];
			$appsecret = $salvata['facebookSECRET'];
			$app_name = $salvata['facebookAppName']; 
			$app_url = $salvata['facebookAppURL']; // Assumes application is at http://apps.facebook.com/app-url/ 
		
			$invite_href = $salvata['facebookRedURL'];
			
			
			$facebook = new Facebook($appapikey, $appsecret);
			$facebook->require_frame(); 
			$user_id = $facebook->require_login();
		
		$facebook->require_frame(); 
		$user_id = $facebook->require_login();
	
		
?>
	          <fb:request-form
	                    action="<?php echo $invite_href; ?>" 
	                    method="POST"
	                    invite="true"
	                    type="<?php echo $app_name; ?>"
						
	                    content="
							<?php echo __("Sign Up ")."<a href='".site_url() ."'>".site_url() ."</a> "; ?>
	                 <fb:req-choice url='<?php $facebook->get_add_url();?>'
	                       label='<?php echo "Become a Member!"; ?>' />
	              "
	              >
	                    <fb:multi-friend-selector
	                    showborder="false"
	                    actiontext="<?php echo "Select the friends you want to invite"; ?>">
	        </fb:request-form>
	      
	  <?php 
	     

	   }
}

?>