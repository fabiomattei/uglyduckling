<?php

namespace Firststep\Common\Json;

use Firststep\Common\Builders\FormBuilder;

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
		$html = $this->formBuilder->createBodyStructure();
		$addToHead = $this->formBuilder->create_addToHead();
		$addToFoot = $this->formBuilder->create_addToFoot();
		return array( 'html' => $html, 'addToHead' => $addToHead, 'addToFoot' => $addToFoot );
	}
	
}
