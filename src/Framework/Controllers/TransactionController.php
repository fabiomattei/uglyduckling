<?php

namespace Fabiom\UglyDuckling\Framework\Controllers;

use Fabiom\UglyDuckling\Framework\DataBase\QueryExecuter;
use Fabiom\UglyDuckling\Framework\DataBase\QueryReturnedValues;

class TransactionController extends JsonResourceBasicController {

    const CONTROLLER_NAME = 'transactioncontroller';

    public $queryExecutor;

    /**
     * This method implements POST Request logic for all possible json resources.
     * This means all json Resources act in the same way when there is a post request
     */
    public function getRequest() {
        $this->queryExecutor = $this->pageStatus->getQueryExecutor();

        $conn = $this->dbconnection;

        // performing transactions
        if (isset($this->resource->get->transactions)) {
            $returnedIds = new QueryReturnedValues;
            try {
                //$conn->beginTransaction();
                $this->queryExecutor->setDBH( $conn );
                foreach ($this->resource->get->transactions as $transaction) {
                    $this->queryExecutor->setQueryStructure( $transaction );
                    if ( $this->queryExecutor->getSqlStatmentType() == QueryExecuter::INSERT) {
                        if (isset($transaction->label)) {
                            $returnedIds->setValue($transaction->label, $this->queryExecutor->executeSql());
                        } else {
                            $returnedIds->setValueNoKey($this->queryExecutor->executeSql());
                        }
                    } else {
                        $this->queryExecutor->executeSql();
                    }
                }
                //$conn->commit();
            }
            catch (\PDOException $e) {
                $conn->rollBack();
                $this->logger->write($e->getMessage(), __FILE__, __LINE__);
            }
        }

        // performing usecases
        if (isset($this->resource->get->usecases) and is_array($this->resource->get->usecases)) {
            foreach ($this->resource->get->usecases as $jsonusecase) {
                $useCase = $this->pageStatus->getUseCasesIndex()->getUseCase($jsonusecase, $this->pageStatus, $this->applicationBuilder);
                $useCase->performAction();
            }
        }

        // if resource->get->sessionupdates is set I need to update the session
        if ( isset($this->resource->get->sessionupdates) ) $this->pageStatus->updateSession( $this->resource->get->sessionupdates );

        // redirect
        if (isset($this->resource->get->redirect)) {
            $this->jsonRedirector($this->resource->get->redirect);
        } else {
            $this->redirectToPreviousPage();
        }
    }

}
