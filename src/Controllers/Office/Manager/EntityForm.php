<?php

namespace Fabiom\UglyDuckling\Controllers\Office\Manager;

use Fabiom\UglyDuckling\Common\Database\QueryReturnedValues;
use Fabiom\UglyDuckling\Common\Json\JsonTemplates\Form\FormJsonTemplate;
use Fabiom\UglyDuckling\Common\Json\JsonTemplates\QueryBuilder;
use Fabiom\UglyDuckling\Common\Controllers\ManagerEntityController;
use Fabiom\UglyDuckling\Common\Database\QueryExecuter;
use Fabiom\UglyDuckling\Common\Router\Router;
use Fabiom\UglyDuckling\Common\Json\JsonTemplates\MenuBuilder;

/**
 * User: Fabio
 * Date: 17/08/2018
 * Time: 07:07
 */
class EntityForm extends ManagerEntityController {

    private $menubuilder;
    private $formBuilder;
    private $queryExecuter;
    private $queryBuilder;

    function __construct() {
		$this->formBuilder = new FormJsonTemplate;
		$this->menubuilder = new MenuBuilder;
        $this->queryExecuter = new QueryExecuter;
        $this->queryBuilder = new QueryBuilder;
    }

    /**
     * @throws GeneralException
     */
	public function getRequest() {
		$this->title = $this->setup->getAppNameForPageTitle() . ' :: Office form';

		$menuresource = $this->jsonloader->loadResource( $this->sessionWrapper->getSessionGroup() );
		$this->menubuilder->setMenuStructure( $menuresource );
		$this->menubuilder->setRouter( $this->routerContainer );

        $this->jsonTemplateFactoriesContainer->setHtmlTemplateLoader( $this->htmlTemplateLoader );
        $this->jsonTemplateFactoriesContainer->setJsonloader($this->jsonloader);
        $this->jsonTemplateFactoriesContainer->setSessionWrapper( $this->getSessionWrapper() );
        $this->jsonTemplateFactoriesContainer->setDbconnection($this->dbconnection);
        $this->jsonTemplateFactoriesContainer->setRouter($this->routerContainer);
        $this->jsonTemplateFactoriesContainer->setJsonloader($this->jsonloader);
        $this->jsonTemplateFactoriesContainer->setParameters($this->getParameters);
        $this->jsonTemplateFactoriesContainer->setLogger($this->logger);
        $this->jsonTemplateFactoriesContainer->setAction($this->routerContainer->make_url( Router::ROUTE_OFFICE_ENTITY_FORM, 'res='.$this->getParameters['res'] ));

		$this->menucontainer    = array( $this->menubuilder->createMenu() );
		$this->leftcontainer    = array();
		$this->centralcontainer = array( $this->jsonTemplateFactoriesContainer->getHTMLBlock( $this->resource ) );
	}
	
	public function postRequest() {
        $conn = $this->dbconnection->getDBH();
        $returnedIds = new QueryReturnedValues;
        try {
            //$conn->beginTransaction();
            $this->queryExecuter->setDBH($conn);
            $this->queryExecuter->setQueryBuilder($this->queryBuilder);
            $this->queryExecuter->setPostParameters($this->postParameters);
            $this->queryExecuter->setSessionWrapper( $this->sessionWrapper );
            $this->queryExecuter->setReturnedIds( $returnedIds );
            foreach ($this->resource->post->transactions as $transaction) {
                $this->queryExecuter->setQueryStructure($transaction);
                if ( $this->queryExecuter->getSqlStatmentType() == QueryExecuter::INSERT) {
                    if (isset($transaction->label)) {
                        $returnedIds->setValue($transaction->label, $this->queryExecuter->executeQuery());
                    } else {
                        $returnedIds->setValueNoKey($this->queryExecuter->executeQuery());
                    }
                } else {
                    $this->queryExecuter->executeQuery();
                }
            }
            //$conn->commit();
        }
        catch (\PDOException $e) {
            $conn->rollBack();
            $this->logger->write($e->getMessage(), __FILE__, __LINE__);
        }

		$this->redirectToSecondPreviousPage();
	}

    public function show_get_error_page() {
        parent::show_get_error_page(); // TODO: Change the autogenerated stub
    }

    public function show_post_error_page() {
	    $this->messages->setError( $this->readableErrors );

        $this->title = $this->setup->getAppNameForPageTitle() . ' :: Office form';

        $menuresource = $this->jsonloader->loadResource( $this->sessionWrapper->getSessionGroup() );
        $this->menubuilder->setMenuStructure( $menuresource );
        $this->menubuilder->setRouter( $this->routerContainer );

        $this->formBuilder->setRouter( $this->routerContainer );
        $this->formBuilder->setResource( $this->resource );
        $this->formBuilder->setParameters( $this->internalGetParameters );
        $this->formBuilder->setDbconnection( $this->dbconnection );
        $this->formBuilder->setHtmlTemplateLoader( $this->htmlTemplateLoader );
        $this->formBuilder->setAction($this->routerContainer->make_url( Router::ROUTE_OFFICE_ENTITY_FORM, 'res='.$this->getParameters['res'] ));

        $this->menucontainer    = array( $this->menubuilder->createMenu() );
        $this->leftcontainer    = array();
        $this->centralcontainer = array( $this->formBuilder->createForm() );
    }


}
