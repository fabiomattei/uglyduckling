<?php

namespace Fabiom\UglyDuckling\Framework\Controllers;

use Fabiom\UglyDuckling\Framework\DataBase\DBConnection;
use Fabiom\UglyDuckling\Framework\DataBase\QueryExecuter;
use Fabiom\UglyDuckling\Framework\DataBase\QueryReturnedValues;
use Fabiom\UglyDuckling\Framework\Loggers\Logger;
use Fabiom\UglyDuckling\Framework\Mailer\BaseMailer;
use Fabiom\UglyDuckling\Framework\SecurityCheckers\SecurityChecker;
use Fabiom\UglyDuckling\Framework\Utils\PageStatus;

class TransactionController extends JsonResourceBasicController {

    const CONTROLLER_NAME = 'transactioncontroller';

    public $resource; // Json structure
    /* TODO remove following parameter */
    public $internalGetParameters;
    public $resourceName;

    public $secondGump;
    public /* array */ $parameters;
    public /* array */ $postParameters;
    public array $resourceIndex;
    public array $groupsIndex;
    public array $useCasesIndex;
    public DBConnection $dbconnection;
    public Logger $logger;
    public SecurityChecker $securityChecker;
    public BaseMailer $mailer;
    public PageStatus $pageStatus;
    public $queryExecutor;
    public string $templateFile;
    public $menucontainer;
    public $leftcontainer;
    public $jsonTabTemplates;
    public $jsonResourceTemplates;
    public $unvalidated_parameters;
    public array $index_json_smallpartial_templates;
    public $readableErrors;

    /**
     * This function allows to set a resource name to load for a particular instance
     * This helps in case a resource want to be set at programming time and not
     * at run time.
     * @param string $resourceName   the name of the json resource we want to load
     */
    public function setResourceName(string $resourceName) {
        $this->resourceName = $resourceName;
    }

    public function setPageStatus($pageStatus) {
        $this->pageStatus = $pageStatus;
    }

    public function setResourceIndex( $resourceIndex ) {
        $this->resourceIndex = $resourceIndex;
    }

    public function setGroupsIndex( $groupsIndex ) {
        $this->groupsIndex = $groupsIndex;
    }

    public function setUseCasesIndex( $useCasesIndex ) {
        $this->useCasesIndex = $useCasesIndex;
    }

    public function setJsonTagTemplates( $index_json_tag_templates ) {
        $this->jsonTabTemplates = $index_json_tag_templates;
    }

    public function setJsonResourceTemplates( $index_json_resource_templates ) {
        $this->jsonResourceTemplates = $index_json_resource_templates;
    }

    public function setJsonSmallPartialTemplates( $index_json_smallpartial_templates ) {
        $this->index_json_smallpartial_templates = $index_json_smallpartial_templates;
    }


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
                $useCase = new $this->useCasesIndex[$jsonusecase->name]( $jsonusecase, $this->pageStatus );
                $useCase->loadParameters();
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
