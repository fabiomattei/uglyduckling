<?php

namespace Firststep\Common\Router;

use Firststep\Controllers\Office\Document\Inbox;
use Firststep\Controllers\Office\Manager\Gate;
use Firststep\Controllers\Office\Manager\EntityTable;
use Firststep\Controllers\Community\Login;
use Firststep\Controllers\Admin\Dashboard\AdminDashboard;
use Firststep\Controllers\Admin\Entity\EntityList;
use Firststep\Controllers\Admin\Entity\EntityView;
use Firststep\Controllers\Admin\Entity\EntityCreateTable;
use Firststep\Controllers\Admin\Entity\EntityDropTable;
use Firststep\Controllers\Admin\Table\TableList;
use Firststep\Controllers\Admin\Report\ReportList;

class Router {
	
	const ROUTE_OFFICE_INBOX              = 'officeinbox';
	const ROUTE_OFFICE_GATE               = 'officegate';
	const ROUTE_OFFICE_ENTITY_TABLE       = 'officeentitytable';
	const ROUTE_COMMUNITY_LOGIN           = 'communitylogin';
	const ROUTE_ADMIN_DASHBOARD           = 'admindashboard';
	const ROUTE_ADMIN_ENTITY_LIST         = 'adminentitylist';
	const ROUTE_ADMIN_ENTITY_VIEW         = 'adminentityview';
	const ROUTE_ADMIN_ENTITY_CREATE_TABLE = 'adminentitycreatetable';
	const ROUTE_ADMIN_ENTITY_DROP_TABLE   = 'adminentitydroptable';
	const ROUTE_ADMIN_TABLE_LIST          = 'admintablelist';
	const ROUTE_ADMIN_REPORT_LIST         = 'adminreportlist';
	
	public function __construct( $basepath ) {
		$this->basepath = $basepath;
	}

    function getController( string $action ) {
        switch ( $action ) {
            case self::ROUTE_OFFICE_INBOX:              $controller = new Inbox; break;
			case self::ROUTE_OFFICE_GATE:               $controller = new Gate; break;
			case self::ROUTE_OFFICE_ENTITY_TABLE:       $controller = new EntityTable; break;
			case self::ROUTE_COMMUNITY_LOGIN:           $controller = new Login; break;
			case self::ROUTE_ADMIN_DASHBOARD:           $controller = new AdminDashboard; break;
			case self::ROUTE_ADMIN_ENTITY_LIST:         $controller = new EntityList; break;
			case self::ROUTE_ADMIN_ENTITY_VIEW:         $controller = new EntityView; break;
			case self::ROUTE_ADMIN_ENTITY_CREATE_TABLE: $controller = new EntityCreateTable; break;
			case self::ROUTE_ADMIN_ENTITY_DROP_TABLE:   $controller = new EntityDropTable; break;
			case self::ROUTE_ADMIN_TABLE_LIST:          $controller = new TableList; break;
			case self::ROUTE_ADMIN_REPORT_LIST:         $controller = new ReportList; break;
			
            default: $controller = new Login; break;
        }
        return $controller;
    }
	
	/**
	 * It creates a URL appending the content of variable $_SESSION['office'] to BASEPATH
	 *
	 * Result is: BASEPATH . $_SESSION['office'] . $final_part
	 *
	 * @param        string     Action
	 * @param        string     Parameters: string containing all parameters separated by '/'
	 * @param        string     Extension:  .html by default
	 *
	 * @return       string     The url well formed
	 */
	function make_url( $action = '', $parameters = '', $extension = '.html' ) {
		if ( $action == '' ) {
			return $this->basepath;
		} else {
	        return $this->basepath.$action.$extension.( $parameters == '' ? '' : '?'.$parameters );
	    }
	}

	public function getInfo() : string {
		return '[Router] BasePath: '.$this->basepath;
	}

}
