<?php
/**
 * Created Fabio Mattei
 * Date: 2019-02-10
 * Time: 12:00
 */

namespace Fabiom\UglyDuckling\Common\Json\JsonTemplates;

use Fabiom\UglyDuckling\Common\Blocks\EmptyHTMLBlock;
use Fabiom\UglyDuckling\Common\Database\DBConnection;
use Fabiom\UglyDuckling\Common\Database\QueryExecuter;
use Fabiom\UglyDuckling\Common\Json\JsonLoader;
use Fabiom\UglyDuckling\Common\Loggers\Logger;
use Fabiom\UglyDuckling\Common\Router\RoutersContainer;
use Fabiom\UglyDuckling\Common\Utils\HtmlTemplateLoader;

class JsonTemplate {

    protected /* QueryExecuter */ $queryExecuter;
    protected /* QueryBuilder */ $queryBuilder;
    protected $resource;
    protected /* RoutersContainer */ $routerContainer;
    protected /* DBConnection */ $dbconnection;
    protected /* array */ $parameters;
    protected /* array */ $postparameters;
    protected $sessionparameters;
    protected /* string */ $action;
    protected /* HtmlTemplateLoader */ $htmlTemplateLoader;
    protected /* JsonLoader */ $jsonloader;
    protected /* LinkBuilder */ $linkBuilder;
    protected /* Logger */ $logger;
    protected /* JsonTemplateFactoriesContainer */ $jsonTemplateFactoriesContainer;

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
    public function setRouter( RoutersContainer $routerContainer ) {
        $this->routerContainer = $routerContainer;
    }

    /**
     * @param JsonLoader $jsonloader
     */
    public function setJsonloader( JsonLoader $jsonloader) {
        $this->jsonloader = $jsonloader;
    }

    /**
     * @param array $parameters
     */
    public function setParameters(array $parameters): void {
        $this->parameters = $parameters;
    }

    /**
     * @param array $parameters
     */
    public function setPostParameters(array $parameters): void {
        $this->postparameters = $parameters;
    }

    /**
     * @param array $parameters
     */
    public function setSessionParameters(array $parameters): void {
        $this->sessionparameters = $parameters;
    }

    /**
     * @param mixed $resource
     */
    public function setResource($resource) {
        $this->resource = $resource;
    }

    /**
     * @param DBConnection $dbconnection
     */
    public function setDbconnection( DBConnection $dbconnection ): void {
        $this->dbconnection = $dbconnection;
    }

    /**
     * Setting method for testing purposes
     *
     * @param QueryExecuter $queryExecuter
     */
    public function setQueryExecuter( QueryExecuter $queryExecuter ): void {
        $this->queryExecuter = $queryExecuter;
    }

    /**
     * Setting method for testing purposes
     *
     * @param QueryBuilder $queryBuilder
     */
    public function setQueryBuilder( QueryBuilder $queryBuilder ): void {
        $this->queryBuilder = $queryBuilder;
    }

    /**
     * Setting Html template loader
     *
     * @param HtmlTemplateLoader $htmlTemplateLoader
     */
    public function setHtmlTemplateLoader( HtmlTemplateLoader $htmlTemplateLoader): void {
        $this->htmlTemplateLoader = $htmlTemplateLoader;
    }
    
    /**
     * @param mixed $logger
     * the $logger variable contains a logger for this class
     */
    public function setLogger( Logger $logger ): void {
        $this->logger = $logger;
    }

    /**
     * Set the complete URL for the form action
     * @param action $action
     */
    public function setAction( string $action ): void {
        $this->action = $action;
    }

    /**
     * @param LinkBuilder $linkBuilder
     */
    public function setLinkBuilder( LinkBuilder $linkBuilder ): void {
        $this->linkBuilder = $linkBuilder;
    }

    /**
     * Setting panelBuilder
     *
     * @param JsonTemplateFactoriesContainer $jsonTemplateFactoriesContainer
     */
    public function setJsonTemplateFactoriesContainer( JsonTemplateFactoriesContainer $jsonTemplateFactoriesContainer ): void {
        $this->jsonTemplateFactoriesContainer = $jsonTemplateFactoriesContainer;
    }

    /**
     * Get the value to populate a form or a query from the right array of variables: GET POST SESSION
     * @param $field: stdClass must contain fieldname attibute
     * @param $entity: possible entity loaded from the database (TODO: must become a property of this class)
     */
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
