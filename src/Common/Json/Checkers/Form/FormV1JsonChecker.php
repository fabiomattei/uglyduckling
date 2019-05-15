<?php 

namespace Firststep\Common\Json\Checkers\Form;

use  Firststep\Common\Json\Checkers\BasicJsonChecker;

/**
 * 
 */
class FormV1JsonChecker extends BasicJsonChecker {
	
	function __construct() {
		# code...
	}

    public function getActionsDefinedInResource() {
	    $actions = $this->resource->get->form->topactions ?? array();
	    $actions = array_merge( $actions, $this->resource->post->redirect ?? array() );
        return $actions;
    }

}
