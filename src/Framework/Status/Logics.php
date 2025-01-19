<?php

namespace Fabiom\UglyDuckling\Framework\Status;

use Fabiom\UglyDuckling\Framework\Ajax\AjaxObjectsBuilder;
use Fabiom\UglyDuckling\Framework\Blocks\BaseHTMLMessages;
use Fabiom\UglyDuckling\Framework\Database\QueryExecuter;
use Fabiom\UglyDuckling\Framework\Utils\PageStatus;

class Logics {

    public static function performTransactions( PageStatus $pageStatus, $jsonResource ): void {
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
                    } else if ($queryExecutor->getSqlStatmentType() == QueryExecuter::SELECT) {
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
                $pageStatus->addError("There was an error in the transaction");
                $conn->rollBack();
                $pageStatus->logger->write($e->getMessage(), __FILE__, __LINE__);
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
                    } else if ($queryExecutor->getSqlStatmentType() == QueryExecuter::SELECT) {
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
                $pageStatus->addError("There was an error in the transaction");
                $conn->rollBack();
                $pageStatus->logger->write($e->getMessage(), __FILE__, __LINE__);
            }
        }
    }

    public static function performUseCases( PageStatus $pageStatus, $jsonResource, $useCasesIndex ): void {
        if (isset($jsonResource->get->usecases) and is_array($jsonResource->get->usecases)) {
            foreach ($jsonResource->get->usecases as $jsonusecase) {
                $useCase = $pageStatus->getUseCasesIndex()->getUseCase($jsonusecase, $pageStatus, $applicationBuilder);
                $useCase->performAction();
            }
        }
        if (isset($jsonResource->post->usecases) and is_array($jsonResource->post->usecases)) {
            foreach ($jsonResource->post->usecases as $jsonusecase) {
                $useCase = $pageStatus->getUseCasesIndex()->getUseCase($jsonusecase, $pageStatus, $applicationBuilder);
                $useCase->performAction();
            }
        }
    }

    public static function performAjaxCallGet( PageStatus $pageStatus, $jsonResource, $useCasesIndex ): string {
        if ( isset($jsonResource->get->ajax) and is_array($jsonResource->get->ajax)) {
            $out = [];
            foreach ($jsonResource->get->ajax as $ajax) {
                if ( $ajax->type == 'delete' ) {
                    if (is_string( $ajax->destination )) {
                        $destination = $ajax->destination;
                    } else if ( is_object( $ajax->destination ) ) {
                        $destination = $pageStatus->getValue( $ajax->destination );
                    } else {
                        $destination = '';
                    }
                    $out[] = AjaxObjectsBuilder::createAjaxObjectForAjaxDeleteObject(
                        $ajax->type,
                        $destination
                    );
                }

                if ( $ajax->type == 'add' OR $ajax->type == 'update') {
                    if ( is_string( $ajax->destination ) ) {
                        $destination = $ajax->destination;
                    } else if ( is_object( $ajax->destination ) ) {
                        $destination = $pageStatus->getValue( $ajax->destination );
                    } else {
                        $destination = '';
                    }
                    if ( is_string( $ajax->body ) ) {
                        $body = $ajax->body;
                    } else if ( is_object( $ajax->body ) ) {
                        $body = $pageStatus->getValue( $ajax->body );
                    } else {
                        $body = '';
                    }
                    $out[] = AjaxObjectsBuilder::createAjaxObjectForAjaxHtmlBlockObject(
                        $ajax->type,
                        $destination,
                        ( isset($ajax->position) AND is_string( $ajax->position ) ) ? $ajax->position : 'beforeend',
                        $body
                    );
                }
            }

            return json_encode($out);
        }
        return '';
    }

    public static function performAjaxCallPost( PageStatus $pageStatus, ApplicationBuilder $applicationBuilder, $jsonResource ): string {
        if ( $pageStatus->areThereErrors() ) {
            $out = self::createErrorMessagesReadyForAjaxOutput($pageStatus, $applicationBuilder);

            return json_encode($out);
        } else if ( isset($jsonResource->post->ajaxreponses) and is_array($jsonResource->post->ajaxreponses)) {
            $out = [];
            foreach ($jsonResource->post->ajaxreponses as $ajax) {

                if ( $ajax->type == 'delete' OR $ajax->type == 'empty' ) {
                    if (is_string( $ajax->destination )) {
                        $destination = $ajax->destination;
                    } else if ( is_object( $ajax->destination ) ) {
                        $destination = $pageStatus->getValue( $ajax->destination );
                    } else {
                        $destination = '';
                    }

                    $myAjaxResponse = AjaxObjectsBuilder::createAjaxObjectForAjaxDeleteObject(
                        $ajax->type,
                        $destination
                    );

                    $out[] = $myAjaxResponse;
                }

                if ( $ajax->type == 'append' OR $ajax->type == 'overwrite') {
                    if ( is_string( $ajax->destination ) ) {
                        $destination = $ajax->destination;
                    } else if ( is_object( $ajax->destination ) ) {
                        $destination = $pageStatus->getValue( $ajax->destination );
                    } else {
                        $destination = '';
                    }
                    if ( is_string( $ajax->body ) ) {
                        $body = $ajax->body;
                    } else if ( is_object( $ajax->body ) ) {
                        $body = $pageStatus->getValue( $ajax->body );
                    } else {
                        $body = '';
                    }

                    $myAjaxResponse = AjaxObjectsBuilder::createAjaxObjectForAjaxHtmlBlockObject(
                        $ajax->type,
                        $destination,
                        ( isset($ajax->position) AND is_string( $ajax->position ) ) ? $ajax->position : 'beforeend',
                        $body
                    );

                    $out[] = $myAjaxResponse;
                }

                if ( $ajax->type == 'appendurl' OR $ajax->type == 'overwriteurl') {
                    if (is_string( $ajax->destination )) {
                        $destination = $ajax->destination;
                    } else if ( is_object( $ajax->destination ) ) {
                        $destination = $pageStatus->getValue( $ajax->destination );
                    } else {
                        $destination = '';
                    }
                    if (is_string( $ajax->url )) {
                        $url = $ajax->url;
                    } else if ( is_object( $ajax->url ) ) {
                        $url = $applicationBuilder->getRouterContainer()->make_resource_url( $ajax->url, $pageStatus );
                    } else {
                        $url = '';
                    }

                    $myAjaxResponse = AjaxObjectsBuilder::createAjaxObjectForAjaxURLBlockObject(
                        $ajax->type,
                        $destination,
                        ( isset($ajax->position) AND is_string( $ajax->position ) ) ? $ajax->position : 'beforeend',
                        $url,
                        ( isset($ajax->url) AND is_string( $ajax->url ) AND isset($ajax->method) AND is_string( $ajax->method ) ) ? $ajax->method : 'GET'
                    );

                    $out[] = $myAjaxResponse;
                }

                $out = array_merge( $out, self::createErrorMessagesReadyForAjaxOutput($pageStatus, $applicationBuilder) );
                $out = array_merge( $out, self::createInfoMessagesReadyForAjaxOutput($pageStatus, $applicationBuilder) );
                $out = array_merge( $out, self::createWarningMessagesReadyForAjaxOutput($pageStatus, $applicationBuilder) );
                $out = array_merge( $out, self::createSuccessMessagesReadyForAjaxOutput($pageStatus, $applicationBuilder) );

            }

            return json_encode($out);
        }
        return '';
    }

    /**
     * @param PageStatus $pageStatus
     * @param ApplicationBuilder $applicationBuilder
     * @return array|\stdClass[]
     */
    public static function createErrorMessagesReadyForAjaxOutput(PageStatus $pageStatus, ApplicationBuilder $applicationBuilder): array {
        $out = [];
        if ($pageStatus->areThereErrors()) {
            $out = array_map(
                function ($errorstring) use ($applicationBuilder) {
                    $msgBlock = new BaseHTMLMessages;
                    $msgBlock->setHtmlTemplateLoader($applicationBuilder->getHtmlTemplateLoader());
                    $msgBlock->setError($errorstring);

                    $myAjaxResponse = AjaxObjectsBuilder::createAjaxObjectForAjaxMessageObject( 'error',
                        $jsonResource->post->error->destination ?? '#messagescontainer',
                        $jsonResource->post->error->position ?? 'beforeend',
                        $msgBlock->show()
                    );
                    return $myAjaxResponse;
                },
                $pageStatus->getErrors()
            );
        }
        return $out;
    }

    /**
     * @param PageStatus $pageStatus
     * @param ApplicationBuilder $applicationBuilder
     * @return array|\stdClass[]
     */
    public static function createInfoMessagesReadyForAjaxOutput(PageStatus $pageStatus, ApplicationBuilder $applicationBuilder): array {
        $out = [];
        if ($pageStatus->areThereInfos()) {
            $out = array_map(
                function ($infoString) use ($applicationBuilder) {
                    $msgBlock = new BaseHTMLMessages;
                    $msgBlock->setHtmlTemplateLoader($applicationBuilder->getHtmlTemplateLoader());
                    $msgBlock->setInfo($infoString);

                    $myAjaxResponse = AjaxObjectsBuilder::createAjaxObjectForAjaxMessageObject( 'info',
                        $jsonResource->post->error->destination ?? '#messagescontainer',
                        $jsonResource->post->error->position ?? 'beforeend',
                        $msgBlock->show()
                    );
                    return $myAjaxResponse;
                },
                $pageStatus->getErrors()
            );
        }
        return $out;
    }

    /**
     * @param PageStatus $pageStatus
     * @param ApplicationBuilder $applicationBuilder
     * @return array|\stdClass[]
     */
    public static function createWarningMessagesReadyForAjaxOutput(PageStatus $pageStatus, ApplicationBuilder $applicationBuilder): array {
        $out = [];
        if ($pageStatus->areThereWarnings()) {
            $out = array_map(
                function ($warningString) use ($applicationBuilder) {
                    $msgBlock = new BaseHTMLMessages;
                    $msgBlock->setHtmlTemplateLoader($applicationBuilder->getHtmlTemplateLoader());
                    $msgBlock->setWarning($warningString);

                    $myAjaxResponse = AjaxObjectsBuilder::createAjaxObjectForAjaxMessageObject( 'warning',
                        $jsonResource->post->error->destination ?? '#messagescontainer',
                        $jsonResource->post->error->position ?? 'beforeend',
                        $msgBlock->show()
                    );
                    return $myAjaxResponse;
                },
                $pageStatus->getErrors()
            );
        }
        return $out;
    }

    /**
     * @param PageStatus $pageStatus
     * @param ApplicationBuilder $applicationBuilder
     * @return array|\stdClass[]
     */
    public static function createSuccessMessagesReadyForAjaxOutput(PageStatus $pageStatus, ApplicationBuilder $applicationBuilder): array {
        $out = [];
        if ($pageStatus->areThereSuccesses()) {
            $out = array_map(
                function ($successString) use ($applicationBuilder) {
                    $msgBlock = new BaseHTMLMessages;
                    $msgBlock->setHtmlTemplateLoader($applicationBuilder->getHtmlTemplateLoader());
                    $msgBlock->setSuccess($successString);

                    $myAjaxResponse = AjaxObjectsBuilder::createAjaxObjectForAjaxMessageObject( 'success',
                        $jsonResource->post->error->destination ?? '#messagescontainer',
                        $jsonResource->post->error->position ?? 'beforeend',
                        $msgBlock->show()
                    );
                    return $myAjaxResponse;
                },
                $pageStatus->getErrors()
            );
        }
        return $out;
    }
}
