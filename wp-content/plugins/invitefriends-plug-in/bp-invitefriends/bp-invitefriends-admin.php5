<?php


/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Gdata
 * @subpackage Demos
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

/**
 * Simple class to verify that the server that this is run on has a correct
 * installation of the Zend Framework Gdata component.
 */
class InstallationChecker {

    const CSS_WARNING = '.warning { color: #fff; background-color: #AF0007;}';
    const CSS_SUCCESS = '.success { color: #000; background-color: #69FF4F;}';
    const CSS_ERROR = '.error { color: #fff; background-color: #FF9FA3;}';
    const PHP_EXTENSION_ERRORS = 'PHP Extension Errors';
    const PHP_MANUAL_LINK_FRAGMENT = 'http://us.php.net/manual/en/book.';
    const PHP_REQUIREMENT_CHECKER_ID = 'PHP Requirement checker v0.1';
    const SSL_CAPABILITIES_ERRORS = 'SSL Capabilities Errors';
    const YOUTUBE_API_CONNECTIVITY_ERRORS = 'YouTube API Connectivity Errors';
    const ZEND_GDATA_INSTALL_ERRORS = 'Zend Framework Installation Errors';
    const ZEND_SUBVERSION_URI = 'http://framework.zend.com/download/subversion';

    private static $REQUIRED_EXTENSIONS = array(
        'ctype', 'dom', 'libxml', 'spl', 'standard', 'openssl');

    private $_allErrors = array(
        self::PHP_EXTENSION_ERRORS => array(
            'tested' => false, 'errors' => null),
        self::ZEND_GDATA_INSTALL_ERRORS => array(
            'tested' => false, 'errors' => null),
        self::SSL_CAPABILITIES_ERRORS => array(
            'tested' => false, 'errors' => null),
        self::YOUTUBE_API_CONNECTIVITY_ERRORS => array(
            'tested' => false, 'errors' => null)
            );

    private $_sapiModeCLI = null;

    /**
     * Create a new InstallationChecker object and run verifications.
     * @return void
     */
    public function __construct()
    {
        $this->determineIfInCLIMode();
        $this->runAllVerifications();
        $this->outputResults();
    }

    /**
     * Set the sapiModeCLI variable to true if we are running CLI mode.
     *
     * @return void
     */
    private function determineIfInCLIMode()
    {
        if (php_sapi_name() == 'cli') {
            $this->_sapiModeCLI = true;
        }
    }

    /**
     * Getter for sapiModeCLI variable.
     *
     * @return boolean True if we are running in CLI mode.
     */
    public function runningInCLIMode()
    {
        if ($this->_sapiModeCLI) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Run verifications, stopping at each step if there is a failure.
     *
     * @return void
     */
    public function runAllVerifications()
    {
        if (!$this->validatePHPExtensions()) {
            return;
        }
        if (!$this->validateZendFrameworkInstallation()) {
            return;
        }
        if (!$this->testSSLCapabilities()) {
            return;
        }
        if (!$this->validateYouTubeAPIConnectivity()) {
            return;
        }
    }

    /**
     * Validate that the required PHP Extensions are installed and available.
     *
     * @return boolean False if there were errors.
     */
    private function validatePHPExtensions()
    {
        $phpExtensionErrors = array();
        foreach (self::$REQUIRED_EXTENSIONS as $requiredExtension) {
            if (!extension_loaded($requiredExtension)) {
                $requiredExtensionError = $requiredExtension .
                    ' extension missing';
                $documentationLink = null;
                if ($requiredExtension != 'standard') {
                    $documentationLink = self::PHP_MANUAL_LINK_FRAGMENT .
                        $requiredExtension . '.php';
                        $documentationLink =
                            $this->checkAndAddHTMLLink($documentationLink);
                } else {
                    $documentationLink = self::PHP_MANUAL_LINK_FRAGMENT .
                        'spl.php';
                    $documentationLink =
                        $this->checkAndAddHTMLLink($documentationLink);
                }

                if ($documentationLink) {
                    $phpExtensionErrors[] = $requiredExtensionError .
                        ' - refer to ' . $documentationLink;
                }
            }
        }
        $this->_allErrors[self::PHP_EXTENSION_ERRORS]['tested'] = true;
        if (count($phpExtensionErrors) > 0) {
            $this->_allErrors[self::PHP_EXTENSION_ERRORS]['errors'] =
                $phpExtensionErrors;
            return false;
        }
        return true;
    }

    /**
     * Validate that the Gdata component of Zend Framework is installed
     * properly. Also checks that the required YouTube API helper methods are
     * found.
     *
     * @return boolean False if there were errors.
     */
    private function validateZendFrameworkInstallation()
    {
        $zendFrameworkInstallationErrors = array();
        $zendLoaderPresent = false;
        try {
            $zendLoaderPresent = @fopen('Zend/Loader.php', 'r', true);
        } catch (Exception $e) {
            $zendFrameworkInstallationErrors[] = 'Exception thrown trying to ' .
                'access Zend/Loader.php using \'use_include_path\' = true ' .
                'Make sure you include the Zend Framework in your ' .
                'include_path which currently contains: "' .
                ini_get('include_path') . '"';
        }

        if ($zendLoaderPresent) {
            @fclose($zendLoaderPresent);
            require_once('Zend/Loader.php');
            require_once('Zend/Version.php');
            Zend_Loader::loadClass('Zend_Gdata_YouTube');
            Zend_Loader::loadClass('Zend_Gdata_YouTube_VideoEntry');
            $yt = new Zend_Gdata_YouTube();
            $videoEntry = $yt->newVideoEntry();
            if (!method_exists($videoEntry, 'setVideoTitle')) {
                $zendFrameworkMessage = 'Your version of the ' .
                    'Zend Framework ' . Zend_Version::VERSION . ' is too old' .
                    ' to run the YouTube demo application and does not' .
                    ' contain the new helper methods. Please check out a' .
                    ' newer version from Zend\'s repository: ' .
                    checkAndAddHTMLLink(self::ZEND_SUBVERSION_URI);
                $zendFrameworkInstallationErrors[] = $zendFrameworkMessage;
            }
        } else {
            if (count($zendFrameworkInstallationErrors) < 1) {
                $zendFrameworkInstallationErrors[] = 'Exception thrown trying' .
                    ' to access Zend/Loader.php using \'use_include_path\' =' .
                    ' true. Make sure you include Zend Framework in your' .
                    ' include_path which currently contains: ' .
                    ini_get('include_path');
            }
        }

        $this->_allErrors[self::ZEND_GDATA_INSTALL_ERRORS]['tested'] = true;

        if (count($zendFrameworkInstallationErrors) > 0) {
            $this->_allErrors[self::ZEND_GDATA_INSTALL_ERRORS]['errors'] =
                $zendFrameworkInstallationErrors;
            return false;
        }
        return true;
    }

    /**
     * Create HTML link from an input string if not in CLI mode.
     *
     * @param string The error message to be converted to a link.
     * @return string Either the original error message or an HTML version.
     */
    private function checkAndAddHTMLLink($inputString) {
        if (!$this->runningInCLIMode()) {
            return $this->makeHTMLLink($inputString);
        } else {
            return $inputString;
        }
    }

    /**
     * Create an HTML link from a string.
     *
     * @param string The string to be made into link text and anchor target.
     * @return string HTML link.
     */
    private function makeHTMLLink($inputString)
    {
        return '<a href="'. $inputString . '" target="_blank">' .
            $inputString . '</a>';
    }

    /**
     * Validate that SSL Capabilities are available.
     *
     * @return boolean False if there were errors.
     */
    private function testSSLCapabilities()
    {
        $sslCapabilitiesErrors = array();
        require_once 'Zend/Loader.php';
        Zend_Loader::loadClass('Zend_Http_Client');

        $httpClient = new Zend_Http_Client(
            'https://www.google.com/accounts/AuthSubRequest');
        $response = $httpClient->request();
        $this->_allErrors[self::SSL_CAPABILITIES_ERRORS]['tested'] = true;

        if ($response->isError()) {
            $sslCapabilitiesErrors[] = 'Response from trying to access' .
                ' \'https://www.google.com/accounts/AuthSubRequest\' ' .
                $response->getStatus() . ' - ' . $response->getMessage();
        }

        if (count($sslCapabilitiesErrors) > 0) {
            $this->_allErrors[self::SSL_CAPABILITIES_ERRORS]['errors'] =
                $sslCapabilitiesErrors;
            return false;
        }
        return true;
    }

    /**
     * Validate that we can connect to the YouTube API.
     *
     * @return boolean False if there were errors.
     */
    private function validateYouTubeAPIConnectivity()
    {
        $connectivityErrors = array();
        require_once 'Zend/Loader.php';
        Zend_Loader::loadClass('Zend_Gdata_YouTube');
        $yt = new Zend_Gdata_YouTube();
        $topRatedFeed = $yt->getTopRatedVideoFeed();
        if ($topRatedFeed instanceof Zend_Gdata_YouTube_VideoFeed) {
            if ($topRatedFeed->getTotalResults()->getText() < 1) {
                $connectivityErrors[] = 'There was less than 1 video entry' .
                    ' in the \'Top Rated Video Feed\'';
            }
        } else {
            $connectivityErrors[] = 'The call to \'getTopRatedVideoFeed()\' ' .
                'did not result in a Zend_Gdata_YouTube_VideoFeed object';
        }

        $this->_allErrors[self::YOUTUBE_API_CONNECTIVITY_ERRORS]['tested'] =
            true;
        if (count($connectivityErrors) > 0) {
            $this->_allErrors[self::YOUTUBE_API_CONNECTIVITY_ERRORS]['tested'] =
                $connectivityErrors;
            return false;
        }
        return true;
    }

    /**
     * Dispatch a call to outputResultsInHTML or outputResultsInText pending
     * the current SAPI mode.
     *
     * @return void
     */
    public function outputResults()
    {
        if ($this->_sapiModeCLI) {
          print $this->getResultsInText();
        } else {
          print $this->getResultsInHTML();
        }
    }


    /**
     * Return a string representing the results of the verifications.
     *
     * @return string A string representing the results.
     */
    private function getResultsInText()
    {
        $output = "== Ran PHP Installation Checker using CLI ==\n";

        $error_count = 0;
        foreach($this->_allErrors as $key => $value) {
            $output .= $key . ' -- ';
            if (($value['tested'] == true) && (count($value['errors']) == 0)) {
                $output .= "No errors found\n";
            } elseif ($value['tested'] == true) {
                $output .= "Tested\n";
                $error_count = 0;
                foreach ($value['errors'] as $error) {
                    $output .= "Error number: " . $error_count . "\n--" .
                        $error . "\n";
                }
            } else {
                $output .= "Not tested\n";
            }
            $error_count++;
        }
        return $output;
    }

    /**
     * Return an HTML table representing the results of the verifications.
     *
     * @return string An HTML string representing the results.
     */
    private function getResultsInHTML()
    {
        $html = "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01//EN\" " .
            "\"http://www.w3.org/TR/html4/strict.dtd\">\n".
            "<html><head>\n<title>PHP Installation Checker</title>\n" .
            "<style type=\"text/css\">\n" .
            self::CSS_WARNING . "\n" .
            self::CSS_SUCCESS . "\n" .
            self::CSS_ERROR . "\n" .
            "</style></head>\n" .
            "<body>\n";
			$html="<table class=\"verification_table\">" .
            "Ran PHP Installation Checker on " .
            gmdate('c') . "\n";
        $error_count = 0;
        foreach($this->_allErrors as $key => $value) {
            $html .= "<tr><td class=\"verification_type\">" . $key . "</td>";
            if (($value['tested'] == true) && (count($value['errors']) == 0)) {
                $html .= "<td class=\"success\">Tested</td></tr>\n" .
                    "<tr><td colspan=\"2\">No errors found</td></tr>\n";
            } elseif ($value['tested'] == true) {
                $html .= "<td class=\"warning\">Tested</td></tr>\n";
                $error_count = 0;
                foreach ($value['errors'] as $error) {
                    $html .= "<tr><td class=\"error\">" . $error_count . "</td>" .
                        "<td class=\"error\">" . $error . "</td></tr>\n";
                }
            } else {
                $html .= "<td class=\"warning\">Not tested</td></tr>\n";
            }
            $error_count++;
        }
        $html .= "</table>";
        return $html;
    }
}




	function add_invitefriendsPage(){  //agginge pannello di amministrazione
		if (function_exists('add_options_page')) {
			add_options_page(__("Invite Friends Managment"), __("Invite Friends"), 8, basename(__FILE__), 'adminInvite_subpanel');
		}
	}
	
	
	
	function checkOption(){
	if (!get_option("wp_InviteFriends")){
	  echo "OPZIONI NON SETTATE";
	   $new= array (
				"mail"=>str_replace(" ", "", get_settings('admin_email')),
				"yahooAPPID"=>str_replace(" ", "", ""),
				"yahooSECRET"=>str_replace(" ", "", ""),
				"YahooMod"=>str_replace(" ", "", "API"),
				"GMailMod"=>str_replace(" ", "", "API"),
				"ZendUrl"=>str_replace(" ", "", WP_PLUGIN_DIR."/bp-invitefriends/lib/Gmail/library"),	
				"HotmailMod"=>str_replace(" ", "", "cURL"),
				"aolMod"=>str_replace(" ", "", "API"),	
				"uploadFile"=>str_replace(" ", "",WP_PLUGIN_DIR),
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

	function adminInvite_subpanel(){
	    checkOption();
	     
		if($_POST["editInvite"]){
			$wp_rp_saved = get_option("wp_InviteFriends");
			$new= array (
				"mail"=>str_replace(" ", "", $_POST['mailFROM']),
				"yahooAPPID"=>str_replace(" ", "", $_POST['yahooAPPID']),
				"yahooSECRET"=>str_replace(" ", "", $_POST['yahooSECRET']),
				"YahooMod"=>str_replace(" ", "", $_POST['YahooMod']),
				"GMailMod"=>str_replace(" ", "", $_POST['GMailMod']),
				"ZendUrl"=>str_replace(" ", "", $_POST['ZendUrl']),	
				"HotmailMod"=>str_replace(" ", "", $_POST['HotmailMod']),
				"aolMod"=>str_replace(" ", "", $_POST['aolMod']),	
				"uploadFile"=>str_replace(" ", "", $_POST['uploadFile']),
				"facebookApiKey"=>str_replace(" ", "", $_POST['facebookApiKey']),
				"facebookSECRET"=>str_replace(" ", "", $_POST['facebookSECRET']),
				"facebookAppName"=>str_replace(" ", "", $_POST['facebookAppName']),
				"facebookAppURL"=>str_replace(" ", "", $_POST['facebookAppURL']),
				"facebookRedURL"=>str_replace(" ", "", $_POST['facebookRedURL']),
				"msnAPPID"=>str_replace(" ", "", $_POST['msnAPPID']),
				"msnSECRET"=>str_replace(" ", "", $_POST['msnSECRET']),
				"Hotmail"=>str_replace(" ", "", $_POST['Hotmail']),
				"Facebook"=>str_replace(" ", "", $_POST['Facebook']),
				"Yahoo"=>str_replace(" ", "", $_POST['Yahoo']),
				"Gmail"=>str_replace(" ", "", $_POST['Gmail']),
				"AOL"=>str_replace(" ", "", $_POST['AOL']),
				"Twitter"=>str_replace(" ", "", $_POST['Twitter']),
				"CSV"=>str_replace(" ", "", $_POST['CSV'])

		     );
			update_option("wp_InviteFriends",$new);
			
			
			$string= "<windowslivelogin>
  <appid>".$_POST['msnAPPID']."</appid>
  <secret>".$_POST['msnSECRET']."</secret>
  <securityalgorithm>wsignin1.0</securityalgorithm>
  <returnurl>".WP_PLUGIN_URL."/bp-invitefriends/lib/msnAPI/delauth-handler.php</returnurl>
  <policyurl>".WP_PLUGIN_URL."/bp-invitefriends/lib/msnAPI/policy.html</policyurl>
</windowslivelogin>";
		echo  "<br />";
		echo htmlentities($string);
        echo  "<br />"; 
		 echo  "<br />"; 
		echo WP_PLUGIN_URL."/bp-invitefriends/lib/msnAPI/DelAuth-Sample1.xml";
		 echo  "<br />"; 
		  echo  "<br />"; 
		$handle = fopen(WP_PLUGIN_DIR."/bp-invitefriends/lib/msnAPI/DelAuth-Sample1.xml", "w");
		fwrite ($handle, $string);
		fclose($handle);
					
			echo __("<h3>DATA SAVED</h3>");
			
		}
		$salvati=get_option("wp_InviteFriends");
	?>
		<div class="wrap">
		
		<h2><?php echo __("Invite friends"); ?></h2>
		
		<?php
		
		// visualizza variabili
		 //echo "<br />BP_PLUGIN_URL:".BP_PLUGIN_URL; 
		 //echo "<br />BP_PLUGIN_DIR:".BP_PLUGIN_DIR;
		 //echo "<br />site_url():".site_url();
		 //echo "<br />WP_CONTENT_DIR:".WP_CONTENT_DIR;
		 //echo "<br />WP_PLUGIN_URL:".WP_PLUGIN_URL;
		 //echo "<br />WP_PLUGIN_DIR:".WP_PLUGIN_DIR;
		 //echo "<p>";
		 //var_dump($salvati);
			//echo "</p>";
		?>
		
		
		<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?page=<?php echo basename(__FILE__); ?>">
		<label for="mailFROM"><?php echo __("Mail from:") ?></label><input class="txtMedio" type="text" name="mailFROM" id="mailFROM" value="<?php echo $salvati['mail']; ?>" /><br /><br /><br />
		
			<div class="msg_list">
			<ul id="ulmenu">
			  <li  <?php if ( $salvati['Hotmail']=="on") $col="#99FF99";else $col="#FFCCCC"; echo "style=\"background-color:".$col.";\""?> ><input type="checkbox" name="Hotmail" <?php if ( $salvati['Hotmail']=="on") echo "CHECKED";?>></li><li <?php echo "style=\"background-color:".$col.";\""?>  class="msg_head"><u>Hotmail</u>
			  </li>
			  <li class="msg_body">
				
				<label for="HotmailMod1">API<input id="HotmailMod1"onclick="methodSelected(this);" type="radio" name="HotmailMod" value="API"   <?php if ($salvati['HotmailMod']=="API") echo "checked"?> > </label>  <br />  
				<label for="HotmailMod2">Scraper<input id="HotmailMod2"onclick="methodSelected(this);" type="radio" name="HotmailMod" value="cURL"<?php if ($salvati['HotmailMod']!="API") echo "checked"?>> </label>  <br />  <br />    
				  <a href="http://go.microsoft.com/fwlink/?LinkID=130560" target="_blank">Get your application ID</a>(Sign in by using your Windows Live ID)Live Services: Existing APIs.<br /><br />  
				<label for="msnAPPID">APPID</label> <input  class="txtMedio" type="text" id="msnAPPID" name="msnAPPID" value="<?php echo $salvati['msnAPPID']; ?>"   <?php if ($salvati['HotmailMod']!="API") echo "disabled"?> /><br />
				 <label for="msnSECRET">SECRET</label>   <input class="txtMedio" type="text" id="msnSECRET" name="msnSECRET" value="<?php echo $salvati['msnSECRET']; ?>" <?php if ($salvati['HotmailMod']!="API") echo "disabled"?> /><br />
			  </li>
			</ul>  		
			<ul id="ulmenu">
			  <li  <?php if ( $salvati['Yahoo']=="on") $col="#99FF99";else $col="#FFCCCC"; echo "style=\"background-color:".$col.";\""?> ><input type="checkbox" name="Yahoo" <?php if ( $salvati['Yahoo']=="on") echo "CHECKED";?>></li><li <?php echo "style=\"background-color:".$col.";\""?>  class="msg_head"><u>Yahoo</u>
			  </li>
			  <li class="msg_body">
				    <label for="YahooMod1">API<input onclick="methodSelected(this);" id="YahooMod1" type="radio" name="YahooMod" value="API" <?php if ($salvati['YahooMod']!="cURL") echo "checked"?> ></label><br />
					<label for="YahooMod2">Scraper<input onclick="methodSelected(this);" id="YahooMod2"type="radio" name="YahooMod" value="cURL"<?php if ($salvati['YahooMod']=="cURL") echo "checked"?>></label><br /><br />
					<a href="https://developer.yahoo.com/wsregapp/" target="_blank">Get an App ID</a> <br />
					<p> Select as Authentication method: Browser Based Authentication. Use this option for browser applications</p>
					<p> Select as Required access scopes: Yahoo! Address Book with Read Only access</p>
					<label for="yahooAPPID">APPID</label><input  class="txtMedio" type="text" id="yahooAPPID" name="yahooAPPID" value="<?php echo $salvati['yahooAPPID']; ?>"   <?php if ($salvati['YahooMod']=="cURL") echo "disabled"?> /><br />
					
					<label for="yahooAPPID">SECRET</label> <input class="txtMedio" type="text" id="yahooSECRET" name="yahooSECRET" value="<?php echo $salvati['yahooSECRET']; ?>" <?php if ($salvati['YahooMod']=="cURL") echo "disabled"?> /><br />
			  </li>
			</ul>  
			
			<ul id="ulmenu">
			  <li  <?php if ( $salvati['Gmail']=="on") $col="#99FF99";else $col="#FFCCCC"; echo "style=\"background-color:".$col.";\""?> ><input type="checkbox" name="Gmail" <?php if ( $salvati['Gmail']=="on") echo "CHECKED";?>></li><li <?php echo "style=\"background-color:".$col.";\""?>  class="msg_head"><u>Gmail</u>
			  </li>
			  <li class="msg_body">
					<label for="GMailMod1">API<input onclick="methodSelected(this);" type="radio" id="GMailMod1" name="GMailMod" value="API" <?php if ($salvati['GMailMod']!="cURL") echo "checked"?> ></label><br />
					<label for="GMailMod2">Scraper<input onclick="methodSelected(this);" type="radio" name="GMailMod2" id="GMailMod" value="cURL"<?php if ($salvati['GMailMod']=="cURL") echo "checked"?> disabled ></label><br /><br />
				     <b>Zend URL</b><br />

					 <?php $oldPath = set_include_path(get_include_path(). PATH_SEPARATOR. $salvati['ZendUrl']);
						echo __("The path to your web accessable folder is: /home/USERID/public_html/. 
						Where USERID is your account name (usually the first seven characters of your domain name).
						<br /><b>Suggest: </b>"); 
						echo WP_PLUGIN_DIR."/bp-invitefriends/lib/Gmail/library";?>
						<input class="txtLungo" type="text" id="ZendUrl" name="ZendUrl" value="<?php echo $salvati['ZendUrl']; ?>" <?php if ($salvati['GMailMod']=="cURL") echo "disabled"?>/><br /><br />
						<b>Zend Installation Checker</b><br />
						<?php $installationChecker = new InstallationChecker();   ?>
				</li>
			</ul>
			
			
			<ul id="ulmenu">
				<li  <?php if ( $salvati['AOL']=="on") $col="#99FF99";else $col="#FFCCCC"; echo "style=\"background-color:".$col.";\""?> ><input type="checkbox" name="AOL"<?php if ( $salvati['AOL']=="on") echo "CHECKED";?>></li><li <?php echo "style=\"background-color:".$col.";\""?>  class="msg_head"><u>AOL</u></li><li class="msg_body">
					<label for="aolMod1">API<input onclick="methodSelected(this);" type="radio" name="aolMod" id="aolMod1" value="API" <?php if ($salvati['aolMod']=="API") echo "checked"?> disabled ></label><br />
					<label for="aolMod2">Scraper<input  onclick="methodSelected(this);"  type="radio"  id="aolMod" name="aolMod2" value="cURL"<?php if ($salvati['aolMod']!="API") echo "checked"?>></label><br /><br />
				</li>
			</ul>
				
				
			<ul id="ulmenu">
				<li  <?php if ( $salvati['CSV']=="on") $col="#99FF99";else $col="#FFCCCC"; echo "style=\"background-color:".$col.";\""?> ><input type="checkbox" name="CSV"<?php if ( $salvati['CSV']=="on") echo "CHECKED";?>></li><li <?php echo "style=\"background-color:".$col.";\""?>  class="msg_head"><u>CSV Upload</u></li><li class="msg_body">
				<?php echo WP_CONTENT_DIR ; ?>/<input class="txtLungo" type="text" name="uploadFile" value="<?php echo $salvati['uploadFile']; ?>"/><br />
				</li>
			</ul>
				
		
			<ul id="ulmenu">
				<li  <?php if ( $salvati['Twitter']=="on") $col="#99FF99";else $col="#FFCCCC"; echo "style=\"background-color:".$col.";\""?> ><input type="checkbox" name="Twitter"<?php if ( $salvati['Twitter']=="on") echo "CHECKED";?>></li><li <?php echo "style=\"background-color:".$col.";\""?>  class="msg_head"><u>Twitter</u></li><li class="msg_body">
				No option<br />
				</li>	
			</ul>
			
			<ul id="ulmenu">
				<li  <?php if ( $salvati['Facebook']=="on") $col="#99FF99";else $col="#FFCCCC"; echo "style=\"background-color:".$col.";\""?> ><input type="checkbox" name="Facebook" <?php if ( $salvati['Facebook']=="on") echo "CHECKED";?>> </li><li <?php echo "style=\"background-color:".$col.";\""?>  class="msg_head"><u>Facebook</u></li><li class="msg_body">
				<a href="http://www.facebook.com/developers/"><b>Create your Facebook Application</b></a><br />
				<?php _e("Ensure that the Canvas Page URL and that your Side Nav URL are both in lowercase!");?><br />
				<b>Use:</b> <br />FBML/iframe: FBML <br/>
							Developer Mode:    Disabled <br />
							Application Type:  Website<br />
							Private Install:    No<br />
				<label for="facebookApiKey">API Key</label><input  class="txtMedio" type="text" id="facebookApiKey" name="facebookApiKey" value="<?php echo $salvati['facebookApiKey']; ?>"  /><br />	
				<label for="facebookSECRET">SECRET</label> <input class="txtMedio" type="text" id="facebookSECRET" name="facebookSECRET" value="<?php echo $salvati['facebookSECRET']; ?>" /><br />	
				<label for="facebookAppName">Application Name</label> <input class="txtMedio" type="text" id="facebookAppName" name="facebookAppName" value="<?php echo $salvati['facebookAppName']; ?>" /><br />	
	
				 <br /><b>Application URL</b><br />
				<label for="facebookAppURL">http://apps.facebook.com/</label> <input class="txtMedio" type="text" id="facebookAppURL" name="facebookAppURL" value="<?php echo $salvati['facebookAppURL']; ?>" /><br />	
				<label for="facebookAppURL">Redirect URL</label> <input class="txtMedio" type="text" id="facebookRedURL" name="facebookRedURL" value="<?php echo $salvati['facebookRedURL']; ?>" /><br />	
				
				</li>	
			</ul>
			

			<input type="hidden" name="action" value="update" />
			<input type="hidden" name="editInvite" value="ok" />
			<p class="submit">
			<br />
				<input class="invio" type="submit" name="Submit" value="<?php _e('Save Changes') ?>" />
			</p>	
			
			</div>
			
			
			
		</form>
		
		  
	   </div>
	 
<?php 

}


function admin_invitefriends_header() {

	/* js includes ============================== */
	echo "\n".'<!-- Start Of Script Generated By Invite Friends Admin -->'."\n";
		// slider doesn't work with jQuery before 1.2 
		//wp_deregister_script('jquery');
		//wp_enqueue_script('jquery', PT_URLPATH.'js/jquery.js', false, '1.2.2');
		//wp_enqueue_script('postthumb', PT_URLPATH.'js/post-thumb.js', array('jquery'));
		//wp_print_scripts(array('jquery', 'postthumb'));
	
		//<script type="text/javascript" src="js/jquery.js"></script>
		//<script type="text/javascript" src="js/post-thumb.js"></script>
	

 
	echo "<style type=\"text/css\">\n" .InstallationChecker::CSS_WARNING . "\n" .InstallationChecker::CSS_SUCCESS . "\n" .
	InstallationChecker::CSS_ERROR . "\n" .
	".txtMedio{width:400px;}"."\n".
	".txtLungo{width:600px;}"."\n".
	"p {
		padding: 0 0 1em;
	}

.msg_list {
margin: 0px;
padding: 0px;
width: 620px;
}
.msg_head {
padding: 5px 10px;
cursor: pointer;
position: relative;
margin:1px;
width: 560px;
}

.invio{
float: left;
cursor: pointer;
position: relative;
display:inline;
}
.submit{
float: left;
cursor: pointer;
position: relative;
display:inline;
}

.msg_body {
padding: 5px 10px 15px;
background-color:#F4F4F8;
width: 600px;
}


label {
text-align: right;
margin: 5px;
width: 142px;
padding-right: 20px;
float: left;
}

br {
clear: left;
}
#ulmenu li {
display:inline;
float:left;
cursor:pointer;
margin: 1px 0px 1px 0px;
padding:5px 10px;
position:relative;
}

".
	
	"</style>";
?>
	<script type="text/javascript">
	        var echo = "<?php echo $echo; ?>";
						
			function methodSelected(rad){
				//alert( "Valore " +rad.value+ "Nome: "+rad.name) ;
				switch(rad.name) {
  
					case "YahooMod":
						if (rad.value=="API"){
							document.getElementById('yahooAPPID').disabled=false;
							document.getElementById('yahooSECRET').disabled=false;
						}else{	
							document.getElementById('yahooAPPID').disabled=true;
							document.getElementById('yahooSECRET').disabled=true;
						}
					 break; 
					 case "GMailMod":
						if (rad.value=="API"){
							document.getElementById('ZendUrl').disabled=false;
							
						}else{	
							document.getElementById('ZendUrl').disabled=true;
							
						}
					 break; 
					case "HotmailMod":
					     if (rad.value=="API"){
							document.getElementById('msnAPPID').disabled=false;
							document.getElementById('msnSECRET').disabled=false;
						}else{	
							document.getElementById('msnAPPID').disabled=true;
							document.getElementById('msnSECRET').disabled=true;
						}
					   break;
					default:
				
				}
				
				
			}
			
			
			
		
	</script>
	
	<?php 
	
	/*
 <script src="<?php echo WP_PLUGIN_URL. "/bp-invitefriends/lib/jquery.js";?>" type="text/javascript"></script>	
<script type="text/javascript">
$(document).ready(function()
{
  //hide the all of the element with class msg_body
  $(".msg_body").hide();
  //toggle the componenet with class msg_body
  
  $(".msg_head").click(function()
  {
    $(this).next(".msg_body").slideToggle(600);
  });
});


</script>

*/
?>
	<?php
	echo '<!-- End Of Script Generated By InviteFreinds plugin-->'."\n";

}

?>