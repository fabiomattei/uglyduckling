<?php

namespace Firststep\SecurityCheckers;

/**
 * Created fabio
 * Date: 07/01/2018
 * Time: 18:58
 */

class PrivateSecurityChecker implements SecurityChecker {

    public function isSessionValid($sessionLoggedIn, $sessionIp, $sessionUserAgent, $sessionLastLogin, $serverRemoteAddr, $serverHttpUserAgent) {
    	// TODO implement
    }

}
