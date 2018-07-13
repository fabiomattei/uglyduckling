<?php

namespace Firststep\Common\Json;

use Firststep\Common\Builders\FormBuilder;
use Firststep\Common\Blocks\EmptyBlock;

/**
 * JsonLoader makes an index of all available resources and load the 
 * resource if needed
 */
class JsonBlockFormParser {
	
	__construct() {
		$this->formBuilder = new FormBuilder();
	}
	
	public static function parse( $resource, $entity ) {
		$this->formBuilder->setFormStructure($resource);
		$this->formBuilder->setEntity($resource);
		return $this->formBuilder->createForm();
	}
	
}
