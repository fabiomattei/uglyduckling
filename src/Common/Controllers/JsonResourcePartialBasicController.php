<?php

namespace Fabiom\UglyDuckling\Common\Controllers;

use Fabiom\UglyDuckling\Common\Database\QueryExecuter;
use Fabiom\UglyDuckling\Common\Database\QueryReturnedValues;
use Fabiom\UglyDuckling\Common\Json\Parameters\BasicParameterGetter;

class JsonResourcePartialBasicController extends ControllerNoCSRFTokenRenew {

    const CONTROLLER_NAME = 'partial';

    /**
     * @throws GeneralException
     */
    public function getRequest() {
        $this->applicationBuilder->getJsonloader()->loadIndex();

        // GETTING json resource name from parameter
        $jsonResourceName = filter_input(INPUT_POST | INPUT_GET, 'res', FILTER_SANITIZE_STRING);

        // loading json resource
        if ( ! $jsonResourceName ) {
            echo 'missing resource name';
        } else {
            if ( strlen( $jsonResourceName ) > 0 ) {
                $jsonResource = $this->applicationBuilder->getJsonloader()->loadResource( $jsonResourceName );
            }
        }

        // if json resource was correctly loaded
        if ( is_object( $jsonResource ) ) {
            $this->templateFile = 'empty';

            // if json resource has parameters
            if(!isset($jsonResource->get->request) OR !isset($jsonResource->get->request->parameters)) {
                if ( isset($jsonResource->get->sessionupdates) ) $this->pageStatus->updateSession( $jsonResource->get->sessionupdates );

                $myBlocks = $this->applicationBuilder->getHTMLBlock( $jsonResource );
                echo $myBlocks->show();
            } else {
                $secondGump = new \Gump;

                $parametersGetter = BasicParameterGetter::parameterGetterFactory( $jsonResource, $this->applicationBuilder );
                $validation_rules = $parametersGetter->getValidationRoules();
                $filter_rules = $parametersGetter->getFiltersRoules();

                if ( count( $validation_rules ) == 0 ) {
                    // nothing to do
                } else {
                    $parms = $secondGump->sanitize( $_GET );
                    $secondGump->validation_rules( $validation_rules );
                    $secondGump->filter_rules( $filter_rules );
                    $cleanGETParameters = $secondGump->run( $parms );
                    $this->pageStatus->setGetParameters( $cleanGETParameters );
                    $this->unvalidated_parameters = $parms;
                    if ( $this->internalGetParameters === false ) {
                        $this->readableErrors = $this->secondGump->get_readable_errors(true);
                    } else {
                        if ( isset($jsonResource->get->sessionupdates) ) $this->pageStatus->updateSession( $jsonResource->get->sessionupdates );

                        $myBlocks = $this->applicationBuilder->getHTMLBlock( $jsonResource );
                        echo $myBlocks->show();
                    }
                }
            }
        } else {
            echo 'resource '.$jsonResourceName.' undefined';
        }
    }

    /**
     * This method implements POST Request logic for all possible json resources.
     * This means all json Resources act in the same way when there is a post request
     */
    public function postRequest() {
        $this->templateFile = "empty";

        $this->queryExecutor = $this->pageStatus->getQueryExecutor();
        $this->queryExecutor->setApplicationBuilder( $this->applicationBuilder );

        $conn = $this->pageStatus->getDbconnection()->getDBH();

        // performing transactions
        if (isset($this->resource->post->transactions)) {
            $returnedIds = new QueryReturnedValues;
            try {
                //$conn->beginTransaction();
                $this->queryExecutor->setDBH( $conn );
                foreach ($this->resource->post->transactions as $transaction) {
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

        // performing usecases
        if (isset($this->resource->post->usecases) and is_array($this->resource->post->usecases)) {
            foreach ($this->resource->post->usecases as $jsonusecase) {
                $useCase = $this->pageStatus->getUseCasesIndex()->getUseCase($jsonusecase, $this->pageStatus, $this->applicationBuilder);
                $useCase->performAction();
            }
        }

        // if resource->post->sessionupdates is set I need to update the session
        if ( isset($this->resource->post->sessionupdates) ) $this->pageStatus->updateSession( $this->resource->post->sessionupdates );

        // redirect
        if (isset($this->resource->post->redirect)) {
            $this->jsonRedirector($this->resource->post->redirect);
        }
        if ( !isset($this->resource->post->redirect) AND !isset($this->resource->post->render) ) {
            // I need this to replicate previous default redirect
            $this->redirectToPreviousPage();
        }

        if (isset($this->resource->post->render)) {
            echo $this->applicationBuilder->getBlock( $this->resource->post->render->resource )->show();
        }
    }

}
