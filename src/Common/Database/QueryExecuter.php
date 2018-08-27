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
    }

    /**
     * It gets all rows contained in a table
     */
    function executeSelect() {
        try {
			$this->queryBuilder = new QueryBuilder;
			$this->queryBuilder->setQueryStructure( $this->queryStructure );
			$this->queryBuilder->setParameters( $this->parameters );

echo($this->queryBuilder->createQuery());
print_r($this->queryStructure->conditions);
            
            $STH = $this->DBH->query($this->queryBuilder->createQuery());
            $STH->setFetchMode(PDO::FETCH_OBJ);

			if ( isset($this->queryStructure->conditions) ) {
            	foreach ($this->queryStructure->conditions as $cond) {
                	$par =& $this->parameters[$value];
               		$STH->bindParam($cond->value, $par);
            	}
			}
            
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
    function insert($fields) {
        $presentmoment = date('Y-m-d H:i:s', time());

        $filedslist = '';
        $filedsarguments = '';
        foreach ($fields as $key => $value) {
            $filedslist .= $key . ', ';
            $filedsarguments .= ':' . $key . ', ';
        }
        $filedslist = substr($filedslist, 0, -2);
        $filedsarguments = substr($filedsarguments, 0, -2);
        try {
            $this->DBH->beginTransaction();
            $STH = $this->DBH->prepare('INSERT INTO ' . $this::DB_TABLE . ' (' . $filedslist . ', ' . $this::DB_TABLE_UPDATED_FIELD_NAME . ', ' . $this::DB_TABLE_CREATED_FLIED_NAME . ') VALUES (' . $filedsarguments . ', "' . $presentmoment . '", "' . $presentmoment . '")');
            foreach ($fields as $key => &$value) {
                $STH->bindParam($key, $value);
            }
            $STH->execute();
            $inserted_id = $this->DBH->lastInsertId();
            $this->DBH->commit();
            return $inserted_id;
        } catch (PDOException $e) {
            $logger = new Logger();
            $logger->write($e->getMessage(), __FILE__, __LINE__);
            throw new \Exception('General malfuction!!!');
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
    function update($id, $fields) {
        $presentmoment = date('Y-m-d H:i:s', time());

        $filedslist = '';
        foreach ($fields as $key => $value) {
            $filedslist .= $key . ' = :' . $key . ', ';
        }
        $filedslist = substr($filedslist, 0, -2);
        try {
            $STH = $this->DBH->prepare('UPDATE ' . $this::DB_TABLE . ' SET ' . $filedslist . ', ' . $this::DB_TABLE_UPDATED_FIELD_NAME . ' = "' . $presentmoment . '" WHERE ' . $this::DB_TABLE_PK . ' = :id');
            foreach ($fields as $key => &$value) {
                $STH->bindParam($key, $value);
            }
            $STH->bindParam(':id', $id);
            $STH->execute();
        } catch (PDOException $e) {
            $logger = new Logger();
            $logger->write($e->getMessage(), __FILE__, __LINE__);
            throw new \Exception('General malfuction!!!');
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
    function delete( $id ) {
        try {
            $STH = $this->DBH->prepare('DELETE FROM ' . $this::DB_TABLE . ' WHERE ' . $this::DB_TABLE_PK . ' = :id');
            $STH->bindParam(':id', $id);
            $STH->execute();
        } catch (PDOException $e) {
            $logger = new Logger();
            $logger->write($e->getMessage(), __FILE__, __LINE__);
            throw new \Exception('General malfuction!!!');
        }
    }

}
