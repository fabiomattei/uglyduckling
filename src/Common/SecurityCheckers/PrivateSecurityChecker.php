<?php

namespace Firststep\Common\SecurityCheckers;

/**
 * Created fabio
 * Date: 07/01/2018
 * Time: 18:58
 */

class PrivateSecurityChecker implements SecurityChecker {

    public function isSessionValid($sessionLoggedIn, $sessionIp, $sessionUserAgent, $sessionLastLogin, $serverRemoteAddr, $serverHttpUserAgent) {
        // check if user logged in
        if (!(isset($sessionLoggedIn) && $sessionLoggedIn)) {
            return false;
        }

        // check if ip matches
        if (!isset($sessionIp) || !isset($serverRemoteAddr)) {
            return false;
        }
        if (!$sessionIp === $serverRemoteAddr) {
            return false;
        }

        // check user agent
        if (!isset($sessionUserAgent) || !isset($serverHttpUserAgent)) {
            return false;
        }
        if (!$sessionUserAgent === $serverHttpUserAgent) {
            return false;
        }

        // check elapsed time
        $max_elapsed = 60 * 60 * 24; // 1 day
        // return false if value is not set
        if (!isset($sessionLastLogin)) {
            return false;
        }
        if (!($sessionLastLogin + $max_elapsed) >= time()) {
            return false;
        }

        return true;
    }

}
