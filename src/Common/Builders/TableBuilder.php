<?php

/**
 * User: fabio
 * Date: 13/07/2018
 * Time: 20:39
 */

namespace Firststep\Common\Builders;

use Firststep\Common\Blocks\StaticTable;
use Firststep\Common\Database\QueryExecuter;

class TableBuilder {

    private $parameters;
    private $queryExecuter;
    private $queryBuilder;
    private $resource;
    private $router;
    private $dbconnection;

    function __construct() {
        $this->queryExecuter = new QueryExecuter;
        $this->queryBuilder = new QueryBuilder;
        $this->query = '';
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
        $this->queryExecuter->setQueryStructure( $this->resource->get->query );
        if (isset( $this->parameters ) ) $this->queryExecuter->setParameters( $this->parameters );
        $entities = $this->queryExecuter->executeQuery();

		$tableBlock = new StaticTable;
		$tableBlock->setTitle($this->resource->get->table->title);
		
		$tableBlock->addTHead();
		$tableBlock->addRow();
		foreach ($this->resource->get->table->fields as $field) {
			$tableBlock->addHeadLineColumn($field->headline);
		}
		$tableBlock->addHeadLineColumn(''); // adding one more for actions
		$tableBlock->closeRow();
		$tableBlock->closeTHead();
		
		$tableBlock->addTBody();
		foreach ($entities as $entity) {
			$tableBlock->addRow();
			foreach ($this->resource->get->table->fields as $field) {
				$tableBlock->addColumn($entity->{$field->sqlfield});
			}
			$links = '';
			foreach ( $this->resource->get->table->actions as $action ) {
				$links .= LinkBuilder::get( $this->router, $action->lable, $action->action, $action->resource, $action->parameters, $entity );
			}
			$tableBlock->addUnfilteredColumn( $links );
			$tableBlock->closeRow();
		}
		$tableBlock->closeTBody();
		
        return $tableBlock;
    }
	
}
