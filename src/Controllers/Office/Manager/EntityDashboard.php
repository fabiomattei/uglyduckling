<?php

namespace Fabiom\UglyDuckling\Controllers\Office\Manager;

use Fabiom\UglyDuckling\Common\Controllers\ManagerEntityController;
use Fabiom\UglyDuckling\Common\Database\QueryReturnedValues;
use Fabiom\UglyDuckling\Common\Router\Router;
use Fabiom\UglyDuckling\Common\Database\QueryExecuter;
use Fabiom\UglyDuckling\Common\Json\JsonTemplates\QueryBuilder;
use Fabiom\UglyDuckling\Common\Json\JsonTemplates\Menu\MenuJsonTemplate;
use Fabiom\UglyDuckling\Common\Json\JsonTemplates\Dashboard\DashboardJsonTemplate;

/**
 * User: Fabio Mattei
 * Date: 31/10/2018
 * Time: 08:10
 */
class EntityDashboard extends ManagerEntityController {

    public $menubuilder;
    public /* MenuJsonTemplate */ $jsonTemplateFactoriesContainer;

    function __construct() {
        $this->queryExecuter = new QueryExecuter;
        $this->queryExecuter->setLogger($this->logger);
        $this->queryBuilder = new QueryBuilder;
        $this->menubuilder = new MenuJsonTemplate;
        $this->dashboardJsonTemplate = new DashboardJsonTemplate;
    }

    /**
     * @throws GeneralException
     */
    public function getRequest() {
        $menuresource = $this->jsonloader->loadResource( $this->sessionWrapper->getSessionGroup() );

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
        $this->jsonTemplateFactoriesContainer->setAction($this->routerContainer->make_url( Router::ROUTE_OFFICE_ENTITY_DASHBOARD, 'res='.$this->getParameters['res'] ));

        $this->menubuilder->setMenuStructure( $menuresource );
        $this->menubuilder->setJsonTemplateFactoriesContainer( $this->jsonTemplateFactoriesContainer );

        $htmlBlock = $this->jsonTemplateFactoriesContainer->getHTMLBlock( $this->resource );

        $this->title = $this->setup->getAppNameForPageTitle() . ' :: Dashboard';

        $this->menucontainer    = array( $this->menubuilder->createMenu() );
        $this->leftcontainer    = array();
        $this->centralcontainer = ( $htmlBlock );
    }

    public function postRequest() {
        $this->postresource = $this->jsonloader->loadResource( $this->getParameters['postres'] );

        $conn = $this->dbconnection->getDBH();
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

        $this->redirectToPreviousPage();
    }

    public function show_second_get_error_page() {
        throw new ErrorPageException('Error page exception function show_get_error_page()');
    }

}
