<?php

namespace Fabiom\UglyDuckling\Controllers\Office\Manager;

use Fabiom\UglyDuckling\Common\Json\JsonTemplates\JsonTemplateFactory;
use Fabiom\UglyDuckling\Common\Controllers\ManagerEntityController;
use Fabiom\UglyDuckling\Common\Router\Router;
use Fabiom\UglyDuckling\Common\Database\QueryExecuter;
use Fabiom\UglyDuckling\Common\Json\JsonTemplates\QueryBuilder;
use Fabiom\UglyDuckling\Common\Json\JsonTemplates\MenuBuilder;
use Fabiom\UglyDuckling\Common\Json\JsonTemplates\Dashboard\DashboardJsonTemplate;

/**
 * User: Fabio Mattei
 * Date: 31/10/2018
 * Time: 08:10
 */
class EntityDashboard extends ManagerEntityController {

    private $jsonTemplateFactory;

    function __construct() {
        $this->queryExecuter = new QueryExecuter;
        $this->queryBuilder = new QueryBuilder;
        $this->menubuilder = new MenuBuilder;
        $this->jsonTemplateFactory = new JsonTemplateFactory;
        $this->dashboardJsonTemplate = new DashboardJsonTemplate;
    }

    /**
     * @throws GeneralException
     */
    public function getRequest() {
        $menuresource = $this->jsonloader->loadResource( $this->sessionWrapper->getSessionGroup() );
        $this->menubuilder->setMenuStructure( $menuresource );
        $this->menubuilder->setRouter( $this->router );

        $this->jsonTemplateFactory->setHtmlTemplateLoader( $this->htmlTemplateLoader );
        $this->jsonTemplateFactory->setJsonloader($this->jsonloader);
        $this->jsonTemplateFactory->setDbconnection($this->dbconnection);
        $this->jsonTemplateFactory->setRouter($this->router);
        $this->jsonTemplateFactory->setJsonloader($this->jsonloader);
        $this->jsonTemplateFactory->setParameters($this->getParameters);
        $this->jsonTemplateFactory->setAction($this->router->make_url( Router::ROUTE_OFFICE_ENTITY_DASHBOARD, 'res='.$this->getParameters['res'] ));

        $htmlBlock = $this->jsonTemplateFactory->getHTMLBlock( $this->resource );

        $this->title = $this->setup->getAppNameForPageTitle() . ' :: Dashboard';

        $this->menucontainer    = array( $this->menubuilder->createMenu() );
        $this->leftcontainer    = array();
        $this->centralcontainer = ( $htmlBlock );
    }

    public function postRequest() {
        $this->postresource = $this->jsonloader->loadResource( $this->getParameters['postres'] );

        $conn = $this->dbconnection->getDBH();
        try {
            $conn->beginTransaction();
            $this->queryExecuter->setDBH( $conn );
            foreach ($this->postresource->post->transactions as $transaction) {
                $this->queryExecuter->setQueryBuilder( $this->queryBuilder );
                $this->queryExecuter->setQueryStructure( $transaction );
                $this->queryExecuter->setPostParameters( $this->postParameters );
                $this->queryExecuter->executeQuery();
            }
            $conn->commit();
        }
        catch (PDOException $e) {
            $conn->rollBack();
            $logger = new Logger();
            $logger->write($e->getMessage(), __FILE__, __LINE__);
        }

        $this->redirectToPreviousPage();
    }

    public function show_second_get_error_page() {
        throw new ErrorPageException('Error page exception function show_get_error_page()');
    }

}
