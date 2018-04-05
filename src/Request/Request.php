<?php

namespace Firststep\Request;

class Request {

	function __construct() {
	}

    /**
     * Get the session variable $_SESSION['msginfo']
     * @return string
     */
    public function getSessionMsgInfo(): string {
        return $this->msginfo;
    }

    /**
     * Set the session variable $_SESSION['msginfo']
     * @param string $msgInfo
     */
    public function setSessionMsgInfo(string $msgInfo) {
        $this->msginfo = $msgInfo;
    }

    /**
     * Get the session variable $_SESSION['msgwarning']
     * @return string
     */
    public function getSessionMsgWarning(): string {
        return $this->msgwarning;
    }

    /**
     * Set the session variable $_SESSION['msgwarning']
     * @param string $msgWarning
     */
    public function setSessionMsgWarning(string $msgWarning) {
        $this->msgwarning = $msgWarning;
    }

    /**
     * Get the session variable $_SESSION['msgerror']
     * @return string
     */
    public function getSessionMsgError(): string {
        return $this->msgerror;
    }

    /**
     * Set the session variable $_SESSION['msgerror']
     * @param string $msgError
     */
    public function setSessionMsgError(string $msgError) {
        $this->msgerror = $msgError;
    }

    /**
     * Get the session variable $_SESSION['msgsuccess']
     * @return string
     */
    public function getSessionMsgSuccess(): string {
        return $this->msgsuccess;
    }

    /**
     * Set the session variable $_SESSION['msgsuccess']
     * @param string $msgSuccess
     */
    public function setSessionMsgSuccess(string $msgSuccess) {
        $this->msgsuccess = $msgSuccess;
    }

    /**
     * This method return a variable set in the prevoius broser request.
     * To have a better understanging look at setFlashVariable description
     *
     * Get the session variable $_SESSION['flashvariable']
     *
     * @return string
     */
    public function getSessionFlashVariable(): string {
        return $this->flashvariable;
    }

    /**
     * Set the session variable $_SESSION['flashvariable']
     * This method give to the programmer the possibility of setting a flashvariable, a
     * variable that will be active up the the next call.
     * This is ment to be used for instance to send variable from a GET form request to a
     * Post form request or in any case a variable is meant to last only to the next browser
     * request.
     *
     * @param string $flashvariable [variable that last for a request in the same session]
     */
    public function setSessionFlashVariable(string $flashvariable) {
        $this->flashvariable = $flashvariable;
    }

    public function isGetRequest(): bool {
        return $_SERVER["REQUEST_METHOD"] == "GET";
    }

    public function isPostRequest(): bool {
        return $_SERVER["REQUEST_METHOD"] == "POST";
    }

    public function getServerRequestMethod(): string {
        return $_SERVER["REQUEST_METHOD"];
    }

    public function getServerPhpSelf(): string {
        return $_SERVER["PHP_SELF"];
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
