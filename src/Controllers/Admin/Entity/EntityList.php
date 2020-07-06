<?php

namespace Fabiom\UglyDuckling\Controllers\Admin\Entity;

use Fabiom\UglyDuckling\Common\Controllers\Controller;
use Fabiom\UglyDuckling\Templates\Blocks\Menus\AdminMenu;
use Fabiom\UglyDuckling\Templates\Blocks\Sidebars\AdminSidebar;
use Fabiom\UglyDuckling\Common\Blocks\StaticTable;
use Fabiom\UglyDuckling\Common\Blocks\Button;
use Fabiom\UglyDuckling\Common\Router\ResourceRouter;

/**
 * This class gives a list of all entities loaded in to the system
 */
class EntityList extends Controller {
	
    /**
     * Overwrite parent showPage method in order to add the functionality of loading a json resource.
     */
    public function showPage() {
		$this->applicationBuilder->getJsonloader()->loadIndex();
		parent::showPage(); 
    }
	
    /**
     * @throws GeneralException
     */
	public function getRequest() {
		$this->title = $this->applicationBuilder->getSetup()->getAppNameForPageTitle() . ' :: Admin Entites list';
		
		$table = new StaticTable;
        $table->setHtmlTemplateLoader( $this->applicationBuilder->getHtmlTemplateLoader() );
		$table->setTitle('Entities list');
		
		$table->addTHead();
		$table->addRow();
		$table->addHeadLineColumn('Name');
		$table->addHeadLineColumn('Type');
		$table->addHeadLineColumn(''); // adding one more for actions
		$table->closeRow();
		$table->closeTHead();
		
		$table->addTBody();
		foreach ( $this->applicationBuilder->getJsonloader()->getResourcesByType( 'entity' ) as $res ) {
			$table->addRow();
			$table->addColumn($res->name);
			$table->addColumn($res->type);
			$table->addUnfilteredColumn( Button::get($this->applicationBuilder->getRouterContainer()->makeRelativeUrl( ResourceRouter::ROUTE_ADMIN_ENTITY_VIEW, 'res='.$res->name ), 'View', Button::COLOR_GRAY.' '.Button::SMALL ) );
			$table->closeRow();
		}
		$table->closeTBody();
		
		$this->menucontainer    = array( new AdminMenu( $this->applicationBuilder->getSetup()->getAppNameForPageTitle(), ResourceRouter::ROUTE_ADMIN_ENTITY_LIST ) );
		$this->leftcontainer    = array( new AdminSidebar( $this->applicationBuilder->getSetup()->getAppNameForPageTitle(), ResourceRouter::ROUTE_ADMIN_ENTITY_LIST, $this->applicationBuilder->getRouterContainer() ) );
		$this->centralcontainer = array( $table );

        $this->templateFile = $this->applicationBuilder->getSetup()->getPrivateTemplateWithSidebarFileName();
	}

}
