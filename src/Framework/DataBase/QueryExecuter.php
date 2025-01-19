<?php

namespace Fabiom\UglyDuckling\Framework\DataBase;

use Fabiom\UglyDuckling\Framework\Utils\PageStatus;
use PDO;
use PDOException;

/**
 * It executes queries on the database
 */
class QueryExecuter {

    private $queryStructure;
    private $DBH;
    private PageStatus $pageStatus;
	private string $resourceName = 'unknown';

    public const SELECT = 'SELECT';
    public const INSERT = 'INSERT';
    public const UPDATE = 'UPDATE';
    public const DELETE = 'DELETE';

    /**
     * Database connection handler setter
     * @param $DBH
     */
    public function setResourceName( $resourceName ) {
        $this->resourceName = $resourceName;
    }

    public function setPageStatus( $pageStatus ) {
        $this->pageStatus = $pageStatus;
    }

    /**
     * Database connection handler setter
     * @param $DBH
     */
    public function setDBH( $DBH ) {
        $this->DBH = $DBH;
    }

    /**
     * @param mixed $queryStructure
     */
    public function setQueryStructure( $queryStructure ) {
        $this->queryStructure = $queryStructure;
    }

    /**
     * Perform a SELECT query on the database
     *
     * @return mixed
     */
    function executeSqlSelect() {
        try {
			$starttime = microtime(true);
			
            $STH = $this->DBH->prepare( $this->queryStructure->sql );
            $STH->setFetchMode(PDO::FETCH_OBJ);

            if ( isset($this->queryStructure->parameters) ) {
                $queryParameters = array();
                foreach ($this->queryStructure->parameters as $cond) {
                    $queryParameters[$cond->placeholder] = $this->pageStatus->getValue( $cond );
                }
                foreach ($this->queryStructure->parameters as $cond) {
                    if ( !isset( $cond->type ) ) {
                        $STH->bindParam($cond->placeholder, $queryParameters[$cond->placeholder]);
                    } else {
                        if ( $cond->type == 'long' OR $cond->type == 'int' ) {
                            $STH->bindParam($cond->placeholder, $queryParameters[$cond->placeholder], PDO::PARAM_INT );
                        }
                        if ( $cond->type == 'string' OR $cond->type == 'str' ) {
                            $STH->bindParam($cond->placeholder, $queryParameters[$cond->placeholder], PDO::PARAM_STR );
                        }
                        if ( $cond->type == 'bool' OR $cond->type == 'boolean' ) {
                            $STH->bindParam($cond->placeholder, $queryParameters[$cond->placeholder], PDO::PARAM_BOOL );
                        }
                        if ( $cond->type == 'float' OR $cond->type == 'decimal' ) {
							if ( is_numeric($queryParameters[$cond->placeholder]) ) {                                
								$STH->bindParam($cond->placeholder,  $queryParameters[$cond->placeholder], PDO::PARAM_STR );
							} else {
								$STH->bindParam($cond->placeholder,  "0", PDO::PARAM_STR );
							}
                        }
                    }
                }
            }

            if ( isset( $this->queryStructure->debug ) ) {
                print_r($queryParameters);
                echo strtr($this->queryStructure->sql, $queryParameters);
            }

            $STH->execute();
			
			$endtime = microtime(true);
			
	        if (($endtime - $starttime) > 5) {
	            $this->pageStatus->logger->write('[UD WARNING] QUERY TIME :: ' . ($this->resourceName === 'unknown' ? '' : 'Resource: ' . $this->resourceName . ' ' ) . $this->queryStructure->sql . ' - TIME: ' . ($endtime - $starttime) . ' sec', __FILE__, __LINE__);
	        }

	        if ( isset( $this->queryStructure->debug ) ) {
                $STH->debugDumpParams();
            }
	        // to debug
            // $STH->debugDumpParams();

            return $STH;
        } catch (\PDOException $e) {
            $this->pageStatus->logger->write( ($this->resourceName === 'unknown' ? '' : 'Resource: ' . $this->resourceName . ' ' ) . $e->getMessage(), __FILE__, __LINE__);
        }
    }

    /**
     * It makes an INSERT query in the database and it returnd the id of the inserted row
     * using the function lastInsertId
     *
     * @return mixed
     */
    function executeSqlInsert() {
		try {
        	$STH = $this->DBH->prepare( $this->queryStructure->sql );
        	$STH->setFetchMode(PDO::FETCH_OBJ);
        	
        	if ( isset($this->queryStructure->parameters) ) {
        	    $cont = 1;
        	    foreach ($this->queryStructure->parameters as $cond) {
                    $queryParameters = array();
                    foreach ($this->queryStructure->parameters as $cond) {
                        $queryParameters[$cond->placeholder] = $this->pageStatus->getValue( $cond );
                    }
                    foreach ($this->queryStructure->parameters as $cond) {
                        if ( !isset( $cond->type ) ) {
                            $STH->bindParam($cond->placeholder, $queryParameters[$cond->placeholder]);
                        } else {
                            if ( $cond->type == 'long' OR $cond->type == 'int' ) {
                                if ( isset( $cond->returnedid ) AND $this->pageStatus->getQueryReturnedValues()->isValueSet($cond->returnedid) ) {
                                    $par =& $this->pageStatus->getQueryReturnedValues()->getPointerToValue($cond->returnedid);
                                    $STH->bindParam($cond->placeholder, $par, PDO::PARAM_INT);
                                } else {
                                    $STH->bindParam($cond->placeholder, $queryParameters[$cond->placeholder], PDO::PARAM_INT );
                                }
                            }
                            if ( $cond->type == 'string' OR $cond->type == 'str' ) {
                                $STH->bindParam($cond->placeholder, $queryParameters[$cond->placeholder], PDO::PARAM_STR );
                            }
                            if ( $cond->type == 'bool' OR $cond->type == 'boolean' ) {
                                $STH->bindParam($cond->placeholder, $queryParameters[$cond->placeholder], PDO::PARAM_BOOL );
                            }
                            if ( $cond->type == 'float' OR $cond->type == 'decimal' ) {
                                if ( is_numeric($queryParameters[$cond->placeholder]) ) {                                
                                    $STH->bindParam($cond->placeholder,  $queryParameters[$cond->placeholder], PDO::PARAM_STR );
                                } else {
                                    $STH->bindParam($cond->placeholder,  "0", PDO::PARAM_STR );
                                }
                            }
                        }
                    }

                    /*
        	        } elseif ( isset( $cond->returnedid ) AND $this->queryReturnedValues->isValueSet($cond->returnedid) ) {

        	        } elseif ( isset( $cond->fileparameter ) AND isset( $_FILES[$cond->fileparameter] ) ) {
        	            $mime[$cont] = $_FILES[$cond->fileparameter]['type'] ?? '';
        	            $size[$cont] = $_FILES[$cond->fileparameter]['size'] ?? '';
        	            $error[$cont] = $_FILES[$cond->fileparameter]['error'] ?? '';
        	            $name[$cont] = $_FILES[$cond->fileparameter]['name'] ?? '';
        	            $tmpf[$cont] = $_FILES[$cond->fileparameter]['tmp_name'];
        	            $file[$cont] = fopen($_FILES[$cond->fileparameter]['tmp_name'], "rb");
        	            $mime =& $mime[$cont];
        	            $size =& $size[$cont];
        	            $name =& $name[$cont];
        	            $par =& $file[$cont];
        	            $STH->bindParam($cond->placeholder.'mime', $mime);
        	            $STH->bindParam($cond->placeholder.'size', $size);
        	            $STH->bindParam($cond->placeholder.'name', $name);
        	            $STH->bindParam($cond->placeholder.'error', $error);
        	            $STH->bindParam($cond->placeholder, $par, PDO::PARAM_LOB);
        	        */

        	        $cont++;
        	    }
        	}

            if ( isset( $this->queryStructure->debug ) ) {
                print_r($queryParameters);
                echo strtr($this->queryStructure->sql, $queryParameters);
            }
			
			$STH->execute();

            if ( isset( $this->queryStructure->debug ) ) {
                $STH->debugDumpParams();
            }
			
		} catch (\PDOException $e) {
            $this->pageStatus->logger->write( ($this->resourceName === 'unknown' ? '' : 'Resource: ' . $this->resourceName . ' ' ) . $e->getMessage(), __FILE__, __LINE__);
    	}

    	// uncomment for debug purpose
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

            $STH = $this->DBH->prepare( $this->queryStructure->sql );
            $STH->setFetchMode(PDO::FETCH_OBJ);

            if ( isset($this->queryStructure->parameters) ) {
                $queryParameters = array();
                foreach ($this->queryStructure->parameters as $cond) {
                    $queryParameters[$cond->placeholder] = $this->pageStatus->getValue( $cond );
                }
                foreach ($this->queryStructure->parameters as $cond) {
                    if ( !isset( $cond->type ) ) {
                        $STH->bindParam($cond->placeholder, $queryParameters[$cond->placeholder]);
                    } else {
                        if ( $cond->type == 'long' OR $cond->type == 'int' ) {
                            $STH->bindParam($cond->placeholder, $queryParameters[$cond->placeholder], PDO::PARAM_INT );
                        }
                        if ( $cond->type == 'string' OR $cond->type == 'str' ) {
                            $STH->bindParam($cond->placeholder, $queryParameters[$cond->placeholder], PDO::PARAM_STR );
                        }
                        if ( $cond->type == 'bool' OR $cond->type == 'boolean' ) {
                            $STH->bindParam($cond->placeholder, $queryParameters[$cond->placeholder], PDO::PARAM_BOOL );
                        }
                        if ( $cond->type == 'float' OR $cond->type == 'decimal' ) {
                            if ( is_numeric($queryParameters[$cond->placeholder]) ) {                                
                                $STH->bindParam($cond->placeholder,  $queryParameters[$cond->placeholder], PDO::PARAM_STR );
                            } else {
                                $STH->bindParam($cond->placeholder,  "0", PDO::PARAM_STR );
                            }
                        }
                    }
                }
            }

            if ( isset( $this->queryStructure->debug ) ) {
                print_r($queryParameters);
                echo strtr($this->queryStructure->sql, $queryParameters);
            }

            $STH->execute();

            if ( isset( $this->queryStructure->debug ) ) {
                $STH->debugDumpParams();
            }

            // uncomment for debug purpose
            // $STH->debugDumpParams();

            return $STH;
        } catch (\PDOException $e) {
            $this->pageStatus->logger->write( ($this->resourceName === 'unknown' ? '' : 'Resource: ' . $this->resourceName . ' ' ) . $e->getMessage(), __FILE__, __LINE__);
        }
    }

    /**
     * Perform a DELETE query to the database
     *
     * @return mixed
     */
    function executeSqlDelete() {
        try {
            $STH = $this->DBH->prepare( $this->queryStructure->sql );
            $STH->setFetchMode(PDO::FETCH_OBJ);

            if ( isset($this->queryStructure->parameters) ) {
                if ( isset($this->queryStructure->parameters) ) {
                    $queryParameters = array();
                    foreach ($this->queryStructure->parameters as $cond) {
                        $queryParameters[$cond->placeholder] = $this->pageStatus->getValue( $cond );
                    }
                    foreach ($this->queryStructure->parameters as $cond) {
                        if ( !isset( $cond->type ) ) {
                            $STH->bindParam($cond->placeholder, $queryParameters[$cond->placeholder]);
                        } else {
                            if ( $cond->type == 'long' OR $cond->type == 'int' ) {
                                $STH->bindParam($cond->placeholder, $queryParameters[$cond->placeholder], PDO::PARAM_INT );
                            }
                            if ( $cond->type == 'string' OR $cond->type == 'str' ) {
                                $STH->bindParam($cond->placeholder, $queryParameters[$cond->placeholder], PDO::PARAM_STR );
                            }
                            if ( $cond->type == 'bool' OR $cond->type == 'boolean' ) {
                                $STH->bindParam($cond->placeholder, $queryParameters[$cond->placeholder], PDO::PARAM_BOOL );
                            }
                            if ( $cond->type == 'float' OR $cond->type == 'decimal' ) {
                                if ( is_numeric($queryParameters[$cond->placeholder]) ) {                                
                                    $STH->bindParam($cond->placeholder,  $queryParameters[$cond->placeholder], PDO::PARAM_STR );
                                } else {
                                    $STH->bindParam($cond->placeholder,  "0", PDO::PARAM_STR );
                                }
                            }
                        }
                    }
                }
            }

            if ( isset( $this->queryStructure->debug ) ) {
                print_r($queryParameters);
                echo strtr($this->queryStructure->sql, $queryParameters);
            }

            $STH->execute();

            if ( isset( $this->queryStructure->debug ) ) {
                $STH->debugDumpParams();
            }

            // $STH->debugDumpParams();

            return $STH;
        } catch (\PDOException $e) {
            $this->pageStatus->logger->write( ($this->resourceName === 'unknown' ? '' : 'Resource: ' . $this->resourceName . ' ' ) . $e->getMessage(), __FILE__, __LINE__);
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
            if (isset( $this->queryStructure->fieldtosave )) {
                $obj = $this->executeSqlSelect()->fetch();
                $field = $this->queryStructure->fieldtosave;
                return $obj->$field;
            } else {
                return $this->executeSqlSelect();
            }
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
        } catch (\PDOException $e) {
            $this->pageStatus->logger->write($e->getMessage(), __FILE__, __LINE__);
            $this->pageStatus->logger->write($STH->activeQueryString(), __FILE__, __LINE__);
        }
    }
	
    /**
     * It creates a table
     */
    function executeTableCreate( $query ) {
        $STH = $this->DBH->prepare( $query );
        try {
            $STH->execute();
        } catch (\PDOException $e) {
            $this->pageStatus->logger->write($e->getMessage(), __FILE__, __LINE__);
            $this->pageStatus->logger->write($STH->activeQueryString(), __FILE__, __LINE__);
        }
    }
	
    /**
     * It drops a table
     */
    function executeTableDrop( $query ) {
        $STH = $this->DBH->prepare( $query );
        try {
            $STH->execute();
        } catch (\PDOException $e) {
            $this->pageStatus->logger->write($e->getMessage(), __FILE__, __LINE__);
            $this->pageStatus->logger->write($STH->activeQueryString(), __FILE__, __LINE__);
        }
    }
}
