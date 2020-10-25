<?php

namespace Fabiom\UglyDuckling\BusinessLogic\Ip\UseCases;

class UserIsNotDeactivated {

    public function performAction( $username, $deactivatedUserDao ) {
        return !$deactivatedUserDao->checkIfIpIsDeactivated( $username );
    }

}
