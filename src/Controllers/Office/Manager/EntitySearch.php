<?php

namespace Firststep\Controllers\Office\Manager;

use Firststep\Common\Controllers\ManagerEntityController;
use Firststep\Templates\Blocks\Sidebars\AdminSidebar;
use Firststep\Common\Router\Router;
use Firststep\Common\Json\TemplateBuilders\Form\FormBuilder;
use Firststep\Common\Json\TemplateBuilders\Table\TableBuilder;
use Firststep\Common\Json\TemplateBuilders\MenuBuilder;

/**
 * User: Fabio
 * Date: 11/09/2018
 * Time: 22:34
 */
class EntitySearch extends ManagerEntityController {

    private $formBuilder;
    private $tableBuilder;
    private $menubuilder;

    function __construct() {
		$this->formBuilder = new FormBuilder;
		$this->tableBuilder = new TableBuilder;
		$this->menubuilder = new MenuBuilder;
    }

    /**
     * @throws GeneralException
     */
	public function getRequest() {
	    $menuresource = $this->jsonloader->loadResource( $this->sessionWrapper->getSessionGroup() );

		$this->menubuilder->setMenuStructure( $menuresource );
		$this->menubuilder->setRouter( $this->router );

        $this->formBuilder->setResource( $this->resource );
        $this->formBuilder->setAction($this->router->make_url( Router::ROUTE_OFFICE_ENTITY_SEARCH, 'res='.$this->getParameters['res'] ));

        $this->tableBuilder->setRouter( $this->router );
        $this->tableBuilder->setResource( $this->resource );
        $this->tableBuilder->setParameters( $this->internalGetParameters );
        $this->tableBuilder->setDbconnection( $this->dbconnection );
		
		$this->title = $this->setup->getAppNameForPageTitle() . ' :: Office search';
	
		$this->menucontainer    = array( $this->menubuilder->createMenu() );
		$this->leftcontainer    = array();
		$this->centralcontainer = array( $this->formBuilder->createForm() );
		$this->secondcentralcontainer = array( $this->tableBuilder->createTable() );
	}
	
	public function postRequest() {
		$menuresource = $this->jsonloader->loadResource( $this->sessionWrapper->getSessionGroup() );
		$this->menubuilder->setMenuStructure( $menuresource );
		$this->menubuilder->setRouter( $this->router );

        $this->formBuilder->setResource( $this->resource );
        $this->formBuilder->setAction($this->router->make_url( Router::ROUTE_OFFICE_ENTITY_SEARCH, 'res='.$this->getParameters['res'] ));

        $this->tableBuilder->setRouter( $this->router );
        $this->tableBuilder->setResource( $this->resource );
        $this->tableBuilder->setParameters( $this->postParameters );
        $this->tableBuilder->setDbconnection( $this->dbconnection );
        $this->tableBuilder->setMethod(TableBuilder::POST_METHOD);
		
		$this->title = $this->setup->getAppNameForPageTitle() . ' :: Office search';
	
		$this->menucontainer    = array( $this->menubuilder->createMenu() );
		$this->leftcontainer    = array();
		$this->centralcontainer = array( $this->formBuilder->createForm() );
		$this->secondcentralcontainer = array( $this->tableBuilder->createTable() );
	}

}
