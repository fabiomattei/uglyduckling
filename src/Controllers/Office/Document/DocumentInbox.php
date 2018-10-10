<?php

namespace Firststep\Controllers\Office\Document;

use Firststep\Common\Controllers\Controller;
use Firststep\Templates\Blocks\Menus\AdminMenu;
use Firststep\Templates\Blocks\Sidebars\AdminSidebar;
use Firststep\Common\Json\JsonBlockParser;
use Firststep\Common\Blocks\StaticTable;
use Firststep\Common\Blocks\Button;
use Firststep\Common\Router\Router;
use Firststep\Common\Database\QueryExecuter;
use Firststep\Common\Builders\QueryBuilder;
use Firststep\Common\Builders\MenuBuilder;

/**
 * This page represent the inbox for all received documents.
 * It checks all documents that a user belonging to a specific group can receive and
 * query the database lokking for all documents at a "SENT" state.
 */
class DocumentInbox extends Controller {
	
    function __construct() {
		$this->queryExecuter = new QueryExecuter;
		$this->queryBuilder = new QueryBuilder;
		$this->menubuilder = new MenuBuilder;
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
		$this->title = $this->setup->getAppNameForPageTitle() . ' :: In box';
		
		$menuresource = $this->jsonloader->loadResource( $this->sessionWrapper->getSessionGroup() );
		$this->menubuilder->setMenuStructure( $menuresource );
		$this->menubuilder->setRouter( $this->router );
		
		$table = new StaticTable;
		$table->setTitle('Received documents');
		
		$table->addTHead();
		$table->addRow();
		$table->addHeadLineColumn('Object');
		$table->addHeadLineColumn('Type');
		$table->addHeadLineColumn(''); // adding one more column with no title for links connected to actions
		$table->closeRow();
		$table->closeTHead();
		
		$table->addTBody();
		foreach ( $this->jsonloader->getResourcesIndex() as $res ) {
			if ( $res->type === 'document' ) {
				$resource = $this->jsonloader->loadResource( $res->path );
				
				if ( in_array( $this->sessionWrapper->getSessionGroup(), $resource->destinationgroups ) ) {
					// This user can access the documents because he belongs to the right groups
					// I need to query the database to check if I have any document at the right status
					// for any document this user has access to
					
					$this->queryExecuter->setDBH( $this->dbconnection->getDBH() );
				    $this->queryExecuter->setQueryBuilder( $this->queryBuilder );
				    $this->queryExecuter->setQueryStructure( $this->resource->query );
				    $this->queryExecuter->setParameters( $parameters )
					$entities = $this->queryExecuter->executeQuery();
					
					
					
					$table->addRow();
					$table->addColumn($res->name);
					$table->addColumn($resource->title);
					$table->addUnfilteredColumn( 
						Button::get($this->router->make_url( Router::ROUTE_ADMIN_ENTITY_VIEW, 'res='.$res->name ), 'View', Button::COLOR_GRAY.' '.Button::SMALL ) 
					);
					$table->closeRow();
				}
			}
		}
		$table->closeTBody();
		
		$this->menucontainer    = array( $this->menubuilder->createMenu() );
		$this->leftcontainer    = array();
		$this->centralcontainer = array( $table );
	}

}
