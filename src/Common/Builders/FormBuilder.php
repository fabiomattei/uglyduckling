<?php

/**
 * User: Fabio Mattei
 * Date: 13/07/2018
 * Time: 12:00
 */

namespace Firststep\Common\Builders;

use Firststep\Common\Blocks\BaseForm;
use Firststep\Common\Database\QueryExecuter;

class FormBuilder {

    private $queryExecuter;
    private $queryBuilder;
    private $resource;
    private $router;
    private $dbconnection;
    private $parameters;
    private $action;

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

    /**
     * Set the complete URL for the form action
     * @param action $action
     */
    public function setAction( string $action ) {
        $this->action = $action;
    }

    public function createForm() {
        // If there is a query I look for data to fill the form,
        // if there is not query I do not
        if ( isset($this->resource->get->query) AND isset($this->dbconnection) ) {
            $this->queryExecuter->setDBH( $this->dbconnection->getDBH() );
            $this->queryExecuter->setQueryBuilder( $this->queryBuilder );
            $this->queryExecuter->setQueryStructure( $this->resource->get->query );
            if (isset( $this->parameters ) ) $this->queryExecuter->setGetParameters( $this->parameters );

            $result = $this->queryExecuter->executeQuery();
            $entity = $result->fetch();
        } else {
            $entity = new \stdClass();
        }

		$formBlock = new BaseForm;
		$formBlock->setTitle($this->resource->get->form->title ?? '');
        $formBlock->setAction( $this->action ?? '');
		$fieldRows = array();
		
		foreach ($this->resource->get->form->fields as $field) {
			if( !array_key_exists($field->row, $fieldRows) ) $fieldRows[$field->row] = array();
			$fieldRows[$field->row][] = $field;
		}
		
		foreach ($fieldRows as $row) {
			$formBlock->addRow();
			foreach ($row as $field) {
				$fieldname = $field->sqlfield;
				$value = ($entity == null ? '' : ( isset($entity->{$fieldname}) ? $entity->{$fieldname} : '' ) );
                if ($field->type === 'text') {
                    $formBlock->addTextField($field->name, $field->label, $field->placeholder, $value, $field->width);
                }
                if ($field->type === 'dropdown') {
                    $options = array();
                    foreach ($field->options as $op) {
                        $options[$op->value] = $op->label;
                    }
                    $formBlock->addDropdownField($field->name, $field->label, $options, $value, $field->width);
                }
				if ($field->type === 'textarea') {
                    $formBlock->addTextAreaField($field->name, $field->label, $value, $field->width);
                }
                if ($field->type === 'currency') {
                    $formBlock->addCurrencyField($field->name, $field->label, $field->placeholder, $value, $field->width);
                }
                if ($field->type === 'date') {
                    $formBlock->addDateField($field->name, $field->label, $value, $field->width);
                }
                if ($field->type === 'hidden') {
                    $formBlock->addHiddenField($field->name, $value);
                }
			}
			$formBlock->closeRow('row '.$row->row);
		}
        $formBlock->addRow();
        $formBlock->addSubmitButton( 'save', $this->resource->get->form->submitTitle );
        $formBlock->closeRow('row save');
        return $formBlock;
    }

}
