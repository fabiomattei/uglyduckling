<?php

namespace Fabiom\UglyDuckling\Common\Database;

use Fabiom\UglyDuckling\Common\Loggers\Logger;
use Fabiom\UglyDuckling\Common\Wrappers\SessionWrapper;
use PDO;
use PDOException;
use Fabiom\UglyDuckling\Common\Json\JsonTemplates\QueryBuilder;

/**
 * It executes queries on the database
 */
class QueryExecuter {

    private $queryStructure;
    private $parameters;
    private $DBH;
    private $queryBuilder;
    private /* Logger */ $logger;
    private /* SessionWrapper */ $sessionWrapper;
    private /* QueryReturnedValues */ $queryReturnedValues;

    public const SELECT = 'SELECT';
    public const INSERT = 'INSERT';
    public const UPDATE = 'UPDATE';
    public const DELETE = 'DELETE';

    /**
     * Database connection handler setter
     * @param $DBH
     */
    public function setDBH( $DBH ) {
        $this->DBH = $DBH;
    }

    /**
     * Database connection handler setter
     */
    public function setQueryBuilder( $queryBuilder ) {
        $this->queryBuilder = $queryBuilder;
    }

    /**
     * @param mixed $queryStructure
     */
    public function setQueryStructure( $queryStructure ) {
        $this->queryStructure = $queryStructure;
    }

    /**
     * @param mixed $parameters
     * the $parameters variable contains all values for the query
     */
    public function setParameters( $parameters ) {
        $this->parameters = $parameters;
    }

    /**
     * @param mixed $getParameters
     * the $parameters variable contains all values for the query
     */
    public function setGetParameters( array $getParameters ): void {
        $this->getParameters = $getParameters;
    }

    /**
     * @param mixed $postParameters
     * the $parameters variable contains all values for the query
     */
    public function setPostParameters( array $postParameters ): void {
        $this->postParameters = $postParameters;
    }

    /**
     * @param mixed $logger
     * the $logger variable contains a logger for this class
     */
    public function setLogger( $logger ): void {
        $this->logger = $logger;
    }

    /**
     * Setting the SessionWrapper
     *
     * @param $sessionWrapper
     */
    public function setSessionWrapper( SessionWrapper $sessionWrapper ) {
        $this->sessionWrapper = $sessionWrapper;
    }

    /**
     * Set the arrary that is going to contain the INSERT SQL statement returned Id's
     *
     * @param $returnedIds
     */
    public function setQueryReturnedValues( $queryReturnedValues ) {
        $this->queryReturnedValues = $queryReturnedValues;
    }

    /*
     * @deprecated
     */
    public function executeQuery() {
        if($this->queryStructure->type === 'select') {
            return $this->executeSelect();
        }
        if($this->queryStructure->type === 'insert') {
            return $this->insert();
        }
        if($this->queryStructure->type === 'update') {
            return $this->update();
        }
        if($this->queryStructure->type === 'delete') {
            return $this->delete();
        }
        return $this->executeSql();
    }

    /**
     * Perform a SELECT query on the database
     *
     * @return mixed
     */
    function executeSqlSelect() {
        try {
            //echo $this->queryStructure->sql;
            //echo "GET";
            //print_r($this->getParameters);
            //echo "POST";
            //print_r($this->postParameters);

            $STH = $this->DBH->prepare( $this->queryStructure->sql );
            $STH->setFetchMode(PDO::FETCH_OBJ);

            if ( isset($this->queryStructure->parameters) ) {
                foreach ($this->queryStructure->parameters as $cond) {
                    if ( isset( $this->getParameters[$cond->getparameter] ) ) {
                        $par =& $this->getParameters[$cond->getparameter];
                    } elseif ( isset( $this->postParameters[$cond->postparameter] ) ) {
                        $par =& $this->postParameters[$cond->postparameter];
                    } elseif ( isset( $cond->constant ) ) {
                        $par =& $cond->constant;
                    } elseif ( isset( $cond->sessionparameter ) AND $this->sessionWrapper->isSessionParameterSet( $cond->sessionparameter ) ) {
                        $sp = $this->sessionWrapper->getSessionParameter( $cond->sessionparameter );
                        $par =& $sp;
                    } elseif ( isset( $cond->returnedid ) AND $this->queryReturnedValues->isValueSet($cond->returnedid) ) {
                        $sp =& $this->queryReturnedValues->getValue($cond->returnedid);
                        $par =& $sp;
                    }
                    // echo "$cond->placeholder, $par";
                    $STH->bindParam($cond->placeholder, $par);
                }
            }

            $STH->execute();

            // $STH->debugDumpParams();

            return $STH;
        } catch (\PDOException $e) {
            $this->logger->write($e->getMessage(), __FILE__, __LINE__);
        }
    }

    /**
     * It makes an INSERT query in the database and it returnd the id of the inserted row
     * using the function lastInsertId
     *
     * @return mixed
     */
    function executeSqlInsert() {
        $STH = $this->DBH->prepare( $this->queryStructure->sql );
        $STH->setFetchMode(PDO::FETCH_OBJ);

        if ( isset($this->queryStructure->parameters) ) {
            foreach ($this->queryStructure->parameters as $cond) {
                if ( isset( $this->getParameters[$cond->getparameter] ) ) {
                    $par =& $this->getParameters[$cond->getparameter];
                } elseif ( isset( $this->postParameters[$cond->postparameter] ) ) {
                    $par =& $this->postParameters[$cond->postparameter];
                } elseif ( isset( $cond->constant ) ) {
                    $par =& $cond->constant;
                } elseif ( isset( $cond->sessionparameter ) AND $this->sessionWrapper->isSessionParameterSet( $cond->sessionparameter ) ) {
                    $sp = $this->sessionWrapper->getSessionParameter( $cond->sessionparameter );
                    $par =& $sp;
                } elseif ( isset( $cond->returnedid ) AND $this->queryReturnedValues->isValueSet($cond->returnedid) ) {
                    $sp =& $this->queryReturnedValues->getValue($cond->returnedid);
                    $par =& $sp;
                }
                // echo "$cond->placeholder, $par";
                $STH->bindParam($cond->placeholder, $par);
            }
        }

        $STH->execute();

        // $STH->debugDumpParams();

        return $this->DBH->lastInsertId();
    }

    /**
     * Performs an UPDATE query to the database
     *
     * @return mixed
     */
    function executeSqlUpdate() {
        try {
            //echo $this->queryStructure->sql;
            //echo "GET";
            //print_r($this->getParameters);
            //echo "POST";
            //print_r($this->postParameters);

            $STH = $this->DBH->prepare( $this->queryStructure->sql );
            $STH->setFetchMode(PDO::FETCH_OBJ);

            if ( isset($this->queryStructure->parameters) ) {
                foreach ($this->queryStructure->parameters as $cond) {
                    if ( isset( $this->getParameters[$cond->getparameter] ) ) {
                        $par =& $this->getParameters[$cond->getparameter];
                    } elseif ( isset( $this->postParameters[$cond->postparameter] ) ) {
                        $par =& $this->postParameters[$cond->postparameter];
                    } elseif ( isset( $cond->constant ) ) {
                        $par =& $cond->constant;
                    } elseif ( isset( $cond->sessionparameter ) AND $this->sessionWrapper->isSessionParameterSet( $cond->sessionparameter ) ) {
                        $sp = $this->sessionWrapper->getSessionParameter( $cond->sessionparameter );
                        $par =& $sp;
                    } elseif ( isset( $cond->returnedid ) AND $this->queryReturnedValues->isValueSet($cond->returnedid) ) {
                        $sp =& $this->queryReturnedValues->getValue($cond->returnedid);
                        $par =& $sp;
                    }
                    // echo "$cond->placeholder, $par";
                    $STH->bindParam($cond->placeholder, $par);
                }
            }

            $STH->execute();

            // $STH->debugDumpParams();

            return $STH;
        } catch (PDOException $e) {
            $this->logger->write($e->getMessage(), __FILE__, __LINE__);
        }
    }

    /**
     * Perform a DELETE query to the database
     *
     * @return mixed
     */
    function executeSqlDelete() {
        try {
            //echo $this->queryStructure->sql;
            //echo "GET";
            //print_r($this->getParameters);
            //echo "POST";
            //print_r($this->postParameters);

            $STH = $this->DBH->prepare( $this->queryStructure->sql );
            $STH->setFetchMode(PDO::FETCH_OBJ);

            if ( isset($this->queryStructure->parameters) ) {
                foreach ($this->queryStructure->parameters as $cond) {
                    if ( isset( $this->getParameters[$cond->getparameter] ) ) {
                        $par =& $this->getParameters[$cond->getparameter];
                    } elseif ( isset( $this->postParameters[$cond->postparameter] ) ) {
                        $par =& $this->postParameters[$cond->postparameter];
                    } elseif ( isset( $cond->constant ) ) {
                        $par =& $cond->constant;
                    } elseif ( isset( $cond->sessionparameter ) AND $this->sessionWrapper->isSessionParameterSet( $cond->sessionparameter ) ) {
                        $par =& $this->sessionWrapper->getSessionParameter( $cond->sessionparameter );
                    } elseif ( isset( $cond->returnedid ) AND $this->queryReturnedValues->isValueSet($cond->returnedid) ) {
                        $par =& $this->queryReturnedValues->getValue($cond->returnedid);
                    }
                    // echo "$cond->placeholder, $par";
                    $STH->bindParam($cond->placeholder, $par);
                }
            }

            $STH->execute();

            // $STH->debugDumpParams();

            return $STH;
        } catch (PDOException $e) {
            $this->logger->write($e->getMessage(), __FILE__, __LINE__);
        }
    }

    /**
     * Check the SQL statment and return the constant:
     *   SELECT  if the statement starts with SELECT
     *   INSERT  if the statement starts with INSERT
     *   UPDATE  if the statement starts with UPDATE
     *   DELETE  if the statement starts with DELETE
     */
    function getSqlStatmentType() {
        if ( substr(strtoupper(trim($this->queryStructure->sql)), 0, 6) === self::SELECT ) {
            return self::SELECT;
        }
        if ( substr(strtoupper(trim($this->queryStructure->sql)), 0, 6) === self::INSERT  ) {
            return self::INSERT;
        }
        if ( substr(strtoupper(trim($this->queryStructure->sql)), 0, 6) === self::UPDATE  ) {
            return self::UPDATE;
        }
        if ( substr(strtoupper(trim($this->queryStructure->sql)), 0, 6) === self::DELETE  ) {
            return self::DELETE;
        }
    }

    /**
     * Call the specific function for performing the query depeding from the text of the SQL query.
     * If the text contains
     *   "SELECT"  calls executeSqlSelect
     *   "INSERT"  calls executeSqlInsert
     *   "UPDATE"  calls executeSqlUpdate
     *   "DELETE"  calls executeSqlDelete
     */
    function executeSql() {
        if ( strpos(strtoupper($this->queryStructure->sql), self::SELECT) !== false ) {
            return $this->executeSqlSelect();
        }
        if ( strpos(strtoupper($this->queryStructure->sql), self::INSERT) !== false ) {
            return $this->executeSqlInsert();
        }
        if ( strpos(strtoupper($this->queryStructure->sql), self::UPDATE) !== false ) {
            return $this->executeSqlUpdate();
        }
        if ( strpos(strtoupper($this->queryStructure->sql), self::DELETE) !== false ) {
            return $this->executeSqlDelete();
        }
    }

    /**
     * It gets all rows contained in a table
     */
    function executeSelect() {
        $this->queryBuilder = new QueryBuilder;
        $this->queryBuilder->setQueryStructure( $this->queryStructure );
        $this->queryBuilder->setParameters( $this->parameters );

        $STH = $this->DBH->prepare($this->queryBuilder->createQuery());
        try {
            $STH->setFetchMode(PDO::FETCH_OBJ);

			if ( isset($this->queryStructure->conditions) ) {
            	foreach ($this->queryStructure->conditions as $cond) {
                	$par =& $this->parameters[$cond->value];
               		$STH->bindParam(':'.$cond->value, $par);
            	}
			}

            $STH->execute();

            return $STH;
        } catch (PDOException $e) {
            $this->logger->write($e->getMessage(), __FILE__, __LINE__);
            $this->logger->write($STH->activeQueryString(), __FILE__, __LINE__);
        }
    }
	
    /**
     * It gets all rows contained in a table
     */
    function executeTableExists( $query ) {
        $out = false;
        $STH = $this->DBH->prepare( $query );
        try {
            $STH->setFetchMode(PDO::FETCH_OBJ);

            $STH->execute();

			foreach ($STH as $table) {
				$out = true;
			}
            return $out;
        } catch (PDOException $e) {
            $this->logger->write($e->getMessage(), __FILE__, __LINE__);
            $this->logger->write($STH->activeQueryString(), __FILE__, __LINE__);
        }
    }
	
    /**
     * It creates a table
     */
    function executeTableCreate( $query ) {
        $STH = $this->DBH->prepare( $query );
        try {
            $STH->execute();
        } catch (PDOException $e) {
            $this->logger->write($e->getMessage(), __FILE__, __LINE__);
            $this->logger->write($STH->activeQueryString(), __FILE__, __LINE__);
        }
    }
	
    /**
     * It drops a table
     */
    function executeTableDrop( $query ) {
        $STH = $this->DBH->prepare( $query );
        try {
            $STH->execute();
        } catch (PDOException $e) {
            $this->logger->write($e->getMessage(), __FILE__, __LINE__);
            $this->logger->write($STH->activeQueryString(), __FILE__, __LINE__);
        }
    }

    /**
     * Insert a row in the database.
     * Set the updated and created fields to current date and time
     * It accpts an array containing as key the field name and as value
     * the field content.
     *
     * @param $fields :: array of fields to insert
     *
     * EX.
     * array( 'field1' => 'content field 1', 'field2', 'content field 2' );
     */
    function insert() {
        $this->queryBuilder = new QueryBuilder;
        $this->queryBuilder->setQueryStructure( $this->queryStructure );
        $this->queryBuilder->setParameters( $this->parameters );

        $STH = $this->DBH->prepare($this->queryBuilder->createQuery());

        try {
            if ( isset($this->queryStructure->fields) ) {
                foreach ($this->queryStructure->fields as $field) {
                    $par =& $this->parameters[$field->value];
                    $STH->bindParam(':'.$field->value, $par);
                }
            }

            $STH->execute();
            
            return $STH;
        } catch (PDOException $e) {
            $this->logger->write($e->getMessage(), __FILE__, __LINE__);
            $this->logger->write($STH->activeQueryString(), __FILE__, __LINE__);
        }
    }

    /**
     * This function updates a single row of the delared table.
     * It uptades the row haveing id = $id
     * @param $id :: integer id
     * @param $fields :: array of fields to update
     * Ex. array( 'field1' => 'value1', 'field2' => 'value2' )
     *
     */
    function update() {
        $this->queryBuilder = new QueryBuilder;
        $this->queryBuilder->setQueryStructure( $this->queryStructure );
        $this->queryBuilder->setParameters( $this->parameters );

        $STH = $this->DBH->prepare($this->queryBuilder->createQuery());

        try {
            if ( isset($this->queryStructure->fields) ) {
                foreach ($this->queryStructure->fields as $field) {
                    $par =& $this->parameters[$field->value];
                    $STH->bindParam(':'.$field->value, $par);
                }
            }

            if ( isset($this->queryStructure->conditions) ) {
                foreach ($this->queryStructure->conditions as $cond) {
                    $par =& $this->parameters[$cond->value];
                    $STH->bindParam(':'.$cond->value, $par);
                }
            }

            $STH->execute();
            
            return $STH;
        } catch (PDOException $e) {
            $this->logger->write($e->getMessage(), __FILE__, __LINE__);
            $this->logger->write($STH->activeQueryString(), __FILE__, __LINE__);
        }
    }

    /**
     * This is the basic function for one row from a table specifying the primary key
     * of the row you want to delete.
     * Once you created a instance of the DAO object you can do for example:
     *
     * $tododao->delete( 15 );
     * this will delete the row having the primary key set to 15.
     *
     * Remeber that you need to set the primary key in the tabledao.php file
     * in a costant named DB_TABLE_PK
     *
     * Example:
     * const DB_TABLE_PK = 'stp_id';
     */
    function delete() {
        $this->queryBuilder = new QueryBuilder;
        $this->queryBuilder->setQueryStructure( $this->queryStructure );
        $this->queryBuilder->setParameters( $this->parameters );

        $STH = $this->DBH->prepare($this->queryBuilder->createQuery());

        try {
            if ( isset($this->queryStructure->conditions) ) {
                foreach ($this->queryStructure->conditions as $cond) {
                    $par =& $this->parameters[$cond->value];
                    $STH->bindParam(':'.$cond->value, $par);
                }
            }

            $STH->execute();
            
            return $STH;
        } catch (PDOException $e) {
            $this->logger->write($e->getMessage(), __FILE__, __LINE__);
            $this->logger->write($STH->activeQueryString(), __FILE__, __LINE__);
        }
    }

}
