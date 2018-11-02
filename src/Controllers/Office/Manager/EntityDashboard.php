<?php

namespace Firststep\Controllers\Office\Manager;

use Firststep\Common\Blocks\EmptyBlock;
use Firststep\Common\Blocks\RowBlock;
use Firststep\Common\Builders\PanelBuilder;
use Firststep\Common\Controllers\Controller;

use Firststep\Common\Controllers\ManagerEntityController;
use Firststep\Common\Router\Router;
use Firststep\Common\Database\QueryExecuter;
use Firststep\Common\Builders\QueryBuilder;
use Firststep\Common\Builders\MenuBuilder;

/**
 * 
 */
class EntityDashboard extends ManagerEntityController {

    private $panelBuilder;

    function __construct() {
        $this->queryExecuter = new QueryExecuter;
        $this->queryBuilder = new QueryBuilder;
        $this->menubuilder = new MenuBuilder;
        $this->panelBuilder = new PanelBuilder;
    }

    /**
     * @throws GeneralException
     */
    public function getRequest() {
        $menuresource = $this->jsonloader->loadResource( $this->sessionWrapper->getSessionGroup() );
        $this->menubuilder->setMenuStructure( $menuresource );
        $this->menubuilder->setRouter( $this->router );

        $this->panelBuilder->setDbconnection($this->dbconnection);
        $this->panelBuilder->setRouter($this->router);
        $this->panelBuilder->setJsonloader($this->jsonloader);
        $this->panelBuilder->setParameters($this->getParameters);
        $this->panelBuilder->setAction($this->router->make_url( Router::ROUTE_OFFICE_ENTITY_DASHBOARD, 'res='.$this->getParameters['res'] ));

        $fieldRows = array();

        foreach ($this->resource->panels as $panel) {
            if( !array_key_exists($panel->row, $fieldRows) ) $fieldRows[$panel->row] = array();
            $fieldRows[$panel->row][] = $panel;
        }

        $rowcontainer = array();

        foreach ($fieldRows as $row) {
            $rowBlock = new RowBlock;
            foreach ($row as $panel) {
                $rowBlock->addBlock( $this->panelBuilder->getPanel($panel) );
            }
            $rowcontainer[] = $rowBlock;
        }

        $this->title = $this->setup->getAppNameForPageTitle() . ' :: Dashboard';

        $this->menucontainer    = array( $this->menubuilder->createMenu() );
        $this->leftcontainer    = array();
        $this->centralcontainer = ( isset($rowcontainer[0]) ? $rowcontainer[0] : array() );
        $this->secondcentralcontainer = ( isset($rowcontainer[1]) ? $rowcontainer[1] : array() );
        $this->thirdcentralcontainer = ( isset($rowcontainer[2]) ? $rowcontainer[2] : array() );
    }

    public function show_second_get_error_page() {
        throw new ErrorPageException('Error page exception function show_get_error_page()');
    }

}
