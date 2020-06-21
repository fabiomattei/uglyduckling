<?php

/**
 * Created Fabio Mattei
 * Date: 21-06-2020
 * Time: 21:43
 */

namespace Fabiom\UglyDuckling\Common\Mailer;

/**
 * Class EchoMailer
 *
 * This class gives the structure to create a Mail.
 *
 */
class EchoMailer {

	function send( string $dest_email, string $from_email, string $object, string $message ) { 
		echo('Destination: ' . $dest_email . ' From: '. $from_email . ' Object: ' . $object . ' Message: ' . $messages ); 
	}

}
