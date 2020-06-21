<?php

/**
 * Created Fabio Mattei
 * Date: 21-06-2020
 * Time: 21:43
 */

namespace Fabiom\UglyDuckling\Common\Mailer;

/**
 * Class BaseMailer
 *
 * This class gives the structure to create a Mail.
 *
 */
class BaseMailer {
	
	private /* string */ $username;
	private /* string */ $password;
	
	function __construct( string $username, string $password ) {
		$this->username = $username;
		$this->password = $password;
	}

	function send( string $dest_email, string $from_email, string $object, string $message ) { 
		mail($dest_email, $object, $message, 'From: '.$from_email); 
	}

}
