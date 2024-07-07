<?php

namespace Fabiom\UglyDuckling\Common\Request;

use Fabiom\UglyDuckling\Common\Utils\StringUtils;

class Request {

    private /* string */ $requestURI = '';
    private /* string */ $action = '';

    function __construct() {
    }

    public function setServerRequestURI( string $requestURI, string $pathToApp = '/' ) {
        $this->requestURI = $requestURI;
        $request2 = str_replace( ['.html', '.pdf', '.svg'], '',$requestURI );
        $request  = preg_replace( '/\?.*/', '', $request2 );

        if ( $request == '' ) throw new \Exception('General malfuction!!!');

        # removing the first '/' from the path
        $this->action = substr( $request, strlen($pathToApp) ); // explode( '/', $request );

        if (!StringUtils::validate_string( $this->action )) {
            throw new \Exception('Illegal access to calculateSplittedURL!!! Unvalidated action: "' . $this->action . '" over request: ' . $this->requestURI);
        }
    }

    public function getAction() {
        return $this->action;
    }

    public function getInfo(): string {
        return '[Request] requestURI:'.$this->requestURI.' Action: '.$this->action;
    }

}
