<?php

namespace Fabiom\UglyDuckling\Common\Status;

use Fabiom\UglyDuckling\Common\Database\DBConnection;
use Fabiom\UglyDuckling\Common\Database\QueryExecuter;
use Fabiom\UglyDuckling\Common\Database\QuerySet;
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
    private /* QueryExecuter */ $queryExecuter;
    public /* stdClass */ $lastEntity; // result of last query in database
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

    function setQueryExecutor( $queryExecuter ) {
        $this->queryExecuter = $queryExecuter;
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
     * @param array $postParameters
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

    /**
     * @return QueryExecuter
     */
    public function getQueryExecutor(): QueryExecuter {
        return $this->queryExecuter;
    }

    /**
     * Get the value to populate a form, a table or a info panel or more.
     *
     * This Class contains all status for the application, consisting in:
     * - GET parameters
     * - POST paramenters
     * - FILES parameters
     * - SESSION parameters
     * - SERVER parameters
     *
     * Example:
     * Imagine there is a json resource of type table containing the following fields:
     * "fields": [
     *    {"headline": "Title", "sqlfield": "title", "default":"" },
     *    {"headline": "Description", "sqlfield": "description", "default":"" },
     *    {"headline": "Date", "sqlfield": "created", "defaultfunction":"getcurrentdate" }
     * ]
     * This method is going to return the SQL field returned from the query for each field:
     * - SQL field title
     * - SQL field description
     * - SQL field created
     *
     * In case the field is null for any reason this method is going to call de defined defaul value
     * or default function
     *
     * @param $field: stdClass must contain fieldname attibute
     * @param $entity: possible entity loaded from the database
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
            return $this->getParameters[$field->parameter] ?? $this->checkForDefaultValues($field);
        }
        if ( isset($field->getparameter) ) {
            return $this->getParameters[$field->getparameter] ?? $this->checkForDefaultValues($field);
        }
        if ( isset($field->postparameter) ) {
            return $this->postParameters[$field->postparameter] ?? $this->checkForDefaultValues($field);
        }
        if ( isset($field->sessionparameter) ) {
            return ($this->sessionWrapper->isSessionParameterSet( $field->sessionparameter ) ? $this->sessionWrapper->getSessionParameter( $field->sessionparameter ) : $this->checkForDefaultValues($field) );
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
            $queryExecuter = new QueryExecuter;

            $queryExecuter->setDBH($this->dbconnection);
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
                if ( isset( $sessionvar->getparameter ) ) {
                    $this->getSessionWrapper()->setSessionParameter($sessionvar->name, $this->getParameters[$sessionvar->getparameter]);
                }
                if ( isset( $sessionvar->postparameter ) ) {
                    $this->getSessionWrapper()->setSessionParameter($sessionvar->name, $this->postParameters[$sessionvar->postparameter]);
                }
            }
        }
    }

    /**
     * This method check if a field has a defined default value or a default function.
     * It is used in form fields, info panel fields, table fields and more
     *
     * Example:
     * Imagine there is a json resource of type table containing the following fields:
     * "fields": [
     *    {"headline": "Title", "sqlfield": "title", "default":"" },
     *    {"headline": "Description", "sqlfield": "description", "default":"" },
     *    {"headline": "Date", "sqlfield": "created", "defaultfunction":"getcurrentdate" }
     * ]
     *
     * As you can see the first two fields define a default value, the last one defines a default function
     * In case the field resulting from SQL query is null:
     * - The first field has a default value of an empty string
     * - The second field has a default value of an empty string
     * - The third field has a default function (See method callDefaultFunction)
     *
     * @param $field
     * @return string
     */
    function checkForDefaultValues( $field ): string {
        if ( isset($field->default) ) return $field->default;
        if ( isset($field->defaultfunction) ) return $this->callDefaultFunction($field->defaultfunction);
        return '';
    }
    
    /**
     * This method specifies a set of strings corresponding to function for a default setting
     * It is used in form fields, info panel fields, table fields and more
     *
     * Example:
     * Imagine there is a json resource of type table containing the following fields:
     * "fields": [
     *    {"headline": "Title", "sqlfield": "title", "default":""},
     *    {"headline": "Description", "sqlfield": "description", "default":""},
     *    {"headline": "Date", "sqlfield": "created", "defaultfunction":"getcurrentdate" }
     * ]
     *
     * As you can see the last field, named Date, gather data from the field that is resulting
     * from SQL query. In case that is null the method callDefaultFunction will be
     * called specifying the function getcurrentdate that return date('Y-m-d', time())
     *
     * @Override this function in order to have more default functions
     */
    function callDefaultFunction(string $functionName): string {
        switch ( $functionName ) {
            case 'getcurrentdate': return date('Y-m-d', time());
            case 'getcurrentdatetime': return date('Y-m-d H:i:s', time());
            case 'getcurrentyear': return date('Y');
            case 'getcurrentmonth': return date('m');
            case 'getcurrentquarter': return ceil(date('n') / 3);
            case 'getcurrentriskcenter' : return $this->sessionWrapper->getSessionParameter('siteid');
            default: return '';
        }
    }

}
