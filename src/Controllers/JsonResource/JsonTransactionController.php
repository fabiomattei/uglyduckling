<?php

namespace Fabiom\UglyDuckling\Controllers\JsonResource;

use Fabiom\UglyDuckling\Common\Controllers\JsonResourceBasicController;
use Fabiom\UglyDuckling\Common\Database\QueryExecuter;
use Fabiom\UglyDuckling\Common\Database\QueryReturnedValues;
use Fabiom\UglyDuckling\Common\Json\JsonTemplates\QueryBuilder;

/**
 * User: Fabio
 * Date: 29/09/2018
 * Time: 05:57
 */
class JsonTransactionController extends JsonResourceBasicController {
	
	public function getRequest() {
        $this->queryExecutor = $this->pageStatus->getQueryExecutor();

        $conn = $this->pageStatus->getDbconnection()->getDBH();

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
                $this->applicationBuilder->getLogger()->write($e->getMessage(), __FILE__, __LINE__);
            }
        }

        // if resource->get->sessionupdates is set I need to update the session
        if ( isset($this->resource->get->sessionupdates) ) $this->pageStatus->updateSession( $this->resource->get->sessionupdates );

        // redirect
        if (isset($this->resource->get->redirect)) {
            if (isset($this->resource->get->redirect->internal) AND $this->resource->get->redirect->internal->type === 'onepageback') {
                $this->redirectToPreviousPage();
            } elseif (isset($this->resource->get->redirect->internal) AND $this->resource->get->redirect->internal->type === 'twopagesback') {
                $this->redirectToSecondPreviousPage();
            } elseif ( isset($this->resource->get->redirect->action) AND isset($this->resource->get->redirect->action->resource) ) {
                $this->redirectToPage(
                    $this->applicationBuilder->getRouterContainer()->makeRelativeUrl(
                        $this->applicationBuilder->getJsonloader()->getActionRelatedToResource($this->resource->get->redirect->action->resource), 'res='.$this->resource->get->redirect->action->resource
                    )
                );
            } else {
                $this->redirectToPreviousPage();
            }
        } else {
            $this->redirectToPreviousPage();
        }
    }

}
