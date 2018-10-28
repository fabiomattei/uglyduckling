<?php

namespace Firststep\BusinessLogic\Group\Daos;

use Firststep\Common\Database\BasicDao;
use PDO;
use stdClass;

class UserGroupDao extends BasicDao {
	
	const DB_TABLE = 'usergroup';
	const DB_TABLE_PK = 'ug_id';
    const DB_TABLE_UPDATED_FIELD_NAME = 'usr_updated';
    const DB_TABLE_CREATED_FLIED_NAME = 'usr_created';
	
	/*
	  Fields list
	  ug_id                     Primary Key
	  ug_groupslug              slug that belongs to a group set in a json file, it is like a FK
	  ug_userid                 FK user :: usr_id
      ug_updated
      ug_created
	*/
	
	/**
	 * it overloads the getEmpty method of the parent class
	 */
	public function getEmpty() {
		$empty = new stdClass;
		$empty->ug_id        = 0;
		$empty->ug_groupslug = '';
		$empty->ug_userid    = 0;
		$empty->ug_updated   = date( 'Y-m-d' );
		$empty->ug_created   = date( 'Y-m-d' );
		return $empty;
	}

	function getUsersByGroupSlug( string $slug ) {
        $query = 'SELECT UG.*, U.usr_id, U.usr_name, U.usr_surname FROM '.$this::DB_TABLE.' as UG '.
            ' LEFT JOIN user as U ON UG.ug_userid = U.usr_id '.
            ' WHERE UG.ug_groupslug = :groupslug '.
            ' ORDER BY U.usr_name, U.usr_surname  ';  // trick to have the offices at the top of the list
        try {
            $STH = $this->DBH->prepare( $query );
            $STH->bindParam( ':groupslug', $slug, PDO::PARAM_STR );

            $STH->execute();

            # setting the fetch mode
            $STH->setFetchMode(PDO::FETCH_OBJ);

            return $STH;
        }
        catch(PDOException $e) {
            $logger = new Logger();
            $logger->write($e->getMessage(), __FILE__, __LINE__);
        }
	}

}
