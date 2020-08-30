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
use Fabiom\UglyDuckling\Common\Wrappers\SessionWrapper;

class JsonTemplate {

    protected $resource;
    protected /* string */ $action;
    protected /* JsonTemplateFactoriesContainer */ $jsonTemplateFactoriesContainer;

    const blocktype = 'basebuilder';

    /**
     * BaseBuilder constructor.
     */
    public function __construct() {

    }

    /**
     * @param mixed $resource
     */
    public function setResource($resource) {
        $this->resource = $resource;
    }

    /**
     * Set the complete URL for the form action
     * @param action $action
     */
    public function setAction( string $action ): void {
        $this->action = $action;
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
     * @deprecated
     *
     * Get the value to populate a form or a query from the right array of variables: GET POST SESSION
     * @param $field: stdClass must contain fieldname attibute
     * @param $entity: possible entity loaded from the database (TODO: must become a property of this class)
     */
    public function getValue( $field, $parameters, $postparameters, $sessionWrapper, $entity = null ) {
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
            return $parameters[$field->getparameter] ?? '';
        }
        if ( isset($field->postparameter) ) {
            return $postparameters[$field->postparameter] ?? '';
        }
        if ( isset($field->sessionparameter) ) {
            if ( !empty ( $field->sessionparameter ) ) {
                if ( $sessionWrapper->isSessionParameterSet($field->sessionparameter) ) {
                    return $sessionWrapper->getSessionParameter($field->sessionparameter);
                }
            }
        }
    }

    /**
     * Get the value to populate a form or a query from the right array of variables: GET POST SESSION
     * @param $field: stdClass must contain fieldname attibute
     * @param $entity: possible entity loaded from the database (TODO: must become a property of this class)
     */
    public function getValueFromPageStatus( $field, $entity = null ) {
        if ( !is_null($entity) ) {
            $this->jsonTemplateFactoriesContainer->getPageStatus()->setLastEntity($entity);
        }
        return $this->jsonTemplateFactoriesContainer->getPageStatus()->getValue( $field );
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
