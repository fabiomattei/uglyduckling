<?php

namespace Fabiom\UglyDuckling\Controllers\Admin\Export;

use Fabiom\UglyDuckling\Common\Controllers\Controller;
use Fabiom\UglyDuckling\Common\Router\AdminRouter;
use Fabiom\UglyDuckling\Templates\Blocks\Menus\AdminMenu;
use Fabiom\UglyDuckling\Templates\Blocks\Sidebars\AdminSidebar;
use Fabiom\UglyDuckling\Common\Blocks\StaticTable;
use Fabiom\UglyDuckling\Common\Blocks\Button;

/**
 * This class gives a list of all entities loaded in to the system
 */
class AdminExportList extends Controller {
	
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
		$this->title = $this->applicationBuilder->getSetup()->getAppNameForPageTitle() . ' :: Admin Exports list';
		
		$table = new StaticTable;
        $table->setHtmlTemplateLoader( $this->applicationBuilder->getHtmlTemplateLoader() );
		$table->setTitle('Exports list');
		
		$table->addTHead();
		$table->addRow();
		$table->addHeadLineColumn('Name');
		$table->addHeadLineColumn('Type');
		$table->addHeadLineColumn(''); // adding one more for actions
		$table->closeRow();
		$table->closeTHead();
		
		$table->addTBody();
        foreach ( $this->applicationBuilder->getJsonloader()->getResourcesByType( 'export' ) as $res ) {
			$table->addRow();
			$table->addColumn($res->name);
			$table->addColumn($res->type);
			$table->addUnfilteredColumn( Button::get($this->applicationBuilder->getRouterContainer()->makeRelativeUrl( AdminRouter::ROUTE_ADMIN_EXPORT_VIEW, 'res='.$res->name ), 'View', Button::COLOR_GRAY.' '.Button::SMALL ) );
			$table->closeRow();
		}
		$table->closeTBody();
		
		$this->menucontainer    = array( new AdminMenu( $this->applicationBuilder->getSetup()->getAppNameForPageTitle(), AdminRouter::ROUTE_ADMIN_EXPORT_LIST ) );
		$this->leftcontainer    = array( new AdminSidebar( $this->applicationBuilder->getSetup()->getAppNameForPageTitle(), AdminRouter::ROUTE_ADMIN_EXPORT_LIST, $this->applicationBuilder->getRouterContainer() ) );
		$this->centralcontainer = array( $table );

        $this->templateFile = $this->applicationBuilder->getSetup()->getPrivateTemplateWithSidebarFileName();
	}

}
