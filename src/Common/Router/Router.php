<?php

namespace Firststep\Common\Router;

class Router {

    function getController( string $argument ) {
        switch ( $argument ) {
            case 'officeinbox':
                return new Firststep\Controllers\Office\Inbox;
            
            default:
                return new Firststep\Controllers\Community\Index;
        }
    }

}
