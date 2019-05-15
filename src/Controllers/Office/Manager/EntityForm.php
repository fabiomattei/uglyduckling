<?php

namespace Firststep\Controllers\Office\Manager;

use Firststep\Common\Json\Builders\FormBuilder;
use Firststep\Common\Json\Builders\QueryBuilder;
use Firststep\Common\Controllers\ManagerEntityController;
use Firststep\Common\Database\QueryExecuter;
use Firststep\Common\Router\Router;
use Firststep\Common\Json\Builders\MenuBuilder;

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
		$this->formBuilder = new FormBuilder;
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
		$this->menubuilder->setRouter( $this->router );

        $this->formBuilder->setRouter( $this->router );
        $this->formBuilder->setResource( $this->resource );
        $this->formBuilder->setParameters( $this->internalGetParameters );
        $this->formBuilder->setDbconnection( $this->dbconnection );
        $this->formBuilder->setHtmlTemplateLoader( $this->htmlTemplateLoader );
        $this->formBuilder->setAction($this->router->make_url( Router::ROUTE_OFFICE_ENTITY_FORM, 'res='.$this->getParameters['res'] ));

		$this->menucontainer    = array( $this->menubuilder->createMenu() );
		$this->leftcontainer    = array();
		$this->centralcontainer = array( $this->formBuilder->createForm() );
	}
	
	public function postRequest() {
        $conn = $this->dbconnection->getDBH();
        try {
            $conn->beginTransaction();
            $this->queryExecuter->setDBH($conn);
            $this->queryExecuter->setQueryBuilder($this->queryBuilder);
            $this->queryExecuter->setPostParameters($this->postParameters);
            foreach ($this->resource->post->transactions as $transaction) {
                $this->queryExecuter->setQueryStructure($transaction);
                $this->queryExecuter->executeQuery();
            }
            $conn->commit();
        }
        catch (PDOException $e) {
            $conn->rollBack();
            $logger = new Logger();
            $logger->write($e->getMessage(), __FILE__, __LINE__);
        }

		$this->redirectToSecondPreviousPage();
	}

}
