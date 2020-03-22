<?php

/**
 * User: Fabio Mattei
 * Date: 13/07/2018
 * Time: 20:39
 */

namespace Fabiom\UglyDuckling\Common\Json\JsonTemplates\Table;

use Fabiom\UglyDuckling\Common\Blocks\EmptyHTMLBlock;
use Fabiom\UglyDuckling\Common\Blocks\StaticTable;
use Fabiom\UglyDuckling\Common\Database\QueryExecuter;
use Fabiom\UglyDuckling\Common\Json\JsonTemplates\JsonTemplate;
use Fabiom\UglyDuckling\Common\Json\JsonTemplates\LinkBuilder;
use Fabiom\UglyDuckling\Common\Json\JsonTemplates\QueryBuilder;

class TableJsonTemplate extends JsonTemplate {

    const blocktype = 'table';
    const GET_METHOD = 'GET';
    const POST_METHOD = 'POST';
    private $query;
    private $method;

    function __construct() {
        $this->queryExecuter = new QueryExecuter;
        $this->queryBuilder = new QueryBuilder;
        $this->linkBuilder = new LinkBuilder;
        $this->query = '';
        $this->method = self::GET_METHOD;
    }

    /**
     * @param string $method
     * refers to http method: GET or POST
     */
    public function setMethod(string $method) {
        $this->method = $method;
    }

    public function getTableFromResource() {
        return $this->method === self::GET_METHOD ? $this->resource->get->table : $this->resource->post->table;
    }

    public function createTable() {
        $queryExecuter = $this->jsonTemplateFactoriesContainer->getQueryExecuter();
        $queryBuilder = $this->jsonTemplateFactoriesContainer->getQueryBuilder();
        $parameters = $this->jsonTemplateFactoriesContainer->getParameters();
        $dbconnection = $this->jsonTemplateFactoriesContainer->getDbconnection();
        $logger = $this->jsonTemplateFactoriesContainer->getLogger();
        $htmlTemplateLoader = $this->jsonTemplateFactoriesContainer->getHtmlTemplateLoader();
        $sessionWrapper = $this->jsonTemplateFactoriesContainer->getSessionWrapper();
        $linkBuilder = $this->jsonTemplateFactoriesContainer->getLinkBuilder();
        $jsonloader = $this->jsonTemplateFactoriesContainer->getJsonloader();
        $routerContainer = $this->jsonTemplateFactoriesContainer->getRouterContainer();
        $serverWrapper = $this->jsonTemplateFactoriesContainer->getServerWrapper();

        // If there are dummy data they take precedence in order to fill the table
        if ( isset($this->resource->get->dummydata) ) {
            $entities = $this->resource->get->dummydata;
        } else {
            // If there is a query I look for data to fill the table,
            // if there is not query I do not
            if ( isset($this->resource->get->query) AND isset($dbconnection) ) {
                $queryExecuter->setDBH( $dbconnection->getDBH() );
                $queryExecuter->setQueryBuilder( $queryBuilder );
                $queryExecuter->setLogger( $logger );
                $queryExecuter->setSessionWrapper( $sessionWrapper );
                if ( $serverWrapper->isGetRequest() ) {
                    $query = $this->resource->get->query;
                    if (isset( $this->parameters ) ) $queryExecuter->setParameters( $parameters );
                } else {
                    $query = $this->resource->post->query;
                    if (isset( $this->parameters ) ) $queryExecuter->setPostParameters( $parameters );
                }
                $queryExecuter->setQueryStructure( $query );
                $entities = $queryExecuter->executeSql();
            }
        }

        $table = $this->getTableFromResource();

		$tableBlock = new StaticTable;
        $tableBlock->setHtmlTemplateLoader( $htmlTemplateLoader );
		$tableBlock->setTitle($table->title ?? '');
		
		$tableBlock->addTHead();
		$tableBlock->addRow();
		foreach ($table->fields as $field) {
			$tableBlock->addHeadLineColumn($field->headline);
		}
		$tableBlock->addHeadLineColumn(''); // adding one more for actions
		$tableBlock->closeRow();
		$tableBlock->closeTHead();
		
		$tableBlock->addTBody();
		foreach ($entities as $entity) {
			$tableBlock->addRow();
			foreach ($table->fields as $field) {
				$tableBlock->addColumn($this->getValue($field, $parameters, array(), $sessionWrapper, $entity));
			}
			$links = '';
            if (isset($table->actions) AND is_array($table->actions)) {
                foreach ( $table->actions as $action ) {
                $links .= $linkBuilder->getButton( $jsonloader, $routerContainer, $action->label, $action->resource, $action->parameters, $entity );
                }
            }
			$tableBlock->addUnfilteredColumn( $links );
			$tableBlock->closeRow();
		}
		$tableBlock->closeTBody();
		
        return $tableBlock;
    }

    /**
     * Return a object that inherit from BaseHTMLBlock class
     * It is an object that has to generate HTML code
     *
     * @return EmptyHTMLBlock
     */
    public function createHTMLBlock() {
        return $this->createTable();
    }

}
