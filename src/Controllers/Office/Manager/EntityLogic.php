<?php

namespace Firststep\Controllers\Office\Manager;

use Firststep\Common\Controllers\Controller;
use Firststep\Templates\Blocks\Menus\AdminMenu;
use Firststep\Templates\Blocks\Sidebars\AdminSidebar;
use Firststep\Common\Json\JsonBlockParser;
use Firststep\Common\Blocks\StaticTable;
use Firststep\Common\Blocks\Button;
use Firststep\Common\Router\Router;
use Firststep\Common\Database\QueryExecuter;
use Firststep\Common\Builders\QueryBuilder;
use Firststep\Common\Builders\ValidationBuilder;
use Gump;

/**
 * User: Fabio
 * Date: 29/09/2018
 * Time: 05:57
 */
class EntityLogic extends Controller {

	public $get_validation_rules = array( 'res' => 'required|max_len,50' );
    public $get_filter_rules     = array( 'res' => 'trim' );

    function __construct() {
		$this->queryExecuter = new QueryExecuter;
		$this->queryBuilder = new QueryBuilder;
    }

    public function loadResource() {
    	$this->resource = $this->jsonloader->loadResource( $this->getParameters['res'] );
    }
	
    /**
     * @throws GeneralException
     */
	public function getRequest() {
		$this->queryExecuter->setDBH( $this->dbconnection->getDBH() );

        foreach ($this->resource->logics as $logic) {
            $this->queryExecuter->setQueryBuilder( $this->queryBuilder );
            $this->queryExecuter->setQueryStructure( $logic );
            $this->queryExecuter->setParameters( $this->internalGetParameters );
            $this->queryExecuter->executeQuery();
        }

        $this->redirectToPreviousPage();
	}

	/**
     * check the parameters sent through the url and check if they are ok from
     * the point of view of the validation rules
     */
    public function second_check_get_request() {
    	$this->secondGump = new Gump;

    	$val = new ValidationBuilder;
    	$validation_rules = $val->getValidationRoules( $this->resource->request->parameters );
    	$filter_rules = $val->getValidationFilters( $this->resource->request->parameters );

        if ( count( $validation_rules ) == 0 ) {
            return true;
        } else {
            $parms = $this->secondGump->sanitize( $this->getParameters );
            $this->secondGump->validation_rules( $validation_rules );
            $this->secondGump->filter_rules( $filter_rules );
            $this->internalGetParameters = $this->secondGump->run( $parms );
			$this->unvalidated_parameters = $parms;
            if ( $this->internalGetParameters === false ) {
				$this->readableErrors = $this->secondGump->get_readable_errors(true);
                return false;
            } else {
                return true;
            }
        }
    }

    public function showPage() {
        $time_start = microtime(true);

        $this->jsonloader->loadIndex();

        if ($this->serverWrapper->isGetRequest()) {
			if ( $this->check_authorization_get_request() ) {
	            if ( $this->check_get_request() ) {
	            	$this->loadResource();
	            	if ( $this->second_check_get_request() ) {
	            		$this->getRequest();	
	            	} else {
	                	$this->show_second_get_error_page();
	            	}
	            } else {
	                $this->show_get_error_page();
	            }
			} else {
				$this->check_authorization_get_request();
			}
        } else {
			if ( $this->check_authorization_post_request() ) {
	            if ( $this->check_get_request() ) {
	            	$this->loadResource();
	            	if ( $this->check_post_request() ) {
	            		$this->postRequest();	
	            	} else {
	                	$this->show_post_error_page();
	            	}
	            } else {
	                $this->show_post_error_page();
	            }
			} else {
				$this->check_authorization_post_request();
			}
        }

        $this->loadTemplate();

        $time_end = microtime(true);
        if (($time_end - $time_start) > 5) {
            $this->logger->write('WARNING TIME :: ' . $this->request->getServerRequestMethod() . ' ' . $this->request->getServerPhpSelf() . ' ' . ($time_end - $time_start) . ' sec', __FILE__, __LINE__);
        }
    }

    public function show_second_get_error_page() {
        throw new ErrorPageException('Error page exception function show_get_error_page()');
    }

}
