<?php

namespace Fabiom\UglyDuckling\Controllers\Admin\DocsExport;

use Fabiom\UglyDuckling\Common\Blocks\BaseHtmlDoc;
use Fabiom\UglyDuckling\Common\Blocks\Button;
use Fabiom\UglyDuckling\Common\Blocks\StaticTable;
use Fabiom\UglyDuckling\Common\Blocks\BaseHTMLInfo;
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

        $docsList = [];
        foreach ( $this->applicationBuilder->getJsonloader()->getResourcesByType( 'group' ) as $jsonGroupName ) {
            $jsonGroup = $this->applicationBuilder->getJsonloader()->loadResource($jsonGroupName->name);

            $doc = new BaseHtmlDoc;
            $doc->h1('Group: ' . $jsonGroup->name);
            if ( isset($jsonGroup->docs) and is_array($jsonGroup->docs) ) {
                foreach ( $jsonGroup->docs as $paragraph) {
                    $doc->paragraph($paragraph, 12);
                }
            }

            $doc->h3('Menu structure');
            $doc->openTable();
            $doc->openRow();
            $doc->th('Menu');
            $doc->th('Sub-menu');
            $doc->closeRow();

            if ( isset($jsonGroup->menu) and is_array($jsonGroup->menu) ) {
                foreach ( $jsonGroup->menu as $menu) {
                    $doc->openRow();
                    $doc->td( $menu->label );
                    $doc->td( '');
                    $doc->closeRow();
                    if ( isset($menu->submenu) and is_array($menu->submenu) ) {
                        foreach ( $menu->submenu as $submenuitem) {
                            $doc->openRow();
                            $doc->td( '');
                            $doc->td( $submenuitem->label );
                            $doc->closeRow();
                        }
                    }
                }
            }
            $doc->closeTable();

            $docsList[] = $doc;

            if ( isset($jsonGroup->menu) and is_array($jsonGroup->menu) ) {
                foreach ( $jsonGroup->menu as $menu) {
                    if (isset($menu->resource)) {
                        $jsonResource = $this->applicationBuilder->getJsonloader()->loadResource($menu->resource);
                        $infoMenuItem = new BaseHtmlDoc;
                        $infoMenuItem->h3($menu->label);
                        $infoMenuItem->paragraph($GLOBALS['myDocFunctions'][$jsonResource->metadata->type]($jsonResource, $this->applicationBuilder->getJsonloader()), 12);

                        if ( isset($jsonResource->description) and is_string($jsonResource->description) ) {
                            $doc->paragraph($jsonResource->description, 12);
                        }
                        if ( isset($jsonResource->docs) and is_array($jsonResource->docs) ) {
                            foreach ( $jsonResource->docs as $paragraph) {
                                $doc->paragraph($paragraph, 12);
                            }
                        }

                        $docsList[] = $infoMenuItem;
                    }

                    if ( isset($menu->submenu) and is_array($menu->submenu) ) {
                        foreach ( $menu->submenu as $submenuitem) {
                            if (isset($submenuitem->resource)) {
                                if ( $this->applicationBuilder->getJsonloader()->isJsonResourceIndexedAndFileExists($submenuitem->resource) ) {
                                    $jsonResource = $this->applicationBuilder->getJsonloader()->loadResource($submenuitem->resource);
                                    $infoMenuItem = new BaseHtmlDoc;
                                    $infoMenuItem->h3($menu->label.': '.$submenuitem->label);
                                    $infoMenuItem->paragraph($GLOBALS['myDocFunctions'][$jsonResource->metadata->type]($jsonResource, $this->applicationBuilder->getJsonloader()), 12);

                                    if ( isset($jsonResource->description) and is_string($jsonResource->description) ) {
                                        $doc->paragraph($jsonResource->description, 12);
                                    }
                                    if ( isset($jsonResource->docs) and is_array($jsonResource->docs) ) {
                                        foreach ( $jsonResource->docs as $paragraph) {
                                            $doc->paragraph($paragraph, 12);
                                        }
                                    }

                                    $docsList[] = $infoMenuItem;
                                } else {
                                    echo "error ".$submenuitem->resource;
                                }
                            }
                        }
                    }
                }
            }
        }

        $this->menucontainer    = array( new AdminMenu( $this->applicationBuilder->getSetup()->getAppNameForPageTitle(), AdminRouter::ROUTE_ADMIN_GROUP_LIST ) );
        $this->leftcontainer    = array( new AdminSidebar( $this->applicationBuilder->getSetup()->getAppNameForPageTitle(), AdminRouter::ROUTE_ADMIN_GROUP_LIST, $this->applicationBuilder->getRouterContainer() ) );
        $this->centralcontainer = $docsList;

        $this->templateFile = $this->applicationBuilder->getSetup()->getPrivateTemplateWithSidebarFileName();
    }

}
