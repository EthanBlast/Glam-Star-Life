<?php

/*Yahoo API
*/

class connectToYahooApi{
   var $authObj;
   var $APPID;
   var $SECRET;
   
   function connectToYahooApi(){
	$salvati=get_option("wp_InviteFriends");
	 $this->APPID=$salvati['yahooAPPID'];
	$this->SECRET=$salvati['yahooSECRET'];
	}

function CreateLink() {
	$v = phpversion();
	if ($v[0] == '4') {
		include("lib/ybrowserauth.class.php4");
		//echo "<br>VERSIONE 4<br>";
	} elseif ($v[0] == '5') {
		include("lib/ybrowserauth.class.php5");
		//echo "<br>VERSIONE 5<br>";
	} else {
		die('Error: could not find the bbauth PHP class file.');
	}
   
	$this->authObj = new YBBauthREST($this->APPID, $this->SECRET);

	// If Yahoo! isn't sending the token, then we aren't coming back from an
	// authentication attempt
	if (empty($_GET["token"])) {
		// You can send some data along with the authentication request
		// In this case, the data is the string 'some_application_data'
		echo 'You have not signed on using Yahoo yet<br /><br />';
		echo '<a href="'.$this->authObj->getAuthURL('some_application_data', true).'">Click here to authorize on Yahoo</a>';
		return;
	}
	
}

function seeYahooContact(){
// Validate the sig
   $authObj=$this->authObj;
	if ($authObj->validate_sig()) {		
    $path="http://address.yahooapis.com/api/ws/v1/searchContacts?format=xml&fields=email&email.present=1";
	$xmlstr=$authObj->makeAuthWSgetCall($path);
	/*formato
	      <search-response>
		<contact cid="2">
			<email fid="7">@...</email>
			<email work="true" fid="8">...@...</email>
		</contact>
		<contact cid="2">
			<email fid="7">@...</email>
			<email work="true" fid="8">...@...</email>
		</contact>
	</search-response>
	*/
	//echo $xmlstr."<br>".  strlen($xmlstr)."<br>";
	
//	print htmlentities($xmlstr);

	require_once("lib/parser_php4.phps"); 	
	$xml='<?xml version="1.0" encoding="UTF-8" standalone="yes"?> <!DOCTYPE search-response SYSTEM "http://l.yimg.com/us.yimg.com/lib/pim/r/abook/xml/2/pheasant.dtd"><search-response><contact cid="2"><email fid="7">babby@pippo.it</email><email work="true" fid="8">giovannicaputo86@gmail.com</email></contact><contact cid="1"><email fid="3">caputo.nicola@libero.it</email><email work="true" fid="4">prova@alice.it</email></contact></search-response><!-- web214.address.pim.mud.yahoo.com uncompressed/chunked Sun Jan 4 06:40:33 PST 2009 -->';
	$parser = new XMLParser($xmlstr);

	//Work the magic...
	$parser->Parse();
	$x=Array();
	$cont=0;
	foreach($parser->document->contact as $c)
	{
		//echo $c->mail[0]->tagData. "<br>*";
		//echo $c->tagData;
		//echo $c->tagAttrs['cid'];
		//echo $c->tagAttrs['fid'];
		  foreach ($c->email as $m){
		   //echo $m->tagData;
		   //echo $m->tagAttrs['fid'];
		   $x[$cont]=Array($m->tagData);
		   $cont++;
		   
		  }
		
		//var_dump($c);
	}
	//print_r($x);
	
	global $current_blog;
	$urlpag=$current_blog->domain.$_SERVER['REQUEST_URI'];
	selectfriends($x);
	} else {
		die('<h3>BBauth authentication Failed</h3> Possible error msg is in $sig_validation_error:<br />'. $authObj->sig_validation_error);
	}


}


}


?>