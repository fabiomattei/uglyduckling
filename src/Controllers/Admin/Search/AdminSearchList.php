<?php

/**
 * Created by Fabio Mattei
 * 
 * Date: 01/11/18
 * Time: 6.07
 */

namespace Fabiom\UglyDuckling\Controllers\Admin\Search;

use Fabiom\UglyDuckling\Common\Controllers\AdminController;
use Fabiom\UglyDuckling\Common\Router\AdminRouter;
use Fabiom\UglyDuckling\Templates\Blocks\Menus\AdminMenu;
use Fabiom\UglyDuckling\Templates\Blocks\Sidebars\AdminSidebar;
use Fabiom\UglyDuckling\Common\Blocks\StaticTable;
use Fabiom\UglyDuckling\Common\Blocks\Button;

class AdminSearchList extends AdminController {

    /**
     * @throws GeneralException
     */
    public function getRequest() {
        $this->title = $this->applicationBuilder->getSetup()->getAppNameForPageTitle() . ' :: Admin Exports list';

        $table = new StaticTable;
        $table->setHtmlTemplateLoader( $this->applicationBuilder->getHtmlTemplateLoader() );
        $table->setTitle('Search list');

        $table->addTHead();
        $table->addRow();
        $table->addHeadLineColumn('Name');
        $table->addHeadLineColumn('Type');
        $table->addHeadLineColumn(''); // adding one more for actions
        $table->closeRow();
        $table->closeTHead();

        $table->addTBody();
        foreach ( $this->applicationBuilder->getJsonloader()->getResourcesByType( 'search' ) as $res ) {
            $table->addRow();
            $table->addColumn($res->name);
            $table->addColumn($res->type);
            $table->addUnfilteredColumn( Button::get($this->applicationBuilder->getRouterContainer()->makeRelativeUrl( AdminRouter::ROUTE_ADMIN_SEARCH_VIEW, 'res='.$res->name ), 'View', Button::COLOR_GRAY.' '.Button::SMALL ) );
            $table->closeRow();
        }
        $table->closeTBody();

        $this->menucontainer    = array( new AdminMenu( $this->applicationBuilder->getSetup()->getAppNameForPageTitle(), AdminRouter::ROUTE_ADMIN_SEARCH_LIST ) );
        $this->leftcontainer    = array( new AdminSidebar( $this->applicationBuilder->getSetup()->getAppNameForPageTitle(), AdminRouter::ROUTE_ADMIN_SEARCH_LIST, $this->applicationBuilder->getRouterContainer() ) );
        $this->centralcontainer = array( $table );

        $this->templateFile = $this->applicationBuilder->getSetup()->getPrivateTemplateWithSidebarFileName();
    }

}
