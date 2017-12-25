<?php

namespace Firststep\Request;

// use templates\blocks\message\Messages;
// use core\libs\gump\GUMP;

class Request {

	function __construct() {
	}

    /**
     * Get the session variable $_SESSION['msginfo']
     * @return string
     */
    public function getSessionMsgInfo(): string {
        return $_SESSION['msginfo'];
    }

    /**
     * Set the session variable $_SESSION['msginfo']
     * @param string $msgInfo
     */
    public function setSessionMsgInfo(string $msgInfo) {
        $_SESSION['msginfo'] = $msgInfo;
    }

    /**
     * Get the session variable $_SESSION['msgwarning']
     * @return string
     */
    public function getSessionMsgWarning(): string {
        return $_SESSION['msgwarning'];
    }

    /**
     * Set the session variable $_SESSION['msgwarning']
     * @param string $msgWarning
     */
    public function setSessionMsgWarning(string $msgWarning) {
        $_SESSION['msgwarning'] = $msgWarning;
    }

    /**
     * Get the session variable $_SESSION['msgerror']
     * @return string
     */
    public function getSessionMsgError(): string {
        return $_SESSION['msgerror'];
    }

    /**
     * Set the session variable $_SESSION['msgerror']
     * @param string $msgError
     */
    public function setSessionMsgError(string $msgError) {
        $_SESSION['msgerror'] = $msgError;
    }

    /**
     * Get the session variable $_SESSION['msgsuccess']
     * @return string
     */
    public function getSessionMsgSuccess(): string {
        return $_SESSION['msgsuccess'];
    }

    /**
     * Set the session variable $_SESSION['msgsuccess']
     * @param string $msgSuccess
     */
    public function setSessionMsgSuccess(string $msgSuccess) {
        $_SESSION['msgsuccess'] = $msgSuccess;
    }

    /**
     * Get the session variable $_SESSION['flashvariable']
     * @return string
     */
    public function getSessionFlashVariable(): string {
        return $_SESSION['flashvariable'];
    }

    /**
     * Set the session variable $_SESSION['flashvariable']
     * @param string $flashvariable
     */
    public function setSessionFlashVariable(string $flashvariable) {
        $_SESSION['flashvariable'] = $flashvariable;
    }

    /**
     * Called to reset session variables at the end of the next page rendering
     */
    public function endOfRound() {
        unset($_SESSION['msginfo']);
        unset($_SESSION['msgwarning']);
        unset($_SESSION['msgerror']);
        unset($_SESSION['msgsuccess']);
        unset($_SESSION['flashvariable']);
    }

    public function isGetRequest(): bool {
        return $_SERVER["REQUEST_METHOD"] == "GET";
    }

    public function isPostRequest(): bool {
        return $_SERVER["REQUEST_METHOD"] == "POST";
    }

    public function isSessionValid() {
        // check if user logged in
        if (!(isset($_SESSION['logged_in']) && $_SESSION['logged_in'])) {
            return false;
        }

        // check if ip matches
        if (!isset($_SESSION['ip']) || !isset($_SERVER['REMOTE_ADDR'])) {
            return false;
        }
        if (!$_SESSION['ip'] === $_SERVER['REMOTE_ADDR']) {
            return false;
        }

        // check user agent
        if (!isset($_SESSION['user_agent']) || !isset($_SERVER['HTTP_USER_AGENT'])) {
            return false;
        }
        if (!$_SESSION['user_agent'] === $_SERVER['HTTP_USER_AGENT']) {
            return false;
        }

        // check elapsed time
        $max_elapsed = 60 * 60 * 24; // 1 day
        // return false if value is not set
        if (!isset($_SESSION['last_login'])) {
            return false;
        }
        if (!($_SESSION['last_login'] + $max_elapsed) >= time()) {
            return false;
        }

        return true;
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

}
