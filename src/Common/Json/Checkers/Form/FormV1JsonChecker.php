<?php 

namespace Firststep\Common\Json\Checkers\Form;

use  Firststep\Common\Json\Checkers\BasicJsonChecker;

/**
 * 
 */
class FormV1JsonChecker extends BasicJsonChecker {

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
	    $actions = array_merge( $actions, $this->resource->post->redirect ?? array() );
        return $actions;
    }

}
