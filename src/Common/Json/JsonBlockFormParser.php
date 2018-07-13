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
		$this->formBuilder->setForm($resource);
		$this->formBuilder->setEntity($resource);
		$block = new EmptyBlock;
		$block->setHtml($this->formBuilder->createBodyStructure());
		$block->setAddToHead($this->formBuilder->create_addToHead());
		$block->setAddToFoot($this->formBuilder->create_addToFoot());
		return $block;
	}
	
}
