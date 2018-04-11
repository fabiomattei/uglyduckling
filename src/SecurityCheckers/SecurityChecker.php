<?php
/**
 * User: fabio
 * Date: 11/04/2018
 * Time: 10:30
 */

namespace Firststep\SecurityCheckers;

interface SecurityChecker {

    public function isSessionValid($sessionLoggedIn, $sessionIp, $sessionUserAgent, $sessionLastLogin, $serverRemoteAddr, $serverHttpUserAgent);

}

