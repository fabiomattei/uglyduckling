<?php

namespace Firststep\Controllers\Admin\Report;

use Firststep\Common\Controllers\Controller;
use Firststep\Templates\Blocks\Menus\AdminMenu;
use Firststep\Templates\Blocks\Sidebars\AdminSidebar;
use Firststep\Common\Json\JsonBlockParser;
use Firststep\Common\Blocks\StaticTable;
use Firststep\Common\Blocks\Button;
use Firststep\Common\Router\Router;

/**
 * This class gives a list of all entities loaded in to the system
 */
class ReportList extends Controller {
	
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
		$this->title = $this->setup->getAppNameForPageTitle() . ' :: Admin Reports list';
		
		$table = new StaticTable;
		$table->setTitle('Reports list');
		
		$table->addTHead();
		$table->addRow();
		$table->addHeadLineColumn('Name');
		$table->addHeadLineColumn('Type');
		$table->addHeadLineColumn(''); // adding one more for actions
		$table->closeRow();
		$table->closeTHead();
		
		$table->addTBody();
		foreach ( $this->jsonloader->getResourcesIndex() as $res ) {
			if ( $res->type === 'report' ) {
				$table->addRow();
				$table->addColumn($res->name);
				$table->addColumn($res->type);
				$table->addUnfilteredColumn( Button::get($this->router->make_url( Router::ROUTE_ADMIN_ENTITY_VIEW, 'res='.$res->name ), 'View', Button::COLOR_GRAY.' '.Button::SMALL ) );
				$table->closeRow();
			}
		}
		$table->closeTBody();
		
		$this->menucontainer    = array( new AdminMenu( $this->setup->getAppNameForPageTitle(), Router::ROUTE_ADMIN_ENTITY_LIST ) );
		$this->leftcontainer    = array( new AdminSidebar( $this->setup->getAppNameForPageTitle(), Router::ROUTE_ADMIN_REPORT_LIST, $this->router ) );
		$this->centralcontainer = array( $table );
	}

}
