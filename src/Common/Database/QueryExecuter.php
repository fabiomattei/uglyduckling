<?php

namespace Firststep\Common\Database;

use PDO;
use Firststep\Common\Builders\QueryBuilder;

/**
 * It executes queries on the database
 */
class QueryExecuter {

    private $queryStructure;
    private $parameters;
    private $DBH;
    private $queryBuilder;

    /**
     * Database connection handler setter
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
    public function setGetParameters( array $getParameters ) {
        $this->getParameters = $getParameters;
    }

    /**
     * @param mixed $postParameters
     * the $parameters variable contains all values for the query
     */
    public function setPostParameters( array $postParameters ) {
        $this->postParameters = $postParameters;
    }

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
                    }
                    // echo "$cond->placeholder, $par";
                    $STH->bindParam($cond->placeholder, $par);
                }
            }

            $STH->execute();

            // $STH->debugDumpParams();

            return $STH;
        } catch (\PDOException $e) {
            $logger = new Logger();
            $logger->write($e->getMessage(), __FILE__, __LINE__);
        }
    }

    /**
     * It makes an INSERT query in the database and it returnd the id of the inserted row
     * using the function lastInsertId
     *
     * @return mixed
     */
    function executeSqlInsert() {
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
                    }
                    // echo "$cond->placeholder, $par";
                    $STH->bindParam($cond->placeholder, $par);
                }
            }

            $STH->execute();

            // $STH->debugDumpParams();

            return $STH;
        } catch (PDOException $e) {
            $logger = new Logger();
            $logger->write($e->getMessage(), __FILE__, __LINE__);
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
                    }
                    // echo "$cond->placeholder, $par";
                    $STH->bindParam($cond->placeholder, $par);
                }
            }

            $STH->execute();

            // $STH->debugDumpParams();

            return $STH;
        } catch (PDOException $e) {
            $logger = new Logger();
            $logger->write($e->getMessage(), __FILE__, __LINE__);
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
        if ( strpos(strtoupper($this->queryStructure->sql), 'SELECT') !== false ) {
            return $this->executeSqlSelect();
        }
        if ( strpos(strtoupper($this->queryStructure->sql), 'INSERT') !== false ) {
            return $this->executeSqlInsert();
        }
        if ( strpos(strtoupper($this->queryStructure->sql), 'UPDATE') !== false ) {
            return $this->executeSqlUpdate();
        }
        if ( strpos(strtoupper($this->queryStructure->sql), 'DELETE') !== false ) {
            return $this->executeSqlDelete();
        }
    }

    /**
     * It gets all rows contained in a table
     */
    function executeSelect() {
        try {
			$this->queryBuilder = new QueryBuilder;
			$this->queryBuilder->setQueryStructure( $this->queryStructure );
			$this->queryBuilder->setParameters( $this->parameters );

            $STH = $this->DBH->prepare($this->queryBuilder->createQuery());
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
            $logger = new Logger();
            $logger->write($e->getMessage(), __FILE__, __LINE__);
        }
    }
	
    /**
     * It gets all rows contained in a table
     */
    function executeTableExists( $query ) {
        try {
			$out = false;
            $STH = $this->DBH->query( $query );
            $STH->setFetchMode(PDO::FETCH_OBJ);
			
			foreach ($STH as $table) {
				$out = true;
			}
            return $out;
        } catch (PDOException $e) {
            $logger = new Logger();
            $logger->write($e->getMessage(), __FILE__, __LINE__);
        }
    }
	
    /**
     * It creates a table
     */
    function executeTableCreate( $query ) {
        try {
            $STH = $this->DBH->query( $query );
        } catch (PDOException $e) {
            $logger = new Logger();
            $logger->write($e->getMessage(), __FILE__, __LINE__);
        }
    }
	
    /**
     * It drops a table
     */
    function executeTableDrop( $query ) {
        try {
            $STH = $this->DBH->query( $query );
        } catch (PDOException $e) {
            $logger = new Logger();
            $logger->write($e->getMessage(), __FILE__, __LINE__);
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
        try {
            $this->queryBuilder = new QueryBuilder;
            $this->queryBuilder->setQueryStructure( $this->queryStructure );
            $this->queryBuilder->setParameters( $this->parameters );
            
            $STH = $this->DBH->prepare($this->queryBuilder->createQuery());

            if ( isset($this->queryStructure->fields) ) {
                foreach ($this->queryStructure->fields as $field) {
                    $par =& $this->parameters[$field->value];
                    $STH->bindParam(':'.$field->value, $par);
                }
            }

            $STH->execute();
            
            return $STH;
        } catch (PDOException $e) {
            $logger = new Logger();
            $logger->write($e->getMessage(), __FILE__, __LINE__);
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
        try {
            $this->queryBuilder = new QueryBuilder;
            $this->queryBuilder->setQueryStructure( $this->queryStructure );
            $this->queryBuilder->setParameters( $this->parameters );
            
            $STH = $this->DBH->prepare($this->queryBuilder->createQuery());

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
            $logger = new Logger();
            $logger->write($e->getMessage(), __FILE__, __LINE__);
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
        try {
            $this->queryBuilder = new QueryBuilder;
            $this->queryBuilder->setQueryStructure( $this->queryStructure );
            $this->queryBuilder->setParameters( $this->parameters );
            
            $STH = $this->DBH->prepare($this->queryBuilder->createQuery());

            if ( isset($this->queryStructure->conditions) ) {
                foreach ($this->queryStructure->conditions as $cond) {
                    $par =& $this->parameters[$cond->value];
                    $STH->bindParam(':'.$cond->value, $par);
                }
            }

            $STH->execute();
            
            return $STH;
        } catch (PDOException $e) {
            $logger = new Logger();
            $logger->write($e->getMessage(), __FILE__, __LINE__);
        }
    }

}
