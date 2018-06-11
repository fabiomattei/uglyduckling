<?php

namespace Firststep\Common\Request;

use Firststep\Common\Wrappers;

class SessionWrapper {
	
	public function setSessionUserId( $user_id ) {
		$_SESSION['user_id'] = $user_id;
	}
	
	public function getSessionUserId() {
		return $_SESSION['user_id'];
	}
	
	public function setSessionUsername( $username ) {
		$_SESSION['username'] = $username;
	}
	
	public function getSessionUsename() {
		return $_SESSION['username'];
	}
	
	public function setSessionLoggedIn( $logged_in ) {
		$_SESSION['logged_in'] = $logged_in;
	}
	
	public function getSessionLoggedIn() {
		return $_SESSION['logged_in'];
	}
	
	public function setSessionIp( $ip ) {
		$_SESSION['ip'] = $ip;
	}
	
	public function getSessionIp() {
		return $_SESSION['ip'];
	}
	
	public function setSessionUserAgent( $user_agent ) {
		$_SESSION['user_agent'] = $user_agent;
	}
	
	public function getSessionUserAgent() {
		return $_SESSION['user_agent'];
	}
	
	public function setSessionLastLogin( $last_login ) {
		$_SESSION['last_login'] = $last_login;
	}
	
	public function getSessionLastLogin() {
		return $_SESSION['last_login'];
	}
	
    /**
     * Saving the request made to webserver
     * It saves the STRING in $_SESSION['request'] variable and moves the previous request
     * to STRING $_SESSION['prevrequest']
     *
     * @param $request STRING containing URL complete of parameters
     */
    public function setRequestedURL( $requestedUrl ) {
        $_SESSION['prevprevrequest'] = ( isset($_SESSION['prevrequest']) ? $_SESSION['prevrequest'] : '' );
        $_SESSION['prevrequest'] = ( isset($_SESSION['request']) ? $_SESSION['request'] : '' );
        $_SESSION['request'] = $requestedUrl;
    }

    public function getRequestedURL(): string {
        return $_SESSION['request'];
    }

    public function getSecondRequestedURL(): string {
        return $_SESSION['prevrequest'];
    }

    public function getThirdRequestedURL(): string {
        return $_SESSION['prevprevrequest'];
    }

}
