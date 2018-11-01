<?php

namespace Firststep\Controllers\Office;

use Firststep\Common\Controllers\Controller;

/**
 * 
 */
class EntityDashboard extends ManagerEntityController {

    function __construct() {
        $this->queryExecuter = new QueryExecuter;
        $this->queryBuilder = new QueryBuilder;
        $this->infoBuilder = new InfoBuilder;
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
        $this->queryExecuter->setGetParameters( $this->internalGetParameters );

        $result = $this->queryExecuter->executeQuery();
        $entity = $result->fetch();

        $this->infoBuilder->setFormStructure( $this->resource->get->info );
        $this->infoBuilder->setEntity( $entity );

        $this->title = $this->setup->getAppNameForPageTitle() . ' :: Office form';

        $this->menucontainer    = array( $this->menubuilder->createMenu() );
        $this->leftcontainer    = array( new AdminSidebar( $this->setup->getAppNameForPageTitle(), Router::ROUTE_ADMIN_ENTITY_LIST, $this->router ) );
        $this->centralcontainer = array( $this->infoBuilder->createInfo() );
    }

    public function show_second_get_error_page() {
        throw new ErrorPageException('Error page exception function show_get_error_page()');
    }

}
