<?php

/**
 * User: Fabio Mattei
 * Date: 29/09/18
 * Time: 11.54
 */

namespace Firststep\Common\Builders;

use Firststep\Common\Blocks\BaseMenu;

class MenuBuilder {

    private $menuStructure;
    private $router;

    function __construct( $menuStructure, $router ) {
        $this->menuStructure = $menuStructure;
        $this->router = $router;
    }

    /**
     * @param mixed $infoStructure
     */
    public function setMenuStructure( $menuStructure ) {
        $this->menuStructure = $menuStructure;
    }

    function setRouter( $router ) {
        $this->router = $router;
    }

    public function createMenu() {
		$menu = new BaseMenu;
        $menu->addBrand( $this->menuStructure->home->label, $this->menuStructure->home->action );
        $menu->addButtonToggler();
        foreach ($this->menuStructure->menu as $menuitem) {
            if (isset($menuitem->submenu)) {
                $menu->addNavItemWithDropdown( $menuitem->label, 
                    LinkBuilder::getURL( $this->router, $menuitem->action, $menuitem->resource ), 
                    false, false, 
                    $menuitem->submenu 
                );
            } else {
                $menu->addNavItem( $menuitem->label, 
                    LinkBuilder::getURL( $this->router, $menuitem->action, $menuitem->resource ), 
                    false, false 
                );
            }
        }

        return $menu;
    }

}
