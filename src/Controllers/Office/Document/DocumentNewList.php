<?php

namespace Firststep\Controllers\Office\Document;

use Firststep\Common\Controllers\Controller;
use Firststep\Common\Blocks\StaticTable;
use Firststep\Common\Blocks\Button;
use Firststep\Common\Router\Router;
use Firststep\Common\Database\DocumentDao;
use Firststep\Common\Json\JsonTemplates\MenuBuilder;

/**
 * This page gives to the users buttons in order to create all documents his group has permission to create
 */
class DocumentNewList extends Controller {
	
    function __construct() {
		$this->documentDao = new DocumentDao;
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
		$table->setTitle('List of all possible documents');
		
		$table->addTHead();
		$table->addRow();
		$table->addHeadLineColumn('Type');
		$table->addHeadLineColumn(''); // adding one more column with no title for links connected to actions
		$table->closeRow();
		$table->closeTHead();
		
		$table->addTBody();
		foreach ( $this->jsonloader->getResourcesIndex() as $res ) {
			if ( $res->type === 'document' ) {
				$resource = $this->jsonloader->loadResource( $res->name );
				
				if ( in_array( $this->sessionWrapper->getSessionGroup(), $resource->sourcegroups ) ) {
					// This user can access the documents because he belongs to the right groups
					// I need to query the database to check if I have any document at the right status
					// for any document this user has access to
					
					$table->addRow();
					$table->addColumn($resource->title);
					$table->addUnfilteredColumn( 
						Button::get($this->router->make_url( Router::ROUTE_OFFICE_DOCUMENT_NEW, 'res='.$resource->name ), 'New', Button::COLOR_GRAY.' '.Button::SMALL )
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
