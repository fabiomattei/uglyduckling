<?php

namespace Fabiom\UglyDuckling\Common\Wrappers;

class SessionWrapper {

    /**
     * Check using the isset native PHP function if a specific session parameter has been set in $_SESSION super array
     *
     * @param string $parameterName
     * @return bool
     */
    public function isSessionParameterSet( string $parameterName ): bool {
        return isset($_SESSION[$parameterName]);
    }

    /**
     * Get a session parameter previously set in $_SESSION super array
     *
     * @param string $parameterName
     * @return string
     */
    public function getSessionParameter( string $parameterName ): string {
        return $_SESSION[$parameterName];
    }

    /**
     * Set a session parameter in $_SESSION super array
     *
     * @param string $parameterName
     * @param string $parameterValue
     * @return string
     */
    public function setSessionParameter( string $parameterName, string $parameterValue ): string {
        return $_SESSION[$parameterName] = $parameterValue;
    }
	
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

	public function setSessionGroup( $group ) {
		$_SESSION['group'] = $group;
	}
	
	public function getSessionGroup() {
		return $_SESSION['group'];
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

	public function setmMsgInfo( $msginfo ) {
		$_SESSION['msginfo'] = $msginfo;
	}
	
	public function getMsgInfo() {
		return $_SESSION['msginfo'];
	}

	public function setMsgWarning( $msgwarning ) {
		$_SESSION['msgwarning'] = $msgwarning;
	}
	
	public function getMsgWarning() {
		return $_SESSION['msgwarning'];
	}

	public function setMsgError( $msgerror ) {
		$_SESSION['msgerror'] = $msgerror;
	}
	
	public function getMsgError() {
		return $_SESSION['msgerror'];
	}

	public function setMsgSuccess( $msgsuccess ) {
		$_SESSION['msgsuccess'] = $msgsuccess;
	}
	
	public function getMsgSuccess() {
		return $_SESSION['msgsuccess'];
	}

	public function setFlashVariable( $flashvariable ) {
		$_SESSION['flashvariable'] = $flashvariable;
	}
	
	public function getFlashVariable() {
		return $_SESSION['flashvariable'];
	}

	/**
	 * Check the session variables to see if the user opening the page has logged in to the system
	 */
	public function isUserLoggedIn() {
		return isset( $_SESSION['logged_in'] );
	}

	/**
	 * Reset all session variables at the end of the page rendering in order to be ready for the next
	 * page loading
	 */
	public function endOfRound() {
		unset($_SESSION['msginfo']);
		unset($_SESSION['msgwarning']);
		unset($_SESSION['msgerror']);
		unset($_SESSION['msgsuccess']);
		unset($_SESSION['flashvariable']);
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

    /**
     * Return the current requested URL
     */
    public function getRequestedURL(): string {
        return $_SESSION['request'];
    }

    /**
     * Return the previous requested URL
     */
    public function getSecondRequestedURL(): string {
        return $_SESSION['prevrequest'];
    }

    /**
     * Return the second previous requested URL
     */
    public function getThirdRequestedURL(): string {
        return $_SESSION['prevprevrequest'];
    }

}
