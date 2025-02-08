<?php

namespace Fabiom\UglyDuckling\Framework\Status;

use Fabiom\UglyDuckling\Framework\Ajax\AjaxObjectsBuilder;
use Fabiom\UglyDuckling\Framework\Blocks\BaseHTMLMessages;
use Fabiom\UglyDuckling\Framework\DataBase\QueryExecuter;
use Fabiom\UglyDuckling\Framework\Json\JsonTemplates\JsonDefaultTemplateFactory;
use Fabiom\UglyDuckling\Framework\Utils\PageStatus;
use Fabiom\UglyDuckling\Framework\Utils\UrlServices;

class Logics {

    /**
     * @param PageStatus $pageStatus: object containing all the status of the page we are composing with a URL call
     * @param $jsonResource: json structure containing the transaction section: resource->get->transactions or resource->post->transactions
     * @return void
     * @throws \Exception
     *
     * Documentation: https://www.uddocs.com/resources/transaction
     *
     */
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

    /**
     * @param PageStatus $pageStatus: object containig all the status of the page we are composing with a URL call
     * @param $resource: json resource containing the usecases section: resource->get->usecases or resource->post->usecases
     * @param $useCasesIndex: index listing all defined usecases, array list having the use case name as key and the use case file path as value
     * @return void
     *
     * Documentation: https://www.uddocs.com/baseresources/usecase
     *
     */
    public static function performUseCases(PageStatus $pageStatus, $resource, array $useCasesIndex ): void {
        if (isset($resource->get->usecases) and is_array($resource->get->usecases)) {
            foreach ($resource->get->usecases as $jsonUseCase) {
                $useCase = JsonDefaultTemplateFactory::getUseCase( $useCasesIndex, $jsonUseCase, $pageStatus );
                $useCase->performAction();
            }
        }
        if (isset($resource->post->usecases) and is_array($resource->post->usecases)) {
            foreach ($resource->post->usecases as $jsonUseCase) {
                $useCase = JsonDefaultTemplateFactory::getUseCase( $useCasesIndex, $jsonUseCase, $pageStatus );
                $useCase->performAction();
            }
        }
    }

    /**
     * @param PageStatus $pageStatus: object containing all the status of the page we are composing with a URL call
     * @param $jsonResource
     * @param $useCasesIndex
     * @return string
     */
    public static function performAjaxCallGet( PageStatus $pageStatus, $jsonResource ): string {
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

    /**
     * @param PageStatus $pageStatus: object containing all the status of the page we are composing with a URL call
     * @param $jsonResource
     * @return string
     */
    public static function performAjaxCallPost( PageStatus $pageStatus, $jsonResource ): string {
        if ( $pageStatus->areThereErrors() ) {
            $out = self::createErrorMessagesReadyForAjaxOutput($pageStatus);

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
                        $url = UrlServices::make_resource_url( $ajax->url, $pageStatus );
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

                $out = array_merge( $out, self::createErrorMessagesReadyForAjaxOutput($pageStatus) );
                $out = array_merge( $out, self::createInfoMessagesReadyForAjaxOutput($pageStatus) );
                $out = array_merge( $out, self::createWarningMessagesReadyForAjaxOutput($pageStatus) );
                $out = array_merge( $out, self::createSuccessMessagesReadyForAjaxOutput($pageStatus) );

            }

            return json_encode($out);
        }
        return '';
    }

    /**
     * @param PageStatus $pageStatus
     * @return array|\stdClass[]
     */
    public static function createErrorMessagesReadyForAjaxOutput(PageStatus $pageStatus ): array {
        $out = [];
        if ($pageStatus->areThereErrors()) {
            $out = array_map(
                function ($errorstring) {
                    $msgBlock = new BaseHTMLMessages;
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
     * @return array|\stdClass[]
     */
    public static function createInfoMessagesReadyForAjaxOutput(PageStatus $pageStatus): array {
        $out = [];
        if ($pageStatus->areThereInfos()) {
            $out = array_map(
                function ($infoString) {
                    $msgBlock = new BaseHTMLMessages;
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
     * @return array|\stdClass[]
     */
    public static function createWarningMessagesReadyForAjaxOutput(PageStatus $pageStatus): array {
        $out = [];
        if ($pageStatus->areThereWarnings()) {
            $out = array_map(
                function ($warningString) {
                    $msgBlock = new BaseHTMLMessages;
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
     * @return array|\stdClass[]
     */
    public static function createSuccessMessagesReadyForAjaxOutput(PageStatus $pageStatus): array {
        $out = [];
        if ($pageStatus->areThereSuccesses()) {
            $out = array_map(
                function ($successString) {
                    $msgBlock = new BaseHTMLMessages;
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
