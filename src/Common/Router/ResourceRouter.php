<?php

/**
 * Created Fabio Mattei
 * Date: 2020-06-07
 * Time: 10:42
 */

namespace Fabiom\UglyDuckling\Common\Router;

use Fabiom\UglyDuckling\Common\Controllers\JsonResourceJInPlaceBasicController;
use Fabiom\UglyDuckling\Controllers\JsonResource\JsonAjax;
use Fabiom\UglyDuckling\Controllers\JsonResource\JsonDashboardController;
use Fabiom\UglyDuckling\Controllers\JsonResource\JsonExportController;
use Fabiom\UglyDuckling\Controllers\JsonResource\JsonTransactionController;

class ResourceRouter extends RouterBase {

    const ROUTE_OFFICE_ENTITY_CHART         = 'officeentitychart';
	const ROUTE_OFFICE_ENTITY_TABLE         = 'officeentitytable';
	const ROUTE_OFFICE_ENTITY_FORM          = 'officeentityform';
	const ROUTE_OFFICE_ENTITY_INFO          = 'officeentityinfo';
	const ROUTE_OFFICE_ENTITY_SEARCH        = 'officeentitysearch';
	const ROUTE_OFFICE_ENTITY_EXPORT        = 'officeentityexport';
	const ROUTE_OFFICE_ENTITY_LOGIC         = 'officeentitytransaction';
    const ROUTE_OFFICE_ENTITY_DASHBOARD     = 'officeentitydashboard';
    const ROUTE_OFFICE_ENTITY_NO_TEMPLATE   = 'officeentitynotemplate';

    /**
     * Overwrite this function
     *
     * @param string $controller
     */
    function isActionSupported( string $controllerSlug ) {
        return in_array($controllerSlug, array(
            self::ROUTE_OFFICE_ENTITY_CHART,
            self::ROUTE_OFFICE_ENTITY_TABLE,
            self::ROUTE_OFFICE_ENTITY_FORM,
            self::ROUTE_OFFICE_ENTITY_INFO,
            self::ROUTE_OFFICE_ENTITY_SEARCH,
            self::ROUTE_OFFICE_ENTITY_EXPORT,
            self::ROUTE_OFFICE_ENTITY_LOGIC,
            self::ROUTE_OFFICE_ENTITY_DASHBOARD,
            self::ROUTE_OFFICE_ENTITY_NO_TEMPLATE,
            JsonAjax::CONTROLLER_NAME
        ));
    }

    function getController( string $controllerSlug ) {
        if ( $controllerSlug === self::ROUTE_OFFICE_ENTITY_LOGIC ) {
            return new JsonTransactionController;
        }
        if ( $controllerSlug === self::ROUTE_OFFICE_ENTITY_EXPORT ) {
            return new JsonExportController;
        }
        if ( $controllerSlug === JsonAjax::CONTROLLER_NAME ) {
            return new JsonAjax;
        }
        if ( $controllerSlug === JsonResourceJInPlaceBasicController::CONTROLLER_NAME ) {
            return new JsonResourceJInPlaceBasicController;
        }
        return new JsonDashboardController;
    }

}
