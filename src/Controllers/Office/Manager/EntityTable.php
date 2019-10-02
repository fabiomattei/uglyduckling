<?php

namespace Fabiom\UglyDuckling\Controllers\Office\Manager;

use Fabiom\UglyDuckling\Common\Controllers\ManagerEntityController;
use Fabiom\UglyDuckling\Common\Json\JsonTemplates\JsonDefaultTemplateFactory;
use Fabiom\UglyDuckling\Common\Router\Router;
use Fabiom\UglyDuckling\Common\Json\JsonTemplates\MenuBuilder;

/**
 * User: Fabio Mattei
 * Date: 16/08/2018
 * Time: 12:02
 */
class EntityTable extends ManagerEntityController {

    private $panelBuilder;
    private $menubuilder;

    function __construct() {
        $this->panelBuilder = new JsonDefaultTemplateFactory;
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

        $this->panelBuilder->setHtmlTemplateLoader( $this->htmlTemplateLoader );
        $this->panelBuilder->setDbconnection($this->dbconnection);
        $this->panelBuilder->setRouter($this->router);
        $this->panelBuilder->setJsonloader($this->jsonloader);
        $this->panelBuilder->setParameters($this->getParameters);
        $this->panelBuilder->setAction($this->router->make_url( Router::ROUTE_OFFICE_ENTITY_DASHBOARD, 'res='.$this->getParameters['res'] ));

		$this->title = $this->setup->getAppNameForPageTitle() . ' :: Office table';
		
		$this->menucontainer    = array( $this->menubuilder->createMenu() );
		$this->leftcontainer    = array();
		$this->centralcontainer = array( $this->panelBuilder->getWidePanel($this->resource) );
	}

}
