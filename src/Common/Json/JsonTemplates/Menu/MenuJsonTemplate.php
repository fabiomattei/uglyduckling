<?php

namespace Fabiom\UglyDuckling\Common\Json\JsonTemplates\Menu;

use Fabiom\UglyDuckling\Common\Json\JsonTemplates\JsonTemplate;
use Fabiom\UglyDuckling\Common\Blocks\BaseHTMLMenu;
use stdClass;

/**
 * User: Fabio Mattei
 * Date: 29/09/18
 * Time: 11.54
 */
class MenuJsonTemplate extends JsonTemplate {

    private $menuStructure;

    /**
     * Set the json structure in order to build the menu
     * Usually the structure is set in a json group file
     *
     * @param mixed $menuStructure
     */
    public function setMenuStructure( $menuStructure ) {
        $this->menuStructure = $menuStructure;
    }

    public function createMenu() {
        $htmlTemplateLoader = $this->applicationBuilder->getHtmlTemplateLoader();

        $menu = new BaseHTMLMenu;
        $menu->setHtmlTemplateLoader( $htmlTemplateLoader );
        $menu->addBrand( $this->menuStructure->home->label, $this->menuStructure->home->action );
        $menu->addButtonToggler();

        foreach ($this->menuStructure->menu as $menuitem) {
            if (isset($menuitem->submenu)) {
                $submenuItems = array();
                foreach ($menuitem->submenu as $item) {
                    $mi = new stdClass;
                    $mi->label = $item->label;
                    $mi->url = $this->applicationBuilder->make_resource_url_simplified( $item, $this->pageStatus );
                    $submenuItems[] = $mi;
                }
                $menu->addNavItemWithDropdown( $menuitem->label,
                    $this->applicationBuilder->make_resource_url_simplified( $menuitem, $this->pageStatus ),
                    false, false, 
                    $submenuItems 
                );
            } else {
                $menu->addNavItem( $menuitem->label,
                    $this->applicationBuilder->make_resource_url_simplified( $menuitem, $this->pageStatus ),
                    false, false 
                );
            }
        }

        return $menu;
    }

}
