<?php

namespace Fabiom\UglyDuckling\Controllers\Office\Manager;

use Fabiom\UglyDuckling\Common\Controllers\ManagerEntityController;
use Fabiom\UglyDuckling\Common\Json\JsonTemplates\Info\InfoJsonTemplate;
use Fabiom\UglyDuckling\Common\Json\JsonTemplates\MenuBuilder;

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

        $this->infoBuilder->setRouter( $this->router );
        $this->infoBuilder->setResource( $this->resource );
        $this->infoBuilder->setParameters( $this->internalGetParameters );
        $this->infoBuilder->setDbconnection( $this->dbconnection );
        $this->infoBuilder->setHtmlTemplateLoader( $this->htmlTemplateLoader );
		
		$this->title = $this->setup->getAppNameForPageTitle() . ' :: Office info';
	
		$this->menucontainer    = array( $this->menubuilder->createMenu() );
		$this->leftcontainer    = array();
		$this->centralcontainer = array( $this->infoBuilder->createInfo() );
	}

    public function show_second_get_error_page() {
        throw new ErrorPageException('Error page exception function show_get_error_page()');
    }

}
