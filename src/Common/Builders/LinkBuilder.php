<?php

/**
 * User: Fabio Mattei
 * Date: 13/07/18
 * Time: 18.15
 */

namespace Firststep\Common\Builders;

use Firststep\Common\Router\Router;
use Firststep\Common\Blocks\Button;

class LinkBuilder {

    static function get( $router, $lable, $action, $resource, $parameters, $entity ) {
        $url_parameters = 'res='.$resource.'&';
        foreach ($parameters as $par) {
            $url_parameters .= $par->name.'='.$entity->{$par->value}.'&';
        }
        $url_parameters = rtrim($url_parameters, '&');
        switch ( $action ) {
            case 'entitytable':
                return Button::get($router->make_url( Router::ROUTE_OFFICE_ENTITY_TABLE, $url_parameters ), $lable, Button::COLOR_GRAY.' '.Button::SMALL); 
                break;
            case 'entityform':
                return Button::get($router->make_url( Router::ROUTE_OFFICE_ENTITY_FORM, $url_parameters ), $lable, Button::COLOR_GRAY.' '.Button::SMALL);
                break;
            case 'entityinfo':
                return Button::get($router->make_url( Router::ROUTE_OFFICE_ENTITY_INFO, $url_parameters ), $lable, Button::COLOR_GRAY.' '.Button::SMALL);
                break;
            case 'entitysearch':
                return Button::get($router->make_url( Router::ROUTE_OFFICE_ENTITY_SEARCH, $url_parameters ), $lable, Button::COLOR_GRAY.' '.Button::SMALL);
                break;
            case 'entityexport':
                return Button::get($router->make_url( Router::ROUTE_OFFICE_ENTITY_EXPORT, $url_parameters ), $lable, Button::COLOR_GRAY.' '.Button::SMALL);
                break;
            case 'entitylogic':
                return Button::get($router->make_url( Router::ROUTE_OFFICE_ENTITY_LOGIC, $url_parameters ), $lable, Button::COLOR_GRAY.' '.Button::SMALL);
                break;
            
            default:
                return '#';
                break;
        }
    }    

}
