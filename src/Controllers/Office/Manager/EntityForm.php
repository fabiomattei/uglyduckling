<?php

namespace Firststep\Controllers\Office\Manager;

use Firststep\Common\Controllers\ManagerEntityController;
use Firststep\Templates\Blocks\Menus\AdminMenu;
use Firststep\Templates\Blocks\Sidebars\AdminSidebar;
use Firststep\Common\Json\JsonBlockParser;
use Firststep\Common\Blocks\StaticTable;
use Firststep\Common\Blocks\Button;
use Firststep\Common\Router\Router;
use Firststep\Common\Database\QueryExecuter;
use Firststep\Common\Builders\QueryBuilder;
use Firststep\Common\Builders\FormBuilder;
use Firststep\Common\Builders\ValidationBuilder;
use Gump;

/**
 * User: Fabio
 * Date: 17/08/2018
 * Time: 07:07
 */
class EntityForm extends ManagerEntityController {

    function __construct() {
		$this->queryExecuter = new QueryExecuter;
		$this->queryBuilder = new QueryBuilder;
		$this->formBuilder = new FormBuilder;
    }

    /**
     * @throws GeneralException
     */
	public function getRequest() {
		$this->queryExecuter->setDBH( $this->dbconnection->getDBH() );
	    $this->queryExecuter->setQueryBuilder( $this->queryBuilder );
	    $this->queryExecuter->setQueryStructure( $this->resource->query );
	    $this->queryExecuter->setParameters( $this->internalGetParameters );

		$result = $this->queryExecuter->executeQuery();
		$entity = $result->fetch();

		$this->formBuilder->setFormStructure( $this->resource->form );
		$this->formBuilder->setEntity( $entity );
		$this->formBuilder->setAction( $this->router->make_url( Router::ROUTE_OFFICE_ENTITY_FORM, 'res='.$this->getParameters['res'] ) );
		
		$this->title = $this->setup->getAppNameForPageTitle() . ' :: Office form';
	
		$this->menucontainer    = array( new AdminMenu( $this->setup->getAppNameForPageTitle(), Router::ROUTE_ADMIN_ENTITY_LIST ) );
		$this->leftcontainer    = array( new AdminSidebar( $this->setup->getAppNameForPageTitle(), Router::ROUTE_ADMIN_ENTITY_LIST, $this->router ) );
		$this->centralcontainer = array( $this->formBuilder->createForm() );
	}
	
	public function postRequest() {
		$this->queryExecuter->setDBH( $this->dbconnection->getDBH() );

		foreach ($this->resource->logics as $logic) {
			$this->queryExecuter->setQueryBuilder( $this->queryBuilder );
	    	$this->queryExecuter->setQueryStructure( $logic );
	    	$this->queryExecuter->setParameters( $this->postParameters );

			$this->queryExecuter->executeQuery();
		}

		$this->redirectToPreviousPage();
	}

    public function show_second_get_error_page() {
        throw new ErrorPageException('Error page exception function show_get_error_page()');
    }

}
