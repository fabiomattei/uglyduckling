<?php

namespace Fabiom\UglyDuckling\Framework\BusinessLogic\Ip\UseCases;

class AddEscalationFailedAttemptToSecurityLog {

    public function performAction( string $remote_address, string $username, string $description, $securityLogDao ) {
        $securityLogDao->insertEvent( $remote_address, $username, '', $description );
    }

}
