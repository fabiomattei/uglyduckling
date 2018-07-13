<?php

namespace Firststep\Common\Json;

use Firststep\Common\Builders\InfoBuilder;

/**
 * JsonLoader makes an index of all available resources and load the 
 * resource if needed
 */
class JsonBlockInfoParser {
	
	__construct() {
		$this->infoBuilder = new InfoBuilder();
	}
	
	public static function parse( $resource, $entity ) {
		$this->infoBuilder->setFormStructure($resource);
		$this->infoBuilder->setEntity($resource);
		return $this->infoBuilder->createInfo();
	}
	
}
