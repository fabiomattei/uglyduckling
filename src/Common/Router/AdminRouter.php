<?php

/**
 * Created Fabio Mattei
 * Date: 2020-06-07
 * Time: 10:39
 */

namespace Fabiom\UglyDuckling\Common\Router;

use Fabiom\UglyDuckling\Controllers\Admin\Dashboard\AdminDashboard;
use Fabiom\UglyDuckling\Controllers\Admin\Dashboard\AdminMetricsDashboard;
use Fabiom\UglyDuckling\Controllers\Admin\Entity\EntityList;
use Fabiom\UglyDuckling\Controllers\Admin\Entity\EntityView;
use Fabiom\UglyDuckling\Controllers\Admin\Entity\EntityCreateTable;
use Fabiom\UglyDuckling\Controllers\Admin\Entity\EntityDropTable;
use Fabiom\UglyDuckling\Controllers\Admin\Forms\AdminFormsList;
use Fabiom\UglyDuckling\Controllers\Admin\Forms\AdminFormView;
use Fabiom\UglyDuckling\Controllers\Admin\Security\BlockedIpDelete;
use Fabiom\UglyDuckling\Controllers\Admin\Security\DeactivatedUserDelete;
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
use Fabiom\UglyDuckling\Controllers\Admin\Security\BlockedIpList;
use Fabiom\UglyDuckling\Controllers\Admin\Security\DeactivatedUserList;
use Fabiom\UglyDuckling\Controllers\Admin\Security\SecurityLogList;
use Fabiom\UglyDuckling\Controllers\Admin\User\UserDelete;
use Fabiom\UglyDuckling\Controllers\Admin\User\UserEdit;
use Fabiom\UglyDuckling\Controllers\Admin\User\UserEditPassword;
use Fabiom\UglyDuckling\Controllers\Admin\User\UserList;
use Fabiom\UglyDuckling\Controllers\Admin\User\UserNew;
use Fabiom\UglyDuckling\Controllers\Admin\User\UserView;

class AdminRouter extends RouterBase {

    const ROUTE_ADMIN_DASHBOARD                 = 'admindashboard';
    const ROUTE_ADMIN_METRICS_DASHBOARD         = 'adminmetricsdashboard';
    const ROUTE_ADMIN_ENTITY_LIST               = 'adminentitylist';
    const ROUTE_ADMIN_ENTITY_VIEW               = 'adminentityview';
    const ROUTE_ADMIN_ENTITY_CREATE_TABLE       = 'adminentitycreatetable';
    const ROUTE_ADMIN_ENTITY_DROP_TABLE         = 'adminentitydroptable';
    const ROUTE_ADMIN_FORM_LIST                 = 'adminformslist';
    const ROUTE_ADMIN_FORM_VIEW                 = 'adminformview';
    const ROUTE_ADMIN_DOCUMENT_LIST             = 'admindocumentlist';
    const ROUTE_ADMIN_TABLE_LIST                = 'admintablelist';
    const ROUTE_ADMIN_TABLE_VIEW                = 'admintableview';
    const ROUTE_ADMIN_EXPORT_LIST               = 'adminexportlist';
    const ROUTE_ADMIN_EXPORT_VIEW               = 'adminexportview';
    const ROUTE_ADMIN_GROUP_ADD_USER            = 'admingroupadduser';
    const ROUTE_ADMIN_GROUP_DOC                 = 'admingroupdoc';
    const ROUTE_ADMIN_GROUP_LIST                = 'admingrouplist';
    const ROUTE_ADMIN_GROUP_REMOVE_USER         = 'admingroupremoveuser';
    const ROUTE_ADMIN_GROUP_VIEW                = 'admingroupview';
    const ROUTE_ADMIN_INFO_LIST                 = 'admininfolist';
    const ROUTE_ADMIN_INFO_VIEW                 = 'admininfoview';
    const ROUTE_ADMIN_TRANSACTION_LIST          = 'admintransactionlist';
    const ROUTE_ADMIN_TRANSACTION_VIEW          = 'admintransactionview';
    const ROUTE_ADMIN_SEARCH_LIST               = 'adminsearchlist';
    const ROUTE_ADMIN_SEARCH_VIEW               = 'adminsearchview';
    const ROUTE_ADMIN_SECURITY_BLOCKED_IP       = 'adminsecurityblockedip';
    const ROUTE_ADMIN_SECURITY_BLOCKED_IP_DELETE= 'adminsecurityblockedipdelete';
    const ROUTE_ADMIN_SECURITY_DEACTIVATED_USER = 'adminsecuritydeactivateduser';
    const ROUTE_ADMIN_SECURITY_DEACTIVATED_USER_DELETE= 'adminsecuritydeactivateduserdelete';
    const ROUTE_ADMIN_SECURITY_SECURITY_LOG     = 'adminsecuritysecuritylog';
    const ROUTE_ADMIN_USER_DELETE               = 'adminuserdelete';
    const ROUTE_ADMIN_USER_EDIT                 = 'adminuseredit';
    const ROUTE_ADMIN_USER_EDIT_PASSWORD        = 'adminusereditpassword';
    const ROUTE_ADMIN_USER_LIST                 = 'adminuserlist';
    const ROUTE_ADMIN_USER_NEW                  = 'adminusernew';
    const ROUTE_ADMIN_USER_VIEW                 = 'adminuserview';
    
    /**
     * Overwrite this function
     *
     * @param string $action
     */
    function isActionSupported( string $action ) {
        return in_array($action, array(
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
            self::ROUTE_ADMIN_SECURITY_BLOCKED_IP,
            self::ROUTE_ADMIN_SECURITY_BLOCKED_IP_DELETE,
            self::ROUTE_ADMIN_SECURITY_DEACTIVATED_USER,
            self::ROUTE_ADMIN_SECURITY_DEACTIVATED_USER_DELETE,
            self::ROUTE_ADMIN_SECURITY_SECURITY_LOG,
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
            case self::ROUTE_ADMIN_DASHBOARD:                        $controller = new AdminDashboard; break;
            case self::ROUTE_ADMIN_METRICS_DASHBOARD:                $controller = new AdminMetricsDashboard; break;
            case self::ROUTE_ADMIN_ENTITY_LIST:                      $controller = new EntityList; break;
            case self::ROUTE_ADMIN_ENTITY_VIEW:                      $controller = new EntityView; break;
            case self::ROUTE_ADMIN_ENTITY_CREATE_TABLE:              $controller = new EntityCreateTable; break;
            case self::ROUTE_ADMIN_ENTITY_DROP_TABLE:                $controller = new EntityDropTable; break;
            case self::ROUTE_ADMIN_FORM_LIST:                        $controller = new AdminFormsList; break;
            case self::ROUTE_ADMIN_FORM_VIEW:                        $controller = new AdminFormView; break;
            case self::ROUTE_ADMIN_TABLE_LIST:                       $controller = new TableList; break;
            case self::ROUTE_ADMIN_TABLE_VIEW:                       $controller = new AdminTableView(); break;
            case self::ROUTE_ADMIN_EXPORT_LIST:                      $controller = new AdminExportList; break;
            case self::ROUTE_ADMIN_EXPORT_VIEW:                      $controller = new AdminExportView(); break;
            case self::ROUTE_ADMIN_GROUP_ADD_USER:                   $controller = new AdminGroupAddUser(); break;
            case self::ROUTE_ADMIN_GROUP_DOC:                        $controller = new AdminGroupDoc(); break;
            case self::ROUTE_ADMIN_GROUP_LIST:                       $controller = new AdminGroupList; break;
            case self::ROUTE_ADMIN_GROUP_REMOVE_USER:                $controller = new AdminGroupRemoveUser(); break;
            case self::ROUTE_ADMIN_GROUP_VIEW:                       $controller = new AdminGroupView; break;
            case self::ROUTE_ADMIN_INFO_LIST:                        $controller = new AdminInfoList; break;
            case self::ROUTE_ADMIN_INFO_VIEW:                        $controller = new AdminInfoView; break;
            case self::ROUTE_ADMIN_TRANSACTION_LIST:                 $controller = new AdminTransactionsList; break;
            case self::ROUTE_ADMIN_TRANSACTION_VIEW:                 $controller = new AdminTransactionView; break;
            case self::ROUTE_ADMIN_SEARCH_LIST:                      $controller = new AdminSearchList; break;
            case self::ROUTE_ADMIN_SEARCH_VIEW:                      $controller = new AdminSearchView; break;
            case self::ROUTE_ADMIN_SECURITY_BLOCKED_IP:              $controller = new BlockedIpList; break;
            case self::ROUTE_ADMIN_SECURITY_BLOCKED_IP_DELETE:       $controller = new BlockedIpDelete; break;
            case self::ROUTE_ADMIN_SECURITY_DEACTIVATED_USER:        $controller = new DeactivatedUserList; break;
            case self::ROUTE_ADMIN_SECURITY_DEACTIVATED_USER_DELETE: $controller = new DeactivatedUserDelete; break;
            case self::ROUTE_ADMIN_SECURITY_SECURITY_LOG:            $controller = new SecurityLogList; break;
            case self::ROUTE_ADMIN_USER_DELETE:                      $controller = new UserDelete; break;
            case self::ROUTE_ADMIN_USER_EDIT:                        $controller = new UserEdit; break;
            case self::ROUTE_ADMIN_USER_EDIT_PASSWORD:               $controller = new UserEditPassword; break;
            case self::ROUTE_ADMIN_USER_LIST:                        $controller = new UserList; break;
            case self::ROUTE_ADMIN_USER_NEW:                         $controller = new UserNew; break;
            case self::ROUTE_ADMIN_USER_VIEW:                        $controller = new UserView; break;
        }
        return $controller;
    }

}
