<?php

/**
 * Created by Fabio
 * Date: 01/11/18
 * Time: 9.34
 */

namespace Firststep\Controllers\Office\Manager;

use Firststep\Common\Builders\ChartjsBuilder;
use Firststep\Common\Controllers\ManagerEntityController;
use Firststep\Templates\Blocks\Sidebars\AdminSidebar;
use Firststep\Common\Router\Router;
use Firststep\Common\Database\QueryExecuter;
use Firststep\Common\Builders\QueryBuilder;
use Firststep\Common\Builders\MenuBuilder;

class EntityChart extends ManagerEntityController {

    private $queryExecuter;
    private $queryBuilder;
    private $chartjsBuilder;
    private $menubuilder;

    function __construct() {
        $this->queryExecuter = new QueryExecuter;
        $this->queryBuilder = new QueryBuilder;
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

        $this->queryExecuter->setDBH( $this->dbconnection->getDBH() );
        $this->queryExecuter->setQueryBuilder( $this->queryBuilder );
        $this->queryExecuter->setQueryStructure( $this->resource->get->query );
        // $this->queryExecuter->setGetParameters( $this->internalGetParameters ); TODO check, if there are no parameters it gets null instead of an array

        $result = $this->queryExecuter->executeQuery();

        $this->chartjsBuilder->setChartStructure( $this->resource->get->chart );
        $this->chartjsBuilder->setEntities( $result );

        $this->title = $this->setup->getAppNameForPageTitle() . ' :: Office chart';

        $this->menucontainer    = array( $this->menubuilder->createMenu() );
        $this->leftcontainer    = array( new AdminSidebar( $this->setup->getAppNameForPageTitle(), Router::ROUTE_ADMIN_ENTITY_LIST, $this->router ) );
        $this->centralcontainer = array( $this->chartjsBuilder->createChart() );
    }

    public function show_second_get_error_page() {
        throw new ErrorPageException('Error page exception function show_get_error_page()');
    }

}
