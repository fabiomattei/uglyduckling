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
 * This class handle the deleting of a document entity instance
 *
 * It needs two parameters:
 * $_GET['res'] the resource type of the document
 * $_GET['id'] the id of the document
 */
class DocumentDelete extends ManagerDocumentSenderController {

    private $documentDao;
	
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
		$this->documentDao->delete( $this->getParameters['id'] );
		
		// applying the possible logics
		$this->queryExecuter->setDBH( $this->dbconnection->getDBH() );

        // if there are logics to implement
        if ( isset($this->resource->ondelete->logics) ) {
            foreach ($this->resource->ondelete->logics as $logic) {
                $this->queryExecuter->setQueryBuilder($this->queryBuilder);
                $this->queryExecuter->setQueryStructure($logic);
                $this->queryExecuter->setParameters($this->getParameters);

                $this->queryExecuter->executeQuery();
            }
        }

        $this->redirectToPreviousPage();
	}

}
