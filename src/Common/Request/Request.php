<?php

namespace Fabiom\UglyDuckling\Common\Request;

use Fabiom\UglyDuckling\Common\Utils\StringUtils;

class Request {

    private /* string */ $requestURI = '';
    private /* string */ $action = '';

	function __construct() {
	}

    public function setServerRequestURI( string $requestURI ) {
        $this->requestURI = $requestURI;
        $this->calculateSplittedURL();
    }

    public function getAction() {
        return $this->action;
    }

    /**
    * @param $request  a string containing the request
    *
    * @return array    an array containing the results
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

        # removing the first '/' from the path
        $this->action = substr($request, 1); // explode( '/', $request );

        if (!StringUtils::validate_string( $this->action )) {
        	throw new \Exception('Illegal access to calculateSplittedURL!!! Unvalidated action: "' . $this->action .'" ');
        }
    }

    public function getInfo(): string {
        return '[Request] requestURI:'.$this->requestURI.' Action: '.$this->action;
    }

}
