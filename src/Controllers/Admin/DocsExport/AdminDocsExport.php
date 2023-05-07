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
        $chapterNumber = 1;
        foreach ( $this->applicationBuilder->getJsonloader()->getResourcesByType( 'group' ) as $jsonGroupName ) {
            $jsonGroup = $this->applicationBuilder->getJsonloader()->loadResource($jsonGroupName->name);

            $doc = new BaseHtmlDoc;
            $doc->h1($chapterNumber . ' Group: ' . $jsonGroup->doctitle ?? $jsonGroup->name ?? '');
            if ( isset($jsonGroup->docs) and is_array($jsonGroup->docs) ) {
                foreach ( $jsonGroup->docs as $paragraph) {
                    $doc->paragraph($paragraph);
                }
            }

            $doc->h3($chapterNumber . '.1 Menu structure');
            $doc->openTable( [ 'border' => 1 ] );
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

            $subChapterNumber = 2;
            if ( isset($jsonGroup->menu) and is_array($jsonGroup->menu) ) {
                foreach ( $jsonGroup->menu as $menu) {
                    if (isset($menu->resource)) {
                        $jsonResource = $this->applicationBuilder->getJsonloader()->loadResource($menu->resource);
                        $infoMenuItem = new BaseHtmlDoc;
                        $infoMenuItem->h2($chapterNumber . '.'.$subChapterNumber . ' Menu: '.$menu->label);
                        $items = $GLOBALS['myDocFunctions'][$jsonResource->metadata->type]($jsonResource, $this->applicationBuilder->getJsonloader(), $chapterNumber . '.'.$subChapterNumber, 1);
                        $docsList[] = $infoMenuItem;

                        if ( is_array($items)) {
                            $docsList = array_merge($docsList, $items);
                        } else {
                            array_push($docsList, $items);
                        }
                    }

                    $subSubChapterNumber =1;
                    if ( isset($menu->submenu) and is_array($menu->submenu) ) {
                        $infoMenuItem = new BaseHtmlDoc;
                        $infoMenuItem->h2($chapterNumber . '.'.$subChapterNumber . ' Menu: '.$menu->label);
                        $docsList[] = $infoMenuItem;
                        foreach ( $menu->submenu as $submenuitem) {
                            if (isset($submenuitem->resource)) {
                                if ( $this->applicationBuilder->getJsonloader()->isJsonResourceIndexedAndFileExists($submenuitem->resource) ) {
                                    $jsonResource = $this->applicationBuilder->getJsonloader()->loadResource($submenuitem->resource);
                                    $infoMenuItem = new BaseHtmlDoc;
                                    $infoMenuItem->h3($chapterNumber . '.'.$subChapterNumber .'.'.$subSubChapterNumber . ' Menu: '.$menu->label.' - Sub-Menu: '.$submenuitem->label);
                                    $items = $GLOBALS['myDocFunctions'][$jsonResource->metadata->type]($jsonResource, $this->applicationBuilder->getJsonloader(), $chapterNumber . '.'.$subChapterNumber .'.'.$subSubChapterNumber, 2);

                                    $docsList[] = $infoMenuItem;
                                    if ( is_array($items)) {
                                        $docsList = array_merge($docsList, $items);
                                    } else {
                                        array_push($docsList, $items);
                                    }
                                } else {
                                    echo "error ".$submenuitem->resource;
                                }
                            }
                            $subSubChapterNumber +=1;
                        }
                    }
                    $subChapterNumber +=1;
                }
            }
            $chapterNumber += 1;
        }

        $this->menucontainer    = array( new AdminMenu( $this->applicationBuilder->getSetup()->getAppNameForPageTitle(), AdminRouter::ROUTE_ADMIN_GROUP_LIST ) );
        $this->leftcontainer    = array( new AdminSidebar( $this->applicationBuilder->getSetup()->getAppNameForPageTitle(), AdminRouter::ROUTE_ADMIN_GROUP_LIST, $this->applicationBuilder->getRouterContainer() ) );
        $this->centralcontainer = $docsList;

        $this->templateFile = $this->applicationBuilder->getSetup()->getPrivateTemplateWithSidebarFileName();
    }

}
