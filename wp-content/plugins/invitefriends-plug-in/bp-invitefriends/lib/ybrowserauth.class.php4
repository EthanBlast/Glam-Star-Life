<?php
// ybrowserauth.class.php4 -- PHP4 classes for using Yahoo's browser
// authentication service
//
// Author: Jason Levitt
// March 19th, 2007 Version 1.6
// Credits: Based on code by Allen Tom with additions by Dan Theurer, Zack Steinkamp, Paul Hammond, and Ryan Kennedy
// Requirements: PHP4. The Curl extension is required.
//               For YahooMailJSONRPC, the Pear JSON Services library or PHP JSON extension is required:
//               Pear JSON: http://pear.php.net/pepr/pepr-proposal-show.php?id=198
//               PHP ext: http://www.aurore.net/projects/php-json
//
// This file contains five classes:
//
// YBrowserAuth...........authenticates users using Yahoo! Browser-Based Authentication
// YBBauthWebServices.....(child of YBrowserAuth) a utility class that fetches the auth credentials necessary
//                        for authenticated web service calls
// YBBauthREST............(child of YBBauthWebServices) makes authenticated REST web service calls
// YahooMailJSONRPC.......(child of YBBauthWebServices) makes authenticated JSON-RPC calls for the
//                        Mail API
//
// Choose the appropriate class depending on which API you want to use:
//
// Constructor usage:
// $yourappid = 'asdafasdfadfadfa';
// $yoursecret = 'asd1234125dfasdfasdfas';
// $authObj = new YBrowserAuth($yourappid, $yoursecret);  // for authentication only (Single Sign-on)
// $authObj = new YBBauthREST($yourappid, $yoursecret);   // for authenticated GET/POST calls (e.g. Yahoo Photos)
// $authObj = new YahooMailJSONRPC($yourappid, $yoursecret); // for Mail API access via JSON RPC
//
//

// This is the path to the bbauth service
DEFINE ('WSLOGIN_PREFIX', 'https://api.login.yahoo.com/WSLogin/V1/');

// This is the endpoint for the Mail API's JSON-RPC service
DEFINE ('JSONRPC_PREFIX', 'http://mail.yahooapis.com/ws/mail/v1.1/jsonrpc');

class YBrowserAuth {

	var $appid;
	var $secret;
	var $appdata = null;
	var $userhash;
	var $sig_validation_error;

	/**
	 * Constructor function. Instantiates the application id and shared secret
	 * used to authorize access.
	 * @param string $yourappid
	 * @param string $yoursecret
	 */
	function YBrowserAuth($yourappid, $yoursecret) {
		$this->appid = $yourappid;
		$this->secret = $yoursecret;
	}

	/**
	 * Create the Login URL used to fetch authentication credentials. This is the 
	 * first step in the browser authentication process.
	 *
	 * @param string $appd Optional data string, typically a session id,
	 * that Yahoo will transfer to the target application upon successful authentication
     * @param boolean $hash Optional flag. If set, the send_userhash=1 request will be 
	 * appended to the request URL so that the userhash will be returned by Yahoo! after
     * successful authentication.
	 * @return string A full URL that initiates browser-based authentication.
	 */
	function getAuthURL($appd=null, $hash=false) {
		// Add optional appdata parameter, if requested
		$appdata = (empty($appd)) ? null : '&appdata=' . urlencode($appd);
		$hashdata = ($hash) ? '&send_userhash=1' : null;
		return $this->createAuthURL(WSLOGIN_PREFIX . "wslogin?appid=" . $this->appid . $appdata . $hashdata);
	}

	/**
	 * Validates the signature returned by Yahoo's browser authentication
	 * services
	 *
	 * @param string $ts Optional timestamp that typically will normally be retrieved
	 * from the global $_GET array after authentication succeeds.
	 * @param string $sig Optional sig that typically will normally be retrieved
	 * from the global $_GET array after authentication succeeds.
	 * @return true | false Returns true if the sig is validated. Returns false if
	 * any error occurs. If false is returned, $this->sig_validation_error should
	 * contain a string describing the error.
	 */
	function validate_sig($ts=null, $sig=null) {
		// There might be a reason you'd want to pass in the timestamp and
		// signature yourself, but typically not.
		$ts = (empty($ts)) ? $_GET["ts"] : $ts;
		$sig = (empty($sig)) ? $_GET["sig"] : $sig;
		$this->userhash =  isset($_GET["userhash"]) ? $_GET["userhash"] : null;
		$this->appdata =  isset($_GET["appdata"]) ? $_GET["appdata"] : null;

		// Fetch the Request URI from the environment
		$relative_url = getenv('REQUEST_URI');
		if ($relative_url === false ) {
			$this->sig_validation_error = "Failed getting REQUEST_URI from the environment";
			return false;
		}

		// Parse the signature out of the REQUEST_URI
		$match = array();
		$preg_rv = preg_match("@^(.+)&sig=(\\w{32})$@", $relative_url, $match);

		// Only one sig should be found. If it's found, it should match the sig
		// sent by Yahoo!
		if ($preg_rv == 1) {
			if ($match[2] != $sig) {
				$this->sig_validation_error = "Invalid sig may have been passed: " . urlencode($match[2]) . " Yahoo sig: " . $sig;
				return false;
			}
		} else {
			$this->sig_validation_error = "Invalid url may have been passed - relative_url: ".urlencode($relative_url);
			return false;
		}

		// At this point, the url looks valid, and we pulled the sig from the url.
		// The sig was guaranteed to be the last param on the url.
		$relative_url_without_sig = $match[1];

		// Make sure your server time is within 10 minutes (600 seconds) of Yahoo's servers
		$current_time = time();
		$clock_skew  = abs($current_time - $ts);
		if ($clock_skew >= 600) {
			$this->sig_validation_error = "Invalid timestamp - clock_skew is $clock_skew seconds, current time is $current_time, ts is ". urlencode($ts);
			return false;
		}

		// Use the PHP md5 function to caculate the sig using your shared secret, and
		// then compare that sig to the one passed by Yahoo.
		$sig_input = $relative_url_without_sig . $this->secret;
		$calculated_sig = md5($sig_input);
		if ($calculated_sig == $sig) {
			return true;
		} else {
			$this->sig_validation_error = "calculated_sig was " . urlencode($calculated_sig) . ", supplied sig was" . urlencode($sig) . ", sig input was ". urlencode($sig_input);
			return false;
		}
	}

	/**
	 * A utility function used to build authenticated URLs
	 *
	 * @param string $url
	 * @return string The URL with authentication credentials added to it
	 */
	function createAuthURL($url) {
		// Take apart the URL
		$parts = parse_url($url);
		// Get the current time
		$ts = time();
		// Re-build the path and query with the timestamp added
		$relative_uri = "";
		// Make sure we form the URL correctly
		if (isset($parts["path"])) {
			$relative_uri .= $parts["path"];
		}
		if (empty($parts["query"])) {
			$relative_uri .= "?" . "ts=$ts";
		} else {
			$relative_uri .= "?" . $parts["query"] . "&ts=$ts";
		}
		// Generate the sig
		$sig = md5($relative_uri . $this->secret);
		// Build the signed URL
		$signed_url = $parts["scheme"] . "://" .  $parts["host"] . $relative_uri . "&sig=$sig";
		return $signed_url;
	}
}


class YBBauthWebServices extends YBrowserAuth {

	var $timeout;
	var $token;
	var $WSSID;
	var $cookie;
	var $access_credentials_error;
	var $web_services_error;

	/**
	 * This method is used by getAccessCredentials to fetch all the
	 * values necessary to make an authenticated web service call
	 *
	 * @param string $yourtoken Typically, you would not pass this in. It's sent by Yahoo
	 * as part of the response from successful user authentication
	 * @return string Returns a full URL that is used to retrieve all the authentication 
	 * values
	 */
	function getAccessURL() {
		// If the app is making a call by manually setting $this->token, we want to check for that
		$this->token = (isset($this->token)) ? $this->token : $_GET["token"];
		return $this->createAuthURL(WSLOGIN_PREFIX . "wspwtoken_login?token=$this->token&appid=$this->appid");
	}


	/**
	 * Fetches all the authentication values for use in making authenticated
	 * web service calls
	 *
	 * @return true | false Returns true if all the authentication values are 
	 * successfully fetched. Returns false if anything else happens and the error
	 * is in $this->access_credentials_error
	 */
	function getAccessCredentials() {
		// Get the wspwtoken_login URL
		$url = $this->getAccessURL();

		// Do an HTTP GET to get the values
		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, $url );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
		$xml = curl_exec( $ch );

		if (curl_errno($ch)) {
			$this->access_credentials_error = "Curl error number:".curl_errno($ch);
			return false;
		}
		curl_close($ch);

		// Check in the returned XML for an error
		$match_array = array();
		if (preg_match("@<ErrorCode>(.+)</ErrorCode>@", $xml, $match_array) == 1) {
			$this->access_credentials_error = "Error code returned in XML response: $match_array[1]";
			return false;
		}

		// Get the cookie
		if (preg_match("@(Y=.*)@", $xml, $match_array) == 1) {
			$this->cookie = $match_array[1];
		} else {
			$this->access_credentials_error = "No cookie found";
			return false;
		}

		// Get the WSSID - Web Services Session ID. Used to avoid replay attacks
		$match_array = array();
		if (preg_match("@<WSSID>(.+)</WSSID>@", $xml, $match_array) == 1) {
			$this->WSSID = $match_array[1];
		} else {
			$this->access_credentials_error = "No WSSID found";
			return false;
		}

		// Get the timeout value. This is the length of time, in seconds, that
		// The cookie value is valid. Usually 3600 seconds (1 hour).
		$match_array = array();
		if (preg_match( "@<Timeout>(.+)</Timeout>@", $xml, $match_array) == 1) {
			$this->timeout = $match_array[1];
		} else {
			$this->access_credentials_error = "No timeout found";
			return false;
		}

		return true;
	}
}

class YBBauthREST extends YBBauthWebServices  {

	/**
	 * Takes a REST-style web service URL and adds the necessary parameters
	 * to turn it into an authenticated REST-style web service URL for use
	 * with Yahoo browser-based authentication.
	 *
	 * @param string $url A valid URL for a web service call minus the authentication
	 * credentials.
	 * @return false | string Returns the full URL you can use to make an authenticated
	 * web service call. Returns false on error and the error should 
	 * be in $this->access_credentials_error
	 */
	function createAuthWSurl($url) {
		// If we already have the authentication cookie, don't bother getting the
		// credentials again. If you want to force getting credentials again, you
		// can unset($this->cookie) before calling this.
		if (!isset($this->cookie)) {
			if (!$this->getAccessCredentials()) {
				return false;
			}
		}
		// Security concern -- make sure there is a question mark in the URL
		// If there's no question mark, add one.
		$url = trim($url);
		$url.= strpos($url,'?') === false ? '?' : '&';

		return $url."WSSID=$this->WSSID&appid=$this->appid";
	}

	/**
	 * Make an authenticated web services call using HTTP GET
	 *
	 * @param string $url The web services call minus the authentication credentials
	 * @return string | false If successful, a string is returned containing the web
	 * service response which might be XML, JSON, or some other type of text. If a curl
	 * error occurs, the error is stored in $this->access_credentials_error. Note that
     * access to the HTTP status code (for further error checking) is not provided
     * in this method.
	 */
	function makeAuthWSgetCall($url) {

		// Add the authentication credentials to the web service call
		$url = $this->createAuthWSurl($url);
		if ($url === false) {
			return false;
		}

		// Do an HTTP GET using Curl
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Cookie: $this->cookie"));
		$xml = curl_exec($ch);
		if (curl_errno ($ch)) {
			$this->web_services_error = "Curl error number:".curl_errno($ch);
			return false;
		}
		curl_close( $ch );

		return $xml;
	}

	/**
	 * Make an authenticated web services call using HTTP POST
	 *
	 * @param string $url The web services call minus the authentication credentials
	 * @return string | false If successful, a string is returned containing the web
	 * service response which might be XML, JSON, or some other type of text. If a curl
	 * error occurs, the error is stored in $this->access_credentials_error. Note that
     * access to the HTTP status code (for further error checking) is not provided
     * in this method.
	 */
	function makeAuthWSpostCall($url) {

		$url = $this->createAuthWSurl($url);
		if ($url === false) {
			return false;
		}

		$parts = parse_url($url);

		$prefix = $parts["scheme"]."://".$parts["host"].$parts["path"];

		$ch = curl_init($prefix);
		curl_setopt ($ch, CURLOPT_POST, true);
		curl_setopt ($ch, CURLOPT_POSTFIELDS, $parts["query"]);
		curl_setopt( $ch, CURLOPT_HTTPHEADER, array("Cookie: $this->cookie"));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$xml = curl_exec( $ch );
		if (curl_errno( $ch )) {
			$this->web_services_error = "Curl error number:".curl_errno($ch);
			return false;
		}
		curl_close( $ch );

		return $xml;
	}

}

class YahooMailJSONRPC Extends YBBauthWebServices {

	/**
	 * Create a JSON-RPC client
	 *
	 * @param string $body The JSON method and parameters
	 * @return string | false If successful, a string is returned containing the JSON
	 * data decoded into a PHP array. If a curl or JSON encode/decode
	 * error occurs, the error is stored in $this->web_services_error. Note that
     * access to the HTTP status code (for further error checking) is not provided
     * in this method.
	 */
	function JSONRPCpost($body) {

		// Use the JSON PHP extension if it's available
		if (function_exists('json_encode')) {
			// Anything json_encode can't encode is encoded as nulls
			$data = json_encode($body);
		} else {
			$json = new Services_JSON();
			$data = $json->encode($body);
			if ($json->isError($data)) {
				$this->web_services_error = "Error encoding JSON";
				return false;
			}
		}
		$ch = curl_init(JSONRPC_PREFIX."?appid=$this->appid&WSSID=$this->WSSID");
		curl_setopt ($ch, CURLOPT_POST, true);
		curl_setopt ($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Cookie: $this->cookie"));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$jsondata = curl_exec( $ch );
		if (curl_errno( $ch )) {
			$this->web_services_error = "Curl error number:".curl_errno($ch);
			return false;
		}
		curl_close( $ch );

		// Use the JSON PHP extension if it's available
		if (function_exists('json_encode')) {
			$data = json_decode($jsondata);
			if ($data == null) {
				$this->web_services_error = "Error decoding JSON";
				return false;
			}
		} else {
			$data = $json->decode($jsondata);
			if ($json->isError($data)) {
				$this->web_services_error = "Error decoding JSON";
				return false;
			}
		}
		return $data;
	}
}
?>