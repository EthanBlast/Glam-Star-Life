<?php


/*
This class connects to the MSNM service and returns
all the email addresses and screen names in the contact
list of the supplied user.

This is a derivation of a more general purpose php class
available at http://flumpcakes.co.uk/php/msn-messenger.

is edited by GIOVANNI CAPUTO giovannicaputo86@gmail.cm

*/

class msn
{
	// messenger.hotmail.com is an exchange server
	// using it will redirect to a server with an open slot
	// using a known server ip will help connect faster

	// commenting out $ssh_login will mean the url to the
	// secure login server will be taken from a secure
	// session.  this will slow down connecting a bit.
	// Note: comment out $ssh_login if you experience auth failures

	var $server	=	'messenger.hotmail.com';
	var $port	=	1863;

	var $nexus	=	'https://nexus.passport.com/rdr/pprdr.asp';
	var $ssh_login	=	'login.live.com/login2.srf';

	var $debug	=	0;

	var $vectorMail = array();
	
	
	//Used to prevent the script from hanging
    var $count = 0;
	
	
	// curl is used for the secure login, if you don't have
	// the php_curl library installed, you can use a curl binary
	// instead. $use_curl needs to be set to 1 to enable this.
	// set $curl to the path where curl is installed.
	// curl can be downloaded here: http://curl.haxx.se/download.html

	var $curl_bin	=	0;
	var $curl	=	'/usr/local/bin/curl';	// linux
	//var $curl	=	'c:\curl.exe';		// windows




	/**
	 *
	 * desc	:	Connect to MSN Messenger Network
	 *
	 * in	:	$passport	=	passport i.e: user@hotmail.com
	 *		$password	=	password for passport
	 *
	 * out	:	true on success else return false
	 *
	 */

	function connect($passport, $password)
	{
		$this->trID = 1;

		if ($this->fp = @fsockopen($this->server, $this->port, $errno, $errstr, 2))
		{
			$this->_put("VER $this->trID MSNP9 CVR0\r\n");

			while (! feof($this->fp))
			{
				$data = $this->_get();

				switch ($code = substr($data, 0, 3))
				{
					default:
						echo $this->_get_error($code);

						return false;
					break;
					case 'VER':
						$this->_put("CVR $this->trID 0x0409 win 4.10 i386 MSNMSGR 7.0.0816 MSMSGS $passport\r\n");
					break;
					case 'CVR':
						$this->_put("USR $this->trID TWN I $passport\r\n");
					break;
					case 'XFR':
						list(, , , $ip)  = explode (' ', $data);
						list($ip, $port) = explode (':', $ip);

						if ($this->fp = @fsockopen($ip, $port, $errno, $errstr, 2))
						{
							$this->trID = 1;

							$this->_put("VER $this->trID MSNP9 CVR0\r\n");
						}
						else
						{
							if (! empty($this->debug)) echo 'Unable to connect to msn server (transfer)';

							return false;
						}
					break;
					case 'USR':
						if (isset($this->authed))
						{
							return true;
						}
						else
						{
							$this->passport = $passport;
							$this->password = urlencode($password);

							list(,,,, $code) = explode(' ', trim($data));

							if ($auth = $this->_ssl_auth($code))
							{
								$this->_put("USR $this->trID TWN S $auth\r\n");

								$this->authed = 1;
							}
							else
							{
								if (! empty($this->debug)) echo 'auth failed';

								return false;
							}
						}
					break;
				}
			}
		}
		else
		{
			if (! empty($this->debug)) echo 'Unable to connect to msn server';

			return false;
		}
	}


	function rx_data()
	{

		$this->_put("SYN $this->trID 0\r\n");
		$this->_put("CHG $this->trID NLN\r\n");
	  $stream_info = stream_get_meta_data($this->fp);
        $email_total = 1000;
		
	$start=mktime();;
		while ((! @feof($this->fp)) && (! $stream_info['timed_out']) && ($this->count <= 1) && (count($this->vectorMail) < $email_total) && ($diff<=10))
		{
			$data = $this->_get();
			$stream_info = @stream_get_meta_data($this->fp);
		
			if ($data)
			{	
				//echo $data.'<br />';

				switch($code = substr($data, 0, 3))
				{
					default:
						// uncommenting this line here would probably give a load of "error code not found" messages.
						//echo $this->_get_error($code);
					break;
					case 'MSG':
					   //This prevents the script hanging as it waits for more content
					   $this->count++;
					break;
					case 'CHL':
						$bits = explode (' ', trim($data));

						$return = md5($bits[2].'Q1P7W2E4J9R8U3S5');
						$this->_put("QRY $this->trID msmsgs@msnmsgr.com 32\r\n$return");
					break;
					case 'LST':
					   //These are the email addresses
					   //They need to be collected in email_input
					
					   $v = explode(' ', $data);
					
					   $this->vectorMail[] = $v[1];
					   
					   
					break;
					case 'RNG':
						// someone's trying to talk to us
						list(, $sid, $server, , $as, $email, $name) = explode(' ', $data);
						
						list($sb_ip, $sb_port) = explode(':', $server);


						$sbsess = new switchboard;

						if ($sbsess->auth($sb_ip, $sb_port, $this->passport, $sid, $as))
						{
							// sb session opened
							// recieve users message
							if ($msg = $sbsess->rx_im())
							{
								// send the message straight back!
								$sbsess->tx_im($this->fp, $msg, $this->passport, $email);

								// close IM sessions
								$sbsess->im_close();
							}
							else
							{
								echo 'No message was received from user.';
							}
						}
						else
						{
							echo 'Unable to authenticate with switchboard.';
						}
					break;
				}
			}
		
			$diff=mktime()-$start;
		
		}
	}


	/*====================================*\
		Various private functions
	\*====================================*/

	function _ssl_auth($auth_string)
	{
		if (empty($this->ssh_login))
		{
			if ($this->curl_bin)
			{
				exec("$this->curl -m 60 -LkI $this->nexus", $header);
				$header = implode($header, null);
			}
			else
			{
				$ch = curl_init($this->nexus);

				curl_setopt($ch, CURLOPT_HEADER, 1);
				curl_setopt($ch, CURLOPT_NOBODY, 1);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				// curl_setopt($ch, CURLOPT_TIMEOUT, 2);

				$header = curl_exec($ch);

				curl_close($ch);
			}

			preg_match ('/DALogin=(.*?),/', $header, $out);

			if (isset($out[1]))
			{
				$slogin = $out[1];
			}
			else
			{
				return false;
			}
		}
		else
		{
			$slogin = $this->ssh_login;
		}


		if ($this->curl_bin)
		{
			$header1 = '"Authorization: Passport1.4 OrgVerb=GET,OrgURL=http%3A%2F%2Fmessenger%2Emsn%2Ecom,sign-in='.$this->passport.',pwd='.$this->password.','.$auth_string.'"';

			exec("$this->curl -m 60 -LkI -H $header1 https://$slogin", $auth_string);

			$header = null;

			foreach ($auth_string as $key => $value)
			{
				if (strstr($value, 'Unauthorized'))
				{
					echo 'Unauthorised';
					return false;
				}
				elseif (strstr($value, 'Authentication-Info'))
				{
					$header = $value;
				}
			}
		}
		else
		{
			$ch = curl_init('https://'.$slogin);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
							'Authorization: Passport1.4 OrgVerb=GET,OrgURL=http%3A%2F%2Fmessenger%2Emsn%2Ecom,sign-in='.$this->passport.',pwd='.$this->password.','.$auth_string,
							'Host: login.passport.com'
							));

			curl_setopt($ch, CURLOPT_HEADER, 1);
			curl_setopt($ch, CURLOPT_NOBODY, 1);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			// curl_setopt($ch, CURLOPT_TIMEOUT, 2);

			$header = curl_exec($ch);

			curl_close($ch);
		}

		preg_match ("/from-PP='(.*?)'/", $header, $out);

		return (isset($out[1])) ? $out[1] : false;
	}


	function _get()
	{
		if ($data = @fgets($this->fp, 4096))
		{
			if ($this->debug) echo "<div class=\"r\">&lt;&lt;&lt; $data</div>\n";

			return $data;
		}
		else
		{
			return false;
		}
	}


	function _put($data)
	{
		fwrite($this->fp, $data);

		$this->trID++;

		if ($this->debug) echo "<div class=\"g\">&gt;&gt;&gt; $data</div>";
	}


	function _get_error($code)
	{
		switch ($code)
		{
			case 201:
				return 'Error: 201 Invalid parameter';
			break;
			case 217:
				return 'Error: 217 Principal not on-line';
			break;
			case 500:
				return 'Error: 500 Internal server error';
			break;
			case 540:
				return 'Error: 540 Challenge response failed';
			break;
			case 601:
				return 'Error: 601 Server is unavailable';
			break;
			case 710:
				return 'Error: 710 Bad CVR parameters sent';
			break;
			case 713:
				return 'Error: 713 Calling too rapidly';
			break;
			case 731:
				return 'Error: 731 Not expected';
			break;
			case 800:
				return 'Error: 800 Changing too rapidly';
			break;
			case 910:
			case 921:
				return 'Error: 910/921 Server too busy';
			break;
			case 911:
				return 'Error: 911 Authentication failed';
			break;
			case 923:
				return 'Error: 923 Kids Passport without parental consent';
			break;
			case 928:
				return 'Error: 928 Bad ticket';
			break;
			default:
				return 'Error code '.$code.' not found';
			break;
		}
	}

	
	
	function getMailVector(){
		$cont=0;
		$list=Array();
		 foreach ($this->vectorMail as $c ) $list[$cont++]=Array($c);
	  return $list;
	}

	
	
	
	
}








?>
