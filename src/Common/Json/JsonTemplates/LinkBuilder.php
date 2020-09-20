<?php

/**
 * User: Fabio Mattei
 * Date: 13/07/18
 * Time: 18.15
 */

namespace Fabiom\UglyDuckling\Common\Json\JsonTemplates;

use Fabiom\UglyDuckling\Common\Router\ResourceRouter;

class LinkBuilder {

    /**
     * @deprecated
     * used only in the menu
     *
     * used only for menu generation
     */
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
