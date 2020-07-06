<?php

namespace Fabiom\UglyDuckling\Controllers\JsonResource;

use Fabiom\UglyDuckling\Common\Controllers\JsonResourceBasicController;
use Fabiom\UglyDuckling\Common\Database\QueryReturnedValues;
use Fabiom\UglyDuckling\Common\Database\QuerySet;
use Fabiom\UglyDuckling\Common\Exceptions\ErrorPageException;
use Fabiom\UglyDuckling\Common\Router\ResourceRouter;
use Fabiom\UglyDuckling\Common\Database\QueryExecuter;
use Fabiom\UglyDuckling\Common\Json\JsonTemplates\QueryBuilder;
use Fabiom\UglyDuckling\Common\Json\JsonTemplates\Menu\MenuJsonTemplate;
use Fabiom\UglyDuckling\Common\Json\JsonTemplates\Dashboard\DashboardJsonTemplate;

/**
 * User: Fabio Mattei
 * Date: 31/10/2018
 * Time: 08:10
 */
class JsonDashboardController extends JsonResourceBasicController {

    public $menubuilder;
    public /* MenuJsonTemplate */ $jsonTemplateFactoriesContainer;

    function __construct() {
        $this->menubuilder = new MenuJsonTemplate;
        $this->dashboardJsonTemplate = new DashboardJsonTemplate;
    }

    /**
     * @throws GeneralException
     */
    public function getRequest() {
        $menuresource = $this->applicationBuilder->getJsonloader()->loadResource( $this->pageStatus->getSessionWrapper()->getSessionGroup() );

        // if resource->get->sessionupdates is set I need to update the session
        if ( isset($this->resource->get->sessionupdates) ) $this->pageStatus->updateSession( $this->resource->get->sessionupdates );

        $this->applicationBuilder->getJsonTemplateFactoriesContainer()->setApplicationBuilder($this->applicationBuilder);
        $this->applicationBuilder->getJsonTemplateFactoriesContainer()->setPageStatus($this->pageStatus);
        $this->applicationBuilder->getJsonTemplateFactoriesContainer()->setParameters($this->getParameters);
        $this->applicationBuilder->getJsonTemplateFactoriesContainer()->setAction( $this->applicationBuilder->getRouterContainer()->makeRelativeUrl( ResourceRouter::ROUTE_OFFICE_ENTITY_DASHBOARD, 'res='.$this->getParameters['res'] ) );

        $this->menubuilder->setMenuStructure( $menuresource );
        $this->menubuilder->setJsonTemplateFactoriesContainer( $this->applicationBuilder->getJsonTemplateFactoriesContainer() );

        $htmlBlock = $this->applicationBuilder->getHTMLBlock( $this->resource );

        $this->title = $this->applicationBuilder->getAppNameForPageTitle() . ' :: Dashboard';

        $this->menucontainer    = array( $this->menubuilder->createMenu() );
        $this->leftcontainer    = array();
        $this->centralcontainer = ( $htmlBlock );
    }

    public function postRequest() {
        $this->queryExecuter = new QueryExecuter;
        $this->queryBuilder = new QueryBuilder;
        $this->queryExecuter->setLogger($this->applicationBuilder->getLogger());

        $this->postresource = $this->applicationBuilder->getJsonloader()->loadResource( $this->getParameters['postres'] );

        $conn = $this->applicationBuilder->getDbconnection()->getDBH();

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
                    $this->queryExecuter->setLogger( $this->applicationBuilder->getLogger() );
                    $this->queryExecuter->setSessionWrapper( $this->pageStatus->getSessionWrapper() );
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
                $this->applicationBuilder->getLogger()->write($e->getMessage(), __FILE__, __LINE__);
            }
        }

        // session updates
        if (isset($this->postresource->post->sessionupdates)) {
            $querySet = new QuerySet;

            $this->queryExecuter->setDBH($conn);
            $this->queryExecuter->setQueryBuilder($this->queryBuilder);
            $this->queryExecuter->setParameters(array());
            $this->queryExecuter->setPostParameters(array());
            $this->queryExecuter->setSessionWrapper($this->pageStatus->getSessionWrapper());

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
                            $this->pageStatus->getSessionWrapper()->setSessionParameter($sessionvar->name, $querySet->getResult($sessionvar->querylabel)->{$sessionvar->sqlfield} );
                        }
                    }
                    if ( isset( $sessionvar->constantparamenter ) ) {
                        $this->pageStatus->getSessionWrapper()->setSessionParameter($sessionvar->name, $sessionvar->constantparamenter);
                    }
                    if ( isset( $sessionvar->getparamenter ) ) {
                        $this->pageStatus->getSessionWrapper()->setSessionParameter($sessionvar->name, $this->getParameters[$sessionvar->getparamenter]);
                    }
                    if ( isset( $sessionvar->postparamenter ) ) {
                        $this->pageStatus->getSessionWrapper()->setSessionParameter($sessionvar->name, $this->postParameters[$sessionvar->postparamenter]);
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
                    $this->applicationBuilder->getRouterContainer()->makeRelativeUrl(
                        $this->applicationBuilder->getJsonloader()->getActionRelatedToResource($this->postresource->post->redirect->action->resource), 'res='.$this->postresource->post->redirect->action->resource
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
