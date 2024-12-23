<?php

namespace Fabiom\UglyDuckling\Framework\SecurityCheckers;

class PublicSecurityChecker implements SecurityChecker {

    public function isSessionValid($sessionLoggedIn, $sessionIp, $sessionUserAgent, $sessionLastLogin, $serverRemoteAddr, $serverHttpUserAgent) {
        return true;
    }

}
