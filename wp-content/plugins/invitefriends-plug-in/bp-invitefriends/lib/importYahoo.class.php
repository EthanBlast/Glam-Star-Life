<?php
        /************************************************************************************************
         * Yahoo.com Contact List Grabber                                                               *
         * Version 1.0                                                                                  *
         * Released 2 July, 2007                                                                      *
         * Author: Ma'moon Al-akash ( soosas@gmail.com )                                                *
         *                                                                                              *
         * This program is free software; you can redistribute it and/or                                *
         * modify it under the terms of the GNU General Public License                                  *
         * as published by the Free Software Foundation; either version 2                               *
         * of the License, or (at your option) any later version.                                       *
         *                                                                                              *
         * This program is distributed in the hope that it will be useful,                              *
         * but WITHOUT ANY WARRANTY; without even the implied warranty of                               *
         * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the                                *
         * GNU General Public License for more details.                                                 *
         *                                                                                              *
         * You should have received a copy of the GNU General Public License                            *
         * along with this program; if not, write to the Free Software                                  *
         * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.              *
         ************************************************************************************************/

	class yahooGrabber {
		
		var $_username;
		var $_password;
		var $_cookie;
		

		/**
		 * Constructor of the class, initialzing the privates and validates them 
		 * @param $username, yahoo login name
		 * @param $password, yahoo account password
		 */
		function yahooGrabber( $username, $password ) {
			$un	= trim ( $username );
			$pw	= trim ( $password );
			if ( empty ( $un ) )
				die( 'Please Provide your yahoo login name!' );
			if ( empty ( $pw ) )
				die( 'Please Provide your yahoo password account!' );
			$this->_username = $un;
			$this->_password = $pw;
			$this->_cookie	 = '/var/tmp/yahoo_'.$un.'.txt';
		}

		/**
		 * returns $this->_username
		 * @return string
		 */
		function _getUsername() {
			return $this->_username;
		}

		/**
		 * returns $this->_password
		 * @return string
		 */
		function _getPassword() {
			return $this->_password;
		}

		/**
		 * return $this->_cookie, the cookie file path
		 * @return string
		 */
		function _getCookie() {
			return $this->_cookie;
		}

		/**
		 * returns the grabbed set of contacts from the yahoo account in the form of array['name'] = email
		 * @return array
		 */
		function grabYahoo() {
			$ch = curl_init();

			/******************************** AUTHENTICATION SECTION ********************************/
			curl_setopt( $ch, CURLOPT_URL, 'http://login.yahoo.com/config/login?' );
			curl_setopt( $ch, CURLOPT_POST, 22 );
			$postFields = 'login='.$this->_getUsername().'&passwd='.$this->_getPassword().'&.src=&.tries=5&.bypass=&.partner=&.md5=&.hash=&.intl=us&.tries=1&.challenge=ydKtXwwZarNeRMeAufKa56.oJqaO&.u=dmvmk8p231bpr&.yplus=&.emailCode=&pkg=&stepid=&.ev=&hasMsgr=0&.v=0&.chkP=N&.last=&.done=http://address.mail.yahoo.com/';
			curl_setopt( $ch, CURLOPT_POSTFIELDS, $postFields );
			curl_setopt( $ch, CURLOPT_COOKIEJAR, $this->_getCookie() );
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
			if( strstr( curl_exec( $ch ), 'Invalid ID or password' ) ) {
				return array();
			}
			/****************************************************************************************/

			// Set the User Agent
			curl_setopt( $ch, CURLOPT_USERAGENT, 'YahooSeeker-Testing/v3.9 (compatible; Mozilla 4.0; MSIE 5.5; http://search.yahoo.com/)' );
			// Set the URL that PHP will fetch using cURL
			curl_setopt( $ch, CURLOPT_URL, 'http://address.mail.yahoo.com/' );
			// Set the number of fields to be passed via HTTP POST
			curl_setopt( $ch, CURLOPT_POST, 1 );
			// Set the filename where cookie information will be saved
			curl_setopt( $ch, CURLOPT_COOKIEJAR, $this->_getCookie() );
			// Set the filename where cookie information will be looked up
			curl_setopt( $ch, CURLOPT_COOKIEFILE, $this->_getCookie() );
			// Set the option to set Cookie into HTTP header
			curl_setopt( $ch, CURLOPT_COOKIE, 0 );
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
			curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1 );

			$contactsGrid = curl_exec( $ch );
			$getTable = array();
			$contactsPages = array();
			preg_match_all( '/abcnav">(.*?)<\/ol>/s', $contactsGrid, $contactsPages);
			$contactsPages[1][0] = strip_tags($contactsPages[0][0], '<a>');
			$contactsArrPages = explode( '</a>', $contactsPages[1][0] );
			foreach( $contactsArrPages as $key => $value ) {
				$removedItems	= array( '<a href="', 'title="', 'abcnav">', '"' );
				$tmp		=	trim( str_replace( $removedItems, '', $value ) );
				$tmpArray	= explode(' ', $tmp );
				$contactsArrPages[$key] = $tmpArray[0];
			}
			$contacts = array();
			foreach( $contactsArrPages as $value ) {
				$urlPages = 'http://address.mail.yahoo.com/'.$value;
				curl_setopt( $ch, CURLOPT_URL, $urlPages );
				$contactsGrid = curl_exec( $ch );

				preg_match_all( '/datatable snippets(.*?)<\/table>/s', $contactsGrid, $getTable );
				$getTbody  = array();
				$tmp = ''.$getTable[0][0];
				unset( $getTable );
				preg_match_all( '/<tbody>(.*?)<\/tbody>/s',$tmp , $getTbody );
				$getTr	= array();
				$tmp = ''.$getTbody[0][0];
				unset( $getTbody );
				preg_match_all( '/<tr (.*?)<\/tr>/s', $tmp, $getTr, PREG_SET_ORDER );
				$tmpArray = array();
				foreach( $getTr as $value ) {
					$tmpArray[] = $value[0];
				}
				unset( $getTr );
				foreach( $tmpArray as $key => $value ) {
					$value = trim( strip_tags( $value ) );
					if ( $key % 2 != 0 ) {
						$tmp = array();
						preg_match_all( '/contactname\">(.*?)<\/span>/s',$tmpArray[$key - 1], $tmp );
						$name = trim( $tmp[1][0] );
						if ( !empty( $value ) ) {
							$val	= explode( ',', $value );
							$contacts[strip_tags( $name )] = trim( str_replace( '[Edit]', '', $val[0] ) );
							//echo "<br />'FIRST-->'".$contacts[strip_tags( $name )]."<br />";
						} else {
							$url		= 'http://address.mail.yahoo.com/';
							$hrefArray	= explode( ' ', $tmp[1][0] );
							$href		= str_replace( 'href="', '', $hrefArray[1] );
							$href		= str_replace( '"', '', $href );
							$href		= str_replace( '&amp;', '&', $href );
							$url		= $url . $href;
							curl_setopt( $ch, CURLOPT_URL, $url );
							$page		= curl_exec( $ch );
							$yahooID	= array();
							preg_match_all( '/ymsgr:sendIM\?(.*?)\">/s', $page, $yahooID );
							$contacts[strip_tags( $tmp[1][0] )]	= $yahooID[1][0].'@yahoo.com';
							//echo "<br />'SECOND-->'".$contacts[strip_tags( $tmp[1][0] )]."<br />";
						}
					}
				}
			}
			// clean up and finalize the process ...
			$this->_rmFile( $this->_getCookie() );
			return $contacts;
		}

		/**
		 * Remove the target file
		 * @param $fileName, the full path and name of the target file to be removed
		 * @return VOID, nothing to be returned
		 */
		function _rmFile( $fileName ) {
			@unlink( $fileName );
		}
	}
?>
