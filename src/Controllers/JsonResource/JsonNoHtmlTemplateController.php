<?php

namespace Fabiom\UglyDuckling\Controllers\JsonResource;

use Fabiom\UglyDuckling\Common\Controllers\JsonEntityController;
use Fabiom\UglyDuckling\Common\Database\QueryReturnedValues;
use Fabiom\UglyDuckling\Common\Database\QuerySet;
use Fabiom\UglyDuckling\Common\Router\Router;
use Fabiom\UglyDuckling\Common\Database\QueryExecuter;
use Fabiom\UglyDuckling\Common\Json\JsonTemplates\QueryBuilder;
use Fabiom\UglyDuckling\Common\Json\JsonTemplates\Dashboard\DashboardJsonTemplate;

/**
 * User: Fabio Mattei
 * Date: 31/10/2018
 * Time: 08:10
 */
class JsonNoHtmlTemplateController extends JsonEntityController {

    function __construct() {
        $this->queryExecuter = new QueryExecuter;
        $this->queryExecuter->setLogger($this->logger);
        $this->queryBuilder = new QueryBuilder;
        $this->dashboardJsonTemplate = new DashboardJsonTemplate;
    }

    /**
     * @throws GeneralException
     */
    public function getRequest() {
        $this->templateFile = 'notemplate';

        $this->jsonTemplateFactoriesContainer->setHtmlTemplateLoader( $this->htmlTemplateLoader );
        $this->jsonTemplateFactoriesContainer->setJsonloader($this->jsonloader);
        $this->jsonTemplateFactoriesContainer->setSessionWrapper( $this->getSessionWrapper() );
        $this->jsonTemplateFactoriesContainer->setServerWrapper($this->serverWrapper);
        $this->jsonTemplateFactoriesContainer->setLinkBuilder( $this->linkBuilder );
        $this->jsonTemplateFactoriesContainer->setDbconnection($this->dbconnection);
        $this->jsonTemplateFactoriesContainer->setRouter($this->routerContainer);
        $this->jsonTemplateFactoriesContainer->setJsonloader($this->jsonloader);
        $this->jsonTemplateFactoriesContainer->setParameters($this->getParameters);
        $this->jsonTemplateFactoriesContainer->setLogger($this->logger);
        $this->jsonTemplateFactoriesContainer->setSetup($this->setup);
        $this->jsonTemplateFactoriesContainer->setAction($this->routerContainer->make_url( Router::ROUTE_OFFICE_ENTITY_DASHBOARD, 'res='.$this->getParameters['res'] ));


        $htmlBlock = $this->jsonTemplateFactoriesContainer->getHTMLBlock( $this->resource );

        $this->title = $this->setup->getAppNameForPageTitle() . ' :: No template';

        $this->centralcontainer = ( $htmlBlock );
    }

    public function postRequest() {
        $this->templateFile = 'notemplate';

        $this->postresource = $this->jsonloader->loadResource( $this->getParameters['postres'] );

        $conn = $this->dbconnection->getDBH();

        // performing transactions
        if (isset($this->postresource->post->transactions)) {
            $returnedIds = new QueryReturnedValues;
            try {
                //$conn->beginTransaction();
                $this->queryExecuter->setDBH( $conn );
                foreach ($this->postresource->post->transactions as $transaction) {
                    $this->queryExecuter->setQueryBuilder( $this->queryBuilder );
                    $this->queryExecuter->setQueryStructure( $transaction );
                    $this->queryExecuter->setPostParameters( $this->postParameters );
                    $this->queryExecuter->setLogger( $this->logger );
                    $this->queryExecuter->setSessionWrapper( $this->sessionWrapper );
                    $this->queryExecuter->setQueryReturnedValues( $returnedIds );
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
                $this->logger->write($e->getMessage(), __FILE__, __LINE__);
            }
        }

        // session updates
        if (isset($this->postresource->post->sessionupdates)) {
            $querySet = new QuerySet;

            $this->queryExecuter->setDBH($conn);
            $this->queryExecuter->setQueryBuilder($this->queryBuilder);
            $this->queryExecuter->setParameters(array());
            $this->queryExecuter->setPostParameters(array());
            $this->queryExecuter->setSessionWrapper($this->sessionWrapper);

            if (isset($this->postresource->post->sessionupdates->queryset) AND is_array($this->postresource->post->sessionupdates->queryset)) {
                foreach ($this->postresource->post->sessionupdates->queryset as $query) {
                    $this->queryExecuter->setQueryStructure($query);
                    $result = $this->queryExecuter->executeSql();
                    $entity = $result->fetch();
                    if (isset($query->label)) {
                        $querySet->setResult($query->label, $entity);
                    } else {
                        $querySet->setResultNoKey($entity);
                    }
                }
            }

            if (isset($this->postresource->post->sessionupdates->sessionvars) AND is_array($this->postresource->post->sessionupdates->sessionvars)) {
                foreach ($this->postresource->post->sessionupdates->sessionvars as $sessionvar) {
                    if ( isset( $sessionvar->querylabel ) AND isset( $sessionvar->sqlfield ) ) {
                        if ( isset($querySet->getResult($sessionvar->querylabel)->{$sessionvar->sqlfield}) ) {
                            $this->sessionWrapper->setSessionParameter($sessionvar->name, $querySet->getResult($sessionvar->querylabel)->{$sessionvar->sqlfield} );
                        }
                    }
                    if ( isset( $sessionvar->constantparamenter ) ) {
                        $this->sessionWrapper->setSessionParameter($sessionvar->name, $sessionvar->constantparamenter);
                    }
                    if ( isset( $sessionvar->getparamenter ) ) {
                        $this->sessionWrapper->setSessionParameter($sessionvar->name, $this->getParameters[$sessionvar->getparamenter]);
                    }
                    if ( isset( $sessionvar->postparamenter ) ) {
                        $this->sessionWrapper->setSessionParameter($sessionvar->name, $this->postParameters[$sessionvar->postparamenter]);
                    }
                }
            }
        }

        // redirect
        if (isset($this->postresource->post->redirect)) {
            if (isset($this->postresource->post->redirect->internal) AND $this->postresource->post->redirect->internal->type === 'onepageback') {
                $this->redirectToPreviousPage();
            } elseif (isset($this->postresource->post->redirect->internal) AND $this->postresource->post->redirect->internal->type === 'twopagesback') {
                $this->redirectToSecondPreviousPage();
            } elseif ( isset($this->postresource->post->redirect->action) AND isset($this->postresource->post->redirect->action->resource) ) {
                $this->redirectToPage(
                    $this->routerContainer->make_url(
                        $this->jsonloader->getActionRelatedToResource($this->postresource->post->redirect->action->resource), 'res='.$this->postresource->post->redirect->action->resource
                    )
                );
            } else {
                $this->redirectToPreviousPage();
            }
        } else {
            $this->redirectToPreviousPage();
        }
    }

    public function show_second_get_error_page() {
        throw new ErrorPageException('Error page exception function show_get_error_page()');
    }

}
