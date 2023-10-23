<?php 

/* ==============================================================
 * This object has been created to adapt the framework to 
 * Google App Engine environment
 * The object has just a method that send an email.
 * ============================================================== */

use \google\appengine\api\mail\Message;

class GaeMailer {

	/**
	 * @param $dest_email   string containing email
	 * @param $object		email object
	 * @param $messagebody  email body
	 */
	function send($dest_email, $object, $messagebody) {

		try {
			$message = new Message();
			$message->setSender(EMAIL_SENDER);
			$message->addTo( $dest_email );
			$message->setSubject( $object );
			$message->setTextBody( $messagebody );
			$message->send();
		} catch (InvalidArgumentException $e) {
			$logger = new Logger();
			$logger->write($e->getMessage(), __FILE__, __LINE__);
		}

	}

    /**
     * @param $dest_emails  array of strings containing email
     * @param $object		email object
     * @param $messagebody  email body
     */
	function send_many($dest_emails, $object, $messagebody) {

		try {
			$message = new Message();
			$message->setSender(EMAIL_SENDER);
            foreach( $dest_emails as $em) {
                $message->addTo( $em );
            }
			$message->setSubject( $object );
			$message->setTextBody( $messagebody );
			$message->send();
		} catch (InvalidArgumentException $e) {
			$logger = new Logger();
			$logger->write($e->getMessage(), __FILE__, __LINE__);
		}

	}
	
} 
