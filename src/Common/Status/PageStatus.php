<?php

namespace Fabiom\UglyDuckling\Common\Status;

use Fabiom\UglyDuckling\Common\Database\QueryExecuter;
use Fabiom\UglyDuckling\Common\Request\Request;
use Fabiom\UglyDuckling\Common\Wrappers\ServerWrapper;
use Fabiom\UglyDuckling\Common\Wrappers\SessionWrapper;

class PageStatus {

    public /* Request */ $request;
    public /* ServerWrapper */ $serverWrapper;
    public /* SessionWrapper */ $sessionWrapper;
    public /* array */ $getParameters;
    public /* array */ $postParameters;
    public /* array */ $filesParameters;
    public $lastEntity; // result of last query in database, it is a stdClass

    /**
     * PageStatus constructor.
     */
    public function __construct() {
    }

    function setRequest($request) {
        $this->request = $request;
    }

    function setServerWrapper($serverWrapper) {
        $this->serverWrapper = $serverWrapper;
    }

    function setSessionWrapper($sessionWrapper) {
        $this->sessionWrapper = $sessionWrapper;
    }

    /**
     * @param mixed $lastEntity
     */
    public function setLastEntity($lastEntity): void {
        $this->lastEntity = $lastEntity;
    }

    /**
     * @param mixed $getParameters
     */
    public function setGetParameters($getParameters): void {
        $this->getParameters = $getParameters;
    }

    /**
     * @param mixed $postParameters
     */
    public function setPostParameters($postParameters): void {
        $this->postParameters = $postParameters;
    }

    /**
     * @param mixed $filesParameters
     */
    public function setFilesParameters($filesParameters): void {
        $this->filesParameters = $filesParameters;
    }

    /**
     * @return Request
     */
    public function getRequest(): Request {
        return $this->request;
    }

    /**
     * @return ServerWrapper
     */
    public function getServerWrapper(): ServerWrapper {
        return $this->serverWrapper;
    }

    /**
     * @return SessionWrapper
     */
    public function getSessionWrapper(): SessionWrapper {
        return $this->sessionWrapper;
    }



    // TODO it is going to handle query results too

    /**
     * Get the value to populate a form or a query from the right array of variables: GET POST SESSION
     * @param $field: stdClass must contain fieldname attibute
     * @param $entity: possible entity loaded from the database (TODO: must become a property of this class)
     */
    public function getValue( $field ) {
        if ( isset($field->value) ) {  // used for info builder but I need to remove this
            $fieldname = $field->value;
            return ($this->lastEntity == null ? '' : ( isset($entity->{$fieldname}) ? $entity->{$fieldname} : '' ) );
        }
        if ( isset($field->sqlfield) ) {
            $fieldname = $field->sqlfield;
            return ($this->lastEntity == null ? '' : ( isset($entity->{$fieldname}) ? $entity->{$fieldname} : '' ) );
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

}
