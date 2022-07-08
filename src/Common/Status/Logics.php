<?php

namespace Fabiom\UglyDuckling\Common\Status;

use Fabiom\UglyDuckling\Common\Database\QueryExecuter;

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

    public static function performUseCases( PageStatus $pageStatus, ApplicationBuilder $applicationBuilder, $jsonResource ): void {
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

    public static function performAjaxCallGet( PageStatus $pageStatus, ApplicationBuilder $applicationBuilder, $jsonResource ): string {
        if ( isset($jsonResource->get->ajax) and is_array($jsonResource->get->ajax)) {
            $out = [];
            foreach ($jsonResource->get->ajax as $ajax) {
                if ( $ajax->type == 'delete' ) {
                    $myAjaxResponse = new \stdClass();
                    $myAjaxResponse->type = 'delete';
                    if (is_string( $ajax->destination )) {
                        $myAjaxResponse->destination = $ajax->destination;
                    } else if ( is_object( $ajax->destination ) ) {
                        $myAjaxResponse->destination = $pageStatus->getValue( $ajax->destination );
                    } else {
                        $myAjaxResponse->destination = '';
                    }
                    $out[] = $myAjaxResponse;
                }

                if ( $ajax->type == 'add' OR $ajax->type == 'update') {
                    $myAjaxResponse = new \stdClass();
                    $myAjaxResponse->type = $ajax->type;
                    if (is_string( $ajax->destination )) {
                        $myAjaxResponse->destination = $ajax->destination;
                    } else if ( is_object( $ajax->destination ) ) {
                        $myAjaxResponse->destination = $pageStatus->getValue( $ajax->destination );
                    } else {
                        $myAjaxResponse->destination = '';
                    }
                    if (is_string( $ajax->body )) {
                        $myAjaxResponse->body = $ajax->body;
                    } else if ( is_object( $ajax->body ) ) {
                        $myAjaxResponse->body = $pageStatus->getValue( $ajax->body );
                    } else {
                        $myAjaxResponse->body = '';
                    }
                    $out[] = $myAjaxResponse;
                }
            }

            return json_encode($out);
        }
        return '';
    }

    public static function performAjaxCallPost( PageStatus $pageStatus, ApplicationBuilder $applicationBuilder, $jsonResource ): string {
        if ( isset($jsonResource->post->ajaxreponses) and is_array($jsonResource->post->ajaxreponses)) {
            $out = [];
            foreach ($jsonResource->post->ajaxreponses as $ajax) {
                if ( $ajax->type == 'delete' ) {
                    $myAjaxResponse = new \stdClass();
                    $myAjaxResponse->type = 'delete';
                    if (is_string( $ajax->destination )) {
                        $myAjaxResponse->destination = $ajax->destination;
                    } else if ( is_object( $ajax->destination ) ) {
                        $myAjaxResponse->destination = $pageStatus->getValue( $ajax->destination );
                    } else {
                        $myAjaxResponse->destination = '';
                    }
                    $out[] = $myAjaxResponse;
                }

                if ( $ajax->type == 'append' OR $ajax->type == 'overwrite') {
                    $myAjaxResponse = new \stdClass();
                    $myAjaxResponse->type = $ajax->type;
                    if (is_string( $ajax->destination )) {
                        $myAjaxResponse->destination = $ajax->destination;
                    } else if ( is_object( $ajax->destination ) ) {
                        $myAjaxResponse->destination = $pageStatus->getValue( $ajax->destination );
                    } else {
                        $myAjaxResponse->destination = '';
                    }
                    if (is_string( $ajax->body )) {
                        $myAjaxResponse->body = $ajax->body;
                    } else if ( is_object( $ajax->body ) ) {
                        $myAjaxResponse->body = $pageStatus->getValue( $ajax->body );
                    } else {
                        $myAjaxResponse->body = '';
                    }
                    $out[] = $myAjaxResponse;
                }

                if ( $ajax->type == 'appendurl' OR $ajax->type == 'overwriteurl') {
                    $myAjaxResponse = new \stdClass();
                    $myAjaxResponse->type = $ajax->type;
                    if (is_string( $ajax->destination )) {
                        $myAjaxResponse->destination = $ajax->destination;
                    } else if ( is_object( $ajax->destination ) ) {
                        $myAjaxResponse->destination = $pageStatus->getValue( $ajax->destination );
                    } else {
                        $myAjaxResponse->destination = '';
                    }
                    if (is_string( $ajax->url )) {
                        $myAjaxResponse->url = $ajax->url;
                    } else if ( is_object( $ajax->url ) ) {
                        $myAjaxResponse->url = $applicationBuilder->getRouterContainer()->make_resource_url( $ajax->url, $pageStatus );
                    } else {
                        $myAjaxResponse->url = '';
                    }
                    $out[] = $myAjaxResponse;
                }
            }

            return json_encode($out);
        }
        return '';
    }

}