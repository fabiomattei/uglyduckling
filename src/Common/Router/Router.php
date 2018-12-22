<?php

namespace Firststep\Common\Router;

use Firststep\Controllers\Office\Manager\EntityDashboard;
use Firststep\Controllers\Office\Manager\EntityChart;
use Firststep\Controllers\Office\Manager\EntityTable;
use Firststep\Controllers\Office\Manager\EntityForm;
use Firststep\Controllers\Office\Manager\EntityInfo;
use Firststep\Controllers\Office\Manager\EntitySearch;
use Firststep\Controllers\Office\Manager\EntityExport;
use Firststep\Controllers\Office\Manager\EntityTransaction;
use Firststep\Controllers\Office\Document\DocumentAccept;
use Firststep\Controllers\Office\Document\DocumentDelete;
use Firststep\Controllers\Office\Document\DocumentEdit;
use Firststep\Controllers\Office\Document\DocumentExport;
use Firststep\Controllers\Office\Document\DocumentInbox;
use Firststep\Controllers\Office\Document\DocumentAcceptedBox;
use Firststep\Controllers\Office\Document\DocumentRejectedBox;
use Firststep\Controllers\Office\Document\DocumentOutbox;
use Firststep\Controllers\Office\Document\DocumentOutboxUser;
use Firststep\Controllers\Office\Document\DocumentDraft;
use Firststep\Controllers\Office\Document\DocumentDraftUser;
use Firststep\Controllers\Office\Document\DocumentInfo;
use Firststep\Controllers\Office\Document\DocumentNew;
use Firststep\Controllers\Office\Document\DocumentNewList;
use Firststep\Controllers\Office\Document\DocumentReject;
use Firststep\Controllers\Office\Document\DocumentSearch;
use Firststep\Controllers\Office\Document\DocumentSend;
use Firststep\Controllers\Community\Login;
use Firststep\Controllers\Admin\Dashboard\AdminDashboard;
use Firststep\Controllers\Admin\Entity\EntityList;
use Firststep\Controllers\Admin\Entity\EntityView;
use Firststep\Controllers\Admin\Entity\EntityCreateTable;
use Firststep\Controllers\Admin\Entity\EntityDropTable;
use Firststep\Controllers\Admin\Forms\AdminFormsList;
use Firststep\Controllers\Admin\Forms\AdminFormView;
use Firststep\Controllers\Admin\Document\AdminDocumentsList;
use Firststep\Controllers\Admin\Document\AdminDocumentView;
use Firststep\Controllers\Admin\Document\AdminDocumentCreateTable;
use Firststep\Controllers\Admin\Document\AdminDocumentDropTable;
use Firststep\Controllers\Admin\Table\TableList;
use Firststep\Controllers\Admin\Table\AdminTableView;
use Firststep\Controllers\Admin\Export\AdminExportList;
use Firststep\Controllers\Admin\Export\AdminExportView;
use Firststep\Controllers\Admin\Group\AdminGroupAddUser;
use Firststep\Controllers\Admin\Group\AdminGroupList;
use Firststep\Controllers\Admin\Group\AdminGroupRemoveUser;
use Firststep\Controllers\Admin\Group\AdminGroupView;
use Firststep\Controllers\Admin\Info\AdminInfoList;
use Firststep\Controllers\Admin\Info\AdminInfoView;
use Firststep\Controllers\Admin\Transactions\AdminTransactionsList;
use Firststep\Controllers\Admin\Transactions\AdminTransactionView;
use Firststep\Controllers\Admin\Search\AdminSearchList;
use Firststep\Controllers\Admin\Search\AdminSearchView;
use Firststep\Controllers\Admin\User\UserDelete;
use Firststep\Controllers\Admin\User\UserEdit;
use Firststep\Controllers\Admin\User\UserEditPassword;
use Firststep\Controllers\Admin\User\UserList;
use Firststep\Controllers\Admin\User\UserNew;
use Firststep\Controllers\Admin\User\UserView;

class Router {

    const ROUTE_OFFICE_ENTITY_CHART         = 'officeentitychart';
	const ROUTE_OFFICE_ENTITY_TABLE         = 'officeentitytable';
	const ROUTE_OFFICE_ENTITY_FORM          = 'officeentityform';
	const ROUTE_OFFICE_ENTITY_INFO          = 'officeentityinfo';
	const ROUTE_OFFICE_ENTITY_SEARCH        = 'officeentitysearch';
	const ROUTE_OFFICE_ENTITY_EXPORT        = 'officeentityexport';
	const ROUTE_OFFICE_ENTITY_LOGIC         = 'officeentitylogic';
    const ROUTE_OFFICE_ENTITY_DASHBOARD     = 'officeentitydashboard';
	const ROUTE_OFFICE_DOCUMENT_ACCEPT      = 'officedocumentaccept';
	const ROUTE_OFFICE_DOCUMENT_DELETE      = 'officedocumentdelete';
	const ROUTE_OFFICE_DOCUMENT_EDIT        = 'officedocumentedit';
	const ROUTE_OFFICE_DOCUMENT_EXPORT      = 'officedocumentexport';
	const ROUTE_OFFICE_DOCUMENT_INBOX       = 'officedocumentinbox';
    const ROUTE_OFFICE_DOCUMENT_ACCEPTEDBOX = 'documentacceptedbox';
    const ROUTE_OFFICE_DOCUMENT_REJECTEDBOX = 'documentrejectedbox';
	const ROUTE_OFFICE_DOCUMENT_OUTBOX      = 'officedocumentoutbox';
	const ROUTE_OFFICE_DOCUMENT_OUTBOX_USER = 'officedocumentoutboxuser';
	const ROUTE_OFFICE_DOCUMENT_DRAFT       = 'officedocumentdraft';
	const ROUTE_OFFICE_DOCUMENT_DRAFT_USER  = 'officedocumentdraftuser';
	const ROUTE_OFFICE_DOCUMENT_INFO        = 'officedocumentinfo';
	const ROUTE_OFFICE_DOCUMENT_NEW         = 'officedocumentnew';
	const ROUTE_OFFICE_DOCUMENT_NEW_LIST    = 'officedocumentnewlist';
	const ROUTE_OFFICE_DOCUMENT_REJECT      = 'officedocumentreject';
	const ROUTE_OFFICE_DOCUMENT_SEARCH      = 'officedocumentsearch';
	const ROUTE_OFFICE_DOCUMENT_SEND        = 'officedocumentsend';
	const ROUTE_COMMUNITY_LOGIN             = 'communitylogin';
	const ROUTE_ADMIN_DASHBOARD             = 'admindashboard';
	const ROUTE_ADMIN_ENTITY_LIST           = 'adminentitylist';
	const ROUTE_ADMIN_ENTITY_VIEW           = 'adminentityview';
	const ROUTE_ADMIN_ENTITY_CREATE_TABLE   = 'adminentitycreatetable';
	const ROUTE_ADMIN_ENTITY_DROP_TABLE     = 'adminentitydroptable';
    const ROUTE_ADMIN_FORM_LIST             = 'adminformslist';
    const ROUTE_ADMIN_FORM_VIEW             = 'adminformview';
	const ROUTE_ADMIN_DOCUMENT_LIST         = 'admindocumentlist';
	const ROUTE_ADMIN_DOCUMENT_VIEW         = 'admindocumentview';
	const ROUTE_ADMIN_DOCUMENT_CREATE_TABLE = 'admindocumentcreatetable';
	const ROUTE_ADMIN_DOCUMENT_DROP_TABLE   = 'admindocumentdroptable';
	const ROUTE_ADMIN_TABLE_LIST            = 'admintablelist';
    const ROUTE_ADMIN_TABLE_VIEW            = 'admintableview';
	const ROUTE_ADMIN_EXPORT_LIST           = 'adminexportlist';
    const ROUTE_ADMIN_EXPORT_VIEW           = 'adminexportview';
    const ROUTE_ADMIN_GROUP_ADD_USER        = 'admingroupadduser';
	const ROUTE_ADMIN_GROUP_LIST            = 'admingrouplist';
    const ROUTE_ADMIN_GROUP_REMOVE_USER     = 'admingroupremoveuser';
    const ROUTE_ADMIN_GROUP_VIEW            = 'admingroupview';
    const ROUTE_ADMIN_INFO_LIST             = 'admininfolist';
    const ROUTE_ADMIN_INFO_VIEW             = 'admininfoview';
    const ROUTE_ADMIN_LOGIC_LIST            = 'adminlogiclist';
    const ROUTE_ADMIN_LOGIC_VIEW            = 'adminlogicview';
    const ROUTE_ADMIN_SEARCH_LIST           = 'adminsearchlist';
    const ROUTE_ADMIN_SEARCH_VIEW           = 'adminsearchview';
    const ROUTE_ADMIN_USER_DELETE           = 'adminuserdelete';
    const ROUTE_ADMIN_USER_EDIT             = 'adminuseredit';
    const ROUTE_ADMIN_USER_EDIT_PASSWORD    = 'adminusereditpassword';
    const ROUTE_ADMIN_USER_LIST             = 'adminuserlist';
    const ROUTE_ADMIN_USER_NEW              = 'adminusernew';
    const ROUTE_ADMIN_USER_VIEW             = 'adminuserview';

	public function __construct( $basepath ) {
		$this->basepath = $basepath;
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
			case self::ROUTE_OFFICE_DOCUMENT_ACCEPT:      $controller = new DocumentAccept; break;
            case self::ROUTE_OFFICE_DOCUMENT_DELETE:      $controller = new DocumentDelete; break;
			case self::ROUTE_OFFICE_DOCUMENT_EDIT:        $controller = new DocumentEdit; break;
			case self::ROUTE_OFFICE_DOCUMENT_EXPORT:      $controller = new DocumentExport; break;
			case self::ROUTE_OFFICE_DOCUMENT_INBOX:       $controller = new DocumentInbox; break;
            case self::ROUTE_OFFICE_DOCUMENT_ACCEPTEDBOX: $controller = new DocumentAcceptedBox; break;
            case self::ROUTE_OFFICE_DOCUMENT_REJECTEDBOX: $controller = new DocumentRejectedBox(); break;
			case self::ROUTE_OFFICE_DOCUMENT_OUTBOX:      $controller = new DocumentOutbox; break;
			case self::ROUTE_OFFICE_DOCUMENT_OUTBOX_USER: $controller = new DocumentOutboxUser; break;
			case self::ROUTE_OFFICE_DOCUMENT_DRAFT:       $controller = new DocumentDraft; break;
			case self::ROUTE_OFFICE_DOCUMENT_DRAFT_USER:  $controller = new DocumentDraftUser; break;
			case self::ROUTE_OFFICE_DOCUMENT_INFO:        $controller = new DocumentInfo; break;
			case self::ROUTE_OFFICE_DOCUMENT_NEW:         $controller = new DocumentNew; break;
			case self::ROUTE_OFFICE_DOCUMENT_NEW_LIST:    $controller = new DocumentNewList; break;
			case self::ROUTE_OFFICE_DOCUMENT_REJECT:      $controller = new DocumentReject; break;
			case self::ROUTE_OFFICE_DOCUMENT_SEARCH:      $controller = new DocumentSearch; break;
			case self::ROUTE_OFFICE_DOCUMENT_SEND:        $controller = new DocumentSend; break;
			case self::ROUTE_COMMUNITY_LOGIN:             $controller = new Login; break;
			case self::ROUTE_ADMIN_DASHBOARD:             $controller = new AdminDashboard; break;
			case self::ROUTE_ADMIN_ENTITY_LIST:           $controller = new EntityList; break;
			case self::ROUTE_ADMIN_ENTITY_VIEW:           $controller = new EntityView; break;
			case self::ROUTE_ADMIN_ENTITY_CREATE_TABLE:   $controller = new EntityCreateTable; break;
			case self::ROUTE_ADMIN_ENTITY_DROP_TABLE:     $controller = new EntityDropTable; break;
            case self::ROUTE_ADMIN_FORM_LIST:             $controller = new AdminFormsList; break;
            case self::ROUTE_ADMIN_FORM_VIEW:             $controller = new AdminFormView; break;
			case self::ROUTE_ADMIN_DOCUMENT_LIST:         $controller = new AdminDocumentsList; break;
			case self::ROUTE_ADMIN_DOCUMENT_VIEW:         $controller = new AdminDocumentView; break;
			case self::ROUTE_ADMIN_DOCUMENT_CREATE_TABLE: $controller = new AdminDocumentCreateTable; break;
			case self::ROUTE_ADMIN_DOCUMENT_DROP_TABLE:   $controller = new AdminDocumentDropTable; break;
			case self::ROUTE_ADMIN_TABLE_LIST:            $controller = new TableList; break;
            case self::ROUTE_ADMIN_TABLE_VIEW:            $controller = new AdminTableView(); break;
			case self::ROUTE_ADMIN_EXPORT_LIST:           $controller = new AdminExportList; break;
            case self::ROUTE_ADMIN_EXPORT_VIEW:           $controller = new AdminExportView(); break;
            case self::ROUTE_ADMIN_GROUP_ADD_USER:        $controller = new AdminGroupAddUser(); break;
            case self::ROUTE_ADMIN_GROUP_LIST:            $controller = new AdminGroupList; break;
            case self::ROUTE_ADMIN_GROUP_REMOVE_USER:     $controller = new AdminGroupRemoveUser(); break;
            case self::ROUTE_ADMIN_GROUP_VIEW:            $controller = new AdminGroupView; break;
            case self::ROUTE_ADMIN_INFO_LIST:             $controller = new AdminInfoList; break;
            case self::ROUTE_ADMIN_INFO_VIEW:             $controller = new AdminInfoView; break;
            case self::ROUTE_ADMIN_LOGIC_LIST:            $controller = new AdminTransactionsList; break;
            case self::ROUTE_ADMIN_LOGIC_VIEW:            $controller = new AdminTransactionView; break;
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
