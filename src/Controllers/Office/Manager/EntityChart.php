<?php

/**
 * Created by Fabio Mattei
 * Date: 01/11/18
 * Time: 9.34
 */

namespace Firststep\Controllers\Office\Manager;

use Firststep\Common\Builders\ChartjsBuilder;
use Firststep\Common\Controllers\ManagerEntityController;
use Firststep\Templates\Blocks\Sidebars\AdminSidebar;
use Firststep\Common\Router\Router;
use Firststep\Common\Builders\MenuBuilder;

class EntityChart extends ManagerEntityController {

    private $chartjsBuilder;
    private $menubuilder;

    function __construct() {
        $this->chartjsBuilder = new ChartjsBuilder;
        $this->menubuilder = new MenuBuilder;
    }

    /**
     * @throws GeneralException
     */
    public function getRequest() {
        $menuresource = $this->jsonloader->loadResource( $this->sessionWrapper->getSessionGroup() );
        $this->menubuilder->setMenuStructure( $menuresource );
        $this->menubuilder->setRouter( $this->router );

        $this->chartjsBuilder->setRouter( $this->router );
        $this->chartjsBuilder->setResource( $this->resource );
        $this->chartjsBuilder->setParameters( $this->internalGetParameters );
        $this->chartjsBuilder->setDbconnection( $this->dbconnection );

        $this->title = $this->setup->getAppNameForPageTitle() . ' :: Office chart';

        $this->menucontainer    = array( $this->menubuilder->createMenu() );
        $this->leftcontainer    = array();
        $this->centralcontainer = array( $this->chartjsBuilder->createChart() );
    }

    public function show_second_get_error_page() {
        throw new ErrorPageException('Error page exception function show_get_error_page()');
    }

}
