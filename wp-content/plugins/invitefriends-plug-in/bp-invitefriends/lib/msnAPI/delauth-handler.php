<?php

/**
 * This page handles the 'delauth' Delegated Authentication action.
 * When you create a Windows Live application, you must specify the URL 
 * of this handler page.
 */

// Load common settings.  For more information, see settings.php.
//include 'settings.php';


//SETTINGS
// Specify true to log messages to Web server logs.
$DEBUG = false;

// Comma-delimited list of offers to be used.
$OFFERS = "Contacts.View";

// Application key file: store in an area that cannot be
// accessed from the Web.
$KEYFILE = 'DelAuth-Sample1.xml';

// Name of cookie to use to cache the consent token. 
$COOKIE = 'delauthtoken';
$COOKIETTL = time() + (10 * 365 * 24 * 60 * 60);

// URL of Delegated Authentication sample index page.


// Default handler for Delegated Authentication.
$HANDLER = 'delauth-handler.php';

include 'windowslivelogin.php';

// Initialize the WindowsLiveLogin module.
$wll = WindowsLiveLogin::initFromXml($KEYFILE);
$wll->setDebug($DEBUG);

// Extract the 'action' parameter, if any, from the request.
$action = @$_REQUEST['action'];


if ($action == 'delauth') {
  $consent = $wll->processConsent($_REQUEST);

// If a consent token is found, store it in the cookie that is 
// configured in the settings.php file and then redirect to 
// the main page.
  if ($consent) {
    setcookie($COOKIE, $consent->getToken(), $COOKIETTL);
  }
  else {
    setcookie($COOKIE);
  }
}

include ("msnAPIgestore.php");

	
				$gestore= new msnAPIgestore();
				$wll= $gestore->init();
				//echo $gestore->getLink($wll);  //rest click here
				
				//echo htmlentities($gestore->getxml($wll));
				
	$temp_file = tempnam(sys_get_temp_dir(), 'BP-invite');

	$handle = fopen($temp_file, "w");
	fwrite($handle, $gestore->getxml($wll));
	fclose($handle);
	
	$urlrserver=$_SERVER['HTTP_HOST'];
    $urlSub=$_SERVER['REQUEST_URI'];
	$pos=stripos($urlSub, "/wp-content/");
	if ($pos!==0){
		$urlrserver=$urlrserver.substr($urlSub,0, $pos);
	}
	
	$INDEX = "http://".$urlrserver.'/members/admin/friends/InviteFriends/index.php?msn=true&temp='.urlencode($temp_file);




header("Location: $INDEX");
?>
