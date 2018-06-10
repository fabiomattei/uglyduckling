<?php

namespace Firststep\Common\Router;

use Firststep\Controllers\Office\Inbox;
use Firststep\Controllers\Community\Index;

class Router {

    function getController( string $argument ) {
        switch ( $argument ) {
            case 'officeinbox':
                return new Inbox;
            
            default:
                return new Index;
        }
    }

}
