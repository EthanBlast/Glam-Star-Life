<?php class msnAPIgestore{
	
	public $DEBUG = false;
	 // Comma-delimited list of offers to be used.
	public $OFFERS = "Contacts.View";
	// Application key file: store in an area that cannot be
	// accessed from the Web.
	public $KEYFILE ;
	// Name of cookie to use to cache the consent token. 
	public $COOKIE = 'delauthtoken';
	private $COOKIETTL ;
	// URL of Delegated Authentication sample index page.
	public $INDEX = 'index3.php';
	public $HANDLER = 'index3.php?msn=true&gestore=true';
	
	  public function __construct(){
	  
	  $this->COOKIETTL=time() + (10 * 365 * 24 * 60 * 60);
	  
	  $urlrserver=$_SERVER['HTTP_HOST'];
    $urlSub=$_SERVER['REQUEST_URI'];
	$pos=stripos($urlSub, "/wp-content/");
	if ($pos!==0){
		$urlrserver=$urlrserver.substr($urlSub,0, $pos);
	}
	
	
	$this->KEYFILE= "http://".$urlrserver.'/bp/wp-content/plugins/bp-invitefriends/lib/msnAPI/DelAuth-Sample1.xml';
	//echo    $this->KEYFILE;
	  }
 
  public static function fixed_base_convert($numstring, $frombase, $tobase)
	{
	    $chars = "0123456789abcdefghijklmnopqrstuvwxyz";
	    $tostring = substr($chars, 0, $tobase);
	
	    $length = strlen($numstring);
	    $result = '';
	    for ($i = 0; $i < $length; $i++)
	    {
		$number[$i] = strpos($chars, $numstring{$i});
	    }
	    do
	    {
		$divide = 0;
		$newlen = 0;
		for ($i = 0; $i < $length; $i++)
		{
		    $divide = $divide * $frombase + $number[$i];
		    if ($divide >= $tobase)
		    {
			$number[$newlen++] = (int)($divide / $tobase);
			$divide = $divide % $tobase;
		    } elseif ($newlen > 0)
		    {
			$number[$newlen++] = 0;
		    }
		}
		$length = $newlen;
		$result = $tostring{$divide} . $result;
	    } while ($newlen != 0);
	    return $result;
	}   
	
	public static function hexaTo64SignedDecimal($hexa)
	{
	    $bin = msnAPIgestore::fixed_base_convert($hexa, 16, 2);
	    if (64 === strlen($bin) and 1 == $bin[0])
	    {
		$inv_bin = strtr($bin, '01', '10');
		$i = 63;
		while (0 !== $i)
		{
		    if (0 == $inv_bin[$i])
		    {
			$inv_bin[$i] = 1;
			$i = 0;
		    }
		    else
		    {
			$inv_bin[$i] = 0;
			$i--;
		    }
		}
		return '-' . msnAPIgestore::fixed_base_convert($inv_bin, 2, 10);
	    }
	    else
	    {
		return msnAPIgestore::fixed_base_convert($hexa, 16, 10);
	    }
	}
 
 
  function init(){
	$wll = WindowsLiveLogin::initFromXml($this->KEYFILE);
	$wll->setDebug($this->DEBUG);
	return $wll;
}


function getLink($wll){
	//Get the consent URL for the specified offers.
	$consenturl = $wll->getConsentUrl($this->OFFERS);
	$message_html = "<p>Please <a href=\"$consenturl\">click here</a> to grant consent 
                 for this application to access your Windows Live data.</p>"; 
	 $token = null;
	$cookie = @$_COOKIE[$this->COOKIE];
	if ($cookie) {
		$token = $wll->processConsentToken($cookie);
	}

	if ($token && !$token->isValid()) {
		$token = null;
	}
	if ($token) {
	   $message_html = <<<END
    <p>Click <a href="{$this->HANDLER}&action=delauth">here</a> to remove the token from your session.</p>
END;
	}
	 return $message_html;
}

function getxml($wll){

		$token = null;
		$cookie = @$_COOKIE[$this->COOKIE];
		if ($cookie) {
		    $token = $wll->processConsentToken($cookie);
		}

		if ($token && !$token->isValid()) {
		    $token = null;
		}

		if ($token) {
		    // Convert Unix epoch time stamp to user-friendly format.
		    $expiry = $token->getExpiry();
		    $expiry = date(DATE_RFC2822, $expiry);
		    // Prepare the message to display the consent token contents.
		 

		 $delegation_token = $token->getDelegationToken();
			$cid = $token->getLocationID();
			
			// convert the cid to a signed 64-bit integer
			$lid = msnAPIgestore::hexaTo64SignedDecimal($cid, 16, 10);
			$uri = "https://livecontacts.services.live.com/users/@C@" . $lid . "/rest/livecontacts";
			
			$host = "livecontacts.services.live.com";
			$urisplit = split("://", $uri);
			$page = substr($urisplit[1], strlen($host));
			
			// Add the token to the header
			$headers = array("Authorization: DelegatedToken dt=\"$delegation_token\"");
			
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $uri);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_TIMEOUT, 60);
			curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			
			$xml = curl_exec($curl);
			return $xml;
			
		 
			}
}
 
 }
?>