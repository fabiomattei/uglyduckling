<?php

namespace Firststep\Common\Json;

use Firststep\Common\Builders\TableBuilder;

/**
 * JsonLoader makes an index of all available resources and load the 
 * resource if needed
 */
class JsonBlockTableParser {
	
	__construct() {
		$this->tableBuilder = new TableBuilder();
	}
	
	public static function parse( $resource, $entities ) {
		$this->tableBuilder->setTableStructure($resource);
		$this->tableBuilder->setEntities($entities);
		return $this->tableBuilder->createTable();
	}
	
}
