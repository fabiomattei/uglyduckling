<?php

namespace Firststep\Controllers\Office\Manager;

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

    function __construct() {
        $this->queryExecuter = new QueryExecuter;
        $this->queryBuilder = new QueryBuilder;
        $this->menubuilder = new MenuBuilder;
    }

    /**
     * @throws GeneralException
     */
    public function getRequest() {
        $menuresource = $this->jsonloader->loadResource( $this->sessionWrapper->getSessionGroup() );
        $this->menubuilder->setMenuStructure( $menuresource );
        $this->menubuilder->setRouter( $this->router );

        //$this->queryExecuter->setDBH( $this->dbconnection->getDBH() );
        //$this->queryExecuter->setQueryBuilder( $this->queryBuilder );
        //$this->queryExecuter->setQueryStructure( $this->resource->get->query );
        //$this->queryExecuter->setGetParameters( $this->internalGetParameters );

        //$result = $this->queryExecuter->executeQuery();
        //$entity = $result->fetch();

        //$this->infoBuilder->setFormStructure( $this->resource->get->info );
        //$this->infoBuilder->setEntity( $entity );

        $this->title = $this->setup->getAppNameForPageTitle() . ' :: Dashboard';

        $this->menucontainer    = array( $this->menubuilder->createMenu() );
        $this->leftcontainer    = array();
        $this->centralcontainer = array();
    }

    public function show_second_get_error_page() {
        throw new ErrorPageException('Error page exception function show_get_error_page()');
    }

}
