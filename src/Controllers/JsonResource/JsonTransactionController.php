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

    /** @var QueryExecuter */
    private $queryExecuter;
    /** @var QueryBuilder */
    private $queryBuilder;

    function __construct() {
		$this->queryExecuter = new QueryExecuter;
		$this->queryBuilder = new QueryBuilder;
    }
	
	public function getRequest() {
        $this->queryExecuter = new QueryExecuter;
        $this->queryBuilder = new QueryBuilder;
        $this->queryExecuter->setLogger( $this->applicationBuilder->getLogger() );

        $conn = $this->applicationBuilder->getDbconnection()->getDBH();

        // performing transactions
        if (isset($this->resource->get->transactions)) {
            $returnedIds = new QueryReturnedValues;
            try {
                //$conn->beginTransaction();
                $this->queryExecuter->setDBH( $conn );
                foreach ($this->resource->get->transactions as $transaction) {
                    $this->queryExecuter->setQueryBuilder( $this->queryBuilder );
                    $this->queryExecuter->setQueryStructure( $transaction );
                    $this->queryExecuter->setPageStatus($this->pageStatus);
                    if ( $this->queryExecuter->getSqlStatmentType() == QueryExecuter::INSERT) {
                        if (isset($transaction->label)) {
                            $returnedIds->setValue($transaction->label, $this->queryExecuter->executeSql());
                        } else {
                            $returnedIds->setValueNoKey($this->queryExecuter->executeSql());
                        }
                    } else {
                        $this->queryExecuter->executeSql();
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
