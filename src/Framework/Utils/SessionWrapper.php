<?php

namespace Fabiom\UglyDuckling\Framework\Utils;

class SessionWrapper {

    /**
     * Check using the isset native PHP function if a specific session parameter has been set in $_SESSION super array
     *
     * @param string $parameterName
     * @return bool
     */
    static public function isSessionParameterSet( string $parameterName ): bool {
        return isset($_SESSION[$parameterName]);
    }

    /**
     * Get a session parameter previously set in $_SESSION super array
     *
     * @param string $parameterName
     * @return string
     */
    static public function getSessionParameter( string $parameterName ): string {
        return $_SESSION[$parameterName] ?? '';
    }

    /**
     * Get a pointer to a session parameter previously set in $_SESSION super array
     * Used for bindpar in PDO parameters
     *
     * @param string $parameterName
     * @return string
     */
    static public function &getPointerToSessionParameter( string $parameterName ): string {
        return $_SESSION[$parameterName];
    }

    /**
     * Set a session parameter in $_SESSION super array
     *
     * @param string $parameterName
     * @param string $parameterValue
     * @return string
     */
    static public function setSessionParameter( string $parameterName, string $parameterValue ): string {
        return $_SESSION[$parameterName] = $parameterValue;
    }

    static public function setSessionUserId( $user_id ) {
        $_SESSION['user_id'] = $user_id;
    }

    static public function getSessionUserId() {
        return $_SESSION['user_id'] ?? '';
    }

    static public function setSessionUsername( $username ) {
        $_SESSION['username'] = $username;
    }

    static public function getSessionUsename() {
        return $_SESSION['username'] ?? '';
    }

    static public function setSessionGroup( $group ) {
        $_SESSION['group'] = $group;
    }

    static public function getSessionGroup() {
        return $_SESSION['group'] ?? '';
    }

    static public function setSessionLoggedIn( $logged_in ) {
        $_SESSION['logged_in'] = $logged_in;
    }

    static public function getSessionLoggedIn() {
        return $_SESSION['logged_in'] ?? '';
    }

    static public function setSessionIp( $ip ) {
        $_SESSION['ip'] = $ip;
    }

    static public function getSessionIp() {
        return $_SESSION['ip'] ?? '';
    }

    static public function setSessionUserAgent( $user_agent ) {
        $_SESSION['user_agent'] = $user_agent;
    }

    static public function getSessionUserAgent() {
        return $_SESSION['user_agent'] ?? '';
    }

    static public function setSessionLastLogin( $last_login ) {
        $_SESSION['last_login'] = $last_login;
    }

    static public function getSessionLastLogin() {
        return $_SESSION['last_login'] ?? '';
    }

    static public function setmMsgInfo( $msginfo ) {
        $_SESSION['msginfo'] = $msginfo;
    }

    static public function getMsgInfo() {
        return $_SESSION['msginfo'] ?? '';
    }

    static public function setMsgWarning( $msgwarning ) {
        $_SESSION['msgwarning'] = $msgwarning;
    }

    static public function getMsgWarning() {
        return $_SESSION['msgwarning'] ?? '';
    }

    static public function setMsgError( $msgerror ) {
        $_SESSION['msgerror'] = $msgerror;
    }

    static public function getMsgError() {
        return $_SESSION['msgerror'] ?? '';
    }

    static public function setMsgSuccess( $msgsuccess ) {
        $_SESSION['msgsuccess'] = $msgsuccess;
    }

    static public function getMsgSuccess() {
        return $_SESSION['msgsuccess'] ?? '';
    }

    static public function setFlashVariable( $flashvariable ) {
        $_SESSION['flashvariable'] = $flashvariable;
    }

    static public function getFlashVariable() {
        return $_SESSION['flashvariable'] ?? '';
    }

    /**
     * Check the session variables to see if the user opening the page has logged in to the system
     */
    static public function isUserLoggedIn() {
        return isset( $_SESSION['logged_in'] );
    }

    /**
     * Reset all session variables at the end of the page rendering in order to be ready for the next
     * page loading
     */
    static public function endOfRound() {
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
    static public function setRequestedURL( $requestedUrl ) {
        $_SESSION['prevprevrequest'] = ( isset($_SESSION['prevrequest']) ? $_SESSION['prevrequest'] : '' );
        $_SESSION['prevrequest'] = ( isset($_SESSION['request']) ? $_SESSION['request'] : '' );
        $_SESSION['request'] = $requestedUrl;
    }

    /**
     * Return the current requested URL
     */
    static public function getRequestedURL(): string {
        return $_SESSION['request'];
    }

    /**
     * Return the previous requested URL
     */
    static public function getSecondRequestedURL(): string {
        return $_SESSION['prevrequest'];
    }

    /**
     * Return the second previous requested URL
     */
    static public function getThirdRequestedURL(): string {
        return $_SESSION['prevprevrequest'];
    }

    static public function getCsrfToken(): string {
        return $_SESSION['csrftoken'];
    }

    static public function createCsrfToken() {
        $_SESSION['csrftoken'] = StringUtils::generateRandomString( 40 );
    }

}