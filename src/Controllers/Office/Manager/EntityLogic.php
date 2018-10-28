<?php

namespace Firststep\Controllers\Office\Manager;

use Firststep\Common\Controllers\ManagerEntityController;
use Firststep\Common\Database\QueryExecuter;
use Firststep\Common\Builders\QueryBuilder;

/**
 * User: Fabio
 * Date: 29/09/2018
 * Time: 05:57
 */
class EntityLogic extends ManagerEntityController {

    function __construct() {
		$this->queryExecuter = new QueryExecuter;
		$this->queryBuilder = new QueryBuilder;
    }
	
    /**
     * @throws GeneralException
     */
	public function getRequest() {
		$this->queryExecuter->setDBH( $this->dbconnection->getDBH() );

        foreach ($this->resource->logics as $logic) {
            $this->queryExecuter->setQueryBuilder( $this->queryBuilder );
            $this->queryExecuter->setQueryStructure( $logic );
            $this->queryExecuter->setParameters( $this->internalGetParameters );
            $this->queryExecuter->executeQuery();
        }

        $this->redirectToPreviousPage();
	}

}
