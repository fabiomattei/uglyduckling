<?php

namespace Fabiom\UglyDuckling\Common\Json\JsonTemplates\Menu;

use Fabiom\UglyDuckling\Common\Json\JsonTemplates\JsonTemplate;
use Fabiom\UglyDuckling\Common\Blocks\BaseHTMLMenu;
use stdClass;

/**
 * User: Fabio Mattei
 * Date: 29/09/18
 * Time: 11.54
 *
 * This class cares about creating a menu for a UD application.
 * The Json menu structure is coded in group json structures
 *
 * Check the documentation
 * http://www.uddocs.com/docs/group
 *
 */
class MenuJsonTemplate extends JsonTemplate {

    private $menuStructure;
    protected string $controllerName;
    protected string $resourceName;

    /**
     * JsonTemplate constructor.
     */
    public function __construct( $applicationBuilder, $pageStatus, $controllerName = '', $resourceName = '' ) {
        $this->applicationBuilder = $applicationBuilder;
        $this->pageStatus = $pageStatus;
        $this->controllerName = $controllerName;
        $this->resourceName = $resourceName;
    }

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
            $active = false;
            $current = false;
            if ( isset($menuitem->submenu) ) {
                // A submenu is present
                $submenuItems = array();
                foreach ($menuitem->submenu as $item) {
                    $mi = new stdClass;
                    $mi->label = $item->label ?? '';
                    $mi->controller = $item->controller ?? '';
                    $mi->resource = $item->resource ?? '';
                    $mi->url = $this->applicationBuilder->make_resource_url( $item, $this->pageStatus );
                    $submenuItems[] = $mi;
                    if ( (isset($item->resource) AND $this->resourceName == $item->resource) OR (isset($item->controller) AND $this->controllerName == $item->controller) ) {
                        $current = true;
                    }
                }
                if ( isset( $menuitem->resource ) OR isset( $menuitem->controller ) ) {
                    $menu->addNavItemWithDropdown( $menuitem->label,
                        $this->applicationBuilder->make_resource_url( $menuitem, $this->pageStatus ),
                        $active, $current,
                        $submenuItems, $this->controllerName, $this->resourceName
                    );
                } else {
                    $menu->addNavItemWithDropdown( $menuitem->label, '#', $active, $current, $submenuItems, $this->controllerName, $this->resourceName );
                }
            } else {
                // there is no submenu
                if ( isset( $menuitem->resource ) OR isset( $menuitem->controller ) ) {
                    if ( ( isset( $menuitem->resource ) AND $this->resourceName == $menuitem->resource) OR ( isset( $menuitem->controller ) AND $this->controllerName == $menuitem->controller) ) {
                        $current = true;
                    }

                    $menu->addNavItem( $menuitem->label,
                        $this->applicationBuilder->make_resource_url( $menuitem, $this->pageStatus ),
                        $active, $current
                    );
                } else {
                    $menu->addNavItem( $menuitem->label, '#',false, false );
                }
            }
        }

        return $menu;
    }

}
