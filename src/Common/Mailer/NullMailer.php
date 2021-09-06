<?php

/**
 * Created Fabio Mattei
 * Date: 23-06-2020
 * Time: 06:32
 */

namespace Fabiom\UglyDuckling\Common\Mailer;

/**
 * Class EchoMailer
 *
 * This class gives the structure to create a Mail.
 *
 */
class NullMailer extends BaseMailer {

	function send( string $dest_email, string $from_email, string $subject, string $message ) { 
		// null
	}

}
