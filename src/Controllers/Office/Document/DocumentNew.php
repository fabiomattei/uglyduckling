<?php

namespace Firststep\Controllers\Office\Document;

use Firststep\Common\Controllers\ManagerDocumentSenderController;
use Firststep\Templates\Blocks\Sidebars\AdminSidebar;
use Firststep\Common\Json\JsonBlockFormParser;
use Firststep\Common\Blocks\StaticTable;
use Firststep\Common\Blocks\Button;
use Firststep\Common\Router\Router;
use Firststep\Common\Database\QueryExecuter;
use Firststep\Common\Builders\QueryBuilder;
use Firststep\Common\Builders\MenuBuilder;
use Firststep\Common\Database\DocumentDao;

/**
 * This class cares about the creation of a new istance of a selected document
 * The document selected is passed in the get parameter "res"
 * This class creates the form in order to fill the data in the GET call and 
 * apply the changes to the database in the POST call
 */
class DocumentNew extends ManagerDocumentSenderController {

    function __construct() {
		$this->queryExecuter = new QueryExecuter;
		$this->queryBuilder = new QueryBuilder;
		$this->jsonBlockFormParser = new JsonBlockFormParser;
		$this->menubuilder = new MenuBuilder;
		$this->documentDao = new DocumentDao;
    }

    /**
     * @throws GeneralException
     */
	public function getRequest() {
		$this->queryExecuter->setDBH( $this->dbconnection->getDBH() );
	    $this->queryExecuter->setQueryBuilder( $this->queryBuilder );
	    $this->queryExecuter->setQueryStructure( $this->resource->query );
	    $this->queryExecuter->setParameters( $this->internalGetParameters );

		$formBlock = $this->jsonBlockFormParser->parse( 
			$this->resource, 
			null,
			$this->router->make_url( Router::ROUTE_OFFICE_DOCUMENT_NEW, 'res='.$this->getParameters['res'] )
		);
		
		$this->title = $this->setup->getAppNameForPageTitle() . ' :: Document new';

		$menuresource = $this->jsonloader->loadResource( $this->sessionWrapper->getSessionGroup() );
		$this->menubuilder->setMenuStructure( $menuresource );
		$this->menubuilder->setRouter( $this->router );
		
		$this->menucontainer    = array( $this->menubuilder->createMenu() );
		$this->leftcontainer    = array( new AdminSidebar( $this->setup->getAppNameForPageTitle(), Router::ROUTE_ADMIN_ENTITY_LIST, $this->router ) );
		$this->centralcontainer = array( $formBlock );
	}
	
	public function postRequest() {
		// updating the document table
		$this->documentDao->setDBH( $this->dbconnection->getDBH() );
		$this->documentDao->setTableName( $this->resource->name );
		
		// removing save button from parameters and adding user id and user group
		$queryparameters = $this->postParameters;
		$queryparameters['sourceuserid'] = $this->sessionWrapper->getSessionUserId();
		$queryparameters['sourcegroup'] = $this->sessionWrapper->getSessionGroup();
		unset( $queryparameters['save'] );
		
		// saving in database
		$this->documentDao->insert( $queryparameters );
		
		// applying the possible logics
		$this->queryExecuter->setDBH( $this->dbconnection->getDBH() );

        // if there are logics to implement
		if ( isset( $this->resource->oninsert->logics ) ) {
            foreach ( $this->resource->oninsert->logics as $logic ) {
                $this->queryExecuter->setQueryBuilder( $this->queryBuilder );
                $this->queryExecuter->setQueryStructure( $logic );
                $this->queryExecuter->setParameters( $this->postParameters );

                $this->queryExecuter->executeQuery();
            }
        }

		$this->redirectToSecondPreviousPage();
	}

    public function show_second_get_error_page() {
        throw new ErrorPageException('Error page exception function show_get_error_page()');
    }

}
