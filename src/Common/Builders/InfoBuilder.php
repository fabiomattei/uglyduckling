<?php

/**
 * User: Fabio Mattei
 * Date: 13/07/18
 * Time: 18.15
 */

namespace Firststep\Common\Builders;

use Firststep\Common\Blocks\BaseInfo;
use Firststep\Common\Database\QueryExecuter;

class InfoBuilder {

    private $queryExecuter;
    private $queryBuilder;
    private $resource;
    private $router;
    private $dbconnection;
    private $parameters;

    /**
     * InfoBuilder constructor.
     */
    public function __construct() {
        $this->queryExecuter = new QueryExecuter;
        $this->queryBuilder = new QueryBuilder;
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

    /**
     * Setting method for testing purposes
     *
     * @param $queryExecuter
     */
    public function setQueryExecuter( $queryExecuter ) {
        $this->queryExecuter = $queryExecuter;
    }

    /**
     * Setting method for testing purposes
     *
     * @param $queryBuilder
     */
    public function setQueryBuilder( $queryBuilder ) {
        $this->queryBuilder = $queryBuilder;
    }

    public function createInfo() {
        $this->queryExecuter->setDBH( $this->dbconnection->getDBH() );
        $this->queryExecuter->setQueryBuilder( $this->queryBuilder );
        $this->queryExecuter->setQueryStructure( $this->resource->get->query );
        if (isset( $this->parameters ) ) $this->queryExecuter->setGetParameters( $this->parameters );

        $result = $this->queryExecuter->executeQuery();
        $entity = $result->fetch();

		$formBlock = new BaseInfo;
		$formBlock->setTitle($this->resource->get->info->title ?? '');
		$fieldRows = array();
		
		foreach ($this->resource->get->info->fields as $field) {
			if( !array_key_exists(($field->row ?? 1), $fieldRows) ) $fieldRows[$field->row] = array();
			$fieldRows[($field->row ?? 1)][] = $field;
		}
		
        $rowcounter = 1;
		foreach ($fieldRows as $row) {
			$formBlock->addRow();
			foreach ($row as $field) {
				$fieldname = $field->value;
				$value = ($entity == null ? '' : ( isset($entity->$fieldname) ? $entity->$fieldname : '' ) );
                if ($field->type === 'textarea') {
                    $formBlock->addTextAreaField($field->label, $value, $field->width);
                }
                if ($field->type === 'currency') {
                    $formBlock->addCurrencyField($field->label, $value, $field->width);
                }
                if ($field->type === 'date') {
                    $formBlock->addDateField($field->label, $value, $field->width);
                }
			}
			$formBlock->closeRow('row '.$rowcounter);
            $rowcounter++;
		}
        return $formBlock;
    }

}
