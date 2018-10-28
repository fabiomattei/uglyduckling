<?php

namespace Firststep\Controllers\Office\Document;

use Firststep\Common\Controllers\ManagerEntityController;
use Firststep\Templates\Blocks\Sidebars\AdminSidebar;
use Firststep\Common\Router\Router;
use Firststep\Common\Database\QueryExecuter;
use Firststep\Common\Builders\QueryBuilder;
use Firststep\Common\Builders\InfoBuilder;
use Firststep\Common\Builders\MenuBuilder;

/**
 * 
 */
class DocumentInfo extends ManagerEntityController {

    function __construct() {
		$this->queryExecuter = new QueryExecuter;
		$this->queryBuilder = new QueryBuilder;
		$this->infoBuilder = new InfoBuilder;
		$this->menubuilder = new MenuBuilder;
    }

    /**
     * @throws GeneralException
     */
	public function getRequest() {
		$menuresource = $this->jsonloader->loadResource( $this->sessionWrapper->getSessionGroup() );
		$this->menubuilder->setMenuStructure( $menuresource );
		$this->menubuilder->setRouter( $this->router );

		$this->queryExecuter->setDBH( $this->dbconnection->getDBH() );
	    $this->queryExecuter->setQueryBuilder( $this->queryBuilder );
	    $this->queryExecuter->setQueryStructure( $this->resource->query );
	    $this->queryExecuter->setParameters( $this->internalGetParameters );

		$result = $this->queryExecuter->executeQuery();
		$entity = $result->fetch();

		$this->infoBuilder->setFormStructure( $this->resource->form );
		$this->infoBuilder->setEntity( $entity );
		
		$this->title = $this->setup->getAppNameForPageTitle() . ' :: Info';
	
		$this->menucontainer    = array( $this->menubuilder->createMenu() );
		$this->leftcontainer    = array( new AdminSidebar( $this->setup->getAppNameForPageTitle(), Router::ROUTE_ADMIN_ENTITY_LIST, $this->router ) );
		$this->centralcontainer = array( $this->infoBuilder->createInfo() );
	}

    public function show_second_get_error_page() {
        throw new ErrorPageException('Error page exception function show_get_error_page()');
    }

}
