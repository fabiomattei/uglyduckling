<?php

/**
 * Created Fabio Mattei
 * Date: 2020-06-07
 * Time: 10:42
 */

namespace Fabiom\UglyDuckling\Common\Router;

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
     * @param string $action
     */
    function isActionSupported( string $action ) {
        return in_array($action, array(
            self::ROUTE_OFFICE_ENTITY_CHART,
            self::ROUTE_OFFICE_ENTITY_TABLE,
            self::ROUTE_OFFICE_ENTITY_FORM,
            self::ROUTE_OFFICE_ENTITY_INFO,
            self::ROUTE_OFFICE_ENTITY_SEARCH,
            self::ROUTE_OFFICE_ENTITY_EXPORT,
            self::ROUTE_OFFICE_ENTITY_LOGIC,
            self::ROUTE_OFFICE_ENTITY_DASHBOARD,
            self::ROUTE_OFFICE_ENTITY_NO_TEMPLATE
        ));
    }

    function getController( string $action ) {
        if ( $action === self::ROUTE_OFFICE_ENTITY_LOGIC ) {
            return new JsonTransactionController;
        }
        if ( $action === self::ROUTE_OFFICE_ENTITY_EXPORT ) {
            return new JsonExportController();
        }
        return new JsonDashboardController;
    }

}
