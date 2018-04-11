<?php

namespace Firststep\SecurityCheckers;

/**
 * Created fabio
 * Date: 07/01/2018
 * Time: 18:58
 */

class PublicSecurityChecker implements SecurityChecker {

    public function isSessionValid($sessionLoggedIn, $sessionIp, $sessionUserAgent, $sessionLastLogin, $serverRemoteAddr, $serverHttpUserAgent) {
    	return true;
    }

}
