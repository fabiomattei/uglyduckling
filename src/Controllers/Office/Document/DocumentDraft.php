<?php

namespace Fabiom\UglyDuckling\Controllers\Office\Document;

use Fabiom\UglyDuckling\Common\Controllers\Controller;
use Fabiom\UglyDuckling\Common\Blocks\StaticTable;
use Fabiom\UglyDuckling\Common\Blocks\Button;
use Fabiom\UglyDuckling\Common\Router\Router;
use Fabiom\UglyDuckling\Common\Database\DocumentDao;
use Fabiom\UglyDuckling\Common\Json\JsonTemplates\MenuBuilder;

/**
 * This page represent the inbox for all received documents.
 * It checks all documents that a user belonging to a specific group can receive and
 * query the database lokking for all documents at a "SENT" state.
 */
class DocumentDraft extends Controller {

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
		$this->title = $this->setup->getAppNameForPageTitle() . ' :: Drafted documents';
	
		$menuresource = $this->jsonloader->loadResource( $this->sessionWrapper->getSessionGroup() );
		$this->menubuilder->setMenuStructure( $menuresource );
		$this->menubuilder->setRouter( $this->routerContainer );
	
		$this->documentDao->setDBH( $this->dbconnection->getDBH() );
	
		$table = new StaticTable;
		$table->setTitle('Drafted documents');
	
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
				$resource = $this->jsonloader->loadResource( $res->name );
			
				if ( $this->sessionWrapper->getSessionGroup() === $resource->destinationgroup ) {
					// This user can access the documents because he belongs to the right groups
					// I need to query the database to check if I have any document at the right status
					// for any document this user has access to
				
					$this->documentDao->setDBH( $this->dbconnection->getDBH() );
					$this->documentDao->setTableName( $resource->name );
					$entities = $this->documentDao->getGroupDraft( $resource->object, $this->sessionWrapper->getSessionGroup() );
				
					// printing all found entities in the table
					foreach ( $entities as $doc ) {
						$table->addRow();
						$object = '';
						foreach ( $resource->object as $obj ) {
							$object .= $doc->{$obj}.' ';
						}
						$table->addColumn($object);
						$table->addColumn($resource->title);
						$table->addUnfilteredColumn( 
							Button::get($this->routerContainer->make_url( Router::ROUTE_OFFICE_DOCUMENT_EDIT, 'res='.$resource->name.'&id='.$doc->id ), 'Edit', Button::COLOR_GRAY.' '.Button::SMALL ) .' ' .
							Button::get($this->routerContainer->make_url( Router::ROUTE_OFFICE_DOCUMENT_SEND, 'res='.$resource->name.'&id='.$doc->id ), 'Send', Button::COLOR_GRAY.' '.Button::SMALL ) .' ' .
							Button::get($this->routerContainer->make_url( Router::ROUTE_OFFICE_DOCUMENT_DELETE, 'res='.$resource->name.'&id='.$doc->id ), 'Del', Button::COLOR_GRAY.' '.Button::SMALL ) 
						);
						$table->closeRow();
					}
				}
			}
		}
		$table->closeTBody();
	
		$this->menucontainer    = array( $this->menubuilder->createMenu() );
		$this->leftcontainer    = array();
		$this->centralcontainer = array( $table );
	}

}
