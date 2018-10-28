<?php

namespace Firststep\Controllers\Office\Manager;

use Firststep\Common\Controllers\ManagerEntityController;
use Firststep\Templates\Blocks\Sidebars\AdminSidebar;
use Firststep\Common\Router\Router;
use Firststep\Common\Database\QueryExecuter;
use Firststep\Common\Builders\QueryBuilder;
use Firststep\Common\Builders\FormBuilder;
use Firststep\Common\Builders\TableBuilder;
use Firststep\Common\Builders\MenuBuilder;

/**
 * User: Fabio
 * Date: 11/09/2018
 * Time: 22:34
 */
class EntitySearch extends ManagerEntityController {

    function __construct() {
		$this->queryExecuter = new QueryExecuter;
		$this->queryBuilder = new QueryBuilder;
		$this->formBuilder = new FormBuilder;
		$this->tableBuilder = new TableBuilder;
		$this->menubuilder = new MenuBuilder();
    }

    /**
     * @throws GeneralException
     */
	public function getRequest() {
		$this->queryExecuter->setDBH( $this->dbconnection->getDBH() );
	    $this->queryExecuter->setQueryBuilder( $this->queryBuilder );
	    $this->queryExecuter->setQueryStructure( $this->resource->query );
	    $this->queryExecuter->setParameters( $this->internalGetParameters );
	    $menuresource = $this->jsonloader->loadResource( $this->sessionWrapper->getSessionGroup() );

		$this->menubuilder->setMenuStructure( $menuresource );
		$this->menubuilder->setRouter( $this->router );

		$result = $this->queryExecuter->executeQuery();
		$entity = $result->fetch();

		$this->formBuilder->setFormStructure( $this->resource->form );
		$this->formBuilder->setEntity( $entity );
		$this->formBuilder->setAction( $this->router->make_url( Router::ROUTE_OFFICE_ENTITY_SEARCH, 'res='.$this->getParameters['res'] ) );

		$this->tableBuilder->setRouter( $this->router );
		$this->tableBuilder->setTableStructure( $this->resource->table );
		$this->tableBuilder->setEntities( array() );
		
		$this->title = $this->setup->getAppNameForPageTitle() . ' :: Office search';
	
		$this->menucontainer    = array( $this->menubuilder->createMenu() );
		$this->leftcontainer    = array( new AdminSidebar( $this->setup->getAppNameForPageTitle(), Router::ROUTE_ADMIN_ENTITY_LIST, $this->router ) );
		$this->centralcontainer = array( $this->formBuilder->createForm() );
		$this->secondcentralcontainer = array( $this->tableBuilder->createTable() );
	}
	
	public function postRequest() {
		$this->queryExecuter->setDBH( $this->dbconnection->getDBH() );
		$this->queryExecuter->setQueryBuilder( $this->queryBuilder );
	    $this->queryExecuter->setQueryStructure( $this->resource->query );
	    $this->queryExecuter->setParameters( $this->postParameters );
		$this->queryExecuter->executeQuery();

		$result = $this->queryExecuter->executeQuery();

		$menuresource = $this->jsonloader->loadResource( $this->sessionWrapper->getSessionGroup() );
		$this->menubuilder->setMenuStructure( $menuresource );
		$this->menubuilder->setRouter( $this->router );

		$this->formBuilder->setFormStructure( $this->resource->form );
		$this->formBuilder->setEntity( $entity );
		$this->formBuilder->setAction( $this->router->make_url( Router::ROUTE_OFFICE_ENTITY_SEARCH, 'res='.$this->getParameters['res'] ) );

		$this->tableBuilder->setRouter( $this->router );
		$this->tableBuilder->setTableStructure( $this->resource->table );
		$this->tableBuilder->setEntities( $result );
		
		$this->title = $this->setup->getAppNameForPageTitle() . ' :: Office search';
	
		$this->menucontainer    = array( $this->menubuilder->createMenu() );
		$this->leftcontainer    = array( new AdminSidebar( $this->setup->getAppNameForPageTitle(), Router::ROUTE_ADMIN_ENTITY_LIST, $this->router ) );
		$this->centralcontainer = array( $this->formBuilder->createForm() );
		$this->secondcentralcontainer = array( $this->tableBuilder->createTable() );
	}

    public function show_second_get_error_page() {
        throw new ErrorPageException('Error page exception function show_get_error_page()');
    }

}
