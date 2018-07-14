<?php

namespace Firststep\Common\Json;

use Firststep\Common\Builders\QueryBuilder;

/**
 * JsonLoader makes an index of all available resources and load the 
 * resource if needed
 */
class JsonBlockInfoParser {
	
	function __construct() {
		$this->queryBuilder = new QueryBuilder();
	}
	
	public static function parse( $resource, $entity ) {
		$this->queryBuilder->setQueryStructure($resource);
		$this->queryBuilder->setEntity($resource);
		return $this->queryBuilder->createQuery();
	}
	
}
