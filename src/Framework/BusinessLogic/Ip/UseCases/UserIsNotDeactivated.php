<?php

namespace Fabiom\UglyDuckling\Framework\BusinessLogic\Ip\UseCases;

use Fabiom\UglyDuckling\BusinessLogic\Ip\Daos\DeactivatedUserDao;

class UserIsNotDeactivated {

    public function performAction( $username, DeactivatedUserDao $deactivatedUserDao ) {
        return !$deactivatedUserDao->checkIfIpIsDeactivated( $username );
    }

}
