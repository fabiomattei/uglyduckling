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
class EchoMailer extends BaseMailer {

	function send( string $dest_email, string $from_email, string $subject, string $message ) { 
		echo('Destination: ' . $dest_email . ' From: '. $from_email . ' Subject: ' . $subject . ' Message: ' . $message ); 
	}

}
