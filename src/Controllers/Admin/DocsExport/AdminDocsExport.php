<?php

namespace Fabiom\UglyDuckling\Controllers\Admin\DocsExport;

use Fabiom\UglyDuckling\Common\Blocks\Button;
use Fabiom\UglyDuckling\Common\Blocks\StaticTable;
use Fabiom\UglyDuckling\Common\Controllers\AdminController;
use Fabiom\UglyDuckling\Common\Router\AdminRouter;
use Fabiom\UglyDuckling\Templates\Blocks\Menus\AdminMenu;
use Fabiom\UglyDuckling\Templates\Blocks\Sidebars\AdminSidebar;
class AdminDocsExport extends AdminController {

    /**
     * @throws GeneralException
     */
    public function getRequest() {
        $this->title = $this->applicationBuilder->getSetup()->getAppNameForPageTitle() . ' :: Admin Docs export';

        $table = new StaticTable;
        $table->setHtmlTemplateLoader( $this->applicationBuilder->getHtmlTemplateLoader() );
        $table->setTitle('Groups list');

        $table->addTHead();
        $table->addRow();
        $table->addHeadLineColumn('Name');
        $table->addHeadLineColumn('Type');
        $table->addHeadLineColumn(''); // adding one more for actions
        $table->closeRow();
        $table->closeTHead();

        $table->addTBody();
        foreach ( $this->applicationBuilder->getJsonloader()->getResourcesByType( 'group' ) as $res ) {
            $table->addRow();
            $table->addColumn($res->name);
            $table->addColumn($res->type);
            $table->addUnfilteredColumn(
                Button::get($this->applicationBuilder->getRouterContainer()->makeRelativeUrl( AdminRouter::ROUTE_ADMIN_GROUP_VIEW, 'res='.$res->name ), 'View', Button::COLOR_GRAY.' '.Button::SMALL ) . ' ' .
                Button::get($this->applicationBuilder->getRouterContainer()->makeRelativeUrl( AdminRouter::ROUTE_ADMIN_GROUP_DOC, 'res='.$res->name ), 'Doc', Button::COLOR_GRAY.' '.Button::SMALL )
            );
            $table->closeRow();
        }
        $table->closeTBody();

        $this->menucontainer    = array( new AdminMenu( $this->applicationBuilder->getSetup()->getAppNameForPageTitle(), AdminRouter::ROUTE_ADMIN_GROUP_LIST ) );
        $this->leftcontainer    = array( new AdminSidebar( $this->applicationBuilder->getSetup()->getAppNameForPageTitle(), AdminRouter::ROUTE_ADMIN_GROUP_LIST, $this->applicationBuilder->getRouterContainer() ) );
        $this->centralcontainer = array( $table );

        $this->templateFile = $this->applicationBuilder->getSetup()->getPrivateTemplateWithSidebarFileName();
    }

}
