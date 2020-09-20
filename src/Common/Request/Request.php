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
    }

    public function getAction() {
        return $this->action;
    }

    public function getInfo(): string {
        return '[Request] requestURI:'.$this->requestURI.' Action: '.$this->action;
    }

}
