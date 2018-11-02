<?php

namespace Firststep\Controllers\Office\Manager;

use Firststep\Common\Builders\FormBuilder;
use Firststep\Common\Builders\QueryBuilder;
use Firststep\Common\Controllers\ManagerEntityController;
use Firststep\Common\Database\QueryExecuter;
use Firststep\Templates\Blocks\Sidebars\AdminSidebar;
use Firststep\Common\Router\Router;
use Firststep\Common\Builders\MenuBuilder;

/**
 * User: Fabio
 * Date: 17/08/2018
 * Time: 07:07
 */
class EntityForm extends ManagerEntityController {

    private $menubuilder;
    private $formBuilder;
    private $queryExecuter;
    private $queryBuilder;

    function __construct() {
		$this->formBuilder = new FormBuilder;
		$this->menubuilder = new MenuBuilder;
        $this->queryExecuter = new QueryExecuter;
        $this->queryBuilder = new QueryBuilder;
    }

    /**
     * @throws GeneralException
     */
	public function getRequest() {
		$this->title = $this->setup->getAppNameForPageTitle() . ' :: Office form';

		$menuresource = $this->jsonloader->loadResource( $this->sessionWrapper->getSessionGroup() );
		$this->menubuilder->setMenuStructure( $menuresource );
		$this->menubuilder->setRouter( $this->router );

        $this->formBuilder->setRouter( $this->router );
        $this->formBuilder->setResource( $this->resource );
        $this->formBuilder->setParameters( $this->internalGetParameters );
        $this->formBuilder->setDbconnection( $this->dbconnection );
        $this->formBuilder->setAction($this->router->make_url( Router::ROUTE_OFFICE_ENTITY_FORM, 'res='.$this->getParameters['res'] ));

		$this->menucontainer    = array( $this->menubuilder->createMenu() );
		$this->leftcontainer    = array( new AdminSidebar( $this->setup->getAppNameForPageTitle(), Router::ROUTE_ADMIN_ENTITY_LIST, $this->router ) );
		$this->centralcontainer = array( $this->formBuilder->createForm() );
	}
	
	public function postRequest() {
		$this->queryExecuter->setDBH( $this->dbconnection->getDBH() );

		foreach ($this->resource->post->logics as $logic) {
			$this->queryExecuter->setQueryBuilder( $this->queryBuilder );
	    	$this->queryExecuter->setQueryStructure( $logic );
	    	$this->queryExecuter->setPostParameters( $this->postParameters );

			$this->queryExecuter->executeQuery();
		}

		$this->redirectToSecondPreviousPage();
	}

    public function show_second_get_error_page() {
        throw new ErrorPageException('Error page exception function show_get_error_page()');
    }

}
