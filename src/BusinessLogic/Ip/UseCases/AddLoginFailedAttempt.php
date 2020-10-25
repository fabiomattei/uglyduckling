<?php

namespace Fabiom\UglyDuckling\BusinessLogic\Ip\UseCases;

class AddLoginFailedAttempt {

    public function performAction( string $remote_address, string $username, string $password, string $description, $securityLogDao ) {
        $securityLogDao->insertEvent( $remote_address, $username, $password, $description );
    }

}
