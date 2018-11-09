<?php

/**
 * User: Fabio Mattei
 * Date: 13/07/2018
 * Time: 20:39
 */

namespace Firststep\Common\Builders;

use Firststep\Common\Blocks\StaticTable;
use Firststep\Common\Database\QueryExecuter;

class TableBuilder {

    const GET_METHOD = 'GET';
    const POST_METHOD = 'POST';
    private $queryExecuter;
    private $queryBuilder;
    private $resource;
    private $router;
    private $dbconnection;
    private $parameters;
    private $method;

    function __construct() {
        $this->queryExecuter = new QueryExecuter;
        $this->queryBuilder = new QueryBuilder;
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

    public function setRouter( $router ) {
    	$this->router = $router;
    }

    /**
     * @param mixed $parameters
     */
    public function setParameters($parameters) {
        $this->parameters = $parameters;
    }

    /**
     * @param mixed $resource
     */
    public function setResource($resource) {
        $this->resource = $resource;
    }

    /**
     * @param mixed $dbconnection
     */
    public function setDbconnection($dbconnection) {
        $this->dbconnection = $dbconnection;
    }

    public function createTable() {
        $this->queryExecuter->setDBH( $this->dbconnection->getDBH() );
        $this->queryExecuter->setQueryBuilder( $this->queryBuilder );


        if ($this->method === self::GET_METHOD) {
            $query = $this->resource->get->query;
            $table = $this->resource->get->table;
            if (isset( $this->parameters ) ) $this->queryExecuter->setParameters( $this->parameters );
        } else {
            $query = $this->resource->post->query;
            $table = $this->resource->post->table;
            if (isset( $this->parameters ) ) $this->queryExecuter->setPostParameters( $this->parameters );
        }

        $this->queryExecuter->setQueryStructure( $query );


        $entities = $this->queryExecuter->executeQuery();

		$tableBlock = new StaticTable;
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
				$tableBlock->addColumn($entity->{$field->sqlfield});
			}
			$links = '';
			foreach ( $table->actions as $action ) {
				$links .= LinkBuilder::get( $this->router, $action->label, $action->action, $action->resource, $action->parameters, $entity );
			}
			$tableBlock->addUnfilteredColumn( $links );
			$tableBlock->closeRow();
		}
		$tableBlock->closeTBody();
		
        return $tableBlock;
    }
	
}
