<?php

namespace Fabiom\UglyDuckling\Framework\SecurityCheckers;

interface SecurityChecker {
    public function isSessionValid($sessionLoggedIn, $sessionIp, $sessionUserAgent, $sessionLastLogin, $serverRemoteAddr, $serverHttpUserAgent);
}
