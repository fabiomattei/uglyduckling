<?php

namespace Fabiom\UglyDuckling\Controllers\Admin\Document;

use Fabiom\UglyDuckling\Common\Controllers\Controller;
use Fabiom\UglyDuckling\Templates\Blocks\Menus\AdminMenu;
use Fabiom\UglyDuckling\Templates\Blocks\Sidebars\AdminSidebar;
use Fabiom\UglyDuckling\Common\Blocks\StaticTable;
use Fabiom\UglyDuckling\Common\Blocks\Button;
use Fabiom\UglyDuckling\Common\Router\Router;

/**
 * 
 */
class AdminDocumentsList extends Controller {
	
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
		$this->title = $this->setup->getAppNameForPageTitle() . ' :: Admin Documents list';
		
		$table = new StaticTable;
        $table->setHtmlTemplateLoader( $this->htmlTemplateLoader );
		$table->setTitle('Documents list');
		
		$table->addTHead();
		$table->addRow();
		$table->addHeadLineColumn('Name');
		$table->addHeadLineColumn('Type');
		$table->addHeadLineColumn(''); // adding one more for actions
		$table->closeRow();
		$table->closeTHead();
		
		$table->addTBody();
        foreach ( $this->jsonloader->getResourcesByType( 'document' ) as $res ) {
			$table->addRow();
			$table->addColumn($res->name);
			$table->addColumn($res->type);
			$table->addUnfilteredColumn( Button::get($this->routerContainer->make_url( Router::ROUTE_ADMIN_DOCUMENT_VIEW, 'res='.$res->name ), 'View', Button::COLOR_GRAY.' '.Button::SMALL ) );
			$table->closeRow();
		}
		$table->closeTBody();
		
		$this->menucontainer    = array( new AdminMenu( $this->setup->getAppNameForPageTitle(), Router::ROUTE_ADMIN_DOCUMENT_LIST ) );
		$this->leftcontainer    = array( new AdminSidebar( $this->setup->getAppNameForPageTitle(), Router::ROUTE_ADMIN_DOCUMENT_LIST, $this->routerContainer ) );
		$this->centralcontainer = array( $table );

        $this->templateFile = $this->setup->getPrivateTemplateWithSidebarFileName();
	}
	
}
