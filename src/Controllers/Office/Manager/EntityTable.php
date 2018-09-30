<?php

namespace Firststep\Controllers\Office\Manager;

use Firststep\Common\Controllers\Controller;
use Firststep\Templates\Blocks\Menus\AdminMenu;
use Firststep\Templates\Blocks\Sidebars\AdminSidebar;
use Firststep\Common\Json\JsonBlockParser;
use Firststep\Common\Blocks\StaticTable;
use Firststep\Common\Blocks\Button;
use Firststep\Common\Router\Router;
use Firststep\Common\Database\QueryExecuter;
use Firststep\Common\Builders\QueryBuilder;
use Firststep\Common\Builders\TableBuilder;
use Firststep\Common\Builders\MenuBuilder;

/**
 * User: fabio
 * Date: 16/08/2018
 * Time: 12:02
 */
class EntityTable extends Controller {

	public $get_validation_rules = array( 'res' => 'required|max_len,50' );
    public $get_filter_rules     = array( 'res' => 'trim' );

    function __construct() {
		$this->queryExecuter = new QueryExecuter;
		$this->queryBuilder = new QueryBuilder;
		$this->tableBuilder = new TableBuilder;
    }
	
    /**
     * Overwrite parent showPage method in order to add the functionality of loading a json resource.
     */
    public function showPage() {
		$this->jsonloader->loadIndex();
		parent::showPage(); 
    }
	
    /**
     * @throws GeneralException
     */
	public function getRequest() {
		$this->resource = $this->jsonloader->loadResource( $this->getParameters['res'] );
		$menuresource = $this->jsonloader->loadResource( $this->sessionWrapper->getSessionGroup() );
		$menubuilder = new MenuBuilder( $menuresource, $this->router );
		
		$this->queryExecuter->setDBH( $this->dbconnection->getDBH() );
	    $this->queryExecuter->setQueryBuilder( $this->queryBuilder );
	    $this->queryExecuter->setQueryStructure( $this->resource->query );
	    // $this->queryExecuter->setParameters( $parameters )
		$entities = $this->queryExecuter->executeQuery();
		
		$this->tableBuilder->setRouter( $this->router );
		$this->tableBuilder->setTableStructure( $this->resource->table );
		$this->tableBuilder->setEntities( $entities );
		
		$this->title = $this->setup->getAppNameForPageTitle() . ' :: Office table';
		
		$this->menucontainer    = array( $menubuilder->createMenu() );
		$this->leftcontainer    = array( new AdminSidebar( $this->setup->getAppNameForPageTitle(), Router::ROUTE_ADMIN_ENTITY_LIST, $this->router ) );
		$this->centralcontainer = array( $this->tableBuilder->createTable() );
	}

}
