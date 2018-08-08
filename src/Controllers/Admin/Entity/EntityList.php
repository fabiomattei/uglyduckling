<?php

namespace Firststep\Controllers\Admin\Entity;

use Firststep\Common\Controllers\Controller;
use Firststep\Common\Json\JsonBlockParser;
use Firststep\Common\Blocks\StaticTable;

/**
 * This class gives a list of all entities loaded in to the system
 */
class EntityList extends Controller {
	
    /**
     * Overwrite parent showPage method in order to add the functionality of loading a json resource.
     */
    public function showPage() {
		$this->jsonloader->loadIndex();
		parent::showPage(); 
    }
	
    /**
     * @throws GeneralException
     *
     * $this->getParameters['res'] resource key index
     */
	public function getRequest() {
		$this->title = $this->setup->getAppNameForPageTitle() . ' :: Admin Entites list';
		
		$table = new StaticTable;
		$table->setTitle('Entities list');
		
		$table->addTHead();
		$table->addRow();
		$table->addHeadLineColumn('Name');
		$table->addHeadLineColumn('Type');
		$table->addHeadLineColumn(''); // adding one more for actions
		$table->closeRow();
		$table->closeTHead();
		
		$table->addTBody();
		foreach ( $this->jsonloader->getResourcesIndex() as $res ) {
			if ( $res->type === 'entity' ) {
				$table->addRow();
				$table->addColumn($res->name);
				$table->addColumn($res->type);
				$table->closeRow();
			}
		}
		$table->closeTBody();
		
		$this->centralcontainer = array( $table );
	}

}
