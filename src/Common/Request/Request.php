<?php

namespace Firststep\Common\Request;

use Firststep\Common\Utils\StringUtils;

class Request {

    private $msginfo = '';
    private $msgwarning = '';
    private $msgerror = '';
    private $msgsuccess = '';
    private $flashvariable = '';
    private $requestURI = '';

	function __construct() {
	}

    public function setServerRequestURI( string $requestURI ) {
        $this->requestURI = $requestURI;
        $this->calculateSplittedURL();
    }

    public function getAction() {
        return $this->action;
    }

    public function getParameters() {
        return $this->parameters;
    }

    /**
    * @param $request             a string containing the request
    *
    * @return array               an array containing the results
    *
    * @throws\Exception    in case of empty request
    *
    * Prende una stringa e la divide nelle sue parti.
    * Restituisce poi le parti ottenute attraverso un array.
    *
    * Es. 'folder-subfolder/action/par1/par2/par3'
    * Diventa array( 'folder', 'subfolder', 'action', array( 'par1', 'par2', 'par3' ) )
    */
    public function calculateSplittedURL() {
        $request2 = str_replace( '.html', '', $this->requestURI );
        $request3 = str_replace( '.pdf', '', $request2 );
        $request  = preg_replace( '/\?.*/', '', $request3 );

        if ( $request == '' ) throw new \Exception('General malfuction!!!');
    
        #split the string by '/'
        $params = explode( '/', $request );

        $this->action = $params[1];
        $this->parameters = array();
        if ( isset( $params[2] ) ) { $parameters[] = $params[2]; }
        if ( isset( $params[3] ) ) { $parameters[] = $params[3]; }
        if ( isset( $params[4] ) ) { $parameters[] = $params[4]; }
        if ( isset( $params[5] ) ) { $parameters[] = $params[5]; }
        if ( isset( $params[6] ) ) { $parameters[] = $params[6]; }

        if (!StringUtils::validate_string( $this->action ))
            throw new \Exception('Illegal access to spliturl!!!');
    }

    public function setSecurityChecker( $securityChecker ) {
        $this->securityChecker = $securityChecker;
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

    public function setServerRequestMethod( string $serverRequestMethod ) {
        $this->serverRequestMethod = $serverRequestMethod;
    }

    public function isGetRequest(): bool {
        return $this->serverRequestMethod == "GET";
    }

    public function isPostRequest(): bool {
        return $this->serverRequestMethod == "POST";
    }

    public function getServerRequestMethod(): string {
        return $this->serverRequestMethod;
    }

    public function setServerPhpSelf( string $serverPhpSelf ) {
        $this->serverPhpSelf = $serverPhpSelf;
    }

    public function getServerPhpSelf(): string {
        return $this->serverPhpSelf;
    }

    public function setServerRemoteAddress( string $serverRemoteAddr ) {
        $this->serverRemoteAddr = $serverRemoteAddr;
    }

    public function setServerHttpUserAgent( string $serverHttpUserAgent ) {
        $this->serverHttpUserAgent = $serverHttpUserAgent;
    }

    public function setSessionLoggedId( string $sessionLoggedIn ) {
        $this->sessionLoggedIn = $sessionLoggedIn;
    }

    public function setSessionIp( string $sessionIp ) {
        $this->sessionIp = $sessionIp;
    }

    public function setSessionUserAgent( string $sessionUserAgent ) {
        $this->sessionUserAgent = $setSessionUserAgent;
    }

    public function setSessionLastLogin( string $sessionLastLogin ) {
        $this->sessionLastLogin = $sessionLastLogin;
    }

    public function isSessionValid() {
        return $this->securityChecker->isSessionValid(
            $this->sessionLoggedIn, 
            $this->sessionIp, 
            $this->sessionUserAgent, 
            $this->sessionLastLogin, 
            $this->serverRemoteAddr, 
            $this->serverHttpUserAgent
        );
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
