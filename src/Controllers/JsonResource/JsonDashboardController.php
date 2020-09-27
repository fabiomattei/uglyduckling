<?php

namespace Fabiom\UglyDuckling\Controllers\JsonResource;

use Fabiom\UglyDuckling\Common\Controllers\JsonResourceBasicController;
use Fabiom\UglyDuckling\Common\Exceptions\ErrorPageException;
use Fabiom\UglyDuckling\Common\Json\JsonTemplates\Menu\MenuJsonTemplate;

/**
 * User: Fabio Mattei
 * Date: 31/10/2018
 * Time: 08:10
 */
class JsonDashboardController extends JsonResourceBasicController {

    public $menubuilder;
    public /* JsonTemplateFactoriesContainer */ $jsonTemplateFactoriesContainer;

    function __construct() {
        
    }

    /**
     * @throws GeneralException
     */
    public function getRequest() {
        $this->menubuilder = new MenuJsonTemplate($this->applicationBuilder, $this->pageStatus);
        
        $menuresource = $this->applicationBuilder->getJsonloader()->loadResource( $this->pageStatus->getSessionWrapper()->getSessionGroup() );

        // if resource->get->sessionupdates is set I need to update the session
        if ( isset($this->resource->get->sessionupdates) ) $this->pageStatus->updateSession( $this->resource->get->sessionupdates );

        $this->menubuilder->setMenuStructure( $menuresource );

        $this->title = $this->applicationBuilder->getAppNameForPageTitle() . ' :: Dashboard';

        $this->menucontainer    = array( $this->menubuilder->createMenu() );
        $this->leftcontainer    = array();
        $this->centralcontainer = ( $this->applicationBuilder->getHTMLBlock( $this->resource ) );
    }

    public function show_second_get_error_page() {
        throw new ErrorPageException('Error page exception function show_get_error_page()');
    }

}
