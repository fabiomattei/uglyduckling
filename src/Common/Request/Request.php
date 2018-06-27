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
    * Diventa array( 'action', array( 'par1', 'par2', 'par3' ) )
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
        if ( isset( $params[2] ) ) { $this->parameters[] = $params[2]; }
        if ( isset( $params[3] ) ) { $this->parameters[] = $params[3]; }
        if ( isset( $params[4] ) ) { $this->parameters[] = $params[4]; }
        if ( isset( $params[5] ) ) { $this->parameters[] = $params[5]; }
        if ( isset( $params[6] ) ) { $this->parameters[] = $params[6]; }

        if (!StringUtils::validate_string( $this->action ))
            throw new \Exception('Illegal access to spliturl!!!');
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

}
