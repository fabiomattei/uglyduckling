<?php

namespace Fabiom\UglyDuckling\Common\Setup;

use Fabiom\UglyDuckling\Common\Database\QueryExecuter;
use Fabiom\UglyDuckling\Common\Json\JsonTemplates\QueryBuilder;
use Fabiom\UglyDuckling\Common\Wrappers\SessionWrapper;

class SessionJsonSetup {
	
	private /* string */ $sessionSetupPath;
	private /* QueryExecuter */ $queryExecuter;
	private /* QueryBuilder */ $queryBuilder;
	private /* SessionWrapper */ $sessionWrapper;

	public function __construct($sessionSetupPath. $queryBuilder, $queryExecuter, $sessionWrapper) {
		$this->sessionSetupPath = $sessionSetupPath;
		$this->queryBuilder = $queryBuilder;
		$this->queryExecuter = $queryExecuter;
		$this->sessionWrapper = $sessionWrapper;
	}
	
	

}
