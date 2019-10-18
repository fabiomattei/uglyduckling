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
		$this->menubuilder->setRouter( $this->routerContainer );

        $this->jsonTemplateFactoriesContainer->setHtmlTemplateLoader( $this->htmlTemplateLoader );
        $this->jsonTemplateFactoriesContainer->setJsonloader($this->jsonloader);
        $this->jsonTemplateFactoriesContainer->setDbconnection($this->dbconnection);
        $this->jsonTemplateFactoriesContainer->setRouter($this->routerContainer);
        $this->jsonTemplateFactoriesContainer->setJsonloader($this->jsonloader);
        $this->jsonTemplateFactoriesContainer->setParameters($this->getParameters);
        $this->jsonTemplateFactoriesContainer->setLogger($this->logger);
        $this->jsonTemplateFactoriesContainer->setAction($this->routerContainer->make_url( Router::ROUTE_OFFICE_ENTITY_TABLE, 'res='.$this->getParameters['res'] ));

        $this->title = $this->setup->getAppNameForPageTitle() . ' :: Office table';
		
		$this->menucontainer    = array( $this->menubuilder->createMenu() );
		$this->leftcontainer    = array();
		$this->centralcontainer = array( $this->jsonTemplateFactoriesContainer->getHTMLBlock( $this->resource ) );
	}

}
