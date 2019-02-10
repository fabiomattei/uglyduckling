<?php
/**
 * Created Fabio Mattei
 * Date: 2019-02-10
 * Time: 12:00
 */

namespace Firststep\Common\Builders;

use Firststep\Common\Database\QueryExecuter;

class BaseBuilder {

    protected $queryExecuter;
    protected $queryBuilder;
    protected $resource;
    protected $router;
    protected $dbconnection;
    protected $parameters;
    protected $action;
    protected $htmlTemplateLoader;

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

    public function setHtmlTemplateLoader($htmlTemplateLoader) {
        $this->htmlTemplateLoader = $htmlTemplateLoader;
    }

    /**
     * Set the complete URL for the form action
     * @param action $action
     */
    public function setAction( string $action ) {
        $this->action = $action;
    }

}
