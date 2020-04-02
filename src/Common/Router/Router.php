<?php

/**
 * Created Fabio Mattei
 * Date: 2019-10-13
 * Time: 17:12
 */

namespace Fabiom\UglyDuckling\Common\Router;

use Fabiom\UglyDuckling\Controllers\Office\Manager\EntityDashboard;
use Fabiom\UglyDuckling\Controllers\Office\Manager\EntityChart;
use Fabiom\UglyDuckling\Controllers\Office\Manager\EntityTable;
use Fabiom\UglyDuckling\Controllers\Office\Manager\EntityForm;
use Fabiom\UglyDuckling\Controllers\Office\Manager\EntityInfo;
use Fabiom\UglyDuckling\Controllers\Office\Manager\EntitySearch;
use Fabiom\UglyDuckling\Controllers\Office\Manager\EntityExport;
use Fabiom\UglyDuckling\Controllers\Office\Manager\EntityTransaction;
use Fabiom\UglyDuckling\Controllers\Community\Login;
use Fabiom\UglyDuckling\Controllers\Admin\Dashboard\AdminDashboard;
use Fabiom\UglyDuckling\Controllers\Admin\Dashboard\AdminMetricsDashboard;
use Fabiom\UglyDuckling\Controllers\Admin\Entity\EntityList;
use Fabiom\UglyDuckling\Controllers\Admin\Entity\EntityView;
use Fabiom\UglyDuckling\Controllers\Admin\Entity\EntityCreateTable;
use Fabiom\UglyDuckling\Controllers\Admin\Entity\EntityDropTable;
use Fabiom\UglyDuckling\Controllers\Admin\Forms\AdminFormsList;
use Fabiom\UglyDuckling\Controllers\Admin\Forms\AdminFormView;
use Fabiom\UglyDuckling\Controllers\Admin\Table\TableList;
use Fabiom\UglyDuckling\Controllers\Admin\Table\AdminTableView;
use Fabiom\UglyDuckling\Controllers\Admin\Export\AdminExportList;
use Fabiom\UglyDuckling\Controllers\Admin\Export\AdminExportView;
use Fabiom\UglyDuckling\Controllers\Admin\Group\AdminGroupAddUser;
use Fabiom\UglyDuckling\Controllers\Admin\Group\AdminGroupDoc;
use Fabiom\UglyDuckling\Controllers\Admin\Group\AdminGroupList;
use Fabiom\UglyDuckling\Controllers\Admin\Group\AdminGroupRemoveUser;
use Fabiom\UglyDuckling\Controllers\Admin\Group\AdminGroupView;
use Fabiom\UglyDuckling\Controllers\Admin\Info\AdminInfoList;
use Fabiom\UglyDuckling\Controllers\Admin\Info\AdminInfoView;
use Fabiom\UglyDuckling\Controllers\Admin\Transactions\AdminTransactionsList;
use Fabiom\UglyDuckling\Controllers\Admin\Transactions\AdminTransactionView;
use Fabiom\UglyDuckling\Controllers\Admin\Search\AdminSearchList;
use Fabiom\UglyDuckling\Controllers\Admin\Search\AdminSearchView;
use Fabiom\UglyDuckling\Controllers\Admin\User\UserDelete;
use Fabiom\UglyDuckling\Controllers\Admin\User\UserEdit;
use Fabiom\UglyDuckling\Controllers\Admin\User\UserEditPassword;
use Fabiom\UglyDuckling\Controllers\Admin\User\UserList;
use Fabiom\UglyDuckling\Controllers\Admin\User\UserNew;
use Fabiom\UglyDuckling\Controllers\Admin\User\UserView;

class Router extends RouterBase {

    const ROUTE_OFFICE_ENTITY_CHART         = 'officeentitychart';
	const ROUTE_OFFICE_ENTITY_TABLE         = 'officeentitytable';
	const ROUTE_OFFICE_ENTITY_FORM          = 'officeentityform';
	const ROUTE_OFFICE_ENTITY_INFO          = 'officeentityinfo';
	const ROUTE_OFFICE_ENTITY_SEARCH        = 'officeentitysearch';
	const ROUTE_OFFICE_ENTITY_EXPORT        = 'officeentityexport';
	const ROUTE_OFFICE_ENTITY_LOGIC         = 'officeentitytransaction';
    const ROUTE_OFFICE_ENTITY_DASHBOARD     = 'officeentitydashboard';
    const ROUTE_OFFICE_DOCUMENT_ACCEPTEDBOX = 'documentacceptedbox';
    const ROUTE_OFFICE_DOCUMENT_REJECTEDBOX = 'documentrejectedbox';
	const ROUTE_COMMUNITY_LOGIN             = 'communitylogin';
	const ROUTE_ADMIN_DASHBOARD             = 'admindashboard';
	const ROUTE_ADMIN_METRICS_DASHBOARD     = 'adminmetricsdashboard';
	const ROUTE_ADMIN_ENTITY_LIST           = 'adminentitylist';
	const ROUTE_ADMIN_ENTITY_VIEW           = 'adminentityview';
	const ROUTE_ADMIN_ENTITY_CREATE_TABLE   = 'adminentitycreatetable';
	const ROUTE_ADMIN_ENTITY_DROP_TABLE     = 'adminentitydroptable';
    const ROUTE_ADMIN_FORM_LIST             = 'adminformslist';
    const ROUTE_ADMIN_FORM_VIEW             = 'adminformview';
	const ROUTE_ADMIN_DOCUMENT_LIST         = 'admindocumentlist';
	const ROUTE_ADMIN_TABLE_LIST            = 'admintablelist';
    const ROUTE_ADMIN_TABLE_VIEW            = 'admintableview';
	const ROUTE_ADMIN_EXPORT_LIST           = 'adminexportlist';
    const ROUTE_ADMIN_EXPORT_VIEW           = 'adminexportview';
    const ROUTE_ADMIN_GROUP_ADD_USER        = 'admingroupadduser';
    const ROUTE_ADMIN_GROUP_DOC             = 'admingroupdoc';
	const ROUTE_ADMIN_GROUP_LIST            = 'admingrouplist';
    const ROUTE_ADMIN_GROUP_REMOVE_USER     = 'admingroupremoveuser';
    const ROUTE_ADMIN_GROUP_VIEW            = 'admingroupview';
    const ROUTE_ADMIN_INFO_LIST             = 'admininfolist';
    const ROUTE_ADMIN_INFO_VIEW             = 'admininfoview';
    const ROUTE_ADMIN_TRANSACTION_LIST      = 'admintransactionlist';
    const ROUTE_ADMIN_TRANSACTION_VIEW      = 'admintransactionview';
    const ROUTE_ADMIN_SEARCH_LIST           = 'adminsearchlist';
    const ROUTE_ADMIN_SEARCH_VIEW           = 'adminsearchview';
    const ROUTE_ADMIN_USER_DELETE           = 'adminuserdelete';
    const ROUTE_ADMIN_USER_EDIT             = 'adminuseredit';
    const ROUTE_ADMIN_USER_EDIT_PASSWORD    = 'adminusereditpassword';
    const ROUTE_ADMIN_USER_LIST             = 'adminuserlist';
    const ROUTE_ADMIN_USER_NEW              = 'adminusernew';
    const ROUTE_ADMIN_USER_VIEW             = 'adminuserview';

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
            self::ROUTE_COMMUNITY_LOGIN,
            self::ROUTE_ADMIN_DASHBOARD,
            self::ROUTE_ADMIN_METRICS_DASHBOARD,
            self::ROUTE_ADMIN_ENTITY_LIST,
            self::ROUTE_ADMIN_ENTITY_VIEW,
            self::ROUTE_ADMIN_ENTITY_CREATE_TABLE,
            self::ROUTE_ADMIN_ENTITY_DROP_TABLE,
            self::ROUTE_ADMIN_FORM_LIST,
            self::ROUTE_ADMIN_FORM_VIEW,
            self::ROUTE_ADMIN_TABLE_LIST,
            self::ROUTE_ADMIN_TABLE_VIEW,
            self::ROUTE_ADMIN_EXPORT_LIST,
            self::ROUTE_ADMIN_EXPORT_VIEW,
            self::ROUTE_ADMIN_GROUP_ADD_USER,
            self::ROUTE_ADMIN_GROUP_DOC,
            self::ROUTE_ADMIN_GROUP_LIST,
            self::ROUTE_ADMIN_GROUP_REMOVE_USER,
            self::ROUTE_ADMIN_GROUP_VIEW,
            self::ROUTE_ADMIN_INFO_LIST,
            self::ROUTE_ADMIN_INFO_VIEW,
            self::ROUTE_ADMIN_TRANSACTION_LIST,
            self::ROUTE_ADMIN_TRANSACTION_VIEW,
            self::ROUTE_ADMIN_SEARCH_LIST,
            self::ROUTE_ADMIN_SEARCH_VIEW,
            self::ROUTE_ADMIN_USER_DELETE,
            self::ROUTE_ADMIN_USER_EDIT,
            self::ROUTE_ADMIN_USER_EDIT_PASSWORD,
            self::ROUTE_ADMIN_USER_LIST,
            self::ROUTE_ADMIN_USER_NEW,
            self::ROUTE_ADMIN_USER_VIEW
        ));
    }

    function getController( string $action ) {
        switch ( $action ) {
            case self::ROUTE_OFFICE_ENTITY_CHART:         $controller = new EntityChart; break;
			case self::ROUTE_OFFICE_ENTITY_TABLE:         $controller = new EntityTable; break;
			case self::ROUTE_OFFICE_ENTITY_FORM:          $controller = new EntityForm; break;
			case self::ROUTE_OFFICE_ENTITY_INFO:          $controller = new EntityInfo; break;
			case self::ROUTE_OFFICE_ENTITY_SEARCH:        $controller = new EntitySearch; break;
			case self::ROUTE_OFFICE_ENTITY_EXPORT:        $controller = new EntityExport; break;
			case self::ROUTE_OFFICE_ENTITY_LOGIC:         $controller = new EntityTransaction; break;
            case self::ROUTE_OFFICE_ENTITY_DASHBOARD:     $controller = new EntityDashboard; break;
			case self::ROUTE_COMMUNITY_LOGIN:             $controller = new Login; break;
			case self::ROUTE_ADMIN_DASHBOARD:             $controller = new AdminDashboard; break;
			case self::ROUTE_ADMIN_METRICS_DASHBOARD:     $controller = new AdminMetricsDashboard; break;
			case self::ROUTE_ADMIN_ENTITY_LIST:           $controller = new EntityList; break;
			case self::ROUTE_ADMIN_ENTITY_VIEW:           $controller = new EntityView; break;
			case self::ROUTE_ADMIN_ENTITY_CREATE_TABLE:   $controller = new EntityCreateTable; break;
			case self::ROUTE_ADMIN_ENTITY_DROP_TABLE:     $controller = new EntityDropTable; break;
            case self::ROUTE_ADMIN_FORM_LIST:             $controller = new AdminFormsList; break;
            case self::ROUTE_ADMIN_FORM_VIEW:             $controller = new AdminFormView; break;
			case self::ROUTE_ADMIN_TABLE_LIST:            $controller = new TableList; break;
            case self::ROUTE_ADMIN_TABLE_VIEW:            $controller = new AdminTableView(); break;
			case self::ROUTE_ADMIN_EXPORT_LIST:           $controller = new AdminExportList; break;
            case self::ROUTE_ADMIN_EXPORT_VIEW:           $controller = new AdminExportView(); break;
            case self::ROUTE_ADMIN_GROUP_ADD_USER:        $controller = new AdminGroupAddUser(); break;
            case self::ROUTE_ADMIN_GROUP_DOC:             $controller = new AdminGroupDoc(); break;
            case self::ROUTE_ADMIN_GROUP_LIST:            $controller = new AdminGroupList; break;
            case self::ROUTE_ADMIN_GROUP_REMOVE_USER:     $controller = new AdminGroupRemoveUser(); break;
            case self::ROUTE_ADMIN_GROUP_VIEW:            $controller = new AdminGroupView; break;
            case self::ROUTE_ADMIN_INFO_LIST:             $controller = new AdminInfoList; break;
            case self::ROUTE_ADMIN_INFO_VIEW:             $controller = new AdminInfoView; break;
            case self::ROUTE_ADMIN_TRANSACTION_LIST:      $controller = new AdminTransactionsList; break;
            case self::ROUTE_ADMIN_TRANSACTION_VIEW:      $controller = new AdminTransactionView; break;
            case self::ROUTE_ADMIN_SEARCH_LIST:           $controller = new AdminSearchList; break;
            case self::ROUTE_ADMIN_SEARCH_VIEW:           $controller = new AdminSearchView; break;
            case self::ROUTE_ADMIN_USER_DELETE:           $controller = new UserDelete; break;
            case self::ROUTE_ADMIN_USER_EDIT:             $controller = new UserEdit; break;
            case self::ROUTE_ADMIN_USER_EDIT_PASSWORD:    $controller = new UserEditPassword; break;
            case self::ROUTE_ADMIN_USER_LIST:             $controller = new UserList; break;
            case self::ROUTE_ADMIN_USER_NEW:              $controller = new UserNew; break;
            case self::ROUTE_ADMIN_USER_VIEW:             $controller = new UserView; break;

            default: $controller = new Login; break;
        }
        return $controller;
    }

}
