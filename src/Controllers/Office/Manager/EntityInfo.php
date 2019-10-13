<?php

namespace Fabiom\UglyDuckling\Controllers\Office\Manager;

use Fabiom\UglyDuckling\Common\Controllers\ManagerEntityController;
use Fabiom\UglyDuckling\Common\Json\JsonTemplates\Info\InfoJsonTemplate;
use Fabiom\UglyDuckling\Common\Json\JsonTemplates\MenuBuilder;
use Fabiom\UglyDuckling\Common\Router\Router;

/**
 * User: Fabio
 * Date: 11/09/2018
 * Time: 22:34
 */
class EntityInfo extends ManagerEntityController {

    private $menubuilder;
    private $infoBuilder;

    function __construct() {
		$this->infoBuilder = new InfoJsonTemplate;
		$this->menubuilder = new MenuBuilder;
    }

    /**
     * @throws GeneralException
     */
	public function getRequest() {
		$menuresource = $this->jsonloader->loadResource( $this->sessionWrapper->getSessionGroup() );
		$this->menubuilder->setMenuStructure( $menuresource );
		$this->menubuilder->setRouter( $this->router );

        $this->jsonTemplateFactoriesContainer->setHtmlTemplateLoader( $this->htmlTemplateLoader );
        $this->jsonTemplateFactoriesContainer->setJsonloader($this->jsonloader);
        $this->jsonTemplateFactoriesContainer->setDbconnection($this->dbconnection);
        $this->jsonTemplateFactoriesContainer->setRouter($this->router);
        $this->jsonTemplateFactoriesContainer->setJsonloader($this->jsonloader);
        $this->jsonTemplateFactoriesContainer->setParameters($this->getParameters);
        $this->jsonTemplateFactoriesContainer->setLogger($this->logger);
        $this->jsonTemplateFactoriesContainer->setAction($this->router->make_url( Router::ROUTE_OFFICE_ENTITY_INFO, 'res='.$this->getParameters['res'] ));

        $this->title = $this->setup->getAppNameForPageTitle() . ' :: Office info';
	
		$this->menucontainer    = array( $this->menubuilder->createMenu() );
		$this->leftcontainer    = array();
		$this->centralcontainer = array( $this->jsonTemplateFactoriesContainer->getHTMLBlock( $this->resource ) );
	}

    public function show_second_get_error_page() {
        throw new ErrorPageException('Error page exception function show_get_error_page()');
    }

}
