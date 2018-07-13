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
    * @throws \Exception    in case of empty request
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
            throw new \Exception('Illegal access to calculateSplittedURL!!!');
    }

    public function getInfo(): string {
        return '[Request] requestURI:'.$this->requestURI.' Action: '.$this->action;
    }

}
