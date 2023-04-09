<?php

namespace Fabiom\UglyDuckling\Controllers\Admin\DocsExport;

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

            $info = new BaseHTMLInfo;
            $info->setHtmlTemplateLoader( $this->applicationBuilder->getHtmlTemplateLoader() );
            $info->setTitle('Group: ' . $jsonGroup->name);
            if ( isset($jsonGroup->docs) and is_array($jsonGroup->docs) ) {
                foreach ( $jsonGroup->docs as $paragraph) {
                    $info->addParagraph($paragraph, 12);
                }
            }

            $docsList[] = $info;

            $table = new StaticTable;
            $table->setHtmlTemplateLoader( $this->applicationBuilder->getHtmlTemplateLoader() );
            $table->setTitle('Menu structure');
            $table->addTHead();
            $table->addRow();
            $table->addHeadLineColumn('Menu');
            $table->addHeadLineColumn('Sub-menu');
            $table->closeRow();
            $table->closeTHead();

            $table->addTBody();

            if ( isset($jsonGroup->menu) and is_array($jsonGroup->menu) ) {
                foreach ( $jsonGroup->menu as $menu) {
                    $table->addRow();
                    $table->addColumn( $menu->label );
                    $table->addColumn( '');
                    $table->closeRow();
                    if ( isset($menu->submenu) and is_array($menu->submenu) ) {
                        foreach ( $menu->submenu as $submenuitem) {
                            $table->addRow();
                            $table->addColumn( '');
                            $table->addColumn( $submenuitem->label );
                            $table->closeRow();
                        }
                    }
                }
            }

            $table->closeTBody();
            $docsList[] = $table;
        }

        if ( isset($jsonGroup->menu) and is_array($jsonGroup->menu) ) {
            foreach ( $jsonGroup->menu as $menu) {
                if (isset($menu->resource)) {
                    $jsonResource = $this->applicationBuilder->getJsonloader()->loadResource($menu->resource);
                    $infoMenuItem = new BaseHTMLInfo;
                    $infoMenuItem->setHtmlTemplateLoader( $this->applicationBuilder->getHtmlTemplateLoader() );

                    $infoMenuItem->setTitle($jsonResource->name);
                    if ( isset($jsonResource->table->name) ) {
                        $infoMenuItem->setTitle('Table: ' . $jsonResource->table->name);
                    }

                    if ( isset($jsonResource->description) and is_string($jsonResource->description) ) {
                        $info->addParagraph($jsonResource->description, 12);
                    }
                    if ( isset($jsonResource->docs) and is_array($jsonResource->docs) ) {
                        foreach ( $jsonResource->docs as $paragraph) {
                            $info->addParagraph($paragraph, 12);
                        }
                    }

                    $docsList[] = $infoMenuItem;
                }

                if ( isset($menu->submenu) and is_array($menu->submenu) ) {
                    foreach ( $menu->submenu as $submenuitem) {
                        if (isset($submenuitem->resource)) {
                            if ( $this->applicationBuilder->getJsonloader()->isJsonResourceIndexedAndFileExists($submenuitem->resource) ) {
                                $jsonResource = $this->applicationBuilder->getJsonloader()->loadResource($submenuitem->resource);
                                print_r($jsonResource);
                                $infoMenuItem = new BaseHTMLInfo;
                                $infoMenuItem->setHtmlTemplateLoader( $this->applicationBuilder->getHtmlTemplateLoader() );

                                $infoMenuItem->setTitle($jsonResource->name);

                                $infoMenuItem->setTitle($GLOBALS['myDocFunctions'][$jsonResource->metadata->type]($jsonResource, $this->applicationBuilder->getJsonloader()));

                                if ( isset($jsonResource->description) and is_string($jsonResource->description) ) {
                                    $info->addParagraph($jsonResource->description, 12);
                                }
                                if ( isset($jsonResource->docs) and is_array($jsonResource->docs) ) {
                                    foreach ( $jsonResource->docs as $paragraph) {
                                        $info->addParagraph($paragraph, 12);
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

        $this->menucontainer    = array( new AdminMenu( $this->applicationBuilder->getSetup()->getAppNameForPageTitle(), AdminRouter::ROUTE_ADMIN_GROUP_LIST ) );
        $this->leftcontainer    = array( new AdminSidebar( $this->applicationBuilder->getSetup()->getAppNameForPageTitle(), AdminRouter::ROUTE_ADMIN_GROUP_LIST, $this->applicationBuilder->getRouterContainer() ) );
        $this->centralcontainer = $docsList;

        $this->templateFile = $this->applicationBuilder->getSetup()->getPrivateTemplateWithSidebarFileName();
    }

}
