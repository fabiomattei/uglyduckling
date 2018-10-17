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
use Firststep\Common\Database\DocumentDao;

/**
 * 
 */
class DocumentEdit extends ManagerDocumentSenderController {

    function __construct() {
		$this->queryExecuter = new QueryExecuter;
		$this->queryBuilder = new QueryBuilder;
		$this->jsonBlockFormParser = new JsonBlockFormParser;
		$this->menubuilder = new MenuBuilder;
		$this->documentDao = new DocumentDao;
    }

    /**
     * @throws GeneralException
     */
	public function getRequest() {
		$this->queryExecuter->setDBH( $this->dbconnection->getDBH() );
	    $this->queryExecuter->setQueryBuilder( $this->queryBuilder );
	    $this->queryExecuter->setQueryStructure( $this->resource->query );
	    $this->queryExecuter->setParameters( $this->internalGetParameters );
		
		$this->documentDao->setDBH( $this->dbconnection->getDBH() );
		$this->documentDao->setTableName( $this->resource->name );
		$entity = $this->documentDao->getById( $this->getParameters['id'] );
		
		// adding id hidden field to the structure
		$idfield = new \stdClass;
		$idfield->type = 'hidden';
		$idfield->validation = 'required|numeric';
		$idfield->name = 'id';
		$idfield->value = 'id';
		$idfield->row = 1;
		$this->resource->fields[] = $idfield; 

		$formBlock = $this->jsonBlockFormParser->parse(
			$this->resource,
			$entity,
			$this->router->make_url( Router::ROUTE_OFFICE_DOCUMENT_EDIT, 'res='.$this->getParameters['res'] )
		);
		
		$this->title = $this->setup->getAppNameForPageTitle() . ' :: Document edit';
		
		$menuresource = $this->jsonloader->loadResource( $this->sessionWrapper->getSessionGroup() );
		$this->menubuilder->setMenuStructure( $menuresource );
		$this->menubuilder->setRouter( $this->router );
		
		$this->menucontainer    = array( $this->menubuilder->createMenu() );
		$this->leftcontainer    = array( new AdminSidebar( $this->setup->getAppNameForPageTitle(), Router::ROUTE_ADMIN_ENTITY_LIST, $this->router ) );
		$this->centralcontainer = array( $formBlock );
	}
	
	public function postRequest() {
		// updating the document table
		$this->documentDao->setDBH( $this->dbconnection->getDBH() );
		$this->documentDao->setTableName( $this->resource->name );
		
		// removing save button from parameters and adding user id and user group
		$queryparameters = $this->postParameters;
		$idToUpdate = $queryparameters['id'];
		$queryparameters['sourceuserid'] = $this->sessionWrapper->getSessionUserId();
		$queryparameters['sourcegroup'] = $this->sessionWrapper->getSessionGroup();
		unset( $queryparameters['save'] );
		unset( $queryparameters['id'] );
		
		// saving in database
		$this->documentDao->update( $idToUpdate, $queryparameters );
		
		// applying the possible logics
		$this->queryExecuter->setDBH( $this->dbconnection->getDBH() );

		foreach ( $this->resource->logics->onupdate as $logic ) {
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
