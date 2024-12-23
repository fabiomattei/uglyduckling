<?php

namespace Fabiom\UglyDuckling\Framework\SecurityCheckers;

/**
 * Created fabio
 * Date: 07/01/2018
 * Time: 18:58
 */

class PrivateSecurityChecker implements SecurityChecker {

    public function isSessionValid($sessionLoggedIn, $sessionIp, $sessionUserAgent, $sessionLastLogin, $serverRemoteAddr, $serverHttpUserAgent) {
        // check if user logged in
        if (empty($sessionLoggedIn)) {
            return false;
        }

        // check if ip matches
        if (empty($sessionIp) || empty($serverRemoteAddr)) {
            return false;
        }
        if (!$sessionIp === $serverRemoteAddr) {
            return false;
        }

        // check user agent
        if (empty($sessionUserAgent) || empty($serverHttpUserAgent)) {
            return false;
        }
        if (!$sessionUserAgent === $serverHttpUserAgent) {
            return false;
        }

        // check elapsed time
        $max_elapsed = 60 * 60 * 24; // 1 day
        // return false if value is not set
        if (empty($sessionLastLogin)) {
            return false;
        }
        if (!($sessionLastLogin + $max_elapsed) >= time()) {
            return false;
        }

        return true;
    }

}
