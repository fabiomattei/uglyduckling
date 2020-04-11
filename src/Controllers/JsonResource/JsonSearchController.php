<?php

namespace Fabiom\UglyDuckling\Controllers\JsonResource;

use Fabiom\UglyDuckling\Common\Controllers\JsonResourceBasicController;
use Fabiom\UglyDuckling\Templates\Blocks\Sidebars\AdminSidebar;
use Fabiom\UglyDuckling\Common\Router\Router;
use Fabiom\UglyDuckling\Common\Json\JsonTemplates\Form\FormJsonTemplate;
use Fabiom\UglyDuckling\Common\Json\JsonTemplates\Table\TableJsonTemplate;
use Fabiom\UglyDuckling\Common\Json\JsonTemplates\Menu\MenuJsonTemplate;

/**
 * User: Fabio
 * Date: 11/09/2018
 * Time: 22:34
 */
class JsonSearchController extends JsonResourceBasicController {

    private $formBuilder;
    private $tableBuilder;
    private $menubuilder;

    function __construct() {
		$this->formBuilder = new FormJsonTemplate;
		$this->tableBuilder = new TableJsonTemplate;
		$this->menubuilder = new MenuJsonTemplate;
    }

    /**
     * @throws GeneralException
     */
	public function getRequest() {
	    $menuresource = $this->jsonloader->loadResource( $this->sessionWrapper->getSessionGroup() );

		$this->menubuilder->setMenuStructure( $menuresource );
		$this->menubuilder->setRouter( $this->routerContainer );
        $this->menubuilder->setHtmlTemplateLoader( $this->htmlTemplateLoader );

		$this->formBuilder->setHtmlTemplateLoader( $this->htmlTemplateLoader );
        $this->formBuilder->setResource( $this->resource );
        $this->formBuilder->setAction($this->routerContainer->makeRelativeUrl( Router::ROUTE_OFFICE_ENTITY_SEARCH, 'res='.$this->getParameters['res'] ));

		$this->tableBuilder->setHtmlTemplateLoader( $this->htmlTemplateLoader );
        $this->tableBuilder->setRouter( $this->routerContainer );
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
		$this->menubuilder->setRouter( $this->routerContainer );

		$this->formBuilder->setHtmlTemplateLoader( $this->htmlTemplateLoader );
        $this->formBuilder->setResource( $this->resource );
        $this->formBuilder->setAction($this->routerContainer->makeRelativeUrl( Router::ROUTE_OFFICE_ENTITY_SEARCH, 'res='.$this->getParameters['res'] ));

		$this->tableBuilder->setHtmlTemplateLoader( $this->htmlTemplateLoader );
        $this->tableBuilder->setRouter( $this->routerContainer );
        $this->tableBuilder->setJsonloader($this->jsonloader);
        $this->tableBuilder->setResource( $this->resource );
        $this->tableBuilder->setParameters( $this->postParameters );
        $this->tableBuilder->setDbconnection( $this->dbconnection );
        $this->tableBuilder->setMethod(TableJsonTemplate::POST_METHOD);
		
		$this->title = $this->setup->getAppNameForPageTitle() . ' :: Office search';
	
		$this->menucontainer    = array( $this->menubuilder->createMenu() );
		$this->leftcontainer    = array();
		$this->centralcontainer = array( $this->formBuilder->createForm() );
		$this->secondcentralcontainer = array( $this->tableBuilder->createTable() );
	}

}
