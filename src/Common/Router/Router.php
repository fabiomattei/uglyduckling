<?php

namespace Firststep\Common\Router;

class Router {

	function __construct() {}

    function getController( string $argument ) {
        switch ( $argument ) {
            case 'inbox':
                return new Firststep\Controllers\Office\Inbox;
            
            default:
                return new Firststep\Controllers\Open\Index;
        }
    }


}
