<?php

namespace Fabiom\UglyDuckling\Framework\BusinessLogic\Ip\UseCases;

class AddLoginFailedAttemptToSecurityLog {

    public function performAction( string $remote_address, string $username, string $password, string $description, $securityLogDao ) {
        $securityLogDao->insertEvent( $remote_address, $username, $password, $description );
    }

}
