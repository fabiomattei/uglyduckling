<?php

namespace Firststep\Controllers\Office\Manager;

use Firststep\Common\Controllers\ManagerEntityController;
use Firststep\Templates\Blocks\Sidebars\AdminSidebar;
use Firststep\Common\Json\JsonBlockFormParser;
use Firststep\Common\Router\Router;
use Firststep\Common\Database\QueryExecuter;
use Firststep\Common\Builders\QueryBuilder;
use Firststep\Common\Builders\MenuBuilder;

/**
 * User: Fabio
 * Date: 17/08/2018
 * Time: 07:07
 */
class EntityForm extends ManagerEntityController {

    private $queryExecuter;
    private $queryBuilder;
    private $jsonBlockFormParser;
    private $menubuilder;

    function __construct() {
		$this->queryExecuter = new QueryExecuter;
		$this->queryBuilder = new QueryBuilder;
		$this->jsonBlockFormParser = new JsonBlockFormParser;
		$this->menubuilder = new MenuBuilder;
    }

    /**
     * @throws GeneralException
     */
	public function getRequest() {
		$this->queryExecuter->setDBH( $this->dbconnection->getDBH() );
	    $this->queryExecuter->setQueryBuilder( $this->queryBuilder );
	    $this->queryExecuter->setQueryStructure( $this->resource->get->query );
	    $this->queryExecuter->setGetParameters( $this->internalGetParameters );

		$result = $this->queryExecuter->executeQuery();
		$entity = $result->fetch();

		$formBlock = $this->jsonBlockFormParser->parse( 
			$this->resource,
			$entity,
			$this->router->make_url( Router::ROUTE_OFFICE_ENTITY_FORM, 'res='.$this->getParameters['res'] )
		);
		
		$this->title = $this->setup->getAppNameForPageTitle() . ' :: Office form';

		$menuresource = $this->jsonloader->loadResource( $this->sessionWrapper->getSessionGroup() );
		$this->menubuilder->setMenuStructure( $menuresource );
		$this->menubuilder->setRouter( $this->router );
		
		$this->menucontainer    = array( $this->menubuilder->createMenu() );
		$this->leftcontainer    = array( new AdminSidebar( $this->setup->getAppNameForPageTitle(), Router::ROUTE_ADMIN_ENTITY_LIST, $this->router ) );
		$this->centralcontainer = array( $formBlock );
	}
	
	public function postRequest() {
		$this->queryExecuter->setDBH( $this->dbconnection->getDBH() );

		foreach ($this->resource->post->logics as $logic) {
			$this->queryExecuter->setQueryBuilder( $this->queryBuilder );
	    	$this->queryExecuter->setQueryStructure( $logic );
	    	$this->queryExecuter->setPostParameters( $this->postParameters );

			$this->queryExecuter->executeQuery();
		}

		$this->redirectToSecondPreviousPage();
	}

    public function show_second_get_error_page() {
        throw new ErrorPageException('Error page exception function show_get_error_page()');
    }

}
