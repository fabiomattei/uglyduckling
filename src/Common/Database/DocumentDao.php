<?php

namespace Firststep\Common\Database;

use PDO;

/**
 * This class allows me to work with all documents tables
 */
class DocumentDao {

	/* I set few costants with fields name, this allows me to record when the document changed status */
	const DB_TABLE_ID_FIELD_NAME           = 'id';
	const DB_TABLE_SOURCE_ID_FIELD_NAME    = 'sourceuserid';
	const DB_TABLE_SOURCE_GROUP_FIELD_NAME = 'sourcegroup'; 
	const DB_TABLE_STATUS_FIELD_NAME       = 'docstatus';
	const DB_TABLE_CREATED_FIELD_NAME      = 'doccreated';
    const DB_TABLE_UPDATED_FIELD_NAME      = 'docupdated';
	const DB_TABLE_SENT_FIELD_NAME         = 'docsent';
    const DB_TABLE_RECEIVED_FIELD_NAME     = 'docreceived';
	const DB_TABLE_REJECTED_FIELD_NAME     = 'docrejected';
	
	/* Possible document status */
	const DOC_STATUS_DRAFT = 'DRAFT';
	const DOC_STATUS_SENT = 'SENT';
	const DOC_STATUS_RECEIVED = 'RECEIVED';
	const DOC_STATUS_REJECTED = 'REJECTED';

	/**
	 * Contructor of the class
	 */
    function __construct() {
    }

	/**
	 * Setting table name
	 */
	public function setTableName( $tablename ) {
		$this->tablename = $tablename;
	}
	
    /**
     * Database connection handler setter
     */
    public function setDBH( $DBH ) {
		$this->DBH = $DBH;
    }

    /**
     * It gets all rows contained in a table
     */
    function getAll() {
        try {
            $STH = $this->DBH->query('SELECT * FROM ' . $this->tablename);
            $STH->setFetchMode(PDO::FETCH_OBJ);

            return $STH;
        } catch (PDOException $e) {
            $logger = new Logger();
            $logger->write($e->getMessage(), __FILE__, __LINE__);
        }
    }

    /**
     * It Get the row with the selected id
     * if no corresponding row is found it gives the empty object
     * calling the getEmpty method (null object).
     */
    function getById( $id ) {
        try {
            $STH = $this->DBH->prepare('SELECT * from ' . $this->tablename . ' WHERE ' . $this::DB_TABLE_ID_FIELD_NAME . ' = :id');
            $STH->bindParam( ':id' , $id );
            $STH->execute();

            # setting the fetch mode
            $STH->setFetchMode(PDO::FETCH_OBJ);
            $obj = $STH->fetch();

            if ($obj == null) {
                $obj = $this->getEmpty();
            }

            return $obj;
        } catch (PDOException $e) {
            $logger = new Logger();
            $logger->write($e->getMessage(), __FILE__, __LINE__);
            throw new \Exception('General malfuction!!!');
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
    function insert( $fields ) {
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
            $STH = $this->DBH->prepare('INSERT INTO ' . $this->tablename . ' (' . $filedslist . ', ' . $this::DB_TABLE_STATUS_FIELD_NAME . ', ' . $this::DB_TABLE_CREATED_FIELD_NAME . ', ' . $this::DB_TABLE_UPDATED_FIELD_NAME . ') VALUES (' . $filedsarguments . ', "' . $this::DOC_STATUS_DRAFT . '", "' . $presentmoment . '", "' . $presentmoment . '")');
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
            $STH = $this->DBH->prepare('UPDATE ' . $this->tablename . ' SET ' . $this::DB_TABLE_STATUS_FIELD_NAME . ', ' . $this::DB_TABLE_UPDATED_FIELD_NAME . ' = "' . $presentmoment . '" WHERE ' . $this::DB_TABLE_ID_FIELD_NAME . ' = :id');
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
     * Is set all necessary fields in order to obtain a sent document
     *
     * Th updates the status field and the date sent field
     *
     * @param $id
     * @throws \Exception
     */
    function updateSend( $id ) {
        $presentmoment = date('Y-m-d H:i:s', time());

        try {
            $STH = $this->DBH->prepare('UPDATE ' . $this->tablename . ' SET ' . $this::DB_TABLE_STATUS_FIELD_NAME . ' = "' . $this::DOC_STATUS_SENT . '", ' . $this::DB_TABLE_SENT_FIELD_NAME . ' = "' . $presentmoment . '" WHERE ' . $this::DB_TABLE_ID_FIELD_NAME . ' = :id');
            $STH->bindParam(':id', $id);
            $STH->execute();
        } catch (PDOException $e) {
            $logger = new Logger();
            $logger->write($e->getMessage(), __FILE__, __LINE__);
            throw new \Exception('General malfuction!!!');
        }
    }

    /**
     * This method cares about the deletion of a row from the table
     *
     * Example:
     *
     * $documentDao->delete( 15 );
     * this will delete the row having the primary key set to 15.
     *
     * The primary key field name is stored in the costant: DB_TABLE_ID_FIELD_NAME
     */
    function delete( $id ) {
        try {
            $STH = $this->DBH->prepare('DELETE FROM ' . $this->tablename . ' WHERE ' . $this::DB_TABLE_ID_FIELD_NAME . ' = :id');
            $STH->bindParam(':id', $id);
            $STH->execute();
        } catch (PDOException $e) {
            $logger = new Logger();
            $logger->write($e->getMessage(), __FILE__, __LINE__);
            throw new \Exception('General malfuction!!!');
        }
    }

    /**
     * This function deletes a set of row from a table depending from the
     * parameters you set when calling it.
     *
     * $tododao->delete( array( 'open' => '0', 'handling' => '1' ) );
     * this will delete the row having the field open set to 0 and the field handling set to 1.
     *
     * Remeber that you need to set the table name in the tabledao.php file
     * in a costant named DB_TABLE
     *
     * Example:
     * const DB_TABLE = 'mytablename';
     */
    function deleteByFields( $fields ) {
        $filedslist = '';
        foreach ($fields as $key => $value) {
            $filedslist .= $key . ' = :' . $key . ' AND ';
        }
        $filedslist = substr($filedslist, 0, -4);
        try {
            $STH = $this->DBH->prepare('DELETE FROM ' . $this->tablename . ' WHERE ' . $filedslist);
            foreach ($fields as $key => &$value) {
                $STH->bindParam($key, $value);
            }
            $STH->execute();
        } catch (PDOException $e) {
            $logger = new Logger();
            $logger->write($e->getMessage(), __FILE__, __LINE__);
            throw new \Exception('General malfuction!!!');
        }
    }

    /**
     * This is the basic function for getting a set of elements from a table.
     * Once you created a instance of the DAO object you can do for example:
     *
     * $tododao->getByFields( array( 'open' => '0' ) );
     * this will get all the row having the field open = 0
     *
     * you can set more then a search parameter (evaluated in AND)
     * $tododao->getByFields( array( 'open' => '0', 'handling' => '1' ) );
     *
     * you can even specify how to order the rows you requested
     * $tododao->getByFields( array( 'id' => '42' ), array('name', 'description') );
     *
     * you can even request few specific fields and not the whole table fields
     * $tododao->getByFields( array( 'id' => '42' ), array('name', 'description'), array('id', 'name', 'description') );
     */
    public function getByFields($conditionsfields, $orderby = 'none', $requestedfields = 'none') {
        $filedslist = $this->organizeConditionsFields($conditionsfields);

        $requestedfieldlist = $this->organizeRequestedFields($requestedfields);

        $orderbyfieldlist = $this->organizeOrderByFields($orderby);

        try {
            // building the query
            $query = 'SELECT ' . $requestedfieldlist . ' FROM ' . $this->tablename . ' ';
            if ($filedslist != '') {
                $query .= 'WHERE ' . $filedslist . ' ';
            }
            $query .= $orderbyfieldlist;

            $STH = $this->DBH->prepare($query);
            foreach ($conditionsfields as $key => &$value) {
                $STH->bindParam($key, $value);
            }
            $STH->execute();

            # setting the fetch mode
            $STH->setFetchMode(PDO::FETCH_OBJ);

            return $STH;
        } catch (PDOException $e) {
            $logger = new Logger();
            $logger->write($e->getMessage(), __FILE__, __LINE__);
            throw new \Exception('General malfuction!!!');
        }
    }
	
    /**
     * Getting all documents related to a specifing group as a source
	 */
    public function getGroupOutbox( $requestedfieldlist, $groupname ) {
		$fields = $this->organizeRequestedFields( $requestedfieldlist );
        try {
            $query = 'SELECT ' . $this::DB_TABLE_ID_FIELD_NAME . ', ' . $fields . ' FROM ' . $this->tablename . ' ';
            $query .= 'WHERE (' . $this::DB_TABLE_STATUS_FIELD_NAME . '="' . $this::DOC_STATUS_SENT . '" ' .
				' OR ' . $this::DB_TABLE_STATUS_FIELD_NAME . '="' . $this::DOC_STATUS_RECEIVED . '" ' .
				' OR ' . $this::DB_TABLE_STATUS_FIELD_NAME . '="' . $this::DOC_STATUS_REJECTED .'") ' .
				' AND ' . $this::DB_TABLE_SOURCE_GROUP_FIELD_NAME . '= :'.$this::DB_TABLE_SOURCE_GROUP_FIELD_NAME.' ;';
            $STH = $this->DBH->prepare($query);
			$STH->bindParam($this::DB_TABLE_SOURCE_GROUP_FIELD_NAME, $groupname);
            $STH->execute();

            # setting the fetch mode
            $STH->setFetchMode(PDO::FETCH_OBJ);

            return $STH;
        } catch (PDOException $e) {
            $logger = new Logger();
            $logger->write($e->getMessage(), __FILE__, __LINE__);
            throw new \Exception('General malfuction!!!');
        }
    }
	
    /**
     * Getting all documents related to a specifing user as a source
	 */
    public function getUserGroupOutbox( $requestedfieldlist, $groupname, $userid ) {
		$fields = $this->organizeRequestedFields( $requestedfieldlist );
        try {
            $query = 'SELECT ' . $this::DB_TABLE_ID_FIELD_NAME . ', ' . $fields . ' FROM ' . $this->tablename . ' ';
            $query .= 'WHERE (' . $this::DB_TABLE_STATUS_FIELD_NAME . '="' . $this::DOC_STATUS_SENT . '" ' .
				' OR ' . $this::DB_TABLE_STATUS_FIELD_NAME . '="' . $this::DOC_STATUS_RECEIVED . '" ' .
				' OR ' . $this::DB_TABLE_STATUS_FIELD_NAME . '="' . $this::DOC_STATUS_REJECTED .'") ' .
				' AND ' . $this::DB_TABLE_SOURCE_GROUP_FIELD_NAME . '= :'.$this::DB_TABLE_SOURCE_GROUP_FIELD_NAME.' ' .
				' AND ' . $this::DB_TABLE_SOURCE_ID_FIELD_NAME . '= :'.$this::DB_TABLE_SOURCE_ID_FIELD_NAME.' ' . ';';
            $STH = $this->DBH->prepare($query);
			$STH->bindParam($this::DB_TABLE_SOURCE_GROUP_FIELD_NAME, $groupname);
			$STH->bindParam($this::DB_TABLE_SOURCE_ID_FIELD_NAME, $userid);
            $STH->execute();

            # setting the fetch mode
            $STH->setFetchMode(PDO::FETCH_OBJ);

            return $STH;
        } catch (PDOException $e) {
            $logger = new Logger();
            $logger->write($e->getMessage(), __FILE__, __LINE__);
            throw new \Exception('General malfuction!!!');
        }
    }

    /**
     * Getting all documents related to a specifing group as a source
     */
    public function getGroupInbox( $requestedfieldlist, $groupname ) {
        $fields = $this->organizeRequestedFields( $requestedfieldlist );
        try {
            $query = 'SELECT ' . $this::DB_TABLE_ID_FIELD_NAME . ', ' . $fields . ' FROM ' . $this->tablename . ' ';
            $query .= 'WHERE (' . $this::DB_TABLE_STATUS_FIELD_NAME . '="' . $this::DOC_STATUS_SENT . '") ' .
                ' AND ' . $this::DB_TABLE_SOURCE_GROUP_FIELD_NAME . '= :'.$this::DB_TABLE_SOURCE_GROUP_FIELD_NAME.' ;';
            $STH = $this->DBH->prepare($query);
            $STH->bindParam($this::DB_TABLE_SOURCE_GROUP_FIELD_NAME, $groupname);
            $STH->execute();

            # setting the fetch mode
            $STH->setFetchMode(PDO::FETCH_OBJ);

            return $STH;
        } catch (PDOException $e) {
            $logger = new Logger();
            $logger->write($e->getMessage(), __FILE__, __LINE__);
            throw new \Exception('General malfuction!!!');
        }
    }
	
    /**
     * Getting all documents related to a specifing group as a source
	 */
    public function getGroupDraft( $requestedfieldlist, $groupname ) {
		$fields = $this->organizeRequestedFields( $requestedfieldlist );
        try {
            $query = 'SELECT ' . $this::DB_TABLE_ID_FIELD_NAME . ', ' . $fields . ' FROM ' . $this->tablename . ' ';
            $query .= 'WHERE ' . $this::DB_TABLE_STATUS_FIELD_NAME . '="' . $this::DOC_STATUS_DRAFT . '" ' .
				' AND ' . $this::DB_TABLE_SOURCE_GROUP_FIELD_NAME . '= :'.$this::DB_TABLE_SOURCE_GROUP_FIELD_NAME.' ;';
            $STH = $this->DBH->prepare($query);
			$STH->bindParam($this::DB_TABLE_SOURCE_GROUP_FIELD_NAME, $groupname);
            $STH->execute();

            # setting the fetch mode
            $STH->setFetchMode(PDO::FETCH_OBJ);

            return $STH;
        } catch (PDOException $e) {
            $logger = new Logger();
            $logger->write($e->getMessage(), __FILE__, __LINE__);
            throw new \Exception('General malfuction!!!');
        }
    }
	
    /**
     * Getting all documents related to a specifing user as a source
	 */
    public function getUserGroupDraft( $requestedfieldlist, $groupname, $userid ) {
		$fields = $this->organizeRequestedFields( $requestedfieldlist );
        try {
            $query = 'SELECT ' . $this::DB_TABLE_ID_FIELD_NAME . ', ' . $fields . ' FROM ' . $this->tablename . ' ';
            $query .= 'WHERE ' . $this::DB_TABLE_STATUS_FIELD_NAME . '="' . $this::DOC_STATUS_DRAFT . '" ' .
				' AND ' . $this::DB_TABLE_SOURCE_GROUP_FIELD_NAME . '= :'.$this::DB_TABLE_SOURCE_GROUP_FIELD_NAME.' ' .
				' AND ' . $this::DB_TABLE_SOURCE_ID_FIELD_NAME . '= :'.$this::DB_TABLE_SOURCE_ID_FIELD_NAME.' ' . ';';
            $STH = $this->DBH->prepare($query);
			$STH->bindParam($this::DB_TABLE_SOURCE_GROUP_FIELD_NAME, $groupname);
			$STH->bindParam($this::DB_TABLE_SOURCE_ID_FIELD_NAME, $userid);
            $STH->execute();

            # setting the fetch mode
            $STH->setFetchMode(PDO::FETCH_OBJ);

            return $STH;
        } catch (PDOException $e) {
            $logger = new Logger();
            $logger->write($e->getMessage(), __FILE__, __LINE__);
            throw new \Exception('General malfuction!!!');
        }
    }

    /**
     * This function allows user to get a set of elements from a table.
     *
     * @param $fieldname                name of field that needs to be confronted with the array of ids
     * @param $ids                      array of ids
     * @param $conditionsfields
     * @param string $orderby
     * @param string $requestedfields
     * @return array|PDOStatement
     * @throws\Exception
     */
    public function getByFieldList($fieldname, $ids, $conditionsfields, $orderby = 'none', $requestedfields = 'none') {
        if (count($ids) > 0) {
            $ids_string = join(',', $ids);

            $filedslist = $this->organizeConditionsFields($conditionsfields);

            $requestedfieldlist = $this->organizeRequestedFields($requestedfields);

            $orderbyfieldlist = $this->organizeOrderByFields($orderby);

            try {
                // building the query
                $query = 'SELECT ' . $requestedfieldlist . ' FROM ' . $this->tablename . ' ';
                $query .= 'WHERE ' . $fieldname . ' IN (' . $ids_string . ') ';
                if ($filedslist != '') {
                    $query .= 'AND ' . $filedslist;
                }
                $query .= $orderbyfieldlist;

                $STH = $this->DBH->prepare($query);

                foreach ($conditionsfields as $key => &$value) {
                    $STH->bindParam($key, $value);
                }

                $STH->execute();

                # setting the fetch mode
                $STH->setFetchMode(PDO::FETCH_OBJ);

                return $STH;
            } catch (PDOException $e) {
                $logger = new Logger();
                $logger->write($e->getMessage(), __FILE__, __LINE__);
                throw new \Exception('General malfuction!!!');
            }
        } else {
            return array();
        }
    }
	
    /**
     * This function allows user to get a set of elements from a table.
     *
     * @param  $fieldname                name of field that needs to be confronted with the array of ids
     * @param  $ids                      array of ids
     * @param  $conditionsfields
     * @param  string $orderby
     * @param  string $requestedfields
     * @return array|PDOStatement
     * @throws\Exception
     */
    public function getArrayByFieldList($fieldname, $ids, $conditionsfields, $orderby = 'none', $requestedfields = 'none') {
        if (count($ids) > 0) {
            $ids_string = join(',', $ids);

            $filedslist = $this->organizeConditionsFields($conditionsfields);

            $requestedfieldlist = $this->organizeRequestedFields($requestedfields);

            $orderbyfieldlist = $this->organizeOrderByFields($orderby);

            try {
                // building the query
                $query = 'SELECT ' . $requestedfieldlist . ' FROM ' . $this->tablename . ' ';
                $query .= 'WHERE ' . $fieldname . ' IN (' . $ids_string . ') ';
                if ($filedslist != '') {
                    $query .= 'AND ' . $filedslist;
                }
                $query .= $orderbyfieldlist;

                $STH = $this->DBH->prepare($query);

                foreach ($conditionsfields as $key => &$value) {
                    $STH->bindParam($key, $value);
                }

	            $STH->execute();

	            # setting the fetch mode
	            $STH->setFetchMode(PDO::FETCH_OBJ);

			    $out = array();
			    while ($item = $STH->fetch()) {
			        $id = $item->{$this->pk};
			        $out[$id] = $item;
			    }

			    return $out;
            } catch (PDOException $e) {
                $logger = new Logger();
                $logger->write($e->getMessage(), __FILE__, __LINE__);
                throw new \Exception('General malfuction!!!');
            }
        } else {
            return array();
        }
    }

    /**
     * This is the basic function for getting one element from a table.
     * Once you created a instance of the DAO object you can do for example:
     *
     * $tododao->getOneByFields( array( 'id' => '42' ) );
     * this will get the field having id = 42
     *
     * you can set more then a search parameter (evaluated in AND)
     * $tododao->getOneByFields( array( 'id' => '42', 'open' => '1' ) );
     *
     * you can even request few specific fields and not the whole table fields
     * $tododao->getOneByFields( array( 'id' => '42' ), array('id', 'name', 'description') );
     */
    public function getOneByFields($conditionsfields, $requestedfields = 'none') {
        $filedslist = $this->organizeConditionsFields($conditionsfields);

        $requestedfieldlist = $this->organizeRequestedFields($requestedfields);

        try {
            // building the query
            $query = 'SELECT ' . $requestedfieldlist . ' FROM ' . $this->tablename . ' ';
            if ($filedslist != '') {
                $query .= 'WHERE ' . $filedslist;
            }

            $STH = $this->DBH->prepare($query);
            foreach ($conditionsfields as $key => &$value) {
                $STH->bindParam($key, $value);
            }
            $STH->execute();

            # setting the fetch mode
            $STH->setFetchMode(PDO::FETCH_OBJ);
            $obj = $STH->fetch();

            if ($obj == null) {
                $obj = $this->getEmpty();
            }

            return $obj;
        } catch (PDOException $e) {
            $logger = new Logger();
            $logger->write($e->getMessage(), __FILE__, __LINE__);
            throw new \Exception('General malfuction!!!');
        }
    }

    /**
     * This is the basic function for getting an array of elements from a table.
     * The returned array will have the entity id as index
     * Once you created a instance of the DAO object you can do for example:
     *
     * $tododao->getByFields( array( 'open' => '0' ) );
     * this will get all the row having the field open = 0
     *
     * you can set more then a search parameter (evaluated in AND)
     * $tododao->getByFields( array( 'open' => '0', 'handling' => '1' ) );
     *
     * you can even specify how to order the rows you requested
     * $tododao->getByFields( array( 'id' => '42' ), array('name', 'description') );
     *
     * you can even request few specific fields and not the whole table fields
     * $tododao->getByFields( array( 'id' => '42' ), array('name', 'description'), array('id', 'name', 'description') );
     */
    public function getArrayByFields($conditionsfields, $orderby = 'none', $requestedfields = 'none') {
        $filedslist = $this->organizeConditionsFields($conditionsfields);

        $requestedfieldlist = $this->organizeRequestedFields($requestedfields);

        $orderbyfieldlist = $this->organizeOrderByFields($orderby);

        try {
            // building the query
            $query = 'SELECT ' . $requestedfieldlist . ' FROM ' . $this->tablename . ' ';
            if ($filedslist != '') {
                $query .= 'WHERE ' . $filedslist . ' ';
            }
            $query .= $orderbyfieldlist;

            $STH = $this->DBH->prepare($query);
            foreach ($conditionsfields as $key => &$value) {
                $STH->bindParam($key, $value);
            }
            $STH->execute();

            # setting the fetch mode
            $STH->setFetchMode(PDO::FETCH_OBJ);

            $out = array();
            while ($item = $STH->fetch()) {
                $id = $item->{$this->pk};
                $out[$id] = $item;
            }

            return $out;
        } catch (PDOException $e) {
            $logger = new Logger();
            $logger->write($e->getMessage(), __FILE__, __LINE__);
            throw new \Exception('General malfuction!!!');
        }
    }

    /**
     * This method allow to count the number of rows, contained in a table, that
     * respect given conditions.
     *
     * Once you created a instance of the DAO object you can do for example:
     *
     * $tododao->getByFields( array( 'open' => '0' ) );
     * this will get all the row having the field open = 0
     *
     * you can set more then a search parameter (evaluated in AND)
     * $tododao->getByFields( array( 'open' => '0', 'handling' => '1' ) );
     */
    public function countByFields( $conditionsfields ) {
        $filedslist = $this->organizeConditionsFields($conditionsfields);

        try {
            // building the query
            $query = 'SELECT COUNT(' . $this->pk . ') as countresult FROM ' . $this->tablename . ' ';
            if ($filedslist != '') {
                $query .= 'WHERE ' . $filedslist . ' ';
            }

            $STH = $this->DBH->prepare($query);
            foreach ($conditionsfields as $key => &$value) {
                $STH->bindParam($key, $value);
            }
            $STH->execute();

            # setting the fetch mode
            $STH->setFetchMode(PDO::FETCH_OBJ);

            $out = 0;
            if ( $item = $STH->fetch() ) {
                $out = $item->countresult;
            }

            return $out;
        } catch (PDOException $e) {
            $logger = new Logger();
            $logger->write($e->getMessage(), __FILE__, __LINE__);
            throw new \Exception('General malfuction!!!');
        }
    }

    /**
     * This method allow to count the number of rows, contained in a table, that
     * respect given conditions.
     *
     * @param $fieldname                name of field that needs to be confronted with the array of ids
     * @param $ids                      array of ids
     * @param $conditionsfields
     * @param string $orderby
     * @param string $requestedfields
     * @return array|PDOStatement
     * @throws\Exception
     */
    public function countByFieldList($fieldname, $ids, $conditionsfields, $orderby = 'none', $requestedfields = 'none') {
        if (count($ids) > 0) {
            $ids_string = join(',', $ids);

            $filedslist = $this->organizeConditionsFields($conditionsfields);

            $orderbyfieldlist = $this->organizeOrderByFields($orderby);

            try {
                // building the query
                $query = 'SELECT ' . $fieldname . ', COUNT(' . $this->pk . ') as countresult FROM ' . $this->tablename . ' ';
                $query .= 'WHERE ' . $fieldname . ' IN (' . $ids_string . ') ';
                if ($filedslist != '') {
                    $query .= 'AND ' . $filedslist;
                }
                $query .= ' GROUP BY ' . $fieldname;
                $query .= $orderbyfieldlist;

                $STH = $this->DBH->prepare($query);

                foreach ($conditionsfields as $key => &$value) {
                    $STH->bindParam($key, $value);
                }

                $STH->execute();

                # setting the fetch mode
                $STH->setFetchMode(PDO::FETCH_OBJ);

                $out = array();
                while ($item = $STH->fetch()) {
                    $out[$item->{$fieldname}] = $item->countresult;
                }

                return $out;
            } catch (PDOException $e) {
                $logger = new Logger();
                $logger->write($e->getMessage(), __FILE__, __LINE__);
                throw new \Exception('General malfuction!!!');
            }
        } else {
            return array();
        }
    }

    /**
     * This method get just one filed from a table
     *
     * @param $fieldname                name of field to get
     * @param $conditionsfields         conditions evaluated in AND
     * @return the field content
     * @throws\Exception
     */
    public function getOneField($fieldname, $conditionsfields) {
        $filedslist = $this->organizeConditionsFields($conditionsfields);

        try {
            // building the query
            $query = 'SELECT ' . $fieldname . ' as countresult FROM ' . $this->tablename . ' ';
            if ($filedslist != '') {
                $query .= 'WHERE ' . $filedslist;
            }

            $STH = $this->DBH->prepare($query);

            foreach ($conditionsfields as $key => &$value) {
                $STH->bindParam($key, $value);
            }

            $STH->execute();

            # setting the fetch mode
            $STH->setFetchMode(PDO::FETCH_OBJ);

            while ($item = $STH->fetch()) {
                $out = $item->countresult;
            }

            if ( isset($out) ){
                return $out;
            } else {
                return '';
            }

        } catch (PDOException $e) {
            $logger = new Logger();
            $logger->write($e->getMessage(), __FILE__, __LINE__);
            throw new \Exception('General malfuction!!!');
        }
    }

    public function getEmpty() {
        return null;
    }

    public function organizeConditionsFields($conditionsfields) {
        $filedslist = '';
        foreach ($conditionsfields as $key => $value) {
            $filedslist .= $key . ' = :' . $key . ' AND ';
        }
        $filedslist = substr($filedslist, 0, -4);
        return $filedslist;
    }

    public function organizeRequestedFields($requestedfields) {
        if (is_array($requestedfields)) {
            $requestedfieldlist = '';
            foreach ($requestedfields as $value) {
                $requestedfieldlist .= $value . ', ';
            }
            $requestedfieldlist = substr($requestedfieldlist, 0, -2);
        } else {
            $requestedfieldlist = '*';
        }
        return $requestedfieldlist;
    }

    public function organizeOrderByFields($orderby) {
        if (is_array($orderby)) {
            $orderbyfields = ' ORDER BY ';
            foreach ($orderby as $value) {
                $orderbyfields .= $value . ', ';
            }
            $orderbyfields = substr($orderbyfields, 0, -2);
        } else {
            $orderbyfields = '';
        }
        return $orderbyfields;
    }

    public function putCache($query, $key, $stuff) {
        $dirname = 'cache/' . $query;
        if (!file_exists($dirname)) {
            mkdir($dirname, 0777, true);
        }
        $filename = $dirname . '/' . $key . '.data';
        file_put_contents($filename, serialize($stuff), LOCK_EX);
    }

    public function getCache($query, $key) {
        $filename = 'cache/' . $query . '/' . $key . '.data';
        $cached = NULL;
        if (file_exists($filename) AND ( time() - filemtime($filename) < 2 * 3600)) { // 2 h
            $cached = unserialize(file_get_contents($filename));
        }
        return $cached;
    }

}
