<?php

/**
 * User: Fabio Mattei
 * Date: 13/07/18
 * Time: 18.15
 */

namespace Fabiom\UglyDuckling\Common\Json\JsonTemplates;

use Fabiom\UglyDuckling\Common\Router\ResourceRouter;
use Fabiom\UglyDuckling\Common\Blocks\Button;

class LinkBuilder {

    /**
     * @param $buttonBuilder
     * @param $jsonresource
     * @param $jsonloader
     * @param $routerContainer
     * @param $entity
     * @return mixed
     */
    function getAppButton($buttonBuilder, $jsonresource, $jsonloader, $routerContainer, $entity ) {
        $resource = $jsonresource->resource;
        $parameters = $jsonresource->parameters;
        $label = $jsonresource->label;
        $url_parameters = 'res='.$resource.'&';
        foreach ($parameters as $par) {
            $url_parameters .= $par->name.'='.$entity->{$par->sqlfield}.'&';
        }
        $url_parameters = rtrim($url_parameters, '&');

        $action = $jsonloader->getActionRelatedToResource($resource);
        switch ( $action ) {
            case 'entitydashboard':
                return $buttonBuilder::get($jsonresource, $routerContainer->makeRelativeUrl( ResourceRouter::ROUTE_OFFICE_ENTITY_DASHBOARD, $url_parameters ));
                break;
            case 'entitychart':
                return $buttonBuilder::get($jsonresource, $routerContainer->makeRelativeUrl( ResourceRouter::ROUTE_OFFICE_ENTITY_CHART, $url_parameters ));
                break;
            case 'entitytable':
                return $buttonBuilder::get($jsonresource, $routerContainer->makeRelativeUrl( ResourceRouter::ROUTE_OFFICE_ENTITY_TABLE, $url_parameters ));
                break;
            case 'entityform':
                return $buttonBuilder::get($jsonresource, $routerContainer->makeRelativeUrl( ResourceRouter::ROUTE_OFFICE_ENTITY_FORM, $url_parameters ));
                break;
            case 'entityinfo':
                return $buttonBuilder::get($jsonresource, $routerContainer->makeRelativeUrl( ResourceRouter::ROUTE_OFFICE_ENTITY_INFO, $url_parameters ));
                break;
            case 'entitysearch':
                return $buttonBuilder::get($jsonresource, $routerContainer->makeRelativeUrl( ResourceRouter::ROUTE_OFFICE_ENTITY_SEARCH, $url_parameters ));
                break;
            case 'entityexport':
                return $buttonBuilder::get($jsonresource, $routerContainer->makeRelativeUrl( ResourceRouter::ROUTE_OFFICE_ENTITY_EXPORT, $url_parameters ));
                break;
            case 'entitytransaction':
                return $buttonBuilder::get($jsonresource, $routerContainer->makeRelativeUrl( ResourceRouter::ROUTE_OFFICE_ENTITY_LOGIC, $url_parameters ));
                break;

            default:
                return $buttonBuilder::get($jsonresource, $routerContainer->makeRelativeUrl( ResourceRouter::ROUTE_OFFICE_ENTITY_DASHBOARD, $url_parameters ));
                break;
        }
    }

    /**
     * @deprecated
     *
     * @param $jsonloader
     * @param $routerContainer
     * @param $label
     * @param $resource
     * @param $parameters
     * @param $entity
     * @return string
     */
    function getButton($jsonloader, $routerContainer, $label, $resource, $parameters, $entity ) {
        $url_parameters = 'res='.$resource.'&';
        foreach ($parameters as $par) {
            $url_parameters .= $par->name.'='.$entity->{$par->sqlfield}.'&';
        }
        $url_parameters = rtrim($url_parameters, '&');

        $action = $jsonloader->getActionRelatedToResource($resource);
        switch ( $action ) {
            case 'entitydashboard':
                return Button::get($routerContainer->makeRelativeUrl( ResourceRouter::ROUTE_OFFICE_ENTITY_DASHBOARD, $url_parameters ), $label, Button::COLOR_GRAY.' '.Button::SMALL);
                break;
            case 'entitychart':
                return Button::get($routerContainer->makeRelativeUrl( ResourceRouter::ROUTE_OFFICE_ENTITY_CHART, $url_parameters ), $label, Button::COLOR_GRAY.' '.Button::SMALL);
                break;
            case 'entitytable':
                return Button::get($routerContainer->makeRelativeUrl( ResourceRouter::ROUTE_OFFICE_ENTITY_TABLE, $url_parameters ), $label, Button::COLOR_GRAY.' '.Button::SMALL);
                break;
            case 'entityform':
                return Button::get($routerContainer->makeRelativeUrl( ResourceRouter::ROUTE_OFFICE_ENTITY_FORM, $url_parameters ), $label, Button::COLOR_GRAY.' '.Button::SMALL);
                break;
            case 'entityinfo':
                return Button::get($routerContainer->makeRelativeUrl( ResourceRouter::ROUTE_OFFICE_ENTITY_INFO, $url_parameters ), $label, Button::COLOR_GRAY.' '.Button::SMALL);
                break;
            case 'entitysearch':
                return Button::get($routerContainer->makeRelativeUrl( ResourceRouter::ROUTE_OFFICE_ENTITY_SEARCH, $url_parameters ), $label, Button::COLOR_GRAY.' '.Button::SMALL);
                break;
            case 'entityexport':
                return Button::get($routerContainer->makeRelativeUrl( ResourceRouter::ROUTE_OFFICE_ENTITY_EXPORT, $url_parameters ), $label, Button::COLOR_GRAY.' '.Button::SMALL);
                break;
            case 'entitytransaction':
                return Button::get($routerContainer->makeRelativeUrl( ResourceRouter::ROUTE_OFFICE_ENTITY_LOGIC, $url_parameters ), $label, Button::COLOR_GRAY.' '.Button::SMALL);
                break;

            default:
                return Button::get($routerContainer->makeRelativeUrl( ResourceRouter::ROUTE_OFFICE_ENTITY_DASHBOARD, $url_parameters ), $label, Button::COLOR_GRAY.' '.Button::SMALL);
                break;
        }
    }

    function getUrlForLink( $jsonloader, $routerContainer, $resource, $parameters, $entity ) {
        $url_parameters = 'res='.$resource.'&';
        foreach ($parameters as $par) {
            $url_parameters .= $par->name.'='.$entity->{$par->sqlfield}.'&';
        }
        $url_parameters = rtrim($url_parameters, '&');

        $action = $jsonloader->getActionRelatedToResource($resource);
        switch ( $action ) {
            case 'entitydashboard':
                return $routerContainer->makeRelativeUrl( ResourceRouter::ROUTE_OFFICE_ENTITY_DASHBOARD, $url_parameters );
                break;
            case 'entitychart':
                return $routerContainer->makeRelativeUrl( ResourceRouter::ROUTE_OFFICE_ENTITY_CHART, $url_parameters );
                break;
            case 'entitytable':
                return $routerContainer->makeRelativeUrl( ResourceRouter::ROUTE_OFFICE_ENTITY_TABLE, $url_parameters );
                break;
            case 'entityform':
                return $routerContainer->makeRelativeUrl( ResourceRouter::ROUTE_OFFICE_ENTITY_FORM, $url_parameters );
                break;
            case 'entityinfo':
                return $routerContainer->makeRelativeUrl( ResourceRouter::ROUTE_OFFICE_ENTITY_INFO, $url_parameters );
                break;
            case 'entitysearch':
                return $routerContainer->makeRelativeUrl( ResourceRouter::ROUTE_OFFICE_ENTITY_SEARCH, $url_parameters );
                break;
            case 'entityexport':
                return $routerContainer->makeRelativeUrl( ResourceRouter::ROUTE_OFFICE_ENTITY_EXPORT, $url_parameters );
                break;
            case 'entitytransaction':
                return $routerContainer->makeRelativeUrl( ResourceRouter::ROUTE_OFFICE_ENTITY_LOGIC, $url_parameters );
                break;

            default:
                return $routerContainer->makeRelativeUrl( ResourceRouter::ROUTE_OFFICE_ENTITY_DASHBOARD, $url_parameters );
                break;
        }
    }

    /**
     * @deprecated
     */
    static function get( $jsonloader, $routerContainer, $label, $resource, $parameters, $entity ) {
        $url_parameters = 'res='.$resource.'&';
        foreach ($parameters as $par) {
            $url_parameters .= $par->name.'='.$entity->{$par->sqlfield}.'&';
        }
        $url_parameters = rtrim($url_parameters, '&');

        $action = $jsonloader->getActionRelatedToResource($resource);
        switch ( $action ) {
            case 'entitydashboard':
                return Button::get($routerContainer->makeRelativeUrl( ResourceRouter::ROUTE_OFFICE_ENTITY_DASHBOARD, $url_parameters ), $label, Button::COLOR_GRAY.' '.Button::SMALL);
                break;
            case 'entitychart':
                return Button::get($routerContainer->makeRelativeUrl( ResourceRouter::ROUTE_OFFICE_ENTITY_CHART, $url_parameters ), $label, Button::COLOR_GRAY.' '.Button::SMALL);
                break;
            case 'entitytable':
                return Button::get($routerContainer->makeRelativeUrl( ResourceRouter::ROUTE_OFFICE_ENTITY_TABLE, $url_parameters ), $label, Button::COLOR_GRAY.' '.Button::SMALL);
                break;
            case 'entityform':
                return Button::get($routerContainer->makeRelativeUrl( ResourceRouter::ROUTE_OFFICE_ENTITY_FORM, $url_parameters ), $label, Button::COLOR_GRAY.' '.Button::SMALL);
                break;
            case 'entityinfo':
                return Button::get($routerContainer->makeRelativeUrl( ResourceRouter::ROUTE_OFFICE_ENTITY_INFO, $url_parameters ), $label, Button::COLOR_GRAY.' '.Button::SMALL);
                break;
            case 'entitysearch':
                return Button::get($routerContainer->makeRelativeUrl( ResourceRouter::ROUTE_OFFICE_ENTITY_SEARCH, $url_parameters ), $label, Button::COLOR_GRAY.' '.Button::SMALL);
                break;
            case 'entityexport':
                return Button::get($routerContainer->makeRelativeUrl( ResourceRouter::ROUTE_OFFICE_ENTITY_EXPORT, $url_parameters ), $label, Button::COLOR_GRAY.' '.Button::SMALL);
                break;
            case 'entitytransaction':
                return Button::get($routerContainer->makeRelativeUrl( ResourceRouter::ROUTE_OFFICE_ENTITY_LOGIC, $url_parameters ), $label, Button::COLOR_GRAY.' '.Button::SMALL);
                break;

            default:
                return Button::get($routerContainer->makeRelativeUrl( ResourceRouter::ROUTE_OFFICE_ENTITY_DASHBOARD, $url_parameters ), $label, Button::COLOR_GRAY.' '.Button::SMALL);
                break;
        }
    }

    static function getURL( $routerContainer, $action, $resource ) {
        $url_parameters = 'res='.$resource;
        switch ( $action ) {
            case 'entitydashboard':
                return $routerContainer->makeRelativeUrl( ResourceRouter::ROUTE_OFFICE_ENTITY_DASHBOARD, $url_parameters );
                break;
            case 'entitychart':
                return $routerContainer->makeRelativeUrl( ResourceRouter::ROUTE_OFFICE_ENTITY_CHART, $url_parameters );
                break;
            case 'entitytable':
                return $routerContainer->makeRelativeUrl( ResourceRouter::ROUTE_OFFICE_ENTITY_TABLE, $url_parameters );
                break;
            case 'entityform':
                return $routerContainer->makeRelativeUrl( ResourceRouter::ROUTE_OFFICE_ENTITY_FORM, $url_parameters );
                break;
            case 'entityinfo':
                return $routerContainer->makeRelativeUrl( ResourceRouter::ROUTE_OFFICE_ENTITY_INFO, $url_parameters );
                break;
            case 'entitysearch':
                return $routerContainer->makeRelativeUrl( ResourceRouter::ROUTE_OFFICE_ENTITY_SEARCH, $url_parameters );
                break;
            case 'entityexport':
                return $routerContainer->makeRelativeUrl( ResourceRouter::ROUTE_OFFICE_ENTITY_EXPORT, $url_parameters );
                break;
            case 'entitytransaction':
                return $routerContainer->makeRelativeUrl( ResourceRouter::ROUTE_OFFICE_ENTITY_LOGIC, $url_parameters );
                break;
            
            default:
                return $routerContainer->makeRelativeUrl( ResourceRouter::ROUTE_OFFICE_ENTITY_DASHBOARD, $url_parameters );
                break;
        }
    }    
}
