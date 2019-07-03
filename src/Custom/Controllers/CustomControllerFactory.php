<?php

namespace Firststep\Custom\Controllers;

class CustomControllerFactory {

    function getController( string $action ) {
        switch ( $action ) {
            case CustomControllerExample::CONTROLLER_NAME: $controller = new CustomControllerExample; break;
        }
        return $controller;
    }

}
