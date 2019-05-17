<?php 

namespace Firststep\Common\Json\Checkers\Info;

use  Firststep\Common\Json\Checkers\BasicJsonChecker;

/**
 * 
 */
class InfoV1JsonChecker extends BasicJsonChecker {

	function isResourceBlockWellStructured() : bool {
	    return true;
    }

    /**
     * Return an array containing all actions defined in this resource
     *
     * @return array
     */
    public function getActionsDefinedInResource(): array {
	    $actions = $this->resource->get->info->topactions ?? array();
        return $actions;
    }

}
