<?php

/**
 * Created by Fabio Mattei
 * Date: 01/11/18
 * Time: 9.34
 */

namespace Fabiom\UglyDuckling\Controllers\JsonResource;

use Fabiom\UglyDuckling\Common\Controllers\JsonResourceBasicController;
use Fabiom\UglyDuckling\Common\Router\ResourceRouter;
use Fabiom\UglyDuckling\Common\Json\JsonTemplates\Menu\MenuJsonTemplate;

class JsonChartController extends JsonResourceBasicController {

    private $menubuilder;

    function __construct() {
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

        $this->jsonTemplateFactoriesContainer->setHtmlTemplateLoader( $this->htmlTemplateLoader );
        $this->jsonTemplateFactoriesContainer->setJsonloader($this->jsonloader);
        $this->jsonTemplateFactoriesContainer->setSessionWrapper( $this->getSessionWrapper() );
        $this->jsonTemplateFactoriesContainer->setServerWrapper($this->serverWrapper);
        $this->jsonTemplateFactoriesContainer->setDbconnection($this->dbconnection);
        $this->jsonTemplateFactoriesContainer->setRouter($this->routerContainer);
        $this->jsonTemplateFactoriesContainer->setJsonloader($this->jsonloader);
        $this->jsonTemplateFactoriesContainer->setParameters($this->getParameters);
        $this->jsonTemplateFactoriesContainer->setLogger($this->logger);
        $this->jsonTemplateFactoriesContainer->setAction($this->routerContainer->makeRelativeUrl( ResourceRouter::ROUTE_OFFICE_ENTITY_CHART, 'res='.$this->getParameters['res'] ));

        $this->title = $this->setup->getAppNameForPageTitle() . ' :: Office chart';

        $this->menucontainer    = array( $this->menubuilder->createMenu() );
        $this->leftcontainer    = array();
        $this->centralcontainer = array( $this->jsonTemplateFactoriesContainer->getHTMLBlock( $this->resource ) );
    }

    public function show_second_get_error_page() {
        throw new ErrorPageException('Error page exception function show_get_error_page()');
    }

}
