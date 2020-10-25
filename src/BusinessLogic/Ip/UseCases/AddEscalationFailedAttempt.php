<?php

namespace Fabiom\UglyDuckling\BusinessLogic\Ip\UseCases;

class AddEscalationFailedAttempt {

    public function performAction( string $remote_address, string $username, string $description, $securityLogDao ) {
        $securityLogDao->insertEvent( $remote_address, $username, '', $description );
    }

}
