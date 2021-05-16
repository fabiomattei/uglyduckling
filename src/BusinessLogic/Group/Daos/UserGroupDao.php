<?php

namespace Fabiom\UglyDuckling\BusinessLogic\Group\Daos;

use Fabiom\UglyDuckling\Common\Database\BasicDao;
use PDO;
use stdClass;

class UserGroupDao extends BasicDao {
	
	const DB_TABLE = 'usergroup';
	const DB_TABLE_PK = 'ug_id';
    const DB_TABLE_UPDATED_FIELD_NAME = 'ug_updated';
    const DB_TABLE_CREATED_FLIED_NAME = 'ug_created';
	
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

    /**
     * In order to save the password it uses the algorithms created by the community
     * password_hash("rasmuslerdorf", PASSWORD_DEFAULT);
     * password_verify('rasmuslerdorf', $hash)
     */
    function checkUserHasAccessToGroup( int $usr_id, string $slug ) {
        try {
            $STH = $this->DBH->prepare('SELECT ug_groupslug FROM usergroup WHERE ug_userid = :usrid AND ug_groupslug = :slug ;');
            $STH->bindParam(':usrid', $usr_id, PDO::PARAM_INT);
            $STH->bindParam(':slug', $slug, PDO::PARAM_STR);
            $STH->execute();

            $STH->setFetchMode(PDO::FETCH_OBJ);
            $obj = $STH->fetch();

            // user with given email does not exist
            if ($obj == null) {
                return false;
            }

            return true;
        }
        catch(\PDOException $e) {
            $logger = new Logger();
            $logger->write($e->getMessage(), __FILE__, __LINE__);
        }
    }

	function getUsersByGroupSlug( string $slug ) {
        $query = 'SELECT UG.*, U.usr_id, U.usr_name, U.usr_surname FROM '.$this::DB_TABLE.' as UG '.
            ' LEFT JOIN user as U ON UG.ug_userid = U.usr_id '.
            ' WHERE UG.ug_groupslug = :groupslug '.
            ' ORDER BY U.usr_name, U.usr_surname  ';
        try {
            $STH = $this->DBH->prepare( $query );
            $STH->bindParam( ':groupslug', $slug );

            $STH->execute();

            # setting the fetch mode
            $STH->setFetchMode(PDO::FETCH_OBJ);

            return $STH;
        }
        catch(\PDOException $e) {
            $logger = new Logger();
            $logger->write($e->getMessage(), __FILE__, __LINE__);
        }
	}

    public function makeListForDropdownByUserId( int $usrid ) {
        $query = 'SELECT * FROM '.$this::DB_TABLE.
            ' WHERE ug_userid = :usrid'.
            ' ORDER BY ug_groupslug  ';
        try {
            $STH = $this->DBH->prepare( $query );
            $STH->bindParam( ':usrid', $usrid );
            $STH->execute();

            # setting the fetch mode
            $STH->setFetchMode(PDO::FETCH_OBJ);

            $groupsForDropDown = array();
            foreach ($STH as $gr) {
                $groupsForDropDown[$gr->ug_groupslug] = $gr->ug_groupslug;
            }

            return $groupsForDropDown;
        }
        catch(\PDOException $e) {
            $logger = new Logger();
            $logger->write($e->getMessage(), __FILE__, __LINE__);
        }
    }

}
