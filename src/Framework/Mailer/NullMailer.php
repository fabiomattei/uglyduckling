<?php

namespace Fabiom\UglyDuckling\Framework\Mailer;

/**
 * Class NullMailer
 *
 * This class gives the structure to create a Mail.
 *
 */
class NullMailer extends BaseMailer {

    function send( string $dest_email, string $from_email, string $subject, string $message ) {
        // null
    }

}
