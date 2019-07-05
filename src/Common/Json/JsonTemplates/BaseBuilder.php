<?php
/**
 * Created Fabio Mattei
 * Date: 2019-02-10
 * Time: 12:00
 */

namespace Firststep\Common\Json\JsonTemplates;

use Firststep\Common\Blocks\BaseHTMLBlock;
use Firststep\Common\Blocks\EmptyHTMLBlock;
use Firststep\Common\Database\QueryExecuter;

class BaseBuilder {

    protected $queryExecuter;
    protected $queryBuilder;
    protected $resource;
    protected $router;
    protected $dbconnection;
    protected $parameters;
    protected $postparameters;
    protected $sessionparameters;
    protected $action;
    protected $htmlTemplateLoader;
    protected $jsonloader;

    const blocktype = 'basebuilder';

    /**
     * BaseBuilder constructor.
     */
    public function __construct() {
        $this->queryExecuter = new QueryExecuter;
        $this->queryBuilder = new QueryBuilder;
    }

    /**
     * Setting router object
     *
     * @param $router
     */
    public function setRouter( $router ) {
        $this->router = $router;
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
     * Set the complete URL for the form action
     * @param action $action
     */
    public function setAction( string $action ) {
        $this->action = $action;
    }

    public function getValue( $field, $entity = null ) {
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



    /**
     * Return a object that inherit from BaseHTMLBlock class
     * It is an object that has to generate HTML code
     *
     * @return EmptyHTMLBlock
     */
    public function createHTMLBlock() {
        return new EmptyHTMLBlock;
    }

}
