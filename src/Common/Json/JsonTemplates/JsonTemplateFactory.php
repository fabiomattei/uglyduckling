<?php

/**
 * Created by Fabio Mattei
 * Date: 02/11/2018
 * Time: 04:56
 */

namespace Fabiom\UglyDuckling\Common\Json\JsonTemplates;

use Fabiom\UglyDuckling\Common\Blocks\BaseHTMLBlock;
use Fabiom\UglyDuckling\Common\Blocks\EmptyHTMLBlock;
use Fabiom\UglyDuckling\Common\Database\QueryExecuter;
use Fabiom\UglyDuckling\Common\Json\JsonTemplates\LinkBuilder;
use Fabiom\UglyDuckling\Common\Router\RoutersContainer;

class JsonTemplateFactory {

    protected /* QueryExecuter */ $queryExecuter;
    protected /* QueryBuilder */ $queryBuilder;
    protected $resource;
    protected /* RoutersContainer */ $routerContainer;
    protected $dbconnection;
    protected $parameters;
    protected $postparameters;
    protected $sessionparameters;
    protected $action;
    protected $htmlTemplateLoader;
    protected $jsonloader;
    protected $linkBuilder;
    protected $logger;

    const blocktype = 'basebuilder';

    /**
     * BaseBuilder constructor.
     */
    public function __construct() {
        $this->queryExecuter = new QueryExecuter;
        $this->queryBuilder = new QueryBuilder;
    }

    /**
     * Setting routerContainer object
     *
     * @param $routerContainer
     */
    public function setRouter( $routerContainer ) {
        $this->routerContainer = $routerContainer;
    }

    /**
     * @param mixed $jsonloader
     */
    public function setJsonloader($jsonloader) {
        $this->jsonloader = $jsonloader;
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
     * Setting Html template loader
     *
     * @param $htmlTemplateLoader
     */
    public function setHtmlTemplateLoader($htmlTemplateLoader) {
        $this->htmlTemplateLoader = $htmlTemplateLoader;
    }

    /**
     * @param mixed $logger
     * the $logger variable contains a logger for this class
     */
    public function setLogger( $logger ) {
        $this->logger = $logger;
    }

    /**
     * Set the complete URL for the form action
     * @param action $action
     */
    public function setAction( string $action ) {
        $this->action = $action;
    }

    public function setLinkBuilder( $linkBuilder ) {
        $this->linkBuilder = $linkBuilder;
    }

}

