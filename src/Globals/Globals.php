<?php

/**
 * Created by fabio
 * Date: 25/12/17
 * Time: 11.45
 */

class Globals {

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

}
