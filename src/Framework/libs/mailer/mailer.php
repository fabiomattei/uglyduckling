<?php 

class Mailer {
	
	function send($dest_email, $object, $message) { 
		mail($dest_email, $object, $message, 'From: '); 
	}
	
} 
