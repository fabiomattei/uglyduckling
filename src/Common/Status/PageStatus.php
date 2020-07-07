<?php

namespace Fabiom\UglyDuckling\Common\Status;

use Fabiom\UglyDuckling\Common\Database\DBConnection;
use Fabiom\UglyDuckling\Common\Database\QueryExecuter;
use Fabiom\UglyDuckling\Common\Database\QuerySet;
use Fabiom\UglyDuckling\Common\Json\JsonTemplates\QueryBuilder;
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
    public /* DBConnection */ $dbconnection;

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
     * @param DBConnection $dbconnection
     */
    public function setDbconnection(DBConnection $dbconnection): void {
        $this->dbconnection = $dbconnection;
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
            return ($this->lastEntity == null ? '' : ( isset($this->lastEntity->{$fieldname}) ? $this->lastEntity->{$fieldname} : '' ) );
        }
        if ( isset($field->sqlfield) ) {
            $fieldname = $field->sqlfield;
            return ($this->lastEntity == null ? '' : ( isset($this->lastEntity->{$fieldname}) ? $this->lastEntity->{$fieldname} : '' ) );
        }
        if ( isset($field->constantparameter) ) {
            return $field->constantparameter;
        }
        if ( isset($field->constant) ) {
            return $field->constant;
        }
        if ( isset($field->parameter) ) {
            return $this->parameters[$field->parameter] ?? $this->checkForDefaultValues($field);
        }
        if ( isset($field->getparameter) ) {
            return $this->parameters[$field->getparameter] ?? $this->checkForDefaultValues($field);
        }
        if ( isset($field->postparameter) ) {
            return $this->postparameters[$field->postparameter] ?? $this->checkForDefaultValues($field);
        }
        if ( isset($field->sessionparameter) ) {
            return $this->sessionparameters[$field->sessionparameter] ?? $this->checkForDefaultValues($field);
        }
    }

    /**
     * @param $sessionupdates
     *
       "sessionupdates": {
         "queryset": [
           {
             "label": "query1",
             "sql": "SELECT usr_siteid, usr_usrofid, usr_depid FROM user where usr_id = :usrid ;",
             "parameters":[
               { "type":"long", "placeholder": ":usrid", "sessionparameter": "user_id" }
             ]
           }
         ],
         "sessionvars": [
             { "name":"user_id", "system":"ud" },
             { "name":"username", "system":"ud" },
             { "name":"group", "system":"ud" },
             { "name":"logged_in", "system":"ud" },
             { "name":"ip", "system":"ud" },
             { "name":"last_login", "system":"ud" },
             { "name":"siteid", "sqlfield":"usr_siteid", "querylabel":"query1" },
             { "name":"tryaconstantparameter", "constantparamenter":"4" }
           ]
         }
     */
    public function updateSession( $sessionupdates ) {
        $querySet = new QuerySet;

        if (isset($sessionupdates->queryset) AND is_array($sessionupdates->queryset)) {
            $queryBuilder = new QueryBuilder;
            $queryExecuter = new QueryExecuter;

            $queryExecuter->setDBH($this->dbconnection);
            $queryExecuter->setQueryBuilder($queryBuilder);
            $queryExecuter->setParameters(array());
            $queryExecuter->setPostParameters(array());
            $queryExecuter->setSessionWrapper($this->getSessionWrapper());

            foreach ($sessionupdates->queryset as $query) {
                $queryExecuter->setQueryStructure($query);
                $result = $queryExecuter->executeSql();
                $entity = $result->fetch();
                if (isset($query->label)) {
                    $querySet->setResult($query->label, $entity);
                } else {
                    $querySet->setResultNoKey($entity);
                }
            }
        }

        if ( isset($sessionupdates->sessionvars) AND is_array($sessionupdates->sessionvars) ) {
            foreach ($sessionupdates->sessionvars as $sessionvar) {
                if ( isset( $sessionvar->querylabel ) AND isset( $sessionvar->sqlfield ) ) {
                    if ( isset($querySet->getResult($sessionvar->querylabel)->{$sessionvar->sqlfield}) ) {
                        $this->getSessionWrapper()->setSessionParameter($sessionvar->name, $querySet->getResult($sessionvar->querylabel)->{$sessionvar->sqlfield} );
                    }
                }
                if ( isset( $sessionvar->constantparamenter ) ) {
                    $this->getSessionWrapper()->setSessionParameter($sessionvar->name, $sessionvar->constantparamenter);
                }
                if ( isset( $sessionvar->getparamenter ) ) {
                    $this->getSessionWrapper()->setSessionParameter($sessionvar->name, $this->getParameters[$sessionvar->getparamenter]);
                }
                if ( isset( $sessionvar->postparamenter ) ) {
                    $this->getSessionWrapper()->setSessionParameter($sessionvar->name, $this->postParameters[$sessionvar->postparamenter]);
                }
            }
        }
    }
    
    function checkForDefaultValues( $field ): string {
        if ( isset($field->default) ) return $field->default;
        if ( isset($field->defaultfunction) ) return $this->callDefaultFunction($field->defaultfunction);
    }
    
    /**
     * @Override this function in order to have more default functions
     */
    function callDefaultFunction($defaultfunction): string {
        switch ($defaultfunction) {
            case 'getcurrentyear': return date("Y");
            case 'getcurrentmonth': return date("m");
            case 'getcurrentriskcenter' : return $this->sessionWrapper->getSessionParameter('siteid');
            default: return '';
        }
    }

}
