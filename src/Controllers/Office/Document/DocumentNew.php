<?php

namespace Firststep\Controllers\Office\Document;

use Firststep\Common\Controllers\ManagerDocumentSenderController;
use Firststep\Templates\Blocks\Sidebars\AdminSidebar;
use Firststep\Common\Json\JsonBlockFormParser;
use Firststep\Common\Blocks\StaticTable;
use Firststep\Common\Blocks\Button;
use Firststep\Common\Router\Router;
use Firststep\Common\Database\QueryExecuter;
use Firststep\Common\Builders\QueryBuilder;
use Firststep\Common\Builders\MenuBuilder;

/**
 * 
 */
class DocumentNew extends ManagerDocumentSenderController {

    function __construct() {
		$this->queryExecuter = new QueryExecuter;
		$this->queryBuilder = new QueryBuilder;
		$this->jsonBlockFormParser = new JsonBlockFormParser;
		$this->menubuilder = new MenuBuilder;
    }

    /**
     * @throws GeneralException
     */
	public function getRequest() {
		$this->queryExecuter->setDBH( $this->dbconnection->getDBH() );
	    $this->queryExecuter->setQueryBuilder( $this->queryBuilder );
	    $this->queryExecuter->setQueryStructure( $this->resource->query );
	    $this->queryExecuter->setParameters( $this->internalGetParameters );

		$formBlock = $this->jsonBlockFormParser->parse( 
			$this->resource, 
			null,
			$this->router->make_url( Router::ROUTE_OFFICE_DOCUMENT_NEW, 'res='.$this->getParameters['res'] )
		);
		
		$this->title = $this->setup->getAppNameForPageTitle() . ' :: Document new';

		$menuresource = $this->jsonloader->loadResource( $this->sessionWrapper->getSessionGroup() );
		$this->menubuilder->setMenuStructure( $menuresource );
		$this->menubuilder->setRouter( $this->router );
		
		$this->menucontainer    = array( $this->menubuilder->createMenu() );
		$this->leftcontainer    = array( new AdminSidebar( $this->setup->getAppNameForPageTitle(), Router::ROUTE_ADMIN_ENTITY_LIST, $this->router ) );
		$this->centralcontainer = array( $formBlock );
	}
	
	public function postRequest() {
		$this->queryExecuter->setDBH( $this->dbconnection->getDBH() );

		foreach ($this->resource->logics as $logic) {
			$this->queryExecuter->setQueryBuilder( $this->queryBuilder );
	    	$this->queryExecuter->setQueryStructure( $logic );
	    	$this->queryExecuter->setParameters( $this->postParameters );

			$this->queryExecuter->executeQuery();
		}

		$this->redirectToSecondPreviousPage();
	}

    public function show_second_get_error_page() {
        throw new ErrorPageException('Error page exception function show_get_error_page()');
    }

}
