<?php

namespace Firststep\Common\Router;

use Firststep\Controllers\Office\Document\Inbox;
use Firststep\Controllers\Office\Manager\Gate;
use Firststep\Controllers\Office\Manager\EntityTable;
use Firststep\Controllers\Office\Manager\EntityForm;
use Firststep\Controllers\Office\Manager\EntityInfo;
use Firststep\Controllers\Office\Manager\EntitySearch;
use Firststep\Controllers\Office\Manager\EntityExport;
use Firststep\Controllers\Office\Manager\EntityLogic;
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
use Firststep\Controllers\Admin\Document\AdminDocumentsList;
use Firststep\Controllers\Admin\Document\AdminDocumentView;
use Firststep\Controllers\Admin\Document\AdminDocumentCreateTable;
use Firststep\Controllers\Admin\Document\AdminDocumentDropTable;
use Firststep\Controllers\Admin\Table\TableList;
use Firststep\Controllers\Admin\Report\ReportList;

class Router {
	
	const ROUTE_OFFICE_INBOX                = 'officeinbox';
	const ROUTE_OFFICE_GATE                 = 'officegate';
	const ROUTE_OFFICE_ENTITY_TABLE         = 'officeentitytable';
	const ROUTE_OFFICE_ENTITY_FORM          = 'officeentityform';
	const ROUTE_OFFICE_ENTITY_INFO          = 'officeentityinfo';
	const ROUTE_OFFICE_ENTITY_SEARCH        = 'officeentitysearch';
	const ROUTE_OFFICE_ENTITY_EXPORT        = 'officeentityexport';
	const ROUTE_OFFICE_ENTITY_LOGIC         = 'officeentitylogic';
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
	const ROUTE_ADMIN_DOCUMENT_LIST         = 'admindocumentlist';
	const ROUTE_ADMIN_DOCUMENT_VIEW         = 'admindocumentview';
	const ROUTE_ADMIN_DOCUMENT_CREATE_TABLE = 'admindocumentcreatetable';
	const ROUTE_ADMIN_DOCUMENT_DROP_TABLE   = 'admindocumentdroptable';
	const ROUTE_ADMIN_TABLE_LIST            = 'admintablelist';
	const ROUTE_ADMIN_REPORT_LIST           = 'adminreportlist';

	public function __construct( $basepath ) {
		$this->basepath = $basepath;
	}

    function getController( string $action ) {
        switch ( $action ) {
            case self::ROUTE_OFFICE_INBOX:                $controller = new Inbox; break;
			case self::ROUTE_OFFICE_GATE:                 $controller = new Gate; break;
			case self::ROUTE_OFFICE_ENTITY_TABLE:         $controller = new EntityTable; break;
			case self::ROUTE_OFFICE_ENTITY_FORM:          $controller = new EntityForm; break;
			case self::ROUTE_OFFICE_ENTITY_INFO:          $controller = new EntityInfo; break;
			case self::ROUTE_OFFICE_ENTITY_SEARCH:        $controller = new EntitySearch; break;
			case self::ROUTE_OFFICE_ENTITY_EXPORT:        $controller = new EntityExport; break;
			case self::ROUTE_OFFICE_ENTITY_LOGIC:         $controller = new EntityLogic; break;
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
			case self::ROUTE_ADMIN_DOCUMENT_LIST:         $controller = new AdminDocumentsList; break;
			case self::ROUTE_ADMIN_DOCUMENT_VIEW:         $controller = new AdminDocumentView; break;
			case self::ROUTE_ADMIN_DOCUMENT_CREATE_TABLE: $controller = new AdminDocumentCreateTable; break;
			case self::ROUTE_ADMIN_DOCUMENT_DROP_TABLE:   $controller = new AdminDocumentDropTable; break;
			case self::ROUTE_ADMIN_TABLE_LIST:            $controller = new TableList; break;
			case self::ROUTE_ADMIN_REPORT_LIST:           $controller = new ReportList; break;
			
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
