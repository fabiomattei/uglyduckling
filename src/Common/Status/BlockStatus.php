<?php

namespace Fabiom\UglyDuckling\Common\Status;

use Fabiom\UglyDuckling\Common\Database\QueryExecuter;

class BlockStatus {

    protected $queryExecuter;
    protected $queryBuilder;
    protected $dbconnection;
    protected $parameters;
    protected $postparameters;
    protected $sessionparameters;
    protected $defaultQueryResults;

    /**
     * PageStatus constructor.
     */
    public function __construct() {
        $this->queryExecuter = new QueryExecuter;
        $this->queryBuilder = new QueryBuilder;
    }

    /**
     * @param mixed $parameters
     */
    public function setParameters($parameters) {
        $this->parameters = $parameters;
    }

    /**
     * @param mixed $parameters
     */
    public function setPostParameters($parameters) {
        $this->postparameters = $parameters;
    }

    /**
     * @param mixed $parameters
     */
    public function setSessionParameters($parameters) {
        $this->sessionparameters = $parameters;
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
     * Get the value to populate a form or a query from the right array of variables: GET POST SESSION
     * @param $field: stdClass must contain fieldname attibute
     * @param $entity: possible entity loaded from the database (TODO: must become a property of this class)
     */
    public function getValue( $field ) {
        if ( isset($field->value) ) {  // used for info builder but I need to remove this
            $fieldname = $field->value;
            return ($entity == null ? '' : ( isset($entity->{$fieldname}) ? $entity->{$fieldname} : '' ) ); 
        }
        if ( isset($field->sqlfield) ) {
            $fieldname = $field->sqlfield;
            return ($entity == null ? '' : ( isset($entity->{$fieldname}) ? $entity->{$fieldname} : '' ) );   
        }
        if ( isset($field->constantparameter) ) {
            return $field->constantparameter;
        }
        if ( isset($field->getparameter) ) {
            return $this->parameters[$field->getparameter] ?? '';
        }
        if ( isset($field->postparameter) ) {
            return $this->postparameters[$field->postparameter] ?? '';
        }
        if ( isset($field->sessionparameter) ) {
            return $this->sessionparameters[$field->sessionparameter] ?? '';
        }
    }

    public function loadFromDatabase() {
        // If there are dummy data they take precedence in order to fill the table
        if ( isset($this->resource->get->dummydata) ) {
            $this->defaultQueryResults = $this->resource->get->dummydata;
        } else {
            // If there is a query I look for data to fill the table,
            // if there is not query I do not
            if ( isset($this->resource->get->query) AND isset($this->dbconnection) ) {
                $this->queryExecuter->setDBH( $this->dbconnection->getDBH() );
                $this->queryExecuter->setQueryBuilder( $this->queryBuilder );
                if ($this->method === self::GET_METHOD) {
                    $query = $this->resource->get->query;
                    if (isset( $this->parameters ) ) $this->queryExecuter->setParameters( $this->parameters );
                } else {
                    $query = $this->resource->post->query;
                    if (isset( $this->parameters ) ) $this->queryExecuter->setPostParameters( $this->parameters );
                }
                $this->queryExecuter->setQueryStructure( $query );
                $this->defaultQueryResults = $this->queryExecuter->executeQuery();
            }
        }
    }

}
