<?php

namespace Fabiom\UglyDuckling\Framework\Mailer;

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

    function send( string $dest_email, string $from_email, string $subject, string $message ) {
        mail($dest_email, $subject, $message, 'From: '.$from_email);
    }

}
