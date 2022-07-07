<?php

namespace Fabiom\UglyDuckling\Common\Status;

use Fabiom\UglyDuckling\Common\Database\DBConnection;
use Fabiom\UglyDuckling\Common\Database\QueryExecuter;
use Fabiom\UglyDuckling\Common\Database\QueryReturnedValues;
use Fabiom\UglyDuckling\Common\Database\QuerySet;
use Fabiom\UglyDuckling\Common\Request\Request;
use Fabiom\UglyDuckling\Common\UseCases\UseCasesIndex;
use Fabiom\UglyDuckling\Common\Wrappers\ServerWrapper;
use Fabiom\UglyDuckling\Common\Wrappers\SessionWrapper;

class PageStatus {

    public Request $request;
    public ServerWrapper $serverWrapper;
    public SessionWrapper $sessionWrapper;
    public /* array */ $getParameters;
    public /* array */ $postParameters;
    public /* array */ $filesParameters;
    public QueryExecuter $queryExecuter;
    public /* stdClass */ $lastEntity; // result of last query in database
    public DBConnection $dbconnection;
    public UseCasesIndex $useCasesIndex;
    public QueryReturnedValues $queryReturnedValues;

    /**
     * PageStatus constructor.
     */
    public function __construct() {
        $this->queryReturnedValues = new QueryReturnedValues;
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

    function setQueryExecutor( $queryExecutor ) {
        $this->queryExecuter = $queryExecutor;
        $this->queryExecuter->setPageStatus( $this ); // connecting query executor with page status
    }

    /**
     * @param DBConnection $dbconnection
     */
    public function setDbconnection(DBConnection $dbconnection): void {
        $this->dbconnection = $dbconnection;
    }

    function setUseCasesIndex( $useCasesIndex ) {
        $this->useCasesIndex = $useCasesIndex;
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
     * @param QueryReturnedValues $queryReturnedValues
     */
    public function setQueryReturnedValues(QueryReturnedValues $queryReturnedValues): void {
        $this->queryReturnedValues = $queryReturnedValues;
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
     * @return UseCasesIndex
     */
    public function getUseCasesIndex(): UseCasesIndex {
        return $this->useCasesIndex;
    }

    /**
     * @return QueryReturnedValues
     */
    public function getQueryReturnedValues(): QueryReturnedValues {
        return $this->queryReturnedValues;
    }

    /**
     * @return DBConnection
     */
    public function getDbconnection(): DBConnection {
        return $this->dbconnection;
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
     */
    public function getValue( $field ) {
        if ( !isset($field->filter) ) {
            return $this->retriveValue( $field );
        } else {
            return $this->applyFilters( $field->filter, $this->retriveValue( $field ) );
        }
    }

    /**
     * Check if there is a "value" property in field.
     * If it is present iterates over tre structure and return the first value he finds different from empty string.
     * If there is not tries to find a value in the field itself
     */
    public function retriveValue( $field ) {
        if (isset( $field->value ) AND is_array( $field->value ) ) {
            return $this->getValueFromArrayValue( $field->value );
        } else {
            return $this->retriveFieldValue( $field );
        }
    }

    /**
     * Iterates over this structure until it finds a value
     * "value": [
     *   { "type":"long", "sqlfield":"cv_FREQUE", "filter":"substr,1,1" },
     *   { "type":"long", "constant":"0" }
     * ],
     */
    public function getValueFromArrayValue( $fieldvalue ) {
        $value = '';
        foreach ($fieldvalue as $valuedef) {
            $value = $this->retriveFieldValue( $valuedef );
            if ( $value != '' ) {
                if ( isset($valuedef->filter) ) {
                    return $this->applyFilters( $valuedef->filter, $value );
                } else {
                    return $value;
                }
            }
        }
        return $value;
    }

    /**
     * Get a filter string and iterates over it in order to call $this->applyFilter
     * A filter string contains all function calls divide by a pipe simbol |
     *
     * Ex: "filter":"substr,3,6|substr,1,1"
     */
    public function applyFilters( string $filterString, string $value ): string {
        if ( !isset( $value ) OR $value == '' ) return '';  // stopping many errors from happenings
        $filters = explode('|', $filterString);
        foreach ( $filters as $item ) {
            $filterCall = explode( ',', $item );
            $value = $this->applyFilter($filterCall, $value);
        }
        return $value;
    }

    /**
	 * This function retrieves a value from a field checking the current status of the page
     *
     * This Class contains all status for the application, consisting in:
     * - GET parameters
     * - POST paramenters
     * - FILES parameters
     * - SESSION parameters
     * - SERVER parameters
     * - QueryReturnedValues parameters
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
     */
    public function retriveFieldValue( $field ) {
        if ( isset($field->value) ) {  // used for info builder but I need to remove this
            $fieldname = $field->value;
            return ($this->lastEntity == null ? '' : ( isset($this->lastEntity->{$fieldname}) ? $this->lastEntity->{$fieldname} : '' ) );
        }
        if ( isset($field->sqlfield) ) {
            $fieldname = $field->sqlfield;
            return ($this->lastEntity == null ? '' : ( isset($this->lastEntity->{$fieldname}) ? $this->lastEntity->{$fieldname} : $this->checkForDefaultValues($field) ) );
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
        if ( isset($field->composite) ) {
            $search = array();
            $replace = array();
            foreach( $field->parameters as $comVariable ) {
                $search[] = $comVariable->name;
                $replace[] = $this->getValue($comVariable);
            }
            return str_replace( $search, $replace, $field->composite);
        }
        if ( isset($field->returnedid) ) {
            if ( $this->queryReturnedValues->isValueSet( $field->returnedid ) ) {
                return $this->queryReturnedValues->getValue( $field->returnedid );
            }
        }
        return '';
    }

    /**
     * @param $sessionupdates
     *
     * "sessionupdates": {
     *   "queryset": [
     *     {
     *       "label": "query1",
     *       "sql": "SELECT usr_siteid, usr_usrofid, usr_depid FROM user where usr_id = :usrid ;",
     *       "parameters":[
     *         { "type":"long", "placeholder": ":usrid", "sessionparameter": "user_id" }
     *       ]
     *     }
     *   ],
     *   "sessionvars": [
     *       { "name":"user_id", "system":"ud" },
     *       { "name":"username", "system":"ud" },
     *       { "name":"group", "system":"ud" },
     *       { "name":"logged_in", "system":"ud" },
     *       { "name":"ip", "system":"ud" },
     *       { "name":"last_login", "system":"ud" },
     *       { "name":"siteid", "sqlfield":"usr_siteid", "querylabel":"query1" },
     *       { "name":"tryaconstantparameter", "constantparamenter":"4" }
     *     ]
     *   }
     */
    public function updateSession( $sessionupdates ) {
        $querySet = new QuerySet;

        if (isset($sessionupdates->queryset) AND is_array($sessionupdates->queryset)) {
            foreach ($sessionupdates->queryset as $query) {
                $this->queryExecuter->setQueryStructure($query);
                $result = $this->queryExecuter->executeSql();
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
                        $this->sessionWrapper->setSessionParameter($sessionvar->name, $querySet->getResult($sessionvar->querylabel)->{$sessionvar->sqlfield} );
                    }
                }
                if ( isset( $sessionvar->constantparamenter ) ) {
                    $this->sessionWrapper->setSessionParameter($sessionvar->name, $sessionvar->constantparamenter);
                }
                if ( isset( $sessionvar->getparameter ) ) {
                    $this->sessionWrapper->setSessionParameter($sessionvar->name, $this->getParameters[$sessionvar->getparameter]);
                }
                if ( isset( $sessionvar->postparameter ) ) {
                    $this->sessionWrapper->setSessionParameter($sessionvar->name, $this->postParameters[$sessionvar->postparameter]);
                }
            }
        }
    }

    /**
     * This method can be overridden for each project implementation.
     *
     * @param array $filtercall: contains the function call and the parameters to call
     * @param string $value: contains the value we need to apply the filter to
     *
     * Ex: substr: $filtercall = [ 'substr', 2, 5 ]    =>    $value = substr($value, 2, 5)
     *     substr: $filtercall = [ 'substr', 7 ]       =>    $value = substr($value, 7)
     *
     * This function can be overridden and customized
     */
    function applyFilter(array $filtercall, string $value): string {
        if ( $filtercall[0] == 'substr' AND count( $filtercall ) == 3 ) return substr( $value, $filtercall[1], $filtercall[2] );
        if ( $filtercall[0] == 'substr' AND count( $filtercall ) == 2 ) return substr( $value, $filtercall[1] );
        if ( $filtercall[0] == 'mysqltohumandate' ) return date ('d/m/Y', strtotime( $value ) );
        return $value;
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