<?php

/**
 * Created Fabio Mattei
 * Date: 2020-06-07
 * Time: 10:42
 */

namespace Fabiom\UglyDuckling\Common\Router;

use Fabiom\UglyDuckling\Controllers\JsonResource\JsonDashboardController;
use Fabiom\UglyDuckling\Controllers\JsonResource\JsonChartController;
use Fabiom\UglyDuckling\Controllers\JsonResource\JsonTableController;
use Fabiom\UglyDuckling\Controllers\JsonResource\JsonFormController;
use Fabiom\UglyDuckling\Controllers\JsonResource\JsonInfoController;
use Fabiom\UglyDuckling\Controllers\JsonResource\JsonSearchController;
use Fabiom\UglyDuckling\Controllers\JsonResource\JsonExportController;
use Fabiom\UglyDuckling\Controllers\JsonResource\JsonTransactionController;
use Fabiom\UglyDuckling\Controllers\JsonResource\JsonNoHtmlTemplateController;

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
    const ROUTE_OFFICE_DOCUMENT_ACCEPTEDBOX = 'documentacceptedbox';
    const ROUTE_OFFICE_DOCUMENT_REJECTEDBOX = 'documentrejectedbox';

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
        switch ( $action ) {
            case self::ROUTE_OFFICE_ENTITY_CHART:         $controller = new JsonChartController; break;
			case self::ROUTE_OFFICE_ENTITY_TABLE:         $controller = new JsonTableController; break;
			case self::ROUTE_OFFICE_ENTITY_FORM:          $controller = new JsonFormController; break;
			case self::ROUTE_OFFICE_ENTITY_INFO:          $controller = new JsonInfoController; break;
			case self::ROUTE_OFFICE_ENTITY_SEARCH:        $controller = new JsonSearchController; break;
			case self::ROUTE_OFFICE_ENTITY_EXPORT:        $controller = new JsonExportController; break;
			case self::ROUTE_OFFICE_ENTITY_LOGIC:         $controller = new JsonTransactionController; break;
            case self::ROUTE_OFFICE_ENTITY_DASHBOARD:     $controller = new JsonDashboardController; break;
            case self::ROUTE_OFFICE_ENTITY_NO_TEMPLATE:   $controller = new JsonNoHtmlTemplateController; break;
        }
        return $controller;
    }

}
