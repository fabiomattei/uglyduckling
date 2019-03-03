<?php

namespace Firststep\Controllers\Office\Manager;

use Firststep\Common\Controllers\ManagerEntityController;
use Firststep\Templates\Blocks\Sidebars\AdminSidebar;
use Firststep\Common\Router\Router;
use Firststep\Common\Builders\TableBuilder;
use Firststep\Common\Builders\MenuBuilder;

/**
 * User: Fabio Mattei
 * Date: 16/08/2018
 * Time: 12:02
 */
class EntityTable extends ManagerEntityController {

    private $tableBuilder;
    private $menubuilder;

    function __construct() {
		$this->tableBuilder = new TableBuilder;
		$this->menubuilder = new MenuBuilder;
    }
	
    /**
     * @throws GeneralException
     */
	public function getRequest() {
		$this->resource = $this->jsonloader->loadResource( $this->getParameters['res'] );
		
		$menuresource = $this->jsonloader->loadResource( $this->sessionWrapper->getSessionGroup() );
		$this->menubuilder->setMenuStructure( $menuresource );
		$this->menubuilder->setRouter( $this->router );

		$this->tableBuilder->setRouter( $this->router );
        $this->tableBuilder->setResource( $this->resource );
		$this->tableBuilder->setParameters( $this->internalGetParameters );
		$this->tableBuilder->setDbconnection( $this->dbconnection );
		$this->tableBuilder->setHtmlTemplateLoader( $this->htmlTemplateLoader );

		$this->title = $this->setup->getAppNameForPageTitle() . ' :: Office table';
		
		$this->menucontainer    = array( $this->menubuilder->createMenu() );
		$this->leftcontainer    = array();
		$this->centralcontainer = array( $this->tableBuilder->createTable() );
	}

}
