<?php

namespace Fabiom\UglyDuckling\Common\Status;

use Fabiom\UglyDuckling\Common\Database\QueryExecuter;
use Fabiom\UglyDuckling\Common\Database\QueryReturnedValues;

class Logics {

    public static function performTransactions( PageStatus $pageStatus, ApplicationBuilder $applicationBuilder, $jsonResource ): void {
        $queryExecutor = $pageStatus->getQueryExecutor();

        $conn = $pageStatus->getDbconnection()->getDBH();

        // performing get transactions
        if (isset($jsonResource->get->transactions)) {
            $returnedIds = $pageStatus->getQueryReturnedValues();
            try {
                //$conn->beginTransaction();
                $queryExecutor->setDBH($conn);
                foreach ($jsonResource->get->transactions as $transaction) {
                    $queryExecutor->setQueryStructure($transaction);
                    if ($queryExecutor->getSqlStatmentType() == QueryExecuter::INSERT) {
                        if (isset($transaction->label)) {
                            $returnedIds->setValue($transaction->label, $queryExecutor->executeSql());
                        } else {
                            $returnedIds->setValueNoKey($queryExecutor->executeSql());
                        }
                    } else {
                        $queryExecutor->executeSql();
                    }
                }
                //$conn->commit();
            } catch (\PDOException $e) {
                $conn->rollBack();
                $applicationBuilder->getLogger()->write($e->getMessage(), __FILE__, __LINE__);
            }
        }

        // performing post transactions
        if (isset($jsonResource->post->transactions)) {
            $returnedIds = $pageStatus->getQueryReturnedValues();
            try {
                //$conn->beginTransaction();
                $queryExecutor->setDBH($conn);
                foreach ($jsonResource->post->transactions as $transaction) {
                    $queryExecutor->setQueryStructure($transaction);
                    if ($queryExecutor->getSqlStatmentType() == QueryExecuter::INSERT) {
                        if (isset($transaction->label)) {
                            $returnedIds->setValue($transaction->label, $queryExecutor->executeSql());
                        } else {
                            $returnedIds->setValueNoKey($queryExecutor->executeSql());
                        }
                    } else {
                        $queryExecutor->executeSql();
                    }
                }
                //$conn->commit();
            } catch (\PDOException $e) {
                $conn->rollBack();
                $applicationBuilder->getLogger()->write($e->getMessage(), __FILE__, __LINE__);
            }
        }
    }

}