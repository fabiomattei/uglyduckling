<?php 

namespace Fabiom\UglyDuckling\Common\Json\DocBuilders\Group;

use Fabiom\UglyDuckling\Common\Json\DocBuilders\BasicDocBuilder;

/**
 * 
 */
class GroupV1DocBuilder extends BasicDocBuilder {

	function isResourceBlockWellStructured() : bool {
	    return true;
    }

    /**
     * Return an array containing all actions defined in this resource
     *
     * @return array
     */
    public function getActionsDefinedInResource(): array {
		$actions = array();
		foreach ($this->resource->menu as $menuitem) {
			if ( isset( $menuitem->resource ) ) $actions[] = $menuitem->resource;
			if ( isset( $menuitem->submenu ) ) {
				$actions = array_merge( $actions, $menuitem->submenu ); 
			}
		}
        return $actions;
    }

}
