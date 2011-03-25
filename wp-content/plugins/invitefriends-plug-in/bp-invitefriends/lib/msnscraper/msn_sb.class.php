<?php

class switchboard
{
	// font colours/styles
	var $font_fn = 'Arial';
	var $font_co = '333333';
	var $font_ef = '';


	// other
	var $debug = 1;
	var $trID = 1;
	var $email = '';


	function switchboard()
	{
		$this->session_start_time = time();
	}


	/**
	 *
	 * desc	:	send IM message
	 *
	 * in	:	$ns		=	notification server connection
	 *		$msg		=	message to send
	 *		$passport	=	current logged in user
	 *		$email		=	user to send message to
	 *
	 * out	:	true on success else return false
	 *
	 */

	function tx_im($ns, $msg, $passport, $email)
	{
		$message = "MIME-Version: 1.0\r\nContent-Type: text/plain; charset=UTF-8\r\nX-MMS-IM-Format: FN=$this->font_fn; EF=$this->font_ef; CO=$this->font_co; CS=0; PF=22\r\n\r\n$msg";
		$message = "MSG 20 N ".strlen($message)."\r\n$message";

		if (@is_resource($this->sb))
		{
			// switchboard session already open
			$this->_put($message);

			return true;
		}
		else
		{
			// open switchboard session through NS
			fputs($ns, "XFR $this->trID SB\r\n");


			$ns_data = fgets($ns, 4096);

			@list($xfr,,, $server,, $as) = explode(' ', $ns_data);

			if ($xfr != 'XFR')
			{
				echo 'unable to read NS info. last message: ';
				echo $ns_data;

				return false;
			}



			list($server, $port) = explode(':', $server);

			if ($this->sb = @fsockopen($server, $port, $errno, $errstr, 5))
			{
				$this->_put("USR $this->trID $passport $as\r\n");
				$this->_get();

				if (is_array($email))
				{
					foreach($email as $key => $value)
					{
						$this->_put("CAL $this->trID $value\r\n");

						if (strstr($this->_get(), 'CAL'))
						{
							$this->_get(); // should be JOI...
						}
					}
				}
				else
				{
					$this->_put("CAL $this->trID $email\r\n");

					if (strstr($this->_get(), 'CAL'))
					{
						$this->_get(); // should be JOI...
					}
				}



				$this->_put($message);

				return true;
			}
		}

		return false;
	}


	/**
	 *
	 * desc	:	recieve an IM from the switchboard
	 *
	 * in	:	none
	 * out	:	a. null on fail/no message
	 *		b. message string
	 *
	 */

	function rx_im()
	{
		$message = null;
		$msglen = null;

		stream_set_timeout($this->sb, 1);

		while (!feof($this->sb))
		{
			$data = ($msglen) ? $this->_get($msglen) : $this->_get();


			switch (substr($data, 0, 3))
			{
				default:
					//if (empty($msglen)) continue;

					$message.= $data;

					if (strlen($message) >= $msglen && !empty($msglen))
					{
						$mesg = explode("\n", trim($message));

						$last = end($mesg);


						//if (@substr($last, 0, 10) != 'TypingUser')
						if (!strstr($message, 'TypingUser'))
						{
							// this isn't a notification that the user is typing a message
							return $last;
						}


						$msglen = null;
						$message = null;
					}

					if ($this->session_start_time + 10 < time())
					{
						// looks like we've been idle for a while
						echo 'IM timed out';
						$this->im_close();
						return null;
					}
				break;
				case 'MSG':
					list(,,, $msglen) = explode (' ', $data);
				break;
				case 'BYE':
					return null;
				break;
			}
		}

		return null;
	}


	/**
	 *
	 * desc	:	authorise with switchboard from an IM invitation
	 *
	 * in	:	$server		=	switchboard server ip
	 *		$port		=	switchboard server port
	 *		$passport	=	logged in users passport email
	 *		$sID		=	session id
	 *		$as		=	auth string
	 *
	 * out	:	true on success else return false
	 *
	 */

	function auth($server, $port, $passport, $sID, $as)
	{
		if ($this->sb = @fsockopen($server, $port, $errno, $errstr, 5))
		{
			$this->_put("ANS $this->trID $passport $as $sID\r\n");

			if (!$this->rx_iro()) return false;

			return true;
		}

		return false;
	}


	/**
	 *
	 * desc	:	recieve IRO commands from IM session
	 *
	 * in	:	none
	 * out	:	true on success else return false
	 *
	 */

	function rx_iro()
	{
		if ($data = $this->_get())
		{
			@list($iro, , $cur_num, $tot, $email, $name) = explode(' ', $data);

			$sbsess->email = $email;

			if ($iro != 'IRO')
			{
				echo "** BAD data in rx_iro(): see line above **\n";
				return false;
			}

			// recieve names/list of others connected
			for ($i=1; $i<$tot; $i++)
			{
				if (!$data = $this->_get())
				{
					echo "** BAD data in rx_iro(): see line above **\n";
					return false;
				}

			}

			@list($ans) = explode(' ', $this->_get());

			if ($ans != 'ANS') return false;

			return true;
		}

		return false;
	}


	/**
	 *
	 * desc	:	close switchboard connection
	 *
	 * in	:	none
	 * out	:	none
	 *
	 */

	function im_close()
	{
		$this->_put("OUT\r\n");
		@fclose($this->sb);
	}


	/*====================================*\
		Various private functions
	\*====================================*/

	function _get($use_fread=0)
	{
		$data = ($use_fread) ? @fread($this->sb, $use_fread) : @fgets($this->sb, 4096);

		if ($data)
		{
			if ($this->debug) echo "<div class=\"r\">&lt;&lt;&lt; SB: $data</div>\n";
			return $data;
		}
		else
		{
			return false;
		}
	}

	function _put($data)
	{
		@fputs($this->sb, $data);
		$this->trID++;

		if ($this->debug) echo "<div class=\"g\">&gt;&gt;&gt; SB: $data</div>";
	}
}

?>