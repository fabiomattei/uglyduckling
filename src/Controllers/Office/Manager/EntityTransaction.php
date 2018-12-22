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
class EntityTransaction extends ManagerEntityController {

    function __construct() {
		$this->queryExecuter = new QueryExecuter;
		$this->queryBuilder = new QueryBuilder;
    }
	
    /**
     * @throws GeneralException
     */
	public function getRequest() {
        $conn = $this->dbconnection->getDBH();
        try {
            $conn->beginTransaction();
            $this->queryExecuter->setDBH( $conn );
            foreach ($this->resource->get->transactions as $transaction) {
                $this->queryExecuter->setQueryBuilder( $this->queryBuilder );
                $this->queryExecuter->setQueryStructure( $transaction );
                $this->queryExecuter->setGetParameters( $this->internalGetParameters );
                $this->queryExecuter->executeQuery();
            }
            $conn->commit();
        }
        catch (PDOException $e) {
            $conn->rollBack();
            $logger = new Logger();
            $logger->write($e->getMessage(), __FILE__, __LINE__);
        }

        $this->redirectToPreviousPage();
	}

}
