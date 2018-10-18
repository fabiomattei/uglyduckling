<?php

namespace Firststep\Controllers\Office\Document;

use Firststep\Common\Controllers\ManagerDocumentSenderController;
use Firststep\Templates\Blocks\Menus\AdminMenu;
use Firststep\Templates\Blocks\Sidebars\AdminSidebar;
use Firststep\Common\Json\JsonBlockParser;
use Firststep\Common\Blocks\StaticTable;
use Firststep\Common\Blocks\Button;
use Firststep\Common\Router\Router;
use Firststep\Common\Database\QueryExecuter;
use Firststep\Common\Builders\QueryBuilder;
use Firststep\Common\Builders\ValidationBuilder;
use Firststep\Common\Database\DocumentDao;

/**
 * 
 */
class DocumentAccept extends ManagerDocumentSenderController {
	
    function __construct() {
		$this->queryExecuter = new QueryExecuter;
		$this->queryBuilder = new QueryBuilder;
		$this->documentDao = new DocumentDao;
    }
	
    /**
     * @throws GeneralException
     */
	public function getRequest() {
		// updating the document table
		$this->documentDao->setDBH( $this->dbconnection->getDBH() );
		$this->documentDao->setTableName( $this->resource->name );
		
		// deleting from database
		$this->documentDao->delete( $this->getParameters['id'] )
		
		// applying the possible logics
		$this->queryExecuter->setDBH( $this->dbconnection->getDBH() );

		foreach ( $this->resource->ondelete->logics as $logic ) {
			$this->queryExecuter->setQueryBuilder( $this->queryBuilder );
	    	$this->queryExecuter->setQueryStructure( $logic );
	    	$this->queryExecuter->setParameters( $this->getParameters );

			$this->queryExecuter->executeQuery();
		}

        $this->redirectToPreviousPage();
	}

}
