<?php

namespace Firststep\Common\Json\Checkers\Table;

use  Firststep\Common\Json\Checkers\BasicJsonChecker;

/**
 *
 */
class TableV1JsonChecker extends BasicJsonChecker {

    function isResourceBlockWellStructured() : bool {
        return true;
    }

    /**
     * Return an array containing all actions defined in this resource
     *
     * @return array
     */
    public function getActionsDefinedInResource(): array {
        $actions = $this->resource->get->form->topactions ?? array();
        $actions = array_merge( $actions, $this->resource->get->table->actions ?? array() );
        return $actions;
    }

}
